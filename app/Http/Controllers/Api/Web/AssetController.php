<?php
namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
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

            return response()->json([
                'success' => true,
                'data' => $assets
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