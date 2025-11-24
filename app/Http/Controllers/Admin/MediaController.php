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

class MediaController extends Controller
{
    public function index(Request $request)
    {
       $categories = CategoryTranslation::where('locale','en')->get();
        $mediaKit = MediaKit::all();
        return view('admin.media.lists', compact('categories', 'mediaKit'));
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

        $media = MediaKit::findOrFail($id);

        $media->update([
            'status' => ($media->status == 'active') ? 'pending' : 'active',
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Media Kit status changed successful',
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

        $media = MediaKit::findOrFail($id);

        $media->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Media Kit deleted successful',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }

}