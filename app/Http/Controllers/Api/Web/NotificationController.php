<?php
namespace App\Http\Controllers\Api\Web;

use App\Services\FirebaseService;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    // Send to one user
    public function sendNotification(Request $request)
    {
        try {
            $userIds = $request->user_ids;
            $msg = $request->msg;

            if (!is_array($userIds)) {
                $userIds = [$userIds];
            }
            
            $tokens = User::whereIn('id', $userIds)
                ->whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (empty($tokens)) {
                return response()->json([
                    'error' => 'No FCM tokens found for selected users'
                ], 404);
            }

            Log::info('FCM Tokens found:', ['tokens' => $tokens]);

            // dd($tokens);
            // Send notification
            $response = $this->firebase->sendToMultipleTokens(
                $tokens,
                'KEMETIC APP',
                $msg
            );
            Log::info('Firebase Response:', ['response' => $response]);
            // dd($response);

            return response()->json([
                'success' => true,
                'message' => 'Notification sent Successfully',
                'tokens_count' => count($tokens)
            ]);
        }
        catch (\Exception $e) {
            Log::error('Notification send error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to send notification',
                'details' => $e->getMessage() ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // Send to all users
    public function sendToAll()
    {
        $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

        $this->firebase->sendToMultipleTokens(
            $tokens,
            'Kemetic Alert',
            'This message is sent to all users'
        );

        return response()->json(['success' => true]);
    }

    private function getUserIdFromToken(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return null;
        }
        
        $token = substr($authorizationHeader, 7);
        
        if (empty($token)) {
            return null;
        }
        
        try {
            $user = auth('api')->setToken($token)->user();
            return $user ? $user->id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function saveFcmToken(Request $request)
    {
        $userId = $this->getUserIdFromToken($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }
        
        $time = time();
        $fcmToken = $request->fcm_token;
        User::where('id', $userId)->update(['fcm_token' => $fcmToken, 'updated_at' => $time]);

        return response()->json([
            'success' => true,
            'data' => "FCM token saved successfully"
        ]);
    }
}
