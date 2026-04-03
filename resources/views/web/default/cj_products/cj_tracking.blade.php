<?php

namespace App\Http\Controllers\Web;

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

/**
 * CJ Order Fulfillment Controller
 *
 * Called AFTER your Stripe / local payment is confirmed.
 * Handles:
 *   - fulfilCJOrders()     → main hook called from PaymentController
 *   - trackingWebhook()    → receives CJ webhook updates
 *   - getTracking()        → user-facing order tracking endpoint
 *   - getShippingRates()   → AJAX shipping rate calculator shown in cart/checkout
 *   - getStorageList()     → list CJ warehouses
 *
 * ── Routes to add ────────────────────────────────────────────────────────
 *
 *   // Called internally after payment
 *   Route::post('/cj/fulfil/{orderId}', [CJFulfillmentController::class, 'fulfilCJOrders'])->middleware('auth');
 *
 *   // User order tracking page
 *   Route::get('/orders/{orderId}/track', [CJFulfillmentController::class, 'trackingPage'])->middleware('auth')->name('orders.track');
 *
 *   // AJAX endpoints
 *   Route::post('/api/cj/shipping-rates',  [CJFulfillmentController::class, 'getShippingRates']);
 *   Route::get('/api/cj/tracking/{cjOrderId}', [CJFulfillmentController::class, 'getTracking']);
 *   Route::get('/api/cj/storages',         [CJFulfillmentController::class, 'getStorageList']);
 *   Route::get('/api/cj/balance',          [CJFulfillmentController::class, 'getBalance']);
 *
 *   // CJ Webhook (no auth — uses secret key verification)
 *   Route::post('/webhooks/cj', [CJFulfillmentController::class, 'trackingWebhook']);
 */
class CJFulfillmentController extends Controller
{
    public function __construct(protected CJDropshippingService $cj) {}

    // =========================================================
    // MAIN FULFILLMENT — called after Stripe payment succeeds
    // =========================================================

    /**
     * Fulfil all CJ products in a local Order.
     *
     * Call this from your PaymentController after setPaymentAccounting():
     *
     *   app(CJFulfillmentController::class)->fulfilCJOrdersForLocalOrder($order);
     *
     * @param Order $order  Your local Order model (already paid)
     */
    public function fulfilCJOrdersForLocalOrder(Order $order): array
    {
        $results = [];

        // Load all order items that have a product_order
        $orderItems = OrderItem::where('order_id', $order->id)
            ->whereNotNull('product_order_id')
            ->with(['productOrder.product'])
            ->get();

        foreach ($orderItems as $item) {
            $productOrder = $item->productOrder;
            if (empty($productOrder)) continue;

            $specs = json_decode($productOrder->specifications ?? '{}', true);

            // Skip non-CJ items
            if (($specs['source'] ?? '') !== 'cj_dropship') continue;

            $result = $this->fulfilSingleCJItem($order, $item, $productOrder, $specs);
            $results[] = $result;

            // Save CJ tracking info back to product order
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

    /**
     * Fulfil a single CJ product order item.
     */
    protected function fulfilSingleCJItem(Order $order, OrderItem $item, ProductOrder $productOrder, array $specs): array
    {
        // Resolve buyer address from the order's user
        $buyer = $order->user;

        if (empty($buyer)) {
            return ['success' => false, 'message' => 'No buyer attached to order ' . $order->id];
        }

        // Resolve country name & code
        $countryCode = 'US';
        $countryName = 'United States';
        if (!empty($buyer->country_id)) {
            $region = Region::find($buyer->country_id);
            if ($region) {
                $countryCode = $region->code   ?? $countryCode;
                $countryName = $region->name   ?? $countryName;
            }
        }

        // Build CJ order payload
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
                    'vid'             => $specs['cj_vid']  ?? null,
                    'sku'             => $specs['cj_sku']  ?? null,
                    'quantity'        => $productOrder->quantity ?? 1,
                    'storeLineItemId' => 'item_' . $item->id,
                ],
            ],
        ];

        // Run full CJ fulfilment flow
        $result = $this->cj->fulfilOrderAfterPayment($orderData);

