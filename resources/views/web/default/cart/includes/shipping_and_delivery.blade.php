<section class="mt-45">
    <h3 class="section-title">{{ trans('update.shipping_and_delivery') }}</h3>
    <div class="rounded-sm shadow mt-20 py-25 px-20">
        <div class="row">

            @if(!empty(getStoreSettings('show_address_selection_in_cart')))
            <div class="col-12 col-lg-6">
                
                @if(empty($user))
                {{--  @php   $name = explode(" ",$user->full_name); @endphp --}}
                
                <div class="form-group">
                    <input type="hidden" name="create_account" value="0">
                    <input type="checkbox" name="create_account" class="@error('create_account') is-invalid @enderror" 
                           value="1">
                    <label class="input-label font-weight-500"><b>Create an account with kemetic app</b></label>
                </div>
                
                <div class="form-group">
                    <label class="input-label font-weight-500">{{ trans('update.first_name') }}</label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                           value="">
                
                    @error('first_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="input-label font-weight-500">{{ trans('update.last_name') }}</label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                           value="">
                
                    @error('last_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="input-label font-weight-500">{{ trans('update.address') }}</label>

                    <textarea name="address" rows="6" class="form-control @error('address')  is-invalid @enderror">{{ !empty($user) ? $user->address : '' }}</textarea>

                    @error('address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="input-label font-weight-500">House No.</label>
                    <input type="text" name="house_no" id="house_no" value="{{ !empty($user) ? $user->house_no : '' }}" class="form-control @error('house_no')  is-invalid @enderror">
                    @error('house_no')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="input-label font-weight-500">{{ trans('update.email') }}</label>
                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ !empty($user) ? $user->email : '' }}">
                
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="input-label font-weight-500">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ !empty($user) ? $user->mobile : '' }}">
                
                    @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                @endif
                
                <div class="form-group">
                    <label class="input-label font-weight-500">{{ trans('update.country') }}</label>

                    <select name="country_id" class="form-control @error('country_id')  is-invalid @enderror">
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
                
                <!--<input type="hidden" name="province_id" value="0">-->
                <!--<input type="hidden" name="city_id" value="0">-->
                
                <div class="form-group">
                    <label class="input-label font-weight-500">Province/State/District</label>
                    <input type="text" name="province_name" class="form-control @error('province_name') is-invalid @enderror" 
                           value="{{ !empty($user) ? $user->province_name : '' }}">
                
                    @error('province_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="form-group d-none">
                    <label class="input-label font-weight-500">{{ trans('update.district') }}</label>
                    <input type="text" name="district_name" class="form-control @error('district_name') is-invalid @enderror" 
                           value="{{ !empty($user) ? $user->district_name : '' }}">
                
                    @error('district_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>    
                
                <div class="form-group">
                    <label class="input-label font-weight-500">{{ trans('update.city') }}</label>
                    <input type="text" name="city_name" class="form-control @error('city_name') is-invalid @enderror" 
                           value="{{ !empty($user) ? $user->city_name : '' }}">
                
                    @error('city_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>      
                  

                <!--<div class="form-group">-->
                <!--    <label class="input-label font-weight-500">{{ trans('update.province') }}</label>-->

                    <!-- <select name="province_id" class="form-control @error('province_id')  is-invalid @enderror" {{ (!empty($user) and $user->province_id) ? '' : 'disabled' }}> -->
                <!--    <select name="province_id" class="form-control" {{ (!empty($user) and $user->province_id) ? '' : 'disabled' }}>-->
                <!--        <option value="">{{ trans('update.select_province') }}</option>-->

                <!--        @if(!empty($provinces))-->
                <!--        @foreach($provinces as $province)-->
                <!--        <option value="{{ $province->id }}" {{ (!empty($user) and $user->province_id == $province->id) ? 'selected' : '' }}>{{ $province->title }}</option>-->
                <!--        @endforeach-->
                <!--        @endif-->
                <!--    </select>-->

                <!--    @error('province_id')-->
                <!--    <div class="invalid-feedback">-->
                <!--        {{ $message }}-->
                <!--    </div>-->
                <!--    @enderror-->
                <!--</div>-->

                <!--<div class="form-group">-->
                <!--    <label class="input-label font-weight-500">{{ trans('update.city') }}</label>-->

                    <!-- <select name="city_id" class="form-control @error('city_id')  is-invalid @enderror" {{ (!empty($user) and $user->city_id) ? '' : 'disabled' }}> -->
                <!--    <select name="city_id" class="form-control" {{ (!empty($user) and $user->city_id) ? '' : 'disabled' }}>-->
                <!--        <option value="">{{ trans('update.select_city') }}</option>-->

                <!--        @if(!empty($cities))-->
                <!--        @foreach($cities as $city)-->
                <!--        <option value="{{ $city->id }}" {{ (!empty($user) and $user->city_id == $city->id) ? 'selected' : '' }}>{{ $city->title }}</option>-->
                <!--        @endforeach-->
                <!--        @endif-->
                <!--    </select>-->

                <!--    @error('city_id')-->
                <!--    <div class="invalid-feedback">-->
                <!--        {{ $message }}-->
                <!--    </div>-->
                <!--    @enderror-->
                <!--</div>-->

                <!--<div class="form-group d-none">-->
                <!--    <label class="input-label font-weight-500">{{ trans('update.district') }}</label>-->

                    <!-- <select name="district_id" class="form-control @error('district_id')  is-invalid @enderror" {{ (!empty($user) and $user->district_id) ? '' : 'disabled' }}> -->
                <!--    <select name="district_id" class="form-control" {{ (!empty($user) and $user->district_id) ? '' : 'disabled' }}>-->
                <!--        <option value="">{{ trans('update.select_district') }}</option>-->

                <!--        @if(!empty($districts))-->
                <!--        @foreach($districts as $district)-->
                <!--        <option value="{{ $district->id }}" {{ (!empty($user) and $user->district_id == $district->id) ? 'selected' : '' }}>{{ $district->title }}</option>-->
                <!--        @endforeach-->
                <!--        @endif-->
                <!--    </select>-->

                <!--    @error('district_id')-->
                <!--    <div class="invalid-feedback">-->
                <!--        {{ $message }}-->
                <!--    </div>-->
                <!--    @enderror-->
                <!--</div>-->

                <div class="form-group">
                    <label class="input-label font-weight-500">Zip Code</label>
                    <input type="text" name="zip_code" id="zip_code" value="{{ !empty($user) ? $user->zip_code : '' }}" class="form-control @error('zip_code')  is-invalid @enderror">
                    @error('zip_code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
            </div>
            @endif

            <div class="col-12 {{ !empty(getStoreSettings('show_address_selection_in_cart')) ? 'col-lg-6' : '' }}">
                
                <div class="form-group">
                    <label class="input-label font-weight-500"></label>
                </div>
                

                <div class="form-group">
                    <label class="input-label font-weight-500">{{ trans('update.message_to_seller') }}</label>

                    <textarea name="message_to_seller" rows="8" class="form-control @error('message_to_seller')  is-invalid @enderror"></textarea>

                    @error('message_to_seller')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</section>

@if(!empty($deliveryEstimateTime))
<div class="d-flex align-items-center mt-30 rounded-lg border px-10 py-5">
    <div class="appointment-timezone-icon">
        <img src="/assets/default/img/icons/timezone.svg" alt="appointment timezone">
    </div>
    <div class="ml-15">
        <div class="font-16 font-weight-bold text-dark-blue">{{ trans('update.cart_order_estimated_delivery_time') }}</div>
        <p class="font-14 font-weight-500 text-gray">{{ trans('update.cart_order_estimated_delivery_time_hint',['days' => $deliveryEstimateTime]) }}</p>
    </div>
</div>
@endif