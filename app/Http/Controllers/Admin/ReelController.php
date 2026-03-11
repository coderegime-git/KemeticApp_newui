<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use App\Models\ReelCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReelController extends Controller
{
    public function index(Request $request)
    {
        //$this->authorize('admin_reels_list');

        removeContentLocale();

        $query = Reel::with(['category', 'user']);

        // Apply filters
        if ($request->get('title')) {
            $query->where('title', 'like', '%' . $request->get('title') . '%');
        }

        if ($request->get('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // if ($request->get('status')) {
        //     $query->where('status', $request->get('status'));
        // }

        // if ($request->get('from_date') && $request->get('to_date')) {
        //     $query->whereBetween('created_at', [$request->get('from_date'), $request->get('to_date')]);
        // }

        $reels = $query->with(['category'])->latest()->paginate(10);
        
        $categories = ReelCategory::all()->map(function($category) {
            return $category->details;
        });

        // dd($reels);

        $data = [
            'pageTitle' => 'Manage Portals',
            'reels' => $reels,
            'categories' => $categories
        ];

        return view('admin.reel.index', $data);
    }

    public function create()
    {
        //$this->authorize('admin_reels_create');

        $categories = ReelCategory::all()->map(function($category) {
            return $category->details;
        });

        $data = [
            'pageTitle' => 'Create New Portals',
            'categories' => $categories
        ];

        return view('admin.reel.create', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('admin_reels_create');

        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg,webm|max:100000',
            'category_id' => 'required|integer|exists:reel_categories,id',
            'title' => 'required|string|max:255',
            'caption' => 'required|string|max:1000',
            // 'status' => 'required|in:pending,published,rejected'
        ]);

        // Handle video upload
        $filename = null;
        if ($request->file('video')) {
            $video = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();

            // Create directories if they don't exist
            $videoPath = public_path('store/reels/videos');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }

            $video->move($videoPath, $filename);
        }

        // Handle thumbnail if provided
        // $thumbnailName = null;
        // if ($request->file('thumbnail')) {
        //     $thumbnail = $request->file('thumbnail');
        //     $thumbnailName = time() . '_thumb_' . uniqid() . '.' . $thumbnail->getClientOriginalExtension();
            
        //     $thumbnailPath = public_path('store/reels/thumbnails');
        //     if (!file_exists($thumbnailPath)) {
        //         mkdir($thumbnailPath, 0777, true);
        //     }
            
        //     $thumbnail->move($thumbnailPath, $thumbnailName);
        // }

        $now = time();

        Reel::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'caption' => $request->caption,
            'video_path' => $filename,
            // 'thumbnail' => $thumbnailName,
            // 'status' => $request->status,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        return redirect(getAdminPanelUrl() . '/reel')->with('success', 'Reel created successfully!');
    }

    public function edit($id)
    {
        //$this->authorize('admin_reels_edit');

        $reel = Reel::findOrFail($id);
        $categories = ReelCategory::all()->map(function($category) {
            return $category->details;
        });

        $data = [
            'pageTitle' => 'Edit Portals',
            'reel' => $reel,
            'categories' => $categories
        ];

        return view('admin.reel.edit', $data);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('admin_reels_edit');

        $reel = Reel::findOrFail($id);

        $request->validate([
            'video' => 'nullable|mimes:mp4,mov,ogg,webm|max:100000',
            'category_id' => 'required|integer|exists:reel_categories,id',
            'title' => 'required|string|max:255',
            'caption' => 'required|string|max:1000',
            // 'status' => 'required|in:pending,published,rejected'
        ]);

        $now = time();

        $filename = $reel->video_path;
        if ($request->file('video')) {
            // Delete old video
            if ($reel->video_path && file_exists(public_path('store/reels/videos/' . $reel->video_path))) {
                unlink(public_path('store/reels/videos/' . $reel->video_path));
            }

            $video = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();

            $videoPath = public_path('store/reels/videos');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }

            $video->move($videoPath, $filename);
        }

        // // Handle thumbnail update
        // $thumbnailName = $reel->thumbnail;
        // if ($request->file('thumbnail')) {
        //     // Delete old thumbnail
        //     if ($reel->thumbnail && file_exists(public_path('store/reels/thumbnails/' . $reel->thumbnail))) {
        //         unlink(public_path('store/reels/thumbnails/' . $reel->thumbnail));
        //     }

        //     $thumbnail = $request->file('thumbnail');
        //     $thumbnailName = time() . '_thumb_' . uniqid() . '.' . $thumbnail->getClientOriginalExtension();
            
        //     $thumbnailPath = public_path('store/reels/thumbnails');
        //     if (!file_exists($thumbnailPath)) {
        //         mkdir($thumbnailPath, 0777, true);
        //     }
            
        //     $thumbnail->move($thumbnailPath, $thumbnailName);
        // }

        $reel->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'caption' => $request->caption,
            'video_path' => $filename,
            'updated_at' => $now,
            // 'thumbnail' => $thumbnailName,
            // 'status' => $request->status
        ]);

        return redirect(getAdminPanelUrl() . '/reel')->with('success', 'Reel updated successfully!');
    }

    public function delete($id)
    {
        //$this->authorize('admin_reels_delete');

        $reel = Reel::findOrFail($id);

        // Delete video file
        if ($reel->video_path && file_exists(public_path('store/reels/videos/' . $reel->video_path))) {
            unlink(public_path('store/reels/videos/' . $reel->video_path));
        }

        // Delete thumbnail file
        // if ($reel->thumbnail && file_exists(public_path('store/reels/thumbnails/' . $reel->thumbnail))) {
        //     unlink(public_path('store/reels/thumbnails/' . $reel->thumbnail));
        // }

        $reel->delete();

        return redirect(getAdminPanelUrl() . '/reel')->with('success', 'Reel deleted successfully!');
    }

    public function toggleStatus($id)
    {
        //$this->authorize('admin_reels_edit');

        $reel = Reel::findOrFail($id);
        
        $statuses = ['pending', 'published', 'rejected'];
        $currentIndex = array_search($reel->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        
        $reel->update([
            'status' => $statuses[$nextIndex]
        ]);

        return redirect(getAdminPanelUrl() . '/reel')->with('success', 'Reel status updated to ' . $statuses[$nextIndex]);
    }
}