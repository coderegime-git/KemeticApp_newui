@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<style>
    /* Section */
.kemetic-dark-section {
    background: #0b0b0b;
    padding: 30px;
    border-radius: 18px;
}

/* Title */
.kemetic-title {
    color: #d4af37;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 20px;
}

/* Card */
.kemetic-card-dark {
    background: linear-gradient(145deg, #0f0f0f, #151515);
    border-radius: 20px;
    border: 1px solid rgba(212, 175, 55, 0.25);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
}

/* Stat Box */
.kemetic-stat-dark {
    padding: 20px;
    border-radius: 16px;
    transition: all 0.3s ease;
}

.kemetic-stat-dark img {
    width: 52px;
    height: 52px;
    margin-bottom: 10px;
    filter: drop-shadow(0 0 6px rgba(212, 175, 55, 0.6));
}

/* Numbers */
.kemetic-stat-dark strong {
    display: block;
    font-size: 30px;
    font-weight: 800;
    color: #f5d76e;
}

/* Labels */
.kemetic-stat-dark span {
    font-size: 13px;
    color: #c9c9c9;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

/* Hover Effect */
.kemetic-stat-dark:hover {
    background: rgba(212, 175, 55, 0.05);
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.15);
}

/* Section */
.kemetic-dark-section {
    background: #0b0b0b;
    border-radius: 18px;
}

/* Title */
.kemetic-title {
    color: #d4af37;
    font-weight: 700;
    letter-spacing: 1px;
}

/* Card */
.kemetic-card-dark {
    background: linear-gradient(145deg, #0f0f0f, #161616);
    border-radius: 20px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.9);
}

/* Labels */
.kemetic-label {
    color: #bfae70;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

/* Inputs */
.kemetic-input,
.kemetic-select {
    background: #0e0e0e;
    border: 1px solid rgba(212, 175, 55, 0.25);
    color: #f5f5f5;
    border-radius: 10px;
}

.kemetic-input::placeholder {
    color: #777;
}

.kemetic-input:focus,
.kemetic-select:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 0.15rem rgba(212, 175, 55, 0.25);
}

/* Input Group */
.kemetic-input-group .input-group-text {
    background: #111;
    border: 1px solid rgba(212, 175, 55, 0.25);
    color: #d4af37;
}

/* Button */
.btn-kemetic-gold {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    font-weight: 700;
    border-radius: 12px;
    padding: 12px 18px;
    border: none;
    transition: all 0.3s ease;
}

.btn-kemetic-gold:hover {
    background: linear-gradient(135deg, #f5d76e, #d4af37);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}
/* Section */
.kemetic-dark-section {
    background: #0b0b0b;
    border-radius: 20px;
}

/* Title */
.kemetic-title {
    color: #d4af37;
    font-weight: 700;
    letter-spacing: 1px;
}

/* Card */
.kemetic-card-dark {
    background: linear-gradient(145deg, #0f0f0f, #161616);
    border-radius: 22px;
    border: 1px solid rgba(212, 175, 55, 0.25);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.9);
}

/* Table */
.kemetic-table-dark {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
    color: #f5f5f5;
}

.kemetic-table-dark thead th {
    color: #d4af37;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    border: none;
    padding-bottom: 12px;
}

/* Rows */
.kemetic-row {
    background: #0e0e0e;
    border-radius: 14px;
    transition: all 0.3s ease;
}

.kemetic-row td {
    border: none;
    padding: 14px 12px;
    vertical-align: middle;
}

.kemetic-row:hover {
    background: rgba(212, 175, 55, 0.08);
    transform: translateY(-2px);
}

/* Avatar */
.kemetic-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #111;
    border: 1px solid rgba(212, 175, 55, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.kemetic-avatar img {
    width: 22px;
    height: 22px;
}

/* Topic Title */
.kemetic-topic-link {
    text-decoration: none;
}

.kemetic-topic-title {
    color: #f5d76e;
    font-weight: 600;
    transition: color 0.2s;
}

.kemetic-topic-title:hover {
    color: #fff;
}

/* Text Helpers */
.kemetic-muted {
    color: #b8b8b8;
}

.kemetic-count {
    font-weight: 700;
    color: #fff;
}

/* Badges */
.kemetic-badge {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 20px;
    font-weight: 600;
}

.kemetic-badge-published {
    background: rgba(212, 175, 55, 0.15);
    color: #f5d76e;
    border: 1px solid rgba(212, 175, 55, 0.5);
}

.kemetic-badge-closed {
    background: rgba(255, 70, 70, 0.15);
    color: #ff6b6b;
    border: 1px solid rgba(255, 70, 70, 0.5);
}

</style>
@endpush

@section('content')
<section class="kemetic-dark-section">
    <h2 class="section-title kemetic-title">
        {{ trans('update.topics_statistics') }}
    </h2>

    <div class="activities-container kemetic-card-dark mt-25 p-25 p-lg-40">
        <div class="row text-center">
            <div class="col-4">
                <div class="kemetic-stat-dark">
                    <img src="/assets/default/img/activity/125.svg" alt="">
                    <strong>{{ $publishedTopics }}</strong>
                    <span>{{ trans('update.published_topics') }}</span>
                </div>
            </div>

            <div class="col-4">
                <div class="kemetic-stat-dark">
                    <img src="/assets/default/img/activity/126.svg" alt="">
                    <strong>{{ $lockedTopics }}</strong>
                    <span>{{ trans('update.locked_topics') }}</span>
                </div>
            </div>

            <div class="col-4">
                <div class="kemetic-stat-dark">
                    <img src="/assets/default/img/activity/39.svg" alt="">
                    <strong>{{ $topicMessages }}</strong>
                    <span>{{ trans('update.topic_messages') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="mt-25 kemetic-dark-section">
    <h2 class="section-title kemetic-title">
        {{ trans('update.filter_topics') }}
    </h2>

    <div class="panel-section-card kemetic-card-dark py-25 px-30 mt-20" style="padding: 10px;">
        <form action="/panel/forums/topics" method="get" class="row kemetic-form-dark">

            <!-- Date Filters -->
            <div class="col-12 col-lg-5">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('public.from') }}</label>
                            <div class="input-group kemetic-input-group">
                                <input type="date"
                                    class="form-control kemetic-input text-center"
                                    name="from"
                                    value="{{ request()->get('from') }}">
                                    <!-- <span class="input-group-text kemetic-icon bg-gold text-black">
                                        <i data-feather="calendar" width="18" height="18"></i>
                                    </span> -->

                                    <!-- <input type="text" name="from" autocomplete="off"
                                        class="form-control kemetic-input @if(request()->get('from')) datepicker @else datefilter @endif"
                                        value="{{ request()->get('from','') }}"> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('public.to') }}</label>
                            <div class="input-group kemetic-input-group">
                                <input type="date"
                                    class="form-control kemetic-input text-center"
                                    name="to"
                                    value="{{ request()->get('to') }}">

                                <!-- <span class="input-group-text kemetic-icon bg-gold text-black">
                                    <i data-feather="calendar" width="18" height="18"></i>
                                </span> -->
                                
                                <!-- <input type="text" name="to" autocomplete="off"
                                    class="form-control kemetic-input @if(request()->get('to')) datepicker @else datefilter @endif"
                                    value="{{ request()->get('to','') }}"> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forum & Status -->
            <div class="col-12 col-lg-5">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('update.forums') }}</label>
                            <select name="forum_id" class="form-control kemetic-select">
                                <option value="all">{{ trans('public.all') }}</option>
                                @foreach($forums as $forum)
                                    @if(!empty($forum->subForums) and count($forum->subForums))
                                        <optgroup label="{{ $forum->title }}">
                                            @foreach($forum->subForums as $subForum)
                                                <option value="{{ $subForum->id }}" {{ (request()->get('forum_id') == $subForum->id) ? 'selected' : '' }}>
                                                    {{ $subForum->title }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $forum->id }}" {{ (request()->get('forum_id') == $forum->id) ? 'selected' : '' }}>
                                            {{ $forum->title }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('public.status') }}</label>
                            <select class="form-control kemetic-select" name="status">
                                <option value="all">{{ trans('public.all') }}</option>
                                <option value="published" @if(request()->get('status') == 'published') selected @endif>
                                    {{ trans('public.published') }}
                                </option>
                                <option value="closed" @if(request()->get('status') == 'closed') selected @endif>
                                    {{ trans('panel.closed') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button -->
            <div class="col-12 col-lg-2 d-flex align-items-end">
                <button type="submit" class="btn btn-kemetic-gold w-100">
                    {{ trans('public.show_results') }}
                </button>
            </div>

        </form>
    </div>
</section>


<section class="mt-35 kemetic-dark-section">
    <h2 class="section-title kemetic-title">
        {{ trans('update.my_topics') }}
    </h2>

    @if($topics->count() > 0)
        <div class="panel-section-card kemetic-card-dark py-25 px-30 mt-20">
            <div class="table-responsive">
                <table class="kemetic-table-dark">
                    <thead>
                        <tr>
                            <th class="text-center">{{ trans('public.title') }}</th>
                            <th>{{ trans('update.forum') }}</th>
                            <th>{{ trans('site.posts') }}</th>
                            <th>{{ trans('public.status') }}</th>
                            <th>{{ trans('public.publish_date') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($topics as $topic)
                        <tr class="kemetic-row">
                            <td class="text-left">
                                <div class="d-flex align-items-center gap-15">
                                    <div class="kemetic-avatar">
                                        <img src="{{ $topic->forum->icon }}" alt="Forum Icon">
                                    </div>
                                    <a href="{{ $topic->getPostsUrl() }}" target="_blank" class="kemetic-topic-link">
                                        <span class="kemetic-topic-title">{{ $topic->title }}</span>
                                    </a>
                                </div>
                            </td>

                            <td class="kemetic-muted">{{ $topic->forum->title }}</td>
                            <td class="kemetic-count">{{ $topic->posts_count }}</td>

                            <td>
                                @if($topic->close)
                                    <span class="kemetic-badge kemetic-badge-closed">
                                        {{ trans('panel.closed') }}
                                    </span>
                                @else
                                    <span class="kemetic-badge kemetic-badge-published">
                                        {{ trans('public.published') }}
                                    </span>
                                @endif
                            </td>

                            <td class="kemetic-muted">
                                {{ dateTimeFormat($topic->created_at, 'j M Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        @include(getTemplate() . '.includes.no-result',[
            'file_name' => 'quiz.png',
            'title' => trans('update.panel_topics_no_result'),
            'hint' => nl2br(trans('update.panel_topics_no_result_hint')),
            'btn' => ['url' => '/forums','text' => trans('update.forums')]
        ])
    @endif
</section>


<div class="my-30">
    {{ $topics->appends(request()->input())->links('vendor.pagination.panel') }}
</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script>
    feather.replace();
</script>
@endpush
