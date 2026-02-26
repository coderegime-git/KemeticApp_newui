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
    position: relative;
}

.section-title::after {
    content: "";
    display: block;
    width: 70px;
    height: 1px;
    margin-top: 6px;
    background: linear-gradient(to right, var(--k-gold), transparent);
}

/* =========================
   TABLE CARD
========================= */
.kemetic-table-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    padding: 20px;
    box-shadow: var(--k-shadow);
}

/* =========================
   TABLE
========================= */
.kemetic-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
}

.kemetic-table thead th {
    background: transparent;
    color: var(--k-gold);
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    border: none;
    padding: 15px;
}

.kemetic-table thead th.text-left {
    text-align: left;
}

.kemetic-table tbody tr {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
    border-radius: 14px;
    transition: all 0.3s ease;
}

.kemetic-table tbody tr:hover {
    background: #151515;
    box-shadow: 0 10px 28px rgba(212, 175, 55, 0.12);
}

.kemetic-table tbody td,
.kemetic-table tbody th {
    border: none;
    padding: 16px 18px;
    vertical-align: middle;
    color: var(--k-text);
    text-align: center;
}

.kemetic-table tbody td.text-left,
.kemetic-table tbody th.text-left {
    text-align: left;
}

/* =========================
   COURSE NAME CELL
========================= */
.kemetic-title-cell .title {
    color: #fff;
    font-weight: 600;
    display: block;
}

.kemetic-title-cell small {
    color: var(--k-muted);
    font-size: 12px;
    display: block;
    margin-top: 5px;
}

.kemetic-title-cell .text-gray {
    color: var(--k-muted) !important;
}

/* =========================
   BUTTONS
========================= */
.btn-kemetic {
    background: rgba(212, 175, 55, 0.12);
    border: 1px solid var(--k-border);
    color: var(--k-gold);
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 14px;
    transition: 0.25s ease;
}

.btn-kemetic:hover {
    background: var(--k-gold);
    color: #000;
}

/* =========================
   DROPDOWN / ACTIONS
========================= */
.kemetic-actions {
    position: relative;
}

.kemetic-actions > button {
    background: none;
    border: none;
    color: var(--k-gold);
    cursor: pointer;
    padding: 5px 10px;
}

.kemetic-actions .dropdown-menu {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
    border-radius: 12px;
    padding: 6px;
    min-width: 120px;
}

.kemetic-actions .dropdown-item {
    color: var(--k-text);
    font-size: 13px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.kemetic-actions .dropdown-item:hover {
    background: rgba(212, 175, 55, 0.12);
    color: var(--k-gold);
}

.kemetic-actions .dropdown-item.text-danger {
    color: #e74c3c !important;
}

.kemetic-actions .dropdown-item.text-danger:hover {
    background: rgba(231, 76, 60, 0.15);
    color: #ff6b6b !important;
}

/* =========================
   NO RESULT
========================= */
.kemetic-no-result {
    background: #0f0f0f;
    border: 1px dashed var(--k-border);
    border-radius: 18px;
    padding: 50px 20px;
    text-align: center;
}

.kemetic-no-result h2 {
    color: var(--k-gold);
    font-size: 20px;
    margin-top: 15px;
}

.kemetic-no-result p {
    color: var(--k-muted);
    font-size: 14px;
}

/* =========================
   PAGINATION
========================= */
.pagination .page-link {
    background: #111;
    color: var(--k-gold);
    border: 1px solid var(--k-border);
}

.pagination .page-item.active .page-link {
    background: var(--k-gold);
    border-color: var(--k-gold);
    color: #000;
}

.pagination .page-item.disabled .page-link {
    background: #1a1a1a;
    color: var(--k-muted);
    border-color: #2a2a2a;
}

/* =========================
   DATE
========================= */
.kemetic-date {
    font-size: 13px;
    color: var(--k-text);
}

/* =========================
   ATTACHMENT LINK
========================= */
.kemetic-attachment {
    color: var(--k-gold);
    text-decoration: none;
    font-weight: 500;
}

.kemetic-attachment:hover {
    text-decoration: underline;
    color: #ffd700;
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 768px) {
    .kemetic-table thead {
        display: none;
    }

    .kemetic-table tbody tr {
        display: block;
        margin-bottom: 15px;
    }

    .kemetic-table tbody td,
    .kemetic-table tbody th {
        display: block;
        text-align: left;
        padding: 12px;
    }

    .kemetic-table tbody td:before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
        color: var(--k-gold);
        margin-right: 10px;
    }
}
</style>
@endpush

