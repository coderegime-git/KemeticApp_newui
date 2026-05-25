<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CJDropshippingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * CJ Dropshipping Product Controller
 *
 * Routes to add in web.php:
 *
 *   // Product listing
 *   Route::get('/cj-products',              [CJProductController::class, 'index'])->name('cj.products.index');
 *   Route::get('/cj-products/{pid}',        [CJProductController::class, 'show'])->name('cj.products.show');
 *
 *   // AJAX / API helpers used by the views
 *   Route::get('/api/cj/products',          [CJProductController::class, 'apiList']);
 *   Route::get('/api/cj/products/{pid}',    [CJProductController::class, 'apiDetail']);
 *   Route::get('/api/cj/variants/{pid}',    [CJProductController::class, 'apiVariants']);
 *   Route::get('/api/cj/inventory/{pid}',   [CJProductController::class, 'apiInventory']);
 *   Route::get('/api/cj/reviews/{pid}',     [CJProductController::class, 'apiReviews']);
 *   Route::get('/api/cj/categories',        [CJProductController::class, 'apiCategories']);
 *   Route::post('/api/cj/add-to-mine',      [CJProductController::class, 'addToMyProducts']);
 */
class CJProductController extends Controller
{
    public function __construct(protected CJDropshippingService $cj) {}

    // ─────────────────────────────────────────────────────────────
    // PRODUCT LISTING PAGE
    // ─────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user            = auth()->user();
        $activeSubscribe = $user ? \App\Models\Subscribe::getActiveSubscribe($user->id) : null;

        $pageSize        = 20;
        $maxPage         = (int) floor(6000 / $pageSize); // 300
        $page            = max(1, min((int) $request->get('page', 1), $maxPage));

        // Build filters from request
        $filters = array_filter([
            'pageNum'      => $page,
            'pageSize'     => $pageSize,
            'productNameEn'=> $request->get('search'),
            'categoryId'   => $request->get('category_id'),
            'countryCode'  => $request->get('country', ''),
            'minPrice'     => $request->get('min_price'),
            'maxPrice'     => $request->get('max_price'),
            'isFreeShipping' => $request->get('free_shipping') === 'on' ? 1 : null,
            'searchType'   => (int) $request->get('search_type', 0),
            'sort'         => $request->get('sort', 'desc'),
            'orderBy'      => $request->get('order_by', 'createAt'),
        ]);

        $result          = $this->cj->getProductList($filters);
        $products        = $result['list']  ?? [];
        $total           = $result['total'] ?? 0;
        $currentPage     = $result['pageNum']  ?? 1;
        $totalPages      = $total > 0 ? ceil(min($total, 6000) / $pageSize) : 1;

        // Trending sidebar (cached)
        $trendingProducts = $this->cj->getTrendingProducts(6);

        // Categories for filter pills
        $categories = $this->cj->getCategories();

        $selectedCategoryId = $request->get('category_id');

        return view('web.default.cj_products.index', compact(
            'products', 'total', 'currentPage', 'pageSize', 'totalPages',
            'trendingProducts', 'categories', 'selectedCategoryId',
            'user', 'activeSubscribe'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // SINGLE PRODUCT DETAIL PAGE
    // ─────────────────────────────────────────────────────────────

    public function show(string $pid)
    {
        $user            = auth()->user();
        $activeSubscribe = $user ? \App\Models\Subscribe::getActiveSubscribe($user->id) : null;

        // Full detail with description, inventory, video
        $product = $this->cj->getProductDetail(
            pid: $pid,
            features: ['enable_description', 'enable_inventory', 'enable_video']
        );

        if (!$product) {
            abort(404, 'CJ product not found');
        }

        // Reviews (first page)
        $reviewsData = $this->cj->getReviews($pid, 1, 10);
        $reviews     = $reviewsData['list']  ?? [];
        $reviewTotal = $reviewsData['total'] ?? 0;

        // Compute avg rating
        $avgRating = 0;
        if (!empty($reviews)) {
            $avgRating = round(array_sum(array_column($reviews, 'score')) / count($reviews), 1);
        }

        // Inventory per warehouse
        $inventoryData = $this->cj->getInventoryByPid($pid);

        return view('web.default.cj_products.show', compact(
            'product', 'reviews', 'reviewTotal', 'avgRating',
            'inventoryData', 'user', 'activeSubscribe'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // JSON API ENDPOINTS  (used by front-end AJAX)
    // ─────────────────────────────────────────────────────────────

    public function apiList(Request $request): JsonResponse
    {
        $pageSize       = min(100, max(1, (int) $request->get('size', 20)));
        $maxPage        = (int) floor(6000 / $pageSize);
        $page           = max(1, min((int) $request->get('page', 1), $maxPage));

        $filters = array_filter([
            'pageNum'       => $page,
            'pageSize'      => $pageSize,
            'productNameEn' => $request->get('search'),
            'categoryId'    => $request->get('category_id'),
            'countryCode'   => $request->get('country'),
            'minPrice'      => $request->get('min_price'),
            'maxPrice'      => $request->get('max_price'),
            'isFreeShipping'=> $request->get('free_shipping'),
            'searchType'    => $request->get('search_type'),
            'sort'          => $request->get('sort', 'desc'),
            'orderBy'       => $request->get('order_by', 'createAt'),
        ]);

        $result = $this->cj->getProductList($filters);
        return response()->json(['success' => true, 'data' => $result]);
    }

    public function apiDetail(string $pid): JsonResponse
    {
        $product = $this->cj->getProductDetail(
            pid: $pid,
            features: ['enable_description', 'enable_inventory', 'enable_video']
        );

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $product]);
    }

    public function apiVariants(string $pid, Request $request): JsonResponse
    {
        $variants = $this->cj->getVariants(pid: $pid, countryCode: $request->get('country', ''));
        return response()->json(['success' => true, 'data' => $variants]);
    }

    public function apiInventory(string $pid): JsonResponse
    {
        $inventory = $this->cj->getInventoryByPid($pid);
        return response()->json(['success' => true, 'data' => $inventory]);
    }

    public function apiReviews(string $pid, Request $request): JsonResponse
    {
        $reviews = $this->cj->getReviews(
            $pid,
            (int) $request->get('page', 1),
            (int) $request->get('size', 20),
            $request->get('score') ? (int) $request->get('score') : null
        );
        return response()->json(['success' => true, 'data' => $reviews]);
    }

    public function apiCategories(): JsonResponse
    {
        $categories = $this->cj->getCategories();
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function addToMyProducts(Request $request): JsonResponse
    {
        $this->validate($request, ['product_id' => 'required|string']);

        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Login required'], 401);
        }

        $added = $this->cj->addToMyProducts($request->input('product_id'));
        return response()->json([
            'success' => $added,
            'message' => $added ? 'Added to your products' : 'Could not add product',
        ]);
    }
}