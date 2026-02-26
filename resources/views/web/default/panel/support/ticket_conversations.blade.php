@extends('web.default.layouts.newapp')

<style>
  /* KEMETIC STATS */
.kemetic-stat-section {
    margin-top: 25px;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
    margin-bottom: 18px;
}

/* CARD */
.kemetic-stat-card {
    background: linear-gradient(180deg, #121212, #0b0b0b);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 18px;
}

/* ITEM */
.kemetic-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

/* ICON */
.kemetic-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(242, 201, 76, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
}
.kemetic-stat-icon img {
    width: 28px;
    filter: invert(0.9);
}

/* VALUE */
.kemetic-stat-value {
    font-size: 30px;
    font-weight: 700;
    color: #F2C94C;
}

/* LABEL */
.kemetic-stat-label {
    font-size: 14px;
    color: #9a9a9a;
}

/* MOBILE */
@media (max-width: 768px) {
    .kemetic-stat-card {
        padding: 20px 12px;
    }
    .kemetic-stat-value {
        font-size: 24px;
    }
}

/* KEMETIC FILTER */
.kemetic-filter-section {
    color: #eaeaea;
}

.kemetic-title {
    font-size: 22px;
    font-weight: 700;
    color: #F2C94C;
}

/* CARD */
.kemetic-filter-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 26px 22px;
}

/* LABEL */
.kemetic-label {
    font-size: 13px;
    color: #b5b5b5;
    margin-bottom: 6px;
    display: block;
}

/* INPUT GROUP */
.kemetic-input-group {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 10px 12px;
}

.kemetic-input-group i {
    color: #F2C94C;
}

/* INPUT */
.kemetic-input {
    width: 100%;
    background: transparent;
    border: none;
    color: #fff;
    outline: none;
    font-size: 14px;
}

/* SELECT */
.kemetic-select {
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    color: #fff;
    padding: 10px 12px;
    width: 100%;
    outline: none;
}
.kemetic-select option {
    background: #0f0f0f;
    color: #fff;
}

/* BUTTON */
.kemetic-btn {
    background: linear-gradient(135deg, #F2C94C, #d4af37);
    border: none;
    border-radius: 14px;
    padding: 12px;
    font-weight: 700;
    color: #000;
    transition: 0.3s ease;
}
.kemetic-btn:hover {
    background: linear-gradient(135deg, #d4af37, #F2C94C);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(242,201,76,0.4);
}
.kemetic-btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

/* TABLE CARD */
.kemetic-table-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
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
    color:#aaa; 
    font-size:13px;
    font-weight:600; 
    text-align:center;
    padding-bottom: 10px;
}
.kemetic-table thead th.text-left {
    text-align: left;
}
.kemetic-table tbody tr {
    background:#0f0f0f;
    border:1px solid #262626;
    transition: 0.3s ease;
}
.kemetic-table tbody tr:hover {
    background: #1a1a1a;
    border-color: rgba(242,201,76,0.3);
}
.kemetic-table td {
    padding:14px;
    text-align:center;
    vertical-align:middle;
}
.kemetic-table td.text-left { 
    text-align:left; 
}

/* TITLE CELL */
.kemetic-title-cell .title {
    color:#fff; 
    font-weight:600;
}
.kemetic-title-cell small {
    color:#888; 
    display:block;
    font-size: 11px;
}

/* USER AVATAR */
.user-avatar-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(242,201,76,0.3);
    background: #1a1a1a;
}
.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.user-name {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}

/* STATUS BADGES */
.status-badge {
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
    font-weight: 500;
    display: inline-block;
}
.status-badge.open, .status-badge.waiting {
    background: #3d2e1f; 
    color: #f39c12;
}
.status-badge.close {
    background: #3d1f1f; 
    color: #e74c3c;
}
.status-badge.replied {
    background: #1f3d2b; 
    color: #2ecc71;
}

/* CONVERSATION CARD */
.conversation-message-card {
    background: #0f0f0f;
    border: 1px solid #262626;
    border-radius: 16px;
    padding: 18px;
    margin-top: 15px;
}
.message-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 15px;
    border-bottom: 1px solid #262626;
}
.message-sender {
    display: flex;
    align-items: center;
    gap: 12px;
}
.sender-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(242,201,76,0.3);
}
.sender-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.sender-info {
    display: flex;
    flex-direction: column;
}
.sender-name {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}
.sender-role {
    color: #F2C94C;
    font-size: 11px;
}
.message-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
}
.message-time {
    color: #888;
    font-size: 12px;
}
.message-attachment {
    color: #F2C94C;
    font-size: 12px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
}
.message-attachment:hover {
    color: #d4af37;
}
.message-content {
    color: #eaeaea;
    font-size: 14px;
    line-height: 1.6;
    margin-top: 15px;
    white-space: pre-wrap;
}

