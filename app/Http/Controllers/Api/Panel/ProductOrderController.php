<?php
namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductOrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Api\ProductOrder;
use App\Models\Api\Product;
use App\Models\Comment;
use App\User;
use App\Models\Gift;
use App\Models\ProductFile;
use App\Services\CJDropshippingService;
use App\Models\Translation\ProductFileTranslation;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductOrderController extends Controller
{
    protected CJDropshippingService $cjService;

    public function __construct(CJDropshippingService $cjService)
    {
        $this->cjService = $cjService;
    }
    
    public function index(Request $request)
    {
        $user = apiAuth();

        $query = ProductOrder::where('product_orders.seller_id', $user->id)
            ->where('product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            });

        $totalOrders = deepClone($query)->count();
        $pendingOrders = deepClone($query)->where('product_orders.status', ProductOrder::$waitingDelivery)->count();
        $canceledOrders = deepClone($query)->where('product_orders.status', ProductOrder::$canceled)->count();

        $totalSales = deepClone($query)
            ->join('sales', 'sales.product_order_id', 'product_orders.id')
            ->select(DB::raw('(sum(sales.total_amount) - (sum(sales.tax) + sum(sales.commission))) as totalAmount')) // DB::raw("sum(sales.total_amount) as totalAmount")
            ->first();


        $orders = $query->handleFilters()->orderBy('created_at', 'desc')->get();

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'orders' => ProductOrderResource::collection($orders),
                'total_orders_count' => $totalOrders,
                'pending_orders_count' => $pendingOrders,
                'canceled_orders_count' => $canceledOrders,
                'total_sales' => $totalSales->totalAmount ?? 0,
            ]
        );

    }

    public function getBuyers()
    {
        $user = apiAuth();

        $query = ProductOrder::where('product_orders.seller_id', $user->id)
            ->where('product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            });
        $customerIds = deepClone($query)->pluck('buyer_id')->toArray();
        $customers = User::select('id', 'full_name')
            ->whereIn('id', array_unique($customerIds))
            ->get();

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'users' => $customers
            ]
        );

    }

    public function getPurchases()
    {
        // $user = apiAuth();

        // $giftsIds = Gift::query()
        //     ->where(function ($query) use ($user) {
        //         $query->where('email', $user->email);
        //         $query->orWhere('user_id', $user->id);
        //     })
        //     ->where('status', 'active')
        //     ->whereNotNull('product_id')
        //     ->where(function ($query) {
        //         $query->whereNull('date');
        //         $query->orWhere('date', '<', time());
        //     })
        //     ->whereHas('sale')
        //     ->pluck('id')
        //     ->toArray();

        $query = ProductOrder::where('product_orders.buyer_id', apiAuth()->id)
            // ->orWhereIn('product_orders.gift_id', $giftsIds)
            ->where('product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->where('type', 'product');
                $query->where('access_to_purchased_item', true);
                $query->whereNull('refund_at');
            });



        $totalOrders = deepClone($query)->count();
        $pendingOrders = deepClone($query)->where(function ($query) {
            $query->where('status', ProductOrder::$waitingDelivery)
                ->orWhere('status', ProductOrder::$shipped);
        })->count();
        $canceledOrders = deepClone($query)->where('status', ProductOrder::$canceled)->count();

        $totalPurchase = deepClone($query)
            ->join('sales', 'sales.product_order_id', 'product_orders.id')
            ->select(DB::raw("sum(total_amount) as totalAmount"))
            ->first();

        $sellerIds = deepClone($query)->pluck('seller_id')->toArray();
        $sellers = User::select('id', 'full_name')
            ->whereIn('id', array_unique($sellerIds))
            ->get();

        $orders = $query->handleFilters()->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $order->cj_tracking_info = null; // default

            if (empty($order->product) || !$order->product->is_cj_product) {
                continue;
            }

            // Step 1: use already-saved tracking number
            $trackNumber = $order->cj_tracking_number ?? null;

            // Step 2: no tracking number yet → fetch from CJ order detail
            if (empty($trackNumber) && !empty($order->cj_order_id)) {
                try {
                    $orderDetail = $this->cjService->queryOrder($order->cj_order_id);
                    $trackNumber = $orderDetail['trackNumber'] ?? null;

                    if (!empty($trackNumber)) {
                        $order->update(['cj_tracking_number' => $trackNumber]);
                        Log::info('getPurchases CJ: saved tracking number', [
                            'productOrderId' => $order->id,
                            'trackNumber'    => $trackNumber,
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error('getPurchases CJ: queryOrder failed', [
                        'productOrderId' => $order->id,
                        'cj_order_id'    => $order->cj_order_id,
                        'error'          => $e->getMessage(),
                    ]);
                }
            }

            // Step 3: still nothing → shipment not created yet
            if (empty($trackNumber)) {
                $order->cj_tracking_info = [
                    'result'  => false,
                    'message' => 'Tracking not created. Shipment has not been created yet for this order.',
                    'data'    => null,
                ];
                continue;
            }

            // Step 4: call CJ trackInfo
            try {
                $trackingData = $this->cjService->getTracking($trackNumber);

                $order->cj_tracking_info = $trackingData
                    ? ['result' => true,  'message' => 'Success', 'data' => $trackingData]
                    : ['result' => false, 'message' => 'Tracking not created. Shipment still not created for this order.', 'data' => null];

            } catch (\Throwable $e) {
                Log::error('getPurchases CJ: getTracking failed', [
                    'productOrderId' => $order->id,
                    'trackNumber'    => $trackNumber,
                    'error'          => $e->getMessage(),
                ]);
                $order->cj_tracking_info = [
                    'result'  => false,
                    'message' => 'Tracking lookup failed: ' . $e->getMessage(),
                    'data'    => null,
                ];
            }
        }

        $orders->totalOrders = $totalOrders;
        $orders->pendingOrders = $pendingOrders;
        $orders->canceledOrders = $canceledOrders;
        $orders->totalPurchase = $totalPurchase;
        $orders->sellers = $sellers;
        
        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'total_orders_count' => $totalOrders,
                'pending_orders_count' => $pendingOrders,
                'canceled_orders_count' => $canceledOrders,
                'total_purchase_amount' => $totalPurchase->totalAmount ?? 0,
                'orders' => ProductOrderResource::collection($orders),
            ]
        );
    }

    public function getSellers()
    {
        $query = ProductOrder::where('product_orders.buyer_id', apiAuth()->id)
            ->where('product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->where('type', 'product');
                $query->where('access_to_purchased_item', true);
                $query->whereNull('refund_at');
            });

        $sellerIds = deepClone($query)->pluck('seller_id')->toArray();
        $sellers = User::select('id', 'full_name')
            ->whereIn('id', array_unique($sellerIds))
            ->get();
    }


    public function purchaseInvoice($saleId, $orderId, $user_id)
    {
        // die('ghjgjghj');
        $user = USer::find($user_id);
        if(!$user){
            abort(404);
        }
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
            // print_r($data);die;   
            return view('web.default.panel.store.invoice', $data);
        }

    }

    public function salesInvocie($saleId, $orderId, $user_id)
    {

        $user = User::find($user_id);
        if(!$user){
            abort(404);
        }
        $productOrder = ProductOrder::where('seller_id', $user->id)
            ->where('id', $orderId)
            ->where('sale_id', $saleId)
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            })
            ->first();

        if (!empty($productOrder) and !empty($productOrder->product)) {
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

    }

    public function downloadPdf($id)
    {
        
        $file = ProductFile::where('id', $id)->first();
       
        if (!empty($file)) {
            $product = Product::where('id', $file->product_id)
                ->where('status', Product::$active)
                ->first();
               
            if (!empty($product)) {
                $fileType = explode('.', $file->path);
                $fileType = end($fileType);
        
                $filePath = public_path($file->path);

                if (file_exists($filePath)) {
                    // $fileName = str_replace([' ', '.'], '-', $file->title);
                    $fileName = str_replace([' ', '.', '/'], '-', $file->title);
                    $fileName .= '.' . $fileType;

                    $headers = [
                        'Content-Type: application/' . $fileType,
                    ];
                    
                    return response()->download($filePath, $fileName, $headers);
                }
            }
        }

       
    }

}
