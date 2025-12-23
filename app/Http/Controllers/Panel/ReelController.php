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

    public function edit($id)
    {
        $reel = Reel::find($id);
        return view('web.default.panel.reels.edit', compact('reel'));
    }

    public function update(Request $request){
        $request->validate([
            'video' => 'nullable|mimes:mp4,mov,ogg,webm|max:100000',
            'title' => 'required|string|max:255',
            'caption' => 'required|string|max:1000',
        ]);
        $reel = Reel::find($request->id);
        if(!$reel){
            return redirect()->back()->with('danger','Reel not found!');
        }

        $filename = $reel->video_path;
        if($request->file('video')){
            $video = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();

            // Create directories if they don't exist
            $videoPath = public_path('store/reels/videos');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }

            $video->move($videoPath, $filename);
        }

        $reel->update([
            'title' => $request->title,
            'caption' => $request->caption,
            'video_path' => $filename
        ]);
        return redirect()->route('reels.index')->with('success', 'Reel updated!');
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