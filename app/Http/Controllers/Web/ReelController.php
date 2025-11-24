<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use App\Models\ReelLike;
use App\Models\ReelComment;
use App\Models\ReelReport;
use App\Jobs\ProcessReelVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // try {
        //     ReelReport::truncate(); // Assuming you have these models
        //     ReelComment::truncate(); // Truncate dependent tables first, or it won't matter
        //     ReelLike::truncate();    // Truncate dependent tables
        //     Reel::truncate();        // Truncate the main table

        //     // After truncation, re-enable checks
        //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // } catch (\Exception $e) {
        //     // Ensure foreign key checks are re-enabled even if an error occurs
        //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //     // You can log the error or re-throw it
        //     throw $e;
        // }
        $reels = Reel::with([
                'user',
                'likes' => function($query) {
                    $query->where('user_id', Auth::id());
                },
                'comments.user' // Eager load user for each comment
            ])
            ->where(function($query) {
                $query->where('reports_count', '<', 15)
                      ->orWhereNull('reports_count');
            })
            ->where('is_hidden', false)
            ->latest()
            ->paginate(10);

        $heroreels = Reel::with([
            'user',
            'likes' => function($query) {
                $query->where('user_id', Auth::id());
            },
            'comments.user'
        ])
        ->where(function($query) {
            $query->where('reports_count', '<', 15)
                  ->orWhereNull('reports_count');
        })
        ->where('is_hidden', false)
        ->where('created_at', '>=', now()->subDays(30))
        ->orderByRaw('(likes_count * 0.4) + (comments_count * 0.3) + (views_count * 0.3) DESC')
        ->first();

        return view('web.reels.index', compact('reels','heroreels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg,webm|max:100000',
            'title' => 'required|string|max:255',
            'caption' => 'required|string|max:1000',
        ]);

        $video = $request->file('video');
        $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
        
        // Create directories if they don't exist
        $videoPath = public_path('store/reels/videos');
        if (!file_exists($videoPath)) {
            mkdir($videoPath, 0777, true);
        }

        $video->move($videoPath, $filename);

        $now = time();
        $reel = Reel::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'caption' => $request->caption,
            'video_path' => $filename,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Queue video processing job
        ProcessReelVideo::dispatch($reel);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reel uploaded successfully and is being processed'
            ]);
        }

        return back()->with('success', 'Reel uploaded successfully and is being processed');
    }

    public function like(Request $request, Reel $reel)
    {
        $like = ReelLike::where('reel_id', $reel->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($like === true) {
            ReelLike::where('reel_id', $reel->id)->where('user_id', Auth::id())->delete();
            // DB::table('reel_likes')->where('reel_id', $reel->id)->where('user_id', Auth::id())->delete();
            $reel->decrement('likes_count');
            $isLiked = false;
        } else {
            ReelLike::create([
                'reel_id' => $reel->id,
                'user_id' => Auth::id()
            ]);
            // DB::statement('INSERT INTO reel_likes (reel_id, user_id) VALUES (?, ?)', [$reel->id, Auth::id()]);
            $reel->increment('likes_count');
            $isLiked = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'likes_count' => $reel->likes_count,
                'is_liked' => $isLiked
            ]);
        }
        $reel->likes_count = ReelLike::where('reel_id', $reel->id)->count();
        return response()->json([
            'success' => true,
            'likes_count' => $reel->likes_count,
            'is_liked' => $isLiked
        ]);
        // return back()->with('success', $isLiked ? 'Reel liked successfully' : 'Reel unliked successfully');
    }

    public function comment(Request $request, Reel $reel)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:500'
        ]);

            $comment = ReelComment::create([
                'reel_id' => $reel->id,
                'user_id' => Auth::id(),
                'content' => $validated['comment'],
                'created_at' => time(),
            ]);

            $reel->increment('comments_count');
            $reel->comments_count = ReelComment::where('reel_id', $reel->id)->count();
            if ($request->ajax()) {
                $userName = Auth::user()->full_name ?? Auth::user()->name ?? 'Unknown';
                return response()->json([
                    'success' => true,
                    'comments_count' => $reel->comments_count,
                    'user' => $userName,
                    'comment' => $validated['comment']
                ]);
            }

            return back()->with('success', 'Comment added successfully');
    }

    public function report(Request $request, Reel $reel)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $existing = ReelReport::where('reel_id', $reel->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$existing) {
            ReelReport::create([
                'reel_id' => $reel->id,
                'user_id' => Auth::id(),
                'reason' => $validated['reason'],
                'created_at' => time(),
            ]);

            $reel->increment('reports_count');
            $reel->checkAndUpdateHiddenStatus();

            $reel->increment('reports_count');

            // Auto-hide reel if it reaches report threshold
            if ($reel->reports_count >= 15) {
                $reel->update(['is_hidden' => true]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Report submitted successfully'
            ]);
        }

        return back()->with('success', 'Report submitted successfully');
    }
}
