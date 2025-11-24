@extends('admin.layouts.app')

@push('styles_top')
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
                    <a href="{{ getAdminPanelUrl('/installments') }}">{{ trans('update.installments') }}</a>
                </div>
                <div class="breadcrumb-item">{{ !empty($installment) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>


        <div class="section-body">
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ getAdminPanelUrl('/financial/installments/'. (!empty($installment) ? $installment->id.'/update' : 'store')) }}" id="installmentForm" class="installment-form">
                        {{ csrf_field() }}

                        {{-- Basic Information --}}
                        <section>
                            <h2 class="section-title after-line">{{ trans('public.basic_information') }}</h2>

                            @include('admin.financial.installments.create.includes.basic_information')
                        </section>

                        {{-- target products --}}
                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('update.target_products') }}</h2>

                            @include('admin.financial.installments.create.includes.target_products')
                        </section>

                        {{-- verification --}}
                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('update.verification') }}</h2>

                            @include('admin.financial.installments.create.includes.verification')
                        </section>

                        {{-- Payment --}}
                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('update.payment') }}</h2>

                            @include('admin.financial.installments.create.includes.payment')
                        </section>

                        <div class="mb-20">
                            <div class="form-group mt-30 mb-0 d-flex align-items-center">
                                <label class="" for="statusSwitch">{{ trans('admin/main.active') }}</label>
                                <div class="custom-control custom-switch ml-3">
                                    <input type="checkbox" name="enable" class="custom-control-input" id="statusSwitch" {{ (!empty($installment) && $installment->enable) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="statusSwitch"></label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">{{ trans('admin/main.save_change') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <div id="installmentStepsMainRow" class="d-none">
        @include('admin.financial.installments.create.includes.installment_step_inputs')
    </div>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.payment_steps_hint_title')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.payment_steps_hint_description')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.payment_deadline_hint_title')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.payment_deadline_hint_description')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.upfront_hint_title')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.upfront_hint_description')}}</div>
                    </div>
                </div>


            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/default/js/admin/create_installment.min.js"></script>
@endpush
