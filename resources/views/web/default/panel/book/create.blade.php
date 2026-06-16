@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">

<style>
/* ===============================
   KEMETIC BLOG CREATE / EDIT
================================ */
.kemetic-form-card {
    background: linear-gradient(180deg, #0b0b0b, #121212);
    border: 1px solid rgba(242,201,76,0.25);
    border-radius: 20px;
    padding: 25px;
}

/* Titles */
.kemetic-title {
    color: #f2c94c;
    font-weight: 600;
}

/* Labels */
.kemetic-form-card .input-label {
    color: #c9b26d;
    font-size: 13px;
}

/* Inputs */
.kemetic-form-card .form-control {
    background: #0e0e0e;
    border: 1px solid rgba(242,201,76,0.3);
    color: #fff;
    border-radius: 12px;
}

.kemetic-form-card .form-control:focus {
    border-color: #f2c94c;
    box-shadow: 0 0 0 2px rgba(242,201,76,0.15);
}

/* Select */
.kemetic-form-card select option {
    background: #0e0e0e;
}

/* File manager */
.kemetic-form-card .input-group-text {
    background: linear-gradient(135deg, #f2c94c, #caa63c);
    border: none;
}

/* Summernote */
.note-editor.note-frame {
    background: #0e0e0e;
    border: 1px solid rgba(242,201,76,0.3);
    border-radius: 16px;
}

.note-toolbar {
    background: #151515;
    border-bottom: 1px solid rgba(242,201,76,0.25);
}

.note-editor .note-editable {
    background: #0e0e0e;
    color: #fff;
}

/* Summernote error highlight */
.note-editor.border-danger {
    border: 1px solid #dc3545 !important;
    border-radius: 16px;
}

/* Save button */
.kemetic-save-btn {
    background: linear-gradient(135deg, #f2c94c, #caa63c);
    color: #000;
    font-weight: 600;
    border-radius: 14px;
    padding: 10px 26px;
}

/* Summernote Modal Fixes */
.note-modal .modal-content {
    background-color: #121212 !important;
    color: #fff !important;
    border: 1px solid rgba(242,201,76,0.3);
}
.note-modal .modal-header {
    border-bottom: 1px solid rgba(242,201,76,0.25);
}
.note-modal .modal-title, .note-modal label, .note-modal label small {
    color: #f2c94c !important;
}
.note-modal .text-muted {
    color: #c9b26d !important;
}
.note-modal .close {
    color: #fff !important;
    opacity: 1 !important;
    background: transparent !important;
    text-shadow: none !important;
    border: none !important;
}
.note-modal .form-control {
    background: #0e0e0e !important;
    border: 1px solid rgba(242,201,76,0.3) !important;
    color: #fff !important;
}
.note-modal .btn-primary {
    background: linear-gradient(135deg, #f2c94c, #caa63c) !important;
    color: #000 !important;
    border: none !important;
}
.note-modal .checkbox input {
    margin-right: 5px;
}
</style>
@endpush

@section('content')

<section class="mt-25">

    <h2 class="section-title kemetic-title mb-20">
        Scrolls
    </h2>

    <form id="bookForm" action="/panel/book/{{ (!empty($book) ? $book->id.'/update' : 'store') }}" method="post">
        {{ csrf_field() }}

        <div class="kemetic-form-card">

            <div class="row">
                <div class="col-12 col-md-6">

                    {{-- LANGUAGE --}}
                    @if(!empty(getGeneralSettings('content_translate')) && !empty($userLanguages))
                        <div class="form-group">
                            <label class="input-label">{{ trans('auth.language') }}</label>
                            <select name="locale"
                                    class="form-control {{ !empty($book) ? 'js-edit-content-locale' : '' }}" required>
                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}"
                                        {{ mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang) ? 'selected' : '' }}>
                                        {{ $language }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                    @endif

                    {{-- TITLE --}}
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.title') }}</label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ (!empty($book)) ? $book->title : old('title') }}"
                               placeholder="{{ trans('admin/main.choose_title') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.type') }}</label>
                        <select name="type" id="book_type" class="form-control kemetic-select" required onchange="togglePrintFields()">
                            <option value="">Choose type</option>
                            <option value="E-book" {{ (!empty($book) && $book->type == 'E-book') ? 'selected' : '' }}>E-book</option>
                            <option value="Audio Book" {{ (!empty($book) && $book->type == 'Audio Book') ? 'selected' : '' }}>Audio Book</option>
                            <option value="Print" {{ (!empty($book) && $book->type == 'Print') ? 'selected' : '' }}>Print</option>
                        </select>
                    </div>

                    {{-- CATEGORY --}}
                    <div class="form-group">
                        <label class="input-label">{{ trans('/admin/main.category') }}</label>
                        <select name="category_id"
                                class="form-control @error('category_id') is-invalid @enderror" required>
                            <option disabled selected>{{ trans('admin/main.choose_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ (!empty($book) && $book->category_id == $category->id) ? 'selected' : '' }}>
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- COVER IMAGE --}}
                    <div class="form-group">
                        <label class="input-label">{{ trans('public.thumbnail_image') }} Image</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button"
                                        class="input-group-text panel-file-manager-image"
                                        data-input="image"
                                        data-preview="holder">
                                    <i data-feather="upload" width="18"></i>
                                </button>
                            </div>
                            <input type="text" name="image_cover" id="image"
                                   value="{{ !empty($book) ? $book->image_cover : old('image_cover') }}"
                                   class="form-control @error('image') is-invalid @enderror"
                                   placeholder="{{ trans('update.blog_cover_image_placeholder') }}" required>
                        </div>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">Cover PDF</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button"
                                        class="input-group-text panel-file-manager-pdf"
                                        data-input="cover_pdf"
                                        data-preview="holder">
                                    <i data-feather="upload" width="18"></i>
                                </button>
                            </div>
                            <input type="text" name="cover_pdf" id="cover_pdf"
                                   value="{{ !empty($book) ? $book->cover_pdf : old('cover_pdf') }}"
                                   class="form-control @error('cover_pdf') is-invalid @enderror"
                                   placeholder="{{ trans('update.blog_cover_image_placeholder') }}" required>
                        </div>
                        @error('cover_pdf')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">PDF (Front, Spine & Back cover contain the full layout as a single spread pdf)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button"
                                        class="input-group-text panel-file-manager-pdf"
                                        data-input="image_path"
                                        data-preview="holder">
                                    <i data-feather="upload" width="18"></i>
                                </button>
                            </div>
                            <input type="text" name="image_path" id="image_path"
                                   value="{{ !empty($book) ? $book->url : old('image_path') }}"
                                   class="form-control @error('image_path') is-invalid @enderror" required>
                        </div>
                        @error('image_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- PRINT ONLY FIELDS --}}
            <div id="print_fields" class="print-fields" style="{{ (!empty($book) && $book->type == 'Print') ? '' : 'display: none;' }}">
                <div class="form-group">
                    <label class="input-label">Pages</label>
                    <input type="text" id="page_count" name="page_count"
                            class="form-control @error('page_count') is-invalid @enderror"
                            value="{{ !empty($book) ? $book->page_count : old('page_count') }}"
                            placeholder="0" onchange="calculateLuluPrice()">
                    @error('page_count')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">Shipping Price ({{ $currency }})</label>
                    <input type="text" id="shipping_price" name="shipping_price"
                            class="form-control @error('shipping_price') is-invalid @enderror"
                            value="{{ !empty($book) ? $book->shipping_price : old('shipping_price', '14.85') }}"
                            placeholder="{{ trans('public.0_for_free') }}" readonly>
                    @error('shipping_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">Print Price ({{ $currency }})</label>
                    <input type="text" id="print_price" name="print_price"
                            class="form-control @error('print_price') is-invalid @enderror"
                            value="{{ !empty($book) ? $book->print_price : old('print_price') }}"
                            placeholder="{{ trans('public.0_for_free') }}" readonly>
                    @error('print_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="input-label">Your Price ({{ $currency }})</label>
                <input type="text" id="book_price" name="book_price"
                        class="form-control @error('book_price') is-invalid @enderror"
                        value="{{ !empty($book) ? $book->book_price : old('book_price') }}"
                        placeholder="{{ trans('public.0_for_free') }}" required>
                @error('book_price')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">Platform Price ({{ $currency }})</label>
                <input type="text" id="platform_price" name="platform_price"
                        class="form-control @error('platform_price') is-invalid @enderror"
                        value="{{ !empty($book) ? $book->platform_price : old('platform_price') }}"
                        placeholder="0" readonly>
                @error('platform_price')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">Total Price ({{ $currency }})</label>
                <input type="text" id="total_price" name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ !empty($book) ? $book->price : old('price') }}"
                        placeholder="{{ trans('public.0_for_free') }}" readonly>
                @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- DESCRIPTION — unique id: summernote_description --}}
            <div class="form-group mt-20">
                <label class="input-label">{{ trans('public.description') }}</label>
                <textarea id="summernote_description"
                          name="description"
                          class="form-control @error('description') is-invalid @enderror">
{!! (!empty($book)) ? $book->description : old('description') !!}
                </textarea>
                <div class="invalid-feedback" id="description_error" style="display:none;">
                    The description field is required.
                </div>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- CONTENT — unique id: summernote_content --}}
            <div class="form-group mt-20">
                <label class="input-label">{{ trans('admin/main.content') }}</label>
                <textarea id="summernote_content"
                          name="content"
                          class="form-control @error('content') is-invalid @enderror">
{!! (!empty($book)) ? $book->content : old('content') !!}
                </textarea>
                <div class="invalid-feedback" id="content_error" style="display:none;">
                    The content field is required.
                </div>
                @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- SAVE --}}
            <div class="mt-30" style="padding:10px;">
                <button type="submit" class="btn kemetic-save-btn">
                    {{ trans('admin/main.save_change') }}
                </button>
            </div>

        </div>
    </form>

