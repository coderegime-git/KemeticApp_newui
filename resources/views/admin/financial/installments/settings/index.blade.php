@extends('admin.layouts.app')

@push('libraries_top')
<link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush


@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ $pageTitle }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
            </div>
            <div class="breadcrumb-item active">
                {{ trans('update.installments_settings') }}
            </div>
        </div>
    </div>


    <div class="section-body">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <ul class="nav nav-pills" id="myTab3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-toggle="tab"
                                    href="#basic" role="tab" aria-controls="basic"
                                    aria-selected="true">Basic</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="terms-tab" data-toggle="tab" href="#terms"
                                    role="tab" aria-controls="terms" aria-selected="true">Terms &amp;
                                    Policies</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent2">
                            <div class="tab-pane mt-3 fade show active" id="basic" role="tabpanel"
                                aria-labelledby="basic-tab">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <form action="{{ getAdminPanelUrl() }}/financial/installments/settings"
                                            method="post">
                                            <input type="hidden" name="_token"
                                                value="OTckCTqAx7DaBFjqxGRPdyDe4hUTnzDsQjem7RMT">
                                            <input type="hidden" name="page" value="general">
                                            <input type="hidden" name="name"
                                                value="installments_settings">
                                            <input type="hidden" name="locale" value="en">


                                            <div class="form-group custom-switches-stacked">
                                                <label
                                                    class="custom-switch pl-0 d-flex align-items-center">
                                                    <input type="hidden" name="value[status]" value="{{getInstallmentsSettings('status')??0}}">
                                                    <input type="checkbox" name="value[status]"
                                                        id="installmentStatusSwitch" value="{{getInstallmentsSettings('status')??0}}"
                                                        {{getInstallmentsSettings('status')==1?'checked':''}}
                                                        class="custom-switch-input" data-setting="status" />
                                                    <span class="custom-switch-indicator"></span>
                                                    <label
                                                        class="custom-switch-description mb-0 cursor-pointer"
                                                        for="installmentStatusSwitch">Active</label>
                                                </label>
                                                <div class="text-muted text-small">Enable installments
                                                    to allow customers purchase products using
                                                    installments</div>
                                            </div>

                                            <div class="form-group custom-switches-stacked">
                                                <label
                                                    class="custom-switch pl-0 d-flex align-items-center">
                                                    <input type="hidden"
                                                        name="value[disable_course_access_when_user_have_an_overdue_installment]"
                                                        value="{{getInstallmentsSettings('disable_course_access_when_user_have_an_overdue_installment')??0}}">
                                                    <input type="checkbox"
                                                        name="value[disable_course_access_when_user_have_an_overdue_installment]"
                                                        id="disableCourseSwitch" value="{{getInstallmentsSettings('disable_course_access_when_user_have_an_overdue_installment')??0}}"
                                                        {{getInstallmentsSettings('disable_course_access_when_user_have_an_overdue_installment')==1?'checked':''}}
                                                        class="custom-switch-input" data-setting="disable_course_access_when_user_have_an_overdue_installment" />
                                                        
                                                    <span class="custom-switch-indicator"></span>
                                                    <label
                                                        class="custom-switch-description mb-0 cursor-pointer"
                                                        for="disableCourseSwitch">Disable course access
                                                        when users have an overdue installment</label>
                                                </label>
                                                <div class="text-muted text-small">Users won&#039;t have
                                                    access to the product page when they have an overdue
                                                    installment for it</div>
                                            </div>

                                            <div class="form-group custom-switches-stacked">
                                                <label
                                                    class="custom-switch pl-0 d-flex align-items-center">
                                                    <input type="hidden"
                                                        name="value[disable_all_courses_access_when_user_have_an_overdue_installment]"
                                                        value="{{getInstallmentsSettings('disable_all_courses_access_when_user_have_an_overdue_installment')??0}}">
                                                    <input type="checkbox"
                                                        name="value[disable_all_courses_access_when_user_have_an_overdue_installment]"
                                                        id="disableAllCourseSwitch" value="{{getInstallmentsSettings('disable_all_courses_access_when_user_have_an_overdue_installment')??0}}"
                                                        {{getInstallmentsSettings('disable_all_courses_access_when_user_have_an_overdue_installment')==1?'checked':''}}
                                                        class="custom-switch-input" data-setting="disable_all_courses_access_when_user_have_an_overdue_installment" />
                                                        
                                                    <span class="custom-switch-indicator"></span>
                                                    <label
                                                        class="custom-switch-description mb-0 cursor-pointer"
                                                        for="disableAllCourseSwitch">Disable all courses
                                                        access when users have an overdue
                                                        installment</label>
                                                </label>
                                                <div class="text-muted text-small">Users won&#039;t have
                                                    access to all products when having an overdue
                                                    installment</div>
                                            </div>

                                            <div class="form-group custom-switches-stacked">
                                                <label
                                                    class="custom-switch pl-0 d-flex align-items-center">
                                                    <input type="hidden"
                                                        name="value[disable_instalments_when_the_user_have_an_overdue_installment]"
                                                        value="{{getInstallmentsSettings('disable_instalments_when_the_user_have_an_overdue_installment')??0}}">
                                                    <input type="checkbox"
                                                        name="value[disable_instalments_when_the_user_have_an_overdue_installment]"
                                                        id="disableWhenOverdueSwitch" value="{{getInstallmentsSettings('disable_instalments_when_the_user_have_an_overdue_installment')??0}}"
                                                        {{getInstallmentsSettings('disable_instalments_when_the_user_have_an_overdue_installment')==1?'checked':''}}
                                                        class="custom-switch-input" data-setting="disable_instalments_when_the_user_have_an_overdue_installment"/>
                                                    <span class="custom-switch-indicator"></span>
                                                    <label
                                                        class="custom-switch-description mb-0 cursor-pointer"
                                                        for="disableWhenOverdueSwitch">Disable
                                                        installments when the user has an overdue
                                                        installment</label>
                                                </label>
                                                <div class="text-muted text-small">Installment plans
                                                    won&#039;t be displayed for users who have overdue
                                                    installments</div>
                                            </div>

                                            <div class="form-group custom-switches-stacked">
                                                <label
                                                    class="custom-switch pl-0 d-flex align-items-center">
                                                    <input type="hidden"
                                                        name="value[allow_cancel_verification]"
                                                        value="{{getInstallmentsSettings('allow_cancel_verification')??0}}">
                                                    <input type="checkbox"
                                                        name="value[allow_cancel_verification]"
                                                        id="allowCancelVerificationSwitch" value="{{getInstallmentsSettings('allow_cancel_verification')??0}}"
                                                        {{getInstallmentsSettings('allow_cancel_verification')==1?'checked':''}}
                                                        class="custom-switch-input" data-setting="allow_cancel_verification" />
                                                    <span class="custom-switch-indicator"></span>
                                                    <label
                                                        class="custom-switch-description mb-0 cursor-pointer"
                                                        for="allowCancelVerificationSwitch">Allow cancel
                                                        verification</label>
                                                </label>
                                                <div class="text-muted text-small">Users can cancel the
                                                    verification request after submission and the
                                                    upfront will be refunded</div>
                                            </div>

                                            <div class="form-group custom-switches-stacked">
                                                <label
                                                    class="custom-switch pl-0 d-flex align-items-center">
                                                    <input type="hidden"
                                                        name="value[display_installment_button]"
                                                        value="{{getInstallmentsSettings('display_installment_button')??0}}">
                                                    <input type="checkbox"
                                                        name="value[display_installment_button]"
                                                        id="displayInstallmentButtonSwitch" value="{{getInstallmentsSettings('display_installment_button')??0}}"
                                                        {{getInstallmentsSettings('display_installment_button')==1?'checked':''}}
                                                        class="custom-switch-input" data-setting="display_installment_button"/>
                                                    <span class="custom-switch-indicator"></span>
                                                    <label
                                                        class="custom-switch-description mb-0 cursor-pointer"
                                                        for="displayInstallmentButtonSwitch">Display
                                                        installment button</label>
                                                </label>
                                                <div class="text-muted text-small">Installment button
                                                    will be displayed on the product page in addition to
                                                    installment plans</div>
                                            </div>

                                            <div class="form-group">
                                                <label>Overdue interval days</label>
                                                <input type="number" name="value[overdue_interval_days]"
                                                    value="{{getInstallmentsSettings('overdue_interval_days')??0}}" class="form-control text-center" />
                                                <div class="text-muted text-small mt-1">The customer
                                                    will be allowed to pay for installments X days after
                                                    the due date</div>
                                            </div>

                                            <div class="form-group">
                                                <label>Installment plans position</label>
                                                <select name="value[installment_plans_position]"
                                                    class="form-control">
                                                    <option {{getInstallmentsSettings('installment_plans_position')=='top_of_page'?'selected':''}} value="top_of_page" selected>Top of the
                                                        product page</option>
                                                    <option {{getInstallmentsSettings('installment_plans_position')=='bottom_of_page'?'selected':''}} value="bottom_of_page">Bottom of the product
                                                        page</option>
                                                </select>
                                                <div class="text-muted text-small mt-1">Display
                                                    installments on the top or bottom of the page</div>
                                            </div>

                                            <div class="form-group">
                                                <label>Installment Reminder Days (Before Due)</label>
                                                <input type="number"
                                                    name="value[reminder_before_overdue_days]" value="{{getInstallmentsSettings('reminder_before_overdue_days')??0}}"
                                                    class="form-control text-center" />
                                                <div class="text-muted text-small mt-1">An installment
                                                    reminder will be sent to the customer X days before
                                                    the due date</div>
                                            </div>

                                            <div class="form-group">
                                                <label>Installment Reminder Days (After Due)</label>
                                                <input type="number"
                                                    name="value[reminder_after_overdue_days]" value="{{getInstallmentsSettings('reminder_after_overdue_days')??0}}"
                                                    class="form-control text-center" />
                                                <div class="text-muted text-small mt-1">An installment
                                                    reminder will be sent to the customer X days after
                                                    the due date</div>
                                            </div>

                                            <button type="submit"
                                                class="btn btn-primary mt-1">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane mt-3 fade" id="terms" role="tabpanel"
                                aria-labelledby="terms-tab">
                                <form action="{{ getAdminPanelUrl() }}/financial/installments/settings" method="post">
                                    <input type="hidden" name="_token"
                                        value="OTckCTqAx7DaBFjqxGRPdyDe4hUTnzDsQjem7RMT">
                                    <input type="hidden" name="page" value="general">
                                    <input type="hidden" name="name"
                                        value="installments_terms_settings">

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label class="input-label">Language</label>
                                                <select name="locale"
                                                    class="form-control js-edit-content-locale">
                                                    <option {{app()->getLocale()=='en'?'selected':''}} value="en" selected>English</option>
                                                    <option {{app()->getLocale()=='ar'?'selected':''}} value="ar">Arabic</option>
                                                    <option {{app()->getLocale()=='es'?'selected':''}} value="es">Spanish</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group ">
                                                <label class="control-label">Description</label>
                                                <textarea name="value[terms_description]" required
                                                 class="summernote form-control text-left" >{{getInstallmentsTermsSettings('terms_description')}}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-1">Save</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>





@endsection

@push('scripts_bottom')
<script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
    $('.custom-switch-input').on('change', function() {
        // Toggle the value between 1 and 0
        let currentValue = $(this).is(':checked') ? 1 : 0;

        // Update the value attribute to reflect the toggled value
        $(this).val(currentValue);

        // If there's a hidden input with the same name, update its value too
        let settingName = $(this).data('setting');
        $(`input[type="hidden"][name="value[${settingName}]"]`).val(currentValue);
    });
});
</script>
@endpush