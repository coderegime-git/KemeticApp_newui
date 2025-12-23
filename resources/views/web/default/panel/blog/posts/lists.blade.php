@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =========================
   KEMETIC THEME VARIABLES
========================= */
:root {
    --k-bg: #0b0b0b;
    --k-card: #141414;
    --k-gold: #d4af37;
    --k-gold-soft: rgba(212,175,55,.25);
    --k-border: rgba(212,175,55,.15);
    --k-text: #f5f5f5;
    --k-muted: #9a9a9a;
    --k-radius: 18px;
    --k-shadow: 0 12px 40px rgba(0,0,0,.65);
}

/* =========================
   PAGE
========================= */
.kemetic-page {
    background: radial-gradient(circle at top, #1a1a1a, #000);
    min-height: 100vh;
    padding: 25px;
    color: var(--k-text);
}

.section-title {
    color: var(--k-gold);
    font-weight: 700;
    letter-spacing: .6px;
}

/* =========================
   STATS
========================= */
.kemetic-stats {
    background: linear-gradient(145deg, #161616, #0c0c0c);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
}

.stat-item img {
    filter: brightness(1.2);
}

.stat-value {
    font-size: 34px;
    color: var(--k-gold);
}

.stat-label {
    color: var(--k-muted);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* =========================
   TABLE CARD
========================= */
.kemetic-table-card {
    background: var(--k-card);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
}

.custom-table thead {
    background: rgba(212,175,55,.1);
}

.custom-table th {
    color: var(--k-gold);
    font-weight: 600;
    border-bottom: 1px solid var(--k-border);
}

.custom-table td {
    color: var(--k-text);
    border-top: 1px solid rgba(255,255,255,.05);
}

.custom-table tr:hover {
    background: rgba(212,175,55,.05);
}

.custom-table a {
    color: var(--k-gold);
    font-weight: 600;
}

.custom-table a:hover {
    text-decoration: underline;
}

/* =========================
   STATUS
========================= */
.k-status-published {
    color: #2ecc71;
    font-weight: 600;
}

.k-status-pending {
    color: #f1c40f;
    font-weight: 600;
}

/* =========================
   ACTIONS
========================= */
.btn-transparent {
    color: var(--k-gold);
}

.dropdown-menu {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
}

.dropdown-menu a {
    color: var(--k-text);
}

.dropdown-menu a:hover {
    background: rgba(212,175,55,.12);
    color: var(--k-gold);
}

/* =========================
   PAGINATION
========================= */
.pagination .page-link {
    background: #111;
    color: var(--k-gold);
    border: 1px solid var(--k-border);
}

.pagination .active .page-link {
    background: var(--k-gold);
    color: #000;
}

/* ===== Kemetic Black Gold Theme ===== */

.kemetic-section {
    color: #EAEAEA;
}

/* Title */
.kemetic-title {
    color: #D4AF37;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* Card */
.kemetic-card {
    background: linear-gradient(145deg, #0b0b0b, #151515);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 14px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.6);
}

/* Table */
.kemetic-table {
    color: #EAEAEA;
}

.kemetic-table thead th {
    background: transparent;
    color: #D4AF37;
    font-weight: 600;
    border-bottom: 1px solid rgba(212,175,55,0.4);
}

.kemetic-table tbody tr {
    transition: all 0.25s ease;
}

.kemetic-table tbody tr:hover {
    background: rgba(212, 175, 55, 0.05);
}

/* Links */
.kemetic-link {
    color: #E6C87A;
    font-weight: 500;
}

.kemetic-link:hover {
    color: #FFD700;
    text-decoration: underline;
}

/* Status Pills */
.status-pill {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.status-gold {
    background: rgba(212,175,55,0.15);
    color: #D4AF37;
    border: 1px solid rgba(212,175,55,0.4);
}

.status-pending {
    background: rgba(255,193,7,0.15);
    color: #FFC107;
    border: 1px solid rgba(255,193,7,0.4);
}

/* Dropdown */
.kemetic-action-btn i {
    color: #D4AF37;
}

.kemetic-dropdown {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,0.3);
    border-radius: 10px;
}

.kemetic-dropdown-item {
    color: #EAEAEA;
    transition: all 0.2s ease;
}

.kemetic-dropdown-item:hover {
    background: rgba(212,175,55,0.1);
    color: #FFD700;
}


/* TABLE CARD */
.kemetic-table-card {
    background:linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:20px;
}

/* TABLE */
.kemetic-table {
    width:100%;
    border-collapse:separate;
    border-spacing:0 10px;
}
.kemetic-table thead th {
    color:#aaa; font-size:13px;
    font-weight:600; text-align:center;
}
.kemetic-table tbody tr {
    background:#0f0f0f;
    border:1px solid #262626;
}
.kemetic-table td {
    padding:14px;
    text-align:center;
    vertical-align:middle;
}
.kemetic-table td.text-left { text-align:left; }

/* TITLE CELL */
.kemetic-title-cell .title {
    color:#fff; font-weight:600;
}
.kemetic-title-cell small {
    color:#888; display:block;
}

/* STATUS */
.status-badge {
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
}
.status-badge.active {
    background:#1f3d2b; color:#2ecc71;
}
.status-badge.inactive {
    background:#3d1f1f; color:#e74c3c;
}

/* SUCCESS */
.success { color:#2ecc71; display:block; font-size:12px; }

/* ACTIONS */
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
@endpush

@section('content')
<div class="kemetic-page">

    {{-- ===== BLOG STATISTICS ===== --}}
    <section>
        <h2 class="section-title">{{ trans('update.blog_statistics') }}</h2>

        <div class="kemetic-stats mt-25 p-30" style="padding:10px;">
            <div class="row text-center">

                <div class="col-4 stat-item">
                    <img src="/assets/default/img/activity/46.svg" width="60">
                    <div class="stat-value mt-10">{{ $postsCount }}</div>
                    <div class="stat-label">{{ trans('update.articles') }}</div>
                </div>

                <div class="col-4 stat-item">
                    <img src="/assets/default/img/activity/47.svg" width="60">
                    <div class="stat-value mt-10">{{ $commentsCount }}</div>
                    <div class="stat-label">{{ trans('panel.comments') }}</div>
                </div>

                <div class="col-4 stat-item">
                    <img src="/assets/default/img/activity/48.svg" width="60">
                    <div class="stat-value mt-10">{{ $pendingPublishCount }}</div>
                    <div class="stat-label">{{ trans('update.pending_publish') }}</div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-35 kemetic-section">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title kemetic-title">
                {{ trans('update.articles') }}
            </h2>
        </div>

        @if($posts->count() > 0)

            <div class="kemetic-table-card mt-25">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                        <tr>
                            <th class="text-left">{{ trans('public.title') }}</th>
                            <th>{{ trans('public.category') }}</th>
                            <th>{{ trans('panel.comments') }}</th>
                            <th>{{ trans('update.visit_count') }}</th>
                            <th>{{ trans('public.status') }}</th>
                            <th>{{ trans('public.date_created') }}</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td class="text-left">
                                    <a href="{{ $post->getUrl() }}" target="_blank" class="kemetic-link">
                                        {{ $post->title }}
                                    </a>
                                </td>

                                <td>{{ $post->category->title }}</td>
                                <td>{{ $post->comments_count }}</td>
                                <td>{{ $post->visit_count }}</td>

                                <td>
                                    @if($post->status == 'publish')
                                        <span class="status-pill status-gold">
                                            {{ trans('public.published') }}
                                        </span>
                                    @else
                                        <span class="status-pill status-pending">
                                            {{ trans('public.pending') }}
                                        </span>
                                    @endif
                                </td>

                                <td>{{ dateTimeFormat($post->created_at, 'j M Y H:i') }}</td>

                                <td>
                                    <div class="dropdown kemetic-actions">
                                         <button data-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        

                                         <div class="dropdown-menu">
                                            <a href="/panel/blog/posts/{{ $post->id }}/edit"
                                            class="dropdown-item kemetic-dropdown-item">
                                                {{ trans('public.edit') }}
                                            </a>

                                            @can('panel_blog_delete_article')
                                                @include('web.default.panel.includes.content_delete_btn', [
                                                    'deleteContentUrl' => "/panel/blog/posts/{$post->id}/delete",
                                                    'deleteContentClassName' => 'dropdown-item kemetic-dropdown-item text-danger',
                                                    'deleteContentItem' => $post,
                                                ])
                                            @endcan
                                        </div>
                                    </div>
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
                'title' => trans('update.blog_post_no_result'),
                'hint' => nl2br(trans('update.blog_post_no_result_hint')),
                'btn' => ['url' => '/panel/blog/posts/new','text' => trans('update.create_a_post')]
            ])
        @endif
    </section>


    <div class="my-30">
        {{ $posts->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>

</div>
@endsection
