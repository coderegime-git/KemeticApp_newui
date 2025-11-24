<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\WebinarObj;
use App\Models\Api\FeatureWebinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FeatureWebinarController
{
    public function index(Request $request)
    {
        // More efficient cache key generation
        $cacheKey = 'featured_webinars_' . md5(
            $request->get('page', '') . 
            $request->get('status', '') . 
            $request->get('category_id', '') . 
            $request->get('webinar_title', '')
        );
        
        $webinars = Cache::remember($cacheKey, 300, function() use ($request) {
            $query = FeatureWebinar::whereIn('page', ['home', 'home_categories'])
                ->where('status', 'publish')
                ->with(['webinar' => function($query) {
                    $query->with('category');
                }]);
            
            // Apply filters using the same method name as before
            $query = $this->handleFilters($query, $request);
                
            return $query->get()
                ->map(function ($item) {
                    return $item->webinar->brief;
                });
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $webinars);
    }

    private function handleFilters($query, $request)
    {
        $page = $request->get('page', null);
        $status = $request->get('status', null);
        $category_id = $request->get('category_id', null);
        $webinar_title = $request->get('webinar_title', null);

        if (!empty($page)) {
            $query->where('page', $page);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($category_id)) {
            $query->whereHas('webinar', function ($q) use ($category_id) {
                $q->whereHas('category', function ($q) use ($category_id) {
                    $q->where('id', $category_id);
                });
            });
        }

        if (!empty($webinar_title)) {
            $query->whereHas('webinar', function ($q) use ($webinar_title) {
                $q->whereTranslationLike('title', '%' . $webinar_title . '%');
            });
        }

        return $query;
    }
}