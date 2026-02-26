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

/* Save button */
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

<section class="mt-25">

    <h2 class="section-title kemetic-title mb-20">
        Scrolls
    </h2>

    <form action="/panel/book/{{ (!empty($book) ? $book->id.'/update' : 'store') }}" method="post">
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
                        <label class="input-label">{{ trans('public.cover_image') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button"
                                        class="input-group-text panel-file-manager"
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
                        <label class="input-label">PDF (Front, Spine & Back cover contain the full layout as a single spread pdf)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button"
                                        class="input-group-text panel-file-manager"
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

            <div id="print_fields" class="print-fields" style="{{ (!empty($book) && $book->type == 'Print') ? '' : 'display: none;' }}">
                <div class="form-group">
                    <label class="input-label">Pages</label>
                    <input type="text" id="page_count" name="page_count"
                            class="form-control @error('page_count') is-invalid @enderror"
                            value="{{ !empty($book) ? $book->page_count : old('page_count') }}"
                            placeholder="0" required onchange="calculateLuluPrice()">
                    @error('page_count')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" 
                    <label class="input-label">Shipping Price ({{ $currency }})</label>
                    <input type="text" id="shipping_price" name="shipping_price"
                            class="form-control @error('shipping_price') is-invalid @enderror"
                            value="{{ !empty($book) ? $book->shipping_price : old('shipping_price', '14.85') }}"
                            placeholder="{{ trans('public.0_for_free') }}" required readonly>
                    @error('shipping_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">Print Price ({{ $currency }})</label>
                    <input type="text" id="print_price" name="print_price"
                            class="form-control @error('print_price') is-invalid @enderror"
                            value="{{ !empty($book) ? $book->print_price : old('print_price') }}"
                            placeholder="{{ trans('public.0_for_free') }}" required readonly>
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
                        placeholder="0" required readonly>
                @error('platform_price')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">Total Price ({{ $currency }})</label>
                <input type="text" id="total_price" name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ !empty($book) ? $book->price : old('price') }}"
                        placeholder="{{ trans('public.0_for_free') }}" required readonly>
                @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            

            {{-- DESCRIPTION --}}
            <div class="form-group mt-20">
                <label class="input-label">{{ trans('public.description') }}</label>
                <textarea id="summernote"
                          name="description"
                          class="form-control @error('description') is-invalid @enderror">
{!! (!empty($book) ) ? $book->description : old('description') !!}
                </textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- CONTENT --}}
            <div class="form-group mt-20">
                <label class="input-label">{{ trans('admin/main.content') }}</label>
                <textarea id="summernote"
                          name="content"
                          class="form-control @error('content') is-invalid @enderror">
{!! (!empty($book)) ? $book->content : old('content') !!}
                </textarea>
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
    
    document.addEventListener('DOMContentLoaded', function() {
        togglePrintFields();
        calculateTotalPrice();
    });

    function togglePrintFields() {
        
        const bookType = document.getElementById('book_type').value;
        const printFields = document.getElementById('print_fields');
        const pageCountInput = document.getElementById('page_count');
        const shippingPriceInput = document.getElementById('shipping_price');
        const printPriceInput = document.getElementById('print_price');
        
        if (bookType === 'Print') {
            printFields.style.display = 'block';
            
            // Make print-related fields required for print books
            pageCountInput.required = true;
            shippingPriceInput.required = true;
            printPriceInput.required = true;
            shippingPriceInput.value = '14.85'; // Default shipping price
            calculatePlatformFee();

        } else {
            printFields.style.display = 'none';
            
            // Clear and disable print-related fields for non-print books
            pageCountInput.value = '';
            pageCountInput.required = false;
            
            shippingPriceInput.value = '';
            shippingPriceInput.required = false;
            
            printPriceInput.value = '';
            printPriceInput.required = false;
            calculatePlatformFee();
            // Recalculate total price with zero values
            calculateTotalPrice();
        }
    }

    async function calculateLuluPrice() {

         const bookType = document.getElementById('book_type').value;
        
        // Only calculate Lulu price for Print books
        if (bookType !== 'Print') {
            return;
        }

        const pagesInput = document.getElementById('page_count');
        const printPriceInput = document.getElementById('print_price');
        const bookPriceInput = document.getElementById('book_price');
        
        const pages = pagesInput.value;
        
        if (!pages || pages < 1) {
            alert('Please enter a valid number of pages (minimum 1)');
            pagesInput.focus();
            return;
        }
        
        try {
            // Make AJAX call to get Lulu price
            const response = await fetch('/panel/book/luluprice', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    pages: pages
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                const printPrice = data.print_price;
                
                // Update print price field
                printPriceInput.value = printPrice.toFixed(2);
                
                // Auto-calculate platform fee and total
                //calculatePlatformFee();
                
                // Auto-fill book price suggestion
                if (bookPriceInput.value != '0') {
                    //const suggestedPrice = (printPrice * 1.5).toFixed(2); // 50% markup suggestion
                    //bookPriceInput.value = suggestedPrice;
                    //bookPriceInput.placeholder = 'Suggested: ' + suggestedPrice;
                    calculatePlatformFee();
                }
            } else {
                alert('Error calculating print price: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Network error. Please check your connection and try again.');
        } finally {
            // Hide loading spinner
            printPriceLoading.style.display = 'none';
        }
    }

    // Function to calculate platform fee (10% of book price)
    function calculatePlatformFee() {

        const shippingPrice = parseFloat(document.getElementById('shipping_price').value) || 0;
        const printPrice = parseFloat(document.getElementById('print_price').value) || 0;
        const bookPriceInput = parseFloat(document.getElementById('book_price').value) || 0;
        const platformFeeInput = document.getElementById('platform_price');
        
        const totalPrice = shippingPrice + printPrice + bookPriceInput;
        const platformFee = totalPrice * 0.10; // 10% platform fee
        
        platformFeeInput.value = platformFee.toFixed(2);
        
        // Recalculate total price
        calculateTotalPrice();
    }

    // Function to calculate total price
    function calculateTotalPrice() {

        const shippingPrice = parseFloat(document.getElementById('shipping_price').value) || 0;
        const printPrice = parseFloat(document.getElementById('print_price').value) || 0;
        const platformFee = parseFloat(document.getElementById('platform_price').value) || 0;
        const bookPriceInput = parseFloat(document.getElementById('book_price').value) || 0;
        const totalPriceInput = document.getElementById('total_price');
        
        const totalPrice = shippingPrice + printPrice + bookPriceInput + platformFee;
        totalPriceInput.value = totalPrice.toFixed(2);
    }

    // Add event listeners for manual triggers
    document.getElementById('book_price').addEventListener('input', calculatePlatformFee)
</script>
@endpush
