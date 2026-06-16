<?php
namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\LivestreamSetting;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        try {
            $assets = Asset::select('id', 'title', 'path', 'type', 'user_id')
                ->get()
                ->groupBy('type')
            ->map(function ($typeAssets) {
                return $typeAssets->map(function ($asset) {
                    return [
                        'id' => $asset->id,
                        'title' => $asset->title,
                        'url' => $asset->file_url,
                        'user_id' => $asset->user_id
                    ];
                })->values();
            });

            $livestreamSetting = LivestreamSetting::select('app_id', 'app_sign')->first();
            
            $data = $assets->toArray();
            $data['livestream'] = $livestreamSetting ? [
                'app_id'   => $livestreamSetting->app_id,
                'app_sign' => $livestreamSetting->app_sign,
            ] : [];
            
            $data['Stripe'] = [
                'stripe_key'   => env('STRIPE_KEY_DEV'), // only on server
                'stripe_secret' => env('STRIPE_SECRET_DEV'), // only on server
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assets',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $asset = Asset::find($id);

            if (!$asset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset not found'
                ], 404);
            }

            $data = [
                'id' => $asset->id,
                'title' => $asset->title,
                'path' => $asset->path,
                'url' => $asset->file_url, // Using the accessor from model
                'user_id' => $asset->user_id
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}