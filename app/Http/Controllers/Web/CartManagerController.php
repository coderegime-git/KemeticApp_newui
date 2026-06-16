<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ReserveMeeting;
use App\Models\Ticket;
use App\Models\Webinar;
use App\Services\CJDropshippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartManagerController extends Controller
{
    public $cookieKey = 'carts';

    // ─────────────────────────────────────────────────────────────────────
    // GET CARTS
    // ─────────────────────────────────────────────────────────────────────

    public function getCarts()
    {
        $carts = collect();

        if (auth()->check()) {
            $user = auth()->user();

            $user->carts()
                ->whereNotNull('product_order_id')
                ->where(function ($query) {
                    $query->whereDoesntHave('productOrder');
                    $query->orWhereDoesntHave('productOrder.product');
                })
                ->delete();

            $carts = $user->carts()
                ->with([
                    'webinar',
                    'bundle',
                    'installmentPayment',
                    'productOrder' => function ($query) {
                        $query->with(['product']);
                    }
                ])
                ->get();
        } else {
            // Cookie fallback (guest)
            $cookieCarts = Cookie::get($this->cookieKey);

            if (!empty($cookieCarts)) {
                $cookieCarts = json_decode($cookieCarts, true);

                if (!empty($cookieCarts) && count($cookieCarts)) {
                    $carts = collect();

                    foreach ($cookieCarts as $cookieCart) {
                        if (!empty($cookieCart['item_name']) && $cookieCart['item_name'] === 'webinar_id') {
                            $webinar = Webinar::where('id', $cookieCart['item_id'])
                                ->where('private', false)
                                ->where('status', 'active')
                                ->first();

                            if (!empty($webinar)) {
                                $ticket = null;
                                if (!empty($cookieCart['ticket_id'])) {
                                    $ticket = Ticket::where('id', $cookieCart['ticket_id'])->first();
                                }
                                $item = new Cart();
                                $item->webinar_id = $webinar->id;
                                $item->webinar    = $webinar;
                                $item->ticket     = $ticket;
                                $item->ticket_id  = !empty($ticket) ? $ticket->id : null;
                                $carts->add($item);
                            }
                        } elseif (!empty($cookieCart['item_name']) && $cookieCart['item_name'] === 'bundle_id') {
                            $bundle = Bundle::where('id', $cookieCart['item_id'])
                                ->where('status', 'active')
                                ->first();

                            if (!empty($bundle)) {
                                $ticket = null;
                                if (!empty($cookieCart['ticket_id'])) {
                                    $ticket = Ticket::where('id', $cookieCart['ticket_id'])->first();
                                }
                                $item = new Cart();
                                $item->bundle_id = $bundle->id;
                                $item->bundle    = $bundle;
                                $item->ticket    = $ticket;
                                $item->ticket_id = !empty($ticket) ? $ticket->id : null;
                                $carts->add($item);
                            }
                        } elseif (!empty($cookieCart['item_name']) && $cookieCart['item_name'] === 'product_id') {
                            $product = Product::where('id', $cookieCart['item_id'])->first();

                            if (!empty($product)) {
                                $item = new Cart();
                                $item->product_order_id = $product->id;
                                $item->productOrder = (object) [
                                    'quantity' => $cookieCart['quantity'] ?? 1,
                                    'product'  => $product,
                                ];
                                $carts->add($item);
                            }
                        }
                    }
                }
            }
        }

        return $carts;
    }

    public function storeCookieCartsToDB()
    {
        try {
            if (auth()->check()) {
                $user  = auth()->user();
                $carts = Cookie::get($this->cookieKey);

                if (!empty($carts)) {
                    $carts = json_decode($carts, true);

                    if (!empty($carts)) {
                        foreach ($carts as $cart) {
                            if (!empty($cart['item_name']) && !empty($cart['item_id'])) {
                                if ($cart['item_name'] === 'webinar_id') {
                                    $this->storeUserWebinarCart($user, $cart, false);
                                } elseif ($cart['item_name'] === 'product_id') {
                                    $this->storeUserProductCart($user, $cart, false);
                                } elseif ($cart['item_name'] === 'bundle_id') {
                                    $this->storeUserBundleCart($user, $cart, false);
                                }
                            }
                        }
                    }

                    Cookie::queue($this->cookieKey, null, 0);
                }
            }
        } catch (\Exception $e) {
            Log::error('storeCookieCartsToDB: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // STORE HELPERS
    // ─────────────────────────────────────────────────────────────────────

    public function storeUserWebinarCart($user, $data, $user_as_a_guest)
    {
        $webinar = Webinar::where('id', $data['item_id'])
            ->where('private', false)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar) && !empty($user)) {
            $check = checkCourseForSale($webinar, $user);
            if ($check !== 'ok') return $check;

            $activeSpecialOffer = $webinar->activeSpecialOffer();
            $ticket_id          = $data['ticket_id'] ?? null;

            if ($user_as_a_guest) {
                Cart::updateOrCreate(
                    ['creator_id' => 0, 'creator_guest_id' => $user->id, 'webinar_id' => $data['item_id']],
                    ['ticket_id' => $ticket_id, 'special_offer_id' => $activeSpecialOffer->id ?? null, 'created_at' => time()]
                );
            } else {
                Cart::updateOrCreate(
                    ['creator_id' => $user->id, 'webinar_id' => $data['item_id']],
                    ['ticket_id' => $ticket_id, 'special_offer_id' => $activeSpecialOffer->id ?? null, 'created_at' => time()]
                );
            }

            return 'ok';
        }

        return back()->with(['toast' => [
            'title' => trans('public.request_failed'),
            'msg'   => trans('cart.course_not_found'),
            'status'=> 'error',
        ]]);
    }

    public function storeUserBundleCart($user, $data, $user_as_a_guest)
    {
        $bundle = Bundle::where('id', $data['item_id'])->where('status', 'active')->first();

        if (!empty($bundle) && !empty($user)) {
            $check = checkCourseForSale($bundle, $user);
            if ($check !== 'ok') return $check;

            $activeSpecialOffer = $bundle->activeSpecialOffer();
            $ticket_id          = $data['ticket_id'] ?? null;

            if ($user_as_a_guest) {
                Cart::updateOrCreate(
                    ['creator_id' => 0, 'creator_guest_id' => $user->id, 'bundle_id' => $data['item_id']],
                    ['ticket_id' => $ticket_id, 'special_offer_id' => $activeSpecialOffer->id ?? null, 'created_at' => time()]
                );
            } else {
                Cart::updateOrCreate(
                    ['creator_id' => $user->id, 'bundle_id' => $data['item_id']],
                    ['ticket_id' => $ticket_id, 'special_offer_id' => $activeSpecialOffer->id ?? null, 'created_at' => time()]
                );
            }

            return 'ok';
        }

        return back()->with(['toast' => [
            'title' => trans('public.request_failed'),
            'msg'   => trans('cart.course_not_found'),
            'status'=> 'error',
        ]]);
    }

    public function storeUserProductCart($user, $data, $user_as_a_guest)
    {
        $product = Product::where('id', $data['item_id'])->where('status', 'active')->first();

        if (!empty($product) && !empty($user)) {
            $check = checkProductForSale($product, $user);
            if ($check !== 'ok') return $check;

            $activeDiscount = $product->getActiveDiscount();

            $specifications = !empty($data['specifications']) ? $data['specifications'] : [];
            if (!is_array($specifications)) {
                $specifications = json_decode($specifications, true) ?? [];
            }

            if (!empty($data['cj_variant_id'])) {
                $specifications['cj_vid'] = $data['cj_variant_id'];
            }

            $matchConditions = [
                'product_id' => $product->id,
                'seller_id'  => $product->creator_id,
                'buyer_id'   => $user->id,
                'sale_id'    => null,
                'status'     => 'pending',
            ];

            // ✅ If a variant is present, scope the match to that specific variant
            if (!empty($data['cj_variant_id'])) {
                $matchConditions['specifications->cj_vid'] = $data['cj_variant_id'];
            }

            $productOrder = ProductOrder::updateOrCreate(
                $matchConditions,
                [
                    'specifications' => !empty($specifications) ? json_encode($specifications) : null,
                    'quantity'       => $data['quantity'] ?? 1,
                    'discount_id'    => $activeDiscount->id ?? null,
                    'created_at'     => time(),
                ]
            );

            if ($user_as_a_guest) {
                Cart::updateOrCreate(
                    ['creator_id' => 0, 'creator_guest_id' => $user->id, 'product_order_id' => $productOrder->id],
                    ['product_discount_id' => $activeDiscount->id ?? null, 'created_at' => time()]
                );
            } else {
                Cart::updateOrCreate(
                    ['creator_id' => $user->id, 'product_order_id' => $productOrder->id],
                    ['product_discount_id' => $activeDiscount->id ?? null, 'created_at' => time()]
                );
            }

            return 'ok';
        }

        return back()->with(['toast' => [
            'title' => trans('public.request_failed'),
            'msg'   => trans('cart.course_not_found'),
            'status'=> 'error',
        ]]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // CJ PRODUCT ADD TO CART  (NEW)
    // Called when "Add to Cart" is clicked on a CJ product page
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Add a CJ Dropshipping product to cart.
     *
     * The CJ product is NOT in your local DB — we store it as a
     * special ProductOrder with type='cj_dropship' and the CJ pid/vid
     * stored in the specifications JSON.
     *
     * POST /cart/store  with  item_name=cj_product_id
     */
    public function storeUserCJProductCart($user, $data, $user_as_a_guest)
    {
        $cjPid      = $data['item_id']        ?? null;   // CJ product pid
        $cjVid      = $data['cj_vid']         ?? null;   // CJ variant vid
        $cjSku      = $data['cj_sku']         ?? null;
        $cjName     = $data['cj_name']        ?? 'CJ Product';
        $cjPrice    = (float) ($data['cj_price']  ?? 0);
        $cjImage    = $data['cj_image']       ?? '';
        $cjLogistic = $data['cj_logistic']    ?? 'PostNL';
        $quantity   = (int) ($data['quantity'] ?? 1);

        if (empty($cjPid) || empty($user)) {
            return back()->with(['toast' => [
                'title'  => 'Error',
                'msg'    => 'Invalid CJ product.',
                'status' => 'error',
            ]]);
        }

        // We store CJ-specific data in the specifications field as JSON
        $specs = json_encode([
            'source'      => 'cj_dropship',
            'cj_pid'      => $cjPid,
            'cj_vid'      => $cjVid,
            'cj_sku'      => $cjSku,
            'cj_name'     => $cjName,
            'cj_price'    => $cjPrice,
            'cj_image'    => $cjImage,
            'cj_logistic' => $cjLogistic,
        ]);

        // Find or create a "proxy" Product record for CJ items.
        // Product uses $timestamps=false so we supply created_at/updated_at manually.
        // Title is a Translatable attribute (product_translations table) — set separately.
        // Thumbnail is stored in product_media table — inserted separately.
        $now  = time();
        $lang = app()->getLocale() ?: 'en';

        $product = Product::where('slug', 'cj_' . $cjPid)->first();

        if (!$product) {
            $product = new Product();
            $product->slug        = 'cj_' . $cjPid;
            $product->creator_id  = 1;        // system user
            $product->category_id = 1;        // default category
            $product->status      = 'active';
            $product->type        = 'virtual'; // CJ handles physical shipping
            $product->price       = $cjPrice;
            $product->ordering    = false;    // hide from local shop listing
            $product->created_at  = $now;
            $product->updated_at  = $now;
            $product->save();
        }

        // ── 1. Upsert the translation row so $product->title works ────────────
        // product_translations holds locale + title for Translatable products.
        DB::table('product_translations')->updateOrInsert(
            ['product_id' => $product->id, 'locale' => $lang],
            ['title' => $cjName]
        );

        // ── 2. Upsert a thumbnail media row so $product->thumbnail works ──────
        // ProductMedia::$thumbnail = 'thumbnail' (string constant on the model)
        if (!empty($cjImage)) {
            DB::table('product_media')->updateOrInsert(

                ['creator_id' => 1, 'product_id' => $product->id, 'type' => 'thumbnail'],
                ['path' => $cjImage,'created_at' => $now]
            );
        }

        // ── 3. Always refresh price from CJ (prices change) ───────────────────
        DB::table('products')
            ->where('id', $product->id)
            ->update(['price' => $cjPrice, 'updated_at' => $now]);

        $product->price = $cjPrice;

        $productOrder = ProductOrder::updateOrCreate(
            [
                'product_id' => $product->id,
                'seller_id'  => $product->creator_id,
                'buyer_id'   => $user->id,
                'sale_id'    => null,
                'status'     => 'pending',
            ],
            [
                'specifications' => $specs,
                'quantity'       => $quantity,
                'discount_id'    => null,
                'created_at'     => time(),
            ]
        );

        if ($user_as_a_guest) {
            Cart::updateOrCreate(
                ['creator_id' => 0, 'creator_guest_id' => $user->id, 'product_order_id' => $productOrder->id],
                ['created_at' => time()]
            );
        } else {
            Cart::updateOrCreate(
                ['creator_id' => $user->id, 'product_order_id' => $productOrder->id],
                ['created_at' => time()]
            );
        }

        return 'ok';
    }

    // ─────────────────────────────────────────────────────────────────────
    // COOKIE CART
    // ─────────────────────────────────────────────────────────────────────

    public function storeCookieCart($data)
    {
        $carts = Cookie::get($this->cookieKey);
        $carts = !empty($carts) ? json_decode($carts, true) : [];

        $item_id   = $data['item_id'];
        $item_name = $data['item_name'];

        if (empty($data['quantity'])) {
            $data['quantity'] = 1;
        }

        $carts[$item_name . '_' . $item_id] = $data;

        Cookie::queue($this->cookieKey, json_encode($carts), 30 * 24 * 60);
    }

    // ─────────────────────────────────────────────────────────────────────
    // STORE  (main entry point — handles all item_name types)
    // ─────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'item_id'   => 'required',
            'item_name' => 'nullable',
        ]);

        $data      = $request->except('_token');
        $item_name = $data['item_name'] ?? '';

        $user_as_a_guest = false;

        if (empty($user)) {
            $user            = new \stdClass();
            $deviceId        = session('device_id');
            if (!$deviceId) {
                $deviceId = 'guest_' . uniqid();
                session(['device_id' => $deviceId]);
            }
            $user->id        = $deviceId;
            $user_as_a_guest = true;
        }

        $result = null;

        if ($item_name === 'webinar_id') {
            $result = $this->storeUserWebinarCart($user, $data, $user_as_a_guest);

        } elseif ($item_name === 'product_id') {
            // Prevent duplicate in local products
            $cjVariantId = $request->input('cj_variant_id');

            $productOrderQuery  = ProductOrder::where('product_id', $request->input('item_id'))
                ->where('buyer_id', $user->id)
                ->where('status', 'pending')
                ->whereNull('sale_id');

            if (!empty($cjVariantId)) {
                $productOrderQuery->where('specifications->cj_vid', $cjVariantId);
            }

            $productOrder = $productOrderQuery->first();

            if (!empty($productOrder)) {

                $exists = $user_as_a_guest
                ? Cart::where('product_order_id', $productOrder->id)
                    ->where('creator_guest_id', $user->id)
                    ->where('creator_id', 0)
                    ->exists()
                : Cart::where('product_order_id', $productOrder->id)
                    ->where('creator_id', $user->id)
                    ->exists();

                // $exists = $user_as_a_guest
                //     ? Cart::where('product_order_id', $productOrder->id)->where('creator_guest_id', $user->id)->count()
                //     : Cart::where('product_order_id', $productOrder->id)->where('creator_id', $user->id)->count();

                if ($exists) {
                    return back()->with(['toast' => [
                        'title'  => 'Already in Cart',
                        'msg'    => !empty($cjVariantId)
                                ? trans('This product variant is already in your cart.')  // same variant
                                : trans('This product is already in your cart.'),   
                        // 'msg'    => 'This product is already in your cart.',
                        'status' => 'error',
                    ]]);
                }
            }

            $result = $this->storeUserProductCart($user, $data, $user_as_a_guest);

        } elseif ($item_name === 'bundle_id') {
            $result = $this->storeUserBundleCart($user, $data, $user_as_a_guest);

        } elseif ($item_name === 'cj_product_id') {
            // ── CJ Dropshipping product ──────────────────────────────
            $result = $this->storeUserCJProductCart($user, $data, $user_as_a_guest);
        }

        if ($result !== 'ok') {
            return $result;
        }

        $toast = [
            'title'  => trans('cart.cart_add_success_title'),
            'msg'    => trans('cart.cart_add_success_msg'),
            'status' => 'success',
        ];

        // If the caller sent a redirect_after URL (e.g. Buy Now → /cart),
        // honour it so the user lands on the right page with the toast.
        $redirectAfter = $request->input('redirect_after', '');
        if (!empty($redirectAfter)) {
            return redirect($redirectAfter)->with(['toast' => $toast]);
        }

        return back()->with(['toast' => $toast]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        if (auth()->check()) {
            $user_id = auth()->id();
            $cart    = Cart::where('id', $id)->where('creator_id', $user_id)->first();

            if (!empty($cart)) {
                if (!empty($cart->reserve_meeting_id)) {
                    ReserveMeeting::where('id', $cart->reserve_meeting_id)
                        ->where('user_id', $user_id)->first()?->delete();
                } elseif (!empty($cart->installment_payment_id)) {
                    $installmentPayment = $cart->installmentPayment;
                    if (!empty($installmentPayment) && $installmentPayment->status === 'paying') {
                        $installmentOrder = $installmentPayment->installmentOrder;
                        $installmentPayment->delete();
                        if (!empty($installmentOrder) && $installmentOrder->status === 'paying') {
                            $installmentOrder->delete();
                        }
                    }
                }
                $cart->delete();
            }
        } else {
            if (session()->has('device_id')) {
                $deviceId = session('device_id');
                Cart::where('id', $id)->where('creator_guest_id', $deviceId)->first()?->delete();
            } else {
                return redirect('/cart');
            }
        }

        /* If the request came from our fetch() (AJAX), return JSON so no redirect happens */
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'msg' => 'Item removed from cart.']);
        }

        /* If a redirect_to URL was passed, use it (e.g. back to payment step=4) */
        if (request()->get('redirect_to')) {
            return redirect(request()->get('redirect_to'))->with(['toast' => [
                'title'  => 'Cart Removed Successfully',
                'msg'    => 'Item removed from cart.',
                'status' => 'success',
            ]]);
        }

        return back()->with(['toast' => [
            'title'  => 'Cart Removed Successfully',
            'msg'    => 'Item removed from cart.',
            'status' => 'success',
        ]]);
    }
}