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
                    <a href="{{ getAdminPanelUrl('/cashback/rules') }}">{{ trans('update.rules') }}</a>
                </div>
                <div class="breadcrumb-item">{{ !empty($rule) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>


        <div class="section-body">
            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ getAdminPanelUrl('/cashback/rules/'. (!empty($rule) ? $rule->id.'/update' : 'store')) }}">
                        {{ csrf_field() }}

                        {{-- Basic Information --}}
                        <section>
                            <h2 class="section-title after-line">{{ trans('public.basic_information') }}</h2>

                            @include('admin.cashback.rules.create.includes.basic_information')
                        </section>

                        {{-- target products --}}
                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('update.target_products') }}</h2>

                            @include('admin.cashback.rules.create.includes.target_products')
                        </section>

                        {{-- Payment --}}
                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('update.payment') }}</h2>

                            @include('admin.cashback.rules.create.includes.payment')
                        </section>

                        <div class="mb-20">
                            <div class="form-group mt-30 mb-0 d-flex align-items-center">
                                <label class="" for="statusSwitch">{{ trans('admin/main.active') }}</label>
                                <div class="custom-control custom-switch ml-3">
                                    <input type="checkbox" name="enable" class="custom-control-input" id="statusSwitch" {{ (!empty($rule) && $rule->enable) ? 'checked' : '' }}>
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
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/default/js/admin/cashback_create_rule.min.js"></script>
@endpush
