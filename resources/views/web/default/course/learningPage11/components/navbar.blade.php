<style>
    :root {
    --k-gold: #F2C94C;
    --k-dark: #0D0D0D;
    --k-card: #1A1A1A;
    --k-border: #333;
    --k-radius: 14px;
}

/* NAVBAR */
.kemetic-learning-navbar {
    background: var(--k-dark);
    padding: 15px 25px;
    border-bottom: 1px solid var(--k-border);
}

/* Logo */
.kemetic-logo {
    width: 42px;
    height: 42px;
    object-fit: contain;
}

/* Buttons */
.kemetic-btn-sm {
    background: transparent;
    border: 1px solid var(--k-gold);
    padding: 5px 14px;
    color: var(--k-gold);
    border-radius: var(--k-radius);
    font-size: 13px;
    transition: 0.2s;
}

.kemetic-btn-sm:hover {
    background: var(--k-gold);
    color: var(--k-dark);
}

/* Icon button */
.kemetic-icon-btn {
    position: relative;
}

/* Notice Count Badge */
.kemetic-notice-badge {
    position: absolute;
    right: -6px;
    top: -6px;
    background: red;
    color: #fff;
    font-size: 10px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Course Title */
.kemetic-course-title {
    color: var(--k-gold);
    font-size: 17px;
    font-weight: 600;
}

/* Progress bar */
.kemetic-progress-bar {
    height: 8px;
    background: #333;
    border-radius: 20px;
    overflow: hidden;
}

.kemetic-progress-fill {
    display: block;        /* critical fix */
    height: 100%;
    width: 0;
    background: var(--k-gold);
    border-radius: 20px;
    transition: width 0.3s ease;
}

.kemetic-progress-text {
    color: #bbb;
    font-size: 13px;
}

/* Menu Button */
.kemetic-menu-btn {
    background: transparent;
    border: none;
    color: var(--k-gold);
}
.kemetic-menu-btn i {
    stroke: var(--k-gold);
}

</style>

@php
    $percent = $course->getProgress(true);
@endphp

<div class="kemetic-learning-navbar d-flex align-items-center justify-content-between flex-column flex-lg-row">
    
    <!-- Left Section -->
    <div class="d-flex align-items-center flex-column flex-lg-row flex-grow-1">

        <!-- Logo -->
        <div class="kemetic-navbar-logo d-flex align-items-center">
            <a class="navbar-brand mr-0" href="/">
                @if(!empty($generalSettings['logo']))
                    <!-- <img src="{{ $generalSettings['logo'] }}" class="kemetic-logo" alt="site logo"> -->
                @endif
            </a>

            <!-- Mobile Buttons -->
            <div class="d-flex d-lg-none ml-20">
                <a href="{{ $course->getUrl() }}" class="kemetic-btn-sm d-none d-md-block">{{ trans('update.course_page') }}</a>
                <a href="/panel/webinars/purchases" class="kemetic-btn-sm ml-10">{{ trans('update.my_courses') }}</a>
            </div>
        </div>

        <!-- Course Progress -->
        <div class="kemetic-progress-block d-flex flex-column ml-lg-30 mt-15 mt-lg-0">
            <a href="{{ $course->getUrl() }}" class="kemetic-course-title">{{ $course->title }}</a>

            <div class="d-flex align-items-center mt-5">
                <div class="kemetic-progress-bar flex-grow-1">
                    <span class="kemetic-progress-fill" style="width: {{ $percent }}%"></span>
                </div>
                <span class="kemetic-progress-text ml-10">{{ $percent }}% {{ trans('update.learnt') }}</span>
            </div>
        </div>
    </div>

    <!-- Right Section -->
    <div class="d-flex align-items-center mt-15 mt-lg-0">

        @if(!empty($course->noticeboards_count) and $course->noticeboards_count > 0)
            <a href="{{ $course->getNoticeboardsPageUrl() }}" target="_blank" class="kemetic-btn-sm kemetic-icon-btn mr-10">
                <i data-feather="bell"></i>
                <span class="kemetic-notice-badge">{{ $course->noticeboards_count }}</span>
            </a>
        @endif

        @if($course->forum)
            <a href="{{ $course->getForumPageUrl() }}" class="kemetic-btn-sm mr-10">{{ trans('update.course_forum') }}</a>
        @endif

        <div class="d-none d-lg-flex">
            <a href="{{ $course->getUrl() }}" class="kemetic-btn-sm ml-10">{{ trans('update.course_page') }}</a>
            <a href="/panel/webinars/purchases" class="kemetic-btn-sm ml-10">{{ trans('update.my_courses') }}</a>
        </div>

        <button id="collapseBtn" type="button" class="kemetic-menu-btn ml-20">
            <i data-feather="menu"></i>
        </button>
    </div>

</div>

