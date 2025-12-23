@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">

<style>
    :root {
        --k-bg: #0b0b0b;
        --k-card: #141414;
        --k-gold: #f2c94c;
        --k-gold-soft: #e6b93d;
        --k-border: rgba(242,201,76,0.25);
        --k-text: #eaeaea;
        --k-muted: #9a9a9a;
        --k-radius: 16px;
    }

    body {
        background: var(--k-bg);
        color: var(--k-text);
        font-family: 'Nunito', sans-serif;
    }

    .activities-container {
        background: var(--k-card);
        padding: 25px;
        border-radius: var(--k-radius);
        box-shadow: 0 4px 20px rgba(242,201,76,0.15);
    }

    .webinar-card {
        background: var(--k-card);
        border-radius: var(--k-radius);
        box-shadow: 0 4px 20px rgba(242,201,76,0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .webinar-card-body {
        padding: 20px;
    }

    .btn-primary {
        background-color: var(--k-gold);
        border-color: var(--k-gold);
        color: #000;
        border-radius: var(--k-radius);
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-primary:hover {
        background-color: var(--k-gold-soft);
        border-color: var(--k-gold-soft);
        color: #000;
    }

    .btn-danger {
        background-color: #c53030;
        border-color: #c53030;
        border-radius: var(--k-radius);
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-danger:hover {
        background-color: #a82828;
        border-color: #a82828;
    }

    .badge-primary {
        background: var(--k-gold);
        color: #000;
        font-weight: 500;
        border-radius: var(--k-radius);
    }

    .badge-warning {
        background: #f2994a;
        color: #000;
        border-radius: var(--k-radius);
    }

    .badge-danger {
        background: #eb5757;
        color: #000;
        border-radius: var(--k-radius);
    }

    .badge-secondary {
        background: #6b6b6b;
        color: #fff;
        border-radius: var(--k-radius);
    }

    .stat-title {
        font-size: 12px;
        color: var(--k-muted);
    }

    .stat-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--k-text);
    }

    .webinar-actions {
        color: var(--k-gold);
        font-weight: 500;
    }

    .webinar-actions:hover {
        color: var(--k-gold-soft);
    }

    .form-control {
        background: #1c1c1c;
        border: 1px solid var(--k-border);
        color: var(--k-text);
        border-radius: var(--k-radius);
    }

    .form-control:focus {
        background: #1c1c1c;
        border-color: var(--k-gold);
        color: var(--k-text);
        box-shadow: 0 0 0 0.2rem rgba(242,201,76,0.25);
    }

    /* ================= KEMETIC ACTIVITY ================= */
.kemetic-section {
    margin-top: 25px;
}

.kemetic-title {
    color: #FACC15;
    font-weight: 700;
}

.kemetic-activity-wrapper {
    background: linear-gradient(180deg, #0b1120, #020617);
    border: 1px solid #1f2937;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 20px 45px rgba(0,0,0,.45);
}

.kemetic-activity-card {
    background: radial-gradient(circle at top, #111827, #020617);
    border: 1px solid #1f2937;
    border-radius: 16px;
    padding: 24px 15px;
    text-align: center;
    height: 100%;
    transition: all .3s ease;
}

.kemetic-activity-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 45px rgba(250, 204, 21, .15);
    border-color: #FACC15;
}

.kemetic-activity-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 10px;
    border-radius: 50%;
    background: rgba(250, 204, 21, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.kemetic-activity-icon img {
    width: 34px;
    height: 34px;
}

.kemetic-activity-count {
    display: block;
    font-size: 28px;
    font-weight: 800;
    color: #FACC15;
    margin-top: 8px;
}

.kemetic-activity-label {
    font-size: 14px;
    color: #9ca3af;
    margin-top: 4px;
    display: block;
}

/* ================= KEMETIC BUNDLES ================= */
.kemetic-section {
    padding-bottom: 10px;
}

.kemetic-title {
    color: #FACC15;
    font-weight: 800;
}

.kemetic-bundle-card {
    background: linear-gradient(180deg, #0b1120, #020617);
    border-radius: 18px;
    border: 1px solid #1f2937;
    overflow: hidden;
    transition: all .3s ease;
}

.kemetic-bundle-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 25px 50px rgba(250,204,21,.12);
    border-color: #FACC15;
}

.kemetic-bundle-image {
    position: relative;
    height: 100%;
}

.kemetic-bundle-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.kemetic-status-badge {
    position: absolute;
    top: 12px;
    left: 12px;
}

.kemetic-bundle-body {
    padding: 20px 25px;
}

.kemetic-bundle-title {
    font-size: 18px;
    font-weight: 700;
    color: #F9FAFB;
}

.kemetic-action-btn {
    background: transparent;
    border: none;
    color: #9ca3af;
}

.kemetic-dropdown {
    background: #020617;
    border: 1px solid #1f2937;
}

.kemetic-dropdown .dropdown-item {
    color: #e5e7eb;
}

.kemetic-dropdown .dropdown-item:hover {
    background: rgba(250,204,21,.12);
    color: #FACC15;
}

.kemetic-price .price-new {
    font-size: 20px;
    font-weight: 800;
    color: #FACC15;
}

.kemetic-price .price-old {
    font-size: 14px;
    color: #6b7280;
    text-decoration: line-through;
    margin-left: 8px;
}

.price-free {
    color: #22c55e;
    font-weight: 700;
}

.kemetic-bundle-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 15px;
}

.kemetic-bundle-stats span {
    font-size: 12px;
    color: #9ca3af;
}

.kemetic-bundle-stats strong {
    font-size: 14px;
    color: #F9FAFB;
}

</style>
@endpush

@section('content')
<section class="kemetic-section">
    <h2 class="section-title kemetic-title">
        {{ trans('panel.my_activity') }}
    </h2>

    <div class="kemetic-activity-wrapper mt-25">
        <div class="row">

            <!-- Bundles -->
            <div class="col-6 col-md-3 mt-20 mt-md-0">
                <div class="kemetic-activity-card">
                    <div class="kemetic-activity-icon">
                        <img src="/assets/default/img/activity/webinars.svg" alt="">
                    </div>

                    <strong class="kemetic-activity-count">
                        {{ !empty($bundles) ? $bundlesCount : 0 }}
                    </strong>

                    <span class="kemetic-activity-label">
                        {{ trans('update.bundles') }}
                    </span>
                </div>
            </div>

            <!-- Hours -->
            <div class="col-6 col-md-3 mt-20 mt-md-0">
                <div class="kemetic-activity-card">
                    <div class="kemetic-activity-icon">
                        <img src="/assets/default/img/activity/hours.svg" alt="">
                    </div>

                    <strong class="kemetic-activity-count">
                        {{ convertMinutesToHourAndMinute($bundlesHours) }}
                    </strong>

                    <span class="kemetic-activity-label">
                        {{ trans('home.hours') }}
                    </span>
                </div>
            </div>

            <!-- Sales Amount -->
            <div class="col-6 col-md-3 mt-20 mt-md-0">
                <div class="kemetic-activity-card">
                    <div class="kemetic-activity-icon">
                        <img src="/assets/default/img/activity/sales.svg" alt="">
                    </div>

                    <strong class="kemetic-activity-count">
                        {{ handlePrice($bundleSalesAmount) }}
                    </strong>

                    <span class="kemetic-activity-label">
                        {{ trans('update.bundle_sales') }}
                    </span>
                </div>
            </div>

            <!-- Sales Count -->
            <div class="col-6 col-md-3 mt-20 mt-md-0">
                <div class="kemetic-activity-card">
                    <div class="kemetic-activity-icon">
                        <img src="/assets/default/img/activity/download-sales.svg" alt="">
                    </div>

                    <strong class="kemetic-activity-count">
                        {{ $bundleSalesCount }}
                    </strong>

                    <span class="kemetic-activity-label">
                        {{ trans('update.bundle_sales_count') }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>


<section class="kemetic-section mt-25">

    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row mb-20">
        <h2 class="section-title kemetic-title">
            {{ trans('update.my_bundles') }}
        </h2>
    </div>

    @if(!empty($bundles) and !$bundles->isEmpty())
        @foreach($bundles as $bundle)

            <div class="kemetic-bundle-card mt-25">
                <div class="row no-gutters">

                    <!-- Image -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="kemetic-bundle-image">
                            <img src="{{ $bundle->getImage() }}" class="img-cover" alt="">

                            <div class="kemetic-status-badge">
                                @switch($bundle->status)
                                    @case(\App\Models\Bundle::$active)
                                        <span class="badge badge-success">{{ trans('panel.active') }}</span>
                                        @break
                                    @case(\App\Models\Bundle::$isDraft)
                                        <span class="badge badge-secondary">{{ trans('public.draft') }}</span>
                                        @break
                                    @case(\App\Models\Bundle::$pending)
                                        <span class="badge badge-warning">{{ trans('public.waiting') }}</span>
                                        @break
                                    @case(\App\Models\Bundle::$inactive)
                                        <span class="badge badge-danger">{{ trans('public.rejected') }}</span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="col-12 col-md-8 col-lg-9">
                        <div class="kemetic-bundle-body">

                            <div class="d-flex justify-content-between align-items-start">
                                <a href="{{ $bundle->getUrl() }}" target="_blank">
                                    <h3 class="kemetic-bundle-title">
                                        {{ $bundle->title }}
                                    </h3>
                                </a>

                                @if($authUser->id == $bundle->creator_id or $authUser->id == $bundle->teacher_id)
                                    <div class="dropdown">
                                        <button class="btn btn-sm kemetic-action-btn dropdown-toggle" data-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right kemetic-dropdown">
                                            @can('panel_bundles_create')
                                                <a href="/panel/bundles/{{ $bundle->id }}/edit" class="dropdown-item">
                                                    {{ trans('public.edit') }}
                                                </a>
                                            @endcan

                                            @can('panel_bundles_courses')
                                                <a href="/panel/bundles/{{ $bundle->id }}/courses" class="dropdown-item">
                                                    {{ trans('product.courses') }}
                                                </a>
                                            @endcan

                                            @can('panel_bundles_export_students_list')
                                                <a href="/panel/bundles/{{ $bundle->id }}/export-students-list" class="dropdown-item">
                                                    {{ trans('public.export_list') }}
                                                </a>
                                            @endcan

                                            @if($bundle->creator_id == $authUser->id)
                                                @can('panel_bundles_delete')
                                                    @include('web.default.panel.includes.content_delete_btn', [
                                                        'deleteContentUrl' => "/panel/bundles/{$bundle->id}/delete",
                                                        'deleteContentClassName' => 'dropdown-item text-danger',
                                                        'deleteContentItem' => $bundle,
                                                    ])
                                                @endcan
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @include(getTemplate() . '.includes.webinar.rate',['rate' => $bundle->getRate()])

                            <!-- Price -->
                            <div class="kemetic-price mt-10">
                                @if($bundle->price > 0)
                                    @if($bundle->bestTicket() < $bundle->price)
                                        <span class="price-new">
                                            {{ handlePrice($bundle->bestTicket(), true, true, false, null, true) }}
                                        </span>
                                        <span class="price-old">
                                            {{ handlePrice($bundle->price, true, true, false, null, true) }}
                                        </span>
                                    @else
                                        <span class="price-new">
                                            {{ handlePrice($bundle->price, true, true, false, null, true) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="price-free">{{ trans('public.free') }}</span>
                                @endif
                            </div>

                            <!-- Stats -->
                            <div class="kemetic-bundle-stats mt-20">
                                <div>
                                    <span>{{ trans('public.item_id') }}</span>
                                    <strong>#{{ $bundle->id }}</strong>
                                </div>

                                <div>
                                    <span>{{ trans('public.category') }}</span>
                                    <strong>{{ optional($bundle->category)->title }}</strong>
                                </div>

                                <div>
                                    <span>{{ trans('public.duration') }}</span>
                                    <strong>{{ convertMinutesToHourAndMinute($bundle->getBundleDuration()) }} Hrs</strong>
                                </div>

                                <div>
                                    <span>{{ trans('product.courses') }}</span>
                                    <strong>{{ $bundle->bundleWebinars->count() }}</strong>
                                </div>

                                <div>
                                    <span>{{ trans('panel.sales') }}</span>
                                    <strong>
                                        {{ count($bundle->sales) }}
                                        ({{ (!empty($bundle->sales) and count($bundle->sales)) ? handlePrice($bundle->sales->sum('amount')) : 0 }})
                                    </strong>
                                </div>

                                @if($authUser->id == $bundle->teacher_id and $authUser->id != $bundle->creator_id and $bundle->creator->isOrganization())
                                    <div>
                                        <span>{{ trans('webinars.organization_name') }}</span>
                                        <strong>{{ $bundle->creator->full_name }}</strong>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        @endforeach

        <div class="my-30">
            {{ $bundles->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>

    @else
        @include(getTemplate() . '.includes.no-result',[
            'file_name' => 'webinar.png',
            'title' => trans('update.you_not_have_any_bundle'),
            'hint' =>  trans('update.no_result_bundle_hint'),
            'btn' => ['url' => '/panel/bundles/new','text' => trans('update.create_a_bundle')]
        ])
    @endif

</section>

@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
@endpush



   