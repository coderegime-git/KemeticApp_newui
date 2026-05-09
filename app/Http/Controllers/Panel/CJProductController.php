<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\CJDropshippingService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCjVariant;

class CJProductController extends Controller
{
    protected $cjService;

    public function __construct(CJDropshippingService $cjService)
    {
        $this->cjService = $cjService;
    }

    /**
     * Display a listing of CJ items.
     */
    public function index(Request $request)
    {
        $selectedCategoryId = $request->get('category_id');
        $search = $request->get('search');
        $page = $request->get('page', 1);
        $size = 8;

        $categories = $this->cjService->getCategories();
        $productsData = $this->cjService->getProductList([
            'categoryId' => $selectedCategoryId,
            'productNameEn' => $search,
            'pageNum' => $page,
            'pageSize' => $size,
        ]);

        $trendingProducts = $this->cjService->getTrendingProducts(10);

        return view('web.default.panel.cj_products.index', [
            'pageTitle' => 'CJ Dropshipping Products',
            'categories' => $categories,
            'products' => $productsData['list'] ?? [],
            'total' => $productsData['total'] ?? 0,
            'totalPages' => isset($productsData['total']) ? ceil($productsData['total'] / $size) : 1,
            'currentPage' => $page,
            'selectedCategoryId' => $selectedCategoryId,
            'trendingProducts' => $trendingProducts
        ]);
    }

    /**
     * Show details of a single CJ item.
     */
    public function show($pid)
    {
        $product = $this->cjService->getProductDetail($pid);
        
        if (empty($product)) {
            abort(404, 'CJ product not found');
        }

        // Get local product and saved variants if imported
        $localProduct = Product::where('cj_vid', $pid)->first();
        $savedVariants = [];
        if ($localProduct) {
            $savedVariants = ProductCjVariant::where('product_id', $localProduct->id)->pluck('vid')->toArray();
        }

        // Get shipping/inventory info
        $inventoryData = $this->cjService->getInventoryByPid($pid);
        
        // Average rating and reviews (mocked or fetched if service has it)
        $avgRating   = $product['productRating'] ?? 4.5;
        $reviewTotal = $product['reviewCount'] ?? 10;
        $reviews     = []; // CJ API doesn't usually return reviews in detail call

        return view('web.default.panel.cj_products.show', [
            'pageTitle'     => $product['productNameEn'] ?? 'Product Detail',
            'product'       => $product,
            'inventoryData' => $inventoryData,
            'avgRating'     => $avgRating,
            'reviewTotal'   => $reviewTotal,
            'reviews'       => $reviews,
            'localProduct'  => $localProduct,
            'savedVariants' => $savedVariants
        ]);
    }

    /**
     * Save variants for a CJ Product
     */
    public function saveVariants(Request $request, $pid)
    {
        $localProduct = Product::where('cj_vid', $pid)->first();
        if (!$localProduct) {
            return back()->with('toast', [
                'title' => 'Error',
                'msg' => 'Please "Add to Product" first before saving variants.',
                'status' => 'error'
            ]);
        }

        $selectedVids = $request->input('variants', []);
        
        $productDetail = $this->cjService->getProductDetail($pid);
        if (empty($productDetail) || empty($productDetail['variants'])) {
            return back()->with('toast', [
                'title' => 'Error',
                'msg' => 'CJ API error or no variants found.',
                'status' => 'error'
            ]);
        }

        // Delete currently saved variants to re-insert the chosen ones
        ProductCjVariant::where('product_id', $localProduct->id)->delete();

        $shipping = (float)($localProduct->cj_shipping_price ?? 0);
        $earning = (float)($localProduct->cj_your_price ?? 0);

        foreach ($productDetail['variants'] as $v) {
            if (in_array($v['vid'], $selectedVids)) {
                $cjVPrice = (float)($v['variantSellPrice'] ?? $v['sellPrice'] ?? 0);
                // Calculate final selling price
                $variantSellPrice = ceil(($cjVPrice + $shipping + $earning) / 0.9);

                ProductCjVariant::create([
                    'product_id' => $localProduct->id,
                    'cj_pid' => $pid,
                    'vid' => $v['vid'],
                    'variant_name' => $v['variantNameEn'] ?? $v['variantKey'] ?? 'Variant',
                    'variant_key' => $v['variantKey'] ?? '',
                    'variant_sku' => $v['variantSku'] ?? '',
                    'sell_price' => $variantSellPrice,
                    'variant_image' => $v['variantImage'] ?? null,
                    'is_selected' => true,
                    'created_at' => time(),
                    'updated_at' => time(),
                ]);
            }
        }

        return back()->with('toast', [
            'title' => 'Success',
            'msg' => 'Variants saved successfully.',
            'status' => 'success'
        ]);
    }

    /**
     * Order tracking search (if needed in panel)
     */
    public function tracking()
    {
        return view('web.default.panel.cj_products.cj_tracking', [
            'pageTitle' => 'Order Tracking'
        ]);
    }
}
