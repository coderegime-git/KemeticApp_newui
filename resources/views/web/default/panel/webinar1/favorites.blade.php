@extends('web.default.layouts.newapp')
<style>
    /* ================= KEMETIC FAVORITES ================= */

.kemetic-title {
    color: #F2C94C;
    font-size: 22px;
    font-weight: 700;
}

/* CARD */
.kemetic-favorite-card {
    display: flex;
    gap: 20px;
    background: linear-gradient(180deg,#161616,#0f0f0f);
    border: 1px solid rgba(242,201,76,.25);
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 15px 45px rgba(0,0,0,.7);
}

/* IMAGE */
.kemetic-favorite-image {
    width: 240px;
    position: relative;
    border-radius: 14px;
    overflow: hidden;
}

.kemetic-favorite-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* PROGRESS */
.kemetic-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 100%;
    background: rgba(255,255,255,.1);
}

.kemetic-progress span {
    display: block;
    height: 100%;
    background: linear-gradient(90deg,#F2C94C,#E5A100);
}

/* CONTENT */
.kemetic-favorite-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.kemetic-favorite-content h3 {
    color: #fff;
    font-size: 17px;
    font-weight: 600;
}

/* MORE BTN */
.btn-kemetic-more {
    background: transparent;
    border: none;
    color: #aaa;
}

/* PRICE */
.kemetic-price {
    display: flex;
    gap: 10px;
}

.price-now {
    color: #F2C94C;
    font-size: 18px;
    font-weight: 700;
}

.price-old {
    color: #777;
    text-decoration: line-through;
}

/* META */
.kemetic-meta-grid {
    margin-top: auto;
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(140px,1fr));
    gap: 12px;
}

.kemetic-meta-grid label {
    display: block;
    font-size: 12px;
    color: #888;
}

.kemetic-meta-grid span {
    color: #ddd;
    font-size: 14px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .kemetic-favorite-card {
        flex-direction: column;
    }
    .kemetic-favorite-image {
        width: 100%;
        height: 180px;
    }
}

</style>
@push('styles_top')
    <link rel="stylesheet" href="/assets/kemetic/css/favorites.css">
@endpush

@section('content')

<section class="kemetic-page-header">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="kemetic-title">
            {{ trans('panel.favorite_live_classes') }}
        </h2>
    </div>
</section>

@if(!empty($favorites) and !$favorites->isEmpty())

    @foreach($favorites as $favorite)
        @php
            $favItem = !empty($favorite->upcoming_course_id)
                ? $favorite->upcomingCourse
                : ((!empty($favorite->webinar_id)) ? $favorite->webinar : $favorite->bundle);
        @endphp

        <div class="kemetic-favorite-card mt-30">

            {{-- IMAGE --}}
            <div class="kemetic-favorite-image">
                <img src="{{ $favItem->getImage() }}" alt="">

                @if(!empty($favorite->webinar_id) and $favItem->type == 'webinar')
                    <div class="kemetic-progress">
                        <span style="width: {{ $favItem->getProgress() }}%"></span>
                    </div>
                @endif
            </div>

            {{-- CONTENT --}}
            <div class="kemetic-favorite-content">

                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ $favItem->getUrl() }}" target="_blank">
                        <h3>{{ $favItem->title }}</h3>
                    </a>

                    <div class="dropdown">
                        <button class="btn-kemetic-more" data-toggle="dropdown">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="/panel/webinars/favorites/{{ $favorite->id }}/delete"
                               class="dropdown-item text-danger">
                                {{ trans('public.remove') }}
                            </a>
                        </div>
                    </div>
                </div>

                @if(empty($favorite->upcoming_course_id))
                    @include(getTemplate().'.includes.webinar.rate',['rate'=>$favItem->getRate()])
                @endif

                {{-- PRICE --}}
                <div class="kemetic-price mt-10">
                    @if(empty($favorite->upcoming_course_id) and $favItem->bestTicket() < $favItem->price)
                        <span class="price-now">{{ handlePrice($favItem->bestTicket()) }}</span>
                        <span class="price-old">{{ handlePrice($favItem->price) }}</span>
                    @else
                        <span class="price-now">{{ handlePrice($favItem->price) }}</span>
                    @endif
                </div>

                {{-- META --}}
                <div class="kemetic-meta-grid">
                    <div><label>ID</label><span>{{ $favItem->id }}</span></div>
                    <div><label>{{ trans('public.category') }}</label><span>{{ $favItem->category->title ?? '-' }}</span></div>
                    <div><label>{{ trans('public.duration') }}</label><span>{{ convertMinutesToHourAndMinute($favItem->duration) }} {{ trans('home.hours') }}</span></div>
                    <div>
                        <label>
                            {{ !empty($favorite->webinar_id) ? trans('public.start_date') : trans('public.created_at') }}
                        </label>
                        <span>{{ dateTimeFormat($favItem->start_date ?? $favItem->created_at,'j M Y') }}</span>
                    </div>
                    <div><label>{{ trans('public.instructor') }}</label><span>{{ $favItem->teacher->full_name }}</span></div>
                </div>

            </div>
        </div>
    @endforeach

@else
    @include(getTemplate().'.includes.no-result',[
        'file_name'=>'student.png',
        'title'=>trans('panel.no_result_favorites'),
        'hint'=>trans('panel.no_result_favorites_hint')
    ])
@endif

<div class="my-30">
    {{ $favorites->appends(request()->input())->links('vendor.pagination.panel') }}
</div>

@endsection
