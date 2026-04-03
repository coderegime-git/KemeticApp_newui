<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MobileAutoLoginController extends Controller
{
    /**
     * Generate a one-time token for authenticating a mobile WebView
     */
    public function generateToken(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Unauthenticated.'
                ], 401);
            }

            // Generate a cryptographically secure random token
            $token = Str::random(60);

            // Store it in the cache for 2 minutes (120 seconds), mapped to the user ID
            Cache::put('mobile_login_token_' . $token, $user->id, 600);

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => [
                    'token' => $token,
                    'expires_in_seconds' => 600,
                    'login_url' => url('/dropshipping/login?token=' . $token),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
