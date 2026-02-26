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

/* SELECT2 */
.select2-container--default .select2-selection--single {
    background: #0f0f0f !important;
    border: 1px solid #2a2a2a !important;
    border-radius: 12px !important;
    height: 25px !important;
    display: flex;
    align-items: center;
}
.select2-selection__rendered {
    color: #fff !important;
    line-height: 25px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 25px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #F2C94C transparent transparent transparent !important;
}
.select2-dropdown {
    background: #0f0f0f !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    border-radius: 12px !important;
}
.select2-results__option {
    color: #e0e0e0 !important;
    padding: 10px 14px !important;
}
.select2-results__option--highlighted {
    background: rgba(242,201,76,.15) !important;
    color: #fff !important;
}
.select2-results__option[aria-selected=true] {
    background: rgba(242,201,76,.25) !important;
}
.select2-search--dropdown .select2-search__field {
    background: #0d0d0d !important;
    border: 1px solid rgba(242,201,76,.35) !important;
    color: #fff !important;
    border-radius: 8px !important;
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
.user-role {
    color: #F2C94C;
    font-size: 11px;
    background: rgba(242,201,76,0.15);
    padding: 2px 8px;
    border-radius: 12px;
    margin-top: 2px;
}

/* STATUS BADGES */
.status-badge {
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
    font-weight: 500;
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
            <form action="/panel/support" method="get">
                <div class="row g-3">

                    {{-- Date range --}}
                    <div class="col-12 col-md-4 col-lg-2">
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

                    <div class="col-12 col-md-4 col-lg-2">
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

                    @if(!$authUser->isUser())
                        <div class="col-12 col-md-4 col-lg-2">
                            <label class="kemetic-label">{{ trans('public.user_role') }}</label>
                            <div class="kemetic-input-group">
                                <!-- <i data-feather="users" width="18" height="18"></i> -->
                                <select class="kemetic-select select2" id="userRole" name="role" style="appearance: none;">
                                    <option value="all">{{ trans('public.all_roles') }}</option>
                                    <option value="student" @if(request()->get('role') == 'student') selected @endif>{{ trans('quiz.student') }}</option>
                                    <option value="teacher" @if(request()->get('role') == 'teacher') selected @endif>{{ trans('panel.teacher') }}</option>
                                </select>
                            </div>
                        </div>

                        <div id="studentSelectInput" class="col-12 col-md-4 col-lg-2 @if(request()->get('role') != 'student' and (empty(request()->get('student')) or request()->get('student') == 'all')) d-none @endif">
                            <label class="kemetic-label">{{ trans('public.students') }}</label>
                            <div class="kemetic-input-group">
                                <select name="student" class="kemetic-select select2" data-placeholder="{{ trans('public.all') }}" style="width: 100%;">
                                    <option value="all">{{ trans('public.all') }}</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" @if(request()->get('student') == $student->id) selected @endif>{{ $student->full_name }}</option>
                                    @endforeach
                            </select>
                            </div>
                        </div>
                    @endif

                    <div id="teacherSelectInput" class="col-12 col-md-4 col-lg-2 @if(!$authUser->isUser() and request()->get('role') != 'teacher' and (empty(request()->get('teacher')) or request()->get('teacher') == 'all')) d-none @endif">
                        <label class="kemetic-label">{{ trans('home.teachers') }}</label>
                        <div class="kemetic-input-group">
                            <select name="teacher" class="kemetic-select select2" data-placeholder="{{ trans('public.all') }}" style="width: 100%;">
                                <option value="all">{{ trans('public.all') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @if(request()->get('teacher') == $teacher->id) selected @endif>{{ $teacher->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 col-lg-2">
                        <label class="kemetic-label">{{ trans('product.courses') }}</label>
                        <div class="kemetic-input-group">
                            <select name="webinar" class="kemetic-select select2" data-placeholder="{{ trans('public.all') }}" style="width: 100%;">
                                <option value="all">{{ trans('public.all') }}</option>
                                @foreach($webinars as $webinar)
                                    <option value="{{ $webinar->id }}" @if(request()->get('webinar') == $webinar->id) selected @endif>
                                        {{ $webinar->title }} @if(in_array($webinar->id,$purchasedWebinarsIds)) ({{ trans('panel.purchased') }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 col-lg-2">
                        <label class="kemetic-label">{{ trans('public.status') }}</label>
                        <div class="kemetic-input-group">
                            <!-- <i data-feather="activity" width="18" height="18"></i> -->
                            <select class="kemetic-select select2" name="status" style="appearance: none;">
                                <option value="all">{{ trans('public.all') }}</option>
                                <option value="open" @if(request()->get('status') == 'open') selected @endif>{{ trans('public.open') }}</option>
                                <option value="close" @if(request()->get('status') == 'close') selected @endif>{{ trans('public.close') }}</option>
                                <option value="replied" @if(request()->get('status') == 'replied') selected @endif>{{ trans('panel.replied') }}</option>
                            </select>
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

    {{-- Conversations Section --}}
    <section class="kemetic-section mt-40">
        <h2 class="kemetic-title">{{ trans('panel.messages_history') }}</h2>

        @if(!empty($supports) and !$supports->isEmpty())

            <div class="kemetic-table-card mt-25">
                <div class="row">
                    {{-- Conversations List --}}
                    <div id="conversationsList" class="col-12 col-lg-6">
                        <div class="table-responsive">
                            <table class="kemetic-table">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ trans('navbar.contact') }}</th>
                                        <th class="text-left">{{ trans('public.title') }}</th>
                                        <th>{{ trans('public.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supports as $support)
                                        <tr class="@if(!empty($selectSupport) and $selectSupport->id == $support->id) selected-row @endif">
                                            <td class="text-left">
                                                <a href="/panel/support/{{ $support->id }}/conversations" class="text-decoration-none">
                                                    <div class="user-avatar-cell">
                                                        <div class="user-avatar">
                                                            <img src="{{ (!empty($support->webinar) and $support->webinar->teacher_id != $authUser->id) ? $support->webinar->teacher->getAvatar() : $support->user->getAvatar() }}" alt="">
                                                        </div>
                                                        <div class="user-info">
                                                            <span class="user-name">{{ (!empty($support->webinar) and $support->webinar->teacher_id != $authUser->id) ? $support->webinar->teacher->full_name : $support->user->full_name }}</span>
                                                            <span class="user-role">
                                                                {{ (!empty($support->webinar) and $support->webinar->teacher_id != $authUser->id) ? trans('panel.teacher') : (($support->user->isUser()) ? trans('quiz.student') : trans('panel.staff')) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>

                                            <td class="text-left">
                                                <a href="/panel/support/{{ $support->id }}/conversations" class="text-decoration-none">
                                                    <div class="kemetic-title-cell">
                                                        <span class="title">{{ $support->title }}</span>
                                                        <small>
                                                            @if($authUser->isUser() && !empty($support->webinar))
                                                                {{ truncate($support->webinar->title, 30) }}
                                                            @elseif(!empty($support->webinar))
                                                                {{ $support->webinar->title }}
                                                            @endif
                                                            | {{ (!empty($support->conversations) and count($support->conversations)) ? dateTimeFormat($support->conversations->first()->created_at,'j M Y | H:i') : dateTimeFormat($support->created_at,'j M Y | H:i') }}
                                                        </small>
                                                    </div>
                                                </a>
                                            </td>

                                            <td>
                                                @if($support->status == 'close')
                                                    <span class="status-badge close">{{ trans('panel.closed') }}</span>
                                                @elseif(in_array($support->status, ['supporter_replied', 'replied']))
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

                    {{-- Conversation Detail --}}
                    @if(!empty($selectSupport))
                        <div class="col-12 col-lg-6" style="border-left: 1px solid #262626;">
                            {{-- Conversation Header --}}
                            <div class="conversation-message-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="user-name d-block">{{ $selectSupport->title }}</span>
                                        <span class="user-role d-inline-block mt-1">{{ trans('public.created') }}: {{ dateTimeFormat($support->created_at,'j M Y | H:i') }}</span>
                                        
                                        @if(!empty($selectSupport->webinar))
                                            <span class="user-role d-block mt-2">{{ trans('webinars.webinar') }}: {{ $selectSupport->webinar->title }}</span>
                                        @endif
                                    </div>

                                    @if($selectSupport->status != 'close')
                                        <a href="/panel/support/{{ $selectSupport->id }}/close" class="kemetic-btn" style="padding: 8px 16px; font-size: 13px;">
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

                                    <div class="d-flex align-items-center gap-3" style="padding: 10px;">
                                        <div class="flex-grow-1">
                                            <div class="file-input-group">
                                                <span class="input-group-text panel-file-manager" data-input="attach" data-preview="holder">
                                                    <i data-feather="upload" width="18" height="18"></i>
                                                </span>
                                                <input type="text" name="attach" id="attach" value="{{ old('attach') }}" class="form-control" placeholder="{{ trans('panel.attach_file') }}">
                                            </div>
                                        </div>

                                        <button type="submit" class="kemetic-btn" style="padding: 10px 24px;">
                                            {{ trans('site.send_message') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="col-12 col-lg-6" style="border-left: 1px solid #262626;">
                            <div class="no-result-card">
                                @include(getTemplate() . '.includes.no-result',[
                                'file_name' => 'support.png',
                                'title' => trans('panel.select_support'),
                                'hint' => nl2br(trans('panel.select_support_hint')),
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
                                'title' => trans('panel.select_support'),
                                'hint' => nl2br(trans('panel.select_support_hint')),
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
    
    <!-- <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });

            // Role change handler
            $('#userRole').on('change', function() {
                var role = $(this).val();
                
                if (role === 'student') {
                    $('#studentSelectInput').removeClass('d-none');
                    $('#teacherSelectInput').addClass('d-none');
                } else if (role === 'teacher') {
                    $('#studentSelectInput').addClass('d-none');
                    $('#teacherSelectInput').removeClass('d-none');
                } else {
                    $('#studentSelectInput').addClass('d-none');
                    $('#teacherSelectInput').addClass('d-none');
                }
            });
        });
    </script> -->

    <script src="/assets/default/js/panel/conversations.min.js"></script>
@endpush