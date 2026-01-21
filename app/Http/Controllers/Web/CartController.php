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
use App\Models\BookOrder;
use App\User;
use Carbon\Carbon;
use App\Models\Region;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use App\Services\PdfResizerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    use RegionsDataByUser;

    protected $laragonCertPath;
    protected $pdfResizer;

    public function __construct()
    {
        $pdfResizer = new PdfResizerService();
        $this->pdfResizer = $pdfResizer;
        // Laragon certificate path - adjust if different
        $this->laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        
        // Alternative paths to check
        $alternativePaths = [
            "C:/laragon/etc/ssl/cert.pem",
            "C:/laragon/etc/ssl/cacert.pem",
            "C:/laragon/etc/ssl/certs/ca-bundle.crt",
            base_path("cacert.pem"), // If you want to store in your project
            storage_path("app/certs/cacert.pem"), // Custom storage path
        ];
        
        // Find the first existing certificate file
        foreach ($alternativePaths as $path) {
            if (file_exists($path)) {
                $this->laragonCertPath = $path;
                break;
            }
        }
    }

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
                    },
                    'bookOrder' => function ($query) {
                        $query->whereHas('book')->with('book');
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
                    },
                    'bookOrder' => function ($query) {
                        $query->whereHas('book')->with('book');
                    }
                ])
                ->get();
        }
    
       // echo "<pre>"; print_r($carts); die;
        if ($carts->isNotEmpty()) {
            $calculate = $this->calculatePrice($carts, $user, null, $request);
    
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

            // dd($carts);
    
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
    
    public function calculateShipping(Request $request)
    {
        
        try {
            $user = auth()->user();
            $isGuest = false;
            
            // Get cart items
            if (!$user) {
                $isGuest = true;
                $deviceId = session('device_id');
                if (!$deviceId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Session expired. Please refresh the page.'
                    ]);
                }
                
                $user = new \stdClass();
                $user->id = $deviceId;
                $carts = Cart::where('creator_guest_id', $deviceId)
                    ->with(['productOrder' => function($query) {
                        $query->whereHas('product')->with('product');
                    }])
                    ->get();
            } else {
                $carts = Cart::where('creator_id', $user->id)
                    ->with(['productOrder' => function($query) {
                        $query->whereHas('product')->with('product');
                    }])
                    ->get();
            }
            
            if ($carts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty'
                ]);
            }
            
            // Validate request data
            $validator = \Validator::make($request->all(), [
                'country_id' => 'required|integer',
                'city_name' => 'required|string|max:255',
                'zip_code' => 'required|string|max:20',
                'phone' => 'nullable|string|max:20'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fill in all required address fields.'
                ], 422);
            }
            
            // Check if we have physical products
            $hasPhysicalProduct = false;
            foreach ($carts as $cart) {
                if (!empty($cart->productOrder) && 
                    !empty($cart->productOrder->product) && 
                    $cart->productOrder->product->type == Product::$physical) {
                    $hasPhysicalProduct = true;
                    break;
                }
            }
            
            // if (!$hasPhysicalProduct) {
            //     return response()->json([
            //         'success' => true,
            //         'shipping_cost' => 0,
            //         'shipping_cost_formatted' => handlePrice(0),
            //         'total' => 0,
            //         'total_formatted' => handlePrice(0),
            //         'shipping_calculated' => false,
            //         'shipping_method' => 'none',
            //         'message' => 'No physical products in cart.'
            //     ]);
            // }
            
            // Calculate shipping with new address
            $addressData = [
                'country_id' => $request->input('country_id'),
                'city_name' => $request->input('city_name'),
                'zip_code' => $request->input('zip_code'),
                'province_name' => $request->input('province_name', ''),
                'address' => $request->input('address', ''),
                'house_no' => $request->input('house_no', ''),
                'phone' => $request->input('phone', '')
            ];
            
            
           
            // Try Lulu API first
            try {
                $shippingCost = $this->calculateLuluShipping($carts, $addressData);
                dd($shippingCost);
                $shippingMethod = 'lulu_api';
                $shippingCalculated = true;
            } catch (\Exception $e) {
                \Log::warning('Lulu API failed, using default shipping: ' . $e->getMessage());
                $shippingCost = $this->calculateDefaultShipping($carts);
                $shippingMethod = 'default';
                $shippingCalculated = false;
            }
            
            // Calculate the full price with shipping
            $calculate = $this->calculatePrice($carts, $user);
            $newTotal = $calculate['total'] + $shippingCost;
            
            return response()->json([
                'success' => true,
                'shipping_cost' => $shippingCost,
                'shipping_cost_formatted' => handlePrice($shippingCost),
                'total' => $newTotal,
                'total_formatted' => handlePrice($newTotal),
                'shipping_calculated' => $shippingCalculated,
                'shipping_method' => $shippingMethod,
                'subtotal' => $calculate['sub_total'],
                'tax' => $calculate['tax_price'],
                'product_delivery_fee' => $calculate['product_delivery_fee'] ?? 0,
                'message' => $shippingCalculated ? 'Shipping calculated successfully' : 'Estimated shipping calculated'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Shipping calculation error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate shipping. Please try again.'
            ], 500);
        }
    }

    public function calculatePrice($carts, $user, $discountCoupon = null, $request = null)
    {
        $financialSettings = getFinancialSettings();

        $subTotal = 0;
        $totalDiscount = 0;
        $tax = (!empty($financialSettings['tax']) and $financialSettings['tax'] > 0) ? $financialSettings['tax'] : 0;
        $taxPrice = 0;
        $commissionPrice = 0;
        $commission = 0;

        $shippingInfo = $this->calculateShippingCost($carts, $user, $request);
        $shippingCost = $shippingInfo['shipping_cost'];

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
            'shipping_cost' => round($shippingCost, 2),
            'shipping_calculated' => $shippingInfo['shipping_calculated'],
            'shipping_method' => $shippingInfo['shipping_method'],
            'tax_is_different' => $taxIsDifferent
        ];
    }

    private function calculateShippingCost($carts, $user, $request = null)
    {
        $totalShippingCost = 0;
        $hasPhysicalBooks = false;
        
        // Check if we have physical books in cart
        foreach ($carts as $cart) {
            if (!empty($cart->productOrder) && 
                !empty($cart->productOrder->product) && 
                $cart->productOrder->product->type == Product::$physical) {
                $hasPhysicalBooks = true;
                break;
            }
        }
        
        if (!$hasPhysicalBooks) {
            return [
                'shipping_cost' => 0,
                'shipping_calculated' => false,
                'shipping_method' => 'default'
            ];
        }
        
        // Check if user has address information
        $hasAddress = false;
        $addressData = [];
        
        if (!empty($user) && !is_string($user->id)) { // Regular user
            $hasAddress = !empty($user->country_id) && 
                        !empty($user->city_name) && 
                        !empty($user->zip_code);
            
            if ($hasAddress) {
                $addressData = [
                    'country_id' => $user->country_id,
                    'city_name' => $user->city_name,
                    'zip_code' => $user->zip_code,
                    'province_name' => $user->province_name ?? '',
                    'address' => $user->address ?? '',
                    'house_no' => $user->house_no ?? '',
                    'phone' => $user->phone ?? ''
                ];
            }
        } else if ($request && $request->has('country_id') && 
                $request->has('city_name') && $request->has('zip_code')) {
            // Guest user with form data
            $hasAddress = true;
            $addressData = [
                'country_id' => $request->get('country_id'),
                'city_name' => $request->get('city_name'),
                'zip_code' => $request->get('zip_code'),
                'province_name' => $request->get('province_name', ''),
                'address' => $request->get('address', ''),
                'house_no' => $request->get('house_no', ''),
                'phone' => $request->get('phone', '')
            ];
        }
        
        
        if ($hasAddress && !empty($addressData)) {
            // Calculate shipping using Lulu API
            try {
                $shippingCost = $this->calculateLuluShipping($carts, $addressData);
                return [
                    'shipping_cost' => $shippingCost,
                    'shipping_calculated' => true,
                    'shipping_method' => 'lulu_api',
                    'address_data' => $addressData
                ];
            } catch (\Exception $e) {
                \Log::error('Lulu shipping calculation failed: ' . $e->getMessage());
                // Fall back to default shipping
            }
        }
        
        // Use default shipping cost
        $defaultShipping = $this->calculateDefaultShipping($carts);
        return [
            'shipping_cost' => $defaultShipping,
            'shipping_calculated' => false,
            'shipping_method' => 'default'
        ];
    }

    private function calculateLuluShipping($carts, $addressData)
    {
        
        $accessToken = Cache::get('cj_access_token');

        if (!$accessToken) {
            $accessToken = $this->getCJAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }
        }

        // dd($accessToken);
        // dd($accessToken);

        //$response = $this->createCJOrder();
        if($accessToken)
        {
            // dd('hi');
            $response = $this->submitToCJDropshipping();
        }

        dd($response);
        
        $token = $this->getLuluAccessTokenUsingCurl();
        
        if (!$token) {
            throw new \Exception('Failed to get Lulu API token');
        }
        
        
        // Prepare line items for Lulu calculation
        $lineItems = [];
        
        foreach ($carts as $cart) {
            
            if (!empty($cart->productOrder) && 
                !empty($cart->productOrder->product) ) {
                // && $cart->productOrder->product->type == Product::$physical) {
                
                $product = $cart->productOrder->product;
                $pageCount =426; // Default page count
                
                
                // Get PDF URL from product
                //$pdfUrl = $product->getPdfUrl(); // You need to implement this method
                $pdfUrl = "https://kemetic.app/store/1/pdf/400page.pdf";
                
                if (!$pdfUrl) {
                    // Use a fallback PDF URL or skip
                    continue;
                }
                
                
                // Get actual page count
                if($pageCount == 0)
                {
                    $actualPageCount = $this->getPageCountWithPdfinfo($pdfUrl);
                }
                else
                {
                    $actualPageCount = $pageCount;
                }
                // dd($actualPageCount);
                
                if ($actualPageCount === false) {
                    $actualPageCount = $pageCount;
                }

               
                $lineItems[] = [
                    'page_count' => $actualPageCount,
                    'pod_package_id' => '0600X0900BWSTDPB060UW444MXX', // Default package
                    'quantity' => $cart->productOrder->quantity
                ];
            }
        }
        
        if (empty($lineItems)) {
            return 0;
        }
        
        // Get country code from country_id
        $country = Region::find($addressData['country_id']);
        $countryCode = $country ? $country->code : 'US'; // Default to US
        
        $requestData = [
            'line_items' => $lineItems,
            'shipping_address' => [
                'city' => $addressData['city_name'],
                'country_code' => 'US',
                'postcode' => $addressData['zip_code'],
                'state_code' => $addressData['province_name'],
                // 'street1' => ($addressData['house_no'] ?? '') . ' ' . ($addressData['address'] ?? ''),
                'street1' => '231D, munichalai Road',
                'phone_number' => $addressData['phone']
            ],
            'shipping_option' => 'MAIL' // Standard shipping
        ];

        // dd($lineItems);
    
        $response = $this->getLuluPriceUsingCurl('/print-job-cost-calculations/', 'POST', $requestData, $token);
        
        // dd('hi1');
        if (isset($response['total_cost_incl_tax'])) {
            return $response['total_cost_incl_tax'];
        } elseif (isset($response['shipping_cost'])) {
            return $response['shipping_cost'];
        }
        
        throw new \Exception('No shipping cost in Lulu response');
    }

    private function calculateDefaultShipping($carts)
    {
        $totalWeight = 0;
        $totalItems = 0;
        
        foreach ($carts as $cart) {
            if (!empty($cart->productOrder) && 
                !empty($cart->productOrder->product) && 
                $cart->productOrder->product->type == Product::$physical) {
                
                $product = $cart->productOrder->product;
                $weight = $product->weight ?? 1; // Default 1kg
                $totalWeight += $weight * $cart->productOrder->quantity;
                $totalItems += $cart->productOrder->quantity;
            }
        }
        
        // Default shipping calculation logic
        if ($totalWeight <= 1) {
            return 10.00; // $10 for up to 1kg
        } elseif ($totalWeight <= 5) {
            return 20.00; // $20 for up to 5kg
        } else {
            return 30.00 + (($totalWeight - 5) * 5); // $30 + $5 per additional kg
        }
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

            $order = $this->createOrderAndOrderItems($request,$carts, $calculate, $user, $user_as_a_guest, $discountCoupon);

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

    public function createOrderAndOrderItems(Request $request, $carts, $calculate, $user, $user_as_a_guest, $discountCoupon = null)
    {
        $data = $request->all();

        if(!$user_as_a_guest){
            $user->update([
                'mobile' => $data['phone'] ?? $user->mobile,
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
                'book_id' => (!empty($cart->book_order_id) and !empty($cart->bookOrder->book)) ? $cart->bookOrder->book->id : null,
                'book_order_id' => (!empty($cart->book_order_id)) ? $cart->book_order_id : null,
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
        } elseif (!empty($cart->book_order_id) && !empty($cart->bookOrder->book)) {
            $book = $cart->bookOrder->book;

            if (!empty($book)) {
                $bookQuantity = $cart->bookOrder->quantity;
                $price = ($book->price * $bookQuantity);
                $discount = 0; // Books might have discounts in future, you can add getDiscountPrice() method to Book model

                $priceWithoutDiscount = $price - $discount;
                
                if (method_exists($book, 'getTax')) {
                    $bookTax = $book->getTax();
                    $taxIsDifferent = ($taxIsDifferent and $tax != $bookTax);
                    $tax = $bookTax;
                }

                if ($tax > 0 and $priceWithoutDiscount > 0) {
                    $taxPrice += $priceWithoutDiscount * $tax / 100;
                }
                
                $source = 'books'; // You'll need to add 'books' to your commission settings
                $commissionPrice += $this->getCommissionPrice($source, $priceWithoutDiscount, $seller);

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

    function getPageCountWithPdfinfo($url) {
        //dd('count');
        // Fetch the PDF from URL to a temp file first (same code as Method 1)
        $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.pdf';
        $pdfContent = file_get_contents($url);
        $number = preg_match_all("/\/Page\W/",$pdfContent, $dummy);
        
        return $number;
        
    }

    private function getLuluAccessTokenUsingCurl()
    {
        
        $url = "https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token";
        //$url = "https://api.sandbox.lulu.com/auth/realms/glasstree/protocol/openid-connect/token";
        $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
        // $authorization = "9f605b15-6c3c-49e5-919b-84f7341a2283:NtqZNkcvhMg6RionEhERmjrenDA2XSwV"; // Basic xxxx

        $laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        $verifyOption = file_exists($laragonCertPath) ? $laragonCertPath : false;

        
        $curl = curl_init();
         $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $authorization
            ],
        ]);
        
        
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            // Use Laragon certificate
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            // Disable SSL verification if no certificate found (NOT RECOMMENDED for production)
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            
            \Log::warning('SSL certificate verification disabled. Certificate file not found.');
        }
        
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
          
        if ($error) {
            $errorNo = curl_errno($curl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }
        
        curl_close($curl);

        $data = json_decode($response, true);

        // dd($data);
      
        // if ($httpCode !== 200) {
        //     throw new \Exception("Failed to get access token: " . ($data['error_description'] ?? 'Unknown error'));
        // }

        // dd($authorization, $response, $httpCode, $error, $data, curl_getinfo($curl), $curl);

        return $data['access_token'] ?? null;
    }

    private function getLuluPriceUsingCurl($endpoint, $method = 'POST', $data = [], $token = null)
    {
        if (!$token) {
            $token = $this->getLuluAccessTokenUsingCurl();
        }
        
        // dd($token);
        
        // $url = env('LULU_BASE_URL', 'https://api.sandbox.lulu.com') . $endpoint;
        // $url = "https://api.sandbox.lulu.com/print-job-cost-calculations/";

        $printurl = 'https://api.lulu.com/print-jobs/';

        
        $sourcePdfUrl = "https://kemetic.app/store/1/pdf/400page.pdf";

        // try {    // Use NEW credentials after revoking the compromised ones!

        //     $pdfContent = file_get_contents($sourcePdfUrl);
            
        //     if ($pdfContent === false) {
        //         throw new Exception("Failed to download PDF from URL: $sourcePdfUrl");
        //     }
            
        //     $s3Client = new \Aws\S3\S3Client([
        //         'version' => 'latest',
        //         'region'  => env('AWS_DEFAULT_REGION', 'us-east-1'),
        //         'credentials' => [
        //             'key'    => env('AWS_ACCESS_KEY_ID', 'YOUR_NEW_KEY_HERE'),
        //             'secret' => env('AWS_SECRET_ACCESS_KEY', 'YOUR_NEW_SECRET_HERE'),
        //         ]
        //     ]);

        //     $bucket = env('AWS_BUCKET', 'lulu-pdfs-01');
        //     $fileName = 'lulu-uploads/' . uniqid() . '_' . time() . '.pdf';
            
        //     // Upload to S3 with public read access
        //     $result = $s3Client->putObject([
        //         'Bucket' => $bucket,
        //         'Key'    => $fileName,
        //         'Body' => $pdfContent,
        //         'ContentType' => 'application/pdf',
        //         'ACL'    => 'public-read', // This makes it publicly accessible
        //         'Metadata' => [
        //             'Uploaded-By' => 'Lulu-API',
        //             'Expires' => gmdate('D, d M Y H:i:s T', time() + 3600) // 1 hour expiry
        //         ]
        //     ]);

        //     $publicUrl = $result['ObjectURL'];
        //     Log::info("PDF uploaded to S3. Public URL: " . $publicUrl);

        // } catch (\Exception $e) {
        //     Log::error("S3 Upload failed: " . $e->getMessage());
        // } 

        $title = "Test Print Job via Curl";
        $quantity = 1;

        // $pdfurl = "https://studiocaribbean.com/400page.pdf";
        // $pdfpathurl = "https://kemetic.app/store/1/Where-the-Crawdads-Sing.pdf";
        // // $pdfpathurl = "https://kemetic.app/store/1/pdf/traffic_pub_gen19.pdf";

        // // dd('hi');
        // $result = $this->pdfResizer->resizeForLulu($pdfpathurl, false);

        // $cover    = $this->pdfResizer->generateCoverFromPdf($pdfpathurl, $result['page_count']);
        // $coverurl = $cover['local_path'];
        // dd($coverurl);

        // Simulate your API call structure
        // dd($pdfpathurl);
        // $pdfurl = $result['lulu_pdf_url'];
        $pdfurl = "https://kemetic.app/store/lulu/interior/interior_1768311771.pdf";
        $coverurl = "https://kemetic.app/store/lulu/cover/cover_1768311014.pdf";

        $data = [
            "contact_email" => "info@kemetic.com",
            "external_id" => "Kemetic APP",
            "line_items" => [
                [
                    "external_id" => "item-reference-1",
                    "printable_normalization" =>[
                        "cover" => [
                            "source_url" => $coverurl,
                        ],
                        "interior" => [
                            "source_url" => $pdfurl,
                            "page_count" => 327 // You need to add the correct page count
                        ],
                        "pod_package_id" => "0600X0900BWSTDPB060UW444MXX"
                    ],
                    "title" => "My Book",
                    "quantity" => 1, 
                ]
            ],
            "production_delay" => 120,
            "shipping_address" => [
                "city" => "Washington",
                "country_code" => "US",
                "name" => "Kemetic User",
                "phone_number" => "+1 206 555 0100",
                "state_code" => "DC",
                "postcode" => "20540",
                "street1" => "101 Independence Ave SE"

                // "city" => "L\u00fcbeck",
                // "country_code" => "GB",
                // "name" => "Kemetic User",
                // "phone_number" => "844-212-0689",
                // "state_code" => "PO1 3AX",
                // "postcode" => "",
                // "street1" => "Holstenstr. 48"
            ],
            "shipping_level" => "MAIL"
        ];

        // // dd($data);
        
        $printcurl = curl_init();
        curl_setopt_array($printcurl, [
            CURLOPT_URL => $printurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ]);

        // Certificate verification handling
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        if (!empty($data)) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($printcurl, $options);

        $response = curl_exec($printcurl);
        $httpCode = curl_getinfo($printcurl, CURLINFO_HTTP_CODE);
        $error = curl_error($printcurl);
        if ($error) {
            $errorNo = curl_errno($printcurl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }

        // dd($data);
        dd($response);


        $curl = curl_init();
        
        $url = "https://api.lulu.com/print-job-cost-calculations/";
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '. $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ]);

        // Certificate verification handling
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        if (!empty($data)) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);
        
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        if ($error) {
            $errorNo = curl_errno($curl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }

        dd($response);
        
        // Enhanced error logging
        if ($error) {
            $errorNo = curl_errno($curl);
            $errorInfo = [
                'error_no' => $errorNo,
                'error_msg' => $error,
                'endpoint' => $endpoint,
                'cert_path' => $this->laragonCertPath,
                'cert_exists' => file_exists($this->laragonCertPath) ? 'Yes' : 'No',
                'url' => $url,
            ];
            \Log::error('Lulu API cURL Error', $errorInfo);
        }
        
        curl_close($curl);

        $responseData = json_decode($response, true);
       
        return $responseData;
    }

    private function scheduleS3Cleanup($s3Client, $bucket, $fileName)
    {
        // You can use Laravel Jobs or simple delayed execution
        // Option 1: Using Laravel Job (recommended)
        // dispatch(new DeleteS3File($bucket, $fileName))->delay(now()->addHours(24));
        
        // Option 2: Set S3 lifecycle policy on the bucket instead
        
        // For now, just log it
        Log::info("S3 file scheduled for cleanup: {$fileName}");
    }

    private function uploadToS3($filePath)
    {
        // You need AWS SDK: composer require aws/aws-sdk-php
        $s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => [
                'key'    => env("AWS_ACCESS_KEY_ID"),
                'secret' => env("AWS_SECRET_ACCESS_KEY"),
            ]
        ]);
        
        $bucket = 'cart_book';
        $key = 'pdfs/' . basename($filePath);
        
        $result = $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => $key,
            'SourceFile' => $filePath,
            'ContentType' => 'application/pdf',
            'ACL'    => 'public-read'
        ]);
        
        return $result['ObjectURL']; // Public URL
    }

    // Helper method for OPTION B: Create public endpoint on your server
    private function createPublicEndpoint($filePath)
    {
        // Create a unique filename
        $uniqueName = 'pdf_' . uniqid() . '.pdf';
        $publicDir = public_path('temp_pdfs');
        
        if (!file_exists($publicDir)) {
            mkdir($publicDir, 0755, true);
        }
        
        $publicPath = $publicDir . '/' . $uniqueName;
        copy($filePath, $publicPath);
        
        // Return full public URL
        return url('temp_pdfs/' . $uniqueName);
        
        // IMPORTANT: Set up a cleanup job to delete old files
        // You can use Laravel Scheduler or a cron job
    }

    // Helper method for OPTION D: ngrok for testing
    private function exposeViaNgrok($filePath)
    {
        // For testing only! Install ngrok from https://ngrok.com/
        // This exposes your local file via a public URL
        
        // 1. Start ngrok: ngrok http 8000
        // 2. Create a simple PHP server to serve the file
        // 3. Return the ngrok URL
        
        return 'https://YOUR_NGROK_SUBDOMAIN.ngrok.io/temp.pdf';
    }

    private function luluprintjob($path)
    {
        $url = "https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token";
        $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
        // $authorization = "9f605b15-6c3c-49e5-919b-84f7341a2283:NtqZNkcvhMg6RionEhERmjrenDA2XSwV"; // Basic xxxx

        $laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        $verifyOption = file_exists($laragonCertPath) ? $laragonCertPath : false;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $authorization
            ],
        ]);

        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            // Use Laragon certificate
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            // Disable SSL verification if no certificate found (NOT RECOMMENDED for production)
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            
            \Log::warning('SSL certificate verification disabled. Certificate file not found.');
        }
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        if ($error) {
            $errorNo = curl_errno($curl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }
        
        curl_close($curl);

        $data = json_decode($response, true);
        $token = $data['access_token'] ?? null;

        $printcurl = curl_init();

        //$printurl = env('LULU_BASE_URL', 'https://api.sandbox.lulu.com') . '/print-jobs/';
        $printurl = 'https://api.lulu.com/print-jobs/';

        $pdfUrl = "https://kemetic.app/store/1/pdf/traffic_pub_gen19.pdf";
        $title = "Test Print Job via Curl";
        $quantity = 1;

        $data = '{
            "contact_email": "test@test.com",
            "external_id": "demo-time",
            "line_items": [
                {
                "external_id": "item-reference-1",
                "printable_normalization": {
                    "cover": {
                    "source_url": $pdfurl,
                    },
                    "interior": {
                    "source_url": $pdfurl,
                    },
                    "pod_package_id": "0600X0900BWSTDPB060UW444MXX"
                },
                "quantity": 1,
                "title": "My Book"
                }
            ],
            "production_delay": 120,
            "shipping_address": {
                "city": "Lübeck",
                "country_code": "GB",
                "name": "Hans Dampf",
                "phone_number": "844-212-0689",
                "postcode": "PO1 3AX",
                "state_code": "",
                "street1": "Holstenstr. 48"
            },
            "shipping_level": "MAIL"
        }';

        curl_setopt_array($printcurl, [
            CURLOPT_URL => $printurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ]);

        // Certificate verification handling
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($data)) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        if ($error) {
            $errorNo = curl_errno($curl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }

        dd($response);
        
        // Enhanced error logging
        if ($error) {
            $errorNo = curl_errno($curl);
            $errorInfo = [
                'error_no' => $errorNo,
                'error_msg' => $error,
                'endpoint' => $endpoint,
                'cert_path' => $this->laragonCertPath,
                'cert_exists' => file_exists($this->laragonCertPath) ? 'Yes' : 'No',
                'url' => $url,
            ];
            \Log::error('Lulu API cURL Error', $errorInfo);
        }
        
        curl_close($curl);

        //dd($curl,$response, $httpCode, $error);

        // if ($error) {
        //     throw new \Exception("API request failed: " . $error);
        // }

        $responseData = json_decode($response, true);
    }

    private function getCJAccessToken()
    {
        try {
            $apiKey = env('CJ_API_KEY', 'CJ4955433@api@70aef43553ad4cc49603b4ab808e7494');
            
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://developers.cjdropshipping.com/api2.0/v1/authentication/getAccessToken',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(['apiKey' => $apiKey]),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json'
                ],
            ]);
            
            // SSL certificate handling
            if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
                curl_setopt($curl, CURLOPT_CAINFO, $this->laragonCertPath);
                curl_setopt($curl, CURLOPT_CAPATH, dirname($this->laragonCertPath));
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            } else {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
            // dd($response);
            if ($error) {
                \Log::error('CJ Dropshipping Token Error: ' . $error);
                throw new \Exception('Failed to get CJ Access Token: ' . $error);
            }
            
            $data = json_decode($response, true);

            $token = $data['data']['accessToken'];

            session(['cj_access_token' => $token]);

            Cache::put('cj_access_token', $data['data']['accessToken'], now()->addHours(23));

            // dd($token);
            
            return $token;
            
        } catch (\Exception $e) {
            \Log::error('CJ Dropshipping Authentication Failed: ' . $e->getMessage());
            return null;
        }
    }

    private function createCJOrder($orderData)
    {
        try {
            // Get cached token or fetch new one
            // $accessToken = session('cj_access_token');
            // dd('hi1');
            // // if()
            $accessToken = Cache::get('cj_access_token');

            if (!$accessToken) {
                $accessToken = $this->getCJAccessToken();
                if (!$accessToken) {
                    throw new \Exception('Failed to get access token');
                }
            }
            
            
            $platformToken = env('CJ_PLATFORM_TOKEN', ''); // Add this to your .env

            $jsonData = json_encode($orderData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON encode error: ' . json_last_error_msg());
            }

            // dd($jsonData);
            
            $curl = curl_init();
            
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://developers.cjdropshipping.com/api2.0/v1/shopping/order/createOrderV3',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60, // Increased timeout for order creation
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => [
                    'CJ-Access-Token: ' . $accessToken,
                    'platformToken: ' . $platformToken,
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Content-Length: ' . strlen($jsonData)
                ],
            ]);

            
            
            // SSL certificate handling
            if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
                curl_setopt($curl, CURLOPT_CAINFO, $this->laragonCertPath);
                curl_setopt($curl, CURLOPT_CAPATH, dirname($this->laragonCertPath));
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            } else {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
        
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);

            
            // dd($response);
            if ($error) {
                \Log::error('CJ Dropshipping Order Error: ' . $error);
                throw new \Exception('Failed to create CJ order: ' . $error);
            }
            
            $data = json_decode($response, true);
            
            // If token expired, try once more with fresh token
            if (($httpCode === 401 || (isset($data['code']) && $data['code'] === 1002)) && 
                Cache::get('cj_access_token_retry_attempt', 0) < 1) {
                
                Cache::forget('cj_access_token');
                Cache::increment('cj_access_token_retry_attempt');
                
                return $this->createCJOrder($orderData);
            }
            
            // Reset retry counter
            Cache::forget('cj_access_token_retry_attempt');
            
            if ($httpCode !== 200 || !isset($data['code']) || $data['code'] !== 200) {
                \Log::error('CJ Dropshipping Order API Error: ' . json_encode($data));
                throw new \Exception('Failed to create order: ' . ($data['msg'] ?? 'Unknown error'));
            }
            
            return $data['data'];
            
        } catch (\Exception $e) {
            \Log::error('CJ Dropshipping Order Creation Failed: ' . $e->getMessage());
            return null;
        }
    }

    // public function submitToCJDropshipping($order, $user, $carts)
    public function submitToCJDropshipping()
    {
        try {
            // Check if order has CJ products
            $cjProducts = [];
            $orderItems = [];
            
            // foreach ($carts as $cart) {
            //     if (!empty($cart->productOrder) && 
            //         !empty($cart->productOrder->product)) {
                    
            //         $product = $cart->productOrder->product;
                    
            //         if (!empty($product->cj_vid)) {
            //             $cjProducts[] = [
            //                 'vid' => $product->cj_vid,
            //                 'quantity' => $cart->productOrder->quantity,
            //                 'product' => $product
            //             ];
                        
            //             $orderItems[] = [
            //                 'vid' => $product->cj_vid,
            //                 'quantity' => $cart->productOrder->quantity,
            //                 'storeLineItemId' => 'item_' . $cart->id . '_' . time()
            //             ];
            //         }
            //     }
            // }
            
            // if (empty($cjProducts)) {
            //     return ['success' => true, 'message' => 'No CJ products in order'];
            // }
            
            // // Get country information
            // $country = Region::find($user->country_id);
            // $countryCode = $country ? $country->code : 'US';

            $sampleOrderData = [
                'orderNumber' => 'TEST_' . time(),
                'shippingZip' => '10001',
                'shippingCountry' => 'United States',
                'shippingCountryCode' => 'US',
                'shippingProvince' => 'DC',
                'shippingCity' => 'Washington',
                'shippingCounty' => 'United States',
                'shippingPhone' => '+1 206 555 0100',
                'shippingCustomerName' => 'Test Customer',
                'shippingAddress' => '101 Independence Ave SE',
                'shippingAddress2' => '20540',
                'taxId' => '',
                'remark' => 'Test order',
                'email' => 'test@example.com',
                'consigneeID' => '',
                'payType' => '',
                'shopAmount' => '29.99',
                'logisticName' => 'PostNL',
                'fromCountryCode' => 'CN',
                'houseNumber' => '123',
                'iossType' => '',
                // 'platform' => 'shopify',
                'iossNumber' => '',
                // 'storageId' => '',
                'products' => [
                    [
                        'vid' => '92511400-C758-4474-93CA-66D442F5F787',
                        'quantity' => 1,
                        'storeLineItemId' => 'test_item_' . time()
                    ]
                ]
            ];
            
            // Submit to CJ
            // $result = $this->getTrackingInfo('SD2601030613480657800');
            $result = $this->createCJOrder($sampleOrderData);
            
            // dd($result);
            if ($result) {
                // Update order with CJ reference
                $orderId = $result['orderId'] ?? null;
                if (!$orderId) {
                    return [
                        'success' => false,
                        'message' => 'Failed to Order Id: ' . ($orderId['message'] ?? 'Unknown error')
                    ];
                }
                else
                {
                    $addToCartResult = $this->addOrderToCart([$orderId]);
                }
                
                if (!$addToCartResult['success'])
                {
                    return [
                        'success' => false,
                        'message' => 'Failed to add order to cart: ' . ($addToCartResult['message'] ?? 'Unknown error')
                    ];
                }
                else
                {
                    $addSuccessOrders = $addToCartResult['data']['addSuccessOrders'] ?? null;
                    $confirmCartResult = $this->confirmCart([$orderId]);
                }
                dd($addToCartResult);

                if (!$confirmCartResult['success'])
                {
                    return [
                        'success' => false,
                        'message' => 'Failed to confirm cart: ' . ($confirmCartResult['message'] ?? 'Unknown error')
                    ];
                }
                else
                {
                    $shipmentsId = $confirmCartResult['data']['shipmentsId'] ?? null;
                    $saveorder = $this->saveGenerateParentOrder($shipmentsId);
                }

                if(!$saveorder['success'])
                {
                    return [
                        'success' => false,
                        'message' => 'Failed to save order: ' . ($saveorder['message'] ?? 'Unknown error')
                    ];
                }
                else
                {
                    $payId = $saveorder['data']['payId'] ?? null;
                    $payResult = $this->payOrder($shipmentsId, $payId);
                }

                if(!$payResult['success'])
                {
                    return [
                        'success' => false,
                        'message' => 'Failed to pay order: ' . ($payResult['message'] ?? 'Unknown error')
                    ];
                }
                else
                {
                    $finalOrderDetails = $this->getOrderDetails($orderId);
                }

                if(!$finalOrderDetails['success'])
                {
                    return [
                        'success' => false,
                        'message' => 'Failed to get final order details: ' . ($finalOrderDetails['message'] ?? 'Unknown error')
                    ];
                }
                else
                {
                    $trackingNumber = $finalOrderDetails['data']['trackNumber'];
            
                    // Step 7: Get tracking info
                    $trackingInfo = $this->getTrackingInfo($trackingNumber);
                    
                }

                dd($trackingNumber);
                dd('hi2');
                
                \Log::info('CJ Dropshipping order process completed successfully', [
                    'order_id' => $order->id,
                    'cj_order_id' => $result['orderId'] ?? 'N/A'
                ]);

                

                dd('hi');

                $order->update([
                    'cj_order_id' => $orderId ?? null,
                    // 'cj_order_number' => $result['orderNumber'] ?? null,
                    'cj_tracking_number' => $trackingNumber ?? null,
                    'cj_status' => 'submitted'
                ]);

                // $result = $this->getTrackingInfo($result['orderNumber']);
                
                // Log success
                \Log::info('CJ Dropshipping order submitted successfully', [
                    'order_id' => $order->id,
                    'cj_order_id' => $result['orderId'] ?? 'N/A'
                ]);
                
                return [
                    'success' => true,
                    'cj_order_id' => $result['orderId'] ?? null,
                    'tracking_number' => $result['trackingNumber'] ?? null,
                    'message' => 'Order submitted to CJ Dropshipping successfully'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Failed to submit order to CJ Dropshipping'
            ];
            
        } catch (\Exception $e) {
            \Log::error('CJ Dropshipping Submission Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to submit to CJ Dropshipping: ' . $e->getMessage()
            ];
        }
    }

    private function addOrderToCart($orderIds)
    {
        $accessToken = Cache::get('cj_access_token');

        if (!$accessToken) {
            $accessToken = $this->getCJAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }
        }
        
        $data = [
            'cjOrderIdList' => $orderIds
        ];
        
        $jsonData = json_encode($data);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://developers.cjdropshipping.com/api2.0/v1/shopping/order/addCart',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => [
                'CJ-Access-Token: ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
        ]);
        
        // SSL certificate handling
        $laragonCertPath = storage_path('certs/cacert.pem');
        if ($laragonCertPath && file_exists($laragonCertPath)) {
            curl_setopt($curl, CURLOPT_CAINFO, $laragonCertPath);
            curl_setopt($curl, CURLOPT_CAPATH, dirname($laragonCertPath));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $error
            ];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200 || !isset($result['success']) || !$result['success']) {
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to add order to cart',
                'http_code' => $httpCode
            ];
        }
        
        return [
            'success' => true,
            'data' => $result ?? []
        ];
    }

    private function confirmCart($orderIds)
    {
        $accessToken = Cache::get('cj_access_token');

        if (!$accessToken) {
            $accessToken = $this->getCJAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }
        }
        
        $data = [
            'cjOrderIdList' => $orderIds
        ];
        
        $jsonData = json_encode($data);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://developers.cjdropshipping.com/api2.0/v1/shopping/order/addCartConfirm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => [
                'CJ-Access-Token: ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
        ]);
        
        // SSL certificate handling
        $laragonCertPath = storage_path('certs/cacert.pem');
        if ($laragonCertPath && file_exists($laragonCertPath)) {
            curl_setopt($curl, CURLOPT_CAINFO, $laragonCertPath);
            curl_setopt($curl, CURLOPT_CAPATH, dirname($laragonCertPath));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $error
            ];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200 || !isset($result['success']) || !$result['success']) {
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to confirm cart',
                'http_code' => $httpCode
            ];
        }
        
        return [
            'success' => true,
            'data' => $result ?? []
        ];
    }

    private function payOrder($shipmentOrderId, $payId)
    {
        $accessToken = Cache::get('cj_access_token');

        if (!$accessToken) {
            $accessToken = $this->getCJAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }
        }
        
        $data = [
            'shipmentOrderId' => $shipmentOrderId,
            'payId' => $payId
        ];
        
        $jsonData = json_encode($data);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://developers.cjdropshipping.com/api2.0/v1/shopping/pay/payBalanceV2',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => [
                'CJ-Access-Token: ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
        ]);
        
        // SSL certificate handling
        $laragonCertPath = storage_path('certs/cacert.pem');
        if ($laragonCertPath && file_exists($laragonCertPath)) {
            curl_setopt($curl, CURLOPT_CAINFO, $laragonCertPath);
            curl_setopt($curl, CURLOPT_CAPATH, dirname($laragonCertPath));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $error
            ];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200 || !isset($result['success']) || !$result['success']) {
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Payment failed',
                'http_code' => $httpCode
            ];
        }
        
        return [
            'success' => true,
            'data' => $result ?? []
        ];
    }

    private function saveGenerateParentOrder($shipmentOrderId)
    {
        $accessToken = Cache::get('cj_access_token');

        if (!$accessToken) {
            $accessToken = $this->getCJAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }
        }
        
        $data = [
            'shipmentOrderId' => $shipmentOrderId
        ];
        
        $jsonData = json_encode($data);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://developers.cjdropshipping.com/api2.0/v1/shopping/order/saveGenerateParentOrder',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => [
                'CJ-Access-Token: ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
        ]);
        
        // SSL certificate handling
        $laragonCertPath = storage_path('certs/cacert.pem');
        if ($laragonCertPath && file_exists($laragonCertPath)) {
            curl_setopt($curl, CURLOPT_CAINFO, $laragonCertPath);
            curl_setopt($curl, CURLOPT_CAPATH, dirname($laragonCertPath));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $error
            ];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200 || !isset($result['success']) || !$result['success']) {
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to save generate parent order',
                'http_code' => $httpCode,
                'response' => $result
            ];
        }
        
        return [
            'success' => true,
            'data' => $result ?? []
        ];
    }

    private function getOrderDetails($orderId)
    {
        $accessToken = Cache::get('cj_access_token');

        if (!$accessToken) {
            $accessToken = $this->getCJAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }
        }
        
        $url = 'https://developers.cjdropshipping.com/api2.0/v1/shopping/order/getOrderDetail?orderId=' . urlencode($orderId);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'CJ-Access-Token: ' . $accessToken,
                'Accept: application/json'
            ],
        ]);
        
        // SSL certificate handling
        $laragonCertPath = storage_path('certs/cacert.pem');
        if ($laragonCertPath && file_exists($laragonCertPath)) {
            curl_setopt($curl, CURLOPT_CAINFO, $laragonCertPath);
            curl_setopt($curl, CURLOPT_CAPATH, dirname($laragonCertPath));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $error
            ];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200 || !isset($result['success']) || !$result['success']) {
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to get order details',
                'http_code' => $httpCode
            ];
        }
        
        return [
            'success' => true,
            'data' => $result ?? []
        ];
    }

    private function getTrackingInfo($trackingNumber)
    {
        $accessToken = Cache::get('cj_access_token');

        if (!$accessToken) {
            $accessToken = $this->getCJAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }
        }
        
        $url = 'https://developers.cjdropshipping.com/api2.0/v1/logistic/trackInfo?trackNumber=' . urlencode($trackingNumber);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'CJ-Access-Token: ' . $accessToken,
                'Accept: application/json'
            ],
        ]);
        
        // SSL certificate handling
        $laragonCertPath = storage_path('certs/cacert.pem');
        if ($laragonCertPath && file_exists($laragonCertPath)) {
            curl_setopt($curl, CURLOPT_CAINFO, $laragonCertPath);
            curl_setopt($curl, CURLOPT_CAPATH, dirname($laragonCertPath));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $error
            ];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200 || !isset($result['success']) || !$result['success']) {
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to get tracking info',
                'http_code' => $httpCode
            ];
        }
        
        return [
            'success' => true,
            'data' => $result ?? []
        ];
    }

    public function getTrackingInfos($trackNumber)
    {
        try {
            // Validate tracking number
            if (empty($trackNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tracking number is required'
                ], 400);
            }

            // Get access token from cache or fetch new one
            $accessToken = Cache::get('cj_access_token');
            if (!$accessToken) {
                $accessToken = $this->getCJAccessToken();
                if (!$accessToken) {
                    throw new \Exception('Failed to get CJ access token');
                }
            }

            // Prepare API URL
            $url = "https://developers.cjdropshipping.com/api2.0/v1/logistic/getTrackInfo/trackNumber=" . urlencode($trackNumber);

            // Initialize cURL
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'CJ-Access-Token: ' . $accessToken,
                    'Accept: application/json',
                ],
            ]);

            // SSL certificate handling
            if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
                curl_setopt($curl, CURLOPT_CAINFO, $this->laragonCertPath);
                curl_setopt($curl, CURLOPT_CAPATH, dirname($this->laragonCertPath));
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            } else {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }

            // Execute request
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);

            dd($response);

            // Handle cURL errors
            if ($error) {
                Log::error('CJ Tracking API cURL Error: ' . $error);
                throw new \Exception('Failed to fetch tracking information: ' . $error);
            }

            // Parse response
            $data = json_decode($response, true);

            // Handle token expiration (retry once with fresh token)
            if (($httpCode === 401 || (isset($data['code']) && $data['code'] === 1002)) && 
                Cache::get('cj_tracking_retry_attempt', 0) < 1) {
                
                Cache::forget('cj_access_token');
                Cache::increment('cj_tracking_retry_attempt');
                
                return $this->getTrackingInfo($trackNumber);
            }

            // Reset retry counter
            Cache::forget('cj_tracking_retry_attempt');

            // Check for API errors
            if ($httpCode !== 200) {
                Log::error('CJ Tracking API HTTP Error: ' . $httpCode . ' - ' . $response);
                throw new \Exception('API returned HTTP ' . $httpCode);
            }

            // Check response code
            if (!isset($data['code']) || $data['code'] !== 200) {
                $errorMsg = $data['message'] ?? 'Unknown API error';
                Log::error('CJ Tracking API Error: ' . $errorMsg);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get tracking info: ' . $errorMsg,
                    'api_code' => $data['code'] ?? null
                ], 400);
            }

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Tracking information retrieved successfully',
                'data' => $data['data'] ?? [],
                'tracking_number' => $trackNumber
            ]);

        } catch (\Exception $e) {
            Log::error('CJ Tracking Failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get tracking information: ' . $e->getMessage()
            ], 500);
        }
    }
}
