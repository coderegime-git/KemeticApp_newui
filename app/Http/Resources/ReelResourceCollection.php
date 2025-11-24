<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReelResourceCollection extends ResourceCollection
{
    public $collects = ReelResource::class;
    
    public function toArray($request)
    {
        return [
            'current_page' => $this->resource->currentPage(),
            'reels' => $this->collection,
            'first_page_url' => $this->resource->url(1) ?: '',
            'from' => $this->resource->firstItem() ?: 0,
            'last_page' => $this->resource->lastPage() ?: 1,
            'last_page_url' => $this->resource->url($this->resource->lastPage()) ?: '',
            'links' => collect($this->resource->linkCollection()->toArray())->map(function ($link) {
                return [
                    'url' => $link['url'] ?: '',
                    'label' => $link['label'] ?: '',
                    'active' => $link['active'] ?: false,
                ];
            })->values()->all(),
            'next_page_url' => $this->resource->nextPageUrl() ?: '',
            'path' => $this->resource->path() ?: '',
            'per_page' => $this->resource->perPage(),
            'prev_page_url' => $this->resource->previousPageUrl() ?: '',
            'to' => $this->resource->lastItem() ?: 0,
            'total' => $this->resource->total() ?: 0
        ];
    }
}
