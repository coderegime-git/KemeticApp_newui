<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * CJ Dropshipping Full-Stack Service
 *
 * Covers:
 *   - Auth (token + refresh)
 *   - Products, Categories, Variants, Inventory
 *   - Storage / Warehouses
 *   - Logistics (freight calculation)
 *   - Shopping: Create Order, Add Cart, Confirm, Generate Parent, List/Query Orders
 *   - Payment: Balance, Pay Balance V2
 *   - Tracking (v2)
 */
class CJDropshippingService
{
    protected string $baseUrl   = 'https://developers.cjdropshipping.com/api2.0/v1';
    protected ?string $certPath = null;

    public function __construct()
    {
        foreach ([
            storage_path('certs/cacert.pem'),
            base_path('cacert.pem'),
            'C:/laragon/etc/ssl/cert.pem',
            'C:/laragon/etc/ssl/cacert.pem',
        ] as $p) {
            if (file_exists($p)) { $this->certPath = $p; break; }
        }
    }

    // =========================================================
    // AUTH
    // =========================================================

    public function getAccessToken(): ?string
    {
        $cached = Cache::get('cj_access_token');
        if ($cached) return $cached;

        $res = $this->rawRequest('POST', '/authentication/getAccessToken', [], [
            'apiKey' => env('CJ_API_KEY', ''),
        ]);

        if ($res && isset($res['data']['accessToken'])) {
            $token = $res['data']['accessToken'];
            Cache::put('cj_access_token', $token, now()->addHours(23));
            return $token;
        }

        Log::error('CJ: Failed to get access token');
        return null;
    }

    // =========================================================
    // STORAGE / WAREHOUSES
    // =========================================================

    /**
     * Get CJ storage / warehouse list (GET)
     * https://developers.cjdropshipping.com/api2.0/v1/product/getStorageInfo
     */
    public function getStorageList(): array
    {
        $cached = Cache::get('cj_storage_list');
        if ($cached) return $cached;

        $res  = $this->request('GET', '/product/getStorageInfo');
        $data = $res['data'] ?? [];
        Cache::put('cj_storage_list', $data, now()->addHours(12));
        return $data;
    }

    /**
     * Get global warehouse list (used for shipping origin)
     */
    public function getWarehouses(): array
    {
        $cached = Cache::get('cj_warehouses');
        if ($cached) return $cached;

        $res  = $this->request('GET', '/product/globalWarehouseList');
        $data = $res['data'] ?? [];
        Cache::put('cj_warehouses', $data, now()->addHours(12));
        return $data;
    }

    // =========================================================
    // LOGISTICS / FREIGHT
    // =========================================================

    /**
     * Simple freight calculation (POST)
     * Returns available shipping methods with prices.
     *
     * @param string $startCountryCode  Origin country code e.g. 'CN'
     * @param string $endCountryCode    Destination country code e.g. 'US'
     * @param array  $products          [['vid'=>'...','quantity'=>1], ...]
     * @param string $zip               Optional destination zip
     */
    public function calculateFreight(
        string $startCountryCode,
        string $endCountryCode,
        array  $products,
        string $zip = ''
    ): array {
        $body = [
            'startCountryCode' => $startCountryCode,
            'endCountryCode'   => $endCountryCode,
            'products'         => $products,
        ];
        if ($zip) $body['zip'] = $zip;

        $res = $this->request('POST', '/logistic/freightCalculate', [], $body);
        return $res['data'] ?? [];
    }

    /**
     * Advanced freight calculation (freightCalculateTip) - more accurate
     *
     * @param string $srcAreaCode
     * @param string $destAreaCode
     * @param array  $freightTrialSkuList  [['sku'=>'...','skuQuantity'=>1], ...]
     * @param array  $skuList              ['SKU1','SKU2']
     * @param int    $weight               grams
     * @param float  $volume               cm³
     * @param array  $productProp          ['COMMON'] etc
     * @param string $zip
     */
    public function calculateFreightTip(
        string $srcAreaCode,
        string $destAreaCode,
        array  $freightTrialSkuList,
        array  $skuList,
        int    $weight,
        float  $volume,
        array  $productProp = ['COMMON'],
        string $zip = ''
    ): array {
        $req = [
            'srcAreaCode'         => $srcAreaCode,
            'destAreaCode'        => $destAreaCode,
            'freightTrialSkuList' => $freightTrialSkuList,
            'skuList'             => $skuList,
            'weight'              => $weight,
            'volume'              => $volume,
            'productProp'         => $productProp,
        ];
        if ($zip) $req['zip'] = $zip;

        $res = $this->request('POST', '/logistic/freightCalculateTip', [], ['reqDTOS' => [$req]]);
        return $res['data'] ?? [];
    }

