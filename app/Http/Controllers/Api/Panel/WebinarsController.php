<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\PurchaseResource;
use App\Mixins\Cashback\CashbackRules;
use App\Models\Api\Sale;
use App\Models\Api\Webinar;
use App\Models\Api\Gift;
use App\Models\WebinarPartnerTeacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\Models\WebinarExtraDescription;
use App\Models\Ticket;
use App\Models\Translation\TicketTranslation;
use App\Models\Translation\WebinarExtraDescriptionTranslation;
use App\Models\Quiz;
use App\Models\Translation\QuizTranslation;


class WebinarsController extends Controller
{
    public function show($id)
    {
        $user = apiAuth();

        $webinar = Webinar::query()->where('id', $id)
            ->where(function (Builder $query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($webinar)) {
            $cashbackRules = null;

            $data = $webinar->brief;

            if (!empty($data["price"]) and getFeaturesSettings('cashback_active') and (empty($user) or !$user->disable_cashback)) {
                $cashbackRulesMixin = new CashbackRules($user);
                $cashbackRules = $cashbackRulesMixin->getRules('courses', $data["id"], $data["type"], null, null);
            }

            $data["cashbackRules"] = $cashbackRules;

            return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
        }

        return apiResponse2(0, 'invalid', trans('api.public.invalid'));
    }

    public function list(Request $request, $id = null)
    {
        return [
            'my_classes' => $this->myClasses($request),
            'purchases' => $this->purchases($request),
            'organizations' => $this->organizations($request),
            'invitations' => $this->invitations($request),
        ];
    }

    public function myClasses(Request $request)
    {
        $user = apiAuth();

        $webinars = Webinar::where(function ($query) use ($user) {

            if ($user->isTeacher()) {
                $query->where('teacher_id', $user->id);
            } elseif ($user->isOrganization()) {
                $query->where('creator_id', $user->id);
            }
        })->handleFilters()->orderBy('updated_at', 'desc')->get()->map(function ($webinar) {
            return $webinar->brief;
        });

        return $webinars;
    }

    public function indexPurchases()
     {
            return apiResponse2(
                1,
                'retrieved',
                trans('api.public.retrieved'),
                [
                    'purchases' => $this->purchases()
                ]
            );
        }
    
        public function free(Request $request, $id)
        {
            $user = apiAuth();
    
            $course = Webinar::where('id', $id)
                ->where('status', 'active')
                ->first();
            abort_unless($course, 404);
    
    
            $checkCourseForSale = $course->checkCourseForSale($user);
    
            if ($checkCourseForSale != 'ok') {
                return apiResponse2(0, $checkCourseForSale, trans('api.course.purchase.' . $checkCourseForSale));
            }
    
            if (!empty($course->price) and $course->price > 0) {
                return apiResponse2(0, 'not_free', trans('api.cart.not_free'));
    
    
            }
    
            Sale::create([
                'buyer_id' => $user->id,
                'seller_id' => $course->creator_id,
                'webinar_id' => $course->id,
                'type' => Sale::$webinar,
                'payment_method' => Sale::$credit,
                'amount' => 0,
                'total_amount' => 0,
                'created_at' => time(),
            ]);
    
            return apiResponse2(1, 'enrolled', trans('api.webinar.enrolled'));
    
        }
    
        public function purchases()
        
        {
        try {
            $user = apiAuth();
            
            if (!$user) {
                       return response()->json([
                    'debug' => 'User is null',
                    'token' => request()->bearerToken(),
                    'success' => false,
                    'status' => 'unauthorized',
                    'message' => trans('api.public.unauthorized')
                ], 401);
            }
    
            $giftsIds = Gift::query()->where('email', $user->email)
                ->where('status', 'active')
                ->whereNull('product_id')
                ->where(function ($query) {
                    $query->whereNull('date');
                    $query->orWhere('date', '<', time());
                })
                ->whereHas('sale')
                ->pluck('id')
                ->toArray();
    
            $query = Sale::query()
                ->where(function ($query) use ($user, $giftsIds) {
                    $query->where('sales.buyer_id', $user->id);
                    $query->orWhereIn('sales.gift_id', $giftsIds);
                })
                ->whereNull('sales.refund_at')
                ->where('access_to_purchased_item', true)
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->whereNotNull('sales.webinar_id')
                            ->where('sales.type', 'webinar')
                            ->whereHas('webinar', function ($query) {
                                $query->where('status', 'active');
                            });
                    });
                    $query->orWhere(function ($query) {
                        $query->whereNotNull('sales.bundle_id')
                            ->where('sales.type', 'bundle')
                            ->whereHas('bundle', function ($query) {
                                $query->where('status', 'active');
                            });
                    });
                    $query->orWhere(function ($query) {
                        $query->whereNotNull('gift_id');
                        $query->whereHas('gift');
                    });
                });
    
            $sales = $query
                ->with([
                    'webinar' => function ($query) {
                        $query->with([
                            'files',
                            'reviews' => function ($query) {
                                $query->where('status', 'active');
                            },
                            'category',
                            'teacher' => function ($query) {
                                $query->select('id', 'full_name');
                            },
                        ]);
                        $query->withCount([
                            'sales' => function ($query) {
                                $query->whereNull('refund_at');
                            }
                        ]);
                    },
                    'bundle' => function ($query) {
                        $query->with([
                            'reviews' => function ($query) {
                                $query->where('status', 'active');
                            },
                            'category',
                            'teacher' => function ($query) {
                                $query->select('id', 'full_name');
                            },
                        ]);
                    },
                    'gift' => function ($query) {
                        $query->with(['webinar', 'bundle', 'receipt']);
                    },
                    'buyer' => function ($query) {
                        $query->select('id', 'full_name');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->get();
    
            $time = time();
    
            foreach ($sales as $sale) {
                try {
                    $purchaseDate = $sale->created_at->timestamp ?? time();
    
                    if (!empty($sale->gift_id) && !empty($sale->gift)) {
                        $gift = $sale->gift;
    
                        $purchaseDate = $gift->date ?? $purchaseDate;
    
                        $sale->webinar_id = $gift->webinar_id ?? null;
                        $sale->bundle_id = $gift->bundle_id ?? null;
    
                        $sale->webinar = !empty($gift->webinar_id) ? $gift->webinar : null;
                        $sale->bundle = !empty($gift->bundle_id) ? $gift->bundle : null;
    
                        $sale->gift_recipient = !empty($gift->receipt) ? $gift->receipt->full_name : ($gift->name ?? null);
                        $sale->gift_sender = $sale->buyer->full_name ?? null;
                        $sale->gift_date = $gift->date ?? null;
                    }
    
                    // Ensure we have valid objects before accessing properties
                    if (!empty($sale->webinar) && is_object($sale->webinar)) {
                        $accessDays = $sale->webinar->access_days ?? 0;
                        if ($accessDays > 0) {
                            $expiredAt = strtotime("+{$accessDays} days", $purchaseDate);
                            $sale->expired = $expiredAt < $time;
                            $sale->expired_at = $expiredAt;
                        } else {
                            $sale->expired = false;
                            $sale->expired_at = null;
                        }
                    } else if (!empty($sale->bundle) && is_object($sale->bundle)) {
                        $accessDays = $sale->bundle->access_days ?? 0;
                        if ($accessDays > 0) {
                            $expiredAt = strtotime("+{$accessDays} days", $purchaseDate);
                            $sale->expired = $expiredAt < $time;
                            $sale->expired_at = $expiredAt;
                        } else {
                            $sale->expired = false;
                            $sale->expired_at = null;
                        }
                    } else {
                        $sale->expired = false;
                        $sale->expired_at = null;
                    }
                } catch (\Exception $e) {
                    // Skip problematic sales records
                    \Log::warning('Error processing sale record: ' . $e->getMessage());
                    $sale->expired = false;
                    $sale->expired_at = null;
                }
            }
    
            return PurchaseResource::collection($sales);
    
        } catch (\Exception $e) {
            \Log::error('Purchases endpoint error: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            
            // Return empty collection instead of failing
            return PurchaseResource::collection(collect([]));
            
            // Or return error response
            // return apiResponse2(0, 'server_error', trans('api.public.server_error'));
        }
    }

    public function invitations(Request $request)
    {
        $user = apiAuth();

        $invitedWebinarIds = WebinarPartnerTeacher::where('teacher_id', $user->id)->pluck('webinar_id')->toArray();
        $webinars = Webinar::where('status', 'active')
            ->whereIn('id', $invitedWebinarIds)
            ->handleFilters()
            ->orderBy('updated_at', 'desc')->get()->map(function ($webinar) {
                return $webinar->brief;
            });

        return $webinars;
    }

    public function organizations()
    {
        $user = apiAuth();
        // $user=User::find(927) ;

        $webinars = Webinar::where('creator_id', $user->organ_id)
            ->where('status', 'active')->handleFilters()
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')->get()->map(function ($webinar) {
                return $webinar->brief;
            });

        return $webinars;
    }

    public function indexOrganizations()
    {

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                'webinars' => $this->organizations()
            ]
        );

    }

    public function offlinePurchases()
    {
        $user = apiAuth();
        $sales = Sale::where('sales.buyer_id', $user->id)
            ->whereNull('sales.refund_at')
            ->where('access_to_purchased_item', true)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('sales.webinar_id')
                        ->where('sales.type', 'webinar')
                        ->whereHas('webinar', function ($query) {
                            $query->where('status', 'active');
                        });
                });
                $query->orWhere(function ($query) {
                    $query->whereNotNull('sales.bundle_id')
                        ->where('sales.type', 'bundle')
                        ->whereHas('bundle', function ($query) {
                            $query->where('status', 'active');
                        });
                });
            })->with([
                    'webinar' => function ($query) {
                        $query->with([
                            'files',
                            'reviews' => function ($query) {
                                $query->where('status', 'active');
                            },
                            'category',
                            'teacher' => function ($query) {
                                $query->select('id', 'full_name');
                            },
                        ]);
                        $query->withCount([
                            'sales' => function ($query) {
                                $query->whereNull('refund_at');
                            }
                        ]);
                    },
                    'bundle' => function ($query) {
                        $query->with([
                            'reviews' => function ($query) {
                                $query->where('status', 'active');
                            },
                            'category',
                            'teacher' => function ($query) {
                                $query->select('id', 'full_name');
                            },
                        ]);
                    }
                ])
            ->orderBy('created_at', 'desc')
            ->get();

        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            [
                $sales
            ]
        );
    }

    public function store(Request $request)
    {

        $user = apiAuth();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            abort(403);
        }

        $userPackage = new UserPackage();
        $userCoursesCountLimited = $userPackage->checkPackageLimit('courses_count');

        if ($userCoursesCountLimited) {
            session()->put('registration_package_limited', $userCoursesCountLimited);
            return apiResponse2(0, 'Package Limit Exceed');
        }

        validateParam($request->all(), [
            'type' => 'required|in:webinar,course,text_lesson',
            'title' => 'required|max:255',
            'thumbnail' => 'required',
            'image_cover' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'duration' => 'required|numeric',
            'partners' => 'required_if:partner_instructor,on',
            'capacity' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'start_date' => 'required|date'
        ]);

        $data = $request->all();
        $data = $this->handleVideoDemoData($request, $data, "course_demo_" . time());

        if($data['termsandCondition'] != 1){
            return apiResponse2(0, 'Please Agree with Terms and Conditions to proceed');
        }

        $webinar = Webinar::create([
            'teacher_id' => $user->isTeacher() ? $user->id : (!empty($data['teacher_id']) ? $data['teacher_id'] : $user->id),
            'creator_id' => $user->id,
            'slug' => Webinar::makeSlug($data['title']),
            'type' => $data['type'],
            'private' => (!empty($data['private']) and $data['private'] == 'on') ? true : false,
            'thumbnail' => $data['thumbnail'],
            'image_cover' => $data['image_cover'],
            'video_demo' => $data['video_demo'],
            'video_demo_source' => $data['video_demo'] ? $data['video_demo_source'] : null,
            'status' => ((!empty($data['draft']) and $data['draft'] == 1) or (!empty($data['get_next']) and $data['get_next'] == 1)) ? Webinar::$isDraft : Webinar::$pending,
            'created_at' => time(),
            'message_for_reviewer' => $data['message_for_reviewer']??null,
        ]);

        if ($webinar) {
            WebinarTranslation::updateOrCreate([
                'webinar_id' => $webinar->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'seo_description' => $data['seo_description'],
            ]);

            if ($webinar->isWebinar()) {
                if (empty($data['timezone']) or !getFeaturesSettings('timezone_in_create_webinar')) {
                    $data['timezone'] = getTimezone();
                }

                $startDate = convertTimeToUTCzone($data['start_date'], $data['timezone']);

                $data['start_date'] = $startDate->getTimestamp();
            }

            $data['forum'] = !empty($data['forum']) ? true : false;
            $data['support'] = !empty($data['support']) ? true : false;
            $data['certificate'] = !empty($data['certificate']) ? true : false;
            $data['downloadable'] = !empty($data['downloadable']) ? true : false;
            $data['partner_instructor'] = !empty($data['partner_instructor']) ? true : false;

            if (empty($data['partner_instructor'])) {
                WebinarPartnerTeacher::where('webinar_id', $webinar->id)->delete();
                unset($data['partners']);
            }

            if ($data['category_id'] !== $webinar->category_id) {
                WebinarFilterOption::where('webinar_id', $webinar->id)->delete();
            }

            $data['subscribe'] = !empty($data['subscribe']) ? true : false;
            $data['price'] = !empty($data['price']) ? convertPriceToDefaultCurrency($data['price']) : null;
            $data['organization_price'] = !empty($data['organization_price']) ? convertPriceToDefaultCurrency($data['organization_price']) : null;

            $filters = $request->get('filters', null);

            Webinar::where('id', $webinar->id)->update($data);

            if (!empty($filters) and is_array($filters)) {
                WebinarFilterOption::where('webinar_id', $webinar->id)->delete();
                foreach ($filters as $filter) {
                    WebinarFilterOption::create([
                        'webinar_id' => $webinar->id,
                        'filter_option_id' => $filter
                    ]);
                }
            }

            if (!empty($request->get('tags'))) {
                $tags = explode(',', $request->get('tags'));
                Tag::where('webinar_id', $webinar->id)->delete();

                foreach ($tags as $tag) {
                    Tag::create([
                        'webinar_id' => $webinar->id,
                        'title' => $tag,
                    ]);
                }
            }

            if (!empty($request->get('partner_instructor')) and !empty($request->get('partners'))) {
                WebinarPartnerTeacher::where('webinar_id', $webinar->id)->delete();

                foreach ($request->get('partners') as $partnerId) {
                    WebinarPartnerTeacher::create([
                        'webinar_id' => $webinar->id,
                        'teacher_id' => $partnerId,
                    ]);
                }
            }

            //add webinar chapters
            if (!empty($webinar) and $webinar->canAccess($user)) {
                $status = (!empty($data['status']) and $data['status'] == true) ? WebinarChapter::$chapterActive : WebinarChapter::$chapterInactive;

                $chapter = WebinarChapter::create([
                    'user_id' => $user->id,
                    'webinar_id' => $webinar->id,
                    //'type' => $data['type'],
                    'status' => $status,
                    'check_all_contents_pass' => (!empty($data['check_all_contents_pass']) and $data['check_all_contents_pass'] == true),
                    'created_at' => time(),
                ]);

                if (!empty($chapter)) {
                    WebinarChapterTranslation::updateOrCreate([
                        'webinar_chapter_id' => $chapter->id,
                        'locale' => mb_strtolower($data['locale']),
                    ], [
                        'title' => $data['title'],
                    ]);
                }

                //webinar quizes
                $locale = $data['locale'] ?? getDefaultLocale();
                
                $quiz = Quiz::create([
                    'webinar_id' => !empty($webinar) ? $webinar->id : null,
                    'chapter_id' => !empty($chapter) ? $chapter->id : null,
                    'creator_id' => $user->id,
                    'attempt' => $data['attempt'] ?? null,
                    'pass_mark' => $data['pass_mark'],
                    'time' => $data['time'] ?? null,
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? Quiz::ACTIVE : Quiz::INACTIVE,
                    'certificate' => (!empty($data['certificate']) and $data['certificate'] == 'on'),
                    'display_questions_randomly' => (!empty($data['display_questions_randomly']) and $data['display_questions_randomly'] == 'on'),
                    'expiry_days' => (!empty($data['expiry_days']) and $data['expiry_days'] > 0) ? $data['expiry_days'] : null,
                    'created_at' => time(),
                ]);
        
                if (!empty($quiz)) {
                    QuizTranslation::updateOrCreate([
                        'quiz_id' => $quiz->id,
                        'locale' => mb_strtolower($locale),
                    ], [
                        'title' => $data['title'],
                    ]);
        
                    if (!empty($quiz->chapter_id)) {
                        WebinarChapterItem::makeItem($quiz->creator_id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
                    }
                }
        
                // Send Notification To All Students
                if (!empty($webinar)) {
                    $webinar->sendNotificationToAllStudentsForNewQuizPublished($quiz);
                }


                //new pricing plan (ticket store)
                $canStore = true;

                if (!empty($webinar->capacity)) {
                    $sumTicketsCapacities = $webinar->tickets->sum('capacity');
                    $capacity = $webinar->capacity - $sumTicketsCapacities;

                    $rules['capacity'] = 'nullable|numeric|min:1|max:' . $capacity;
                }

                if ($canStore) {
                    $ticket = Ticket::create([
                        'creator_id' => $user->id,
                        'webinar_id' => !empty($data['webinar_id']) ? $data['webinar_id'] : null,
                        'bundle_id' => !empty($data['bundle_id']) ? $data['bundle_id'] : null,
                        'start_date' => strtotime($data['start_date']),
                        'end_date' => strtotime($data['end_date']),
                        'discount' => $data['discount'],
                        'capacity' => $data['capacity'] ?? null,
                        'created_at' => time()
                    ]);

                    if (!empty($ticket)) {
                        TicketTranslation::updateOrCreate([
                            'ticket_id' => $ticket->id,
                            'locale' => mb_strtolower($data['locale']),
                        ], [
                            'title' => $data['title'],
                        ]);
                    }

                }


                //add faqs
                $columnName = 'webinar_id';
                $columnValue = $webinar->id;

                $order = Faq::query()
                    ->where(function ($query) use ($user, $columnName, $columnValue) {
                        $query->where('creator_id', $user->id);
                        $query->orWhere($columnName, $columnValue);
                    })
                    ->count() + 1;

                $faq = Faq::create([
                    'creator_id' => $user->id,
                    'webinar_id' => !empty($webinar->id) ? $webinar->id : null,
                    'bundle_id' => isset($data['bundle_id']) && !empty($data['bundle_id']) ? $data['bundle_id'] : null,
                    'upcoming_course_id' => isset($data['upcoming_course_id']) && !empty($data['upcoming_course_id']) ? $data['upcoming_course_id'] : null,
                    'order' => $order,
                    'created_at' => time()
                ]);

                if (!empty($faq)) {
                    FaqTranslation::updateOrCreate([
                        'faq_id' => $faq->id,
                        'locale' => mb_strtolower($data['locale']),
                    ], [
                        'title' => $data['title'],
                        'answer' => $data['answer'],
                    ]);
                }


                //webinar extra description
                $columnName = 'webinar_id';
                $columnValue = $webinar->id;

                $order = WebinarExtraDescription::query()
                    ->where($columnName, $columnValue)
                    ->where('type', $data['type'])
                    ->count() + 1;

                if($data['type'] == 'company_logos'){
                    $imageFile = $data['value'];
                    $companyLogo = '';
                }

                $webinarExtraDescription = WebinarExtraDescription::create([
                    'creator_id' => $user->id,
                    'webinar_id' => !empty($webinar->id) ? $webinar->id : null,
                    'upcoming_course_id' => isset($data['upcoming_course_id']) && !empty($data['upcoming_course_id']) ? $data['upcoming_course_id'] : null,
                    'type' => $data['type'],
                    'order' => $order,
                    'created_at' => time()
                ]);

                if (!empty($webinarExtraDescription)) {
                    WebinarExtraDescriptionTranslation::updateOrCreate([
                        'webinar_extra_description_id' => $webinarExtraDescription->id,
                        'locale' => mb_strtolower($data['locale']),
                    ], [
                        'value' => $data['type'] == 'company_logos' ? $companyLogo : $data['value'],
                    ]);
                }

                //webinar quizes


            }

            //add sections
            if (!empty($webinar) and $webinar->canAccess()) {

                $required = (!empty($data['required']) and $data['required'] == 'on') ? true : false;

                Prerequisite::create([
                    'webinar_id' => $data['webinar_id'],
                    'prerequisite_id' => $data['prerequisite_id'],
                    'required' => $required,
                    'created_at' => time()
                ]);

            }

            //add related course
            $type = 'App\Models\Webinar';

            RelatedCourse::query()->updateOrCreate([
                'creator_id' => $user->id,
                'targetable_id' => $data['item_id'],
                'targetable_type' => $type,
                'course_id' => $webinar->id
            ], [
                'order' => null,
            ]);


        }


        $notifyOptions = [
            '[u.name]' => $user->full_name,
            '[item_title]' => $webinar->title,
            '[content_type]' => trans('admin/main.course'),
        ];
        sendNotification("new_item_created", $notifyOptions, 1);


    }

}
