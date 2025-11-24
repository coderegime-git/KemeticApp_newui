<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Webinar;
use App\Models\MediaKit;
use App\Models\MediaTool;
use App\Models\Translation\CategoryTranslation;
use App\Models\WebinarReview;
use Illuminate\Http\Request;

class MediaToolController extends Controller
{
    public function index(Request $request)
    {
        $mediaTool = MediaTool::all();
        return view('admin.media_tool.lists', compact('mediaTool'));
    }
    
    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $search = $request->get('search', null);
        $webinar_ids = $request->get('webinar_ids');
        $status = $request->get('status', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($search)) {
            $query->where('description', 'like', "%$search%");
        }

        if (!empty($webinar_ids)) {
            $query->whereIn('webinar_id', $webinar_ids);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function toggleStatus($id)
    {
        $this->authorize('admin_reviews_status_toggle');

        $media = MediaTool::findOrFail($id);

        $media->update([
            'status' => ($media->status == 'active') ? 'pending' : 'active',
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Media Tool status changed successful',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function reply(Request $request, $id)
    {
        $this->authorize('admin_reviews_reply');

        $review = WebinarReview::findOrFail($id);

        $data = [
            'pageTitle' => trans('admin/pages/comments.reply_comment'),
            'review' => $review,
        ];

        return view('admin.reviews.comment_reply', $data);
    }

    public function delete($id)
    {

        $media = MediaTool::findOrFail($id);

        $media->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Media Tool deleted successful',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }
    
    public function createMediaTool(Request $request){
        $request->validate([
            'tool_name'    => 'required|string|max:255',
            'tool_link'       => 'required|string',
            'tool_icon' => 'required|string',
        ]);

        // Save data to database
        MediaTool::create([
            'name' => $request->tool_name,
            'link'       => $request->tool_link,
            'icon' => $request->tool_icon,
        ]);

        return back()->with('success', 'Media tool successfully uploaded.');
    }
}