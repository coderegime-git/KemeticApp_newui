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