    // =========================================================
    // PRODUCTS / CATEGORIES  (kept for completeness)
    // =========================================================

    public function getCategories(): array
    {
        $cached = Cache::get('cj_categories');
        if ($cached) return $cached;

        $res  = $this->request('GET', '/product/getCategory');
        $data = $res['data'] ?? [];
        Cache::put('cj_categories', $data, now()->addHours(6));
        return $data;
    }

    public function getProductList(array $filters = []): array
    {
        $params = array_merge(['pageNum' => 1, 'pageSize' => 20], $filters);
        $res    = $this->request('GET', '/product/list', $params);
        return $res['data'] ?? ['list' => [], 'total' => 0];
    }

    public function getTrendingProducts(int $limit = 10): array
    {
        $key    = "cj_trending_{$limit}";
        $cached = Cache::get($key);
        if ($cached) return $cached;

        $data = $this->getProductList(['searchType' => 2, 'pageSize' => $limit]);
        $list = $data['list'] ?? [];
        Cache::put($key, $list, now()->addMinutes(30));
        return $list;
    }

    public function getProductDetail(string $pid = '', string $productSku = '', array $features = []): ?array
    {
        $cacheKey = 'cj_product_' . md5($pid . $productSku);
        $cached   = Cache::get($cacheKey);
        if ($cached) return $cached;

        $params = [];
        if ($pid)        $params['pid']        = $pid;
        if ($productSku) $params['productSku'] = $productSku;
        if ($features)   $params['features']   = $features;

        $res  = $this->request('GET', '/product/query', $params);
        $data = $res['data'] ?? null;
        if ($data) Cache::put($cacheKey, $data, now()->addMinutes(60));
        return $data;
    }

    public function getVariants(string $pid, string $countryCode = ''): array
    {
        $params = ['pid' => $pid];
        if ($countryCode) $params['countryCode'] = $countryCode;
        $res = $this->request('GET', '/product/variant/query', $params);
        return $res['data'] ?? [];
    }

    public function getInventoryByPid(string $pid): array
    {
        $res = $this->request('GET', '/product/stock/getInventoryByPid', ['pid' => $pid]);
        return $res['data'] ?? [];
    }

    public function getReviews(string $pid, int $pageNum = 1, int $pageSize = 20): array
    {
        $res = $this->request('GET', '/product/productComments', [
            'pid'      => $pid,
            'pageNum'  => $pageNum,
            'pageSize' => $pageSize,
        ]);
        return $res['data'] ?? ['list' => [], 'total' => 0];
    }

    // =========================================================
    // SHOPPING — ORDERS
    // =========================================================

    /**
     * Create Order V2 (POST)
     *
     * payType options:
     *   1 = Page payment (returns cjPayUrl)
     *   2 = Balance payment (auto add-to-cart → confirm → pay)
     *   3 = Create only, no payment
     *
     * @param array $orderData {
     *   orderNumber, shippingZip, shippingCountry, shippingCountryCode,
     *   shippingProvince, shippingCity, shippingCustomerName, shippingAddress,
     *   shippingPhone, email, logisticName, fromCountryCode, payType,
     *   shopAmount, remark, houseNumber, platform,
     *   products: [['vid'=>'...','quantity'=>1,'storeLineItemId'=>'...']]
     * }
     */
    public function createOrder(array $orderData): ?array
    {
        // Inject platform token in header — handled via rawRequest headers
        $res = $this->request('POST', '/shopping/order/createOrderV2', [], $orderData, true);
        if ($res && isset($res['data'])) {
            return $res['data'];
        }
        Log::error('CJ createOrder failed', ['response' => $res]);
        return null;
    }

    /**
     * Add CJ order(s) to cart (step 2 of balance-pay flow)
     *
     * @param array $cjOrderIds  e.g. ['abc123', 'def456']
     */
    public function addOrderToCart(array $cjOrderIds): array
    {
        $res = $this->request('POST', '/shopping/order/addCart', [], ['cjOrderIdList' => $cjOrderIds]);
        return $res['data'] ?? [];
    }

    /**
     * Confirm cart (step 3 — generates shipment order + payId)
     *
     * @param array $cjOrderIds
     */
    public function confirmCart(array $cjOrderIds): array
    {
        $res = $this->request('POST', '/shopping/order/addCartConfirm', [], ['cjOrderIdList' => $cjOrderIds]);
        return $res['data'] ?? [];
    }

