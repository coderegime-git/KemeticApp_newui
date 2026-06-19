@extends(getTemplate().'.layouts.app')
@section('title', 'Media Kit' . (!empty($generalSettings['site_name']) ? ' | ' . $generalSettings['site_name'] : ''))
 
@section('fav_icon')
    @if(!empty($generalSettings['fav_icon']))
        <link href="{{ $generalSettings['fav_icon'] }}" rel="icon" type="image/png">
    @endif
@endsection
@push('styles_top')
        <link href="{{ $generalSettings['fav_icon'] }}" rel="icon" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')

<style>
    /* ── GLOBAL ── */
    :root {
        --gold:        #F2C94C;
        --gold-dim:    rgba(242,201,76,.15);
        --gold-mid:    rgba(242,201,76,.35);
        --surface:     #121212;
        --surface-alt: #0a0a0a;
        --border:      #262626;
        --text-muted:  #888;
        --text-sub:    #b5b5b5;
        --text:        #eaeaea;
        --radius-lg:   18px;
        --radius-md:   14px;
        --radius-sm:   10px;
    }

    body { background: #0a0a0a; color: var(--text); }

    /* ── STAT SECTION ── */
    .mk-stat-section { margin-top: 25px; }

    .mk-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 18px;
        letter-spacing: .3px;
    }

    .mk-stat-card {
        background: linear-gradient(180deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 26px 18px;
    }

    .mk-stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .mk-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--gold-dim);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--gold);
        margin-bottom: 4px;
    }

    .mk-stat-value {
        font-size: 30px;
        font-weight: 700;
        color: var(--gold);
    }

    .mk-stat-label {
        font-size: 13px;
        color: var(--text-muted);
    }

    /* ── FILTER SECTION ── */
    .mk-filter-card {
        background: linear-gradient(180deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 26px 22px;
    }

    .mk-label {
        font-size: 13px;
        color: var(--text-sub);
        margin-bottom: 6px;
        display: block;
    }

    .mk-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #0f0f0f;
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 10px 14px;
    }

    .mk-input-group i { color: var(--gold); }

    .mk-input {
        width: 100%;
        background: transparent;
        border: none;
        color: #fff;
        outline: none;
        font-size: 14px;
    }

    .mk-select {
        width: 100%;
        background: #0f0f0f;
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 10px 14px;
        color: #fff;
        font-size: 14px;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
    }

    .mk-select option { background: #0f0f0f; }

    .mk-select-wrap {
        position: relative;
    }

    .mk-select-wrap::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gold);
        pointer-events: none;
        font-size: 13px;
    }

    /* ── BUTTON ── */
    .mk-btn {
        background: linear-gradient(135deg, var(--gold), #d4af37);
        border: none;
        border-radius: var(--radius-md);
        padding: 12px;
        font-weight: 700;
        color: #000;
        transition: .3s ease;
        cursor: pointer;
    }

    .mk-btn:hover {
        background: linear-gradient(135deg, #d4af37, var(--gold));
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(242,201,76,.4);
    }

    .mk-btn-sm {
        padding: 8px 16px;
        font-size: 13px;
        border-radius: var(--radius-sm);
    }

    /* ── SIDEBAR (left categories) ── */
    .mk-sidebar {
        background: linear-gradient(180deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 20px 14px;
        position: sticky;
        top: 90px;
    }

    .mk-nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 12px;
        color: var(--text-sub);
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: .25s ease;
        margin-bottom: 4px;
        cursor: pointer;
    }

    .mk-nav-link i { font-size: 13px; color: var(--gold); opacity: .5; transition: .25s; }

    .mk-nav-link:hover,
    .mk-nav-link.active {
        background: var(--gold-dim);
        color: var(--gold);
    }

    .mk-nav-link.active i,
    .mk-nav-link:hover i { opacity: 1; }

    /* ── CARD GRID ── */
    .mk-card {
        background: linear-gradient(180deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: .3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .mk-card:hover {
        border-color: var(--gold-mid);
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,.4);
    }

    .mk-card-body {
        padding: 20px 18px 14px;
        flex: 1;
    }

    .mk-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 6px;
    }

    .mk-card-desc {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 10px;
        line-height: 1.5;
    }

    .mk-course-link {
        font-size: 12px;
        color: var(--gold);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 12px;
        transition: .2s;
    }

    .mk-course-link:hover { color: #fff; }

    .mk-video-wrap {
        border-radius: 10px;
        overflow: hidden;
        background: #000;
        aspect-ratio: 16/9;
    }

    .mk-video-wrap iframe,
    .mk-video-wrap video {
        width: 100%;
        height: 100%;
        display: block;
        border: none;
    }

    .mk-card-footer {
        padding: 12px 18px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 10px;
    }

    .mk-download-btn {
        flex: 1;
        background: linear-gradient(135deg, var(--gold), #d4af37);
        border: none;
        border-radius: var(--radius-sm);
        padding: 9px;
        font-size: 13px;
        font-weight: 700;
        color: #000;
        cursor: pointer;
        transition: .3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .mk-download-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(242,201,76,.4);
    }

    .mk-share-btn {
        flex: 1;
        background: transparent;
        border: 1px solid var(--gold-mid);
        border-radius: var(--radius-sm);
        padding: 9px;
        font-size: 13px;
        font-weight: 600;
        color: var(--gold);
        cursor: pointer;
        transition: .3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .mk-share-btn:hover {
        background: var(--gold-dim);
        border-color: var(--gold);
    }

    /* ── CATEGORY BADGE ── */
    .mk-cat-badge {
        display: inline-block;
        background: var(--gold-dim);
        color: var(--gold);
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 8px;
        letter-spacing: .3px;
    }

    /* ── RIGHT TOOLS SIDEBAR ── */
    .mk-tools-sidebar {
        background: linear-gradient(180deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 20px 16px;
        position: sticky;
        top: 90px;
    }

    .mk-tools-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mk-tool-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 10px;
        color: var(--text-sub);
        font-size: 13px;
        text-decoration: none;
        transition: .25s;
        margin-bottom: 4px;
    }

    .mk-tool-item i { color: var(--gold); opacity: .6; width: 16px; text-align: center; transition: .25s; }

    .mk-tool-item:hover {
        background: var(--gold-dim);
        color: var(--gold);
    }

    .mk-tool-item:hover i { opacity: 1; }

    /* ── UPLOAD MODAL ── */
    .modal-content {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
    }

    .modal-header {
        border-bottom: 1px solid var(--border);
        padding: 18px 22px;
    }

    .modal-title {
        color: var(--gold);
        font-weight: 700;
    }

    .modal-body { padding: 22px; }

    .modal-footer {
        border-top: 1px solid var(--border);
        padding: 14px 22px;
    }

    .mk-form-label {
        font-size: 13px;
        color: var(--text-sub);
        margin-bottom: 6px;
        display: block;
    }

    .mk-form-control {
        width: 100%;
        background: #0f0f0f;
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 10px 14px;
        color: #fff;
        font-size: 14px;
        outline: none;
        transition: .2s;
    }

    .mk-form-control:focus {
        border-color: var(--gold-mid);
        box-shadow: 0 0 0 3px rgba(242,201,76,.08);
    }

    .mk-form-control::placeholder { color: #555; }

    textarea.mk-form-control { resize: vertical; min-height: 90px; }

    select.mk-form-control option { background: #0f0f0f; }

    .btn-close-custom {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 18px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 8px;
        transition: .2s;
    }

    .btn-close-custom:hover { color: var(--gold); background: var(--gold-dim); }

    /* ── UPLOAD STATUS ── */
    .mk-status-success {
        background: #1f3d2b;
        border: 1px solid #2ecc71;
        color: #2ecc71;
    }

    .mk-status-error {
        background: #3d1f1f;
        border: 1px solid #e74c3c;
        color: #e74c3c;
    }

    /* ── NO DATA ── */
    .mk-no-data {
        background: linear-gradient(180deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 50px 30px;
        text-align: center;
    }

    .mk-no-data i {
        font-size: 40px;
        color: var(--gold);
        opacity: .4;
        margin-bottom: 16px;
        display: block;
    }

    .mk-no-data h4 { color: var(--gold); font-size: 18px; margin-bottom: 8px; }
    .mk-no-data p  { color: var(--text-muted); font-size: 14px; }

    /* ── ADD MEDIA BTN ── */
    .mk-add-btn {
        position: fixed;
        top: 80px;
        right: 24px;
        z-index: 999;
        background: linear-gradient(135deg, var(--gold), #d4af37);
        color: #000;
        font-weight: 700;
        font-size: 13px;
        border: none;
        border-radius: 12px;
        padding: 10px 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 18px rgba(242,201,76,.3);
        transition: .3s;
    }

    .mk-add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(242,201,76,.45);
    }

    /* ── MOBILE FOOTER TOOLS ── */
    .mk-footer-tools {
        background: linear-gradient(180deg, var(--surface), var(--surface-alt));
        border-top: 1px solid var(--border);
        padding: 18px;
        margin-top: 30px;
    }

    .mk-footer-tools-title {
        color: var(--gold);
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .mk-footer-tool-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--text-sub);
        font-size: 13px;
        padding: 6px 0;
    }

    .mk-footer-tool-item i { color: var(--gold); opacity: .7; }

    /* ── ANIMATIONS ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .card-item { animation: fadeInUp .4s ease both; }
    .card-item:nth-child(1) { animation-delay: .05s; }
    .card-item:nth-child(2) { animation-delay: .10s; }
    .card-item:nth-child(3) { animation-delay: .15s; }
    .card-item:nth-child(4) { animation-delay: .20s; }
    .card-item:nth-child(5) { animation-delay: .25s; }
    .card-item:nth-child(6) { animation-delay: .30s; }

    /* ── RESPONSIVE ── */
    @media (max-width: 991px) {
        .mk-add-btn { top: auto; bottom: 20px; right: 20px; }
        .mk-stat-value { font-size: 22px; }
    }
</style>

{{-- Floating Add Media Button --}}
<button class="mk-add-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
    <i class="fas fa-plus"></i> Add Media
</button>

<div class="container-fluid py-4 px-3 px-md-4">

    {{-- ── STATISTICS ── --}}
    <section class="mk-stat-section">
        <h2 class="mk-title"><i class="fa-solid fa-chart-bar me-2" style="font-size:18px;opacity:.7"></i> Media Kit Overview</h2>
        <div class="mk-stat-card">
            <div class="row text-center g-3">
                <div class="col-4">
                    <div class="mk-stat-item">
                        <div class="mk-stat-icon"><i class="fa-solid fa-photo-film"></i></div>
                        <div class="mk-stat-value">{{ $mediaKit->count() }}</div>
                        <div class="mk-stat-label">Total Media</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mk-stat-item">
                        <div class="mk-stat-icon"><i class="fa-solid fa-layer-group"></i></div>
                        <div class="mk-stat-value">{{ $categories->count() }}</div>
                        <div class="mk-stat-label">Categories</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mk-stat-item">
                        <div class="mk-stat-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                        <div class="mk-stat-value">{{ $mediaTools->count() }}</div>
                        <div class="mk-stat-label">Tools</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── MAIN LAYOUT ── --}}
    <div class="row mt-4 g-4">

        {{-- LEFT: Category Sidebar --}}
        <div class="col-12 col-lg-2 d-none d-lg-block">
            <div class="mk-sidebar">
                <p class="mk-label mb-12" style="color:var(--gold);font-weight:700;font-size:13px;letter-spacing:.5px;text-transform:uppercase;">Categories</p>
                <ul class="list-unstyled mb-0">
                    <li>
                        <a class="mk-nav-link active" data-tab="all" href="#">
                            <i class="fa-solid fa-border-all"></i> All
                        </a>
                    </li>
                    @foreach($categories as $category)
                    <li>
                        <a class="mk-nav-link" data-tab="cat-{{ $category->id }}" href="#">
                            <i class="fa-solid fa-folder"></i> {{ $category->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- MOBILE: Category Tabs --}}
        <div class="col-12 d-lg-none">
            <div style="overflow-x:auto;white-space:nowrap;padding-bottom:4px;">
                <a class="mk-nav-link active d-inline-flex" data-tab="all" href="#" style="display:inline-flex!important">
                    <i class="fa-solid fa-border-all"></i> All
                </a>
                @foreach($categories as $category)
                <a class="mk-nav-link d-inline-flex" data-tab="cat-{{ $category->id }}" href="#" style="display:inline-flex!important">
                    <i class="fa-solid fa-folder"></i> {{ $category->title }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- CENTER: Media Cards --}}
        <div class="col-12 col-lg-8">

            {{-- No data --}}
            <div class="mk-no-data d-none" id="noData">
                <i class="fa-solid fa-photo-film"></i>
                <h4>No Media Found</h4>
                <p>No media available for this category yet.</p>
            </div>

            <div class="row g-3" id="cardsContainer">
                @foreach($mediaKit as $media)
                <div class="col-12 col-md-6 col-xl-4 card-item" data-category="cat-{{ $media->category_id }}">
                    <div class="mk-card">
                        <div class="mk-card-body">
                            {{-- Category badge --}}
                            @if(isset($media->category))
                                <span class="mk-cat-badge">{{ $media->category->title ?? '' }}</span>
                            @endif

                            <h5 class="mk-card-title">{{ $media->title }}</h5>
                            <p class="mk-card-desc">{{ \Illuminate\Support\Str::limit($media->description, 70, '...') }}</p>

                            <a class="mk-course-link" href="{{ $media->course_link }}" target="_blank">
                                <i class="fa-solid fa-link" style="font-size:11px;"></i> Course Link
                            </a>

                            {{-- Video --}}
                            <div class="mk-video-wrap mt-2">
                                @if(Str::contains($media->video_path, 'youtube.com'))
                                    <iframe src="{{ $media->video_path }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    <video controls>
                                        <source src="{{ $media->video_path }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        </div>

                        <div class="mk-card-footer">
                            <button class="mk-download-btn download-btn" data-url="{{ asset($media->video_path) }}">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button class="mk-share-btn js-share-blog">
                                <i class="fa-solid fa-share-nodes"></i> Share
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT: Tools Sidebar --}}
        <div class="col-12 col-lg-2 d-none d-lg-block">
            <div class="mk-tools-sidebar">
                <div class="mk-tools-title">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Tools & Resources
                </div>
                <ul class="list-unstyled mb-0">
                    @foreach($mediaTools as $mediaTool)
                    <li>
                        <a href="{{ $mediaTool->link }}" class="mk-tool-item" target="_blank">
                            <i class="{{ $mediaTool->icon }}"></i>
                            <span>{{ $mediaTool->name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>{{-- /row --}}
</div>


{{-- ── UPLOAD MODAL ── --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true" style="z-index:99999">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="fa-solid fa-cloud-arrow-up me-2" style="opacity:.8"></i> Upload Media
                </h5>
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadMediaForm" action="/create-media" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="mk-form-label">Category</label>
                            <select name="category" class="mk-form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="mk-form-label">Title</label>
                            <input type="text" name="title" class="mk-form-control" placeholder="Enter media title" required>
                        </div>

                        <div class="col-12">
                            <label class="mk-form-label">Description</label>
                            <textarea name="description" class="mk-form-control" placeholder="Describe this media..." required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="mk-form-label">Course Link</label>
                            <div class="mk-input-group">
                                <i class="fa-solid fa-link" style="font-size:13px;"></i>
                                <input type="url" name="courseLink" class="mk-input" placeholder="https://..." required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="mk-form-label">Upload Video</label>
                            <input type="file" name="video" class="mk-form-control" accept="video/mp4,video/webm,video/ogg" required
                                   style="padding:8px 14px;cursor:pointer;">
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div id="uploadProgressWrap" style="display:none;margin-top:18px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <span style="font-size:13px;color:var(--text-sub);">Uploading...</span>
                            <span id="uploadProgressText" style="font-size:13px;font-weight:700;color:var(--gold);">0%</span>
                        </div>
                        <div style="background:#1a1a1a;border-radius:30px;height:8px;overflow:hidden;border:1px solid var(--border);">
                            <div id="uploadProgressBar" style="height:100%;width:0%;background:linear-gradient(90deg,#d4af37,var(--gold));border-radius:30px;transition:width .2s ease;"></div>
                        </div>
                    </div>

                    {{-- Status Message --}}
                    <div id="uploadStatus" style="display:none;margin-top:14px;padding:12px 16px;border-radius:12px;font-size:14px;font-weight:500;"></div>

                    <div class="modal-footer mt-3 px-0 pb-0">
                        <button type="button" class="mk-share-btn mk-btn-sm" data-bs-dismiss="modal" style="min-width:90px;">
                            Close
                        </button>
                        <button type="submit" id="uploadSubmitBtn" class="mk-download-btn mk-btn-sm" style="min-width:130px;max-width:160px;">
                            <i class="fas fa-cloud-upload-alt"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- ── MOBILE FOOTER TOOLS ── --}}
<div class="mk-footer-tools d-lg-none">
    <div class="mk-footer-tools-title">
        <i class="fa-solid fa-screwdriver-wrench me-2"></i> Tools & Resources
    </div>
    <div class="row g-2">
        @foreach($mediaTools as $mediaTool)
        <div class="col-6 col-sm-4">
            <a href="{{ $mediaTool->link }}" class="mk-footer-tool-item text-decoration-none">
                <i class="{{ $mediaTool->icon }}"></i>
                <span>{{ $mediaTool->name }}</span>
            </a>
        </div>
        @endforeach
    </div>
</div>


@endsection

@include('web.default.blog.share_media_kit_modal')

@push('scripts_bottom')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/default/js/parts/blog.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    /* ════════════════════════════════════════
       1. CATEGORY FILTER
    ════════════════════════════════════════ */
    const tabs   = document.querySelectorAll(".mk-nav-link");
    const cards  = document.querySelectorAll(".card-item");
    const noData = document.getElementById("noData");

    tabs.forEach(tab => {
        tab.addEventListener("click", function (e) {
            e.preventDefault();
            const selected = this.getAttribute("data-tab");

            tabs.forEach(t => t.classList.remove("active"));
            this.classList.add("active");

            // Pause all videos when switching categories
            pauseAllVideos();

            let visible = 0;
            cards.forEach(card => {
                const show = selected === "all" || card.getAttribute("data-category") === selected;
                card.style.display = show ? "block" : "none";
                if (show) visible++;
            });

            noData.classList.toggle("d-none", visible > 0);
        });
    });


    /* ════════════════════════════════════════
       2. ONE-VIDEO-AT-A-TIME PLAY
       — native <video> elements only
       — YouTube iframes: postMessage pause
    ════════════════════════════════════════ */
    function pauseAllVideos(exceptVideo) {
        // Pause all native videos
        document.querySelectorAll(".mk-video-wrap video").forEach(v => {
            if (v !== exceptVideo) {
                v.pause();
            }
        });

        // Pause all YouTube iframes (except the one currently playing)
        document.querySelectorAll(".mk-video-wrap iframe").forEach(iframe => {
            if (iframe !== exceptVideo) {
                try {
                    iframe.contentWindow.postMessage(
                        '{"event":"command","func":"pauseVideo","args":""}',
                        '*'
                    );
                } catch(e) {}
            }
        });
    }

    // Listen to native video play events
    document.querySelectorAll(".mk-video-wrap video").forEach(video => {
        video.addEventListener("play", function () {
            pauseAllVideos(this);
        });
    });

    // For YouTube iframes: add ?enablejsapi=1 dynamically so postMessage works
    document.querySelectorAll(".mk-video-wrap iframe").forEach(iframe => {
        let src = iframe.src || "";
        if (src.includes("youtube.com") && !src.includes("enablejsapi")) {
            iframe.src = src + (src.includes("?") ? "&" : "?") + "enablejsapi=1";
        }
    });

    // Listen for YouTube play via postMessage
    window.addEventListener("message", function (e) {
        if (!e.data) return;
        try {
            const data = (typeof e.data === "string") ? JSON.parse(e.data) : e.data;
            // YouTube sends info events; playerState 1 = playing
            if (data.event === "infoDelivery" && data.info && data.info.playerState === 1) {
                const playingIframe = document.querySelector(
                    `.mk-video-wrap iframe[src*="${e.origin.replace("https://","").replace("http://","")}"]`
                );
                pauseAllVideos(playingIframe || null);
            }
        } catch(err) {}
    });


    /* ════════════════════════════════════════
       3. DOWNLOAD BUTTON
    ════════════════════════════════════════ */
    document.querySelectorAll(".download-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            const url = this.getAttribute("data-url");
            if (!url) { alert("File not found!"); return; }
            const a = document.createElement("a");
            a.href = url;
            a.download = url.split('/').pop();
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    });


    /* ════════════════════════════════════════
       4. AJAX UPLOAD WITH PROGRESS BAR
    ════════════════════════════════════════ */
    const uploadForm     = document.getElementById("uploadMediaForm");
    const uploadBtn      = document.getElementById("uploadSubmitBtn");
    const progressWrap   = document.getElementById("uploadProgressWrap");
    const progressBar    = document.getElementById("uploadProgressBar");
    const progressText   = document.getElementById("uploadProgressText");
    const uploadStatus   = document.getElementById("uploadStatus");

    if (uploadForm) {
        uploadForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(uploadForm);

            // UI: disable button, show progress
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            progressWrap.style.display  = "block";
            uploadStatus.style.display  = "none";
            progressBar.style.width     = "0%";
            progressText.textContent    = "0%";

            const xhr = new XMLHttpRequest();

            // Track upload progress
            xhr.upload.addEventListener("progress", function (e) {
                if (e.lengthComputable) {
                    const pct = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width  = pct + "%";
                    progressText.textContent = pct + "%";
                }
            });

            xhr.addEventListener("load", function () {
                progressBar.style.width  = "100%";
                progressText.textContent = "100%";

                if (xhr.status === 200 || xhr.status === 201 || xhr.status === 302) {
                    showUploadStatus("success", '<i class="fas fa-check-circle me-2"></i>Media uploaded successfully!');
                    uploadForm.reset();
                    // Reload page after 1.5s to show new card
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    let msg = "Upload failed. Please try again.";
                    try {
                        const res = JSON.parse(xhr.responseText);
                        if (res.message) msg = res.message;
                        if (res.errors) msg = Object.values(res.errors).flat().join(" ");
                    } catch(err) {}
                    showUploadStatus("error", '<i class="fas fa-exclamation-circle me-2"></i>' + msg);
                    resetUploadBtn();
                }
            });

            xhr.addEventListener("error", function () {
                showUploadStatus("error", '<i class="fas fa-exclamation-circle me-2"></i>Network error. Please try again.');
                resetUploadBtn();
            });

            xhr.open("POST", uploadForm.action);
            xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]')?.content
                || "{{ csrf_token() }}");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.send(formData);
        });
    }

    function showUploadStatus(type, html) {
        uploadStatus.style.display      = "block";
        uploadStatus.className          = "mk-upload-status " + (type === "success" ? "mk-status-success" : "mk-status-error");
        uploadStatus.innerHTML          = html;
        progressWrap.style.display      = "none";
    }

    function resetUploadBtn() {
        uploadBtn.disabled     = false;
        uploadBtn.innerHTML    = '<i class="fas fa-cloud-upload-alt"></i> Upload';
    }

    // Reset modal state when closed
    const uploadModal = document.getElementById("uploadModal");
    if (uploadModal) {
        uploadModal.addEventListener("hidden.bs.modal", function () {
            if (uploadForm) uploadForm.reset();
            progressWrap.style.display = "none";
            uploadStatus.style.display = "none";
            progressBar.style.width    = "0%";
            progressText.textContent   = "0%";
            resetUploadBtn();
        });
    }

});
</script>
@endpush