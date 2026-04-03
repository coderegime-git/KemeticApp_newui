<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\User;

class WebAutoLoginController extends Controller
{
    /**
     * Consume the one-time token, authenticate the user, and redirect
     */
    public function loginWithToken(Request $request)
    {
        $token = $request->get('token');
        $redirectUrl = $request->get('redirect', '/panel/cj-products');

        if (!$token) {
            abort(401, 'Unauthorized: Missing token.');
        }

        $cacheKey = 'mobile_login_token_' . $token;
        $userId = Cache::get($cacheKey);

        if (!$userId) {
            abort(401, 'Unauthorized: Token is invalid or has expired.');
        }

        // Token found, log the user in immediately
        Auth::loginUsingId($userId);

        // Destroy the token so it cannot be used again
        Cache::forget($cacheKey);

        // Finally, redirect to the desired area in the panel
        return redirect($redirectUrl);
    }
}
