    @extends('web.default.layouts.newapp')
    <style>
        /* KEMETIC ACTIVITY */
        .kemetic-activity-section{
            margin-top:25px;
        }

        .kemetic-title{
            color:#F2C94C;
            font-size:22px;
            font-weight:700;
            margin-bottom:18px;
        }

        /* CARD */
        .kemetic-activity-card{
            background:#0f0f0f;
            border:1px solid #262626;
            border-radius:18px;
            padding:28px 20px;
        }

        /* ITEM */
        .kemetic-activity-item{
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:6px;
        }

        /* ICON */
        .kemetic-icon{
            width:56px;
            height:56px;
            background:rgba(242,201,76,0.12);
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .kemetic-icon img{
            width:28px;
            filter:invert(0.9);
        }

        /* VALUE */
        .kemetic-value{
            font-size:30px;
            font-weight:700;
            color:#F2C94C;
        }

        /* LABEL */
        .kemetic-label{
            font-size:14px;
            color:#9a9a9a;
        }

        /* MOBILE */
        @media(max-width:768px){
            .kemetic-activity-card{
                padding:20px 12px;
            }
            .kemetic-value{
                font-size:24px;
            }
        }

        /* ===============================
        KEMETIC THEME VARIABLES
        ================================ */
        :root {
            --k-bg: #0f0f0f;
            --k-card: #151515;
            --k-border: #262626;
            --k-gold: #F2C94C;
            --k-gold-soft: rgba(242,201,76,0.15);
            --k-text: #e5e5e5;
            --k-muted: #9a9a9a;
            --k-radius: 16px;
            --k-shadow: 0 10px 30px rgba(0,0,0,0.6);
        }

        /* ===============================
        SECTION
        ================================ */
        .section-title {
            color: var(--k-gold);
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* ===============================
        CARD
        ================================ */
        .webinar-card.webinar-list {
            background: linear-gradient(180deg, #171717, #101010);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            overflow: hidden;
            box-shadow: var(--k-shadow);
            transition: 0.3s ease;
        }

        .webinar-card.webinar-list:hover {
            transform: translateY(-4px);
            border-color: var(--k-gold);
        }

        /* ===============================
        IMAGE
        ================================ */
        .webinar-card .image-box {
            position: relative;
            width: 260px;
            min-width: 260px;
            background: #000;
        }

        .webinar-card .image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===============================
        BADGES
        ================================ */
        .badges-lists {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            gap: 6px;
        }

        .badge {
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary {
            background: var(--k-gold);
            color: #000;
        }

        .badge-secondary,
        .badge-dark {
            background: #222;
            color: var(--k-gold);
            border: 1px solid var(--k-gold-soft);
        }

        .badge-outlined-danger {
            border: 1px solid #ff5c5c;
            color: #ff5c5c;
            background: transparent;
        }

        .badge-outlined-warning {
            border: 1px solid var(--k-gold);
            color: var(--k-gold);
            background: transparent;
        }

        /* ===============================
        BODY
        ================================ */
        .webinar-card-body {
            padding: 20px 24px;
            color: var(--k-text);
        }

        .webinar-title {
            color: var(--k-text);
            line-height: 1.4;
        }

        .webinar-title:hover {
            color: var(--k-gold);
        }

        /* ===============================
        PROGRESS
        ================================ */
        .progress {
            height: 6px;
            background: #1f1f1f;
            border-radius: 6px;
            margin-top: 10px;
        }

        .progress-bar {
            background: linear-gradient(90deg, #b8860b, var(--k-gold));
            border-radius: 6px;
        }

        /* ===============================
        PRICE
        ================================ */
        .webinar-price-box {
            margin-top: 10px;
        }

        .webinar-price-box .real {
            color: var(--k-gold);
            font-size: 18px;
            font-weight: 700;
        }

        .webinar-price-box .off {
            color: var(--k-muted);
            text-decoration: line-through;
            font-size: 14px;
        }

        /* ===============================
        STATS
        ================================ */
        .stat-title {
            font-size: 12px;
            color: var(--k-muted);
        }

        .stat-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--k-text);
        }

        /* ===============================
        DROPDOWN
        ================================ */
        .table-actions .dropdown-menu {
            background: #121212;
            border: 1px solid var(--k-border);
            border-radius: 12px;
            box-shadow: var(--k-shadow);
        }

        .webinar-actions {
            color: var(--k-text);
            font-size: 14px;
            padding: 6px 14px;
            transition: 0.25s;
        }

        .webinar-actions:hover {
            color: var(--k-gold);
            background: var(--k-gold-soft);
        }

        /* ===============================
        PAGINATION
        ================================ */
        .pagination .page-link {
            background: #151515;
            border: 1px solid var(--k-border);
            color: var(--k-text);
        }

        .pagination .page-item.active .page-link {
            background: var(--k-gold);
            color: #000;
            border-color: var(--k-gold);
        }

        .pagination .page-link:hover {
            background: var(--k-gold-soft);
            color: var(--k-gold);
        }

        /* ===============================
        MOBILE
        ================================ */
        @media (max-width: 768px) {
            .webinar-card.webinar-list {
                flex-direction: column;
            }

            .webinar-card .image-box {
                width: 100%;
                min-width: 100%;
                height: 180px;
            }
        }

        .kemetic-actions {
            position: relative;
        }

        .kemetic-actions button {
            background:none; border:none; color:#F2C94C;
        }
        .kemetic-actions .dropdown-menu {
            background:#121212;
            border:1px solid #2a2a2a;
        }
        .kemetic-actions a {
            color:#fff; display:block;
            padding:8px 14px;
        }
        .kemetic-actions a:hover {
            background:#1a1a1a;
        }



    </style>

    @section('content')
       <section class="kemetic-activity-section">
            <h2 class="kemetic-title">{{ trans('panel.my_activity') }}</h2>

            <div class="kemetic-activity-card">
                <div class="row text-center">

                    <div class="col-4">
                        <div class="kemetic-activity-item">
                            <div class="kemetic-icon">
                                <img src="/assets/default/img/activity/webinars.svg" alt="">
                            </div>
                            <div class="kemetic-value">{{ $purchasedCount }}</div>
                            <div class="kemetic-label">{{ trans('panel.purchased') }}</div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="kemetic-activity-item">
                            <div class="kemetic-icon">
                                <img src="/assets/default/img/activity/hours.svg" alt="">
                            </div>
                            <div class="kemetic-value">
                                {{ convertMinutesToHourAndMinute($hours) }}
                            </div>
                            <div class="kemetic-label">{{ trans('home.hours') }}</div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="kemetic-activity-item">
                            <div class="kemetic-icon">
                                <img src="/assets/default/img/activity/upcoming.svg" alt="">
                            </div>
                            <div class="kemetic-value">{{ $upComing }}</div>
                            <div class="kemetic-label">{{ trans('panel.upcoming') }}</div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


            <section class="mt-25">
                <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
                    <h2 class="section-title">{{ trans('panel.my_purchases') }}</h2>
                </div>

                @if(!empty($sales) and !$sales->isEmpty())
                    @foreach($sales as $sale)
                        @php
                            $item = !empty($sale->webinar) ? $sale->webinar : $sale->bundle;

                            $lastSession = !empty($sale->webinar) ? $sale->webinar->lastSession() : null;
                            $nextSession = !empty($sale->webinar) ? $sale->webinar->nextSession() : null;
                            $isProgressing = false;

                            if(!empty($sale->webinar) and $sale->webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
                                $isProgressing = true;
                            }
                        @endphp

                        @if(!empty($item))
                            <div class="row mt-30">
                                <div class="col-12">
                                    <div class="webinar-card webinar-list d-flex">
                                        <div class="image-box">
                                            <img src="{{ $item->getImage() }}" class="img-cover" alt="">

                                            @if(!empty($sale->webinar))
                                                <div class="badges-lists">
                                                    @if($item->type == 'webinar')
                                                        @if($item->start_date > time())
                                                            <span class="badge badge-primary">{{  trans('panel.not_conducted') }}</span>
                                                        @elseif($item->isProgressing())
                                                            <span class="badge badge-secondary">{{ trans('webinars.in_progress') }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ trans('public.finished') }}</span>
                                                        @endif
                                                    @elseif(!empty($item->downloadable))
                                                        <span class="badge badge-secondary">{{ trans('home.downloadable') }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ trans('webinars.'.$item->type) }}</span>
                                                    @endif
                                                </div>

                                                @php
                                                    $percent = $item->getProgress();

                                                    if($item->isWebinar()){
                                                        if($item->isProgressing()) {
                                                            $progressTitle = trans('public.course_learning_passed',['percent' => $percent]);
                                                        } else {
                                                            $progressTitle = $item->sales_count .'/'. $item->capacity .' '. trans('quiz.students');
                                                        }
                                                    } else {
                                                        $progressTitle = trans('public.course_learning_passed',['percent' => $percent]);
                                                    }
                                                @endphp

                                                @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                                    {{--  --}}
                                                @else
                                                    <div class="progress cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $progressTitle }}">
                                                        <span class="progress-bar" style="width: {{ $percent }}%"></span>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="badges-lists">
                                                    <span class="badge badge-secondary">{{ trans('update.bundle') }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="webinar-card-body w-100 d-flex flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <a href="{{ $item->getUrl() }}">
                                                    <h3 class="webinar-title font-weight-bold font-16 text-dark-blue">
                                                        {{ $item->title }}

                                                        @if(!empty($item->access_days))
                                                            @if(!$item->checkHasExpiredAccessDays($sale->created_at, $sale->gift_id))
                                                                <span class="badge badge-outlined-danger ml-10">{{ trans('update.access_days_expired') }}</span>
                                                            @else
                                                                <span class="badge badge-outlined-warning ml-10">{{ trans('update.expired_on_date',['date' => dateTimeFormat($item->getExpiredAccessDays($sale->created_at, $sale->gift_id),'j M Y')]) }}</span>
                                                            @endif
                                                        @endif

                                                        @if($sale->payment_method == \App\Models\Sale::$subscribe and $sale->checkExpiredPurchaseWithSubscribe($sale->buyer_id, $item->id, !empty($sale->webinar) ? 'webinar_id' : 'bundle_id'))
                                                            <span class="badge badge-outlined-danger ml-10">{{ trans('update.subscribe_expired') }}</span>
                                                        @endif

                                                        @if(!empty($sale->webinar))
                                                            <span class="badge badge-dark ml-10 status-badge-dark">{{ trans('webinars.'.$item->type) }}</span>
                                                        @endif

                                                        @if(!empty($sale->gift_id))
                                                            <span class="badge badge-primary ml-10">{{ trans('update.gift') }}</span>
                                                        @endif
                                                    </h3>
                                                </a>

                                                <div class="btn-group dropdown table-actions kemetic-actions">
                                                    <button type="button"
                                                            class="btn-transparent kemetic-action-btn dropdown-toggle"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i data-feather="more-vertical" height="20"></i>
                                                    </button>

                                                    <div class="dropdown-menu kemetic-dropdown">

                                                        @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                                            <a href="/panel/webinars/{{ $item->id }}/sale/{{ $sale->id }}/invoice"
                                                            target="_blank"
                                                            class="kemetic-dropdown-item">
                                                                {{ trans('public.invoice') }}
                                                            </a>
                                                        @else
                                                            @if(!empty($item->access_days) and !$item->checkHasExpiredAccessDays($sale->created_at, $sale->gift_id))
                                                                <a href="{{ $item->getUrl() }}"
                                                                target="_blank"
                                                                class="kemetic-dropdown-item">
                                                                    {{ trans('update.enroll_on_course') }}
                                                                </a>
                                                            @elseif(!empty($sale->webinar))

                                                                <a href="{{ $item->getLearningPageUrl() }}"
                                                                target="_blank"
                                                                class="kemetic-dropdown-item">
                                                                    {{ trans('update.learning_page') }}
                                                                </a>

                                                                @if(!empty($item->start_date) and ($item->start_date > time() or ($item->isProgressing() and !empty($nextSession))))
                                                                    <button type="button"
                                                                            data-webinar-id="{{ $item->id }}"
                                                                            class="kemetic-dropdown-item kemetic-btn-item join-purchase-webinar">
                                                                        {{ trans('footer.join') }}
                                                                    </button>
                                                                @endif

                                                                @if(!empty($item->downloadable) or (!empty($item->files) and count($item->files)))
                                                                    <a href="{{ $item->getUrl() }}?tab=content"
                                                                    target="_blank"
                                                                    class="kemetic-dropdown-item">
                                                                        {{ trans('home.download') }}
                                                                    </a>
                                                                @endif

                                                                @if($item->price > 0)
                                                                    <a href="/panel/webinars/{{ $item->id }}/sale/{{ $sale->id }}/invoice"
                                                                    target="_blank"
                                                                    class="kemetic-dropdown-item">
                                                                        {{ trans('public.invoice') }}
                                                                    </a>
                                                                @endif
                                                            @endif

                                                            <a href="{{ $item->getUrl() }}?tab=reviews"
                                                            target="_blank"
                                                            class="kemetic-dropdown-item kemetic-muted">
                                                                {{ trans('public.feedback') }}
                                                            </a>
                                                        @endif

                                                    </div>
                                                </div>

                                            </div>

                                            @include(getTemplate() . '.includes.webinar.rate',['rate' => $item->getRate()])

                                            <div class="webinar-price-box mt-15">
                                                @if($item->price > 0)
                                                    @if($item->bestTicket() < $item->price)
                                                        <span class="real">{{ handlePrice($item->bestTicket(), true, true, false, null, true) }}</span>
                                                        <span class="off ml-10">{{ handlePrice($item->price, true, true, false, null, true) }}</span>
                                                    @else
                                                        <span class="real">{{ handlePrice($item->price, true, true, false, null, true) }}</span>
                                                    @endif
                                                @else
                                                    <span class="real">{{ trans('public.free') }}</span>
                                                @endif
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">

                                                @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('update.gift_status') }}:</span>

                                                        @if(!empty($sale->gift_date) and $sale->gift_date > time())
                                                            <span class="stat-value text-warning">{{ trans('public.pending') }}</span>
                                                        @else
                                                            <span class="stat-value text-primary">{{ trans('update.sent') }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('public.item_id') }}:</span>
                                                        <span class="stat-value">{{ $item->id }}</span>
                                                    </div>
                                                @endif

                                                @if(!empty($sale->gift_id))
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('update.gift_receive_date') }}:</span>
                                                        <span class="stat-value">{{ (!empty($sale->gift_date)) ? dateTimeFormat($sale->gift_date, 'j M Y H:i') : trans('update.instantly') }}</span>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('public.category') }}:</span>
                                                        <span class="stat-value">{{ !empty($item->category_id) ? $item->category->title : '' }}</span>
                                                    </div>
                                                @endif

                                                @if(!empty($sale->webinar) and $item->type == 'webinar')
                                                    @if($item->isProgressing() and !empty($nextSession))
                                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                            <span class="stat-title">{{ trans('webinars.next_session_duration') }}:</span>
                                                            <span class="stat-value">{{ convertMinutesToHourAndMinute($nextSession->duration) }} Hrs</span>
                                                        </div>

                                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                            <span class="stat-title">{{ trans('webinars.next_session_start_date') }}:</span>
                                                            <span class="stat-value">{{ dateTimeFormat($nextSession->date,'j M Y') }}</span>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                            <span class="stat-title">{{ trans('public.duration') }}:</span>
                                                            <span class="stat-value">{{ convertMinutesToHourAndMinute($item->duration) }} Hrs</span>
                                                        </div>

                                                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                            <span class="stat-title">{{ trans('public.start_date') }}:</span>
                                                            <span class="stat-value">{{ dateTimeFormat($item->start_date,'j M Y') }}</span>
                                                        </div>
                                                    @endif
                                                @elseif(!empty($sale->bundle))
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('public.duration') }}:</span>
                                                        <span class="stat-value">{{ convertMinutesToHourAndMinute($item->getBundleDuration()) }} Hrs</span>
                                                    </div>
                                                @endif

                                                @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('update.receipt') }}:</span>
                                                        <span class="stat-value">{{ $sale->gift_recipient }}</span>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('public.instructor') }}:</span>
                                                        <span class="stat-value">{{ $item->teacher->full_name }}</span>
                                                    </div>
                                                @endif

                                                @if(!empty($sale->gift_id) and $sale->buyer_id != $authUser->id)
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('update.gift_sender') }}:</span>
                                                        <span class="stat-value">{{ $sale->gift_sender }}</span>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                                        <span class="stat-title">{{ trans('panel.purchase_date') }}:</span>
                                                        <span class="stat-value">{{ dateTimeFormat($sale->created_at,'j M Y') }}</span>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'student.png',
                    'title' => trans('panel.no_result_purchases') ,
                    'hint' => trans('panel.no_result_purchases_hint') ,
                    'btn' => ['url' => '/classes?sort=newest','text' => trans('panel.start_learning')]
                ])
                @endif
            </section>

            <div class="my-30">
                {{ $sales->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>

        @include('web.default.panel.webinar.join_webinar_modal')
    @endsection

    @push('scripts_bottom')
        <script>
            var undefinedActiveSessionLang = '{{ trans('webinars.undefined_active_session') }}';
        </script>

        <script src="/assets/default/js/panel/join_webinar.min.js"></script>
    @endpush
