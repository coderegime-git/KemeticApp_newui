<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\RegionsDataByUser;
use App\Mixins\Cashback\CashbackRules;
use App\Models\Cart;
use App\Models\CartDiscount;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\Product;
use App\Models\Subscribe;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use Carbon\Carbon;
use App\Models\Region;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use RegionsDataByUser;

    // public function index()
    // {
    //     $user = auth()->user();
    //     $carts = Cart::where('creator_id', $user->id)
    //         ->with([
    //             'user',
    //             'webinar',
    //             'installmentPayment',
    //             'reserveMeeting' => function ($query) {
    //                 $query->with([
    //                     'meeting',
    //                     'meetingTime'
    //                 ]);
    //             },
    //             'ticket',
    //             'productOrder' => function ($query) {
    //                 $query->whereHas('product');
    //                 $query->with(['product']);
    //             }
    //         ])
    //         ->get();

    //     if (!empty($carts) and !$carts->isEmpty()) {
    //         $calculate = $this->calculatePrice($carts, $user);

    //         $hasPhysicalProduct = $carts->where('productOrder.product.type', Product::$physical);

    //         $deliveryEstimateTime = 0;

    //         if (!empty($hasPhysicalProduct) and count($hasPhysicalProduct)) {
    //             foreach ($hasPhysicalProduct as $physicalProductCart) {
    //                 if (!empty($physicalProductCart->productOrder) and
    //                     !empty($physicalProductCart->productOrder->product) and
    //                     !empty($physicalProductCart->productOrder->product->delivery_estimated_time) and
    //                     $physicalProductCart->productOrder->product->delivery_estimated_time > $deliveryEstimateTime
    //                 ) {
    //                     $deliveryEstimateTime = $physicalProductCart->productOrder->product->delivery_estimated_time;
    //                 }
    //             }
    //         }

    //         if (!empty($calculate)) {

    //             $totalCashbackAmount = $this->getTotalCashbackAmount($carts, $user, $calculate["sub_total"]);

    //             $cartDiscount = CartDiscount::query()
    //                 ->where('show_only_on_empty_cart', false)
    //                 ->where('enable', true)
    //                 ->first();


    //             $data = [
    //                 'pageTitle' => trans('public.cart_page_title'),
    //                 'user' => $user,
    //                 'carts' => $carts,
    //                 'subTotal' => $calculate["sub_total"],
    //                 'totalDiscount' => $calculate["total_discount"],
    //                 'tax' => $calculate["tax"],
    //                 'taxPrice' => $calculate["tax_price"],
    //                 'total' => $calculate["total"],
    //                 'productDeliveryFee' => $calculate["product_delivery_fee"],
    //                 'taxIsDifferent' => $calculate["tax_is_different"],
    //                 'userGroup' => !empty($user->userGroup) ? $user->userGroup->group : null,
    //                 'hasPhysicalProduct' => (count($hasPhysicalProduct) > 0),
    //                 'deliveryEstimateTime' => $deliveryEstimateTime,
    //                 'totalCashbackAmount' => $totalCashbackAmount,
    //                 'cartDiscount' => $cartDiscount,
    //             ];

    //             $data = array_merge($data, $this->getLocationsData($user));

    //             return view('web.default.cart.cart', $data);
    //         }
    //     } else {
    //         $cartDiscount = CartDiscount::query()->where('enable', true)->first();

    //         if (!empty($cartDiscount)) {
    //             $data = [
    //                 'pageTitle' => trans('update.cart_is_empty'),
    //                 'cartDiscount' => $cartDiscount,
    //             ];

    //             return view('web.default.cart.empty_cart', $data);
    //         }
    //     }

    //     return redirect('/');
    // }
    
    public function index(Request $request)
    {
        
        $user = auth()->user();
        $isGuest = false;
        
        if (!$user) {
            
            $isGuest = true;
    
            // Get or create device_id from session
            $deviceId = session('device_id');
            if (!$deviceId) {
                $deviceId = 'guest_' . uniqid();
                session(['device_id' => $deviceId]);
            }
    
            // Mimic a user object
            $user = new \stdClass();
            $user->id = $deviceId;
            $carts = Cart::where('creator_guest_id', $deviceId)
                ->with([
                    'productOrder' => function ($query) {
                        $query->whereHas('product')->with('product');
                    }
                ])
                ->get();
        } else {
           
            $carts = Cart::where('creator_id', $user->id)
                ->with([
                    'user',
                    'webinar',
                    'installmentPayment',
                    'reserveMeeting' => function ($query) {
                        $query->with(['meeting', 'meetingTime']);
                    },
                    'ticket',
                    'productOrder' => function ($query) {
                        $query->whereHas('product')->with('product');
                    }
                ])
                ->get();
        }
    
       // echo "<pre>"; print_r($carts); die;
        if ($carts->isNotEmpty()) {
            $calculate = $this->calculatePrice($carts, $user);
    
            // Only consider carts that have a valid productOrder + product
            $hasPhysicalProduct = $carts->filter(function ($item) {
                return isset($item->productOrder->product) &&
                       $item->productOrder->product->type == Product::$physical;
            });
    
            $deliveryEstimateTime = $hasPhysicalProduct->max(function ($item) {
                return $item->productOrder->product->delivery_estimated_time ?? 0;
            });
            
            $totalCashbackAmount = $this->getTotalCashbackAmount($carts, $user, $calculate["sub_total"]);
    
            $cartDiscount = CartDiscount::query()
                ->where('show_only_on_empty_cart', false)
                ->where('enable', true)
                ->first();
    
            $countries = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
            ->where('type', Region::$country)
            ->get();
            
            
            $data = [
                'pageTitle' => trans('public.cart_page_title'),
                'user' => $isGuest ? null : $user,
                'carts' => $carts,
                'subTotal' => $calculate["sub_total"],
                'totalDiscount' => $calculate["total_discount"],
                'tax' => $calculate["tax"],
                'taxPrice' => $calculate["tax_price"],
                'total' => $calculate["total"],
                'productDeliveryFee' => $calculate["product_delivery_fee"],
                'taxIsDifferent' => $calculate["tax_is_different"],
                'userGroup' => (!$isGuest && isset($user->userGroup)) ? $user->userGroup->group : null,
                'hasPhysicalProduct' => $hasPhysicalProduct->isNotEmpty(),
                'deliveryEstimateTime' => $deliveryEstimateTime,
                'totalCashbackAmount' => $totalCashbackAmount,
                'cartDiscount' => $cartDiscount,
                'countries' => $countries
            ];
    
            if (!$isGuest) {
                $data = array_merge($data, $this->getLocationsData($user));
            }
    
            return view('web.default.cart.cart', $data);
        }
    
        // Empty cart view
        $cartDiscount = CartDiscount::query()->where('enable', true)->first();
        
        return view('web.default.cart.empty_cart', [
            'pageTitle' => trans('update.cart_is_empty'),
            'cartDiscount' => $cartDiscount,
        ]);
    }

    public function couponValidate(Request $request)
    {
        $user = auth()->user() ?? apiAuth();
        $coupon = $request->get('coupon');

        $discountCoupon = Discount::where('code', $coupon)
            ->first();

        if (!empty($discountCoupon)) {
            $checkDiscount = $discountCoupon->checkValidDiscount();
            if ($checkDiscount != 'ok') {
                return response()->json([
                    'status' => 422,
                    'msg' => $checkDiscount
                ]);
            }

            $carts = Cart::where('creator_id', $user->id)
                ->get();

            if (!empty($carts) and !$carts->isEmpty()) {
                $calculate = $this->calculatePrice($carts, $user, $discountCoupon);

                if (!empty($calculate)) {
                    if(apiAuth()){
                        return apiResponse2(1, 'valid', trans('api.cart.valid_coupon'), [
                            'amounts' => $calculate,
                            'discount' => $discountCoupon,
                        ]);
                    }
                    
                    return response()->json([
                        'status' => 200,
                        'discount_id' => $discountCoupon->id,
                        'total_discount' => handlePrice($calculate["total_discount"]),
                        'total_tax' => handlePrice($calculate["tax_price"]),
                        'total_amount' => handlePrice($calculate["total"]),
                    ], 200);
                }
            }
        }

        return response()->json([
            'status' => 422,
            'msg' => trans('cart.coupon_invalid')
        ]);
    }

    private function handleDiscountPrice($discount, $carts, $subTotal)
    {
        $user = auth()->user();
        $percent = $discount->percent ?? 1;
        $totalDiscount = 0;

        if ($discount->source == Discount::$discountSourceCourse) {
            $totalWebinarsAmount = 0;
            $webinarOtherDiscounts = 0;
            $discountWebinarsIds = $discount->discountCourses()->pluck('course_id')->toArray();

            foreach ($carts as $cart) {
                $webinar = $cart->webinar;

                if (!empty($webinar) and (in_array($webinar->id, $discountWebinarsIds) or count($discountWebinarsIds) < 1)) {
                    $totalWebinarsAmount += $webinar->price;
                    //$webinarOtherDiscounts += $webinar->getDiscount($cart->ticket, $user);
                }
            }

            if ($discount->discount_type == Discount::$discountTypeFixedAmount) {
                $totalDiscount = ($totalWebinarsAmount > $discount->amount) ? $discount->amount : $totalWebinarsAmount;

                /*if (!empty($webinarOtherDiscounts)) {
                    $totalDiscount = $totalDiscount - (int)$webinarOtherDiscounts;
                }*/
            } else {
                $totalDiscount = ($totalWebinarsAmount > 0) ? $totalWebinarsAmount * $percent / 100 : 0;
            }
        } elseif ($discount->source == Discount::$discountSourceBundle) {
            $totalBundlesAmount = 0;
            $bundleOtherDiscounts = 0;
            $discountBundlesIds = $discount->discountBundles()->pluck('bundle_id')->toArray();

            foreach ($carts as $cart) {
                $bundle = $cart->bundle;
                if (!empty($bundle) and (in_array($bundle->id, $discountBundlesIds) or count($discountBundlesIds) < 1)) {
                    $totalBundlesAmount += $bundle->price;
                    //$bundleOtherDiscounts += $bundle->getDiscount($cart->ticket, $user);
                }
            }

            if ($discount->discount_type == Discount::$discountTypeFixedAmount) {
                $totalDiscount = ($totalBundlesAmount > $discount->amount) ? $discount->amount : $totalBundlesAmount;

                /*if (!empty($bundleOtherDiscounts)) {
                    $totalDiscount = $totalDiscount - (int)$bundleOtherDiscounts;
                }*/
            } else {
                $totalDiscount = ($totalBundlesAmount > 0) ? $totalBundlesAmount * $percent / 100 : 0;
            }
        } elseif ($discount->source == Discount::$discountSourceProduct) {
            $totalProductsAmount = 0;
            $productOtherDiscounts = 0;

            foreach ($carts as $cart) {
                if (!empty($cart->productOrder)) {
                    $product = $cart->productOrder->product;

                    if (!empty($product) and ($discount->product_type == 'all' or $discount->product_type == $product->type)) {
                        $totalProductsAmount += ($product->price * $cart->productOrder->quantity);
                        //$productOtherDiscounts += $product->getDiscountPrice();
                    }
                }
            }

            if ($discount->discount_type == Discount::$discountTypeFixedAmount) {
                $totalDiscount = ($totalProductsAmount > $discount->amount) ? $discount->amount : $totalProductsAmount;

                /*if (!empty($productOtherDiscounts)) {
                    $totalDiscount = $totalDiscount - (int)$productOtherDiscounts;
                }*/
            } else {
                $totalDiscount = ($totalProductsAmount > 0) ? $totalProductsAmount * $percent / 100 : 0;
            }
        } elseif ($discount->source == Discount::$discountSourceMeeting) {
            $totalMeetingAmount = 0;
            $meetingOtherDiscounts = 0;

            foreach ($carts as $cart) {
                $reserveMeeting = $cart->reserveMeeting;

                if (!empty($reserveMeeting)) {
                    $totalMeetingAmount += $reserveMeeting->paid_amount;
                    //$meetingOtherDiscounts += $reserveMeeting->getDiscountPrice($user);
                }
            }

            if ($discount->discount_type == Discount::$discountTypeFixedAmount) {
                $totalDiscount = ($totalMeetingAmount > $discount->amount) ? $discount->amount : $totalMeetingAmount;

                /*if (!empty($meetingOtherDiscounts)) {
                    $totalDiscount = $totalDiscount - $meetingOtherDiscounts;
                }*/
            } else {
                $totalDiscount = ($totalMeetingAmount > 0) ? $totalMeetingAmount * $percent / 100 : 0;
            }
        } elseif ($discount->source == Discount::$discountSourceCategory) {
            $totalCategoriesAmount = 0;
            $categoriesOtherDiscounts = 0;

            $categoriesIds = ($discount->discountCategories) ? $discount->discountCategories()->pluck('category_id')->toArray() : [];

            foreach ($carts as $cart) {
                $webinar = $cart->webinar;

                if (!empty($webinar) and in_array($webinar->category_id, $categoriesIds)) {
                    $totalCategoriesAmount += $webinar->price;
                    //$categoriesOtherDiscounts += $webinar->getDiscount($cart->ticket, $user);
                }
            }

            if ($discount->discount_type == Discount::$discountTypeFixedAmount) {
                $totalDiscount = ($totalCategoriesAmount > $discount->amount) ? $discount->amount : $totalCategoriesAmount;

                /*if (!empty($categoriesOtherDiscounts)) {
                    $totalDiscount = $totalDiscount - $categoriesOtherDiscounts;
                }*/
            } else {
                $totalDiscount = ($totalCategoriesAmount > 0) ? $totalCategoriesAmount * $percent / 100 : 0;
            }
        } else {
            $totalCartAmount = 0;
            $totalCartOtherDiscounts = 0;

            foreach ($carts as $cart) {
                $webinar = $cart->webinar;
                $bundle = $cart->bundle;
                $reserveMeeting = $cart->reserveMeeting;

                if (!empty($webinar)) {
                    $totalCartAmount += $webinar->price;
                    //$totalCartOtherDiscounts += $webinar->getDiscount($cart->ticket, $user);
                }

                if (!empty($reserveMeeting)) {
                    $totalCartAmount += $reserveMeeting->paid_amount;
                    //$totalCartOtherDiscounts += $reserveMeeting->getDiscountPrice($user);
                }

                if (!empty($bundle)) {
                    $totalCartAmount += $bundle->price;
                    //$bundleOtherDiscounts += $bundle->getDiscount($cart->ticket, $user);
                }

                if (!empty($cart->productOrder)) {
                    $product = $cart->productOrder->product;

                    if (!empty($product)) {
                        $totalCartAmount += ($product->price * $cart->productOrder->quantity);
                        //$productOtherDiscounts += $product->getDiscountPrice();
                    }
                }
            }

            if ($discount->discount_type == Discount::$discountTypeFixedAmount) {
                $totalDiscount = ($totalCartAmount > $discount->amount) ? $discount->amount : $totalCartAmount;

                /*if (!empty($totalCartOtherDiscounts)) {
                    $totalDiscount = $totalDiscount - $totalCartOtherDiscounts;
                }*/
            } else {
                $totalDiscount = ($totalCartAmount > 0) ? $totalCartAmount * $percent / 100 : 0;
            }
        }

        if ($discount->discount_type != Discount::$discountTypeFixedAmount and !empty($discount->max_amount) and $totalDiscount > $discount->max_amount) {
            $totalDiscount = $discount->max_amount;
        }

        return $totalDiscount;
    }

    private function productDeliveryFeeBySeller($carts)
    {
        $productFee = [];

        foreach ($carts as $cart) {
            if (!empty($cart->productOrder) and !empty($cart->productOrder->product)) {
                $product = $cart->productOrder->product;

                if (!empty($product->delivery_fee)) {
                    if (!empty($productFee[$product->creator_id]) and $productFee[$product->creator_id] < $product->delivery_fee) {
                        $productFee[$product->creator_id] = $product->delivery_fee;
                    } else if (empty($productFee[$product->creator_id])) {
                        $productFee[$product->creator_id] = $product->delivery_fee;
                    }
                }
            }
        }

        return $productFee;
    }

    private function physicalProductCountBySeller($carts)
    {
        $productCount = [];

        foreach ($carts as $cart) {
            if (!empty($cart->productOrder) and !empty($cart->productOrder->product)) {
                $product = $cart->productOrder->product;

                if (!empty($product) and $product->isPhysical()) {
                    if (!empty($productCount[$product->creator_id])) {
                        $productCount[$product->creator_id] += 1;
                    } else {
                        $productCount[$product->creator_id] = 1;
                    }
                }
            }
        }

        return $productCount;
    }

    private function calculateProductDeliveryFee($carts)
    {
        $fee = 0;

        if (!empty($carts)) {
            $productsFee = $this->productDeliveryFeeBySeller($carts);

            if (!empty($productsFee) and count($productsFee)) {
                $fee = array_sum($productsFee);
            }
        }

        return $fee;
    }

    public function calculatePrice($carts, $user, $discountCoupon = null)
    {
        $financialSettings = getFinancialSettings();

        $subTotal = 0;
        $totalDiscount = 0;
        $tax = (!empty($financialSettings['tax']) and $financialSettings['tax'] > 0) ? $financialSettings['tax'] : 0;
        $taxPrice = 0;
        $commissionPrice = 0;
        $commission = 0;

        $cartHasWebinar = array_filter($carts->pluck('webinar_id')->toArray());
        $cartHasBundle = array_filter($carts->pluck('bundle_id')->toArray());
        $cartHasMeeting = array_filter($carts->pluck('reserve_meeting_id')->toArray());
        $cartHasInstallmentPayment = array_filter($carts->pluck('installment_payment_id')->toArray());

        $taxIsDifferent = (count($cartHasWebinar) or count($cartHasBundle) or count($cartHasMeeting) or count($cartHasInstallmentPayment));

        foreach ($carts as $cart) {
            $orderPrices = $this->handleOrderPrices($cart, $user, $taxIsDifferent, $discountCoupon);
            $subTotal += $orderPrices['sub_total'];
            $totalDiscount += $orderPrices['total_discount'];
            $tax = $orderPrices['tax'];
            $taxPrice += $orderPrices['tax_price'];
            $commission += $orderPrices['commission'];
            $commissionPrice += $orderPrices['commission_price'];
            $taxIsDifferent = $orderPrices['tax_is_different'];
        }

        if (!empty($discountCoupon)) {
            $totalDiscount += $this->handleDiscountPrice($discountCoupon, $carts, $subTotal);
        }

        if ($totalDiscount > $subTotal) {
            $totalDiscount = $subTotal;
        }

        $subTotalWithoutDiscount = $subTotal - $totalDiscount;
        $productDeliveryFee = $this->calculateProductDeliveryFee($carts);

        $total = $subTotalWithoutDiscount + $taxPrice + $productDeliveryFee;

        if ($total < 0) {
            $total = 0;
        }

        return [
            'sub_total' => round($subTotal, 2),
            'total_discount' => round($totalDiscount, 2),
            'tax' => $tax,
            'tax_price' => round($taxPrice, 2),
            'commission' => $commission,
            'commission_price' => round($commissionPrice, 2),
            'total' => round($total, 2),
            'product_delivery_fee' => round($productDeliveryFee, 2),
            'tax_is_different' => $taxIsDifferent
        ];
    }

    public function checkout(Request $request, $carts = null)
    {
        $user = auth()->user();
        $user_as_a_guest=false;
        //dd('check');
        if (empty($carts)) {
            if($user){
                $carts = Cart::where('creator_id', $user->id)
                ->get();
            }
            else{
                
                if (session()->has('device_id')) {
                    $user = new \stdClass(); // Create an empty object for guest users
                    $user->id = session('device_id');
                    $user_as_a_guest=true;
                    $carts = Cart::where('creator_guest_id', $user->id)
                    ->get();
                }
                else{
                    return redirect('/cart');
                }
                
            }
        }
        
      
        $hasPhysicalProduct = $carts->where('productOrder.product.type', Product::$physical);
   
        $checkAddressValidation = (count($hasPhysicalProduct) > 0);
 
        if (empty(getStoreSettings('show_address_selection_in_cart')) or !empty(getStoreSettings('take_address_selection_optional'))) {
            $checkAddressValidation = false;
        }
        
        if(!$user_as_a_guest){
            
            $this->validate($request, [
                'country_id' => Rule::requiredIf($checkAddressValidation),
                // 'province_id' => Rule::requiredIf($checkAddressValidation),
                // 'city_id' => Rule::requiredIf($checkAddressValidation),
                // 'district_id' => Rule::requiredIf($checkAddressValidation),
                'address' => Rule::requiredIf($checkAddressValidation),
                'house_no' => Rule::requiredIf($checkAddressValidation),
                'zip_code' => Rule::requiredIf($checkAddressValidation),
            ]);
        }
        else{
           
            $rules = [
                'first_name'   => ['required', 'string', 'max:100'],
                'last_name'    => ['required', 'string', 'max:100'],
                'email'        => ['required', 'email', 'max:255'],
                'phone'        => ['required', 'string', 'max:20'],
                'country_id'   => ['required', 'integer'],
                /*'province_name'=> ['required', 'string', 'max:100'],*/
                'city_name'    => ['required', 'string', 'max:100'],
                'address'      => ['required', 'string', 'max:255'],
                'house_no'     => ['required', 'string', 'max:50'],
                'zip_code'     => ['required', 'string', 'max:20'],
            ];
            $this->validate($request, $rules);
        }

        $discountId = $request->input('discount_id');

        $paymentChannels = PaymentChannel::where('status', 'active')->get();

        $discountCoupon = Discount::where('id', $discountId)->first();

        if (empty($discountCoupon) or $discountCoupon->checkValidDiscount() != 'ok') {
            $discountCoupon = null;
        }

        if (!empty($carts) and !$carts->isEmpty()) {
            $calculate = $this->calculatePrice($carts, $user);

            $order = $this->createOrderAndOrderItems($carts, $calculate, $user, $discountCoupon);

            if (!empty($discountCoupon)) {
                $totalCouponDiscount = $this->handleDiscountPrice($discountCoupon, $carts, $calculate['sub_total']);
                $calculate['total_discount'] += $totalCouponDiscount;
                $calculate["total"] = $calculate["total"] - $totalCouponDiscount;
            }

            if (count($hasPhysicalProduct) > 0) {
                $this->updateProductOrders($request, $carts, $user,$user_as_a_guest);
            }
            elseif($user_as_a_guest){
                $this->updateProductOrders($request, $carts, $user,$user_as_a_guest);
            }
            
//dd('check2');

            if (!empty($order) and $order->total_amount > 0) {
                $razorpay = false;
                $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));

                foreach ($paymentChannels as $paymentChannel) {
                    if ($paymentChannel->class_name == 'Razorpay' and (!$isMultiCurrency or in_array(currency(), $paymentChannel->currencies))) {
                        $razorpay = true;
                    }
                }

                $totalCashbackAmount = $this->getTotalCashbackAmount($carts, $user, $calculate["sub_total"]);
                $usergrp = null;
                $usercharge = 0;
                if(!$user_as_a_guest){
                    $usergrp = $user->userGroup ? $user->userGroup->group : null;
                    $usercharge = $user->getAccountingCharge();
                }

                $subscribes = Subscribe::getActiveSubscribe($user->id);
                $data = [
                    'pageTitle' => trans('public.checkout_page_title'),
                    'paymentChannels' => $paymentChannels,
                    'carts' => $carts,
                    'subTotal' => $calculate["sub_total"],
                    'totalDiscount' => $calculate["total_discount"],
                    'tax' => $calculate["tax"],
                    'taxPrice' => $calculate["tax_price"],
                    'total' => $calculate["total"],
                    'userGroup' => $usergrp,
                    'order' => $order,
                    'count' => $carts->count(),
                    'subscribes'=> $subscribes,
                    'userCharge' => $usercharge,
                    'razorpay' => $razorpay,
                    'totalCashbackAmount' => $totalCashbackAmount,
                    'previousUrl' => url()->previous(),
                ];
                return view(getTemplate() . '.cart.payment', $data);
            } else {
                return $this->handlePaymentOrderWithZeroTotalAmount($order);
            }
        }

        return redirect('/cart');
    }

    private function updateProductOrders(Request $request, $carts, $user,$user_as_a_guest)
    {
        $data = $request->all();

        foreach ($carts as $cart) {
            if (!empty($cart->product_order_id)) {
                ProductOrder::where('id', $cart->product_order_id)
                    ->where('buyer_id', $user->id)
                    ->update([
                        'message_to_seller' => $data['message_to_seller']??null,
                    ]);
            }
        }

        if(!$user_as_a_guest){
            $user->update([
                'country_id' => $data['country_id'] ?? $user->country_id,
                'province_name' => $data['province_name'] ?? $user->province_name,
                'city_name' => $data['city_name'] ?? $user->city_name,
                'district_name' => $data['district_name'] ?? $user->district_name,
                'zip_code' => $data['zip_code'] ?? $user->zip_code,
                'house_no' => $data['house_no'] ?? $user->house_no,
                'address' => $data['address'] ?? $user->address,
            ]);
        }
        else{
            $name = $data['first_name']." ".$data['last_name'];
            $createuser = User::create([
                'device_id_or_ip_address' => session('device_id'),
                'country_id'    => $data['country_id'] ?? null,
                'province_name' => $data['province_id'] ?? null,
                'city_name'     => $data['city_id'] ?? null,
                'zip_code'      => $data['zip_code'] ?? null,
                'house_no'      => $data['house_no'] ?? null,
                'address'       => $data['address'] ?? null,
                'full_name'     => $name ?? null,
                'email'         => $data['email'] ?? null,
                'mobile'        => $data['mobile'] ?? null,
                'role_id'       => 1,
                'role_name'     => 'user',
                'created_at'    => Carbon::now()->timestamp,
                'updated_at'    => Carbon::now()->timestamp
            ]);
            if($data['create_account']){    
                if($createuser->id){
                    if($data['create_account']){
                        Cart::where('creator_guest_id', $user->id)
                        ->update([
                            'creator_id' => $createuser->id,
                        ]);
                    }
                }
            }
                
        }
    }

    public function createOrderAndOrderItems($carts, $calculate, $user, $discountCoupon = null)
    {
        $totalCouponDiscount = 0;

        if (!empty($discountCoupon)) {
            $totalCouponDiscount = $this->handleDiscountPrice($discountCoupon, $carts, $calculate['sub_total']);
        }

        $totalAmount = $calculate["total"] - $totalCouponDiscount;

        $order = Order::create([
            'user_id' => $user->id,
            'status' => Order::$pending,
            'amount' => $calculate["sub_total"],
            'tax' => $calculate["tax_price"],
            'total_discount' => $calculate["total_discount"] + $totalCouponDiscount,
            'total_amount' => ($totalAmount > 0) ? $totalAmount : 0,
            'product_delivery_fee' => $calculate["product_delivery_fee"] ?? null,
            'created_at' => time(),
        ]);

        $productsFee = $this->productDeliveryFeeBySeller($carts);
        $sellersProductsCount = $this->physicalProductCountBySeller($carts);

        foreach ($carts as $cart) {

            $orderPrices = $this->handleOrderPrices($cart, $user);
            $price = $orderPrices['sub_total'];
            $totalDiscount = $orderPrices['total_discount'];
            $tax = $orderPrices['tax'];
            $taxPrice = $orderPrices['tax_price'];
            $commission = $orderPrices['commission'];
            $commissionPrice = $orderPrices['commission_price'];


            $productDeliveryFee = 0;
            if (!empty($cart->product_order_id)) {
                $product = $cart->productOrder->product;

                if (!empty($product) and $product->isPhysical() and !empty($productsFee[$product->creator_id])) {
                    $productDeliveryFee = $productsFee[$product->creator_id];
                }

                $sellerProductCount = !empty($sellersProductsCount[$product->creator_id]) ? $sellersProductsCount[$product->creator_id] : 1;

                $productDeliveryFee = $productDeliveryFee > 0 ? $productDeliveryFee / $sellerProductCount : 0;
            }

            $allDiscountPrice = $totalDiscount;
            if ($totalCouponDiscount > 0 and $price > 0) {
                $percent = (($price / $calculate["sub_total"]) * 100);
                $allDiscountPrice += (($totalCouponDiscount * $percent) / 100);
            }

            $subTotalWithoutDiscount = $price - $allDiscountPrice;
            $totalAmount = $subTotalWithoutDiscount + $taxPrice + $productDeliveryFee;

            $ticket = $cart->ticket;
            if (!empty($ticket) and !$ticket->isValid()) {
                $ticket = null;
            }

            OrderItem::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'webinar_id' => $cart->webinar_id ?? null,
                'bundle_id' => $cart->bundle_id ?? null,
                'product_id' => (!empty($cart->product_order_id) and !empty($cart->productOrder->product)) ? $cart->productOrder->product->id : null,
                'product_order_id' => (!empty($cart->product_order_id)) ? $cart->product_order_id : null,
                'reserve_meeting_id' => $cart->reserve_meeting_id ?? null,
                'subscribe_id' => $cart->subscribe_id ?? null,
                'promotion_id' => $cart->promotion_id ?? null,
                'gift_id' => $cart->gift_id ?? null,
                'installment_payment_id' => $cart->installment_payment_id ?? null,
                'ticket_id' => !empty($ticket) ? $ticket->id : null,
                'discount_id' => $discountCoupon ? $discountCoupon->id : null,
                'amount' => $price,
                'total_amount' => $totalAmount,
                'tax' => $tax,
                'tax_price' => $taxPrice,
                'commission' => $commission,
                'commission_price' => $commissionPrice,
                'product_delivery_fee' => $productDeliveryFee,
                'discount' => $allDiscountPrice,
                'created_at' => time(),
            ]);
        }

        return $order;
    }

    private function getSeller($cart)
    {
        $user = null;

        if (!empty($cart->webinar_id) or !empty($cart->bundle_id)) {
            $user = $cart->webinar_id ? $cart->webinar->creator : $cart->bundle->creator;
        } elseif (!empty($cart->reserve_meeting_id)) {
            $user = $cart->reserveMeeting->meeting->creator;
        } elseif (!empty($cart->product_order_id)) {
            $user = $cart->productOrder->seller;
        }

        return $user;
    }

    /**
     * @param $sources => \App\Models\UserCommission::$sources
     * @param $itemPrice
     * @param null $seller
     * */
    private function getCommissionPrice($source, $itemPrice, $seller = null)
    {
        $hasSellerSpecificCommission = false;
        $commissionPrice = 0;

        if (!empty($seller)) {
            $userCommission = $seller->commissions()->where('source', $source)->first();

            if (!empty($userCommission)) {
                $hasSellerSpecificCommission = true;
                $commissionPrice = $userCommission->calculatePrice($itemPrice);
            } else {
                $userGroup = $seller->getUserGroup();

                if (!empty($userGroup)) {
                    $groupCommission = $userGroup->commissions()->where('source', $source)->first();

                    if (!empty($groupCommission)) {
                        $hasSellerSpecificCommission = true;
                        $commissionPrice = $groupCommission->calculatePrice($itemPrice);
                    }
                }
            }
        }

        if (!$hasSellerSpecificCommission) {
            // Get System Default Commission

            $commissionSettings = getCommissionSettings();

            if (!empty($commissionSettings) and !empty($commissionSettings[$source]) and !empty($commissionSettings[$source]['type']) and !empty($commissionSettings[$source]['value'])) {
                $type = $commissionSettings[$source]['type'];
                $value = $commissionSettings[$source]['value'];

                if ($type == "percent") {
                    $commissionPrice = $itemPrice > 0 ? (($itemPrice * $value) / 100) : 0;
                } else {
                    $commissionPrice = $value;
                }
            }
        }

        return $commissionPrice;
    }


    public function handleOrderPrices($cart, $user, $taxIsDifferent = false, $discountCoupon = null)
    {
        $seller = $this->getSeller($cart);
        $financialSettings = getFinancialSettings();

        $subTotal = 0;
        $totalDiscount = 0;
        $tax = (!empty($financialSettings['tax']) and $financialSettings['tax'] > 0) ? $financialSettings['tax'] : 0;
        $taxPrice = 0;
        $commissionPrice = 0;
        $priceWithoutDiscount = 0;


        if (!empty($cart->webinar_id) or !empty($cart->bundle_id)) {
            $item = !empty($cart->webinar_id) ? $cart->webinar : $cart->bundle;
            $price = $item->price;
            $discount = $item->getDiscount($cart->ticket, $user);

            $priceWithoutDiscount = $price - $discount;

            if ($tax > 0 and $priceWithoutDiscount > 0) {
                $taxPrice += $priceWithoutDiscount * $tax / 100;
            }

            $source = !empty($cart->webinar_id) ? 'courses' : 'bundles';
            $commissionPrice += $this->getCommissionPrice($source, $priceWithoutDiscount, $seller);

            $totalDiscount += $discount;
            $subTotal += $price;
        } elseif (!empty($cart->reserve_meeting_id)) {
            $price = $cart->reserveMeeting->paid_amount;
            $discount = $cart->reserveMeeting->getDiscountPrice($user);

            $priceWithoutDiscount = $price - $discount;

            if ($tax > 0 and $priceWithoutDiscount > 0) {
                $taxPrice += $priceWithoutDiscount * $tax / 100;
            }

            $commissionPrice += $this->getCommissionPrice('meetings', $priceWithoutDiscount, $seller);

            $totalDiscount += $discount;
            $subTotal += $price;
        } elseif (!empty($cart->product_order_id)) {
            $product = $cart->productOrder->product;

            if (!empty($product)) {
                $productQuantity = $cart->productOrder->quantity;
                $price = ($product->price * $productQuantity);
                $discount = $product->getDiscountPrice() * $productQuantity;

                $productTax = $product->getTax();

                $priceWithoutDiscount = $price - $discount;

                $taxIsDifferent = ($taxIsDifferent and $tax != $productTax);

                $tax = $productTax;
                if ($productTax > 0 and $priceWithoutDiscount > 0) {
                    $taxPrice += $priceWithoutDiscount * $productTax / 100;
                }

                // Product Commission
                if (isset($product->commission)) {
                    if ($product->commission_type == "percent") {
                        $commissionPrice += ($priceWithoutDiscount > 0 and $product->commission > 0) ? (($priceWithoutDiscount * $product->commission) / 100) : 0;
                    } else {
                        $commissionPrice += $product->commission;
                    }
                } else {
                    $source = ($product->type == Product::$physical) ? 'physical_products' : 'virtual_products';
                    $commissionPrice += $this->getCommissionPrice($source, $priceWithoutDiscount, $seller);
                }

                $totalDiscount += $discount;
                $subTotal += $price;
            }
        } elseif (!empty($cart->installment_payment_id)) {
            $price = $cart->installmentPayment->amount;
            $discount = 0;

            $priceWithoutDiscount = $price - $discount;

            if ($tax > 0 and $priceWithoutDiscount > 0) {
                $taxPrice += $priceWithoutDiscount * $tax / 100;
            }

            // Commission
            $installmentOrder = $cart->installmentPayment->installmentOrder;

            if (!empty($installmentOrder)) {
                $source = null;

                if (!empty($installmentOrder->webinar_id)) {
                    $source = "courses";
                } elseif (!empty($installmentOrder->bundle_id)) {
                    $source = "bundles";
                } elseif (!empty($installmentOrder->product_id) and !empty($installmentOrder->product)) {
                    if ($installmentOrder->product->type == Product::$physical) {
                        $source = "physical_products";
                    } else {
                        $source = "virtual_products";
                    }
                }

                if (!empty($source)) {
                    $commissionPrice += $this->getCommissionPrice($source, $priceWithoutDiscount, $seller);
                }
            }

            $totalDiscount += $discount;
            $subTotal += $price;
        }

        if ($totalDiscount > $subTotal) {
            $totalDiscount = $subTotal;
        }

        $commission = ($commissionPrice > 0 and $priceWithoutDiscount > 0) ? (($commissionPrice / $priceWithoutDiscount) * 100) : 0;

        return [
            'sub_total' => round($subTotal, 2),
            'total_discount' => round($totalDiscount, 2),
            'tax' => $tax,
            'tax_price' => round($taxPrice, 2),
            'commission' => $commission,
            'commission_price' => round($commissionPrice, 2),
            //'product_delivery_fee' => round($productDeliveryFee, 2),
            'tax_is_different' => $taxIsDifferent
        ];
    }

    private function handlePaymentOrderWithZeroTotalAmount($order)
    {
        $order->update([
            'payment_method' => Order::$paymentChannel
        ]);

        $paymentController = new PaymentController();

        $paymentController->setPaymentAccounting($order);

        $order->update([
            'status' => Order::$paid
        ]);

        return redirect('/payments/status?order_id=' . $order->id);
    }


    private function getTotalCashbackAmount($carts, $user, $subTotal)
    {
        $amount = 0;

        if (getFeaturesSettings('cashback_active') and (empty($user) or !$user->disable_cashback)) {
            $cashbackRulesMixin = new CashbackRules($user);
            $applyPerItemRules = [];

            foreach ($carts as $cart) {
                $rules = $cashbackRulesMixin->getRulesByItem($cart);

                if (!empty($rules) and count($rules)) {
                    foreach ($rules as $rule) {
                        if (empty($rule->min_amount) or $rule->min_amount <= $subTotal) {
                            if ($rule->amount_type == "fixed_amount") {
                                if ($rule->apply_cashback_per_item and $rule->apply_cashback_per_item > 0) {
                                    $amount += $rule->amount;
                                } else {
                                    $applyPerItemRules[$rule->id] = $rule;
                                }
                            } else if ($rule->amount_type == "percent") {
                                $itemOrder = $this->handleOrderPrices($cart, $user);
                                $itemPrice = $itemOrder['sub_total'];

                                if (!empty($itemOrder['total_discount'])) {
                                    $itemPrice = $itemPrice - $itemOrder['total_discount'];
                                }

                                $ruleAmount = $rule->getAmount($itemPrice);

                                if (!empty($rule->max_amount) and $rule->max_amount < $ruleAmount) {
                                    $amount += $rule->max_amount;
                                } else {
                                    $amount += $ruleAmount;
                                }
                            }
                        }
                    }
                }
            }


            if (!empty($applyPerItemRules)) {
                foreach ($applyPerItemRules as $applyPerItemRule) {
                    $amount += $applyPerItemRule->amount;
                }
            }
        }

        return $amount;
    }
}