</section>
@endsection

@push('scripts_bottom')
<script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
<script>

$(document).ready(function () {

    // ── File managers ──────────────────────────────────────────────────────
    if ($.fn.filemanager) {
        $('.panel-file-manager-image').filemanager('image', {
            prefix: '/laravel-filemanager'
        });
    }
    $('.panel-file-manager-pdf').filemanager('pdf', {
        prefix: '/laravel-filemanager'
    });

    // ── Summernote: description ────────────────────────────────────────────
    $('#summernote_description').summernote({
        height: 250,
        callbacks: {
            onChange: function () {
                $('textarea[name="description"]').val(
                    $('#summernote_description').summernote('code')
                );
            }
        }
    });

    // ── Summernote: content ────────────────────────────────────────────────
    $('#summernote_content').summernote({
        height: 250,
        callbacks: {
            onChange: function () {
                $('textarea[name="content"]').val(
                    $('#summernote_content').summernote('code')
                );
            }
        }
    });

    // ── Initial state ──────────────────────────────────────────────────────
    togglePrintFields();
    calculateTotalPrice();
});

// ── Fix Summernote modal close button (Bootstrap 5) ────────────────────────
document.addEventListener('click', function (e) {
    if (e.target.closest('.note-modal .close')) {
        var modal = e.target.closest('.note-modal');
        if (modal) $(modal).modal('hide');
    }
});

