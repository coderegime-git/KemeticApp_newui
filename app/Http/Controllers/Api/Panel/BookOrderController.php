<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductOrderResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\BookOrderResource;
use App\Models\Api\ProductOrder;
use App\Models\Api\Product;
use App\Models\Api\Book;
use App\Models\BookOrder;
use App\Models\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Gift;
use App\Models\ProductFile;
use App\Models\Translation\ProductFileTranslation;

class BookOrderController extends Controller
{
    public function getPurchases()
    {
        //dd(apiAuth()->id);
        $query = BookOrder::where('book_order.buyer_id', apiAuth()->id)
            ->where('book_order.status', '!=', 'pending')
        ->whereHas('sale', function ($query) {
            $query->where('type', 'book');
            $query->where('access_to_purchased_item', true);
            $query->whereNull('refund_at');
        });
       

        $totalOrders = deepClone($query)->count();
        $pendingOrders = deepClone($query)->where(function ($query) {
            $query->where('status', BookOrder::$waitingDelivery)
                ->orWhere('status', BookOrder::$shipped);
        })->count();
       
        $canceledOrders = deepClone($query)->where('status', BookOrder::$canceled)->count();
        
        $totalPurchase = deepClone($query)
            ->join('sales', 'sales.book_order_id', 'book_order.id')
            ->select(DB::raw("sum(total_amount) as totalAmount"))
            ->first();

        $sellerIds = deepClone($query)->pluck('seller_id')->toArray();
        $sellers = User::select('id', 'full_name')
            ->whereIn('id', array_unique($sellerIds))
            ->get();
        
        $orders = $query->orderBy('created_at', 'desc')
            ->get();
        $orders->totalOrders = $totalOrders;
        $orders->pendingOrders = $pendingOrders;
        $orders->canceledOrders = $canceledOrders;
        $orders->totalPurchase = $totalPurchase;
        $orders->sellers = $sellers;

        // dd($orders);

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'total_orders_count' => $totalOrders,
                'pending_orders_count' => $pendingOrders,
                'canceled_orders_count' => $canceledOrders,
                'total_purchase_amount' => $totalPurchase->totalAmount ?? 0,
                'orders' => BookOrderResource::collection($orders),
            ]
        );

    }
    
}
