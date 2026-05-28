<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BundlesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bundles;

    public function __construct($bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->bundles;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('admin/main.id'),
            trans('admin/pages/webinars.title'),
            trans('admin/pages/webinars.teacher_name'),
            trans('admin/pages/webinars.sale_count'),
            trans('admin/pages/webinars.price'),
            trans('admin/main.created_at'),
            trans('admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($bundle): array
    {
        return [
            $bundle->id,
            $bundle->title,
            $bundle->teacher->full_name,
            $bundle->sales->count(),
            $bundle->price,
            dateTimeFormat($bundle->created_at, 'j M Y | H:i'),
            $bundle->status,
        ];
    }
}
