@extends('web.default.layouts.newapp')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

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
            margin-bottom: 1rem;
            font-size: 1.5rem;
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
           STATS CARDS
        ========================= */
        .activities-container {
            background: linear-gradient(145deg, #161616, #0c0c0c);
            border-radius: var(--k-radius);
            border: 1px solid var(--k-border);
            box-shadow: var(--k-shadow);
            padding: 20px;
        }

        .activities-container .d-flex {
            padding: 1rem;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: var(--k-radius);
            transition: all 0.3s ease;
        }

        .activities-container .d-flex:hover {
            background: rgba(212,175,55,0.05);
            transform: translateY(-2px);
        }

        .activities-container img {
            filter: brightness(1.2);
            margin-bottom: 0.5rem;
            width: 64px;
            height: 64px;
        }

        .text-dark-blue {
            color: var(--k-gold) !important;
            font-weight: 700;
            font-size: 30px;
        }

        .text-gray {
            color: var(--k-muted) !important;
            font-size: 16px;
        }

        /* =========================
           FORM CARD
        ========================= */
        .panel-section-card {
            background: linear-gradient(180deg, #121212, #0a0a0a);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            padding: 25px;
            box-shadow: var(--k-shadow);
        }

        /* =========================
           FORM STYLING
        ========================= */
        .form-group label {
            color: var(--k-gold);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            letter-spacing: 0.3px;
        }

        .form-control,
        .input-group-text,
        .select2-container--default .select2-selection--single {
            background: #1a1a1a !important;
            color: var(--k-text) !important;
            border: 1px solid var(--k-border) !important;
            border-radius: 12px !important;
            height: 44px;
            transition: all 0.25s ease;
        }

        .form-control:focus,
        .input-group-text:focus {
            border-color: var(--k-gold) !important;
            box-shadow: 0 0 8px var(--k-gold-soft) !important;
            outline: none;
        }

        .form-control::placeholder {
            color: var(--k-muted);
            opacity: 0.7;
        }

        .input-group-text {
            background: #1a1a1a;
            border-right: none;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .input-group .form-control {
            border-left: none;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .input-group-text i {
            color: var(--k-gold);
        }

        /* Select2 Styling */
        .select2-container--default .select2-selection--single {
            height: 44px !important;
            padding: 8px 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--k-text) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--k-gold) transparent transparent transparent !important;
        }

        .select2-dropdown {
            background: #1a1a1a;
            border: 1px solid var(--k-border);
            border-radius: 12px;
            overflow: hidden;
        }

        .select2-results__option {
            color: var(--k-text);
            padding: 10px 15px;
        }

        .select2-results__option:hover,
        .select2-results__option[aria-selected="true"] {
            background: rgba(212,175,55,0.15);
            color: var(--k-gold);
        }

        /* =========================
           BUTTONS
        ========================= */
        .btn-primary {
            background: linear-gradient(135deg, #d4af37, #b8962e) !important;
            color: #000 !important;
            font-weight: 700;
            border-radius: 12px;
            height: 44px;
            border: none;
            transition: all .25s ease;
            padding: 0 20px;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212,175,55,.35);
            color: #000 !important;
        }

        /* =========================
           TABLE
        ========================= */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0 12px;
        }

        .custom-table thead th {
            background: transparent;
            color: var(--k-gold);
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            border: none;
            padding: 15px;
        }

        .custom-table thead th.text-left {
            text-align: left;
        }

        .custom-table tbody tr {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            border-radius: 14px;
            transition: all 0.3s ease;
        }

        .custom-table tbody tr:hover {
            background: #151515;
            box-shadow: 0 10px 28px rgba(212, 175, 55, 0.12);
        }

        .custom-table tbody td {
            border: none;
            /* padding: 16px 18px; */
            vertical-align: middle;
            color: var(--k-text);
            text-align: center;
        }

        .custom-table tbody td.text-left {
            text-align: left;
        }

        /* =========================
           USER AVATAR
        ========================= */
        .user-inline-avatar {
            display: flex;
            align-items: center;
        }

        .user-inline-avatar .avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid var(--k-border);
            background: #1a1a1a;
        }

        .user-inline-avatar .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-inline-avatar .ml-3 {
            margin-left: 12px;
        }

        .user-inline-avatar span:first-child {
            color: #fff;
            font-weight: 600;
            display: block;
        }

        .user-inline-avatar .text-gray {
            color: var(--k-muted) !important;
            font-size: 12px;
            display: block;
            margin-top: 2px;
        }

        /* =========================
           ORDER ID CELL
        ========================= */
        .font-weight-500 {
            font-weight: 600;
        }

        .font-16 {
            font-size: 16px;
        }

        .font-12 {
            font-size: 12px;
        }

        .text-dark-blue {
            color: var(--k-gold) !important;
        }

        /* =========================
           STATUS BADGES
        ========================= */
        .text-warning {
            color: #f1c40f !important;
            font-weight: 600;
            background: rgba(241,196,15,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .text-danger {
            color: #e74c3c !important;
            font-weight: 600;
            background: rgba(231,76,60,0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .text-dark-blue.status-success {
            color: #2ecc71 !important;
            border-radius: 20px;
            display: inline-block;
        }

        /* =========================
           DROPDOWN / ACTIONS
        ========================= */
        .btn-transparent {
            color: var(--k-gold) !important;
            background: none;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-transparent:hover {
            color: #ffd700 !important;
        }

        .table-actions .dropdown-menu {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            border-radius: 12px;
            padding: 6px;
            min-width: 180px;
        }

        .table-actions .dropdown-item,
        .table-actions .d-block {
            color: var(--k-text);
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-align: left;
            display: block;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }

        .table-actions .dropdown-item:hover,
        .table-actions .d-block:hover {
            background: rgba(212, 175, 55, 0.12);
            color: var(--k-gold);
            text-decoration: none;
        }

        /* =========================
           NO RESULT
        ========================= */
        .no-result {
            background: #0f0f0f;
            border: 1px dashed var(--k-border);
            border-radius: 18px;
            padding: 60px 20px;
            text-align: center;
            margin-top: 20px;
        }

        .no-result img {
            filter: brightness(0.9) sepia(0.3);
            opacity: 0.9;
            max-width: 120px;
        }

        .no-result .no-result-content h2 {
            color: var(--k-gold);
            font-size: 20px;
            margin: 20px 0 10px;
        }

        .no-result .no-result-content p {
            color: var(--k-muted);
            font-size: 14px;
            max-width: 400px;
            margin: 0 auto;
        }

        /* =========================
           PAGINATION
        ========================= */
        .pagination .page-link {
            background: #111;
            color: var(--k-gold);
            border: 1px solid var(--k-border);
            border-radius: 10px;
            margin: 0 3px;
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
           RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .custom-table thead {
                display: none;
            }

            .custom-table tbody tr {
                display: block;
                margin-bottom: 15px;
            }

            .custom-table tbody td {
                display: block;
                text-align: left;
                padding: 12px;
                position: relative;
            }

            .custom-table tbody td:before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: var(--k-gold);
                margin-right: 10px;
                min-width: 120px;
            }

            .user-inline-avatar {
                justify-content: flex-start;
            }
        }

        /* ─── Track button (inline) ──────────────────────── */
        .btn-cj-track {
            display:inline-flex; align-items:center; gap:6px;
            padding:5px 13px; border-radius:20px; font-size:11px; font-weight:600;
            background:rgba(212,175,55,.10); border:1px solid rgba(212,175,55,.25);
            color:var(--k-gold); cursor:pointer; transition:.25s; white-space:nowrap;
            letter-spacing:.3px;
        }
        .btn-cj-track:hover { background:var(--k-gold); color:#000; border-color:var(--k-gold); }
        .btn-cj-track svg { width:12px; height:12px; flex-shrink:0; }
 
        /* ═══════════════════════════════════════════════
           TRACKING MODAL  (full overlay — no position:fixed)
           Uses a faux-viewport wrapper so it works inside
           the iframe sandbox (no fixed positioning).
           ═══════════════════════════════════════════════ */
        .cj-overlay-wrap {
            display:none; position:fixed; inset:0; z-index:8000;
            background:rgba(0,0,0,.78);
            -webkit-backdrop-filter:blur(5px); backdrop-filter:blur(5px);
            align-items:center; justify-content:center;
        }
        .cj-overlay-wrap.open { display:flex; }
 
        .cj-modal {
            width:100%; max-width:520px; margin:16px;
            background:#111; border:1px solid var(--k-border);
            border-radius:20px; overflow:hidden;
            box-shadow:0 28px 70px rgba(0,0,0,.85);
            animation:cjIn .32s cubic-bezier(.34,1.4,.64,1);
            display:flex; flex-direction:column;
            max-height:90vh;  
        }
        @keyframes cjIn { from{opacity:0;transform:translateY(24px) scale(.96)} to{opacity:1;transform:none} }
 
        .cj-modal-head {
            display:flex; align-items:center; justify-content:space-between;
            padding:18px 22px; border-bottom:1px solid var(--k-border);
            background:linear-gradient(135deg,#161616,#0d0d0d);
            flex-shrink:0;  
        }
        .cj-modal-head h5 {
            margin:0; color:var(--k-gold); font-size:15px; font-weight:700;
            letter-spacing:.5px; display:flex; align-items:center; gap:9px;
        }
        .cj-modal-head h5 svg { width:16px; height:16px; }
        .cj-close-btn {
            background:transparent; border:none; color:#666;
            font-size:22px; cursor:pointer; line-height:1; transition:.2s; padding:0;
        }
        .cj-close-btn:hover { color:var(--k-gold); }
 
        .cj-modal-body { padding:22px; 
        /* overflow-y:auto; */
        flex:1;}
 
        /* Spinner */
        .cj-spin-wrap { display:flex; flex-direction:column; align-items:center; gap:12px; padding:28px 0; }
        .cj-ring { width:44px; height:44px; border-radius:50%; border:3px solid rgba(212,175,55,.15); border-top-color:var(--k-gold); animation:spin .75s linear infinite; }
        @keyframes spin { to{ transform:rotate(360deg) } }
        .cj-spin-wrap p { color:var(--k-muted); font-size:13px; margin:0; }
 
        /* Track card */
        .cj-card { background:#0d0d0d; border-radius:14px; border:1px solid var(--k-border); overflow:hidden; }
 
        .cj-hero { padding:18px 20px; background:#0f0f0f; border-bottom:1px solid var(--k-border); }
        .cj-tn-label { font-size:10px; color:var(--k-muted); letter-spacing:.9px; text-transform:uppercase; margin-bottom:3px; }
        .cj-tn-value { font-size:15px; font-weight:700; color:var(--k-gold); word-break:break-all; }
 
        /* status pill */
        .cj-pill {
            display:inline-flex; align-items:center; gap:6px; margin-top:10px;
            padding:4px 13px; border-radius:20px; font-size:12px; font-weight:600;
        }
        .cj-pill.transit  { background:rgba(42,122,78,.22);  color:#6fffa0; border:1px solid rgba(42,122,78,.4); }
        .cj-pill.delivered{ background:rgba(212,175,55,.15); color:#d4af37; border:1px solid rgba(212,175,55,.35); }
        .cj-pill.pending  { background:rgba(138,109,29,.22); color:#ffd96a; border:1px solid rgba(138,109,29,.4); }
        .cj-pill.error    { background:rgba(122,31,31,.22);  color:#ff9999; border:1px solid rgba(122,31,31,.4); }
        .cj-dot { width:7px; height:7px; border-radius:50%; background:currentColor; animation:dotpulse 1.8s ease-in-out infinite; }
        @keyframes dotpulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.35;transform:scale(.65)} }
 
        /* info grid */
        .cj-grid { display:grid; grid-template-columns:1fr 1fr; }
        .cj-cell { padding:13px 18px; background:#0d0d0d; border-right:1px solid var(--k-border); border-bottom:1px solid var(--k-border); }
        .cj-cell:nth-child(2n) { border-right:none; }
        .cj-cell:nth-last-child(-n+2) { border-bottom:none; }
        .cj-cell .lbl { font-size:10px; color:var(--k-muted); letter-spacing:.7px; text-transform:uppercase; margin-bottom:2px; }
        .cj-cell .val { font-size:13px; color:var(--k-text); font-weight:500; }
 
        /* delivery bar */
        .cj-delivery { display:flex; align-items:center; gap:13px; padding:14px 18px; background:#0a0a0a; border-top:1px solid var(--k-border); }
        .cj-del-icon { width:36px; height:36px; border-radius:50%; background:rgba(212,175,55,.10); border:1px solid var(--k-border); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .cj-del-icon svg { width:16px; height:16px; color:var(--k-gold); }
        .cj-del-lbl { font-size:10px; color:var(--k-muted); }
        .cj-del-val { font-size:13px; font-weight:600; color:var(--k-text); }
 
        /* last mile */
        .cj-lastmile { padding:12px 18px; border-top:1px solid var(--k-border); font-size:12px; color:var(--k-muted); }
        .cj-lastmile strong { color:var(--k-text); }
        .cj-lastmile a { color:var(--k-gold); text-decoration:none; }
        .cj-lastmile a:hover { text-decoration:underline; }
 
        /* error state */
        .cj-error { display:flex; flex-direction:column; align-items:center; gap:11px; padding:26px 16px; text-align:center; }
        .cj-err-icon { width:48px; height:48px; border-radius:50%; background:rgba(122,31,31,.2); border:1px solid rgba(122,31,31,.4); display:flex; align-items:center; justify-content:center; }
        .cj-err-icon svg { width:22px; height:22px; color:#ff9999; }
        .cj-error h6 { color:#ff9999; font-size:14px; font-weight:600; margin:0; }
        .cj-error p  { color:var(--k-muted); font-size:13px; margin:0; }
 
        /* no tracking */
        .cj-no-track { display:flex; flex-direction:column; align-items:center; gap:10px; padding:24px 16px; text-align:center; }
        .cj-no-track svg { width:36px; height:36px; color:var(--k-muted); }
        .cj-no-track p { color:var(--k-muted); font-size:13px; margin:0; }
 
        /* Responsive */
        @media (max-width:768px) {
            .custom-table thead { display:none; }
            .custom-table tbody tr { display:block; margin-bottom:15px; }
            .custom-table tbody td { display:block; text-align:left; padding:12px; position:relative; }
            .custom-table tbody td:before { content:attr(data-label); float:left; font-weight:600; color:var(--k-gold); margin-right:10px; min-width:120px; }
            .user-inline-avatar { justify-content:flex-start; }
            .cj-grid { grid-template-columns:1fr; }
            .cj-cell { border-right:none; }
            .cj-cell:nth-last-child(-n+2) { border-bottom:1px solid var(--k-border); }
            .cj-cell:last-child { border-bottom:none; }
        }

        .cj-routes-toggle {
            display:flex; align-items:center; justify-content:space-between;
            padding:12px 18px; border-top:1px solid var(--k-border);
            cursor:pointer; background:#0c0c0c; transition:background .2s;
            user-select:none;
        }
        .cj-routes-toggle:hover { background:#111; }
        .cj-routes-toggle .lbl {
            font-size:11px; font-weight:600; letter-spacing:.5px;
            text-transform:uppercase; color:var(--k-gold);
            display:flex; align-items:center; gap:7px;
        }
        .cj-routes-toggle .count {
            font-size:11px; color:var(--k-muted);
            display:flex; align-items:center; gap:6px;
        }
        .cj-chevron {
            width:14px; height:14px; color:var(--k-muted);
            transition:transform .25s ease;
        }
        .cj-chevron.open { transform:rotate(180deg); }
        .cj-routes-body {
            max-height:0; overflow:hidden;
            transition:max-height .35s ease;
            background:#090909;
        }
        .cj-routes-body.open {
            max-height:260px;                    /* shows ~3 events, rest scrolls */
            overflow-y:auto;
            /* custom scrollbar to match theme */
            scrollbar-width:thin;
            scrollbar-color:rgba(212,175,55,.3) transparent;
        }
        .cj-routes-body.open::-webkit-scrollbar { width:4px; }
        .cj-routes-body.open::-webkit-scrollbar-track { background:transparent; }
        .cj-routes-body.open::-webkit-scrollbar-thumb {
            background:rgba(212,175,55,.3); border-radius:4px;
        }
        .cj-routes-body.open::-webkit-scrollbar-thumb:hover {
            background:rgba(212,175,55,.55);
        }
        .cj-timeline { padding:16px 18px 18px; display:flex; flex-direction:column; }
        .cj-step { display:flex; gap:14px; position:relative; }
        .cj-step-line { display:flex; flex-direction:column; align-items:center; flex-shrink:0; width:16px; }
        .cj-step-dot {
            width:10px; height:10px; border-radius:50%;
            border:2px solid rgba(212,175,55,.3);
            background:#090909; flex-shrink:0; margin-top:3px; z-index:1;
        }
        .cj-step-dot.active {
            border-color:var(--k-gold); background:var(--k-gold);
            box-shadow:0 0 7px rgba(212,175,55,.5);
            width:12px; height:12px; margin-top:2px;
        }
        .cj-step-connector { flex:1; width:1px; background:rgba(212,175,55,.12); margin:2px 0; }
        .cj-step:last-child .cj-step-connector { display:none; }
        .cj-step-content { padding-bottom:18px; flex:1; }
        .cj-step:last-child .cj-step-content { padding-bottom:0; }
        .cj-step-remark { font-size:13px; color:var(--k-text); line-height:1.45; font-weight:500; }
        .cj-step-remark.first { color:var(--k-gold); }
        .cj-step-meta { display:flex; gap:10px; align-items:center; margin-top:4px; flex-wrap:wrap; }
        .cj-step-time { font-size:11px; color:var(--k-muted); }
        .cj-step-addr {
            font-size:11px; color:var(--k-muted);
            background:rgba(212,175,55,.07); border:1px solid rgba(212,175,55,.12);
            border-radius:6px; padding:1px 7px;
        }
    </style>
@endpush

@section('content')
    <div class="kemetic-page">
        <section>
            <h2 class="section-title">{{ trans('update.purchases_statistics') }}</h2>

            <div class="activities-container mt-25 p-20 p-lg-35">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/physical_product3.png" width="64" height="64" alt="">
                            <strong class="text-dark-blue mt-2">{{ $totalOrders }}</strong>
                            <span class="text-gray">{{ trans('update.total_orders') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/physical_product2.png" width="64" height="64" alt="">
                            <strong class="text-dark-blue mt-2">{{ $pendingOrders }}</strong>
                            <span class="text-gray">{{ trans('update.pending_orders') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/physical_product1.png" width="64" height="64" alt="">
                            <strong class="text-dark-blue mt-2">{{ $canceledOrders }}</strong>
                            <span class="text-gray">{{ trans('update.canceled_orders') }}</span>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 mb-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="/assets/default/img/activity/33.png" width="64" height="64" alt="">
                            <strong class="text-dark-blue mt-2">{{ !empty($totalPurchase) ? handlePrice($totalPurchase) : 0 }}</strong>
                            <span class="text-gray">{{ trans('update.total_purchase') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-35">
            <h2 class="section-title">{{ trans('update.purchases_report') }}</h2>

            <div class="panel-section-card mt-20">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('public.from') }}</label>
                                        <div class="input-group">
                                            <!-- <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="calendar" width="18" height="18"></i>
                                                </span>
                                            </div> -->
                                            <input type="date" name="from" autocomplete="off"
                                                   class="form-control"
                                                   value="{{ request()->get('from', null) }}" placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('public.to') }}</label>
                                        <div class="input-group">
                                            <!-- <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="calendar" width="18" height="18"></i>
                                                </span>
                                            </div> -->
                                            <input type="date" name="to" autocomplete="off"
                                                   class="form-control"
                                                   value="{{ request()->get('to', null) }}" placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="form-group">
                                        <label>{{ trans('update.seller') }}</label>
                                        <select name="seller_id" class="form-control select2">
                                            <option value="all">{{ trans('public.all') }}</option>
                                            @foreach($sellers as $seller)
                                                <option value="{{ $seller->id }}" @if(request()->get('seller_id') == $seller->id) selected @endif>{{ $seller->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <div class="form-group">
                                        <label>{{ trans('public.type') }}</label>
                                        <select name="type" class="form-control">
                                            <option value="all" @if(request()->get('type') == 'all') selected @endif>{{ trans('public.all') }}</option>
                                            @foreach(\App\Models\Product::$productTypes as $productType)
                                                <option value="{{ $productType }}" @if(request()->get('type') == $productType) selected @endif>{{ trans('update.product_type_'.$productType) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <div class="form-group">
                                        <label>{{ trans('public.status') }}</label>
                                        <select name="status" class="form-control">
                                            <option value="all" @if(request()->get('status') == 'all') selected @endif>{{ trans('public.all') }}</option>
                                            @foreach(\App\Models\ProductOrder::$status as $orderStatus)
                                                @if($orderStatus != 'pending')
                                                    <option value="{{ $orderStatus }}" @if(request()->get('status') == $orderStatus) selected @endif>{{ trans('update.product_order_status_'.$orderStatus) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-2 d-flex align-items-end">
                            <div class="form-group w-100">
                                <label class="d-none d-lg-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">{{ trans('public.show_results') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        @if(!empty($orders) && !$orders->isEmpty())
            <section class="mt-35">
                <h2 class="section-title">{{ trans('update.purchases_history') }}</h2>

                <div class="panel-section-card mt-20">
                    <div class="table-responsive">
                        <table class="custom-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ trans('update.seller') }}</th>
                                    <th class="text-left">{{ trans('update.order_id') }}</th>
                                    <th>{{ trans('public.price') }}</th>
                                    <th>{{ trans('public.discount') }}</th>
                                    <th>{{ trans('cart.tax') }}</th>
                                    <th>{{ trans('update.delivery_fee') }}</th>
                                    <th>{{ trans('financial.total_amount') }}</th>
                                    <th>{{ trans('public.type') }}</th>
                                    <th>{{ trans('public.status') }}</th>
                                    <th>{{ trans('public.date') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    @php
                                        $isCJ = $order->product && $order->product->is_cj_product;
                                        $hasTrack = $isCJ && ($order->tracking_number || $order->cj_order_id);
                                        $isWaiting = $order->status === \App\Models\ProductOrder::$waitingDelivery;
                                    @endphp
                                    <tr>
                                        <td class="text-left" data-label="{{ trans('update.seller') }}">
                                            <div class="user-inline-avatar">
                                                <div class="avatar">
                                                    <img src="{{ $order->seller->getAvatar() ?? '' }}" class="img-cover" alt="">
                                                </div>
                                                <div class="ml-3">
                                                    <span>{{ $order->seller->full_name ?? '' }}</span>
                                                    <span class="text-gray">{{ $order->seller->email ?? '' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-left" data-label="{{ trans('update.order_id') }}">
                                            <span class="font-16 font-weight-500 text-dark-blue">{{ $order->id }}</span>
                                            <span class="d-block font-12 text-gray">{{ $order->quantity }} {{ trans('update.product') }}</span>
                                            @if($isCJ && $isWaiting)
                                                <button
                                                    class="btn-cj-track mt-5"
                                                    onclick="cjOpenModal({{ $order->id }})"
                                                    title="Track CJ Shipment"
                                                >
                                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M9 17H7A5 5 0 0 1 7 7h2"/>
                                                        <path d="M15 7h2a5 5 0 0 1 0 10h-2"/>
                                                        <line x1="8" y1="12" x2="16" y2="12"/>
                                                    </svg>
                                                    Track
                                                </button>
                                            @endif
                                        </td>
                                        <td data-label="{{ trans('public.price') }}">{{ handlePrice($order->sale->amount) }}</td>
                                        <td data-label="{{ trans('public.discount') }}">@if(!empty($order->sale->discount)) {{ handlePrice($order->sale->discount) }} @else - @endif</td>
                                        <td data-label="{{ trans('cart.tax') }}">@if(!empty($order->sale->tax)) {{ handlePrice($order->sale->tax) }} @else - @endif</td>
                                        <td data-label="{{ trans('update.delivery_fee') }}">@if(!empty($order->sale->product_delivery_fee)) {{ handlePrice($order->sale->product_delivery_fee) }} @else - @endif</td>
                                        <td data-label="{{ trans('financial.total_amount') }}">{{ handlePrice($order->sale->total_amount) }}</td>
                                        <td data-label="{{ trans('public.type') }}">
                                            @if(!empty($order->product)) 
                                                <span class="text-gray">{{ trans('update.product_type_'.$order->product->type) }}</span>
                                            @endif
                                        </td>
                                        <td data-label="{{ trans('public.status') }}">
                                            @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                                                <span class="text-warning">{{ trans('update.product_order_status_waiting_delivery') }}</span>
                                            @elseif($order->status == \App\Models\ProductOrder::$success)
                                                <span class="text-dark-blue status-success">{{ trans('update.product_order_status_success') }}</span>
                                            @elseif($order->status == \App\Models\ProductOrder::$shipped)
                                                <span class="text-warning">{{ trans('update.product_order_status_shipped') }}</span>
                                            @elseif($order->status == \App\Models\ProductOrder::$canceled)
                                                <span class="text-danger">{{ trans('update.product_order_status_canceled') }}</span>
                                            @endif
                                        </td>
                                        <td data-label="{{ trans('public.date') }}">{{ dateTimeFormat($order->created_at, 'j M Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($order->product && $order->product->isVirtual())
                                                        <a href="/panel/store/products/{{ $order->product->id }}/getFilesModal" class="dropdown-item">{{ trans('home.download') }}</a>
                                                    @endif
                                                    <a href="/panel/store/purchases/{{ $order->sale_id }}/productOrder/{{ $order->id }}/invoice" target="_blank" class="dropdown-item">{{ trans('public.invoice') }}</a>
                                                    @if($order->product && $order->status == \App\Models\ProductOrder::$success)
                                                        <a href="{{ $order->product->getUrl() }}" target="_blank" class="dropdown-item">{{ trans('public.feedback') }}</a>
                                                    @endif
                                                    @if($order->status == \App\Models\ProductOrder::$shipped)
                                                        <button type="button" data-sale-id="{{ $order->sale_id }}" data-product-order-id="{{ $order->id }}" class="js-view-tracking-code dropdown-item">{{ trans('update.view_tracking_code') }}</button>
                                                        <button type="button" data-sale-id="{{ $order->sale_id }}" data-product-order-id="{{ $order->id }}" class="js-got-the-parcel dropdown-item">{{ trans('update.i_got_the_parcel') }}</button>
                                                    @endif

                                                    @if($isCJ && ($isWaiting || $order->status == \App\Models\ProductOrder::$shipped))
                                                        <button type="button" class="dropdown-item" onclick="cjOpenModal({{ $order->id }})">
                                                            <svg style="width:13px;height:13px;margin-right:6px;vertical-align:-1px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path d="M9 17H7A5 5 0 0 1 7 7h2"/><path d="M15 7h2a5 5 0 0 1 0 10h-2"/>
                                                                <line x1="8" y1="12" x2="16" y2="12"/>
                                                            </svg>
                                                            CJ Live Tracking
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                        <div class="my-30" style="padding:10px;">
                            {{ $orders->appends(request()->input())->links('vendor.pagination.panel') }}
                        </div>
                    @endif
                </div>
            </section>
        @else
            <div class="no-result">
                <div class="no-result-content">
                    <img src="/assets/default/img/no-results/sales.png" alt="{{ trans('update.product_purchases_no_result') }}">
                    <h2>{{ trans('update.product_purchases_no_result') }}</h2>
                    <p>{{ trans('update.product_purchases_no_result_hint') }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="cj-overlay-wrap" id="cjOverlay">
        <div class="cj-modal" role="dialog" aria-modal="true" aria-labelledby="cjModalTitle">
    
            <div class="cj-modal-head">
                <h5 id="cjModalTitle">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 17H7A5 5 0 0 1 7 7h2"/>
                        <path d="M15 7h2a5 5 0 0 1 0 10h-2"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    CJ Shipment Tracking
                </h5>
                <button class="cj-close-btn" onclick="cjCloseModal()" aria-label="Close">&times;</button>
            </div>
    
            <div class="cj-modal-body" id="cjModalBody">
                {{-- injected by JS --}}
            </div>
    
        </div>
    </div>
    
@endsection

@push('scripts_bottom')
    <script>

        var viewTrackingCodeModalTitleLang = '{{ trans('update.view_tracking_code') }}';
        var trackingCodeLang               = '{{ trans('update.tracking_code') }}';
        var closeLang                      = '{{ trans('public.close') }}';
        var confirmLang                    = '{{ trans('update.confirm') }}';
        var gotTheParcelLang               = '{{ trans('update.i_got_the_parcel') }}';
        var gotTheParcelConfirmTextLang    = '{{ trans('update.i_got_the_parcel_confirm') }}';
        var gotTheParcelSaveSuccessLang    = '{{ trans('update.i_got_the_parcel_success_save') }}';
        var gotTheParcelSaveErrorLang      = '{{ trans('update.i_got_the_parcel_error_save') }}';
        var shippingTrackingUrlLang        = '{{ trans('update.track_shipping') }}';
        var addressLang                    = '{{ trans('update.address') }}';
        var filesLang                      = '{{ trans('public.files') }}';
        var onlineViewerModalTitleLang     = '{{ trans('update.online_viewer') }}';

        // When clicking the download button/link
    $('a[href*="getFilesModal"]').on('click', function(e) {
        e.preventDefault();
        
        var downloadUrl = $(this).attr('href');
        // alert(downloadUrl);
        $.ajax({
            url: downloadUrl,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // alert(JSON.stringify(response)); // This will show the response properly
                if (response.code === 200) {
                    // Encode the URL to handle spaces and special characters
                    var encodedUrl = encodeURI(response.url);
                    window.open(encodedUrl, '_blank');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX error
                var response = xhr.responseJSON;
                alert('Error: ' + (response ? response.message : 'Something went wrong'));
            }
        });
    });
    
        var CJ_TRACK_ROUTE = '/panel/store/purchases/{id}/getProductOrder/tracking';
 
    function cjOpenModal(productOrderId) {
        var body    = document.getElementById('cjModalBody');
        var overlay = document.getElementById('cjOverlay');

        body.innerHTML = cjSpinnerHTML('Looking up shipment\u2026');
        overlay.classList.add('open');

        var url = CJ_TRACK_ROUTE.replace('{id}', productOrderId);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.result && res.data) {
                    /* Success — show tracking card */
                    var track = Array.isArray(res.data) ? res.data[0] : res.data;
                    body.innerHTML = cjTrackCardHTML(track);
                } else if (res.code === 404) {
                    /* No tracking number — shipment not created yet */
                    body.innerHTML = cjNoTrackHTML(res.message || '');
                } else {
                    /* Any other error (500, etc.) */
                    body.innerHTML = cjErrorHTML(res.message || 'Tracking data unavailable.');
                }
            })
            .catch(function(err) {
                body.innerHTML = cjErrorHTML('Network error: ' + err.message);
            });
    }
 
    function cjCloseModal() {
        document.getElementById('cjOverlay').classList.remove('open');
    }
 
    /* close on backdrop click */
    document.getElementById('cjOverlay').addEventListener('click', function(e) {
        if (e.target === this) cjCloseModal();
    });
 
    /* close on Escape */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cjCloseModal();
    });
 
    /* ── HTML builders ──────────────────────────────────── */
 
    function cjSpinnerHTML(msg) {
        return '<div class="cj-spin-wrap"><div class="cj-ring"></div><p>' + escH(msg) + '</p></div>';
    }
 
    function cjNoTrackHTML(msg) {
        /* Determine a user-friendly headline based on the server message */
        var isNoShipment = msg && (
            msg.toLowerCase().indexOf('shipment') > -1 ||
            msg.toLowerCase().indexOf('not created') > -1 ||
            msg.toLowerCase().indexOf('not been created') > -1
        );
        var headline = isNoShipment
            ? 'Tracking not created'
            : 'No tracking yet';
        var sub = isNoShipment
            ? 'The shipment has not been created for this order yet.<br>Please check back once the seller dispatches it.'
            : 'No tracking number has been assigned yet.<br>Check back once the order ships.';

        return '<div class="cj-no-track">'
            + '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">'
            + '<path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>'
            + '<path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>'
            + '<line x1="12" y1="12" x2="12" y2="12.01"/>'
            + '</svg>'
            + '<h6 style="color:var(--k-muted);font-size:14px;font-weight:600;margin:4px 0 0">' + escH(headline) + '</h6>'
            + '<p>' + sub + '</p>'
            + '</div>';
    }
 
    function cjErrorHTML(msg) {
        return '<div class="cj-error">'
            + '<div class="cj-err-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">'
            + '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'
            + '</svg></div>'
            + '<h6>Tracking Unavailable</h6>'
            + '<p>' + escH(msg) + '</p>'
            + '<button class="btn-cj-track" onclick="cjCloseModal()" style="margin-top:6px">Close</button>'
            + '</div>';
    }
 
    function pillClass(status) {
        if (!status) return 'pending';
        var s = status.toLowerCase();
        if (s.indexOf('transit') > -1 || s.indexOf('shipping') > -1) return 'transit';
        if (s.indexOf('delivered') > -1 || s.indexOf('success') > -1) return 'delivered';
        if (s.indexOf('error') > -1 || s.indexOf('fail') > -1)        return 'error';
        return 'pending';
    }
 
    function cjTrackCardHTML(t) {
        var pc      = pillClass(t.trackingStatus);
        var route   = [t.trackingFrom, t.trackingTo].filter(Boolean).join(' → ') || '—';
        var carrier = t.logisticName || '—';
        var status  = t.trackingStatus || 'Unknown';
        var routes  = Array.isArray(t.routes) ? t.routes : [];
 
        var html = '<div class="cj-card">'

        /* hero */
        + '<div class="cj-hero">'
        + '<div class="cj-tn-label">Tracking number</div>'
        + '<div class="cj-tn-value">' + escH(t.trackingNumber || '—') + '</div>'
        + '<span class="cj-pill ' + pc + '"><span class="cj-dot"></span>' + escH(status) + '</span>'
        + '</div>'

        /* info grid */
        + '<div class="cj-grid">'
        + cjCell('Carrier', carrier)
        + cjCell('Route', route)
        + '</div>';

        /* delivery estimate */
        if (t.deliveryDay) {
            var eta = t.deliveryTime ? ' · ETA ' + escH(t.deliveryTime) : '';
            html += '<div class="cj-delivery">'
                + '<div class="cj-del-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">'
                + '<rect x="1" y="3" width="15" height="13" rx="1"/>'
                + '<path d="M16 8h4l3 5v3h-7V8z"/>'
                + '<circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'
                + '</svg></div>'
                + '<div><div class="cj-del-lbl">Estimated delivery</div>'
                + '<div class="cj-del-val">' + escH(String(t.deliveryDay)) + ' day(s)' + eta + '</div></div>'
                + '</div>';
        }

        /* last mile */
        if (t.lastMileCarrier || t.lastTrackNumber) {
            html += '<div class="cj-lastmile">';
            if (t.lastMileCarrier) html += '<strong>Last-mile carrier:</strong> ' + escH(t.lastMileCarrier) + ' &nbsp;';
            if (t.lastTrackNumber && t.lastTrackNumber !== 'Updating')
                html += '<strong>Last-mile tracking:</strong> <a href="#" onclick="return false">' + escH(t.lastTrackNumber) + '</a>';
            html += '</div>';
        }

        /* ── Route timeline ── */
        if (routes.length > 0) {
            var uniqueId = 'cjR' + Date.now();
            html += '<div class="cj-routes-toggle" onclick="cjToggleRoutes(\'' + uniqueId + '\', this)">'
                + '<span class="lbl">'
                + '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:13px;height:13px">'
                + '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>'
                + '</svg>'
                + 'Shipment history</span>'
                + '<span class="count">' + routes.length + ' event' + (routes.length !== 1 ? 's' : '')
                + '<svg class="cj-chevron" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">'
                + '<polyline points="6 9 12 15 18 9"/></svg>'
                + '</span>'
                + '</div>'
                + '<div class="cj-routes-body" id="' + uniqueId + '">'
                + '<div class="cj-timeline">';

            routes.forEach(function(step, i) {
                var time = step.acceptTime
                    ? step.acceptTime.replace('T', ' ').replace(/:\d\d$/, '').replace(' ', ' · ')
                    : '';
                var isFirst = i === 0;
                html += '<div class="cj-step">'
                    + '<div class="cj-step-line">'
                    + '<div class="cj-step-dot' + (isFirst ? ' active' : '') + '"></div>'
                    + (i < routes.length - 1 ? '<div class="cj-step-connector"></div>' : '')
                    + '</div>'
                    + '<div class="cj-step-content">'
                    + '<div class="cj-step-remark' + (isFirst ? ' first' : '') + '">' + escH(step.remark || '—') + '</div>'
                    + '<div class="cj-step-meta">'
                    + (time ? '<span class="cj-step-time">' + escH(time) + '</span>' : '')
                    + (step.acceptAddress ? '<span class="cj-step-addr">' + escH(step.acceptAddress) + '</span>' : '')
                    + '</div>'
                    + '</div>'
                    + '</div>';
            });

            html += '</div></div>';
        }

        html += '</div>'; /* .cj-card */
        return html;
    }

    function cjCell(label, value) {
        return '<div class="cj-cell"><div class="lbl">' + escH(label) + '</div><div class="val">' + escH(value) + '</div></div>';
    }

    function cjToggleRoutes(id, btn) {
        var body = document.getElementById(id);
        var chev = btn.querySelector('.cj-chevron');
        if (!body) return;
        var isOpen = body.classList.toggle('open');
        if (chev) chev.classList.toggle('open', isOpen);
    }
 
    /* XSS-safe escape */
    function escH(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
    </script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/js/panel/store/my-purchase.min.js"></script>
    <script src="/assets/default/js/parts/product_show.min.js"></script>
@endpush