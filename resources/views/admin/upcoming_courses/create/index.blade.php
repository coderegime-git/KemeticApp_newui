@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-timepicker/bootstrap-timepicker.min.css">

    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <style>
        .bootstrap-timepicker-widget table td input {
            width: 35px !important;
        }

        .select2-container {
            z-index: 1212 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="{{ getAdminPanelUrl('/upcoming_courses') }}">{{ trans('update.upcoming_courses') }}</a>
                </div>
                <div class="breadcrumb-item">{{!empty($upcomingCourse) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>


        <div class="section-body">

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">

                            <form method="post" action="{{ getAdminPanelUrl('/upcoming_courses/'. (!empty($upcomingCourse) ? $upcomingCourse->id.'/update' : 'store')) }}" id="upcomingCourseForm" class="webinar-form" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <section>
                                    <h2 class="section-title after-line">{{ trans('public.basic_information') }}</h2>

                                    {{-- Basic Information --}}
                                    @include('admin.upcoming_courses.create.includes.basic_information')
                                </section>

                                <section class="mt-3">
                                    <h2 class="section-title after-line">{{ trans('public.additional_information') }}</h2>

                                    {{-- Additional Information --}}
                                    @include('admin.upcoming_courses.create.includes.additional_information')
                                </section>

                                @if(!empty($upcomingCourse))

                                    {{-- Related Course --}}
                                    @include('admin.webinars.relatedCourse.add_related_course', [
                                            'relatedCourseItemId' => $upcomingCourse->id,
                                             'relatedCourseItemType' => 'upcomingCourse',
                                             'relatedCourses' => $upcomingCourse->relatedCourses
                                        ])


                                    {{-- FAQ --}}
                                    @include('admin.upcoming_courses.create.includes.faq')

                                    {{-- Extra Description --}}
                                    @include('admin.upcoming_courses.create.includes.extraDescription')

                                    <section class="mt-3">
                                        <h2 class="section-title after-line">{{ trans('public.message_to_reviewer') }}</h2>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group mt-15">
                                                    <textarea name="message_for_reviewer" rows="10" class="form-control">{{ (!empty($upcomingCourse) && $upcomingCourse->message_for_reviewer) ? $upcomingCourse->message_for_reviewer : old('message_for_reviewer') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                @endif

                                <input type="hidden" name="draft" value="no" id="forDraft"/>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" id="saveAndPublish" class="btn btn-success">{{ !empty($upcomingCourse) ? trans('admin/main.save_and_publish') : trans('admin/main.save_and_continue') }}</button>

                                        @if(!empty($upcomingCourse))
                                            <button type="button" id="saveReject" class="btn btn-warning">{{ trans('public.reject') }}</button>

                                            @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/delete'),
                                                    'btnText' => trans('public.delete'),
                                                    'hideDefaultClass' => true,
                                                    'btnClass' => 'btn btn-danger'
                                                    ])
                                        @endif
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('admin.upcoming_courses.create.includes.modals.faq')
    @include('admin.upcoming_courses.create.includes.modals.extra_description')

@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/admin/js/create_upcoming_course.min.js"></script>
@endpush
