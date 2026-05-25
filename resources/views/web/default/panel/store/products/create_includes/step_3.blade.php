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
                            class="kemetic-upload-btn panel-file-manager-image"
                            data-input="thumbnail"
                            data-preview="holder">
                        <i data-feather="upload" width="18"></i>
                    </button>

                    <input type="text"
                           name="thumbnail"
                           id="thumbnail"
                           value="{{ (!empty($product) && !empty($product->thumbnail)) ? $product->thumbnail : (!empty($cjProduct) ? (is_array($cjProduct['productImage']) ? reset($cjProduct['productImage']) : $cjProduct['productImage']) : old('thumbnail')) }}"
                           class="kemetic-input @error('thumbnail') is-invalid @enderror"
                           placeholder="{{ trans('update.thumbnail_images_size') }}" readonly>

                </div>

                @error('thumbnail')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>


            {{-- PRODUCT IMAGES --}}
            <div id="productImagesInputs" class="form-group kemetic-form-group mt-15">
                <label class="kemetic-label mb-0">{{ trans('update.images') }}</label>

                <div class="main-row input-group product-images-input-group kemetic-input-group mt-10" style="margin-bottom:10px;">
                    <button type="button" class="kemetic-upload-btn panel-file-manager-image" data-input="images_record" data-preview="holder">
                        <i data-feather="upload" width="18"></i>
                    </button>
                    <input type="text" name="images[]" id="images_record" value="{{ (!empty($cjProduct) && !empty($cjProduct['productImageSet']) && (empty($product->images) || count($product->images) == 0)) ? (is_array($cjProduct['productImageSet'][0]) ? reset($cjProduct['productImageSet'][0]) : $cjProduct['productImageSet'][0]) : '' }}" class="form-control" placeholder="{{ trans('update.product_images_size') }}" readonly/>
                    <button type="button" class="kemetic-btn-primary kemetic-icon-btn add-btn">
                        <i data-feather="plus" width="16"></i>
                    </button>
                </div>

                @if(!empty($cjProduct) && !empty($cjProduct['productImageSet']) && count($cjProduct['productImageSet']) > 1 && (empty($product->images) || count($product->images) == 0))
                    @foreach($cjProduct['productImageSet'] as $index => $imagePath)
                        @if($index > 0)
                            <div class="input-group product-images-input-group kemetic-input-group mt-10 cj-prefilled">
                                <button type="button" class="kemetic-upload-btn panel-file-manager-image" data-input="cj_images_{{ $index }}" data-preview="holder">
                                    <i data-feather="upload" width="18"></i>
                                </button>
                                <input type="text" name="images[]" id="cj_images_{{ $index }}" value="{{ is_array($imagePath) ? reset($imagePath) : $imagePath }}" class="form-control" placeholder="{{ trans('update.product_images_size') }}" readonly/>
                                <button type="button" class="kemetic-btn-danger kemetic-icon-btn remove-btn">
                                    <i data-feather="x" width="16"></i>
                                </button>
                            </div>
                        @endif
                    @endforeach
                @endif

                @if(!empty($product->images) && count($product->images))
                    @foreach($product->images as $productImage)
                        <div class="input-group product-images-input-group kemetic-input-group mt-10">
                            <button type="button" class="kemetic-upload-btn panel-file-manager-image" data-input="images_{{ $productImage->id }}" data-preview="holder">
                                <i data-feather="upload" width="18"></i>
                            </button>
                            <input type="text" name="images[]" id="images_{{ $productImage->id }}" value="{{ $productImage->path }}" class="form-control" placeholder="{{ trans('update.product_images_size') }}" readonly/>
                            <button type="button" class="kemetic-btn-danger kemetic-icon-btn remove-btn">
                                <i data-feather="x" width="16"></i>
                            </button>
                        </div>
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
                            class="kemetic-upload-btn panel-file-manager-video"
                            data-input="demo_video"
                            data-preview="holder">
                        <i data-feather="upload" width="18"></i>
                    </button>

                    <input type="text"
                           name="video_demo"
                           id="demo_video"
                           value="{{ !empty($product) ? $product->video_demo : old('video_demo') }}"
                           class="kemetic-input @error('video_demo') is-invalid @enderror" readonly>
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
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $(document).ready(function() {
        $('body').on('click', '.panel-file-manager-image', function (e) {
            e.preventDefault();
            $(this).filemanager('image', {
                prefix: '/laravel-filemanager'
            });
        });

        $('body').on('click', '.panel-file-manager-video', function (e) {
            e.preventDefault();
            $(this).filemanager('video', {
                prefix: '/laravel-filemanager'
            });
        });
    });
</script>
@endpush

@if(!empty($cjProduct))
@push('scripts_bottom')
<script>
(function ($) {
    // For CJ products: override the add-btn so it counts only user-added rows
    // (not the pre-filled CJ image rows) against the max-4 limit.
    function cjRandomString() {
        var text = "", possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        for (var i = 0; i < 4; i++) text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }

    // Remove the global add-btn handler and replace with a CJ-aware one
    $('body').off('click', '.add-btn');
    $('body').on('click', '.add-btn', function (e) {
        e.preventDefault();
        // Allow unlimited images for CJ products
        var icon = feather.icons['x'].toSvg({ width: 18, height: 18 });
        var newKey = cjRandomString();
        var mainRow = $('.main-row');
        var copy = mainRow.clone();
        copy.removeClass('main-row').removeClass('d-none');
        var addBtn = copy.find('.add-btn');
        if (addBtn.length) {
            addBtn.removeClass('add-btn kemetic-btn-primary').addClass('kemetic-btn-danger remove-btn').html(icon);
        }
        var copyHtml = copy.prop('innerHTML');
        copyHtml = copyHtml.replaceAll('record', newKey);
        copyHtml = copyHtml.replaceAll('btn-primary', 'btn-danger');
        copyHtml = copyHtml.replaceAll('add-btn', 'remove-btn');
        copy.html(copyHtml);
        // Clear the value so the new row is always EMPTY
        copy.find('input[type="text"]').val('').prop('readonly', true);
        $('#productImagesInputs').append(copy);
        if (typeof feather !== 'undefined') feather.replace();
    });
})(jQuery);
</script>
@endpush
@endif
