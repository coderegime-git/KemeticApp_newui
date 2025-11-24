<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReelController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Get all reported reels
     */
    public function reportedReels()
    {
        $reels = Reel::with(['user', 'reports.user'])
            ->whereHas('reports')
            ->withCount('reports')
            ->orderByDesc('reports_count')
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $reels
        ]);
    }

    /**
     * Get specific reported reel details
     */
    public function showReportedReel(Reel $reel)
    {
        return response()->json([
            'status' => 'success',
            'data' => $reel->load(['user', 'reports.user'])
        ]);
    }

    /**
     * Restore a hidden reel
     */
    public function restore(Reel $reel)
    {
        $reel->update([
            'is_hidden' => false,
            'reports_count' => 0
        ]);
        
        $reel->reports()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Reel restored successfully'
        ]);
    }

    /**
     * Force hide a reel
     */
    public function hide(Reel $reel)
    {
        $reel->update(['is_hidden' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reel hidden successfully'
        ]);
    }

    /**
     * Get reports statistics
     */
    public function stats()
    {
        $stats = [
            'total_reported_reels' => Reel::whereHas('reports')->count(),
            'total_hidden_reels' => Reel::where('is_hidden', true)->count(),
            'recent_reports' => Reel::with(['user', 'reports.user'])
                ->whereHas('reports')
                ->latest()
                ->take(5)
                ->get()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}
