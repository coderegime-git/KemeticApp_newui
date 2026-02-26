@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<style>
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

/* Inputs & Select */
.kemetic-input,
.kemetic-select {
    background: #0e0e0e;
    border: 1px solid rgba(212, 175, 55, 0.25);
    color: #f5f5f5;
    border-radius: 10px;
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


/* Topic subtitle */
.kemetic-topic-sub {
    font-size: 12px;
    color: #9a9a9a;
    margin-top: 2px;
}

/* Spacing helper */
.gap-15 {
    gap: 15px;
}

</style>
@endpush

@section('content')
<section class="mt-15 kemetic-dark-section">
    <h2 class="section-title kemetic-title">
        {{ trans('update.filter_posts') }}
    </h2>

    <div class="panel-section-card kemetic-card-dark py-25 px-30 mt-20" style="padding:10px;">
        <form action="/panel/forums/posts" method="get" class="row kemetic-form-dark">

            <!-- Date Range -->
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
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <h2 class="section-title kemetic-title">
            {{ trans('update.my_posts') }}
        </h2>
    </div>

    @if($posts->count() > 0)
        <div class="panel-section-card kemetic-card-dark py-25 px-30 mt-20">
            <div class="table-responsive">
                <table class="kemetic-table-dark">
                    <thead>
                        <tr>
                            <th class="text-center">{{ trans('public.topic') }}</th>
                            <th>{{ trans('update.forum') }}</th>
                            <th>{{ trans('update.replies') }}</th>
                            <th>{{ trans('public.publish_date') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($posts as $post)
                        <tr class="kemetic-row">
                            <td class="text-left">
                                <div class="d-flex align-items-center gap-15">
                                    <div class="kemetic-avatar">
                                        <img src="{{ $post->topic->creator->getAvatar(48) }}" alt="Avatar">
                                    </div>
                                    <div>
                                        <a href="{{ $post->topic->getPostsUrl() }}" target="_blank" class="kemetic-topic-link">
                                            <div class="kemetic-topic-title">
                                                {{ $post->topic->title }}
                                            </div>
                                            <div class="kemetic-topic-sub">
                                                {{ trans('public.by') }} {{ $post->topic->creator->full_name }}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <td class="kemetic-muted">
                                {{ $post->topic->forum->title }}
                            </td>

                            <td class="kemetic-count">
                                {{ $post->replies_count }}
                            </td>

                            <td class="kemetic-muted">
                                {{ dateTimeFormat($post->created_at, 'j M Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        @include(getTemplate() . '.includes.no-result',[
            'file_name' => 'comment.png',
            'title' => trans('update.panel_topics_posts_no_result'),
            'hint' => nl2br(trans('update.panel_topics_posts_no_result_hint')),
        ])
    @endif
</section>


<div class="my-30">
    {{ $posts->appends(request()->input())->links('vendor.pagination.panel') }}
</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script>
    feather.replace();
</script>
@endpush
