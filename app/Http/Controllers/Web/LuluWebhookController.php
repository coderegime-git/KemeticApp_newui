<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BookOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LuluWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Lulu Webhook Received', $request->all());

        // Validate signature
        if (!$this->validateSignature($request)) {
            Log::warning('Lulu Webhook: Invalid Signature');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        $printJobId = $payload['id'] ?? null;
        $status = $payload['status']['name'] ?? null;

        if ($printJobId && $status) {
            $bookOrder = BookOrder::where('printjob_id', $printJobId)->first();

            if ($bookOrder) {
                $appStatus = $this->mapLuluStatusToApp($status);
                
                $updateData = ['status' => $appStatus];
                
                // Extract tracking info if status is SHIPPED
                if ($status === 'SHIPPED' && isset($payload['line_items'][0]['tracking_url'])) {
                    $updateData['tracking_code'] = $payload['line_items'][0]['tracking_url'];
                }

                $bookOrder->update($updateData);
                
                Log::info("Lulu Webhook: Updated BookOrder #{$bookOrder->id} to status {$appStatus}");
            } else {
                Log::warning("Lulu Webhook: No BookOrder found for printjob_id {$printJobId}");
            }
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    private function validateSignature(Request $request)
    {
        $signature = $request->header('Lulu-HMAC-SHA256');
        $secret = env('LULU_CLIENT_SECRET');

        if (!$signature || !$secret) {
            return false;
        }

        $computedSignature = hash_hmac('sha256', $request->getContent(), $secret);

        return hash_equals($signature, $computedSignature);
    }

    private function mapLuluStatusToApp($luluStatus)
    {
        switch ($luluStatus) {
            case 'SHIPPED':
                return BookOrder::$shipped;
            case 'DELIVERED':
                return BookOrder::$success;
            case 'CANCELED':
            case 'REJECTED':
                return BookOrder::$canceled;
            case 'IN_PRODUCTION':
            case 'PRODUCTION_READY':
                return BookOrder::$waitingDelivery;
            default:
                return BookOrder::$waitingDelivery;
        }
    }
}
