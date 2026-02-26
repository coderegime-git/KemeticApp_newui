@extends('admin.layouts.app')
@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ !empty($book) ? 'Edit Scrolls' : 'Create New Scrolls' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl() }}/book">Scrolls</a></div>
                <div class="breadcrumb-item">{{ !empty($book) ? 'Edit' : 'Create' }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl() }}/book/{{ !empty($book) ? $book->id.'/update' : 'store' }}" method="post">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        @if(!empty(getGeneralSettings('content_translate')) and !empty($userLanguages))
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('auth.language') }}</label>
                                                <select name="locale" class="form-control {{ !empty($book) ? 'js-edit-content-locale' : '' }}">
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

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.title') }}</label>
                                            <input type="text" name="title"
                                                   class="form-control  @error('title') is-invalid @enderror"
                                                   value="{{ !empty($book) ? $book->title : old('title') }}"
                                                   placeholder="{{ trans('admin/main.choose_title') }}" required/>
                                            @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.type') }}</label>
                                            <select class="form-control @error('type') is-invalid @enderror" required name="type" id="book_type" onchange="togglePrintFields()">
                                                <option value="" {{ empty($book) ? 'selected' : '' }}>{{ trans('admin/main.choose_type') }}</option>
                                                <option value="E-book" {{ (!empty($book) && $book->type == 'E-book') || old('type') == 'E-book' ? 'selected' : '' }}>E-book</option>
                                                <option value="Audio Book" {{ (!empty($book) && $book->type == 'Audio Book') || old('type') == 'Audio Book' ? 'selected' : '' }}>Audio Book</option>
                                                <option value="Print" {{ (!empty($book) && $book->type == 'Print') || old('type') == 'Print' ? 'selected' : '' }}>Print</option>                                                
                                            </select>

                                            @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('/admin/main.category') }}</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                                                <option {{ !empty($trend) ? '' : 'selected' }} disabled>{{ trans('admin/main.choose_category') }}</option>

                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ (((!empty($book) and $book->category_id == $category->id) or (old('category_id') == $category->id)) ? 'selected="selected"' : '') }}>{{ $category->title }}</option>
                                                @endforeach
                                            </select>

                                            @error('category_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-15">
                                            <label class="input-label">{{ trans('public.cover_image') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager" data-input="cover_image" data-preview="holder">
                                                        <i class="fa fa-upload"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="image_cover" id="cover_image" value="{{ !empty($book) ? $book->image_cover : old('image_cover') }}" class="form-control @error('image_cover')  is-invalid @enderror" required/>
                                                <div class="input-group-append">
                                                    <button type="button" class="input-group-text admin-file-view" data-input="cover_image">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                                @error('image_cover')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group mt-15">
                                            <label class="input-label">PDF (Front, Spine & Back cover contain the full layout as a single spread pdf)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager" data-input="path_image" data-preview="holder">
                                                        <i class="fa fa-upload"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="image_path" id="path_image" value="{{ !empty($book) ? $book->url : old('image_path') }}" class="form-control @error('image_path')  is-invalid @enderror" required/>
                                                <!-- <div class="input-group-append">
                                                    <button type="button" class="input-group-text admin-file-view" data-input="path_image">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div> -->
                                                @error('image_path')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div id="print_fields" class="print-fields" style="{{ (!empty($book) && $book->type == 'Print') ? '' : 'display: none;' }}">
                                            <div class="form-group">
                                                <label class="input-label">Pages</label>
                                                <input type="number" id="page_count" name="page_count" value="{{ !empty($book) ? $book->page_count : old('page_count') }}" class="form-control @error('page_count')  is-invalid @enderror" placeholder="0" onchange="calculateLuluPrice()"/>
                                                @error('page_count')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="input-label">Shipping Price ({{ $currency }})</label>
                                                <input type="number" id="shipping_price" name="shipping_price" value="{{ !empty($book) ? $book->shipping_price : old('shipping_price', '14.85') }}" 
                                                class="form-control @error('shipping_price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}" readonly/>
                                                @error('shipping_price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="input-label">Print Price ({{ $currency }})</label>
                                                <input type="number" id="print_price" name="print_price" value="{{ !empty($book) ? $book->print_price : old('print_price') }}" 
                                                class="form-control @error('print_price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}" readonly/>
                                                @error('print_price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">Your Price ({{ $currency }})</label>
                                            <input type="number" id="book_price" name="book_price" value="{{ !empty($book) ? $book->book_price : old('book_price') }}" class="form-control @error('book_price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}"/>
                                            @error('book_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">Platform Price ({{ $currency }})</label>
                                            <input type="number" id="platform_price" name="platform_price" value="{{ !empty($book) ? $book->platform_price : old('platform_price') }}" class="form-control @error('platform_price')  is-invalid @enderror" placeholder="0" required readonly/>
                                            @error('platform_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">Total Price ({{ $currency }})</label>
                                            <input type="number" id="total_price" name="price" value="{{ !empty($book) ? $book->price : old('price') }}" class="form-control @error('price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}" required readonly/>
                                            @error('price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-15">
                                    <label class="input-label">{{ trans('public.description') }}</label>
                                    <div class="text-muted text-small mb-3">{{ trans('admin/main.create_blog_description_hint') }}</div>
                                    <textarea id="summernote" name="description" class="summernote form-control @error('description')  is-invalid @enderror" placeholder="{{ trans('admin/main.description_placeholder') }}">{!! !empty($book) ? $book->description : old('description')  !!}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group mt-15">
                                    <label class="input-label">{{ trans('admin/main.content') }}</label>
                                    <div class="text-muted text-small mb-3">{{ trans('admin/main.create_blog_content_hint') }}</div>
                                    <textarea id="contentSummernote" name="content" class="summernote form-control @error('content')  is-invalid @enderror" placeholder="{{ trans('admin/main.content_placeholder') }}">{!! !empty($book) ? $book->content : old('content')  !!}</textarea>
                                    @error('content')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class=" mt-4">
                                    <button type="submit" class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            shippingPriceInput.value = '14.85'; 
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
            const response = await fetch('/admin/book/luluprice', {
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
