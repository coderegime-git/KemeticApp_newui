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
/* ==============================
   KEMETIC BLACK GOLD THEME
============================== */

.kemetic-card {
    background: linear-gradient(180deg, #0b0b0b, #121212);
    border: 1px solid rgba(212,175,55,0.25);
    border-radius: 14px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6);
}

.kemetic-body {
    padding: 28px;
}

/* Labels */
.kemetic-label {
    color: #d4af37;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

/* Inputs */
.kemetic-input {
    background-color: #0f0f0f !important;
    color: #f5f5f5 !important;
    border: 1px solid rgba(212,175,55,0.35);
    border-radius: 10px;
    height: 44px;
}

.kemetic-input::placeholder {
    color: #8b8b8b;
}

.kemetic-input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212,175,55,0.25);
    background-color: #0f0f0f;
}

/* Select2 override */
.select2-container--default .select2-selection--single {
    background-color: #0f0f0f;
    border: 1px solid rgba(212,175,55,0.35);
    height: 44px;
    border-radius: 10px;
}

.select2-selection__rendered {
    color: #f5f5f5 !important;
    line-height: 42px !important;
}

.select2-selection__arrow {
    height: 42px !important;
}

/* Button */
.kemetic-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    font-weight: 700;
    border-radius: 12px;
    height: 46px;
    letter-spacing: 0.6px;
    transition: all 0.25s ease;
    box-shadow: 0 6px 18px rgba(212,175,55,0.35);
}

.kemetic-btn:hover {
    background: linear-gradient(135deg, #e6c45c, #d4af37);
    transform: translateY(-1px);
    box-shadow: 0 10px 24px rgba(212,175,55,0.45);
}


</style>
@endpush

@section('content')
<div class="kemetic-page">

    <section class="card kemetic-card">
        <div class="card-body kemetic-body">
            <form action="/panel/book" method="get" class="mb-0">
                <div class="row">

                    <!-- Search -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('admin/main.search') }}</label>
                            <input name="title" type="text"
                                class="form-control kemetic-input"
                                value="{{ request()->get('title') }}"
                                placeholder="Search book title...">
                        </div>
                    </div>

                    <!-- Start Date -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('admin/main.start_date') }}</label>
                            <input type="date"
                                class="form-control kemetic-input text-center"
                                name="from"
                                value="{{ request()->get('from') }}">
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('admin/main.end_date') }}</label>
                            <input type="date"
                                class="form-control kemetic-input text-center"
                                name="to"
                                value="{{ request()->get('to') }}">
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('admin/main.category') }}</label>
                            <select name="category_id"
                                    data-plugin-selectTwo
                                    class="form-control kemetic-input">
                                <option value="">{{ trans('admin/main.all_categories') }}</option>
                                @foreach($bookCategories as $category)
                                    <option value="{{ $category->id }}"
                                        @if(request()->get('category_id') == $category->id) selected @endif>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Author -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('admin/main.author') }}</label>
                            <select name="author_id"
                                    data-plugin-selectTwo
                                    class="form-control kemetic-input">
                                <option value="">{{ trans('admin/main.all_authors') }}</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}"
                                        @if(request()->get('author_id') == $author->id) selected @endif>
                                        {{ $author->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn kemetic-btn w-100">
                            {{ trans('admin/main.show_results') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </section>

    

    <section class="mt-35 kemetic-section">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title kemetic-title">
                Book
            </h2>
        </div>

        @if($books->count() > 0)

            <div class="kemetic-table-card mt-25">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-left">Title</th>
                            <th class="text-center">Price</th>
                            <!-- <th class="text-center">Created At</th>
                            <th class="text-center">Updated At</th> -->
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($books as $book)
                            @php
                                $translation = $book->translation;
                            @endphp
                            <tr>
                                <td>{{ $book->id }}</td>
                                <td class="text-center">
                                    {{ $translation ? $translation->title : 'No translation' }}
                                </td>
                                <td class="text-center">{{ $book->price }}</td>
                                <!-- <td class="text-center">{{ dateTimeFormat($book->created_at, 'Y M j | H:i') }}</td>
                                <td class="text-center">{{ dateTimeFormat($book->updated_at, 'Y M j | H:i') }}</td> -->
                                <td>

                                    <div class="dropdown kemetic-actions">
                                        <button data-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        

                                        <div class="dropdown-menu">
                                            <a href="/panel/book/{{ $book->id }}/edit"
                                            class="dropdown-item kemetic-dropdown-item">
                                                {{ trans('public.edit') }}
                                            </a>

                                            <a href="/panel/book/{{ $book->id }}/delete"
                                            class="dropdown-item kemetic-dropdown-item">
                                                {{ trans('public.delete') }}
                                            </a>

                                            
                                            <!-- @include('web.default.panel.includes.content_delete_btn', [
                                                'deleteContentUrl' => "/panel/book/{$book->id}/delete",
                                                'deleteContentClassName' => 'dropdown-item kemetic-dropdown-item text-danger',
                                                'deleteContentItem' => $book,
                                            ]) -->
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
                'title' => trans('No Book Found'),
                'hint' => nl2br(trans('No Book Found'))
            ])
        @endif
    </section>


    <div class="my-30">
        {{ $books->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>

</div>
@endsection
