@extends('web.default.layouts.app')

@section('content')
  <div class="cart-page">
    <header class="cart-page-header">
      <div>
        <h1>Your Cart</h1>
        <span class="cart-sub">Review your items before going to checkout.</span>
      </div>
      <span class="cart-sub">{{$carts->count()}} items</span>
    </header>

    <section class="cart-layout">
      <!-- LEFT: CART ITEMS -->
      <div class="cart-card">
        <table class="cart-cart-table">
            @if($carts->count() > 0)
            <thead class="cart-cart-header">
                <tr>
                <th align="left">Item</th>
                <th align="center">Quantity</th>
                <th align="right">Price</th>
                <th align="right">Total</th>
                <th align="right"></th>
                </tr>
            </thead>
            @endif
           
            <tbody>
            @foreach($carts as $cart)
                @php
                    $cartItemInfo = $cart->getItemInfo();
                    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
                @endphp
            <tr class="cart-cart-row">
              <td>
                <div class="cart-cart-item">
                  <div class="cart-cart-thumb"><img src="{{ $cartItemInfo['imgPath'] }}" width="50" alt="user avatar"></div>
                  <div class="cart-cart-meta">
                    <div class="cart-cart-title">{{ $cartItemInfo['title'] }}</div>
                    <div class="cart-cart-type"> @if(!empty($cartItemInfo['quantity'])) Product @else Course @endif</div>
                  </div>
                </div>
              </td>
              <td align="center">
                @if(!empty($cartItemInfo['quantity']))
                <div class="cart-qty-control">
                    <!-- <div class="cart-qty-btn">−</div> -->
                    <div class="cart-qty-value">{{ $cartItemInfo['quantity'] }}</div>
                    <!-- <div class="cart-qty-btn">+</div> -->
                </div>
                @endif
              </td>
              <td align="right">
                @if(!empty($cartItemInfo['discountPrice']))
                    <div class="cart-cart-unit">{{ handlePrice($cartItemInfo['discountPrice'], true, true, false, null, true, $cartTaxType) }}</div>
                @else
                    <div class="cart-cart-unit">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</div>
                @endif
              </td>
              <td align="right">
                @if(!empty($cartItemInfo['discountPrice']))
                    <div class="cart-cart-total">{{ handlePrice($cartItemInfo['discountPrice'], true, true, false, null, true, $cartTaxType) }}</div>
                @else
                    <div class="cart-cart-total">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</div>
                @endif
              </td>
              <td align="right">
                <div class="cart-cart-remove"> <a href="/cart/{{ $cart->id }}/delete">Remove</a></div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    <form action="/cart/checkout" method="post" id="cartForm">
        {{ csrf_field() }}
            <input type="hidden" name="discount_id" value="">
            @if(!empty(getStoreSettings('show_address_selection_in_cart')))
            <header class="page-header">
                <h1>Checkout</h1>
                <p>Add your address information.</p>
            </header>

            <div class="card-wrapper">
                @if(empty($user))
                    <!-- <div class="card"> -->
                        
                        <div class="field-row">
                            <div class="checkbox-row">
                                <input type="hidden" name="create_account" value="0">
                                <input type="checkbox" id="createAccount" name="create_account" class="@error('create_account') is-invalid @enderror" value="{{ !empty($user) ? 1 : 0}}" />
                                <span>Create account with these details</span>
                            </div>

                            <div class="field-single">
                                <label for="firstName">{{ trans('update.first_name') }}</label>
                                <input id="firstName" name="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" required value="{{ !empty($user) ? $user->full_name : '' }}" />
                                @error('first_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="field-single">
                                <label for="lastName">{{ trans('update.last_name') }}</label>
                                <input id="lastName" name="last_name" class="form-control @error('last_name') is-invalid @enderror" type="text" required />
                                @error('last_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="field-single">
                            <label for="email">{{ trans('update.email') }}</label>
                            <input id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                            value="{{ !empty($user) ? $user->email : '' }}" type="email" required />
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="field-single">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone"  id="phone" class="form-control @error('phone') is-invalid @enderror" 
                            required value="{{ !empty($user) ? $user->mobile : '' }}" />
                            @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Country -->
                       

                        <!-- Address 1 -->
                        <div class="field-single">
                            <label for="address1">House No.</label>
                            <input type="text" name="house_no" id="house_no" required value="{{ !empty($user) ? $user->house_no : '' }}" class="form-control @error('house_no')  is-invalid @enderror" />
                            @error('house_no')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Address 2 -->
                        <div class="field-single">
                            <label for="address2">{{ trans('update.address') }}</label>
                            <textarea name="address" rows="6" required class="form-control @error('address')  is-invalid @enderror">{{ !empty($user) ? $user->address : '' }}</textarea>

                            @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Checkboxes -->
                        <!-- <div class="checkbox-row">
                            <input id="saveInfo" type="checkbox" name="saveInfo" />
                            <span>Save this information for next time</span>
                        </div> -->

                        
                    <!-- </div> -->

                @endif
                <div class="field-single">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone"  id="phone" class="form-control @error('phone') is-invalid @enderror" 
                    required value="{{ !empty($user) ? $user->mobile : '' }}" />
                    @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="field-single">
                    <label for="country">{{ trans('update.country') }}</label>
                     <select name="country_id" id="country" class="form-control @error('country_id')  is-invalid @enderror" required>
                        <option value="">{{ trans('update.select_country') }}</option>

                        @if(!empty($countries))
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ (!empty($user) and $user->country_id == $country->id) ? 'selected' : '' }}>{{ $country->title }}</option>
                        @endforeach
                        @endif
                    </select>

                    @error('country_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="city">Province/State/District</label>
                        <input type="text" name="province_name" id="province" class="form-control @error('province_name') is-invalid @enderror" 
                           value="{{ !empty($user) ? $user->province_name : '' }}" required />
                
                        @error('province_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- <div class="field">
                        <label for="zip">{{ trans('update.district') }}</label>
                        <input type="text" name="district_name" class="form-control @error('district_name') is-invalid @enderror" 
                           value="{{ !empty($user) ? $user->district_name : '' }}"  />
                    
                        @error('district_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div> -->
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="city">{{ trans('update.city') }}</label>
                        <input id="city" name="city_name" class="form-control @error('city_name') is-invalid @enderror" 
                            value="{{ !empty($user) ? $user->city_name : '' }}" required />

                        @error('city_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="zip">ZIP / Postal Code</label>
                        <input id="zip" name="zip_code" value="{{ !empty($user) ? $user->zip_code : '' }}" class="form-control @error('zip_code')  is-invalid @enderror" required />
                        @error('zip_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                @endif
            </div>

        <div class="cart-card cart-summary-card">
            <h2>Order Summary</h2>

            <div class="cart-summary-row">
            <span class="cart-label">{{ trans('cart.sub_total') }}</span>
            <span class="cart-value">{{ handlePrice($subTotal) }}</span>
            </div>

            <div class="cart-summary-row">
            <span class="cart-label">Estimated Shipping</span>
            <span class="cart-value">{{ handlePrice($productDeliveryFee) }}</span>
            </div>

            <div class="cart-summary-row cart-total">
            <span class="cart-label">{{ trans('cart.total') }}</span>
            <span class="cart-value">{{ handlePrice($total) }}</span>
            </div>

            <p class="cart-notice">
            You'll see taxes, shipping and membership options on the next step.
            Login members can get full access to Kemetic courses, ebooks, PDFs,
            livestreams and more.
            </p>

            <div class="cart-upsell">
            <span class="cart-highlight">Join Kemetic Membership for €1</span> on the
            next screen and unlock the full Kemetic App platform while keeping all
            products in your cart.
            </div>

            <div class="cart-btn-group">
            <button type="submit" class="cart-btn-primary">{{ trans('cart.checkout') }}</button>
            <button type="button" class="cart-btn-ghost" onclick="window.history.back()">{{ trans('cart.continue_shopping') }}</button>
            </div>
        </div>
    </form>
    </section>
  </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Calculate shipping when address fields change
    $('#country, #phone, #province, #city, #zip').on('change', function() {
        calculateShipping();
    });
    
    function calculateShipping() {
        var country = $('#country').val();
        var city = $('#city').val();
        var zip = $('#zip').val();
        var phone = $('#phone').val();
        var province = $('#province').val();
        
        if (phone && country && city && zip) {
            $.ajax({
                url: '/cart/calculate-shipping',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    country_id: country,
                    city_name: city,
                    phone: phone,
                    province_name: province,
                    zip_code: zip
                },
                success: function(response) {
                    if (response.success) {
                        alert(response);
                        // Update shipping display
                        $('.cart-shipping-value').text(response.shipping_cost_formatted);
                        $('.cart-total-value').text(response.total_formatted);
                        
                        // Update form hidden field
                        $('#shipping_cost').val(response.shipping_cost);
                        
                        // Update shipping note
                        $('.cart-shipping-note').text('(Calculated)').removeClass('estimated').addClass('calculated');
                    }
                }
            });
        }
    }
});
</script>
<script>
    var couponInvalidLng = '{{ trans('cart.coupon_invalid') }}';
    var selectProvinceLang = '{{ trans('update.select_province') }}';
    var selectCityLang = '{{ trans('update.select_city') }}';
    var selectDistrictLang = '{{ trans('update.select_district') }}';
</script>

<script src="/assets/default/js/parts/get-regions.min.js"></script>
<script src="/assets/default/js/parts/cart.min.js"></script>