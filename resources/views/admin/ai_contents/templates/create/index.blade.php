@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ getAdminPanelUrl() }}/ai-contents/templates/{{ !empty($template) ? $template->id .'/update' : 'store' }}" class="">
                        {{ csrf_field() }}

                        @if(!empty(getGeneralSettings('content_translate')))
                            <div class="form-group col-12 col-md-6">
                                <label class="input-label">{{ trans('auth.language') }}</label>
                                <select name="locale" class="form-control {{ !empty($template) ? 'js-edit-content-locale' : '' }}">
                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                    @endforeach
                                </select>
                                @error('locale')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                        @endif


                        <div class="form-group col-12 col-md-6">
                            <label class="control-label">{!! trans('admin/main.title') !!}</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ (!empty($template) and !empty($template->translate($locale))) ? $template->translate($locale)->title : old('title') }}">
                            <div class="invalid-feedback">@error('title') {{ $message }} @enderror</div>
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label class="control-label">{!! trans('admin/main.type') !!}</label>
                            <select name="type" class="js-template-type form-control @error('type') is-invalid @enderror">
                                <option value="">{{ trans('update.select_template_type') }}</option>
                                <option value="text" {{ (!empty($template) and $template->type == "text") ? 'selected' : '' }}>{{ trans('update.text') }}</option>
                                <option value="image" {{ (!empty($template) and $template->type == "image") ? 'selected' : '' }}>{{ trans('update.image') }}</option>
                            </select>

                            <div class="invalid-feedback">@error('type') {{ $message }} @enderror</div>
                        </div>

                        {{-- Prompt --}}
                        <div class="js-prompt-field form-group col-12 col-md-6 {{ !empty($template) ? '' : 'd-none' }}">
                            <label class="control-label">{{ trans('update.prompt') }}</label>
                            <textarea name="prompt" rows="6" class="form-control @error('prompt') is-invalid @enderror">{{ (!empty($template) and !empty($template->translate($locale))) ? $template->translate($locale)->prompt : old('prompt') }}</textarea>
                            <div class="invalid-feedback">@error('prompt') {{ $message }} @enderror</div>

                            <div class="js-text-prompt-hint {{ (empty($template) or $template->type == "text") ? '' : 'd-none' }}">{{ trans('update.ai_content_text_prompt_hint') }}</div>
                            <div class="js-image-prompt-hint {{ (!empty($template) and $template->type == "image") ? '' : 'd-none' }}">{{ trans('update.ai_content_image_prompt_hint') }}</div>
                        </div>


                        {{-- Text Fields --}}
                        <div class="js-text-fields {{ (!empty($template) and $template->type == "text") ? '' : 'd-none' }}">

                            <div class="form-group col-12 col-md-6 custom-switches-stacked">
                                <label class="custom-switch pl-0 d-flex align-items-center">
                                    <input type="hidden" name="enable_length" value="0">
                                    <input type="checkbox" name="enable_length" id="lengthSwitch" value="1" {{ (!empty($template) and $template->enable_length) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description mb-0 cursor-pointer" for="lengthSwitch">{{ trans('update.enable_length') }}</label>
                                </label>
                            </div>

                            <div class="js-length-field form-group col-12 col-md-6 {{ (!empty($template) and $template->enable_length) ? '' : 'd-none' }}">
                                <label class="control-label">{!! trans('update.length') !!}</label>
                                <input type="number" name="length" class="form-control @error('length') is-invalid @enderror" value="{{ !empty($template) ? $template->length : old('length') }}" min="0">
                                <div class="invalid-feedback">@error('length') {{ $message }} @enderror</div>
                            </div>

                        </div>

                        {{-- Image Fields --}}
                        <div class="js-image-fields {{ (!empty($template) and $template->type == "image") ? '' : 'd-none' }}">
                            <div class="form-group col-12 col-md-6">
                                <label class="control-label">{!! trans('update.image_size') !!}</label>
                                <select name="image_size" class=" form-control @error('image_size') is-invalid @enderror">
                                    <option value="">{{ trans('update.select_image_size') }}</option>
                                    <option value="256" {{ (!empty($template) and $template->image_size == "256") ? 'selected' : '' }}>{{ trans('update.256x256') }}</option>
                                    <option value="512" {{ (!empty($template) and $template->image_size == "512") ? 'selected' : '' }}>{{ trans('update.512x512') }}</option>
                                    <option value="1024" {{ (!empty($template) and $template->image_size == "1024") ? 'selected' : '' }}>{{ trans('update.1024x1024') }}</option>
                                </select>
                                <div class="invalid-feedback">@error('image_size') {{ $message }} @enderror</div>
                            </div>

                        </div>

                        <div class="form-group col-12 col-md-6 custom-switches-stacked">
                            <label class="custom-switch pl-0 d-flex align-items-center">
                                <input type="hidden" name="status" value="disabled">
                                <input type="checkbox" name="status" id="statusSwitch" value="active" {{ (!empty($template) and $template->enable) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                <span class="custom-switch-indicator"></span>
                                <label class="custom-switch-description mb-0 cursor-pointer" for="statusSwitch">{{ trans('admin/main.active') }}</label>
                            </label>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary " type="submit">{{ trans('admin/main.save') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/admin/ai_content_create_template.min.js"></script>
@endpush
