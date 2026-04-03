<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductOrder;
use App\Models\Region;
use App\Services\CJDropshippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CJFulfillmentController extends Controller
{
    public function __construct(protected CJDropshippingService $cj) {}

    /**
     * Fulfil all CJ products in a local Order.
     */
    public function fulfilCJOrdersForLocalOrder(Order $order): array
    {
        $results = [];

        $orderItems = OrderItem::where('order_id', $order->id)
            ->whereNotNull('product_order_id')
            ->with(['productOrder.product'])
            ->get();

        foreach ($orderItems as $item) {
            $productOrder = $item->productOrder;
            if (empty($productOrder)) continue;

            $specs = json_decode($productOrder->specifications ?? '{}', true);

            if (($specs['source'] ?? '') !== 'cj_dropship' && !($productOrder->product->is_cj_product ?? false)) continue;

            $result = $this->fulfilSingleCJItem($order, $item, $productOrder, $specs);
            $results[] = $result;

            if ($result['success']) {
                $productOrder->update([
                    'cj_order_id'       => $result['cj_order_id']      ?? null,
                    'cj_shipment_id'    => $result['shipment_order_id'] ?? null,
                    'cj_tracking_number'=> $result['tracking_number']   ?? null,
                    'cj_status'         => 'submitted',
                    'status'            => ProductOrder::$waitingDelivery,
                ]);
            } else {
                Log::error('CJ fulfil failed for orderItem ' . $item->id, $result);
            }
        }

        return $results;
    }

    protected function fulfilSingleCJItem(Order $order, OrderItem $item, ProductOrder $productOrder, array $specs): array
    {
        $buyer = $order->user;

        if (empty($buyer)) {
            return ['success' => false, 'message' => 'No buyer attached to order ' . $order->id];
        }

        $countryCode = 'US';
        $countryName = 'United States';
        if (!empty($buyer->country_id)) {
            $region = Region::find($buyer->country_id);
            if ($region) {
                $countryCode = $region->code   ?? $countryCode;
                $countryName = $region->name   ?? $countryName;
            }
        }

        $orderData = [
            'orderNumber'         => 'ORDER-' . $order->id . '-ITEM-' . $item->id . '-' . time(),
            'shippingCountryCode' => $countryCode,
            'shippingCountry'     => $countryName,
            'shippingProvince'    => $buyer->province_name ?? '',
            'shippingCity'        => $buyer->city_name     ?? '',
            'shippingAddress'     => $buyer->address       ?? '',
            'shippingAddress2'    => '',
            'shippingZip'         => $buyer->zip_code      ?? '',
            'shippingPhone'       => $buyer->mobile        ?? '',
            'houseNumber'         => $buyer->house_no      ?? '',
            'shippingCustomerName'=> $buyer->full_name     ?? '',
            'email'               => $buyer->email         ?? '',
            'logisticName'        => $specs['cj_logistic'] ?? env('CJ_DEFAULT_LOGISTIC', 'PostNL'),
            'fromCountryCode'     => env('CJ_FROM_COUNTRY', 'CN'),
            'platform'            => env('CJ_PLATFORM', 'Api'),
            'shopAmount'          => (string) ($specs['cj_price'] ?? 0),
            'remark'              => 'Order #' . $order->id,
            'payType'             => 2,  // balance payment
            'products'            => [
                [
                    'vid'             => $specs['cj_vid']  ?? $productOrder->product->cj_vid ?? null,
                    'sku'             => $specs['cj_sku']  ?? null,
                    'quantity'        => $productOrder->quantity ?? 1,
                    'storeLineItemId' => 'item_' . $item->id,
                ],
            ],
        ];

        $result = $this->cj->fulfilOrderAfterPayment($orderData);

        return $result;
    }

    public function fulfilCJOrders(Request $request, int $orderId): JsonResponse
    {
        $user  = auth()->user();
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('status', Order::$paid)
            ->firstOrFail();

        $results = $this->fulfilCJOrdersForLocalOrder($order);
        $allSucceeded = collect($results)->every(fn($r) => $r['success'] ?? false);

        return response()->json([
            'success' => $allSucceeded,
            'results' => $results,
        ]);
    }

    public function trackingPage(int $orderId)
    {
        $user  = auth()->user();
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->with(['orderItems.productOrder'])
            ->firstOrFail();

        $trackingResults = [];

        foreach ($order->orderItems as $item) {
            $po = $item->productOrder;
            if (empty($po)) continue;

            $specs = json_decode($po->specifications ?? '{}', true);
            if (($specs['source'] ?? '') !== 'cj_dropship' && !($po->product->is_cj_product ?? false)) continue;

            $trackingNumber = $po->cj_tracking_number ?? null;
            $cjOrderId      = $po->cj_order_id       ?? null;
            $tracking       = null;

            if ($trackingNumber) {
                $tracking = $this->cj->getTracking($trackingNumber);
            } elseif ($cjOrderId) {
                $orderDetail    = $this->cj->queryOrder($cjOrderId);
                $trackingNumber = $orderDetail['trackNumber'] ?? null;
                if ($trackingNumber) {
                    $po->update(['cj_tracking_number' => $trackingNumber]);
                    $tracking = $this->cj->getTracking($trackingNumber);
                }
            }

            $trackingResults[] = [
                'item_id'         => $item->id,
                'product_name'    => $specs['cj_name'] ?? $po->product->title ?? 'CJ Product',
                'product_image'   => $specs['cj_image'] ?? $po->product->thumbnail ?? '',
                'quantity'        => $po->quantity,
                'cj_order_id'     => $cjOrderId,
                'tracking_number' => $trackingNumber,
                'cj_status'       => $po->cj_status ?? 'pending',
                'tracking'        => $tracking,
            ];
        }

        return view('web.default.panel.cj_products.cj_tracking', compact('order', 'trackingResults'));
    }

    public function getTracking(string $cjOrderId): JsonResponse
    {
        $trackingNumber = null;
        $po = ProductOrder::whereJsonContains('specifications->cj_order_id', $cjOrderId)
            ->orWhere('cj_order_id', $cjOrderId)
            ->first();

        if ($po && !empty($po->cj_tracking_number)) {
            $trackingNumber = $po->cj_tracking_number;
        } else {
            $detail = $this->cj->queryOrder($cjOrderId);
            $trackingNumber = $detail['trackNumber'] ?? null;
            if ($trackingNumber && $po) {
                $po->update(['cj_tracking_number' => $trackingNumber]);
            }
        }

        if (!$trackingNumber) {
            return response()->json(['success' => false, 'message' => 'No tracking number yet'], 404);
        }

        $tracking = $this->cj->getTracking($trackingNumber);

        return response()->json([
            'success'         => true,
            'tracking_number' => $trackingNumber,
            'data'            => $tracking,
        ]);
    }

    public function getShippingRates(Request $request): JsonResponse
    {
        $this->validate($request, [
            'from'       => 'required|string|size:2',
            'to'         => 'required|string|size:2',
            'products'   => 'required|array',
            'products.*.vid'      => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $rates = $this->cj->calculateFreight(
            $request->input('from'),
            $request->input('to'),
            $request->input('products'),
            $request->input('zip', '')
        );

        return response()->json(['success' => true, 'data' => $rates]);
    }

    public function getStorageList(): JsonResponse
    {
        $data = $this->cj->getStorageList();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getBalance(): JsonResponse
    {
        $data = $this->cj->getBalance();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function trackingWebhook(Request $request): JsonResponse
    {
        $secret    = env('CJ_WEBHOOK_SECRET', '');
        $signature = $request->header('X-CJ-Signature', '');

        if ($secret && $signature) {
            $computed = hash_hmac('sha256', $request->getContent(), $secret);
            if (!hash_equals($computed, $signature)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        $payload = $request->all();
        Log::info('CJ Webhook received', $payload);

        $cjOrderId      = $payload['orderId']        ?? ($payload['cjOrderId'] ?? '');
        $trackingNumber = $payload['trackingNumber'] ?? '';
        $orderStatus    = $payload['orderStatus']    ?? '';

        if ($cjOrderId) {
            $po = ProductOrder::where('cj_order_id', $cjOrderId)->first();

            if ($po) {
                $updates = [];
                if ($trackingNumber) $updates['cj_tracking_number'] = $trackingNumber;
                if ($orderStatus) {
                    $updates['cj_status'] = strtolower($orderStatus);
                    $statusMap = [
                        'shipped'   => ProductOrder::$waitingDelivery,
                        'delivered' => ProductOrder::$success,
                        'cancelled' => ProductOrder::$canceled,
                    ];
                    if (isset($statusMap[strtolower($orderStatus)])) {
                        $updates['status'] = $statusMap[strtolower($orderStatus)];
                    }
                }
                if (!empty($updates)) {
                    $po->update($updates);
                    Log::info("CJ Webhook: Updated ProductOrder #{$po->id}", $updates);
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
