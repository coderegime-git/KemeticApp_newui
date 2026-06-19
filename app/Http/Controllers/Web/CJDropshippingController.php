use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Translation\ProductTranslation;
use App\Services\CJDropshippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CJDropshippingController extends Controller
{
    public function __construct(protected CJDropshippingService $cjService) {}

    public function index(Request $request)
    {
        $pageNum = $request->get('page', 1);
        $search = $request->get('search');
        $categoryId = $request->get('category_id');

        $filters = [
            'pageNum' => $pageNum,
            'pageSize' => 20,
            'productNameEn' => $search,
            'categoryId' => $categoryId,
        ];

        $result = $this->cjService->getProductList($filters);
        $products = $result['list'] ?? [];
        $total = $result['total'] ?? 0;

        // Fetch categories for the filter sidebar
        $categories = ProductCategory::whereNull('parent_id')
            ->with(['subCategories' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->get();

        // Also fetch CJ categories if needed for a more precise filter
        $cjCategories = $this->cjService->getCategories();

        return view(getTemplate() . '.products.cj_search', [
            'pageTitle' => 'Dropshipping Products',
            'products' => $products,
            'total' => $total,
            'pageNum' => $pageNum,
            'productCategories' => $categories,
            'cjCategories' => $cjCategories,
            'search' => $search,
            'selectedCategory' => $categoryId,
        ]);
    }

    public function show($vid)
    {
        // Use getProductDetail which handles the correct endpoint (/product/details or /product/query)
        $productData = $this->cjService->getProductDetail($vid);

        if (!$productData) {
            abort(404, 'Product not found on CJ Dropshipping');
        }

        return view(getTemplate() . '.products.cj_show', [
            'pageTitle' => $productData['productNameEn'] ?? 'Product Details',
            'product' => $productData,
        ]);
    }

    public function importAndAddToCart(Request $request, $vid)
    {
        $user = auth()->user();
        
        // 1. Fetch details from CJ using the service
        $cjProduct = $this->cjService->getProductDetail($vid);

        if (!$cjProduct) {
            return back()->with(['toast' => ['status' => 'error', 'msg' => 'Failed to fetch product from CJ']]);
        }

        // 2. Check if already imported
        $product = Product::where('cj_vid', $vid)->first();

        if (!$product) {
            DB::beginTransaction();
            try {
                // 3. Create local product (simplified import)
                $product = Product::create([
                    'creator_id' => 1, // Store Owner / Admin
                    'type' => 'physical',
                    'slug' => Product::makeSlug($cjProduct['productNameEn']),
                    'cj_vid' => $vid,
                    'is_cj_product' => true,
                    'status' => 'active',
                    'price' => $cjProduct['sellPrice'] ?? 0,
                    'thumbnail' => $cjProduct['productImage'] ?? '',
                    'category_id' => $request->get('category_id', 1), 
                    'ordering' => true,
                    'created_at' => time(),
                    'updated_at' => time(),
                ]);

                // Save translations
                ProductTranslation::updateOrCreate([
                    'product_id' => $product->id,
                    'locale' => 'en',
                ], [
                    'title' => $cjProduct['productNameEn'],
                    'description' => $cjProduct['description'] ?? '',
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with(['toast' => ['status' => 'error', 'msg' => 'Failed to import product: ' . $e->getMessage()]]);
            }
        }

        // 4. Add to cart logic (similar to CartManagerController@storeUserProductCart)
        // We use a POST request or manual DB entry to avoid redirect issues
        $quantity = $request->get('quantity', 1);
        
        if ($user) {
            $cartController = new CartManagerController();
            $cartData = [
                'item_id' => $product->id,
                'item_name' => 'product_id',
                'quantity' => $quantity,
            ];
            
            $result = $cartController->storeUserProductCart($user, $cartData, false);
            
            if ($result === 'ok') {
                return redirect()->route('cart')->with(['toast' => ['status' => 'success', 'msg' => trans('cart.cart_add_success_msg')]]);
            } else {
                return back()->with(['toast' => ['status' => 'error', 'msg' => 'Failed to add to cart']]);
            }
        } else {
            // Handle guest cart if needed, or redirect to login
            return redirect()->route('login')->with(['toast' => ['status' => 'info', 'msg' => 'Please login to add items to cart']]);
        }
    }
}
