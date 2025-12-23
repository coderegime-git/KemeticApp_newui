@extends('web.default.layouts.newapp')
<style>
    .kemetic-course-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s;
}

.kemetic-course-card:hover {
    transform: translateY(-3px);
}

.kemetic-progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 6px;
    width: 100%;
    background-color: #e5e7eb;
}

.kemetic-progress-fill {
    height: 100%;
    border-radius: 0 0 8px 8px;
}

.kemetic-stat {
    display: flex;
    flex-direction: column;
}

.stat-title {
    font-size: 12px;
    color: #6b7280;
}

.stat-value {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
}

.status-badge {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 8px;
    background-color: #4b5563;
    color: #fff;
}

</style>
@section('content')
<section class="mt-40">
    <h2 class="font-20 font-weight-bold text-dark-blue mb-25">{{ trans('update.following_courses') }}</h2>

    @if(!empty($upcomingCourses) && $upcomingCourses->isNotEmpty())
        @foreach($upcomingCourses as $upcomingCourse)
            <div class="kemetic-course-card mb-25 shadow-sm rounded-lg overflow-hidden">
                <div class="row no-gutters align-items-center">
                    
                    {{-- Image & Progress --}}
                    <div class="col-12 col-md-4 position-relative">
                        <img src="{{ $upcomingCourse->getImage() }}" alt="{{ $upcomingCourse->title }}" class="img-cover w-100 h-100">
                        
                        @if(!empty($upcomingCourse->course_progress))
                            <div class="kemetic-progress-bar">
                                <div class="kemetic-progress-fill {{ ($upcomingCourse->course_progress < 50) ? 'bg-warning' : 'bg-primary' }}" style="width: {{ $upcomingCourse->course_progress }}%;"></div>
                            </div>
                        @endif
                    </div>

                    {{-- Course Info --}}
                    <div class="col-12 col-md-8 p-20 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="{{ $upcomingCourse->getUrl() }}" target="_blank" class="text-dark-blue">
                                <h3 class="font-18 font-weight-bold mb-10">
                                    {{ $upcomingCourse->title }}
                                    <span class="badge badge-secondary ml-10 status-badge">{{ trans('webinars.'.$upcomingCourse->type) }}</span>
                                </h3>
                            </a>
                        </div>

                        <div class="d-flex flex-wrap mt-auto">
                            <div class="kemetic-stat mr-20 mt-15">
                                <span class="stat-title">{{ trans('public.item_id') }}:</span>
                                <span class="stat-value">{{ $upcomingCourse->id }}</span>
                            </div>

                            <div class="kemetic-stat mr-20 mt-15">
                                <span class="stat-title">{{ trans('public.category') }}:</span>
                                <span class="stat-value">{{ !empty($upcomingCourse->category_id) ? $upcomingCourse->category->title : '-' }}</span>
                            </div>

                            @if(!empty($upcomingCourse->duration))
                                <div class="kemetic-stat mr-20 mt-15">
                                    <span class="stat-title">{{ trans('webinars.next_session_duration') }}:</span>
                                    <span class="stat-value">{{ convertMinutesToHourAndMinute($upcomingCourse->duration) }} Hrs</span>
                                </div>
                            @endif

                            @if(!empty($upcomingCourse->publish_date))
                                <div class="kemetic-stat mr-20 mt-15">
                                    <span class="stat-title">{{ trans('update.estimated_publish_date') }}:</span>
                                    <span class="stat-value">{{ dateTimeFormat($upcomingCourse->publish_date, 'j M Y H:i') }}</span>
                                </div>
                            @endif

                            <div class="kemetic-stat mr-20 mt-15">
                                <span class="stat-title">{{ trans('public.price') }}:</span>
                                <span class="stat-value">{{ (!empty($upcomingCourse->price) && $upcomingCourse->price > 0) ? handlePrice($upcomingCourse->price) : trans('free') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="my-30">
            {{ $upcomingCourses->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>
    @else
        @include(getTemplate() . '.includes.no-result',[
            'file_name' => 'student.png',
            'title' => trans('update.no_result_following_course'),
            'hint' => trans('update.no_result_following_course_hint'),
        ])
    @endif
</section>
@endsection
