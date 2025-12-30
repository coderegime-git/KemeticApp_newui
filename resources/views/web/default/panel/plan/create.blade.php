@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">

<style>
/* ===============================
   KEMETIC PLAN CREATE / EDIT
================================ */
.kemetic-form-card {
    background: linear-gradient(180deg, #0b0b0b, #121212);
    border: 1px solid rgba(212,175,55,0.25);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6);
}

/* Titles */
.kemetic-title {
    color: #d4af37;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* Labels */
.kemetic-label {
    color: #d4af37;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: block;
}

/* Inputs */
.kemetic-input {
    background-color: #0f0f0f !important;
    color: #f5f5f5 !important;
    border: 1px solid rgba(212,175,55,0.35);
    border-radius: 10px;
    height: 44px;
    padding: 0 15px;
}

.kemetic-input::placeholder {
    color: #8b8b8b;
}

.kemetic-input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212,175,55,0.25);
    background-color: #0f0f0f;
}

/* Textarea */
.kemetic-textarea {
    background-color: #0f0f0f !important;
    color: #f5f5f5 !important;
    border: 1px solid rgba(212,175,55,0.35);
    border-radius: 10px;
    min-height: 100px;
    padding: 15px;
}

.kemetic-textarea:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212,175,55,0.25);
}

/* Select */
.kemetic-select {
    background-color: #0f0f0f !important;
    color: #f5f5f5 !important;
    border: 1px solid rgba(212,175,55,0.35);
    border-radius: 10px;
    height: 44px;
    padding: 0 15px;
}

/* Checkbox */
.kemetic-checkbox {
    position: relative;
    padding-left: 30px;
    cursor: pointer;
    user-select: none;
}

.kemetic-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.kemetic-checkbox .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #0f0f0f;
    border: 2px solid rgba(212,175,55,0.35);
    border-radius: 4px;
}

.kemetic-checkbox:hover .checkmark {
    border-color: #d4af37;
}

.kemetic-checkbox input:checked ~ .checkmark {
    background-color: #d4af37;
    border-color: #d4af37;
}

.kemetic-checkbox .checkmark:after {
    content: "";
    position: absolute;
    display: none;
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid #000;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.kemetic-checkbox input:checked ~ .checkmark:after {
    display: block;
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
}

/* Save Button */
.kemetic-save-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    font-weight: 700;
    border-radius: 12px;
    height: 46px;
    padding: 0 30px;
    letter-spacing: 0.6px;
    transition: all 0.25s ease;
    box-shadow: 0 6px 18px rgba(212,175,55,0.35);
    border: none;
    cursor: pointer;
}

.kemetic-save-btn:hover {
    background: linear-gradient(135deg, #e6c45c, #d4af37);
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(212,175,55,0.45);
    color: #000;
}

/* Cancel Button */
.kemetic-cancel-btn {
    background: transparent;
    color: #d4af37;
    font-weight: 600;
    border: 1px solid rgba(212,175,55,0.35);
    border-radius: 12px;
    height: 46px;
    padding: 0 30px;
    letter-spacing: 0.6px;
    transition: all 0.25s ease;
    margin-right: 10px;
}

.kemetic-cancel-btn:hover {
    background: rgba(212,175,55,0.1);
    border-color: #d4af37;
    color: #d4af37;
}

/* Back Button */
.kemetic-back-btn {
    color: #d4af37;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 20px;
    display: inline-flex;
    align-items: center;
}

.kemetic-back-btn:hover {
    color: #e6c45c;
    text-decoration: none;
}

/* Form Row */
.form-row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -10px;
    margin-left: -10px;
}

.form-row > .col {
    padding-right: 10px;
    padding-left: 10px;
}

