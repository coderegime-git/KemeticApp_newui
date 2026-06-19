@push('styles_top')
<style>
:root {
    --k-bg: #0b0b0b;
    --k-card: #141414;
    --k-gold: #f2c94c;
    --k-gold-soft: #e6b93d;
    --k-border: rgba(242, 201, 76, 0.25);
    --k-text: #eaeaea;
    --k-muted: #9a9a9a;
    --k-radius: 16px;
}

/* Accordion card */
.k-accordion-item {
    background: var(--k-card);
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius);
    margin-top: 20px;
    padding: 15px 20px;
    transition: all 0.3s;
}
.k-accordion-item:hover {
    box-shadow: 0 0 15px rgba(242, 201, 76, 0.2);
}

/* Header */
.k-accordion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}
.k-accordion-header .title {
    font-weight: 600;
    color: var(--k-gold);
}
.k-accordion-header .actions i {
    margin-left: 10px;
    color: var(--k-muted);
    cursor: pointer;
}
.k-accordion-header .actions i:hover {
    color: var(--k-gold-soft);
}

/* Disabled badge */
.k-disabled-badge {
    background: var(--k-muted);
    color: var(--k-card);
    font-size: 12px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 8px;
}

/* Collapsible content */
.k-accordion-body {
    margin-top: 15px;
    color: var(--k-text);
}
.k-accordion-body input,
.k-accordion-body textarea,
.k-accordion-body select {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
    border-radius: 12px;
    color: var(--k-text);
    padding: 10px;
    width: 100%;
}

/* Buttons */
.k-accordion-body .btn-primary {
    background-color: var(--k-gold);
    border: none;
    color: #000;
}
.k-accordion-body .btn-primary:hover {
    background-color: var(--k-gold-soft);
}
.k-accordion-body .btn-danger {
    background-color: #ff4c4c;
    color: #fff;
}
.k-accordion-body .btn-danger:hover {
    background-color: #ff2a2a;
}
</style>
@endpush

<li data-id="{{ !empty($file) ? $file->id : '' }}" class="accordion-row k-accordion-item">
    <div class="k-accordion-header" data-toggle="collapse" href="#collapseFile{{ !empty($file) ? $file->id : 'record' }}" aria-controls="collapseFile{{ !empty($file) ? $file->id : 'record' }}" data-parent="#filesAccordion">
        <div class="d-flex align-items-center">
            <i data-feather="file" class="mr-10"></i>
            <span class="title">{{ !empty($file) ? $file->title : trans('public.add_new_files') }}</span>
        </div>

        <div class="actions d-flex align-items-center">
            @if(!empty($file) and $file->status != \App\Models\ProductFile::$Active)
                <span class="k-disabled-badge">{{ trans('public.disabled') }}</span>
            @endif

            <i data-feather="move" class="move-icon"></i>

            @if(!empty($file))
                <a href="/panel/store/products/files/{{ $file->id }}/delete" class="delete-action">
                    <i data-feather="trash-2"></i>
                </a>
            @endif

            <i data-feather="chevron-down" class="collapse-icon"></i>
        </div>
    </div>

    <div id="collapseFile{{ !empty($file) ? $file->id : 'record' }}" class="collapse @if(empty($file)) show @endif k-accordion-body" aria-labelledby="file_{{ !empty($file) ? $file->id : 'record' }}">
        <div class="js-content-form file-form" data-action="/panel/store/products/files/{{ !empty($file) ? $file->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][product_id]" value="{{ !empty($product) ? $product->id : '' }}">

            <div class="row">
                <div class="col-12 col-lg-6">
                    @if(!empty(getGeneralSettings('content_translate')))
                        <div class="form-group">
                            <label class="input-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][locale]"
                                    class="form-control {{ !empty($file) ? 'js-product-content-locale' : '' }}"
                                    data-product-id="{{ !empty($product) ? $product->id : '' }}"
                                    data-id="{{ !empty($file) ? $file->id : '' }}"
                                    data-relation="files"
                                    data-fields="title"
                            >
                                @foreach(getUserLanguagesLists() as $lang => $language)
                                    <option value="{{ $lang }}" {{ (!empty($file) and !empty($file->locale)) ? (mb_strtolower($file->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][locale]" value="{{ $defaultLocale }}">
                    @endif

                    <div class="form-group">
                        <label class="input-label">{{ trans('public.title') }}</label>
                        <input type="text" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($file) ? $file->title : '' }}" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                    </div>

                    <div class="form-group js-file-path-input">
                        <div class="local-input input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="input-group-text panel-file-manager text-white" data-input="file_path{{ !empty($file) ? $file->id : 'record' }}" data-preview="holder">
                                    <i data-feather="upload"></i>
                                </button>
                            </div>
                            <input type="text" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][path]" id="file_path{{ !empty($file) ? $file->id : 'record' }}" value="{{ !empty($file) ? $file->path : '' }}" class="js-ajax-file_path form-control" placeholder="{{ trans('webinars.file_upload_placeholder') }}"/>
                        </div>
                    </div>

                    <div class="row form-group js-file-type-volume">
                        <div class="col-6">
                            <label class="input-label">{{ trans('webinars.file_type') }}</label>
                            <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_type]" class="js-ajax-file_type form-control">
                                <option value="">{{ trans('webinars.select_file_type') }}</option>
                                @foreach(\App\Models\File::$fileTypes as $fileType)
                                    <option value="{{ $fileType }}" @if(!empty($file) and $file->file_type == $fileType) selected @endif>{{ trans('update.file_type_'.$fileType) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="input-label">{{ trans('webinars.file_volume') }}</label>
                            <input type="text" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][volume]" value="{{ !empty($file) ? $file->volume : '' }}" class="js-ajax-volume form-control" placeholder="{{ trans('webinars.online_file_volume') }}"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ trans('public.description') }}</label>
                        <textarea name="ajax[{{ !empty($file) ? $file->id : 'new' }}][description]" rows="4" class="js-ajax-description form-control" placeholder="{{ trans('public.description') }}">{{ !empty($file) ? $file->description : '' }}</textarea>
                    </div>

                    <div class="js-online_viewer-input form-group mt-20 {{ (!empty($file) and $file->file_type == 'pdf') ? '' : 'd-none' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="cursor-pointer input-label" for="online_viewerSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('update.online_viewer') }}</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][online_viewer]" class="custom-control-input" id="online_viewerSwitch{{ !empty($file) ? $file->id : '_record' }}" {{ (!empty($file) and $file->online_viewer) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="online_viewerSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-20">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="cursor-pointer input-label" for="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('public.active') }}</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][status]" class="custom-control-input" id="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}" {{ (empty($file) or $file->status == \App\Models\File::$Active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-30 d-flex align-items-center">
                <button type="button" class="js-save-file btn btn-primary">{{ trans('public.save') }}</button>
                @if(empty($file))
                    <button type="button" class="btn btn-danger ml-10 cancel-accordion">{{ trans('public.close') }}</button>
                @endif
            </div>
        </div>
    </div>
</li>
