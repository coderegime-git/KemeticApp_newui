@push('styles_top')
<link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>

<style>
/* ================= KEMETIC CARDS ================= */
.kemetic-card {
    background: #0c0c0c;
    border: 1px solid rgba(242,201,76,.18);
    border-radius: 16px;
    padding: 22px;
    box-shadow: 0 15px 45px rgba(0,0,0,.65);
}

/* Header */
.kemetic-card-header {
    border-bottom: 1px solid rgba(242,201,76,.15);
    padding-bottom: 12px;
}

/* Title */
.kemetic-title {
    color: #F2C94C;
    font-size: 16px;
    font-weight: 700;
}

/* Hint */
.kemetic-hint {
    font-size: 13px;
    color: #aaa;
}

/* ================= FORMS ================= */
.kemetic-label {
    font-size: 13px;
    font-weight: 600;
    color: #F2C94C;
    margin-bottom: 6px;
    display: block;
}

.kemetic-input-group {
    display: flex;
    gap: 8px;
}

.kemetic-input {
    flex: 1;
    background: #111;
    border: 1px solid rgba(242,201,76,.25);
    color: #fff;
    border-radius: 10px;
    padding: 10px 14px;
}

.kemetic-input:focus {
    outline: none;
    border-color: #F2C94C;
    box-shadow: 0 0 0 2px rgba(242,201,76,.2);
}

/* Upload button */
.kemetic-upload-btn {
    width: 42px;
    background: linear-gradient(135deg,#F2C94C,#d4a017);
    border: none;
    border-radius: 10px;
    color: #000;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ================= BUTTONS ================= */
.kemetic-btn-primary {
    background: linear-gradient(135deg,#F2C94C,#d4a017);
    color: #000;
    border: none;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 10px;
}

.kemetic-btn-danger {
    background: #ff4d4f;
    border: none;
    color: #fff;
    border-radius: 10px;
    padding: 8px 12px;
}

.kemetic-icon-btn {
    width: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kemetic-btn-sm {
    padding: 6px 14px;
    font-size: 13px;
}

/* Muted */
.kemetic-muted {
    font-size: 12px;
    color: #888;
}

/* Sortable list */
.kemetic-sortable > li {
    margin-bottom: 14px;
}

</style>
@endpush

<div class="row">

    {{-- ================= VIRTUAL PRODUCT FILES ================= --}}
    @if($product->isVirtual())
        <div class="col-12 mt-30">

            <div class="kemetic-card">
                <div class="kemetic-card-header d-flex align-items-center justify-content-between">
                    <h2 class="kemetic-title">{{ trans('public.files') }}</h2>

                    <button id="productAddFile"
                            data-product-id="{{ $product->id }}"
                            type="button"
                            class="kemetic-btn-primary kemetic-btn-sm">
                        + {{ trans('public.add_new_files') }}
                    </button>
                </div>

                <p class="kemetic-hint mt-10">
                    {{ trans('update.product_files_hint_1') }}
                </p>

                <div class="accordion-content-wrapper mt-20"
                     id="filesAccordion"
                     role="tablist"
                     aria-multiselectable="true">

                    @if(!empty($product->files) && count($product->files))
                        <ul class="draggable-lists kemetic-sortable"
                            data-order-path="/panel/store/products/files/order-items">

                            @foreach($product->files as $fileInfo)
                                @include('web.default.panel.store.products.create_includes.accordions.file',[
                                    'file' => $fileInfo
                                ])
                            @endforeach
                        </ul>
                    @else
                        @include(getTemplate() . '.includes.no-result',[
                            'file_name' => 'files.png',
                            'title' => trans('public.files_no_result'),
                            'hint' => trans('public.files_no_result_hint'),
                        ])
                    @endif
                </div>

                <div id="newFileForm" class="d-none">
                    @include('web.default.panel.store.products.create_includes.accordions.file')
                </div>
            </div>
        </div>
    @endif


    {{-- ================= MEDIA SECTION ================= --}}
    <div class="col-12 col-md-6 mt-30">

        <div class="kemetic-card">

            {{-- THUMBNAIL --}}
            <div class="form-group kemetic-form-group">
                <label class="kemetic-label">{{ trans('public.thumbnail_image') }}</label>

                <div class="kemetic-input-group">
                    <button type="button"
                            class="kemetic-upload-btn panel-file-manager"
                            data-input="thumbnail"
                            data-preview="holder">
                        <i data-feather="upload" width="18"></i>
                    </button>

                    <input type="text"
                           name="thumbnail"
                           id="thumbnail"
                           value="{{ !empty($product) ? $product->thumbnail : old('thumbnail') }}"
                           class="kemetic-input @error('thumbnail') is-invalid @enderror"
                           placeholder="{{ trans('update.thumbnail_images_size') }}">

                </div>

                @error('thumbnail')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>


            {{-- PRODUCT IMAGES --}}
            <div id="productImagesInputs" class="form-group kemetic-form-group mt-15">
                <label class="kemetic-label mb-0">{{ trans('update.images') }}</label>

                <div class="main-row input-group product-images-input-group kemetic-input-group mt-10" style="margin-bottom:10px;">
                    <!-- <div class="kemetic-input-group"> -->
                        <button type="button" class="kemetic-upload-btn panel-file-manager" data-input="images_record" data-preview="holder">
                            <i data-feather="upload" width="18"></i>
                        </button>
                    <!-- </div> -->
                    <input type="text" name="images[]" id="images_record" value="" class="form-control" placeholder="{{ trans('update.product_images_size') }}"/>

                    <button type="button" class="kemetic-btn-primary kemetic-icon-btn add-btn">
                        <i data-feather="plus" width="16"></i>
                    </button>
                </div>

                @if(!empty($product->images) and count($product->images))
                    @foreach($product->images as $productImage)
                        <div class="input-group product-images-input-group kemetic-input-group mt-10" style="margin-top:10px;">
                            <!-- <div class="kemetic-input-group"> -->
                                <button type="button" class="kemetic-upload-btn panel-file-manager" data-input="images_{{ $productImage->id }}" data-preview="holder">
                                    <i data-feather="upload" width="18"></i>
                                </button>
                            </div>
                            <input type="text" name="images[]" id="images_{{ $productImage->id }}" value="{{ $productImage->path }}" class="form-control" placeholder="{{ trans('update.product_images_size') }}"/>

                             <button type="button" class="kemetic-btn-danger kemetic-icon-btn remove-btn">
                                <i data-feather="x" width="16"></i>
                            </button>
                        <!-- </div> -->
                    @endforeach
                @endif

                @error('images')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- DEMO VIDEO --}}
            <div class="form-group kemetic-form-group mt-25">
                <label class="kemetic-label">
                    {{ trans('public.demo_video') }}
                    <span class="kemetic-muted">({{ trans('public.optional') }})</span>
                </label>

                <div class="kemetic-input-group">
                    <button type="button"
                            class="kemetic-upload-btn panel-file-manager"
                            data-input="demo_video"
                            data-preview="holder">
                        <i data-feather="upload" width="18"></i>
                    </button>

                    <input type="text"
                           name="video_demo"
                           id="demo_video"
                           value="{{ !empty($product) ? $product->video_demo : old('video_demo') }}"
                           class="kemetic-input @error('video_demo') is-invalid @enderror">
                </div>

                @error('video_demo')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>


@push('scripts_bottom')
<script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
