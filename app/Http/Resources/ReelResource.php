<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReelResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title ?? '',
            'caption' => $this->caption ?? '',
            'video_path' => $this->video_path ?? '',
            'thumbnail_path' => $this->thumbnail_path ?? '',
            'processed_video_path' => $this->processed_video_path ?? '',
            'duration' => $this->duration ?? 0,
            'views_count' => $this->views_count ?? 0,
            'likes_count' => $this->likes_count ?? 0,
            'comments_count' => $this->comments_count ?? 0,
            'reports_count' => $this->reports_count ?? 0,
            'is_processed' => $this->is_processed ?? false,
            'is_hidden' => $this->is_hidden ?? false,
            'created_at' => $this->created_at ?? 0,
            'updated_at' => $this->updated_at ?? 0,
            'deleted_at' => $this->deleted_at ?? '',
            'video_url' => $this->video_url ?? '',
            'thumbnail_url' => $this->thumbnail_url ?? '',
            'processed_video_url' => $this->processed_video_url ?? '',
            'user' => $this->when($this->user, function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name ?? '',
                    'avatar' => $this->user->avatar ?? '',
                ];
            }, []),
        ];
    }

    public static function collection($resource)
    {
        return tap(new ReelResourceCollection($resource), function ($collection) {
            if (property_exists($collection->resource, 'resource') && $collection->resource->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $collection->paginationInformation = (new PaginatedResourceResponse($collection))->paginationInformation($collection->resource->toArray());
            }
        });
    }
}
