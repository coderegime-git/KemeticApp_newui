<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\AdReel;
use App\Models\Product;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdReelController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();
        $query = AdReel::query();

        // If not admin, show only user's reels
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Search filter
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
                //   ->orWhere('caption', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('expires_at', '>', now());
            } elseif ($status === 'expired') {
                $query->where('expires_at', '<=', now());
            }
        }

        // Plan filter
        if ($request->has('plan')) {
            $query->where('plan_code', $request->get('plan'));
        }

        $reels = $query->with(['user', 'product'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        $plans = Plan::all();
        
        $data = [
            'pageTitle' => "Find My Deal",
            'reels' => $reels,
            'plans' => $plans,
        ];

        return view(getTemplate() . '.panel.adreel.index', $data);
    }

    public function create()
    {
        $user = auth()->user();
        $products = Product::where('status', 'active')
                        //   ->where('creator_id', $user->id)
                          ->get();
        
        $plans = Plan::all();
        $data = [
            'pageTitle' => "Find My Deal",
            'products' => $products,
            'plans' => $plans,
        ];

        return view('web.default.panel.adreel.create', $data);
    }
    
    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'caption' => 'nullable|string',
        //     'video' => 'required|mimes:mp4,mov,avi,wmv|max:250000', // 250MB
        //     'product_id' => 'nullable|exists:products,id',
        //     'plan_code' => 'nullable|exists:plans,code',
        //     'starts_at' => 'nullable|date',
        //     'expires_at' => 'nullable|date|after:starts_at',
        // ]);

        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg,webm|max:256000',
            'title' => 'required|string|max:255',
        ]);

        try {
            // Handle video upload
            $video = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            
            $videoPath = public_path('store/reels/videos');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }
            
            $video->move($videoPath, $filename);

            // Calculate dates
            $startsAt = $request->starts_at ? strtotime($request->starts_at) : time();
            $expiresAt = $request->expires_at ? strtotime($request->expires_at) : ($startsAt + 86400);

            $reel = AdReel::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                // 'caption' => $request->caption,
                'media_image' => $filename,
                'product_id' => $request->product_id,
                'plan_code' => $request->plan_code,
                'stars' => 0,
                'reviews' => 0,
                'trending_score' => 0,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
            ]);

            return redirect()->route('panel.adreel.index')
                           ->with('success', 'Reel created successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error creating reel: ' . $e->getMessage());
        }
    }
    
    public function edit($id)
    {
        $user = auth()->user();
        $reel = AdReel::findOrFail($id);

        // Check authorization
        if (!$user->isAdmin() && $reel->user_id != $user->id) {
            abort(403);
        }

        $products = Product::where('status', 'active')
                        //   ->where('creator_id', $user->id)
                          ->get();
        
        $plans = Plan::all();

        $data = [
            'pageTitle' => "Find My Deal",
            'reel' => $reel,
            'products' => $products,
            'plans' => $plans,
        ];

        return view('web.default.panel.adreel.create', $data);
    }
    
    public function update(Request $request, $id)
    {
        $reel = AdReel::findOrFail($id);

        // Check authorization
        $user = auth()->user();
        if (!$user->isAdmin() && $reel->user_id != $user->id) {
            abort(403);
        }

        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:250000',
        //     'product_id' => 'nullable|exists:products,id',
        //     'plan_code' => 'nullable|exists:plans,code',
        //     'starts_at' => 'nullable|date',
        //     'expires_at' => 'nullable|date|after:starts_at',
        // ]);

        try {
            $data = [
                'title' => $request->title,
                // 'caption' => $request->caption,
                'product_id' => $request->product_id,
                'plan_code' => $request->plan_code,
            ];

            // Handle video update
            if ($request->hasFile('video')) {
                // Delete old video
                $oldVideo = public_path('store/reels/videos/' . $reel->media_image);
                if (file_exists($oldVideo)) {
                    unlink($oldVideo);
                }

                // Upload new video
                $video = $request->file('video');
                $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                
                $videoPath = public_path('store/reels/videos');
                $video->move($videoPath, $filename);
                
                $data['media_image'] = $filename;
            }

            // Update dates
            if ($request->starts_at) {
                $data['starts_at'] = strtotime($request->starts_at);
            }
            
            if ($request->expires_at) {
                $data['expires_at'] = strtotime($request->expires_at);
            }

            $reel->update($data);

            return redirect()->route('panel.adreel.index')
                           ->with('success', 'Reel updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating reel: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        $reel = AdReel::findOrFail($id);

        // Check authorization
        $user = auth()->user();
        if (!$user->isAdmin() && $reel->user_id != $user->id) {
            abort(403);
        }

        try {
            // Delete video file
            $videoPath = public_path('store/reels/videos/' . $reel->media_image);
            if (file_exists($videoPath)) {
                unlink($videoPath);
            }

            // Delete purchases
            $reel->purchases()->delete();

            // Delete reel
            $reel->delete();

            return redirect()->route('panel.adreel.index')
                           ->with('success', 'Reel deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting reel: ' . $e->getMessage());
        }
    }
    
    public function updateTrendingScore($id)
    {
        $reel = AdReel::findOrFail($id);

        // Calculate trending score based on views, likes, shares, etc.
        $score = $reel->stars * 10 + $reel->reviews * 5;
        $reel->trending_score = $score;
        $reel->save();

        return response()->json(['success' => true, 'trending_score' => $score]);
    }
}