@section('content')
<div class="kemetic-page">
    <section>
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="section-title">{{ trans('update.course_notes') }}</h2>
        </div>

        @if(!empty($personalNotes) and !$personalNotes->isEmpty())
            <div class="kemetic-table-card mt-25">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('product.course') }}</th>
                                <th class="text-left">{{ trans('public.file') }}</th>
                                <th>{{ trans('update.note') }}</th>

                                @if(!empty(getFeaturesSettings('course_notes_attachment')))
                                    <th>{{ trans('update.attachment') }}</th>
                                @endif

                                <th>{{ trans('public.date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personalNotes as $personalNote)
                                @php
                                    $item = $personalNote->getItem();
                                @endphp

                                <tr>
                                    <td class="text-left kemetic-title-cell">
                                        <span class="title">{{ $personalNote->course->title }}</span>
                                        <small>{{ trans('public.by') }} {{ $personalNote->course->teacher->full_name }}</small>
                                    </td>

                                    <td class="text-left kemetic-title-cell">
                                        @if(!empty($item))
                                            <span class="title">{{ $item->title }}</span>
                                            @if(!empty($item->chapter))
                                                <small>{{ trans('public.chapter') }}: {{ $item->chapter->title }}</small>
                                            @else
                                                <small>-</small>
                                            @endif
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <input type="hidden" value="{{ $personalNote->note }}">
                                        <button type="button" class="js-show-note btn-kemetic">{{ trans('public.view') }}</button>
                                    </td>

                                    @if(!empty(getFeaturesSettings('course_notes_attachment')))
                                        <td>
                                            @if(!empty($personalNote->attachment))
                                                <a href="/course/personal-notes/{{ $personalNote->id }}/download-attachment" class="kemetic-attachment">
                                                    {{ trans('home.download') }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif

                                    <td class="kemetic-date">{{ dateTimeFormat($personalNote->created_at,'j M Y | H:i') }}</td>

                                    <td>
                                        <div class="dropdown kemetic-actions">
                                            <button data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" height="20"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="{{ "{$personalNote->course->getLearningPageUrl()}?type={$personalNote->getItemType()}&item={$personalNote->targetable_id}" }}" target="_blank" class="dropdown-item">
                                                    {{ trans('public.view') }}
                                                </a>
                                                <a href="/panel/webinars/personal-notes/{{ $personalNote->id }}/delete" class="dropdown-item text-danger delete-action">
                                                    {{ trans('public.delete') }}
                                                </a>
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
            <div class="kemetic-no-result mt-25">
                <div class="no-result-logo">
                    <img src="/assets/default/img/no-results/personal_note.png" alt="{{ trans('update.no_notes') }}">
                </div>
                <div class="d-flex align-items-center flex-column mt-0 text-center">
                    <h2>{{ trans('update.no_notes') }}</h2>
                    <p class="mt-5 text-center">{{ trans("update.you_haven't_submitted_notes_for_your_courses") }}</p>
                </div>
            </div>
        @endif
    </section>

    <div class="my-30">
        {{ $personalNotes->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>
</div>
@endsection

@push('scripts_bottom')
    <script>
        var noteLang = '{{ trans('update.note') }}';
    </script>
    <script src="/assets/default/js/panel/personal_note.min.js"></script>
@endpush