/* Helper Text */
.help-text {
    color: #8b8b8b;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

/* Error Messages */
.invalid-feedback {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 5px;
}

/* Success Message */
.alert-success {
    background: rgba(46, 204, 113, 0.1);
    border: 1px solid rgba(46, 204, 113, 0.3);
    color: #2ecc71;
    border-radius: 10px;
}

/* Price Preview */
.price-preview {
    background: rgba(212,175,55,0.1);
    border: 1px solid rgba(212,175,55,0.2);
    border-radius: 8px;
    padding: 15px;
    margin-top: 20px;
}

.price-preview h5 {
    color: #d4af37;
    margin-bottom: 10px;
}

/* Duration Info */
.duration-info {
    background: rgba(52, 152, 219, 0.1);
    border: 1px solid rgba(52, 152, 219, 0.2);
    border-radius: 8px;
    padding: 15px;
    margin-top: 20px;
}

.duration-info h5 {
    color: #3498db;
    margin-bottom: 10px;
}
</style>
@endpush

@section('content')

<section class="mt-25">
    {{-- BACK BUTTON --}}
    <a href="/panel/plan" class="kemetic-back-btn">
        <i data-feather="arrow-left" width="18" class="mr-2"></i>
        Back to Plans
    </a>

    {{-- TITLE --}}
    <h2 class="section-title kemetic-title mb-20">
        {{ !empty($plan) ? 'Edit Plan' : 'Create New Plan' }}
    </h2>

    {{-- FORM --}}
    <form action="/panel/plan/{{ !empty($plan) ? $plan->id . '/update' : 'store' }}" method="post">
        @csrf

        <div class="kemetic-form-card">
            <div class="row">
                {{-- PLAN CODE --}}

                {{-- PLAN NAME --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="kemetic-label" for="title">Plan Name *</label>
                        <input type="text" 
                               name="title" 
                               id="title"
                               class="form-control kemetic-input @error('title') is-invalid @enderror"
                               value="{{ old('title', !empty($plan) ? $plan->title : '') }}"
                               placeholder="e.g., Basic Plan, Professional Plan"
                               required>
                        <span class="help-text">Display name for the plan</span>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="kemetic-label" for="code">Plan Code *</label>
                        <input type="text" 
                               name="code" 
                               id="code"
                               class="form-control kemetic-input @error('code') is-invalid @enderror"
                               value="{{ old('code', !empty($plan) ? $plan->code : '') }}"
                               placeholder="e.g., BASIC, PRO, PREMIUM"
                               required>
                        <span class="help-text">Unique identifier for the plan (uppercase recommended)</span>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- PRICE --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="kemetic-label" for="price">Price ({{ $currency }}) *</label>
                        <input type="number" 
                               name="price" 
                               id="price"
                               class="form-control kemetic-input @error('price') is-invalid @enderror"
                               value="{{ old('price', !empty($plan) ? $plan->price : '0') }}"
                               placeholder="0"
                               min="0"
                               step="0.01"
                               required>
                        <span class="help-text">Set 0 for free plan</span>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- DURATION --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="kemetic-label" for="duration_days">Duration (Days) *</label>
                        <input type="number" 
                               name="duration_days" 
                               id="duration_days"
                               class="form-control kemetic-input @error('duration_days') is-invalid @enderror"
                               value="{{ old('duration_days', !empty($plan) ? $plan->duration_days : '30') }}"
                               placeholder="30"
                               min="1"
                               required>
                        <span class="help-text">Subscription duration in days</span>
                        @error('duration_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="kemetic-checkbox">
                            <input type="hidden" name="is_membership" value="0">
                            <input type="checkbox" 
                                   name="is_membership" 
                                   value="1"
                                   {{ old('is_membership', !empty($plan) ? $plan->is_membership : true) ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Active Plan
                        </label>
                        <span class="help-text">Inactive plans won't be available for purchase</span>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <!-- <div class="col-md-12">
                    <div class="form-group">
                        <label class="kemetic-label" for="description">Description</label>
                        <textarea name="description" 
                                  id="description"
                                  class="form-control kemetic-textarea @error('description') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Describe the features and benefits of this plan...">{{ old('description', !empty($plan) ? $plan->description : '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- FEATURES --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="kemetic-label" for="features">Features (One per line)</label>
                        <textarea name="features" 
                                  id="features"
                                  class="form-control kemetic-textarea @error('features') is-invalid @enderror"
                                  rows="6"
                                  placeholder="Feature 1&#10;Feature 2&#10;Feature 3">{{ old('features', !empty($plan) ? $plan->features : '') }}</textarea>
                        <span class="help-text">List each feature on a new line</span>
                        @error('features')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div> -->

                {{-- PREVIEW SECTION --}}
                <!-- <div class="col-md-12">
                    <div class="price-preview">
                        <h5>Plan Preview</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Code:</strong> <span id="preview-code">{{ old('code', !empty($plan) ? $plan->code : 'BASIC') }}</span></p>
                                <p><strong>Name:</strong> <span id="preview-name">{{ old('name', !empty($plan) ? $plan->name : 'Basic Plan') }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Price:</strong> <span id="preview-price">{{ old('price', !empty($plan) ? $plan->price : '0') }}</span> {{ $currency }}</p>
                                <p><strong>Duration:</strong> <span id="preview-duration">{{ old('duration_days', !empty($plan) ? $plan->duration_days : '30') }}</span> days</p>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            {{-- BUTTONS --}}
            <div class="mt-30 d-flex justify-content-between">
                <div>
                    <a href="/panel/plan" class="btn kemetic-cancel-btn">
                        Cancel
                    </a>
                </div>
                <div>
                    <button type="submit" class="btn kemetic-save-btn">
                        <i data-feather="{{ !empty($plan) ? 'save' : 'plus' }}" width="18" class="mr-2"></i>
                        {{ !empty($plan) ? 'Update Plan' : 'Create Plan' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@push('scripts_bottom')
<script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
<script>
// Initialize Feather Icons
feather.replace();

// Real-time preview update
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const nameInput = document.getElementById('title');
    const priceInput = document.getElementById('price');
    const durationInput = document.getElementById('duration_days');
    
    const previewCode = document.getElementById('preview-code');
    const previewName = document.getElementById('preview-name');
    const previewPrice = document.getElementById('preview-price');
    const previewDuration = document.getElementById('preview-duration');
    
    // Update previews on input
    codeInput.addEventListener('input', function() {
        previewCode.textContent = this.value || 'BASIC';
    });
    
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Basic Plan';
    });
    
    priceInput.addEventListener('input', function() {
        previewPrice.textContent = this.value || '0';
    });
    
    durationInput.addEventListener('input', function() {
        previewDuration.textContent = this.value || '30';
    });
    
    // Price validation
    priceInput.addEventListener('blur', function() {
        if (this.value < 0) {
            this.value = 0;
        }
    });
    
    // Duration validation
    durationInput.addEventListener('blur', function() {
        if (this.value < 1) {
            this.value = 1;
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const code = codeInput.value.trim();
        const name = nameInput.value.trim();
        const price = priceInput.value;
        const duration = durationInput.value;
        
        if (!code || !name) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        if (price < 0) {
            e.preventDefault();
            alert('Price cannot be negative.');
            priceInput.focus();
            return;
        }
        
        if (duration < 1) {
            e.preventDefault();
            alert('Duration must be at least 1 day.');
            durationInput.focus();
            return;
        }
    });
});
</script>
@endpush