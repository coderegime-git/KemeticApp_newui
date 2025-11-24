<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceResponse;

class PaginatedResourceResponse extends ResourceResponse
{
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();

        $default = [
            'current_page' => $paginated['current_page'] ?? 1,
            'first_page_url' => $paginated['first_page_url'] ?? '',
            'from' => $paginated['from'] ?? 0,
            'last_page' => $paginated['last_page'] ?? 1,
            'last_page_url' => $paginated['last_page_url'] ?? '',
            'links' => collect($paginated['links'] ?? [])->map(function ($link) {
                return [
                    'url' => $link['url'] ?? '',
                    'label' => $link['label'] ?? '',
                    'active' => $link['active'] ?? false,
                ];
            })->toArray(),
            'next_page_url' => $paginated['next_page_url'] ?? '',
            'path' => $paginated['path'] ?? '',
            'per_page' => $paginated['per_page'] ?? 10,
            'prev_page_url' => $paginated['prev_page_url'] ?? '',
            'to' => $paginated['to'] ?? 0,
            'total' => $paginated['total'] ?? 0
        ];

        return $default;
    }
}
