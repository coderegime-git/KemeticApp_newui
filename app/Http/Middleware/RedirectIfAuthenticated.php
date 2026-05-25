<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $adminPrefix = function_exists('getAdminPanelUrlPrefix') ? getAdminPanelUrlPrefix() : 'admin';
                $adminLoginPath = ltrim($adminPrefix . '/login', '/');

                // Case 1: Accessing Admin Login page while authenticated
                if ($request->is($adminLoginPath) || $request->is('*' . $adminLoginPath)) {
                    if (Auth::guard($guard)->user()->isAdmin()) {
                        return redirect(function_exists('getAdminPanelUrl') ? getAdminPanelUrl() : '/admin');
                    } else {
                        // Log out non-admin users so they can log in as admin
                        Auth::guard($guard)->logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        return $next($request);
                    }
                }

                // Case 2: Accessing Frontend Login page while authenticated
                if ($request->is('login') || $request->is('*/login')) {
                    // Log out the active session so they can log in with a different account
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return $next($request);
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
