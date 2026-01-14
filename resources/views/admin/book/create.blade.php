@extends('admin.layouts.app')
@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ !empty($book) ? 'Edit Book' : 'Create New Book' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl() }}/book">Books</a></div>
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
                                                   placeholder="{{ trans('admin/main.choose_title') }}"/>
                                            @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('/admin/main.category') }}</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
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
                                                <input type="text" name="image_cover" id="cover_image" value="{{ !empty($book) ? $book->image_cover : old('image_cover') }}" class="form-control @error('image_cover')  is-invalid @enderror"/>
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
                                            <label class="input-label">{{ trans('update.path') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager" data-input="path_image" data-preview="holder">
                                                        <i class="fa fa-upload"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="image_path" id="path_image" value="{{ !empty($book) ? $book->url : old('image_path') }}" class="form-control @error('image_path')  is-invalid @enderror"/>
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

                                       <div class="form-group">
                                            <label class="input-label">Shipping Price ({{ $currency }})</label>
                                            <input type="number" id="shipping_price" name="shipping_price" value="{{ !empty($book) ? $book->shipping_price : old('shipping_price', '14.85') }}" class="form-control @error('shipping_price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}"/>
                                            @error('shipping_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">Book Price ({{ $currency }})</label>
                                            <input type="number" id="book_price" name="book_price" value="{{ !empty($book) ? $book->book_price : old('book_price') }}" class="form-control @error('book_price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}"/>
                                            @error('book_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('public.price') }} ({{ $currency }})</label>
                                            <input type="number" id="total_price" name="price" value="{{ !empty($book) ? $book->price : old('price') }}" class="form-control @error('price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}"/>
                                            @error('price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.type') }}</label>
                                            <select class="form-control @error('type') is-invalid @enderror" name="type">
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
        
        // alert('Note: Shipping price is fixed at 14.85 and cannot be changed.');

        // Get price elements
        const shippingInput = document.getElementById('shipping_price');
        const bookInput = document.getElementById('book_price');
        const totalInput = document.getElementById('total_price');

        // Function to calculate total price
        function calculateTotalPrice() {
            // alert('Calculating total price...');
            const shippingPrice = parseFloat(shippingInput.value) || 0;
            const bookPrice = parseFloat(bookInput.value) || 0;
            const totalPrice = shippingPrice + bookPrice;
            
            // Update total price input
            totalInput.value = totalPrice.toFixed(2);
        }

        // Add event listeners to price inputs
        shippingInput.addEventListener('input', calculateTotalPrice);
        bookInput.addEventListener('input', calculateTotalPrice);
        
        // Also update when shipping input loses focus (for manual editing)
        shippingInput.addEventListener('change', calculateTotalPrice);
        bookInput.addEventListener('change', calculateTotalPrice);

        // Initialize calculation on page load
        calculateTotalPrice();
    });
    </script>
@endpush
