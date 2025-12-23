@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

<style>
    :root {
        --k-bg: #0b0b0b;
        --k-card: #141414;
        --k-gold: #f2c94c;
        --k-gold-soft: #e6b93d;
        --k-border: rgba(242,201,76,0.25);
        --k-text: #eaeaea;
        --k-muted: #9a9a9a;
        --k-radius: 16px;
    }

    body {
        background: var(--k-bg);
        color: var(--k-text);
        font-family: 'Nunito', sans-serif;
    }

    .section-title {
        font-size: 1.5rem;
        color: var(--k-gold);
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .rounded-sm {
        border-radius: var(--k-radius);
    }

    .shadow {
        box-shadow: 0 4px 20px rgba(242,201,76,0.1);
    }

    .bg-white {
        background: var(--k-card);
    }

    .form-control {
        background: #1f1f1f;
        color: var(--k-text);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
    }

    .form-control::placeholder {
        color: var(--k-muted);
    }

    .input-label {
        color: var(--k-gold);
        font-weight: 600;
    }

    .input-group-text {
        background: #1f1f1f;
        border: 1px solid var(--k-border);
        color: var(--k-text);
        border-radius: var(--k-radius);
    }

    .btn-primary {
        background-color: var(--k-gold);
        border: none;
        color: #000;
        border-radius: var(--k-radius);
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: var(--k-gold-soft);
    }

    .select2-container--default .select2-selection--single {
        background-color: #1f1f1f;
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        color: var(--k-text);
        height: 40px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--k-text);
        line-height: 40px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: var(--k-text) transparent transparent transparent;
    }
    .kemetic-save-btn {
    background: linear-gradient(135deg, #f2c94c, #caa63c);
    color: #000;
    font-weight: 600;
    border-radius: 14px;
    padding: 10px 26px;
}
</style>
@endpush

@section('content')
<form method="post" action="/panel/support/store">
    {{ csrf_field() }}

    <section>
        <h2 class="section-title">{{ trans('panel.create_support_message') }}</h2>

        <div class="mt-25 rounded-sm shadow py-20 px-10 px-lg-25" style="padding: 10px;">

            <div class="form-group">
                <label class="input-label">{{ trans('site.subject') }}</label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title')  is-invalid @enderror"/>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label d-block">{{ trans('public.type') }}</label>
                <select name="type" id="supportType" class="form-control @error('type')  is-invalid @enderror" data-allow-clear="false" data-search="false">
                    <option selected disabled></option>
                    <option value="course_support" @if($errors->has('webinar_id')) selected @endif>{{ trans('panel.course_support') }}</option>
                    <option value="platform_support" @if($errors->has('department_id')) selected @endif>{{ trans('panel.platform_support') }}</option>
                </select>
                @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="departmentInput" class="form-group @if(!$errors->has('department_id')) d-none @endif">
                <label class="input-label d-block">{{ trans('panel.department') }}</label>
                <select name="department_id" id="departments" class="form-control select2 @error('department_id')  is-invalid @enderror" data-allow-clear="false" data-search="false">
                    <option selected disabled></option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->title }}</option>
                    @endforeach
                </select>
                @error('department_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="courseInput" class="form-group @if(!$errors->has('webinar_id')) d-none @endif">
                <label class="input-label d-block">{{ trans('product.course') }}</label>
                <select name="webinar_id" class="form-control select2 @error('webinar_id')  is-invalid @enderror">
                    <option value="" selected disabled>{{ trans('panel.select_course') }}</option>
                    @foreach($webinars as $webinar)
                        <option value="{{ $webinar->id }}">{{ $webinar->title }} - {{ $webinar->creator->full_name }}</option>
                    @endforeach
                </select>
                @error('webinar_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label d-block">{{ trans('site.message') }}</label>
                <textarea name="message" class="form-control" rows="15">{{ old('message') }}</textarea>
            </div>

            <div class="row">
                <div class="col-12 col-lg-8 d-flex align-items-center">
                    <div class="form-group">
                        <label class="input-label">{{ trans('panel.attach_file') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="input-group-text panel-file-manager" data-input="attach" data-preview="holder">
                                    <i data-feather="arrow-up" width="18" height="18" class="text-white"></i>
                                </button>
                            </div>
                            <input type="text" name="attach" id="attach" value="{{ old('attach') }}" class="form-control"/>
                        </div>
                    </div>

                    
                </div>

                 <div class="mt-30" style="padding:10px;">
                     <button type="submit" class="btn kemetic-save-btn">
                    {{ trans('site.send_message') }}
                </button>
            </div>

        </div>
    </section>
</form>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="/assets/default/js/panel/conversations.min.js"></script>
@endpush
