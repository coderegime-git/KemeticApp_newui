@extends('web.default.layouts.newapp')
<style>
    .kemetic-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.kemetic-followers-scrollable {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 5px;
}

.kemetic-followers-scrollable .border-bottom {
    border-color: #e5e7eb;
}

.kemetic-followers-scrollable img {
    object-fit: cover;
}

.bg-info-light {
    background-color: #e0f2ff !important;
}

@media (max-width: 991px) {
    .kemetic-followers-scrollable {
        max-height: 300px;
    }
}

</style>
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <div class="container">
        <section class="mt-40">
            <h2 class="font-20 font-weight-bold text-dark-blue">
                "<a href="{{ $upcomingCourse->getUrl() }}" target="_blank" class="text-dark-blue">{{ $upcomingCourse->title }}</a>"
                <span class="ml-5 font-14 text-gray">{{ trans('update.followers') }}</span>
            </h2>
        </section>

        <div class="row mt-20">
            {{-- Followers Card --}}
            <div class="col-12 col-lg-6 mb-20">
                <div class="kemetic-card shadow-sm rounded-lg p-25 h-100">
                    <div class="font-16 font-weight-500 text-gray">{{ trans('update.followers') }}</div>

                    @if(!empty($followers) && $followers->isNotEmpty())
                        <div class="kemetic-followers-scrollable mt-20" data-simplebar @if(!empty($isRtl)) data-simplebar-direction="rtl" @endif>
                            @foreach($followers as $follower)
                                <div class="d-flex align-items-center py-10 border-bottom">
                                    <div class="size-50 rounded-circle overflow-hidden">
                                        <img src="{{ $follower->user->getAvatar(50) }}" alt="{{ $follower->user->full_name }}" class="img-cover rounded-circle">
                                    </div>
                                    <div class="ml-15">
                                        <h4 class="font-16 font-weight-bold text-dark-blue mb-2">{{ $follower->user->full_name }}</h4>
                                        <p class="font-12 text-gray mb-0">{{ dateTimeFormat($follower->created_at, 'j M Y H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center mt-20">
                            <img src="/assets/default/img/upcoming/no_followers.svg" alt="no followers" width="180" class="mb-15">
                            <h4 class="font-18 font-weight-bold text-dark-blue">{{ trans('update.no_followers') }}</h4>
                            <p class="font-14 text-gray">{{ trans('update.this_course_doesnt_have_any_followers') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Notification Card --}}
            <div class="col-12 col-lg-6 mb-20">
                <div class="kemetic-card shadow-sm rounded-lg p-25 d-flex flex-column justify-content-between h-100">
                    <div class="text-center">
                        <img src="/assets/default/img/upcoming/send_notification.svg" alt="send notification" class="mb-20" style="max-width: 250px;">
                        <h4 class="font-18 font-weight-bold text-dark-blue">{{ trans('update.send_a_notification') }}</h4>

                        @if(!empty($upcomingCourse->webinar_id))
                            <p class="font-14 text-gray mt-5">{{ trans('update.published_upcoming_course_send_a_notification_hint') }}</p>
                        @else
                            <p class="font-14 text-gray mt-5">{{ trans('update.upcoming_course_send_a_notification_hint') }}</p>
                        @endif
                    </div>

                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mt-25 p-15 border rounded-sm bg-info-light">
                        @if(!empty($upcomingCourse->webinar_id))
                            <div>
                                <h5 class="font-16 font-weight-bold text-dark-blue">{{ trans('update.course_published') }}</h5>
                                <p class="font-12 text-gray mt-5">{{ trans('update.his_course_already_published') }}</p>
                            </div>

                            <a href="{{ $upcomingCourse->webinar->getUrl() }}" target="_blank" class="btn btn-primary btn-sm mt-15 mt-lg-0">{{ trans('update.view_course') }}</a>
                        @else
                            <div>
                                <h5 class="font-16 font-weight-bold text-dark-blue">{{ trans('update.notify_followers') }}</h5>
                                <p class="font-12 text-gray mt-5">{{ trans('update.send_a_notifications_to_all_followers_and_let_them_know_course_publishing') }}</p>
                            </div>

                            <button type="button" data-id="{{ $upcomingCourse->id }}" class="js-mark-as-released webinar-actions btn btn-primary btn-sm mt-15 mt-lg-0">
                                {{ trans('update.assign_a_course') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/js/panel/upcoming_course.min.js"></script>
@endpush