// ── Show / hide print-only fields ─────────────────────────────────────────
function togglePrintFields() {
    var bookType          = document.getElementById('book_type').value;
    var printFields       = document.getElementById('print_fields');
    var pageCountInput    = document.getElementById('page_count');
    var shippingPriceInput = document.getElementById('shipping_price');
    var printPriceInput   = document.getElementById('print_price');

    if (bookType === 'Print') {
        printFields.style.display    = 'block';
        pageCountInput.required      = true;
        shippingPriceInput.required  = true;
        printPriceInput.required     = true;
        shippingPriceInput.value     = '14.85';
        calculatePlatformFee();
    } else {
        printFields.style.display    = 'none';
        pageCountInput.value         = '';
        pageCountInput.required      = false;
        shippingPriceInput.value     = '';
        shippingPriceInput.required  = false;
        printPriceInput.value        = '';
        printPriceInput.required     = false;
        calculatePlatformFee();
        calculateTotalPrice();
    }
}

// ── Fetch Lulu print price ─────────────────────────────────────────────────
async function calculateLuluPrice() {
    var bookType = document.getElementById('book_type').value;
    if (bookType !== 'Print') return;

    var pagesInput     = document.getElementById('page_count');
    var printPriceInput = document.getElementById('print_price');
    var bookPriceInput  = document.getElementById('book_price');
    var pages          = pagesInput.value;

    if (!pages || pages < 1) {
        alert('Please enter a valid number of pages (minimum 1)');
        pagesInput.focus();
        return;
    }

    try {
        var response = await fetch('/panel/book/luluprice', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ pages: pages })
        });

        var data = await response.json();

        if (data.success) {
            printPriceInput.value = data.print_price.toFixed(2);
            if (bookPriceInput.value != '0') {
                calculatePlatformFee();
            }
        } else {
            alert('Error calculating print price: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Network error. Please check your connection and try again.');
    }
}

