<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ReelController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $reels = Reel::whereUserId($user->id)->latest()->paginate(10);
        // $reels = Reel::paginate(10);
        // dd($reels);
        return view('web.default.panel.reels.index', compact('reels'));
    }
    public function destroy($id)
    {
        $reel = Reel::find($id);
        if (!$reel) {
            return redirect()->back()->with('danger', 'Reel not found.');
        }
        // Optional: delete associated video file
        if ($reel->video_url && Storage::disk('public')->exists($reel->video_url)) {
            Storage::disk('public')->delete($reel->video_url);
        }
        $reel->delete();
        return redirect()->back()->with('success', 'Reel deleted successfully.');
    }

}