@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    :root {
        --k-bg: #0f0f0f;
        --k-card: #171717;
        --k-border: #2a2a2a;
        --k-gold: #F2C94C;
        --k-text: #eaeaea;
        --k-muted: #9aa0a6;
        --k-radius: 18px;
    }

    body {
        background: var(--k-bg);
    }

    .k-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--k-gold);
        margin-bottom: 20px;
    }

    .k-plan {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 45px 20px 25px;
        text-align: center;
        height: 100%;
        transition: all .3s ease;
        position: relative;
        cursor: pointer;
    }

    .k-plan:hover {
        transform: translateY(-6px);
        border-color: var(--k-gold);
        box-shadow: 0 12px 35px rgba(0,0,0,.6);
    }

    .k-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #F2C94C, #d4a72c);
        color: #000;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
    }

    .k-plan h3 {
        color: var(--k-gold);
        font-size: 26px;
        margin-top: 15px;
        margin-bottom: 10px;
    }

    .k-price {
        font-size: 36px;
        color: var(--k-text);
        margin-top: 25px;
        font-weight: 700;
    }

    .k-desc {
        color: var(--k-muted);
        font-size: 14px;
        margin-top: 15px;
        line-height: 1.6;
    }

    .k-btn {
        margin-top: 35px;
        background: linear-gradient(135deg, #F2C94C, #d4a72c);
        border: none;
        color: #000;
        font-weight: 600;
        border-radius: 30px;
        padding: 12px 24px;
        transition: all 0.3s ease;
    }

    .k-btn:hover {
        opacity: .9;
        transform: translateY(-2px);
    }

    .k-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .k-card {
        background: var(--k-card);
        border: 1px solid var(--k-border);
        border-radius: var(--k-radius);
        padding: 25px;
    }

    .k-table th {
        background: #121212;
        color: var(--k-gold);
        border-bottom: 1px solid var(--k-border);
        padding: 15px;
    }

    .k-table td {
        color: var(--k-text);
        border-top: 1px solid var(--k-border);
        padding: 15px;
        vertical-align: middle;
    }

    .k-table tr:hover {
        background: rgba(242,201,76,.06);
    }

    /* Custom Modal Styles */
    .promotion-modal-wrapper {
        background: var(--k-card);
        border-radius: 24px;
        max-width: 500px;
        margin: 0 auto;
        position: relative;
    }

    .promotion-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px 30px 0 30px;
    }

    .promotion-modal-body {
        padding: 20px 30px 30px 30px;
    }

    .close-modal-btn {
        background: none;
        border: none;
        color: var(--k-muted);
        font-size: 32px;
        cursor: pointer;
        line-height: 1;
        transition: all 0.3s ease;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .close-modal-btn:hover {
        color: var(--k-gold);
        background: rgba(242,201,76,0.1);
        transform: rotate(90deg);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--k-border);
    }

    .info-label {
        color: var(--k-muted);
        font-size: 14px;
    }

    .info-value {
        color: var(--k-gold);
        font-weight: 600;
        font-size: 16px;
    }

    .form-group label {
        color: var(--k-muted);
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    .custom-select {
        background: #0c0c0c;
        border: 1px solid var(--k-border);
        color: var(--k-text);
        border-radius: 12px;
        padding: 12px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .custom-select:focus {
        border-color: var(--k-gold);
        outline: none;
        box-shadow: 0 0 0 2px rgba(242,201,76,0.2);
    }

    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-secondary {
        background: transparent;
        border: 1px solid var(--k-border);
        color: var(--k-muted);
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        border-color: var(--k-gold);
        color: var(--k-gold);
    }

    .btn-primary {
        background: linear-gradient(135deg, #F2C94C, #d4a72c);
        color: #000;
        padding: 10px 30px;
        border-radius: 30px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(242,201,76,0.3);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Custom Switch */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--k-gold);
        border-color: var(--k-gold);
    }

    /* SweetAlert Customization */
    .custom-swal-popup {
        background: transparent !important;
        box-shadow: none !important;
    }

    .swal2-popup {
        background: var(--k-card) !important;
        border-radius: var(--k-radius) !important;
        border: 1px solid var(--k-border) !important;
    }

    .swal2-title {
        color: var(--k-gold) !important;
    }

    .swal2-html-container {
        color: var(--k-text) !important;
    }

    .swal2-confirm {
        background: linear-gradient(135deg, #F2C94C, #d4a72c) !important;
        color: #000 !important;
    }

    /* Loading Spinner */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    .gap-2 {
        gap: 8px;
    }

    /* No Result Section */
    .no-result {
        text-align: center;
        padding: 60px 20px;
        background: var(--k-card);
        border-radius: var(--k-radius);
        border: 1px solid var(--k-border);
    }

    .no-result img {
        width: 120px;
        margin-bottom: 20px;
        opacity: 0.7;
    }

    .no-result h4 {
        color: var(--k-gold);
        margin-bottom: 10px;
    }

    .no-result p {
        color: var(--k-muted);
    }
</style>
@endpush

@section('content')

{{-- ================= PROMOTION PLANS ================= --}}
<section>
    <h2 class="k-title">{{ trans('panel.select_promotion_plan') }}</h2>

    <div class="row mt-25">
        @foreach($promotions as $promotion)
            <div class="col-12 col-sm-6 col-lg-3 mt-20">
                <div class="k-plan">
                    @if($promotion->is_popular)
                        <span class="k-badge">{{ trans('panel.popular') }}</span>
                    @endif

                    @if($promotion->icon)
                        <img src="{{ $promotion->icon }}" width="64" alt="{{ $promotion->title }}">
                    @else
                        <img src="/assets/default/img/promotion.png" width="64" alt="promotion">
                    @endif

                    <h3>{{ $promotion->title }}</h3>
                    <p class="text-muted mt-5">
                        {{ trans('panel.promotion_days',['day' => $promotion->days]) }}
                    </p>

                    <div class="k-price">
                        {{ (!empty($promotion->price) && $promotion->price > 0)
                            ? handlePrice($promotion->price, true, true, false, null, true)
                            : trans('public.free') }}
                    </div>

                    <div class="k-desc">{!! nl2br($promotion->description) !!}</div>

                    <button type="button"
                        data-promotion-id="{{ $promotion->id }}"
                        data-promotion-title="{{ $promotion->title }}"
                        data-promotion-price="{{ (!empty($promotion->price) && $promotion->price > 0) ? handlePrice($promotion->price, true, true, false, null, true) : trans('public.free') }}"
                        class="js-pay-promotion btn k-btn btn-block">
                        {{ trans('update.purchase') }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ================= PROMOTION HISTORY ================= --}}
@if($promotionSales->count() > 0)
<section class="mt-40">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h2 class="k-title">{{ trans('panel.promotions_history') }}</h2>

        <div class="d-flex align-items-center mt-15 mt-md-0">
            <label class="mr-10 text-muted">
                {{ trans('panel.show_only_active_promotions') }}
            </label>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="activePromotionSwitch">
                <label class="custom-control-label" for="activePromotionSwitch"></label>
            </div>
        </div>
    </div>

    <div class="k-card mt-25">
        <div class="table-responsive">
            <table class="table k-table">
                <thead>
                    <tr>
                        <th class="text-left">{{ trans('panel.webinar') }}</th>
                        <th>{{ trans('panel.plan') }}</th>
                        <th>{{ trans('public.price') }}</th>
                        <th>{{ trans('public.date') }}</th>
                        <th>{{ trans('public.status') }}</th>
                        <th>{{ trans('public.expiry_date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promotionSales as $promotionSale)
                        <tr class="promotion-row" data-expiry="{{ $promotionSale->expired_at }}" data-status="{{ $promotionSale->status }}">
                            <td class="text-left font-weight-500">
                                {{ $promotionSale->webinar->title ?? 'N/A' }}
                            </td>
                            <td>{{ $promotionSale->promotion->title ?? 'N/A' }}</td>
                            <td>
                                {{ (!empty($promotionSale->promotion->price) && $promotionSale->promotion->price > 0)
                                    ? handlePrice($promotionSale->promotion->price)
                                    : trans('public.free') }}
                            </td>
                            <td>{{ dateTimeFormat($promotionSale->created_at, 'j M Y | H:i') }}</td>
                            <td>
                                @php
                                    $isActive = ($promotionSale->status == 'active' && $promotionSale->expired_at > time());
                                @endphp
                                <span class="badge {{ $isActive ? 'badge-success' : 'badge-danger' }}">
                                    {{ $isActive ? trans('public.active') : trans('public.expired') }}
                                </span>
                            </td>
                            <td>{{ dateTimeFormat($promotionSale->expired_at, 'j M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="my-30">
    {{ $promotionSales->appends(request()->input())->links('vendor.pagination.panel') }}
</div>
@else
    <div class="no-result mt-40">
        <img src="/assets/default/img/promotion.png" alt="No promotions">
        <h4>{{ trans('panel.promotion_no_result') }}</h4>
        <p>{!! nl2br(trans('panel.promotion_no_result_hint')) !!}</p>
    </div>
@endif

@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Store current modal data
    let currentPromotion = {
        id: null,
        title: null,
        price: null
    };

    // Handle purchase button click
    $(document).on('click', '.js-pay-promotion', function() {
        const $btn = $(this);
        const promotionId = $btn.data('promotion-id');
        const promotionTitle = $btn.data('promotion-title');
        const promotionPrice = $btn.data('promotion-price');
        
        currentPromotion = {
            id: promotionId,
            title: promotionTitle,
            price: promotionPrice
        };
        
        // Show modal using SweetAlert
        showPromotionModal(promotionId, promotionTitle, promotionPrice);
    });
    
    // Function to show promotion modal
    function showPromotionModal(promotionId, promotionTitle, promotionPrice) {
        // Get courses dropdown HTML
        const coursesDropdown = getCoursesDropdown();
        
        Swal.fire({
            title: 'Promote Course',
            html: `
                <div class="promotion-modal-wrapper">
                    <div class="promotion-modal-header">
                        <h3 style="color: var(--k-gold); margin: 0; font-size: 24px;">${promotionTitle}</h3>
                        <button type="button" class="close-modal-btn" onclick="Swal.close()">×</button>
                    </div>
                    <div class="promotion-modal-body">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img src="/assets/default/img/check.png" width="80" alt="Promotion">
                            <p style="color: var(--k-muted); margin-top: 10px;">Select a course to promote</p>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Plan Price</span>
                            <span class="info-value">${promotionPrice}</span>
                        </div>
                        
                        <div class="form-group" style="margin-top: 20px;">
                            <label>Select Course</label>
                            ${coursesDropdown}
                        </div>
                        
                        <div class="modal-buttons">
                            <button type="button" class="btn-secondary" onclick="Swal.close()">Close</button>
                            <button type="button" class="btn-primary" id="confirmPaymentBtn">Pay Now</button>
                        </div>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            background: 'transparent',
            customClass: {
                popup: 'custom-swal-popup'
            },
            didOpen: () => {
                // Handle payment confirmation
                $('#confirmPaymentBtn').on('click', function() {
                    processPayment(promotionId);
                });
            }
        });
    }
    
    // Function to get courses dropdown
    function getCoursesDropdown() {
        let dropdownHtml = '<select name="webinar_id" id="webinarSelect" class="custom-select" required>';
        dropdownHtml += '<option value="" disabled selected>Select a course</option>';
        
        @foreach($webinars as $webinar)
            dropdownHtml += '<option value="{{ $webinar->id }}">{{ addslashes($webinar->title) }}</option>';
        @endforeach
        
        dropdownHtml += '</select>';
        
        return dropdownHtml;
    }
    
    // Function to process payment
    function processPayment(promotionId) {
        const webinarId = $('#webinarSelect').val();
        
        if (!webinarId) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please select a course first',
                confirmButtonColor: '#F2C94C'
            });
            return;
        }
        
        // Disable button and show loading
        const $payBtn = $('#confirmPaymentBtn');
        const originalText = $payBtn.text();
        $payBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');
        
        // Make AJAX request
        $.ajax({
            url: '/panel/marketing/pay-promotion',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                promotion_id: promotionId,
                webinar_id: webinarId
            },
            success: function(response) {
                if (response.code === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Payment processed successfully',
                        confirmButtonColor: '#F2C94C'
                    }).then(() => {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            location.reload();
                        }
                    });
                } else if (response.code === 422) {
                    let errorMsg = 'Validation failed';
                    if (response.errors) {
                        errorMsg = Object.values(response.errors).join('\n');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg,
                        confirmButtonColor: '#F2C94C'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Payment failed. Please try again.',
                        confirmButtonColor: '#F2C94C'
                    });
                }
            },
            error: function(xhr) {
                let errorMsg = 'An error occurred. Please try again.';
                
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    errorMsg = 'You do not have permission to promote this course';
                } else if (xhr.status === 400) {
                    errorMsg = xhr.responseJSON?.message || 'This course is already promoted';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                    confirmButtonColor: '#F2C94C'
                });
            },
            complete: function() {
                $payBtn.prop('disabled', false).html(originalText);
            }
        });
    }
    
    // Filter active promotions
    $('#activePromotionSwitch').on('change', function() {
        const isChecked = $(this).is(':checked');
        const currentTime = Math.floor(Date.now() / 1000);
        
        $('.promotion-row').each(function() {
            const $row = $(this);
            const expiryDate = parseInt($row.data('expiry'));
            const status = $row.data('status');
            
            if (isChecked) {
                const isActive = (status === 'active' && expiryDate > currentTime);
                if (isActive) {
                    $row.show();
                } else {
                    $row.hide();
                }
            } else {
                $row.show();
            }
        });
    });
    
    // Initialize select2 if needed
    if ($.fn.select2) {
        $('.custom-select').select2({
            theme: 'dark',
            dropdownCssClass: 'dark-select-dropdown'
        });
    }
});

// Add custom style for select2 dropdown
$(document).ready(function() {
    $('head').append(`
        <style>
            .select2-container--default .select2-selection--single {
                background-color: #0c0c0c;
                border: 1px solid var(--k-border);
                border-radius: 12px;
                height: 45px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: var(--k-text);
                line-height: 45px;
            }
            .select2-dropdown {
                background-color: #0c0c0c;
                border-color: var(--k-border);
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: var(--k-gold);
                color: #000;
            }
            .select2-results__option {
                color: var(--k-text);
            }
            .badge-success {
                background: #28a745;
                color: #fff;
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 12px;
            }
            .badge-danger {
                background: #dc3545;
                color: #fff;
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 12px;
            }
        </style>
    `);
});
</script>
@endpush