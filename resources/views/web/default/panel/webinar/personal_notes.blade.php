@extends('web.default.layouts.newapp')

<style>
    /* ======================================================
   KEMETIC COURSE NOTES
   Black • Gold • Learning Dashboard
====================================================== */

:root {
    --k-bg: #0b0b0b;
    --k-card: #141414;
    --k-border: rgba(242, 201, 76, 0.28);
    --k-gold: #F2C94C;
    --k-gold-soft: rgba(242, 201, 76, 0.18);
    --k-text: #e8e8e8;
    --k-muted: #9b9b9b;
    --k-radius: 16px;
}

/* SECTION TITLE */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--k-gold);
    letter-spacing: 0.4px;
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

/* CARD */
.panel-section-card {
    background: linear-gradient(180deg, #161616, #0f0f0f);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.75);
}

/* TABLE */
.custom-table {
    border-collapse: separate;
    border-spacing: 0 12px;
}

.custom-table thead th {
    font-size: 13px;
    font-weight: 600;
    color: var(--k-gold);
    border: none;
}

.custom-table tbody tr {
    background: #101010;
    border-radius: 14px;
    transition: 0.3s ease;
}

.custom-table tbody tr:hover {
    background: #151515;
    box-shadow: 0 10px 28px rgba(242, 201, 76, 0.12);
}

.custom-table tbody td,
.custom-table tbody th {
    border: none;
    padding: 16px 18px;
    vertical-align: middle;
    color: var(--k-text);
}

/* COURSE NAME */
.custom-table th span:first-child {
    font-weight: 600;
    color: var(--k-text);
}

.custom-table .text-gray {
    color: var(--k-muted) !important;
}

/* BUTTONS */
.btn-gray200 {
    background: rgba(242, 201, 76, 0.12);
    border: 1px solid var(--k-border);
    color: var(--k-gold);
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 14px;
    transition: 0.25s ease;
}

.btn-gray200:hover {
    background: var(--k-gold);
    color: #000;
}

/* DROPDOWN */
.table-actions .dropdown-toggle {
    color: var(--k-gold);
}

.dropdown-menu {
    background: #141414;
    border: 1px solid var(--k-border);
    border-radius: 12px;
    padding: 6px;
}

.dropdown-menu a {
    color: var(--k-text);
    font-size: 13px;
    padding: 8px 12px;
    border-radius: 8px;
}

.dropdown-menu a:hover {
    background: var(--k-gold-soft);
    color: var(--k-gold);
}

/* DATE */
.custom-table td.align-middle {
    font-size: 13px;
}

/* NO RESULT */
.no-result {
    background: #101010;
    border: 1px dashed var(--k-border);
    border-radius: 18px;
    padding: 50px 20px;
}

.no-result h2 {
    color: var(--k-gold);
    font-size: 20px;
    margin-top: 15px;
}

.no-result p {
    color: var(--k-muted);
    font-size: 14px;
}

/* PAGINATION */
.pagination .page-link {
    background: #141414;
    border: 1px solid #2a2a2a;
    color: var(--k-text);
}

.pagination .page-item.active .page-link {
    background: var(--k-gold);
    border-color: var(--k-gold);
    color: #000;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .custom-table thead {
        display: none;
    }

    .custom-table tbody tr {
        display: block;
        margin-bottom: 15px;
    }

    .custom-table tbody td,
    .custom-table tbody th {
        display: block;
        text-align: left;
    }
}

</style>

@section('content')

    <section>
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="section-title">{{ trans('update.course_notes') }}</h2>
        </div>

        @if(!empty($personalNotes) and !$personalNotes->isEmpty())

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table custom-table text-center ">
                                <thead>
                                <tr>
                                    <th class="text-left">{{ trans('product.course') }}</th>
                                    <th class="text-left">{{ trans('public.file') }}</th>
                                    <th class="text-center">{{ trans('update.note') }}</th>

                                    @if(!empty(getFeaturesSettings('course_notes_attachment')))
                                        <th class="text-center">{{ trans('update.attachment') }}</th>
                                    @endif

                                    <th class="text-center">{{ trans('public.date') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($personalNotes as $personalNote)
                                    @php
                                        $item = $personalNote->getItem();
                                    @endphp

                                    <tr>
                                        <th class="text-left">
                                            <span class="d-block">{{ $personalNote->course->title }}</span>
                                            <span class="d-block font-12 text-gray mt-5">{{ trans('public.by') }} {{ $personalNote->course->teacher->full_name }}</span>
                                        </th>

                                        <th class="text-left">
                                            @if(!empty($item))
                                                <span class="d-block">{{ $item->title }}</span>

                                                @if(!empty($item->chapter))
                                                    <span class="d-block font-12 text-gray mt-5">{{ trans('public.chapter') }}: {{ $item->chapter->title }}</span>
                                                @else
                                                    -
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </th>

                                        <td class=" text-center">
                                            <input type="hidden" value="{{ $personalNote->note }}">
                                            <button type="button" class="js-show-note btn btn-sm btn-gray200">{{ trans('public.view') }}</button>
                                        </td>

                                        @if(!empty(getFeaturesSettings('course_notes_attachment')))
                                            <td class="align-middle">
                                                @if(!empty($personalNote->attachment))
                                                    <a href="/course/personal-notes/{{ $personalNote->id }}/download-attachment" class="btn btn-sm btn-gray200">{{ trans('home.download') }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endif

                                        <td class="align-middle">{{ dateTimeFormat($personalNote->created_at,'j M Y | H:i') }}</td>

                                        <td class="align-middle text-right">

                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu">

                                                    <a href="{{ "{$personalNote->course->getLearningPageUrl()}?type={$personalNote->getItemType()}&item={$personalNote->targetable_id}" }}" target="_blank" class="d-block text-left btn btn-sm btn-transparent">{{ trans('public.view') }}</a>

                                                    <a href="/panel/webinars/personal-notes/{{ $personalNote->id }}/delete" class="delete-action d-block text-left btn btn-sm btn-transparent">{{ trans('public.delete') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="no-result my-50 d-flex align-items-center justify-content-center flex-column">
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
@endsection

@push('scripts_bottom')
    <script>
        var noteLang = '{{ trans('update.note') }}';
    </script>

    <script src="/assets/default/js/panel/personal_note.min.js"></script>
@endpush
