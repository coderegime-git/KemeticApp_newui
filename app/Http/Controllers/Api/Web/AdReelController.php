<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\AdReel;
use App\Models\Plan;
use App\Models\FmdPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class AdReelController extends Controller
{
    public function adreels(Request $request)
    {
        $tab = $request->get('tab'); // trending, newest, foryou, sponsored
        $plan = $request->plan_code;
        $query = AdReel::with('user');

        if ($tab === 'sponsored') {
            $query->where('plan_code', $plan)
                  ->orderBy('created_at', 'desc');
        } else {
            
            if ($tab === 'trending') {
                $query->orderBy('trending_score', 'desc');
            }

            if ($tab === 'newest') {
                $query->orderBy('created_at', 'desc');
            }

            if ($tab === 'foryou') {
                $query->orderBy('stars', 'desc')
                ->orderBy('trending_score', 'desc');
            }
        }

        return response()->json([
            'status' => true,
            'data' => $query->get()->map(fn($r) => [
                'id' => $r->id,
                'user' => $r->user->full_name ?? '',
                'mediaImage' => url('/store/reels/videos/' . $r->media_image),
                'title' => $r->title,
                'stars' => $r->stars,
                'reviews' => $r->reviews,
                'plan' => $r->plan_code,
                'createdAt' => $r->created_at,
                'starts_at' => $r->starts_at,
                'expires_at' => $r->expires_at,
                'trendingScore' => $r->trending_score
            ])
        ]);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();
        $userid = $user->id;

        ini_set('upload_max_filesize', '250M');
        ini_set('post_max_size', '250M');
        

        $video = $request->file('video');
        $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();

        $videoPath = public_path('store/reels/videos');
        if (!file_exists($videoPath)) {
            mkdir($videoPath, 0777, true);
        }
        $video->move($videoPath, $filename);
        
        $now = time();
        $startsAt = $request->starts_at ?? $now;
        $expiresAt = $request->expires_at ?? ($now + 86400); // Default: 1 day
        
        $reel = AdReel::create([
            'user_id' => $userid,
            'title' => $request->title,
            'media_image' => $filename,
            'stars' => 0,
            'reviews' => 0,
            'product_id' => $request->product_id,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'uploaded successfully',
            'data' => $reel
        ], 201);
    }

    public function plans()
    {
        return response()->json([
            'status' => true,
            'plans' => Plan::all()
        ]);
    }

    public function purchase(Request $request)
    {
        $user = auth('api')->user();
        $userid = $user->id;
        
        $request->validate([
            'plan_code' => 'required'
        ]);

        $plan = Plan::where('code', $request->plan_code)->firstOrFail();

        $currentTimestamp = time();

        $purchase = FmdPurchase::create([
            'user_id' => $userid,
            'reel_id' => $request->reel_id,
            'plan_code' => $plan->code,
            'amount' => $plan->price,
            'starts_at' => $currentTimestamp,
            'expires_at' => $plan->duration_days
                ? $currentTimestamp + ($plan->duration_days * 86400) // days to seconds
                : null,
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp
        ]);

        // Update reel
        AdReel::where('id', $request->reel_id)
            ->update([
                'plan_code' => $plan->code,
                'created_at' => $currentTimestamp,
                'expires_at' =>  $plan->duration_days
                ? $currentTimestamp + ($plan->duration_days * 86400) // days to seconds
                : null,
            ]);

        return response()->json([
            'status' => true,
            'plan' => $plan->code,
            'visibleInSponsoredOnly' => $plan->code === 'sponsored_only',
            'visibleInDiscoveryTabs' => $plan->is_membership
        ]);
    }
}