        return $result;
    }

    // =========================================================
    // HTTP ENDPOINT — trigger fulfil for a specific order
    // =========================================================

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

    // =========================================================
    // USER-FACING TRACKING PAGE
    // =========================================================

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
            if (($specs['source'] ?? '') !== 'cj_dropship') continue;

            $trackingNumber = $po->cj_tracking_number ?? null;
            $cjOrderId      = $po->cj_order_id       ?? null;
            $tracking       = null;

            if ($trackingNumber) {
                $tracking = $this->cj->getTracking($trackingNumber);
            } elseif ($cjOrderId) {
                // Try to get tracking from order details
                $orderDetail    = $this->cj->queryOrder($cjOrderId);
                $trackingNumber = $orderDetail['trackNumber'] ?? null;
                if ($trackingNumber) {
                    $po->update(['cj_tracking_number' => $trackingNumber]);
                    $tracking = $this->cj->getTracking($trackingNumber);
                }
            }

            $trackingResults[] = [
                'item_id'         => $item->id,
                'product_name'    => $specs['cj_name'] ?? 'CJ Product',
                'product_image'   => $specs['cj_image'] ?? '',
                'quantity'        => $po->quantity,
                'cj_order_id'     => $cjOrderId,
                'tracking_number' => $trackingNumber,
                'cj_status'       => $po->cj_status ?? 'pending',
                'tracking'        => $tracking,
            ];
        }

        return view('web.default.orders.cj_tracking', compact('order', 'trackingResults'));
    }

    // =========================================================
    // AJAX — GET TRACKING JSON
    // =========================================================

    public function getTracking(string $cjOrderId): JsonResponse
    {
        $trackingNumber = null;

        // First try to get it from our DB
        $po = ProductOrder::whereJsonContains('specifications->cj_order_id', $cjOrderId)
            ->orWhere('cj_order_id', $cjOrderId)
            ->first();

        if ($po && !empty($po->cj_tracking_number)) {
            $trackingNumber = $po->cj_tracking_number;
        } else {
            // Fetch from CJ API
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

    // =========================================================
    // AJAX — SHIPPING RATES (used in cart/checkout)
    // =========================================================

    /**
     * Calculate available shipping methods for given CJ products.
     *
     * POST /api/cj/shipping-rates
     * Body: {
     *   "from": "CN",
     *   "to":   "US",
     *   "zip":  "10001",
     *   "products": [{"vid":"...","quantity":1}]
     * }
     */
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

    // =========================================================
    // AJAX — STORAGE LIST
    // =========================================================

    public function getStorageList(): JsonResponse
    {
        $data = $this->cj->getStorageList();
        return response()->json(['success' => true, 'data' => $data]);
    }

    // =========================================================
    // AJAX — BALANCE
    // =========================================================

    public function getBalance(): JsonResponse
    {
        $data = $this->cj->getBalance();
        return response()->json(['success' => true, 'data' => $data]);
    }

    // =========================================================
    // WEBHOOK — CJ order/tracking status updates
    // =========================================================

    /**
     * Receives POST from CJ webhook.
     * Set webhook URL in your CJ dashboard to: https://yourdomain.com/webhooks/cj
     *
     * CJ sends events like: ORDER_SHIPPED, ORDER_DELIVERED, TRACKING_UPDATED, etc.
     */
    public function trackingWebhook(Request $request): JsonResponse
    {
        // Verify secret (optional but recommended)
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

        $event          = $payload['event']          ?? '';
        $cjOrderId      = $payload['orderId']        ?? ($payload['cjOrderId'] ?? '');
        $trackingNumber = $payload['trackingNumber'] ?? '';
        $orderStatus    = $payload['orderStatus']    ?? '';

        if ($cjOrderId) {
            $po = ProductOrder::where('cj_order_id', $cjOrderId)->first();

            if ($po) {
                $updates = [];

                if ($trackingNumber) {
                    $updates['cj_tracking_number'] = $trackingNumber;
                }

                if ($orderStatus) {
                    $updates['cj_status'] = strtolower($orderStatus);

                    // Map CJ status → your local ProductOrder status
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