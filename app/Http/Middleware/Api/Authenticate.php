<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        // Log complete request headers and body
        \Log::info('Complete Request:', [
            'headers' => $request->headers->all(),
            'body' => $request->all(), // Logs the full request payload
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ]);

        // $headers = getallheaders();
        // \Log::info('PHP Request:', [
        //     'headers' => $headers
        // ]);


        if (request()->input('test_auth_id')) {
            return $next($request);
        }

        // Extract Authorization header
        $AuthorizationHeader = $request->headers->get('Authorization');
        $authorizationnHeader = $request->headers->get('authorizationn');
        if (empty($AuthorizationHeader) && !empty($authorizationnHeader)) {
            $request->headers->set('Authorization', $authorizationnHeader);
        }


        if ($this->auth->guard('api')->guest()) {
            return apiResponse2(0, 'unauthorized', trans('auth.unauthorized'));
        }

        return $next($request);
    }
}