// ── Calculate platform fee (10% of total) ─────────────────────────────────
function calculatePlatformFee() {
    var shipping    = parseFloat(document.getElementById('shipping_price').value) || 0;
    var print       = parseFloat(document.getElementById('print_price').value)    || 0;
    var book        = parseFloat(document.getElementById('book_price').value)      || 0;
    var fee         = (shipping + print + book) * 0.10;

    document.getElementById('platform_price').value = fee.toFixed(2);
    calculateTotalPrice();
}

// ── Calculate total price ──────────────────────────────────────────────────
function calculateTotalPrice() {
    var shipping = parseFloat(document.getElementById('shipping_price').value)  || 0;
    var print    = parseFloat(document.getElementById('print_price').value)     || 0;
    var platform = parseFloat(document.getElementById('platform_price').value)  || 0;
    var book     = parseFloat(document.getElementById('book_price').value)      || 0;

    document.getElementById('total_price').value = Math.round(shipping + print + book + platform);
}

// ── Live recalculate on book price change ──────────────────────────────────
document.getElementById('book_price').addEventListener('input', calculatePlatformFee);

// ── Form submit with full validation ──────────────────────────────────────
document.getElementById('bookForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Sync Summernote HTML into hidden textareas before validating
    $('textarea[name="description"]').val($('#summernote_description').summernote('code'));
    $('textarea[name="content"]').val($('#summernote_content').summernote('code'));

    var firstError = null;

    // ── 1. Validate all native required fields ─────────────────────────
    var self = this;
    self.querySelectorAll('[required]').forEach(function (field) {
        // Skip fields inside hidden print_fields when type is not Print
        if (field.offsetParent === null) {
            field.classList.remove('is-invalid');
            return;
        }

        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            if (!firstError) firstError = field;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    // ── 2. Validate Summernote Description ────────────────────────────
    var descRaw   = $('textarea[name="description"]').val() || '';
    var descText  = descRaw.replace(/<[^>]*>/g, '').trim();
    var descEditor = $('#summernote_description').closest('.form-group').find('.note-editor');
    var descErrorEl = document.getElementById('description_error');

    if (!descText) {
        descEditor.addClass('border-danger');
        descErrorEl.style.display = 'block';
        if (!firstError) firstError = descEditor[0];
    } else {
        descEditor.removeClass('border-danger');
        descErrorEl.style.display = 'none';
    }

    // ── 3. Validate Summernote Content ────────────────────────────────
    var contentRaw   = $('textarea[name="content"]').val() || '';
    var contentText  = contentRaw.replace(/<[^>]*>/g, '').trim();
    var contentEditor = $('#summernote_content').closest('.form-group').find('.note-editor');
    var contentErrorEl = document.getElementById('content_error');

    if (!contentText) {
        contentEditor.addClass('border-danger');
        contentErrorEl.style.display = 'block';
        if (!firstError) firstError = contentEditor[0];
    } else {
        contentEditor.removeClass('border-danger');
        contentErrorEl.style.display = 'none';
    }

    // ── 4. If any errors, scroll to first and stop ─────────────────────
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    // ── 5. All valid — show loader and submit ──────────────────────────
    Swal.fire({
        html: '<div class="d-flex align-items-center justify-content-center py-20">' +
              '<div class="spinner-border text-primary" role="status"></div>' +
              '<span class="ml-15 font-16">Please wait...</span></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        width: '20rem'
    });

    this.submit();
});

</script>
@endpush