    /**
     * Save / generate parent order from shipment order ID (step 4)
     *
     * @param string $shipmentOrderId
     */
    public function saveGenerateParentOrder(string $shipmentOrderId): array
    {
        $res = $this->request('POST', '/shopping/order/saveGenerateParentOrder', [], [
            'shipmentOrderId' => $shipmentOrderId,
        ]);
        return $res['data'] ?? [];
    }

    /**
     * Get order list (GET)
     *
     * @param array $filters  pageNum, pageSize, status, beginDate, endDate, orderNumber, orderId
     */
    public function listOrders(array $filters = []): array
    {
        $params = array_merge(['pageNum' => 1, 'pageSize' => 20], $filters);
        $res    = $this->request('GET', '/shopping/order/list', $params);
        return $res['data'] ?? ['list' => [], 'total' => 0];
    }

    /**
     * Query single order detail (GET)
     *
     * @param string $orderId  CJ order ID
     */
    public function queryOrder(string $orderId): ?array
    {
        $res = $this->request('GET', '/shopping/order/getOrderDetail', ['orderId' => $orderId]);
        return $res['data'] ?? null;
    }

    /**
     * Confirm order after payment (PATCH)
     */
    public function confirmOrder(string $orderId): bool
    {
        $res = $this->request('PATCH', '/shopping/order/confirmOrder', [], ['orderId' => $orderId]);
        return ($res['result'] ?? false) === true;
    }

    /**
     * Delete / cancel order (DELETE)
     */
    public function deleteOrder(string $orderId): bool
    {
        $res = $this->request('DELETE', '/shopping/order/deleteOrder', ['orderId' => $orderId]);
        return ($res['result'] ?? false) === true;
    }

    // =========================================================
    // SHOPPING — PAYMENT
    // =========================================================

    /**
     * Get CJ account balance (GET)
     */
    public function getBalance(): array
    {
        $res = $this->request('GET', '/shopping/pay/getBalance');
        return $res['data'] ?? [];
    }

    /**
     * Pay with CJ balance V2 (POST)
     *
     * @param string $shipmentOrderId
     * @param string $payId            From saveGenerateParentOrder response
     */
    public function payBalance(string $shipmentOrderId, string $payId): ?array
    {
        $res = $this->request('POST', '/shopping/pay/payBalanceV2', [], [
            'shipmentOrderId' => $shipmentOrderId,
            'payId'           => $payId,
        ]);
        if ($res && ($res['result'] ?? false)) {
            return $res['data'] ?? [];
        }
        Log::error('CJ payBalance failed', ['response' => $res]);
        return null;
    }

    // =========================================================
    // TRACKING
    // =========================================================

    /**
     * Get tracking information by tracking number (v2, GET)
     *
     * @param string $trackNumber  Carrier tracking number
     */
    public function getTracking(string $trackNumber): ?array
    {
        $res = $this->request('GET', '/logistic/trackInfo', ['trackNumber' => $trackNumber]);
        return $res['data'] ?? null;
    }

    /**
     * Get tracking info by CJ order number (GET)
     */
    public function getTrackingByOrderNumber(string $orderNumber): ?array
    {
        $res = $this->request('GET', '/logistic/getTrackInfo', ['orderNumber' => $orderNumber]);
        return $res['data'] ?? null;
    }

    // =========================================================
    // FULL ORDER FULFILLMENT FLOW (balance-pay)
    // =========================================================

