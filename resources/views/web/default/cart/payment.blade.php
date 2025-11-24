@extends('web.default.layouts.app')

@section('content')
    <div class="checkout-page">
        <header class="checkout-page-header">
        <h1>Checkout</h1>
        <p>Review your order, membership and complete your payment.</p>
        </header>

        <section class="checkout-layout">
            <!-- LEFT: ORDER SUMMARY + MEMBERSHIP -->
            <div class="checkout-card">
                <h2>Order Summary</h2>

                <div class="checkout-items-list">
                    @foreach($carts as $cart)
                        @php
                            $cartItemInfo = $cart->getItemInfo();
                            $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
                        @endphp
                    <div class="checkout-item-row">
                        <div class="checkout-item-thumb"><img src="{{ $cartItemInfo['imgPath'] }}" width="50" alt="user avatar"></div>
                        <div class="checkout-item-meta">
                            <div class="checkout-item-title">{{ $cartItemInfo['title'] }}</div>
                            <div class="checkout-item-type"> @if(!empty($cartItemInfo['quantity'])) Product @else Course @endif </div>
                        </div>
                        <div class="checkout-item-qty">@if(!empty($cartItemInfo['quantity'])) x{{ $cartItemInfo['quantity'] }} @endif</div>
                        <div class="checkout-item-price">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="checkout-subtotal-row">
                <span class="checkout-subtotal-label">Subtotal</span>
                <span class="checkout-subtotal-value">{{ handlePrice($total) }}</span>
                </div>

                <!-- Membership Upsell -->
                <!-- @if(!$subscribes)
                    <div class="checkout-membership-card">
                        <div class="checkout-membership-header">
                            <div class="checkout-chakra-badge">
                                <div class="checkout-chakra-badge-inner">∞</div>
                            </div>
                            <div class="checkout-membership-text">
                                <h3>Add Kemetic Membership</h3>
                                <p>
                                    Unlock unlimited Kemetic courses, ebooks, PDFs, downloads,
                                    reels, livestreams, articles and more across the whole platform.
                                </p>
                            </div>
                        </div>
                        <div class="checkout-membership-row">
                            <div>
                                <div class="checkout-membership-price">Only €1 today</div>
                                <div style="font-size: 11px;color: rgba(199, 195, 217, 0.8);margin-top: 2px;">
                                    Membership gives full access alongside your purchase.
                                </div>
                            </div>

                            <label class="checkout-switch">
                            <input type="checkbox" id="subscribe_checkbox" />
                            <span class="checkout-slider"></span>
                            </label>
                        </div>
                    </div>
                @endif -->
            </div>

            @php
                $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
                $userCurrency = currency();
                $invalidChannels = [];
            @endphp
            <form action="/payments/payment-request" method="post" class=" mt-25">
            {{ csrf_field() }}
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <div class="checkout-card checkout-payment-card">
                <h2>Payment</h2>
                <p class="checkout-subtitle">Select how you want to complete your purchase.</p>

                <div class="checkout-payment-row">
                    @if(!empty($paymentChannels))
                        @foreach($paymentChannels as $paymentChannel)
                            @if(!$isMultiCurrency or (!empty($paymentChannel->currencies) and in_array($userCurrency, $paymentChannel->currencies)))
                                <div class="checkout-pay-chip checkout-selected">
                                    <input type="radio" name="gateway" id="{{ $paymentChannel->title }}" data-class="{{ $paymentChannel->class_name }}" value="{{ $paymentChannel->id }}">
                                    <label for="{{ $paymentChannel->title }}" class="h-100 rounded-sm p-20 p-lg-45 d-flex flex-column align-items-center justify-content-center">
                                        <img src="{{ $paymentChannel->image }}" width="120" height="60" alt="">

                                        <p class="mt-30 mt-lg-50 font-weight-500 text-dark-blue">
                                            {{ trans('financial.pay_via') }}
                                            <!-- <span class="font-weight-bold font-14">{{ $paymentChannel->title }}</span> -->
                                        </p>
                                    </label>
                                </div>
                            @else
                                @php
                                    $invalidChannels[] = $paymentChannel;
                                @endphp
                            @endif
                        @endforeach
                    @endif
                </div>

                <div class="checkout-divider-or">or</div>
                @if(!empty($invalidChannels) and empty(getFinancialSettings("hide_disabled_payment_gateways")))
                    @foreach($invalidChannels as $invalidChannel)
                        <div class="checkout-pay-chip checkout-full">
                            <img src="{{ $invalidChannel->image }}" width="120" height="60" alt="">

                            <p class="mt-30 mt-lg-50 font-weight-500 text-dark-blue">
                                {{ trans('financial.pay_via') }}
                                <span class="font-weight-bold font-14">{{ $invalidChannel->title }}</span>
                            </p>
                        </div>
                    @endforeach
                @endif

                <p class="checkout-payment-note">
                This payment includes your Kemetic membership (if selected) and all
                items in your order. After payment you get instant access in your
                Kemetic App account.
                </p>
            </div>
        </section>

        <!-- BOTTOM BAR -->
        <div class="checkout-bottom-bar">
            <div class="checkout-total-text">
                <span class="checkout-label">Grand Total</span>
                <span class="checkout-value">{{ handlePrice($total) }}</span>
            </div>
            <button class="checkout-btn-pay" type="button" id="paymentSubmit">Pay Now</button>
        </div>
        </form>

        @if(!empty($razorpay) and $razorpay)
            <form action="/payments/verify/Razorpay" method="get">
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <script src="https://checkout.razorpay.com/v1/checkout.js"
                        data-key="{{ getRazorpayApiKey()['api_key'] }}"
                        data-amount="{{ (int)($order->total_amount * 100) }}"
                        data-buttontext="product_price"
                        data-description="Rozerpay"
                        data-currency="{{ currency() }}"
                        data-image="{{ $generalSettings['logo'] }}"
                        data-prefill.name="{{ $order->user->full_name }}"
                        data-prefill.email="{{ $order->user->email }}"
                        data-theme.color="#43d477">
                </script>
            </form>
        @endif
    </div>
@endsection
@push('scripts_bottom')
    <script src="/assets/default/js/parts/payment.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const autoRedirect = urlParams.get('auto_redirect');
        if (autoRedirect === "1") {
            // Select the first available payment method
            let firstPaymentOption = $('input[name="gateway"]:not(:disabled)').first();
            if (firstPaymentOption.length) {
                firstPaymentOption.prop("checked", true).trigger("click");
                $("#paymentSubmit").prop("disabled", false); // Enable the submit button
                
                $("#paymentSubmit").trigger("click");
                $('body').html('');
               
            }
        }
    });
</script> -->
<script>
$(document).ready(function () {
    const radios = $('input[name="gateway"]');
    const submitBtn = $('#paymentSubmit');
    const form = $('form[action="/payments/payment-request"]');

    // Enable submit when gateway selected
    radios.on('change', function () {
        submitBtn.prop('disabled', false);
    });

    // Optional: handle manual button click in case type="button"
    submitBtn.on('click', function (e) {
        if ($(this).attr('type') === 'button') {
            e.preventDefault();
            const checked = $('input[name="gateway"]:checked').val();
            if (checked) {
                form.submit();
            } else {
                alert('Please select a payment method first.');
            }
        }
    });
});
</script>
@endpush