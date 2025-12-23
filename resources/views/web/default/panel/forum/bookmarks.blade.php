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

.kemetic-bookmark-remove {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(212, 175, 55, 0.15);
    border: 1px solid rgba(212, 175, 55, 0.4);
    color: #d4af37;
    transition: all 0.25s ease;
}

.kemetic-bookmark-remove svg {
    width: 16px;
    height: 16px;
}

.kemetic-bookmark-remove:hover {
    background: rgba(212, 175, 55, 0.35);
    box-shadow: 0 0 12px rgba(212, 175, 55, 0.6);
    transform: scale(1.1);
    color: #000;
}

    </style>
@endpush

@section('content')
<section class="mt-35 kemetic-dark-section">

    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <h2 class="section-title kemetic-title">
            {{ trans('update.bookmarks') }}
        </h2>
    </div>

    @if($topics->count() > 0)

        <div class="panel-section-card kemetic-card-dark py-25 px-30 mt-20">
            <div class="table-responsive">
                <table class="table kemetic-table-dark">
                    <thead>
                        <tr>
                            <th class="text-left">{{ trans('public.topic') }}</th>
                            <th>{{ trans('update.forum') }}</th>
                            <th>{{ trans('update.replies') }}</th>
                            <th>{{ trans('public.publish_date') }}</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($topics as $topic)
                        <tr class="kemetic-row">
                            <td class="text-left">
                                <div class="d-flex align-items-center gap-15">
                                    <div class="kemetic-avatar">
                                        <img src="{{ $topic->creator->getAvatar(48) }}" alt="Avatar">
                                    </div>
                                    <div>
                                        <a href="{{ $topic->getPostsUrl() }}" target="_blank" class="kemetic-topic-link">
                                            <div class="kemetic-topic-title">
                                                {{ $topic->title }}
                                            </div>
                                            <div class="kemetic-topic-sub">
                                                {{ trans('public.by') }} {{ $topic->creator->full_name }}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <td class="kemetic-muted">
                                {{ $topic->forum->title }}
                            </td>

                            <td class="kemetic-count">
                                {{ $topic->posts_count }}
                            </td>

                            <td class="kemetic-muted">
                                {{ dateTimeFormat($topic->created_at, 'j M Y H:i') }}
                            </td>

                            <!-- Remove Bookmark -->
                            <td class="text-center align-middle">
                                <a href="/panel/forums/topics/{{ $topic->id }}/removeBookmarks"
                                   data-title="{{ trans('update.this_topic_will_be_removed_from_your_bookmark') }}"
                                   data-confirm="{{ trans('update.confirm') }}"
                                   class="kemetic-bookmark-remove d-flex align-items-center justify-content-center">
                                    <i data-feather="bookmark"></i>
                                </a>
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
            'title' => trans('update.panel_topics_bookmark_no_result'),
            'hint' => nl2br(trans('update.panel_topics_bookmark_no_result_hint')),
        ])
    @endif

</section>


    <div class="my-30">
        {{ $topics->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
@endpush