    /**
     * Full automated flow:
     *   createOrder → addCart → confirmCart → saveGenerateParentOrder → payBalance
     *
     * Use this after your Stripe payment succeeds.
     * Returns ['success' => bool, 'orderId' => ..., 'trackingNumber' => ..., ...]
     *
     * @param array $orderData   Same as createOrder()
     */
    public function fulfilOrderAfterPayment(array $orderData): array
    {
        try {
            // Step 1 — Create CJ order (payType=2 = balance)
            $orderData['payType'] = 2;
            $created = $this->createOrder($orderData);

            if (empty($created['orderId'])) {
                return ['success' => false, 'step' => 'create', 'message' => 'Failed to create CJ order'];
            }

            $cjOrderId = $created['orderId'];

            // Step 2 — Add to CJ cart
            $this->addOrderToCart([$cjOrderId]);

            // Step 3 — Confirm cart → get shipmentOrderId
            $confirmed = $this->confirmCart([$cjOrderId]);
            $shipmentOrderId = $confirmed['shipmentsId'] ?? $confirmed['shipmentOrderId'] ?? null;

            if (empty($shipmentOrderId)) {
                return ['success' => false, 'step' => 'confirm_cart', 'cj_order_id' => $cjOrderId,
                        'message' => 'Failed to get shipment order ID'];
            }

            // Step 4 — Save/generate parent order → get payId
            $saved = $this->saveGenerateParentOrder($shipmentOrderId);
            $payId = $saved['payId'] ?? null;

            if (empty($payId)) {
                return ['success' => false, 'step' => 'save_order', 'cj_order_id' => $cjOrderId,
                        'message' => 'Failed to get pay ID'];
            }

            // Step 5 — Pay with CJ balance
            $paid = $this->payBalance($shipmentOrderId, $payId);

            if ($paid === null) {
                return ['success' => false, 'step' => 'pay', 'cj_order_id' => $cjOrderId,
                        'message' => 'Balance payment failed'];
            }

            return [
                'success'          => true,
                'cj_order_id'      => $cjOrderId,
                'shipment_order_id'=> $shipmentOrderId,
                'pay_id'           => $payId,
                'order_data'       => $created,
            ];

        } catch (\Throwable $e) {
            Log::error('CJ fulfilOrderAfterPayment error: ' . $e->getMessage());
            return ['success' => false, 'step' => 'exception', 'message' => $e->getMessage()];
        }
    }

    // =========================================================
    // INTERNAL HTTP LAYER
    // =========================================================

    protected function request(
        string $method,
        string $endpoint,
        array  $query   = [],
        array  $body    = [],
        bool   $withPlatformToken = false
    ): ?array {
        $token = Cache::get('cj_access_token') ?? $this->getAccessToken();

        if (!$token && $endpoint !== '/authentication/getAccessToken') {
            Log::error("CJ: no access token for {$endpoint}");
            return null;
        }

        $result = $this->rawRequest($method, $endpoint, $query, $body, $token, $withPlatformToken);

        // Refresh token once on expiry
        if (isset($result['code']) && in_array($result['code'], [401, 1002])) {
            Cache::forget('cj_access_token');
            $token  = $this->getAccessToken();
            $result = $this->rawRequest($method, $endpoint, $query, $body, $token, $withPlatformToken);
        }

        return $result;
    }

    protected function rawRequest(
        string  $method,
        string  $endpoint,
        array   $query   = [],
        array   $body    = [],
        ?string $token   = null,
        bool    $withPlatformToken = false
    ): ?array {
        $url = $this->baseUrl . $endpoint;
        if (!empty($query) && in_array($method, ['GET', 'DELETE'])) {
            $url .= '?' . http_build_query($query);
        }

        $headers = ['Content-Type: application/json', 'Accept: application/json'];
        if ($token) $headers[] = 'CJ-Access-Token: ' . $token;
        if ($withPlatformToken) {
            $headers[] = 'platformToken: ' . env('CJ_PLATFORM_TOKEN', '');
        }

        $curl = curl_init();
        $opts = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER     => $headers,
        ];

        switch (strtoupper($method)) {
            case 'POST':
                $json = json_encode($body);
                $opts[CURLOPT_CUSTOMREQUEST] = 'POST';
                $opts[CURLOPT_POSTFIELDS]    = $json;
                $opts[CURLOPT_HTTPHEADER][]  = 'Content-Length: ' . strlen($json);
                break;
            case 'PATCH':
                $json = json_encode($body);
                $opts[CURLOPT_CUSTOMREQUEST] = 'PATCH';
                $opts[CURLOPT_POSTFIELDS]    = $json;
                $opts[CURLOPT_HTTPHEADER][]  = 'Content-Length: ' . strlen($json);
                break;
            case 'DELETE':
                $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                break;
            default:
                $opts[CURLOPT_CUSTOMREQUEST] = 'GET';
        }

        if ($this->certPath) {
            $opts[CURLOPT_CAINFO]         = $this->certPath;
            $opts[CURLOPT_SSL_VERIFYPEER] = true;
            $opts[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $opts[CURLOPT_SSL_VERIFYPEER] = false;
            $opts[CURLOPT_SSL_VERIFYHOST] = false;
        }

        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        $error    = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error("CJ cURL [{$endpoint}]: {$error}");
            return null;
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("CJ JSON decode [{$endpoint}]: " . substr($response, 0, 300));
            return null;
        }

        return $decoded;
    }
}