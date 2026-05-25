<?php

namespace App\Http\Controllers\Panel\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductSalesController extends Controller
{
    /**
     * Wisdom Keeper: List of their product sales
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = ProductOrder::query()
            ->where('product_orders.seller_id', $user->id)
            ->whereNotIn('product_orders.status', [ProductOrder::$canceled, ProductOrder::$pending])
            ->whereHas('sale', function ($q) {
                $q->where('type', Sale::$product)
                  ->whereNull('refund_at');
            })
            ->whereHas('product');

        // ── Summary stats ──────────────────────────────────────────────────
        $totalOrders     = deepClone($query)->count();
        $pendingOrders   = deepClone($query)
            ->whereIn('product_orders.status', [ProductOrder::$waitingDelivery, ProductOrder::$shipped])
            ->count();
        $deliveredOrders = deepClone($query)
            ->where('product_orders.status', ProductOrder::$success)
            ->count();
        $canceledOrders  = deepClone($query)
            ->where('product_orders.status', ProductOrder::$canceled)
            ->count();

        $totalRevenue = deepClone($query)
            ->join('sales', 'sales.product_order_id', '=', 'product_orders.id')
            ->select(DB::raw('SUM(sales.total_amount) as total_revenue'))
            ->value('total_revenue') ?? 0;

        // Earning = total - platform commission - tax - shipping
        $totalEarnings = deepClone($query)
            ->join('sales', 'sales.product_order_id', '=', 'product_orders.id')
            ->join('products', 'products.id', '=', 'product_orders.product_id')
            ->select(DB::raw('
                SUM(
                    sales.total_amount
                    - (sales.total_amount * COALESCE(products.commission, 0) / 100)
                    - COALESCE(sales.tax, 0)
                    - COALESCE(sales.product_delivery_fee, 0)
                ) as total_earnings
            '))
            ->value('total_earnings') ?? 0;

        // ── Filters ────────────────────────────────────────────────────────
        $query = $this->applyFilters($query, $request);

        $orders = $query
            ->orderBy('product_orders.created_at', 'desc')
            ->with([
                'product' => function ($q) {
                    $q->with(['cjVariants', 'category', 'media']);
                },
                'sale',
                'buyer' => function ($q) {
                    $q->select('id', 'full_name', 'email', 'avatar');
                },
            ])
            ->paginate(10);

        // Annotate each order with price breakdown
        foreach ($orders as $order) {
            $order = $this->annotateOrder($order);
        }

        $data = [
            'pageTitle'      => 'My Product Sales',
            'orders'         => $orders,
            'totalOrders'    => $totalOrders,
            'pendingOrders'  => $pendingOrders,
            'deliveredOrders'=> $deliveredOrders,
            'canceledOrders' => $canceledOrders,
            'totalEarnings'  => $totalEarnings,
            'totalRevenue'   => $totalRevenue,
        ];

        return view(getTemplate() . '.panel.store.sales.lists', $data);
    }

    /**
     * Wisdom Keeper: Detail view of a single product sale
     */
    public function show($id)
    {
        $user = auth()->user();

        $productOrder = ProductOrder::where('id', $id)
            ->where('seller_id', $user->id)
            ->with([
                'product' => function ($q) {
                    $q->with(['cjVariants', 'category', 'creator', 'media']);
                },
                'sale',
                'buyer',
                'seller',
            ])
            ->firstOrFail();

        $sale    = $productOrder->sale;
        $product = $productOrder->product;

        abort_if(empty($product) || empty($sale), 404);

        // ── CJ detection ──────────────────────────────────────────────────
        $isCj      = (bool) ($product->is_cj_product ?? false);
        $cjSpecs   = $productOrder->cj_specifications ?? [];
        $selectedVid = $cjSpecs['vid'] ?? null;
        $cjVariant = null;

        if ($isCj && $selectedVid) {
            $cjVariant = $product->cjVariants->firstWhere('vid', $selectedVid);
        } elseif ($isCj) {
            $cjVariant = $product->cjVariants->where('is_selected', true)->first();
        }

        // ── Price breakdown ────────────────────────────────────────────────
        $quantity      = (int) ($productOrder->quantity ?? 1);
        $totalAmount   = (float) ($sale->total_amount         ?? 0);
        $tax           = (float) ($sale->tax                  ?? 0);
        $discount      = (float) ($sale->discount             ?? 0);
        $shippingFee   = (float) ($sale->product_delivery_fee ?? 0);

        $commissionPct = (float) $product->getCommission();

        // Base unit price
        $basePrice = $isCj && $cjVariant
            ? (float) $cjVariant->sell_price
            : (float) ($product->price ?? 0);

        $platformAmount = round($totalAmount * $commissionPct / 100, 2);
        $earningAmount  = round($totalAmount - $platformAmount - $tax - $shippingFee, 2);

        // Delivery statuses
        $deliveryStatuses = [
            ProductOrder::$pending         => ['label' => 'Pending',          'icon' => 'clock',        'color' => '#f59e0b'],
            ProductOrder::$waitingDelivery => ['label' => 'Waiting Delivery',  'icon' => 'box',          'color' => '#3b82f6'],
            ProductOrder::$shipped         => ['label' => 'Shipped',           'icon' => 'truck',        'color' => '#8b5cf6'],
            ProductOrder::$success         => ['label' => 'Delivered',         'icon' => 'check-circle', 'color' => '#10b981'],
            ProductOrder::$canceled        => ['label' => 'Canceled',          'icon' => 'x-circle',     'color' => '#ef4444'],
        ];
        $currentStatus = $productOrder->status ?? ProductOrder::$pending;

        $data = [
            'pageTitle'       => 'Product Sale Detail #' . $productOrder->id,
            'productOrder'    => $productOrder,
            'sale'            => $sale,
            'product'         => $product,
            'isCj'            => $isCj,
            'cjVariant'       => $cjVariant,
            'cjSpecs'         => $cjSpecs,
            'isPhysical'      => $product->isPhysical(),
            'quantity'        => $quantity,
            'basePrice'       => $basePrice,
            'totalAmount'     => $totalAmount,
            'tax'             => $tax,
            'discount'        => $discount,
            'shippingFee'     => $shippingFee,
            'commissionPct'   => $commissionPct,
            'platformAmount'  => $platformAmount,
            'earningAmount'   => $earningAmount,
            'deliveryStatuses'=> $deliveryStatuses,
            'currentStatus'   => $currentStatus,
        ];

        return view(getTemplate() . '.panel.store.sales.show', $data);
    }

    private function annotateOrder($order)
    {
        $sale    = $order->sale;
        $product = $order->product;

        if (!$sale || !$product) return $order;

        $commissionPct        = (float) $product->getCommission();
        $totalAmount          = (float) ($sale->total_amount ?? 0);
        $tax                  = (float) ($sale->tax ?? 0);
        $shippingFee          = (float) ($sale->product_delivery_fee ?? 0);
        $order->platformAmount = round($totalAmount * $commissionPct / 100, 2);
        $order->earningAmount  = round($totalAmount - $order->platformAmount - $tax - $shippingFee, 2);
        $order->isCj           = (bool) ($product->is_cj_product ?? false);

        return $order;
    }

    private function applyFilters($query, $request)
    {
        $from   = $request->input('from');
        $to     = $request->input('to');
        $status = $request->input('status');
        $type   = $request->input('type'); // physical / virtual

        $query = fromAndToDateFilter($from, $to, $query, 'product_orders.created_at');

        if (!empty($status) && $status !== 'all') {
            $query->where('product_orders.status', $status);
        }

        if (!empty($type) && in_array($type, ['physical', 'virtual'])) {
            $query->whereHas('product', function ($q) use ($type) {
                $q->where('type', $type);
            });
        }

        return $query;
    }
}