/* REPLY FORM */
.reply-form-card {
    background: #0f0f0f;
    border: 1px solid #262626;
    border-radius: 16px;
    padding: 20px;
    margin-top: 30px;
}
.reply-title {
    color: #F2C94C;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 15px;
}
.reply-textarea {
    background: #1a1a1a;
    border: 1px solid #262626;
    border-radius: 12px;
    color: #fff;
    padding: 12px;
    width: 100%;
    resize: vertical;
}
.reply-textarea:focus {
    outline: none;
    border-color: #F2C94C;
}
.file-input-group {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #1a1a1a;
    border: 1px solid #262626;
    border-radius: 12px;
    padding: 5px;
}
.file-input-group .input-group-text {
    background: rgba(242,201,76,0.15);
    border: none;
    border-radius: 10px;
    color: #F2C94C;
    cursor: pointer;
}
.file-input-group .form-control {
    background: transparent;
    border: none;
    color: #fff;
}
.file-input-group .form-control:focus {
    outline: none;
}

/* SELECTED ROW */
.selected-row {
    background: rgba(242,201,76,0.1) !important;
    border-left: 3px solid #F2C94C;
}

/* NO RESULT */
.no-result-card {
    background: linear-gradient(180deg,#121212,#0a0a0a);
    border:1px solid #262626;
    border-radius:18px;
    padding:40px;
    text-align: center;
}
.no-result-card img {
    opacity: 0.7;
    margin-bottom: 20px;
}
.no-result-card h3 {
    color: #F2C94C;
    font-size: 20px;
    margin-bottom: 10px;
}
.no-result-card p {
    color: #888;
    font-size: 14px;
}

/* BORDER */
.border-left {
    border-left: 1px solid #262626 !important;
}
.border-bottom {
    border-bottom: 1px solid #262626 !important;
}
</style>

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')

    {{-- Statistics --}}
    <section class="kemetic-stat-section">
        <h2 class="kemetic-title">{{ trans('panel.support_summary') }}</h2>

        <div class="kemetic-stat-card">
            <div class="row text-center">
                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/41.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $openSupportsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('panel.open_conversations') }}</div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/40.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $closeSupportsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('panel.closed_conversations') }}</div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="kemetic-stat-item">
                        <div class="kemetic-stat-icon">
                            <img src="/assets/default/img/activity/39.svg" alt="">
                        </div>
                        <div class="kemetic-stat-value">{{ $supportsCount }}</div>
                        <div class="kemetic-stat-label">{{ trans('panel.total_conversations') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="kemetic-filter-section mt-30">
        <h2 class="kemetic-title">{{ trans('panel.message_filters') }}</h2>

        <div class="kemetic-filter-card mt-20">
            <form action="/panel/support/tickets" method="get">
                <div class="row g-3">

                    {{-- Date range --}}
                    <div class="col-12 col-lg-5">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <label class="kemetic-label">{{ trans('public.from') }}</label>
                                <div class="kemetic-input-group">
                                    <input type="date"
                                    class="kemetic-input text-center"
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

                            <div class="col-12 col-md-6">
                                <label class="kemetic-label">{{ trans('public.to') }}</label>
                                <div class="kemetic-input-group">
                                    <input type="date"
                                    class="kemetic-input text-center"
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

                    {{-- Other filters --}}
                    <div class="col-12 col-lg-5">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <label class="kemetic-label">{{ trans('panel.department') }}</label>
                                <select name="department" class="kemetic-select">
                                    <option value="all">{{ trans('public.all') }}</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" @if(request()->get('department') == $department->id) selected @endif>{{ $department->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="kemetic-label">{{ trans('public.status') }}</label>
                                <select class="kemetic-select" name="status">
                                    <option value="all">{{ trans('public.all') }}</option>
                                    <option value="open" @if(request()->get('status') == 'open') selected @endif>{{ trans('public.open') }}</option>
                                    <option value="close" @if(request()->get('status') == 'close') selected @endif>{{ trans('public.close') }}</option>
                                    <option value="replied" @if(request()->get('status') == 'replied') selected @endif>{{ trans('panel.replied') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2 d-flex align-items-end">
                        <button type="submit" class="kemetic-btn w-100">
                            {{ trans('public.show_results') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- Tickets Section --}}
    <section class="kemetic-section mt-40">
        <h2 class="kemetic-title">{{ trans('panel.messages_history') }}</h2>

        @if(!empty($supports) and !$supports->isEmpty())

            <div class="kemetic-table-card mt-25">
                <div class="row">
                    {{-- Tickets List --}}
                    <div id="conversationsList" class="col-12 col-lg-6">
                        <div class="table-responsive">
                            <table class="kemetic-table">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ trans('navbar.title') }}</th>
                                        <th>{{ trans('public.updated_at') }}</th>
                                        <th>{{ trans('panel.department') }}</th>
                                        <th>{{ trans('public.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supports as $support)
                                        <tr class="@if(!empty($selectSupport) and $selectSupport->id == $support->id) selected-row @endif">
                                            <td class="text-left">
                                                <a href="/panel/support/tickets/{{ $support->id }}/conversations" class="text-decoration-none">
                                                    <div class="user-avatar-cell">
                                                        <div class="user-avatar">
                                                            <img src="/assets/default/img/support.png" alt="">
                                                        </div>
                                                        <div class="user-info">
                                                            <span class="user-name">{{ $support->title }}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>

                                            <td>
                                                <span class="user-name d-block">{{ (!empty($support->conversations) and count($support->conversations)) ? dateTimeFormat($support->conversations->first()->created_at,'j M Y | H:i') : dateTimeFormat($support->created_at,'j M Y | H:i') }}</span>
                                            </td>

                                            <td>
                                                <span class="user-name">{{ $support->department->title }}</span>
                                            </td>

                                            <td>
                                                @if($support->status == 'close')
                                                    <span class="status-badge close">{{ trans('panel.closed') }}</span>
                                                @elseif($support->status == 'supporter_replied')
                                                    <span class="status-badge replied">{{ trans('panel.replied') }}</span>
                                                @else
                                                    <span class="status-badge open">{{ trans('public.waiting') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Ticket Detail --}}
                    @if(!empty($selectSupport))
                        <div class="col-12 col-lg-6 border-left">
                            {{-- Ticket Header --}}
                            <div class="conversation-message-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="user-name d-block">{{ $selectSupport->title }}</span>
                                        <span class="sender-role d-inline-block mt-1">{{ trans('public.created') }}: {{ dateTimeFormat($support->created_at,'j M Y | H:i') }}</span>
                                        
                                        @if(!empty($selectSupport->webinar))
                                            <span class="sender-role d-block mt-2">{{ trans('webinars.webinar') }}: {{ $selectSupport->webinar->title }}</span>
                                        @endif
                                    </div>

                                    @if($selectSupport->status != 'close')
                                        <a href="/panel/support/{{ $selectSupport->id }}/close" class="kemetic-btn kemetic-btn-sm">
                                            {{ trans('panel.close_request') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Conversations --}}
                            <div id="conversationsCard" class="mt-20">
                                @if(!empty($selectSupport->conversations) and !$selectSupport->conversations->isEmpty())
                                    @foreach($selectSupport->conversations as $conversation)
                                        <div class="conversation-message-card">
                                            <div class="message-header">
                                                <div class="message-sender">
                                                    <div class="sender-avatar">
                                                        <img src="{{ (!empty($conversation->supporter)) ? $conversation->supporter->getAvatar() : $conversation->sender->getAvatar() }}" alt="">
                                                    </div>
                                                    <div class="sender-info">
                                                        <span class="sender-name">{{ (!empty($conversation->supporter)) ? $conversation->supporter->full_name : $conversation->sender->full_name }}</span>
                                                        <span class="sender-role">{{ (!empty($conversation->supporter)) ? trans('panel.staff') : $conversation->sender->role_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="message-meta">
                                                    <span class="message-time">{{ dateTimeFormat($conversation->created_at,'j M Y | H:i') }}</span>
                                                    @if(!empty($conversation->attach))
                                                        <a href="{{ url($conversation->attach) }}" target="_blank" class="message-attachment">
                                                            <i data-feather="paperclip" height="14"></i> {{ trans('panel.attach') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="message-content">
                                                {{ $conversation->message }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            {{-- Reply Form --}}
                            <div class="reply-form-card">
                                <h3 class="reply-title">{{ trans('panel.reply_to_the_conversation') }}</h3>
                                <form action="/panel/support/{{ $selectSupport->id }}/conversations" method="post">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <textarea name="message" class="reply-textarea" rows="5" placeholder="{{ trans('site.message') }}">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="text-danger mt-2" style="color: #e74c3c; font-size: 12px;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex align-items-center gap-3" style="padding:10px;">
                                        <div class="flex-grow-1">
                                            <div class="file-input-group">
                                                <span class="input-group-text panel-file-manager" data-input="attach" data-preview="holder">
                                                    <i data-feather="upload" width="18" height="18"></i>
                                                </span>
                                                <input type="text" name="attach" id="attach" value="{{ old('attach') }}" class="form-control" placeholder="{{ trans('panel.attach_file') }}">
                                            </div>
                                        </div>

                                        <button type="submit" class="kemetic-btn kemetic-btn-sm">
                                            {{ trans('site.send_message') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="col-12 col-lg-6 border-left">
                            <div class="no-result-card">
                                @include(getTemplate() . '.includes.no-result',[
                                    'file_name' => 'support.png',
                                    'title' => trans('panel.support_no_result'),
                                    'hint' => nl2br(trans('panel.support_no_result_hint')),
                                ])
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        @else
            <div class="no-result-card mt-25">
                @include(getTemplate() . '.includes.no-result',[
                    'file_name' => 'support.png',
                    'title' => trans('panel.support_no_result'),
                    'hint' => nl2br(trans('panel.support_no_result_hint')),
                ])

            </div>
        @endif
    </section>

    {{-- Pagination --}}
    @if(!empty($supports) and !$supports->isEmpty())
        <div class="my-30">
            
        </div>
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/js/panel/conversations.min.js"></script>
@endpush