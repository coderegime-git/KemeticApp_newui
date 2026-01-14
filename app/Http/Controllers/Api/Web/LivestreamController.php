<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\UserObj;
use App\Http\Controllers\Controller;
use App\Models\Api\Book;
use App\Models\Api\User;
use App\Models\Livestream;
use App\Models\BookTranslation;
use App\Services\IvsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Str;

class LivestreamController extends Controller
{
    protected $ivsService;

    public function __construct(IvsService $ivsService)
    {
        $this->ivsService = $ivsService;
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

    public function index(Request $request)
    {
        $userId = $this->getUserIdFromToken($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }

        $count = Livestream::where('creator_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        if($count->count() == 0)
        {
            
            $originalName = $userId;
                
                // Clean the name: remove spaces and special characters
            $cleanName = preg_replace('/[^a-zA-Z0-9-_]/', '', $originalName);
            
            // If after cleaning it's empty, use a default
            if (empty($cleanName)) {
                $cleanName = 'channel';
            }
            
            // Add random string and ensure it's not too long (max 128 chars for AWS)
            $channelName = $cleanName . '-' . Str::random(8);
            $channelName = substr($channelName, 0, 128); 

            // Prepare options for channel creation
            $options = [
                'type' => "BASIC",
                'latencyMode' => "LOW",
                'tags' => [
                    'environment' => config('app.env'),
                    'created_by' => 'laravel-system'
                ]
            ];

            // Create channel in AWS IVS
            $result = $this->ivsService->createChannel($channelName, $options);

            

            if (!$result['success']) {
                throw new \Exception('Failed to create IVS channel: ' . ($result['error'] ?? 'Unknown error'));
            }

            
            $channelData = $result['channel'];
            $streamKeyData = $result['streamKey'];

            // Parse ingest endpoint from channel endpoint
            $ingestEndpoint = '';
            if (isset($channelData['ingestEndpoint'])) {
                $urlParts = parse_url($channelData['ingestEndpoint']);
                $ingestEndpoint = $urlParts['host'] ?? '';
            }
            
            // Parse playback URL
            $playbackUrl = '';
            if (isset($channelData['playbackUrl'])) {
                $urlParts = parse_url($channelData['playbackUrl']);
                $playbackUrl = $urlParts['host'] ?? '';
            }

            // Save to database
            $ivsChannel = Livestream::create([
                'channel_name' => $userId,
                'channel_arn' => $channelData['arn'],
                'ingest_endpoint' => $channelData['ingestEndpoint'],
                'stream_key' => $streamKeyData['value'],
                'stream_key_arn' => $streamKeyData['arn'],
                'playback_url' => $channelData['playbackUrl'],
                'channel_id' => $channelData['id'] ?? Str::random(16),
                'region' => config('ivs.region'),
                'type' => "BASIC",
                'latency_mode' => "LOW",
                'recording_configuration_arn' => null,
                'creator_id' => auth()->id(),
                'tags' => $options['tags'],
                'is_active' => true,
                'created_at' => time(),
                'updated_at' => time(),
            ]);

            DB::commit();
        }

        // Get user's livestreams
        $livestreams = Livestream::where('creator_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $livestreams
        ]);
    }

    public function delete(Request $request, $id)
    {
        $userId = $this->getUserIdFromToken($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }

        $livestream = Livestream::where('id', $id)
            ->where('creator_id', $userId)
            ->first();

        if (!$livestream) {
            return response()->json([
                'success' => false,
                'message' => 'Livestream channel not found or you do not have permission to delete it.'
            ], 404);
        }

        try {
            // In production, you would delete from AWS IVS here:
           try {
                $this->ivsService->deleteChannel($livestream->channel_arn);
            } catch (\Exception $awsError) {
                // Just log the error and continue
                Log::warning('AWS deletion failed (channel might be already deleted): ' . $awsError->getMessage());
            }
            
            // Delete from database
            $livestream->delete();

            return response()->json([
                'success' => true,
                'message' => 'Livestream channel deleted successfully.',
                'data' => [
                    'id' => $id,
                    'deleted' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete livestream channel.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}