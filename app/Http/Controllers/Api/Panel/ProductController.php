<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Api\Product;
use App\Models\Api\Comment;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Translation\ProductTranslation;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\ProductSpecification;
use App\Models\Api\Webinar;
use App\Models\ProductCategory;
use App\Models\ProductMedia;
use App\Models\ProductSelectedFilterOption;
use App\Models\ProductSpecificationCategory;
use App\Models\RelatedCourse;
use App\Models\ProductSelectedSpecification;
use App\Models\ProductSelectedSpecificationMultiValue;
use App\Models\Translation\ProductSelectedSpecificationTranslation;
use App\Models\ProductFaq;
use App\Models\Translation\ProductFaqTranslation;
use App\Models\ProductSpecificationMultiValue;
use App\Http\Resources\ProductOrderResource;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = apiAuth();

        if ((!$user->isTeacher() and !$user->isOrganization()) or !$user->checkCanAccessToStore()) {
            abort(403);
        }

        $query = Product::where('creator_id', $user->id);
        $physicalProducts = deepClone($query)->where('type', Product::$physical)->count();

        $virtualProducts = deepClone($query)->where('type', Product::$virtual)->count();

        $totalPhysicalSales = deepClone($query)->where('products.type', Product::$physical)
            ->join('product_orders', 'products.id', 'product_orders.product_id')
            ->leftJoin('sales', function ($join) {
                $join->on('product_orders.id', '=', 'sales.product_order_id')
                    ->whereNull('sales.refund_at');
            })
            ->select(DB::raw('sum(sales.total_amount) as total_sales'))
            ->whereNotNull('product_orders.sale_id')
            ->whereNotIn('product_orders.status', [ProductOrder::$canceled, ProductOrder::$pending])
            ->first();

        $totalVirtualSales = deepClone($query)->where('products.type', Product::$virtual)
            ->join('product_orders', 'products.id', 'product_orders.product_id')
            ->leftJoin('sales', function ($join) {
                $join->on('product_orders.id', '=', 'sales.product_order_id')
                    ->whereNull('sales.refund_at');
            })
            ->select(DB::raw('sum(sales.total_amount) as total_sales'))
            ->whereNotNull('product_orders.sale_id')
            ->whereNotIn('product_orders.status', [ProductOrder::$canceled, ProductOrder::$pending])
            ->first();


        $products = deepClone($query)
            ->orderBy('created_at', 'desc')
            ->get();

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'products' => $products,
                'physical_products_count' => $physicalProducts,
                'virtual_products_count' => $virtualProducts,
                'physical_products_sale' => (float) $totalPhysicalSales->total_sales ?? 0,
                'virtual_products_sale' => (float) $totalVirtualSales->total_sales ?? 0,
            ]
        );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //khushboo 28-11-24
        $user = apiAuth();

        if ((!$user->isTeacher() and !$user->isOrganization()) or !$user->checkCanAccessToStore()) {
            abort(403);
        }

        $userPackage = new UserPackage();
        $userCoursesCountLimited = $userPackage->checkPackageLimit('product_count');

        // print_r($userPackage);die;
        if ($userCoursesCountLimited) {
            session()->put('registration_package_limited', $userCoursesCountLimited);
            return apiResponse2(0, 'Package Limit Exceed');
            // return redirect()->back();
        }

        validateParam($request->all(), [
            'type' => 'required|in:' . implode(',', Product::$productTypes),
            'title' => 'required|max:255',
            'seo_description' => 'required|max:255',
            'summary' => 'required',
            'description' => 'required',
            'locale' => 'required',
            'inventory' => 'required_without:unlimited_inventory',
            'category_id' => 'required',
            'thumbnail' => 'required',
            'images' => 'required|array',
            'termsandrules' => 'required|in:1'
        ]);

        $data = $request->all();

        $data['unlimited_inventory'] = (!empty($data['unlimited_inventory']) and $data['unlimited_inventory'] == 1) ? 1 : 0;

        $data['price'] = !empty($data['price']) ? convertPriceToDefaultCurrency((int) $data['price']) : null;
        $data['delivery_fee'] = !empty($data['delivery_fee']) ? convertPriceToDefaultCurrency((int) $data['delivery_fee']) : null;

        $product = Product::create([
            'creator_id' => $user->id,
            'type' => $data['type'],
            'slug' => Product::makeSlug($data['title']),
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'unlimited_inventory' => $data['unlimited_inventory'],
            'ordering' => !empty($data['ordering']) and (int) $data['ordering'] == 1 ?? "on",
            'inventory' => isset($data['inventory']) ? (int) $data['inventory'] : null,
            'inventory_warning' => isset($data['inventory_warning']) ? (int) $data['inventory_warning'] : null,
            'inventory_updated_at' => null,
            'delivery_fee' => $data['delivery_fee'],
            'delivery_estimated_time' => $data['delivery_estimated_time'] ?? null,
            'message_for_reviewer' => $data['message_for_reviewer'] ?? null,
            'status' => (!empty($data['draft']) and $data['draft'] == 1) ? Product::$draft : Product::$pending,
            'updated_at' => time(),
            'created_at' => time(),
        ]);

        if ($product) {

            ProductTranslation::updateOrCreate([
                'product_id' => $product->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'seo_description' => $data['seo_description'],
                'summary' => $data['summary'],
                'description' => $data['description'],
            ]);

            $inventory = $data['inventory'];
            $productAvailability = $product->getAvailability();

            if ($inventory != $productAvailability) {
                $data1['inventory_updated_at'] = time();

                Product::where('id', $product->id)->update($data1);
            }


            if (!empty($data['thumbnail'])) {
                $file = $data['thumbnail'];
                $thumbnailImage = $file->getClientOriginalName();
                
                // Define the upload path
                $uploadPath = public_path('assets/store/1/products');

                // Ensure the directory exists
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Move the uploaded file to the public directory
                $file->move($uploadPath, $thumbnailImage);
                // print_r($thumbnailImage);die;
                ProductMedia::updateOrCreate([
                    'creator_id' => $user->id,
                    'product_id' => $product->id,
                    'type' => ProductMedia::$thumbnail,
                ], [
                    'path' => '/assets/store/1/products/' . $thumbnailImage,
                    'created_at' => time(),
                ]);
 
            }


            if (!empty($data['images'])) {

                // ProductMedia::where('creator_id', $user->id)
                //     ->where('product_id', $product->id)
                //     ->where('type', ProductMedia::$imageName)
                //     ->delete();
                $uploadedFiles = $data['images'];

                if ($uploadedFiles) {
                    foreach ($uploadedFiles as $file) {
                        // $imageNames[] = $file->getClientOriginalName();
                        
                        $Image = $file->getClientOriginalName();
                       
                        $uploadPath = public_path('assets/store/1/products');

                        // Ensure the directory exists
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }

                        // Move the uploaded file to the public directory
                        $file->move($uploadPath, $Image);
                        ProductMedia::create([
                            'creator_id' => $user->id,
                            'product_id' => $product->id,
                            'type' => ProductMedia::$image,
                            'path' => '/assets/store/1/products/' . $Image,
                            'created_at' => time(),
                        ]);
                       
                    }
                }

            }

            if (!empty($data['video_demo'])) {
                $file = $data['video_demo'];
                $video = $file->getClientOriginalName();

                ProductMedia::updateOrCreate([
                    'creator_id' => $user->id,
                    'product_id' => $product->id,
                    'type' => ProductMedia::$video,
                ], [
                    'path' => '/assets/store/1/products/' . $video,
                    'created_at' => time(),
                ]);

                $uploadPath = public_path('assets/store/1/products');
                // Ensure the directory exists
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Move the uploaded file to the directory
                $file->move($uploadPath, $video);

            }

            if (!empty($data['courses'])) {
                $courseData = json_decode($data['courses']);
                foreach ($courseData as $val) {
                    RelatedCourse::query()->updateOrCreate([
                        'creator_id' => $user->id,
                        'targetable_id' => $product->id,
                        'targetable_type' => "App\Models\Product",
                        'course_id' => (int) $val
                    ]);
                }
            }

            if (!empty($data['specifications'])) {
                $specificationData = json_decode($data['specifications']);

                foreach ($specificationData as $Val) {

                    // validateParam($Val, [
                    //     'input_type' => 'required|in:' . implode(',', ProductSpecification::$inputTypes),
                    //     'specification_id' => 'required|exists:product_specifications,id',
                    //     // 'multi_values' => 'required_if:input_type,multi_value',
                    //     'summary' => 'required_if:input_type,textarea',
                    // ]);

                    // echo '<pre>';
                    // print_r($Val->specification_id);die;
                    $selectedSpecification = ProductSelectedSpecification::create([
                        'creator_id' => $user->id,
                        'product_id' => $product->id,
                        'product_specification_id' => (int) $Val->specification_id,
                        'type' => $Val->input_type,
                        'allow_selection' => (!empty($Val->allow_selection) and (int) $Val->allow_selection == 1) ?? "on",
                        'order' => null,
                        'status' => (!empty($Val->status) and (int) $Val->status == 1) ? ProductSelectedSpecification::$Active : ProductSelectedSpecification::$Inactive,
                        'created_at' => time(),
                    ]);


                    if (!empty($selectedSpecification)) {
                        if ($Val->input_type == 'multi_value') {

                            $multiValues = $Val->multi_values;
                            ProductSelectedSpecificationMultiValue::where('selected_specification_id', $selectedSpecification->id)->delete();

                            if (!empty($multiValues) and !is_array($multiValues)) {
                                $multiValues = [$multiValues];
                            }

                            if (!empty($multiValues) and is_array($multiValues)) {
                                foreach ($multiValues as $multiValue) {
                                    ProductSelectedSpecificationMultiValue::create([
                                        'selected_specification_id' => $selectedSpecification->id,
                                        'specification_multi_value_id' => $multiValue
                                    ]);
                                }
                            }
                        } else if (!empty($Val->summary)) {
                            ProductSelectedSpecificationTranslation::updateOrCreate([
                                'locale' => mb_strtolower($Val->locale),
                                'product_selected_specification_id' => $selectedSpecification->id
                            ], [
                                'value' => $Val->summary
                            ]);
                        }
                    }

                }

                if (!empty($data['faqs'])) {
                    $faqData = json_decode($data['faqs']);

                    foreach ($faqData as $val) {

                        // validateParam($val, [
                        //     'title' => 'required|max:255',
                        //     'answer' => 'required',
                        // ]);

                        $faq = ProductFaq::create([
                            'creator_id' => $user->id,
                            'product_id' => $product->id,
                            'order' => null,
                            'created_at' => time(),
                        ]);

                        if (!empty($faq)) {
                            ProductFaqTranslation::updateOrCreate([
                                'product_faq_id' => $faq->id,
                                'locale' => mb_strtolower($val->locale),
                            ], [
                                'title' => $val->title,
                                'answer' => $val->answer,
                            ]);
                        }
                    }
                }
            }
        }

        if ($data['termsandrules'] && $data['termsandrules'] == 1) {
            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[item_title]' => $product->title,
                '[content_type]' => trans('update.product'),
            ];
            sendNotification("new_item_created", $notifyOptions, 1);
        }

        return apiResponse2(1, 'stored', 'Product Stored Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where('creator_id', apiAuth()->id)
            ->where('id', $id)->get();
        if (!$product) {
            // abort(404);
        }

    }

    public function purchasedComment()
    {
        $comments = Comment::where('user_id', apiAuth()->id)
            ->whereNotNull('product_id')
            ->handleFilters()->orderBy('created_at', 'desc')->get()->map(function ($comment) {
                return $comment->details;
            });

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'comments' => $comments,

            ]
        );

    }

    public function myComments(Request $request)
    {
        $user = apiAuth();

        $query = Comment::where('status', 'active')
            ->whereNotNull('product_id')
            ->whereHas('product', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            });

        $repliedCommentsCount = deepClone($query)->whereNotNull('reply_id')->count();

        $comments = $query->handleFilters()->orderBy('created_at', 'desc')
            ->get();

        foreach ($comments->whereNull('viewed_at') as $comment) {
            $comment->update([
                'viewed_at' => time()
            ]);
        }
        $comments = $comments->map(function ($comment) {
            return $comment->details;
        });

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'comments_count' => $comments->count(),
                'replied_comment_count' => $repliedCommentsCount,
                'comments' => $comments,

            ]
        );

    }

    //khushboo 28-11-24
    public function getSpecifications($categoryId)
    {

        $specificationIds = ProductSpecificationCategory::where('category_id', $categoryId)
            ->pluck('specification_id')
            ->toArray();

        $specifications = ProductSpecification::whereIn('id', $specificationIds)
            ->get();

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            $specifications,
        );
    }

    public function getSpecificationParameters($id)
    {

        $specification = ProductSpecification::where('id', $id)
            ->first();

        if (!empty($specification)) {
            $multiValues = ProductSpecificationMultiValue::where('specification_id', $specification->id)->get();

            $data = [
                'multiValues' => $multiValues
            ];

            return response()->json($data);
        }

        abort(404);
    }

    public function getCourses()
    {
        // die('jkj');
        $webinars = Webinar::where('webinars.status', 'active')
            ->with([
                "badges" => function ($query) {
                    $query->where('targetable_type', 'App\Models\Webinar');
                    $query->with([
                        'badge' => function ($query) {
                            $time = time();
                            $query->where('enable', true);

                            $query->where(function ($query) use ($time) {
                                $query->whereNull('start_at');
                                $query->orWhere('start_at', '<', $time);
                            });

                            $query->where(function ($query) use ($time) {
                                $query->whereNull('end_at');
                                $query->orWhere('end_at', '>', $time);
                            });
                        }
                    ]);
                },
            ])
            ->whereHas('teacher', function ($query) {
                $query->where('status', 'active')
                    ->where(function ($query) {
                        $query->where('ban', false)
                            ->orWhere(function ($query) {
                                $query->whereNotNull('ban_end_at')
                                    ->where('ban_end_at', '<', time());
                            });
                    });
            })
            ->where('private', false)
            ->get();

        $arr = [];
        foreach ($webinars as $web) {
            $data['id'] = $web->id;
            $data['title'] = $web->title;
            $arr[] = $data;
        }

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $arr);
    }

    public function ongoingOrders()
    {

        $user = apiAuth();
        if (!$user) {
            abort(403);
        }

        $query = ProductOrder::where('product_orders.buyer_id', $user->id)
            ->where('product_orders.status', '!=', 'success')
            ->whereHas('sale', function ($query) {
                $query->where('type', 'product');
                $query->where('access_to_purchased_item', true);
                $query->whereNull('refund_at');
            });

        $pendingOrders = deepClone($query)->where(function ($query) {
            $query->where('status', ProductOrder::$waitingDelivery)
                ->orWhere('status', ProductOrder::$shipped)
                ->orWhere('status', ProductOrder::$pending);

        })->orderBy('created_at', 'desc')
            ->get();


        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'orders' => ProductOrderResource::collection($pendingOrders),
            ]
        );
    }
}
