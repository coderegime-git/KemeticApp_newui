@extends('admin.layouts.app')
@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ !empty($plan) ? 'Edit Plan' : 'Create New Plan' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl() }}/plan">Subscription Plans</a></div>
                <div class="breadcrumb-item">{{ !empty($plan) ? 'Edit' : 'Create' }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl() }}/plan/{{ !empty($plan) ? $plan->id.'/update' : 'store' }}" method="post">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-12 col-md-6">

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.title') }}</label>
                                            <input type="text" name="title"
                                                class="form-control  @error('title') is-invalid @enderror"
                                                value="{{ !empty($plan) ? $plan->title : old('title') }}"
                                                placeholder="Basic Plan" required/>
                                            @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.code') }}</label>
                                            <input type="text" name="code"
                                                   class="form-control  @error('code') is-invalid @enderror"
                                                   value="{{ !empty($plan) ? $plan->code : old('code') }}"
                                                   placeholder="PLAN001" required/>
                                            @error('code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('public.price') }} ({{ $currency }})</label>
                                            <input type="number" name="price" step="0.01" min="0"
                                                   value="{{ !empty($plan) ? $plan->price : old('price') }}" 
                                                   class="form-control @error('price')  is-invalid @enderror" 
                                                   placeholder="9.99" required/>
                                            @error('price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">Duration (Days)</label>
                                            <input type="number" name="duration_days" min="1"
                                                   value="{{ !empty($plan) ? $plan->duration_days : old('duration_days') }}" 
                                                   class="form-control @error('duration_days')  is-invalid @enderror" 
                                                   placeholder="30" required/>
                                            @error('duration_days')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <!-- Active Plan Switch -->
                                        <div class="form-group mt-30">
                                            <label class="input-label d-block mb-2">Plan Status</label>
                                            <div class="custom-control custom-switch">
                                                <input type="hidden" name="is_membership" id="is_membership" value="{{ !empty($plan) && $plan->is_membership == 1 ? '1' : '0' }}">
                                                <input type="checkbox" 
                                                       name="is_membership_switch"
                                                       id="is_membership_switch" 
                                                       class="custom-control-input"
                                                       {{ !empty($plan) && $plan->is_membership == 1 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_membership_switch">
                                                    Active Plan
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Toggle to activate or deactivate this plan
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <!-- <div class="form-group">
                                            <label class="custom-switch pl-0 mt-2">
                                                <input type="checkbox" name="is_popular" value="1" 
                                                       class="custom-switch-input" 
                                                       {{ (!empty($plan) && $plan->is_popular) || old('is_popular') ? 'checked' : '' }}>
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Mark as Popular Plan</span>
                                            </label>
                                        </div> -->

                                        <!-- <div class="form-group">
                                            <div class="d-flex align-items-center">
                                                <input type="hidden" name="is_membership" value="0">
                                                <input type="checkbox" name="is_membership" value="1" 
                                                       id="is_membership" 
                                                       class="custom-switch-input" 
                                                       {{ (!empty($plan) && $plan->is_membership == 1) || old('is_membership', 1) ? 'checked' : '' }}>
                                                <label class="custom-switch-input-label ml-2" for="is_membership">
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">Active Plan</span>
                                                </label>
                                            </div>
                                        </div> -->

                                        

                                        <!-- <div class="form-group mt-3">
                                            <label>Features (one per line)</label>
                                            <textarea name="features[]" 
                                                      class="form-control @error('features')  is-invalid @enderror" 
                                                      rows="6" 
                                                      placeholder="Access to all books&#10;Download PDFs&#10;Priority support">{{ !empty($plan) && $plan->features ? implode(PHP_EOL, $plan->features) : old('features') }}</textarea>
                                            <small class="text-muted">Enter each feature on a new line</small>
                                            @error('features')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div> -->
                                    </div>
                                </div>

                                <!-- <div class="form-group mt-15">
                                    <label class="input-label">{{ trans('public.description') }}</label>
                                    <div class="text-muted text-small mb-3">Detailed description of the plan</div>
                                    <textarea id="summernote" name="description" 
                                              class="summernote form-control @error('description')  is-invalid @enderror" 
                                              placeholder="Plan description...">{!! !empty($plan) ? $plan->description : old('description') !!}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div> -->

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                    <a href="{{ getAdminPanelUrl() }}/plan" class="btn btn-secondary">Cancel</a>
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
        $(document).ready(function() {
             // Optional: Add some JavaScript to handle checkbox state
            $('#is_membership_switch').on('change', function() {
                var membershipInput = document.querySelector('input[name="is_membership"]');
        
                // Toggle the value between 0 and 1
                if ($(this).prop('checked')) {
                    membershipInput.value = "1";
                    // alert('1');
                } else {
                    membershipInput.value = "0";
                    // alert('0');
                }
                 console.log('Membership value changed to:', membershipInput.value);
            });

            $('#summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush