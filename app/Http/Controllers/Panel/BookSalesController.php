<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\BookOrder;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookSalesController extends Controller
{
    /**
     * Wisdom Keeper: List of their book sales
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query: sales where this user is the seller (book type)
        $query = BookOrder::query()
            ->where('book_order.seller_id', $user->id)
            ->where('book_order.status', '!=', BookOrder::$pending)
            ->whereHas('book')
            ->whereHas('sale', function ($q) {
                $q->where('type', Sale::$book)
                  ->whereNull('refund_at');
            });

        // ── Summary stats ──────────────────────────────────────────────────
        $totalOrders    = deepClone($query)->count();
        $pendingOrders  = deepClone($query)
            ->whereIn('book_order.status', [BookOrder::$waitingDelivery, BookOrder::$shipped])
            ->count();
        $deliveredOrders = deepClone($query)
            ->where('book_order.status', BookOrder::$success)
            ->count();
        $canceledOrders = deepClone($query)
            ->where('book_order.status', BookOrder::$canceled)
            ->count();

        $totalEarnings = deepClone($query)
            ->join('sales', 'sales.book_order_id', '=', 'book_order.id')
            ->join('book', 'book.id', '=', 'book_order.book_id')
            ->select(DB::raw('
                SUM(
                    sales.total_amount
                    - COALESCE(book.platform_price, 0)
                    - COALESCE(book.print_price, 0)
                    - COALESCE(book.shipping_price, 0)
                    - COALESCE(sales.tax, 0)
                ) as total_earnings
            '))
            ->value('total_earnings') ?? 0;

        $totalRevenue = deepClone($query)
            ->join('sales', 'sales.book_order_id', '=', 'book_order.id')
            ->select(DB::raw('SUM(sales.total_amount) as total_revenue'))
            ->value('total_revenue') ?? 0;

        // ── Filters ────────────────────────────────────────────────────────
        $query = $this->applyFilters($query, $request);

        $orders = $query
            ->orderBy('book_order.created_at', 'desc')
            ->with([
                'book',
                'sale',
                'buyer' => function ($q) {
                    $q->select('id', 'full_name', 'email', 'avatar');
                },
            ])
            ->paginate(10);

        $data = [
            'pageTitle'      => 'Library Sales',
            'orders'         => $orders,
            'totalOrders'    => $totalOrders,
            'pendingOrders'  => $pendingOrders,
            'deliveredOrders'=> $deliveredOrders,
            'canceledOrders' => $canceledOrders,
            'totalEarnings'  => $totalEarnings,
            'totalRevenue'   => $totalRevenue,
        ];

        return view(getTemplate() . '.panel.book.sales.lists', $data);
    }

    /**
     * Wisdom Keeper: Detail view of a single book sale with full price breakdown
     */
    public function show($id)
    {
        $user = auth()->user();

        $bookOrder = BookOrder::where('id', $id)
            ->where('seller_id', $user->id)
            ->with([
                'book' => function ($q) { $q->with('creator'); },
                'sale',
                'buyer',
                'seller',
            ])
            ->firstOrFail();

        $sale = $bookOrder->sale;
        $book = $bookOrder->book;

        abort_if(empty($book) || empty($sale), 404);

        $quantity = (int) ($bookOrder->quantity ?? 1);

        // ── Direct DB price columns ────────────────────────────────────────
        $sellingPrice  = (float) ($book->getRawOriginal('price')          ?? 0);
        $bookPrice     = (float) ($book->getRawOriginal('book_price')     ?? 0);
        $printPrice    = (float) ($book->getRawOriginal('print_price')    ?? 0);
        $shippingPrice = (float) ($book->getRawOriginal('shipping_price') ?? 0);
        $platformPrice = (float) ($book->getRawOriginal('platform_price') ?? 0);

        // ── Sale amounts ───────────────────────────────────────────────────
        $totalAmount   = (float) ($sale->total_amount ?? 0);
        $tax           = (float) ($sale->tax           ?? 0);
        $discount      = (float) ($sale->discount      ?? 0);

        // ── Per-quantity totals ────────────────────────────────────────────
        $printTotal    = $printPrice    * $quantity;
        $shippingTotal = $shippingPrice * $quantity;

        $commissionPct  = (float) $book->getCommission();
        $platformAmount = $platformPrice > 0
            ? $platformPrice * $quantity
            : round($totalAmount * $commissionPct / 100, 2);

        $earningAmount = round($totalAmount - $platformAmount - $printTotal - $shippingTotal - $tax, 2);

        // ── Book type ──────────────────────────────────────────────────────
        $bookType = $book->getRawOriginal('type') ?? $book->type ?? 'ebook';

        // ── Delivery statuses ──────────────────────────────────────────────
        $deliveryStatuses = [
            BookOrder::$pending         => ['label' => 'Pending',          'icon' => 'clock',          'color' => '#f59e0b'],
            BookOrder::$waitingDelivery => ['label' => 'Waiting Delivery',  'icon' => 'box',            'color' => '#3b82f6'],
            BookOrder::$shipped         => ['label' => 'Shipped',           'icon' => 'truck',          'color' => '#8b5cf6'],
            BookOrder::$success         => ['label' => 'Delivered',         'icon' => 'check-circle',   'color' => '#10b981'],
            BookOrder::$canceled        => ['label' => 'Canceled',          'icon' => 'x-circle',       'color' => '#ef4444'],
        ];
        $currentStatus = $bookOrder->status ?? BookOrder::$pending;

        $data = [
            'pageTitle'       => 'Library Sale Detail #' . $bookOrder->id,
            'bookOrder'       => $bookOrder,
            'sale'            => $sale,
            'book'            => $book,
            'bookType'        => $bookType,
            'quantity'        => $quantity,
            'sellingPrice'    => $sellingPrice,
            'bookPrice'       => $bookPrice,
            'printPrice'      => $printPrice,
            'shippingPrice'   => $shippingPrice,
            'platformPrice'   => $platformPrice,
            'printTotal'      => $printTotal,
            'shippingTotal'   => $shippingTotal,
            'totalAmount'     => $totalAmount,
            'tax'             => $tax,
            'discount'        => $discount,
            'commissionPct'   => $commissionPct,
            'platformAmount'  => $platformAmount,
            'earningAmount'   => $earningAmount,
            'deliveryStatuses'=> $deliveryStatuses,
            'currentStatus'   => $currentStatus,
        ];

        return view(getTemplate() . '.panel.book.sales.show', $data);
    }

    private function applyFilters($query, $request)
    {
        $from   = $request->input('from');
        $to     = $request->input('to');
        $status = $request->input('status');

        $query = fromAndToDateFilter($from, $to, $query, 'book_order.created_at');

        if (!empty($status) && $status !== 'all') {
            $query->where('book_order.status', $status);
        }

        return $query;
    }
}