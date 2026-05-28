@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}/livestream-settings">Livestream Settings</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl() }}/livestream-settings/{{ !empty($setting) ? $setting->id.'/update' : 'store' }}" method="Post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label>App ID</label>
                                    <input type="text" name="app_id"
                                           class="form-control  @error('app_id') is-invalid @enderror"
                                           value="{{ !empty($setting) ? $setting->app_id : old('app_id') }}"
                                           placeholder="Enter App ID"/>
                                    @error('app_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>App Sign</label>
                                    <input type="text" name="app_sign"
                                           class="form-control  @error('app_sign') is-invalid @enderror"
                                           value="{{ !empty($setting) ? $setting->app_sign : old('app_sign') }}"
                                           placeholder="Enter App Sign"/>
                                    @error('app_sign')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
