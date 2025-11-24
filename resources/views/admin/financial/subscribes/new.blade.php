@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.new_subscribe') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.subscribes') }}</div>
            </div>
        </div>


        <div class="section-body card">

            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <h2 class="section-title ml-4">{{ !empty($subscribe) ? trans('admin/main.edit') : trans('admin/main.create') }}</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                        <div class="card-body">
                            <form id="subscribeForm" action="{{ getAdminPanelUrl() }}/financial/subscribes/{{ !empty($subscribe) ? $subscribe->id.'/update' : 'store' }}" method="Post">
                                {{ csrf_field() }}

                                @if(!empty(getGeneralSettings('content_translate')))
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('auth.language') }}</label>
                                        <select name="locale" class="form-control {{ !empty($subscribe) ? 'js-edit-content-locale' : '' }}">
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
                                           value="{{ !empty($subscribe) ? $subscribe->title : old('title') }}"/>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('admin/main.short_description') }} ({{ trans('admin/main.optional') }})</label>
                                    <input type="text" name="description"
                                           class="form-control "
                                           value="{{ !empty($subscribe) ? $subscribe->description : old('description') }}"
                                           placeholder="{{ trans('admin/main.short_description_placeholder') }}"
                                    />
                                </div>


                                <div class="form-group">
                                    <label>{{ trans('admin/main.usable_count') }}</label>
                                    <input type="text" name="usable_count"
                                           class="form-control  @error('usable_count') is-invalid @enderror"
                                           value="{{ !empty($subscribe) ? $subscribe->usable_count : old('usable_count') }}"/>
                                    @error('usable_count')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('admin/main.days') }}</label>
                                    <input type="text" name="days"
                                           class="form-control  @error('days') is-invalid @enderror"
                                           value="{{ !empty($subscribe) ? $subscribe->days : old('days') }}"/>
                                    @error('days')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label>{{ trans('admin/main.price') }} ({{ $currency }})</label>
                                    <input type="text" name="price" id="priceInput"
                                           class="form-control  @error('price') is-invalid @enderror"
                                           value="{{ !empty($subscribe) ? $subscribe->price : old('price') }}"/>
                                    @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('admin/main.priceid') }}</label>
                                    <input type="text" name="price_id" id="priceIdInput"
                                           class="form-control  @error('price_id') is-invalid @enderror"
                                           value="{{ !empty($subscribe) ? $subscribe->price_id : old('price_id') }}"/>
                                    @error('price_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group mt-15">
                                    <label class="input-label">{{ trans('admin/main.icon') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="input-group-text admin-file-manager" data-input="icon" data-preview="holder">
                                                <i class="fa fa-chevron-up"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="icon" id="icon" value="{{ !empty($subscribe->icon) ? $subscribe->icon : old('icon') }}" class="form-control @error('icon') is-invalid @enderror"/>
                                        @error('icon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                        <div class="input-group-append">
                                            <button type="button" class="input-group-text admin-file-view" data-input="icon">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0">
                                        <input type="hidden" name="is_popular" value="0">
                                        <input type="checkbox" name="is_popular" id="isPopular" value="1" {{ (!empty($subscribe) and $subscribe->is_popular) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="isPopular">{{ trans('admin/pages/financial.is_popular') }}</label>
                                    </label>
                                </div>

                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0">
                                        <input type="hidden" name="infinite_use" value="0">
                                        <input type="checkbox" name="infinite_use" id="infiniteUseSwitch" value="1" {{ (!empty($subscribe) and $subscribe->infinite_use) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="infiniteUseSwitch">{{ trans('update.infinite_use') }}</label>
                                    </label>
                                </div>

                                <div class=" mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('priceInput');
            const priceIdInput = document.getElementById('priceIdInput');
            const form = document.getElementById('subscribeForm');
            let originalPrice = '{{ !empty($subscribe) ? $subscribe->price : "" }}';
            let priceChanged = false;

            // Check if price has changed
            if (priceInput) {
                priceInput.addEventListener('input', function() {
                    if (this.value !== originalPrice) {
                        priceChanged = true;
                        // Show warning or clear price_id if price changed
                        if (priceIdInput.value) {
                            if (confirm('{{ trans("admin/main.price_changed_warning") }}')) {
                                priceIdInput.value = '';
                            }
                        }
                    } else {
                        priceChanged = false;
                    }
                });
            }

            // Form validation
            if (form) {
                form.addEventListener('submit', function(e) {
                    const price = priceInput ? priceInput.value : '';
                    const priceId = priceIdInput ? priceIdInput.value : '';
                    
                    // If price is set but price_id is empty, show warning
                    if (price && !priceId) {
                        e.preventDefault();
                        alert('{{ trans("admin/main.stripe_price_id_required") }}');
                        priceIdInput.focus();
                        return false;
                    }
                    
                    return true;
                });
            }
        });
    </script>
@endpush