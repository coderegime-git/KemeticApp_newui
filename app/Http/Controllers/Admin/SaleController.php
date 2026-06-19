<?php

namespace App\Http\Controllers\Admin;

use App\Exports\salesExport;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\BookOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\SaleLog;
use App\Models\Webinar;
use App\Models\Book;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin_sales_list');

        $query = Sale::whereNull('product_order_id')->whereNull('book_order_id');

        $totalSales = [
            'count' => deepClone($query)->count(),
            'amount' => deepClone($query)->sum('total_amount'),
        ];

        $classesSales = [
            'count' => deepClone($query)->whereNotNull('webinar_id')->count(),
            'amount' => deepClone($query)->whereNotNull('webinar_id')->sum('total_amount'),
        ];
        $appointmentSales = [
            'count' => deepClone($query)->whereNotNull('meeting_id')->count(),
            'amount' => deepClone($query)->whereNotNull('meeting_id')->sum('total_amount'),
        ];
        $failedSales = Order::where('status', Order::$fail)->count();

        // Include book and product sales in the full query for listing
        $fullQuery = Sale::query();
        $salesQuery = $this->getSalesFilters($fullQuery, $request);

        $sales = $salesQuery->orderBy('created_at', 'desc')
            ->with([
                'buyer',
                'webinar',
                'meeting',
                'subscribe',
                'promotion',
                'bookOrder.book',
                'productOrder.product',
            ])
            ->paginate(10);
        try {
            foreach ($sales as $sale) {
                $sale = $this->makeTitle($sale);

                if (empty($sale->saleLog)) {
                    SaleLog::create([
                        'sale_id' => $sale->id,
                        'viewed_at' => time()
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
        // dd($sales);

        $data = [
            'pageTitle' => trans('admin/pages/financial.sales_page_title'),
            'sales' => $sales,
            'totalSales' => $totalSales,
            'classesSales' => $classesSales,
            'appointmentSales' => $appointmentSales,
            'failedSales' => $failedSales,
        ];

        $teacher_ids = $request->get('teacher_ids');
        $student_ids = $request->get('student_ids');
        $webinar_ids = $request->get('webinar_ids');

        if (!empty($teacher_ids)) {
            $data['teachers'] = User::select('id', 'full_name')
                ->whereIn('id', $teacher_ids)->get();
        }

        if (!empty($student_ids)) {
            $data['students'] = User::select('id', 'full_name')
                ->whereIn('id', $student_ids)->get();
        }

        if (!empty($webinar_ids)) {
            $data['webinars'] = Webinar::select('id')
                ->whereIn('id', $webinar_ids)->get();
        }

        return view('admin.financial.sales.lists', $data);
    }

    private function makeTitle($sale)
    {
        // Book sale
        if (!empty($sale->book_order_id) && !empty($sale->bookOrder)) {
            $book = $sale->bookOrder->book;
            $sale->item_title = $book ? $book->title : trans('update.deleted_item');
            $sale->item_id    = $book ? $book->id : '';
            $sale->item_seller = ($book && $book->creator) ? $book->creator->full_name : trans('update.deleted_item');
            $sale->seller_id  = ($book && $book->creator) ? $book->creator->id : '';
            $sale->sale_type  = 'book';
            return $sale;
        }

        // Product sale
        if (!empty($sale->product_order_id) && !empty($sale->productOrder)) {
            $product = $sale->productOrder->product;
            $sale->item_title = $product ? $product->title : trans('update.deleted_item');
            $sale->item_id    = $product ? $product->id : '';
            $sale->item_seller = ($product && $product->creator) ? $product->creator->full_name : trans('update.deleted_item');
            $sale->seller_id  = ($product && $product->creator) ? $product->creator->id : '';
            $sale->sale_type  = 'product';
            return $sale;
        }

        if (!empty($sale->webinar_id) or !empty($sale->bundle_id)) {
            $item = !empty($sale->webinar_id) ? $sale->webinar : $sale->bundle;

            $sale->item_title = $item ? $item->title : trans('update.deleted_item');
            $sale->item_id = $item ? $item->id : '';
            $sale->item_seller = ($item and $item->creator) ? $item->creator->full_name : trans('update.deleted_item');
            $sale->seller_id = ($item and $item->creator) ? $item->creator->id : '';
            $sale->sale_type = ($item and $item->creator) ? $item->creator->id : '';
        } elseif (!empty($sale->meeting_id)) {
            $sale->item_title = trans('panel.meeting');
            $sale->item_id = $sale->meeting_id;
            $sale->item_seller = ($sale->meeting and $sale->meeting->creator) ? $sale->meeting->creator->full_name : trans('update.deleted_item');
            $sale->seller_id = ($sale->meeting and $sale->meeting->creator) ? $sale->meeting->creator->id : '';
        } elseif (!empty($sale->subscribe_id)) {
            $sale->item_title = !empty($sale->subscribe) ? $sale->subscribe->title : trans('update.deleted_subscribe');
            $sale->item_id = $sale->subscribe_id;
            $sale->item_seller = 'Admin';
            $sale->seller_id = '';
        } elseif (!empty($sale->promotion_id)) {
            $sale->item_title = !empty($sale->promotion) ? $sale->promotion->title : trans('update.deleted_promotion');
            $sale->item_id = $sale->promotion_id;
            $sale->item_seller = 'Admin';
            $sale->seller_id = '';
        } elseif (!empty($sale->registration_package_id)) {
            $sale->item_title = !empty($sale->registrationPackage) ? $sale->registrationPackage->title : 'Deleted registration Package';
            $sale->item_id = $sale->registration_package_id;
            $sale->item_seller = 'Admin';
            $sale->seller_id = '';
        } elseif (!empty($sale->gift_id) and !empty($sale->gift)) {
            $gift = $sale->gift;
            $item = !empty($gift->webinar_id) ? $gift->webinar : (!empty($gift->bundle_id) ? $gift->bundle : $gift->product);

            $sale->item_title = $gift->getItemTitle();
            $sale->item_id = $item->id;
            $sale->item_seller = $item->creator->full_name;
            $sale->seller_id = $item->creator_id;
        } elseif (!empty($sale->installment_payment_id) and !empty($sale->installmentOrderPayment)) {
            $installmentOrderPayment = $sale->installmentOrderPayment;
            $installmentOrder = $installmentOrderPayment->installmentOrder;
            $installmentItem = $installmentOrder->getItem();

            $sale->item_title = !empty($installmentItem) ? $installmentItem->title : '--';
            $sale->item_id = !empty($installmentItem) ? $installmentItem->id : '--';
            $sale->item_seller = !empty($installmentItem) ? $installmentItem->creator->full_name : '--';
            $sale->seller_id = !empty($installmentItem) ? $installmentItem->creator->id : '--';
        } else {
            $sale->item_title = '---';
            $sale->item_id = '---';
            $sale->item_seller = '---';
            $sale->seller_id = '';
        }

        return $sale;
    }

    private function getSalesFilters($query, $request)
    {
        $item_title = $request->get('item_title');
        $from = $request->get('from');
        $to = $request->get('to');
        $status = $request->get('status');
        $webinar_ids = $request->get('webinar_ids', []);
        $teacher_ids = $request->get('teacher_ids', []);
        $student_ids = $request->get('student_ids', []);
        $userIds = array_merge($teacher_ids, $student_ids);

        if (!empty($item_title)) {
            $ids = Webinar::whereTranslationLike('title', "%$item_title%")->pluck('id')->toArray();
            $webinar_ids = array_merge($webinar_ids, $ids);
        }

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($status)) {
            if ($status == 'success') {
                $query->whereNull('refund_at');
            } elseif ($status == 'refund') {
                $query->whereNotNull('refund_at');
            } elseif ($status == 'blocked') {
                $query->where('access_to_purchased_item', false);
            }
        }

        if (!empty($webinar_ids) and count($webinar_ids)) {
            $query->whereIn('webinar_id', $webinar_ids);
        }

        if (!empty($userIds) and count($userIds)) {
            $query->where(function ($query) use ($userIds) {
                $query->whereIn('buyer_id', $userIds);
                $query->orWhereIn('seller_id', $userIds);
            });
        }

        return $query;
    }

    public function refund($id)
    {
        $this->authorize('admin_sales_refund');

        $sale = Sale::findOrFail($id);

        if ($sale->type == Sale::$subscribe) {
            $salesWithSubscribe = Sale::whereNotNull('webinar_id')
                ->where('buyer_id', $sale->buyer_id)
                ->where('subscribe_id', $sale->subscribe_id)
                ->whereNull('refund_at')
                ->with('webinar', 'subscribe')
                ->get();

            foreach ($salesWithSubscribe as $saleWithSubscribe) {
                $saleWithSubscribe->update([
                    'refund_at' => time()
                ]);

                if (!empty($saleWithSubscribe->webinar) and !empty($saleWithSubscribe->subscribe)) {
                    Accounting::refundAccountingForSaleWithSubscribe($saleWithSubscribe->webinar, $saleWithSubscribe->subscribe);
                }
            }
        }

        if (!empty($sale->total_amount)) {
            Accounting::refundAccounting($sale);
        }

        if (!empty($sale->meeting_id) and $sale->type == Sale::$meeting) {
            $appointment = ReserveMeeting::where('meeting_id', $sale->meeting_id)
                ->where('sale_id', $sale->id)
                ->first();

            if (!empty($appointment)) {
                $appointment->update([
                    'status' => ReserveMeeting::$canceled
                ]);
            }
        }

        $sale->update(['refund_at' => time()]);

        return back();
    }

    public function invoice($id)
    {
        $this->authorize('admin_sales_invoice');

        $sale = Sale::where('id', $id)
            ->with([
                'order',
                'buyer' => function ($query) {
                    $query->select('id', 'full_name');
                },
                'webinar' => function ($query) {
                    $query->with([
                        'teacher' => function ($query) {
                            $query->select('id', 'full_name');
                        },
                        'creator' => function ($query) {
                            $query->select('id', 'full_name');
                        },
                        'webinarPartnerTeacher' => function ($query) {
                            $query->with([
                                'teacher' => function ($query) {
                                    $query->select('id', 'full_name');
                                },
                            ]);
                        }
                    ]);
                },
                'bundle'
            ])
            ->first();

        if (!empty($sale)) {
            $webinar = $sale->webinar;

            if (empty($webinar) and !empty($sale->bundle)) {
                $webinar = $sale->bundle;
            }

            if (!empty($webinar)) {
                $data = [
                    'pageTitle' => trans('webinars.invoice_page_title'),
                    'sale' => $sale,
                    'webinar' => $webinar
                ];

                return view('admin.financial.sales.invoice', $data);
            }
        }

        abort(404);
    }

    /**
     * View Book Sale Details — price breakdown for Wisdom Keeper/Platform
     */
    public function viewBook($id)
    {
        $this->authorize('admin_sales_list');

        $sale = Sale::where('id', $id)
            ->where('type', Sale::$book)
            ->with([
                'buyer',
                'bookOrder' => function ($q) {
                    $q->with([
                        'book' => function ($q) {
                            $q->with('creator');
                        },
                        'seller',
                        'buyer',
                    ]);
                },
            ])
            ->firstOrFail();

        $bookOrder = $sale->bookOrder;
        $book      = $bookOrder ? $bookOrder->book : null;

        abort_if(empty($book), 404);

        // ── Price breakdown ────────────────────────────────────────────────
        $totalAmount = (float) $sale->total_amount;
        $tax         = (float) $sale->tax;
        $discount    = (float) $sale->discount;

        // $bookType = $book->type ?? 'ebook';
        $rawType  = $book->type ?? 'ebook';
        $bookType = match(strtolower(trim($rawType))) {
            'print'                  => 'print',
            'e-book', 'ebook'        => 'ebook',
            'audio book', 'audio'    => 'audio',
            default                  => strtolower(trim($rawType)),
        };

        $sellingPrice  = (float) ($book->getRawOriginal('price')          ?? 0);
        $bookPrice     = (float) ($book->getRawOriginal('book_price')     ?? 0);
        $shippingPrice = (float) ($book->getRawOriginal('shipping_price') ?? 0);
        $printPrice    = (float) ($book->getRawOriginal('print_price')    ?? 0);
        $platformPrice = (float) ($book->getRawOriginal('platform_price') ?? 0);
        

        // All prices live on the Book model
        // $bookPrice     = (float) ($book->price          ?? 0);  // 40.00 — full selling price
        // $shippingPrice = (float) ($book->shipping_price ?? 0);  // 14.85
        // $printPrice    = (float) ($book->print_price    ?? 0);  // 9.20
        // $platformPrice = (float) ($book->platform_price ?? 0);  // 3.64 — stored platform fee

        // Commission % — use platform_price if set, else calculate from getCommission()
        $commissionPct = (float) $book->getCommission();
        if ($platformPrice > 0 && $bookPrice > 0) {
            // Derive real commission % from stored platform_price
            $commissionPct = round($platformPrice / $bookPrice * 100, 2);
        }

        $platformAmount = $platformPrice > 0
            ? $platformPrice
            : round($totalAmount * $commissionPct / 100, 2);

        $earningAmount = round($totalAmount - $platformAmount - $tax - $shippingPrice - $printPrice, 2);
        $earningAmount = max(0, $earningAmount);

        // Delivery status from BookOrder
        $deliveryStatuses = [
            BookOrder::$pending         => ['label' => 'Pending',           'color' => 'warning'],
            BookOrder::$waitingDelivery => ['label' => 'Waiting Delivery',  'color' => 'info'],
            BookOrder::$shipped         => ['label' => 'Shipped',           'color' => 'primary'],
            BookOrder::$success         => ['label' => 'Delivered',         'color' => 'success'],
            BookOrder::$canceled        => ['label' => 'Canceled',          'color' => 'danger'],
        ];
        $currentStatus = $bookOrder->status ?? BookOrder::$pending;

        $data = [
            'pageTitle'       => 'Book Sale Detail — #' . $sale->id,
            'sale'            => $sale,
            'bookOrder'       => $bookOrder,
            'book'            => $book,
            'bookType'        => $bookType,
            'totalAmount'     => $totalAmount,
            'tax'             => $tax,
            'discount'        => $discount,
            'commissionPct'   => $commissionPct,
            'platformAmount'  => $platformAmount,
            'earningAmount'   => $earningAmount,
            'bookPrice'       => $bookPrice,
            'printPrice'      => $printPrice,
            'platformPrice'   => $platformPrice,
            'shippingPrice'   => $shippingPrice,
            'deliveryStatuses'=> $deliveryStatuses,
            'currentStatus'   => $currentStatus,
        ];

        return view('admin.financial.sales.view_book', $data);
    }

    /**
     * View Product Sale Details — price breakdown for CJ vs non-CJ, physical vs virtual
     */
    public function viewProduct($id)
    {
        $this->authorize('admin_sales_list');

        $sale = Sale::where('id', $id)
            ->where('type', Sale::$product)
            ->with([
                'buyer',
                'productOrder' => function ($q) {
                    $q->with([
                        'product' => function ($q) {
                            $q->with(['creator', 'cjVariants', 'category']);
                        },
                        'seller',
                        'buyer',
                    ]);
                },
            ])
            ->firstOrFail();

        $productOrder = $sale->productOrder;
        $product      = $productOrder ? $productOrder->product : null;

        abort_if(empty($product), 404);

        // ── CJ variant detection ───────────────────────────────────────────
        $isCj          = $productOrder->is_cj_product ?? false;
        $cjSpecs       = $productOrder->cj_specifications ?? [];
        $selectedVid   = $cjSpecs['vid'] ?? null;
        $cjVariant     = null;

        if ($isCj && $selectedVid) {
            $cjVariant = $product->cjVariants->firstWhere('vid', $selectedVid);
        }

        // ── Price breakdown ────────────────────────────────────────────────
        $totalAmount    = (float) $sale->total_amount;
        $tax            = (float) $sale->tax;
        $discount       = (float) $sale->discount;
        $shippingFee    = (float) ($sale->product_delivery_fee ?? 0);

        $commissionPct  = (float) $product->getCommission();

        // Base price: CJ variant sell_price or product price
        $basePrice = $isCj && $cjVariant
            ? (float) $cjVariant->sell_price
            : (float) ($product->price ?? 0);

        // Apply active discount to base price for display
        $discountedBase  = $isCj && $cjVariant
            ? $basePrice          // CJ price is already final
            : (float) $product->getPriceWithActiveDiscountPrice();

        $platformAmount  = round($totalAmount * $commissionPct / 100, 2);
        $earningAmount   = round($totalAmount - $platformAmount - $tax - $shippingFee, 2);

        // Delivery status
        $deliveryStatuses = [
            ProductOrder::$pending         => ['label' => 'Pending',          'color' => 'warning'],
            ProductOrder::$waitingDelivery => ['label' => 'Waiting Delivery',  'color' => 'info'],
            ProductOrder::$shipped         => ['label' => 'Shipped',           'color' => 'primary'],
            ProductOrder::$success         => ['label' => 'Delivered',         'color' => 'success'],
            ProductOrder::$canceled        => ['label' => 'Canceled',          'color' => 'danger'],
        ];
        $currentStatus = $productOrder->status ?? ProductOrder::$pending;

        $data = [
            'pageTitle'       => 'Product Sale Detail — #' . $sale->id,
            'sale'            => $sale,
            'productOrder'    => $productOrder,
            'product'         => $product,
            'isCj'            => $isCj,
            'cjVariant'       => $cjVariant,
            'cjSpecs'         => $cjSpecs,
            'isPhysical'      => $product->isPhysical(),
            'basePrice'       => $basePrice,
            'discountedBase'  => $discountedBase,
            'totalAmount'     => $totalAmount,
            'tax'             => $tax,
            'discount'        => $discount,
            'shippingFee'     => $shippingFee,
            'commissionPct'   => $commissionPct,
            'platformAmount'  => $platformAmount,
            'earningAmount'   => $earningAmount,
            'deliveryStatuses'=> $deliveryStatuses,
            'currentStatus'   => $currentStatus,
        ];

        return view('admin.financial.sales.view_product', $data);
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('admin_sales_export');

        $query = Sale::query();

        $salesQuery = $this->getSalesFilters($query, $request);

        $sales = $salesQuery->orderBy('created_at', 'desc')
            ->with([
                'buyer',
                'webinar',
                'meeting',
                'subscribe',
                'promotion',
                'bookOrder.book',
                'productOrder.product',
            ])
            ->get();

        foreach ($sales as $sale) {
            $sale = $this->makeTitle($sale);
        }

        $export = new salesExport($sales);

        return Excel::download($export, 'sales.xlsx');
    }
}