<?php

use App\Services\FirebaseService;
use App\Api\Response;
use App\Api\Request;
use App\Models\User;
use App\Models\Api\UserFirebaseSessions;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function validateParam($request_input, $rules, $somethingElseIsInvalid = null)
{
    $request = new Request();
    return $request->validateParam($request_input, $rules, $somethingElseIsInvalid);
}

function apiResponse2($success, $status, $msg, $data = null, $title = null)
{
    $response = new Response();
    return $response->apiResponse2($success, $status, $msg, $data, $title);
}


function apiAuth()
{
    if (request()->input('test_auth_id')) {
        return App\Models\Api\User::find(request()->input('test_auth_id')) ?? die('test_auth_id not found');
    }
    return auth('api')->user();


}

function nicePrice($price)
{
    $nice = handlePrice($price, false);

    if (is_string($nice)) {
        $nice = (float)$nice;
    }

    return round($nice, 2);
}

function nicePriceWithTax($price)
{

    // return round(handlePrice($price, true,false,true), 2);
    $nice = handlePrice($price, false, false, true);
    if ($nice === 0) {
        return [
            "price" => 0,
            "tax" => 0
        ];
    }
    return $nice;
}


function handleSendFirebaseMessages($user_id, $group_id, $sender, $type, $title, $message)
{
    
    try {
        $firebase = app(\App\Services\FirebaseService::class);
        // dd('handleSendFirebaseMessages', $user_id, $group_id, $sender, $type, $title, $message);
        $cleanMessage = html_entity_decode(strip_tags($message), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Trim extra whitespace
        $cleanMessage = trim(preg_replace('/\s+/', ' ', $cleanMessage));

        $userIds = array_map('intval', explode(',', $user_id));
        $tokens = User::whereIn('id', $userIds)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();
            
        // dd('handleSendFirebaseMessages', $tokens);
        if (empty($tokens)) {
            return response()->json([
                'error' => 'No FCM tokens found for selected users'
            ], 404);
        }

        Log::info('FCM Tokens found:', ['tokens' => $tokens]);

        // dd($tokens);
        // Send notification
        $response = $firebase->sendToMultipleTokens(
            $tokens,
            'KEMETIC APP',
            $cleanMessage
        );
        Log::info('Firebase Response:', ['response' => $response]);
        // dd($response);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Notification sent Successfully',
        //     'tokens_count' => count($tokens)
        // ]);
    }
    catch (\Exception $e) {
        Log::error('Notification send error:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        // return response()->json([
        //     'success' => false,
        //     'error' => 'Failed to send notification',
        //     'details' => $e->getMessage() ? $e->getMessage() : 'Internal server error'
        // ], 500);
    }

    // $fcmTokens = UserFirebaseSessions::where('user_id', $user_id)
    //     ->select('fcm_token')->get()->all();

    // $deviceTokens = [];

    // foreach ($fcmTokens as $fcmToken) {
    //     $deviceTokens[] = $fcmToken->fcm_token;
    // }

    // if (count($deviceTokens) > 0) {
    //     $messageFCM = app('firebase.messaging');

    //     foreach ($deviceTokens as $fcmToken) {
    //         $fcmMessage = CloudMessage::withTarget('token', $fcmToken);

    //         $fcmMessage = $fcmMessage->withNotification([
    //             'title' => $title,
    //             'body' => preg_replace('/<[^>]*>/', '', $message)
    //         ]);

    //         $fcmMessage = $fcmMessage->withData([
    //             'user_id' => $user_id,
    //             'group_id' => $group_id,
    //             'title' => $title,
    //             'message' => preg_replace('/<[^>]*>/', '', $message),
    //             'sender' => $sender,
    //             'type' => $type,
    //             'created_at' => time()
    //         ]);

    //         $fcmMessage = $fcmMessage->withAndroidConfig(\Kreait\Firebase\Messaging\AndroidConfig::fromArray([
    //             'ttl' => '3600s',
    //             'priority' => 'high',
    //             'notification' => [
    //                 'color' => '#f45342',
    //                 'sound' => 'default',
    //             ],
    //         ]));

    //         try {
    //             $messageFCM->send($fcmMessage);
    //         } catch (\Exception $exception) {

    //         }

    //     }

    // }
}



