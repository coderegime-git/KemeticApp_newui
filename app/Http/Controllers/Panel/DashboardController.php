<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Comment;
use App\Models\Gift;
use App\Models\Meeting;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\Support;
use App\Models\Webinar;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\ReelSaved;
use App\Models\Order;
use App\Models\Affiliate;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Product;
use App\Models\Payout;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Registered;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $user = auth()->user();

        if (session()->has('user_just_registered') || $user->user_just_registered == '1') {
            
            // Ensure the tasks only run once
            $registeredUserId = session('user_just_registered');
            session()->forget('user_just_registered'); // Clear the flag immediately
            // update user_just_registered to 0
            $user->update(['user_just_registered' => 0]);

            // Verify the current logged-in user matches the registered ID (safety check)
            if ($user->id == $registeredUserId) {
                event(new Registered($user));

                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[u.role]' => trans("update.role_{$user->role_name}"),
                    '[time.date]' => dateTimeFormat($user->created_at, 'j M Y H:i'),
                ];
                sendNotification("new_registration", $notifyOptions, 1);

                // 2. Reward Accounting
                $registerReward = RewardAccounting::calculateScore(Reward::REGISTER);
                RewardAccounting::makeRewardAccounting($user->id, $registerReward, Reward::REGISTER, $user->id, true);

                // 3. Affiliate/Referral Storage
                $referralCode = session('referralCode', null); // Retrieve referral code saved during registration
                if (!empty($referralCode)) {
                    Affiliate::storeReferral($user, $referralCode);
                    session()->forget('referralCode'); // Clear referral code after use
                }

                // 4. Registration Bonus
                $registrationBonusAccounting = new RegistrationBonusAccounting();
                $registrationBonusAccounting->storeRegistrationBonusInstantly($user);
            }
        }

        $nextBadge = $user->getBadges(true, true);

        $data = [
            'pageTitle' => trans('panel.dashboard'),
            'nextBadge' => $nextBadge
        ];

        $userRole = $user->role->caption;
    
        // For all users
        $data['user_role'] = $userRole;
        $data['username'] = $user->full_name ?: $user->username;
        
        // Add payout summary for all users with earnings
        $data['payout_summary'] = $this->getPayoutSummary($user);

        if ($user->isUser()) {
            
            $data['seeker_data'] = [
                'continue_learning' => $this->getContinueLearningCount($user),
                'my_courses' => $this->getMyCoursesCount($user),
                'saved_reels' => $this->getSavedReelsCount($user),
                'orders' => $this->getOrdersCount($user),
                'membership' => [
                    'status' => $this->getMembershipStatus($user),
                    'price' => $this->getMembershipPrice($user)
                ],
                'messages' => $this->getMessagesCount($user),
                // Store detailed data for drawer
                'detailed_data' => [
                    'continue' => $this->getContinueLearningData($user),
                    'myCourses' => $this->getMyCoursesData($user),
                    'savedReels' => $this->getSavedReelsData($user),
                    'orders' => $this->getOrdersData($user),
                    'membership' => $this->getMembershipDetailedData($user),
                    'messages' => $this->getMessagesData($user),
                ]
            ];
        } elseif ($user->isTeacher() || $user->isOrganization()) {
            $data['creator_data'] = [
                'reel_studio' => $this->getReelStudioCount($user),
                'live_studio' => $this->getLiveStudioStatus($user),
                'creator_analytics' => $this->getCreatorAnalytics($user),
                'payouts' => $this->getPayoutsTotal($user),
                'detailed_data' => [
                    'reelStudio' => $this->getReelStudioData($user),
                    'liveStudio' => $this->getLiveStudioData($user),
                    'creatorAnalytics' => $this->getCreatorAnalyticsData($user),
                    'payouts' => $this->getPayoutsData($user),
                ]
            ];
            
            $data['keeper_data'] = [
                'courses' => $this->getInstructorCoursesCount($user),
                'students' => $this->getInstructorStudentsCount($user),
                'reel_studio' => $this->getReelStudioCount($user),
                'live_studio' => $this->getLiveStudioStatus($user),
                'products' => $this->getProductsCount($user),
                'vendor_orders' => $this->getVendorOrdersCount($user),
                'books' => $this->getBooksCount($user),
                'royalties' => $this->getRoyaltiesTotal($user),
                'analytics' => $this->getAnalyticsGrowth($user),
                'payouts' => $this->getTotalPayouts($user),
                'detailed_data' => [
                    'courses' => $this->getInstructorCoursesData($user),
                    'students' => $this->getInstructorStudentsData($user),
                    'reelStudio' => $this->getReelStudioData($user),
                    'liveStudio' => $this->getLiveStudioData($user),
                    'products' => $this->getProductsData($user),
                    'ordersVendor' => $this->getVendorOrdersData($user),
                    'books' => $this->getBooksData($user),
                    'royalties' => $this->getRoyaltiesData($user),
                    'keeperAnalytics' => $this->getAnalyticsDetailedData($user),
                    'payouts' => $this->getTotalPayoutsData($user),
                ]
            ];
        }

        $data['giftModal'] = $this->showGiftModal($user);
        return view(getTemplate() . '.panel.dashboard.index', $data);
    }

    // Payout Summary Method
    private function getPayoutSummary($user)
    {
        $totalEarnings = 0;
        $availableBalance = 0;
        $totalPayouts = 0;
        $pendingPayouts = 0;
        
        if ($user->isTeacher() || $user->isOrganization() || $user->isUser()) {
            // Calculate total earnings from sales where user is seller
            $totalEarnings = Sale::where('seller_id', $user->id)
                ->whereNull('refund_at')
                ->sum('total_amount');
            
            // Calculate payouts already made
            $payouts = Payout::where('user_id', $user->id)
                ->get();
            
            $totalPayouts = $payouts->where('status', 'paid')->sum('amount');
            $pendingPayouts = $payouts->where('status', 'pending')->sum('amount');
            
            // Available balance = total earnings - (paid payouts + pending payouts)
            $availableBalance = $totalEarnings - ($totalPayouts + $pendingPayouts);
            $availableBalance = max(0, $availableBalance); // Ensure non-negative
        }
        
        return [
            'total_earnings' => $this->formatPrice($totalEarnings),
            'available_balance' => $this->formatPrice($availableBalance),
            'total_payouts' => $this->formatPrice($totalPayouts),
            'pending_payouts' => $this->formatPrice($pendingPayouts),
            'raw' => [
                'total_earnings' => $totalEarnings,
                'available_balance' => $availableBalance,
                'total_payouts' => $totalPayouts,
                'pending_payouts' => $pendingPayouts,
            ]
        ];
    }

    // Updated Payout Methods
    private function getPayoutsTotal($user)
    {
        $summary = $this->getPayoutSummary($user);
        return $summary['total_earnings'];
    }

    private function getTotalPayouts($user)
    {
        $summary = $this->getPayoutSummary($user);
        return $summary['available_balance'];
    }

    private function getPayoutsData($user)
    {
        $payouts = Payout::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($payout) {
                return [
                    'id' => $payout->id,
                    'amount' => $this->formatPrice($payout->amount),
                    'method' => $payout->payout_method ?? 'Bank Transfer',
                    'status' => $payout->status,
                    'status_badge' => $this->getPayoutStatusBadge($payout->status),
                    'date' => date('Y-m-d H:i', $payout->created_at),
                    'processed_at' => $payout->paid_at ? date('Y-m-d H:i', $payout->paid_at) : null,
                ];
            })
            ->toArray();
        
        return $payouts;
    }

    private function getTotalPayoutsData($user)
    {
        $summary = $this->getPayoutSummary($user);
        $recentTransactions = Sale::where('seller_id', $user->id)
            ->whereNull('refund_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($sale) {
                return [
                    'type' => 'Earning',
                    'description' => $sale->webinar->title ?? 'Product Sale',
                    'amount' => $this->formatPrice($sale->total_amount),
                    'date' => date('Y-m-d H:i', $sale->created_at),
                    'status' => 'Completed',
                ];
            })
            ->toArray();
        
        return [
            'summary' => $summary,
            'recent_transactions' => $recentTransactions,
        ];
    }

    private function getPayoutStatusBadge($status)
    {
        $badges = [
            'pending' => 'warning',
            'paid' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
        ];
        
        return $badges[$status] ?? 'secondary';
    }

    // Books Methods
    private function getBooksCount($user)
    {
        return Book::where('creator_id', $user->id)
            // ->where('status', 'active')
            ->count();
    }

    private function getRoyaltiesTotal($user)
    {
        $totalRoyalties = Book::where('creator_id', $user->id)
            // ->where('status', 'active')
            ->sum('price') ?? 0;
        
        return $this->formatPrice($totalRoyalties);
    }

    private function getBooksData($user)
    {
        $books = Book::where('creator_id', $user->id)
            // ->where('status', 'active')
            ->with(['categories', 'creator' => function ($query) {
                $query->select('id', 'full_name');
            }])
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->creator->full_name ?? 'Unknown',
                    'categories' => $book->categories->pluck('slug')->implode(', '),
                    'price' => $this->formatPrice($book->price),
                    'royalties' => $this->formatPrice($book->royalty_earnings ?? 0),
                    'sales' => $book->sales_count ?? 0,
                    // 'rating' => $book->getRate(),
                    'status' => $book->status,
                    'created_at' => date('Y-m-d H:i', $book->created_at),
                    'url' => '/books/' . $book->slug,
                ];
            })
            ->toArray();
        
        return $books;
    }

    private function getRoyaltiesData($user)
    {
        $royaltyData = Book::where('creator_id', $user->id)
            // ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($book) {
                return [
                    'book_id' => $book->id,
                    'book_title' => $book->title,
                    'royalty_rate' => $book->royalty_rate . '%',
                    'earnings' => $this->formatPrice($book->royalty_earnings ?? 0),
                    'last_payout' => $book->last_payout_date ? date('Y-m-d H:i', $book->last_payout_date) : 'No payout yet',
                    'total_sales' => $book->sales_count ?? 0,
                ];
            })
            ->toArray();
        
        return $royaltyData;
    }

    // Products Methods
    private function getProductsCount($user)
    {
        return Product::where('creator_id', $user->id)
            ->where('status', 'active')
            ->count();
    }

    private function getProductsData($user)
    {
        $products = Product::where('creator_id', $user->id)
            ->where('status', 'active')
            ->with(['creator' => function ($query) {
                $query->select('id', 'full_name');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'type' => $product->type ?? 'Physical',
                    'price' => $this->formatPrice($product->price),
                    'inventory' => $product->inventory ?? 'N/A',
                    'sales' => $product->sales_count ?? 0,
                    'status' => $product->status,
                    'created_at' => date('Y-m-d H:i', $product->created_at),
                    'url' => '/products/' . $product->slug,
                ];
            })
            ->toArray();
        
        return $products;
    }

    // Vendor Orders Methods
    private function getVendorOrdersCount($user)
    {
        // Assuming Order has a vendor_id or creator_id field
         return Order::whereHas('orderItems', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', Order::$paid)
        ->count();
    }

    private function getVendorOrdersData($user)
    {
        $orders = Order::whereHas('orderItems', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', Order::$paid)
            ->with(['user', 'orderItems' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) use ($user) {
                // Filter orderItems to get only items created by this vendor
                $vendorItems = $order->orderItems->filter(function ($item) use ($user) {
                    return $item->user_id == $user->id;
                });
                
                // Get the first item to extract product/webinar info
                $firstItem = $vendorItems->first();
                $itemType = '';
                $itemTitle = '';
                
                if ($firstItem) {
                    if ($firstItem->webinar_id) {
                        $itemType = 'Course';
                        $itemTitle = $firstItem->webinar->title ?? 'Deleted Course';
                    } elseif ($firstItem->product_id) {
                        $itemType = 'Product';
                        $itemTitle = $firstItem->product->title ?? 'Deleted Product';
                    } elseif ($firstItem->bundle_id) {
                        $itemType = 'Bundle';
                        $itemTitle = $firstItem->bundle->title ?? 'Deleted Bundle';
                    }
                }
                
                $itemsList = $vendorItems->map(function ($item) {
                    if ($item->webinar_id) {
                        return $item->webinar->title ?? 'Course';
                    } elseif ($item->product_id) {
                        return $item->product->title ?? 'Product';
                    } elseif ($item->bundle_id) {
                        return $item->bundle->title ?? 'Bundle';
                    }
                    return 'Item';
                })->implode(', ');
                
                $totalAmount = $vendorItems->sum('total_amount');
                $totalQuantity = $vendorItems->sum('quantity');
                
                return [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number ?? 'N/A',
                    'customer' => $order->user->full_name ?? $order->user->username ?? 'Unknown',
                    'customer_email' => $order->user->email ?? '',
                    'items' => $itemsList,
                    'item_type' => $itemType,
                    'item_title' => $itemTitle,
                    'quantity' => $totalQuantity,
                    'total' => $this->formatPrice($totalAmount),
                    'date' => date('Y-m-d H:i', $order->created_at),
                    'status' => $order->status,
                    'payment_method' => $order->payment_method ?? 'N/A',
                ];
            })
            ->toArray();
        
        return $orders;
    }

    // Analytics Methods
    private function getAnalyticsGrowth($user)
    {
        // Calculate growth percentage based on previous month's earnings
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $currentMonthEarnings = Sale::where('seller_id', $user->id)
            ->whereNull('refund_at')
            ->whereBetween('created_at', [
                $currentMonth->timestamp,
                $currentMonth->copy()->endOfMonth()->timestamp
            ])
            ->sum('total_amount');
        
        $previousMonthEarnings = Sale::where('seller_id', $user->id)
            ->whereNull('refund_at')
            ->whereBetween('created_at', [
                $previousMonth->timestamp,
                $previousMonth->copy()->endOfMonth()->timestamp
            ])
            ->sum('total_amount');
        
        if ($previousMonthEarnings > 0) {
            $growth = (($currentMonthEarnings - $previousMonthEarnings) / $previousMonthEarnings) * 100;
            $growthFormatted = number_format($growth, 1);
            return ($growth >= 0 ? '↑ ' : '↓ ') . abs($growthFormatted) . '%';
        }
        
        return $currentMonthEarnings > 0 ? '↑ 100%' : '→ 0%';
    }

    private function getAnalyticsDetailedData($user)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        
        $monthlyData = [];
        $currentDate = $sixMonthsAgo->copy();
        
        while ($currentDate <= Carbon::now()) {
            $monthStart = $currentDate->copy()->startOfMonth();
            $monthEnd = $currentDate->copy()->endOfMonth();
            
            $monthEarnings = Sale::where('seller_id', $user->id)
                ->whereNull('refund_at')
                ->whereBetween('created_at', [$monthStart->timestamp, $monthEnd->timestamp])
                ->sum('total_amount');
            
            $monthlyData[] = [
                'month' => $currentDate->format('M Y'),
                'earnings' => $this->formatPrice($monthEarnings),
                'raw_earnings' => $monthEarnings,
                'sales_count' => Sale::where('seller_id', $user->id)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$monthStart->timestamp, $monthEnd->timestamp])
                    ->count(),
            ];
            
            $currentDate->addMonth();
        }
        
        // Top selling products/courses
        $topItems = Sale::where('seller_id', $user->id)
            ->whereNull('refund_at')
            ->select('webinar_id', 'product_order_id', \DB::raw('SUM(total_amount) as total_earnings'), \DB::raw('COUNT(*) as sales_count'))
            ->groupBy('webinar_id', 'product_order_id')
            ->orderBy('total_earnings', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $title = 'Unknown Item';
                if ($item->webinar_id) {
                    $webinar = Webinar::find($item->webinar_id);
                    $title = $webinar->title ?? 'Deleted Course';
                } elseif ($item->product_order_id) {
                    $product = Product::find($item->product_order_id);
                    $title = $product->title ?? 'Deleted Product';
                }
                
                return [
                    'title' => $title,
                    'total_earnings' => $this->formatPrice($item->total_earnings),
                    'sales_count' => $item->sales_count,
                ];
            })
            ->toArray();
        
        return [
            'monthly_earnings' => $monthlyData,
            'top_items' => $topItems,
            'summary' => $this->getPayoutSummary($user)['raw'],
        ];
    }

    // Other existing methods remain the same...
    private function getContinueLearningCount($user)
    {
        $webinarsIds = $user->getPurchasedCoursesIds();
        $count = 0;
        
        if (!empty($webinarsIds)) {
            foreach ($webinarsIds as $webinarId) {
                $webinar = Webinar::find($webinarId);
                if ($webinar && $webinar->getProgress() < 100) {
                    $count++;
                }
            }
        }
        
        return $count;
    }

    private function getMyCoursesCount($user)
    {
        $webinarsIds = $user->getPurchasedCoursesIds();
        return count($webinarsIds ?? []);
    }

    private function getSavedReelsCount($user)
    {
        return ReelSaved::where('user_id', $user->id)->count();
    }

    private function getOrdersCount($user)
    {
        return Order::where('user_id', $user->id)
            ->where('status', Order::$paid)
            ->count();
    }

    private function getMembershipStatus($user)
    {
        return 'Active';
    }

    private function getMembershipPrice($user)
    {
        return '€1/mo';
    }

    private function getMessagesCount($user)
    {
        return 0;
    }

    private function getInstructorCoursesCount($user)
    {
        return Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->count();
    }

    private function getInstructorStudentsCount($user)
    {
        $courses = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->get();
        
        $uniqueStudents = collect();
        
        foreach ($courses as $course) {
            $studentIds = $course->getStudentsIds();
            $uniqueStudents = $uniqueStudents->merge($studentIds);
        }
        
        return $uniqueStudents->unique()->count();
    }

    // Placeholder methods for other counts
    private function getReelStudioCount($user) { return '+ New'; }
    private function getLiveStudioStatus($user) { return 'Ready'; }
    private function getCreatorAnalytics($user) { return '0 views'; }

    // Detailed data methods
    private function getContinueLearningData($user)
    {
        $webinarsIds = $user->getPurchasedCoursesIds();
        $courses = [];
        
        if (!empty($webinarsIds)) {
            $courses = Webinar::whereIn('id', $webinarsIds)
                ->where('status', 'active')
                ->with(['creator'])
                ->get()
                ->map(function ($webinar) use ($user) {
                    $progress = $webinar->getProgress();
                    if ($progress < 100) {
                        return [
                            'id' => $webinar->id,
                            'title' => $webinar->title,
                            'instructor' => $webinar->creator->full_name ?? $webinar->creator->username,
                            'progress' => $progress,
                            'updated_at' => date('Y-m-d H:i', $webinar->updated_at),
                            'url' => $webinar->getLearningPageUrl(),
                        ];
                    }
                    return null;
                })
                ->filter()
                ->values()
                ->toArray();
        }
        
        return $courses;
    }

    private function getMyCoursesData($user)
    {
        $webinarsIds = $user->getPurchasedCoursesIds();
        $courses = [];
        
        if (!empty($webinarsIds)) {
            $purchases = Sale::where('buyer_id', $user->id)
                ->whereIn('webinar_id', $webinarsIds)
                ->where('type', 'webinar')
                ->whereNull('refund_at')
                ->get()
                ->keyBy('webinar_id');
            
            $courses = Webinar::whereIn('id', $webinarsIds)
                ->where('status', 'active')
                ->with(['creator'])
                ->get()
                ->map(function ($webinar) use ($purchases, $user) {
                    $purchase = $purchases->get($webinar->id);
                    return [
                        'id' => $webinar->id,
                        'title' => $webinar->title,
                        'instructor' => $webinar->creator->full_name ?? $webinar->creator->username,
                        'type' => $webinar->type == 'course' ? 'Course' : ($webinar->type == 'webinar' ? 'Webinar' : 'Text Lesson'),
                        'enrolled_at' => $purchase ? date('Y-m-d H:i', $purchase->created_at) : date('Y-m-d H:i', time()),
                        'progress' => $webinar->getProgress(),
                        'url' => $webinar->getLearningPageUrl(),
                    ];
                })
                ->toArray();
        }
        
        return $courses;
    }

    private function getSavedReelsData($user)
    {
        $reels = ReelSaved::where('user_id', $user->id)
            ->with(['reel.creator'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($savedReel) {
                $reel = $savedReel->reel;
                if (!$reel) return null;
                
                return [
                    'id' => $reel->id,
                    'title' => $reel->title ?? 'Untitled Reel',
                    'creator' => $reel->creator->full_name ?? $reel->creator->username ?? 'Unknown',
                    'saved_at' => date('Y-m-d H:i', $savedReel->created_at),
                    'views' => $reel->views ?? 0,
                    'duration' => $reel->duration ?? '0:00',
                ];
            })
            ->filter()
            ->values()
            ->toArray();
        
        return $reels;
    }

    private function getOrdersData($user)
    {
        $orders = Order::where('user_id', $user->id)
            ->where('status', Order::$paid)
            ->with(['orderItems'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($order) {
                $items = $order->orderItems->map(function ($item) {
                    return $item->title ?? 'Product';
                })->implode(', ');
                
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number ?? 'N/A',
                    'items' => $items,
                    'total' => $this->formatPrice($order->total_amount),
                    'date' => date('Y-m-d H:i', $order->created_at),
                    'status' => $order->status,
                ];
            })
            ->toArray();
        
        return $orders;
    }

    private function getMembershipDetailedData($user)
    {
        return [[
            'plan' => 'Basic',
            'cycle' => 'Monthly',
            'status' => 'Active',
            'renewal' => date('Y-m-d', strtotime('+1 month')),
            'price' => '€1/mo',
        ]];
    }

    private function getMessagesData($user)
    {
        return [];
    }

    private function getInstructorCoursesData($user)
    {
        $courses = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->withCount(['sales as enrollments_count' => function($query) {
                $query->whereNull('refund_at');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'type' => $course->type == 'course' ? 'Course' : ($course->type == 'webinar' ? 'Webinar' : 'Text Lesson'),
                    'price' => $this->formatPrice($course->price),
                    'enrollments' => $course->enrollments_count,
                    'rating' => $course->getRate(),
                    'updated_at' => date('Y-m-d H:i', $course->updated_at),
                    'url' => $course->getUrl(),
                ];
            })
            ->toArray();
        
        return $courses;
    }

    private function getInstructorStudentsData($user)
    {
        $courses = Webinar::where(function ($query) use ($user) {
                if ($user->isTeacher()) {
                    $query->where('teacher_id', $user->id);
                } elseif ($user->isOrganization()) {
                    $query->where('creator_id', $user->id);
                }
            })
            ->where('status', 'active')
            ->get();
        
        $students = collect();
        
        foreach ($courses as $course) {
            $studentIds = $course->getStudentsIds();
            
            foreach ($studentIds as $studentId) {
                $student = User::find($studentId);
                if ($student) {
                    $progress = $course->getProgress($studentId);
                    
                    $students->push([
                        'id' => $student->id,
                        'name' => $student->full_name ?? $student->username,
                        'email' => $student->email,
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'progress' => $progress,
                        'enrolled_at' => date('Y-m-d H:i', $course->created_at),
                        'last_access' => date('Y-m-d H:i', $student->last_access_at ?? time()),
                    ]);
                }
            }
        }
        
        return $students->unique('id')->values()->toArray();
    }

    // Placeholder detailed data methods
    private function getReelStudioData($user) { return []; }
    private function getLiveStudioData($user) { return []; }
    private function getCreatorAnalyticsData($user) { return []; }

    private function showGiftModal($user)
    {
        $gift = Gift::query()->where('email', $user->email)
            ->where('status', 'active')
            ->where('viewed', false)
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->first();

        if (!empty($gift)) {
            $gift->update([
                'viewed' => true
            ]);

            $data = [
                'gift' => $gift
            ];

            $result = (string)view()->make('web.default.panel.dashboard.gift_modal', $data);
            $result = str_replace(array("\r\n", "\n", "  "), '', $result);

            return $result;
        }

        return null;
    }

    private function getMonthlySalesOrPurchase($user)
    {
        $months = [];
        $data = [];

        // all 12 months
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(date('Y'), $month);

            $start_date = $date->timestamp;
            $end_date = $date->copy()->endOfMonth()->timestamp;

            $months[] = trans('panel.month_' . $month);

            if (!$user->isUser()) {
                $monthlySales = Sale::where('seller_id', $user->id)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->sum('total_amount');

                $data[] = round($monthlySales, 2);
            } else {
                $monthlyPurchase = Sale::where('buyer_id', $user->id)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->count();

                $data[] = $monthlyPurchase;
            }
        }

        return [
            'months' => $months,
            'data' => $data
        ];
    }

    private function formatPrice($amount, $currency = null, $decimals = 2)
    {
        if (!$currency) {
            $currency = config('app.currency', '€');
        }
        
        $formatted = number_format((float) $amount, $decimals);
        
        if (strpos($currency, '€') !== false || 
            strpos($currency, '$') !== false || 
            strpos($currency, '£') !== false) {
            return $currency . $formatted;
        } else {
            return $formatted . ' ' . $currency;
        }
    }
}