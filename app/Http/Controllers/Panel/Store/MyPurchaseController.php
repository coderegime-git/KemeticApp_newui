<?php

namespace App\Http\Controllers\Panel\Store;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use App\Models\ProductOrder;
use App\Models\Sale;
use App\Models\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_products_purchases");

        $user = auth()->user();

        $giftsIds = Gift::query()
            ->where(function ($query) use ($user) {
                $query->where('email', $user->email);
                $query->orWhere('user_id', $user->id);
            })
            ->where('status', 'active')
            ->whereNotNull('product_id')
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->pluck('id')
            ->toArray();

        $query = ProductOrder::query()
            ->where(function ($query) use ($user, $giftsIds) {
                $query->where('product_orders.buyer_id', $user->id);
                $query->orWhereIn('product_orders.gift_id', $giftsIds);
            })
            ->where('product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->whereIn('type', ['product', 'gift', 'subscribe']);
                $query->where('access_to_purchased_item', true);
                $query->whereNull('refund_at');
            });

        // echo '<pre>';
        // print_r($query->get());die;
        $totalOrders = deepClone($query)->count();
        $pendingOrders = deepClone($query)->where(function ($query) {
            $query->where('status', ProductOrder::$waitingDelivery)
                ->orWhere('status', ProductOrder::$shipped);
        })->count();
        $canceledOrders = deepClone($query)->where('status', ProductOrder::$canceled)->count();

        $totalPurchase = deepClone($query)
            ->join('sales', 'sales.id', 'product_orders.sale_id')
            // ->join('sales', 'sales.product_order_id', 'product_orders.id')
            ->select(DB::raw("sum(total_amount) as totalAmount"))
            ->first();

        $sellerIds = deepClone($query)->pluck('seller_id')->toArray();
        $sellers = User::select('id', 'full_name')
            ->whereIn('id', array_unique($sellerIds))
            ->get();

        $query = $this->filters($query, $request);

        $orders = $query->orderBy('created_at', 'desc')
            ->with([
                'product',
                'sale',
                'seller' => function ($query) {
                    $query->select('id', 'full_name', 'email', 'mobile', 'avatar');
                }
            ])
            ->paginate(10);

        // echo '<pre>';
        // print_r($orders);die;
        $data = [
            'pageTitle' => trans('update.product_purchases_lists_page_title'),
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'canceledOrders' => $canceledOrders,
            'totalPurchase' => $totalPurchase ? $totalPurchase->totalAmount : 0,
            'sellers' => $sellers,
            'orders' => $orders,
        ];

        return view('web.default.panel.store.my-purchases', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $seller_id = $request->input('seller_id');
        $type = $request->input('type');
        $status = $request->input('status');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($seller_id) and $seller_id != 'all') {
            $query->where('seller_id', $seller_id);
        }

        if (isset($type) and $type !== 'all') {
            $query->whereHas('product', function ($query) use ($type) {
                $query->where('type', $type);
            });
        }

        if (isset($status) and $status !== 'all') {
            $query->where('status', $status);
        }

        return $query;
    }

    public function getProductOrder($saleId, $orderId)
    {
        $user = auth()->user();

        $order = ProductOrder::where('buyer_id', $user->id)
            ->where('id', $orderId)
            ->where('sale_id', $saleId)
            ->first();

        if (!empty($order)) {
            $shippingTrackingUrl = getStoreSettings('shipping_tracking_url');

            $order->address = $order->buyer->getAddress(true);

            return response()->json([
                'order' => $order,
                'shipping_tracking_url' => $shippingTrackingUrl
            ]);
        }

        abort(403);
    }

    public function setGotTheParcel($saleId, $orderId)
    {
        $user = auth()->user();

        $order = ProductOrder::where('buyer_id', $user->id)
            ->where('id', $orderId)
            ->where('sale_id', $saleId)
            ->first();

        if (!empty($order)) {
            $order->update([
                'status' => ProductOrder::$success
            ]);

            $product = $order->product;
            $buyer = $order->buyer;

            $notifyOptions = [
                '[p.title]' => $product->title,
                '[u.name]' => $buyer->full_name
            ];
            sendNotification('product_receive_shipment', $notifyOptions, $order->seller_id);

            return response()->json([
                'code' => 200
            ]);
        }

        return response()->json([
            'code' => 422
        ]);
    }

    public function invoice($saleId, $orderId)
    {

        $user = auth()->user();

        // print_r($user->id);die;
        $giftsIds = Gift::query()
            ->where(function ($query) use ($user) {
                $query->where('email', $user->email);
                $query->orWhere('user_id', $user->id);
            })
            ->where('status', 'active')
            ->whereNotNull('product_id')
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->pluck('id')
            ->toArray();

        $productOrder = ProductOrder::query()
            ->where(function ($query) use ($user, $giftsIds) {
                $query->where('buyer_id', $user->id);
                $query->orWhereIn('gift_id', $giftsIds);
            })
            ->where('id', $orderId)
            ->where('sale_id', $saleId)
            ->first();

        if (!empty($productOrder)) {

            if (!empty($productOrder->gift_id)) {
                $gift = $productOrder->gift;

                $productOrder->buyer = $gift->user;
            }

            $data = [
                'pageTitle' => trans('webinars.invoice_page_title'),
                'order' => $productOrder,
                'product' => $productOrder->product,
                'sale' => $productOrder->sale,
                'seller' => $productOrder->seller,
                'buyer' => $productOrder->buyer,
            ];

            return view('web.default.panel.store.invoice', $data);
        }

        abort(404);
    }

    public function memberinvoice($orderId)
    {
        $user = auth()->user();

        // Get membership order with relationships
        $membershipOrder = Order::query()
        ->where('user_id', $user->id)
        ->where('id', $orderId)
        ->whereHas('orderItems', function($query) {
            $query->whereNotNull('subscribe_id'); // Only membership orders
        })
        ->with([
            'orderItems' => function($query) {
                $query->whereNotNull('subscribe_id')
                      ->with('subscribe');
            }
        ])
        ->first();

        if (!empty($membershipOrder)) {
        
        // Get the order item with subscription details
            $orderItem = $membershipOrder->orderItems->first();
            $subscribe = $orderItem->subscribe ?? null;
            
            
            // Get the sale record for this order
            // Assuming you have a sales table with order_id foreign key
            $sale = Sale::query()
                ->where('order_id', $membershipOrder->id)
                ->where('subscribe_id', $subscribe->id)
                ->first();

                // dd($subscribe);
            
            // Alternative: if sales table has buyer_id and product_id structure
            // $sale = Sale::query()
            //     ->where('buyer_id', $user->id)
            //     ->where('product_id', $subscribe->id ?? null)
            //     ->where('product_type', 'subscribe')
            //     ->first();
            
            // Check if subscription is active
            $isActive = false;
            if ($subscribe && $sale) {
                $currentTime = time();
                // Calculate expiration time based on subscription days
                $expirationTime = $orderItem->created_at + ($subscribe->days * 86400);
                
                $isActive = ($sale->payment_method !== 'offline' && 
                            $currentTime >= $orderItem->created_at && 
                            $currentTime < $expirationTime);
            }
            
            // Get membership features/prices from subscribe table
            $membershipFeatures = [
                'title' => $subscribe->title ?? 'Membership',
                'description' => $subscribe->description ?? '',
                'price' => $subscribe->price ?? 0,
                'days' => $subscribe->days ?? 0,
                'icon' => $subscribe->icon ?? '',
                'is_active' => $isActive,
                'usable_time' => $subscribe->days * 86400, // Convert days to seconds
            ];
            
            $data = [
                'pageTitle' => 'Membership Invoice',
                'order' => $membershipOrder,
                'orderItem' => $orderItem,
                'subscribe' => $subscribe,
                'sale' => $sale,
                'seller' => null, // You may need to get seller info if applicable
                'buyer' => $user,
                'membershipFeatures' => $membershipFeatures,
                'isActive' => $isActive,
            ];
            
            return view('web.default.panel.financial.invoice', $data);
        }

        abort(404);
    }
}
