<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\PurchaseResource;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Http\Controllers\Panel\Traits\VideoDemoTrait;
use App\Mixins\Cashback\CashbackRules;
use App\Models\Api\Sale;
use App\Models\Api\Webinar;
use App\Models\Api\Gift;
use App\Models\WebinarPartnerTeacher;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\Models\WebinarExtraDescription;
use App\Models\WebinarFilterOption;
use App\Models\Ticket;
use App\Models\Tag;
use App\Models\Faq;
use App\Models\Session;
use App\Models\WebinarAssignment;
use App\Models\WebinarQuiz;
use App\Models\WebinarQuizQuestion;
use App\Models\File;
use App\Models\Quiz;
use App\Models\Prerequisite;
use App\Models\RelatedCourse;
use App\Models\TextLesson;
use App\Models\WebinarFile;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesQuestionsAnswer;
use App\Models\TextLessonAttachment;
use App\Models\WebinarAssignmentAttachment;
use App\User;
use App\Models\Translation\GiftTranslation;
use App\Models\Translation\SaleTranslation;
use App\Models\Translation\UserTranslation;
use App\Models\Translation\QuizzesQuestionTranslation;
use App\Models\Translation\QuizzesQuestionsAnswerTranslation;
use App\Models\Translation\WebinarTranslation;
use App\Models\Translation\SessionTranslation;
use App\Models\Translation\FileTranslation;
use App\Models\Translation\RelatedCourseTranslation;
use App\Models\Translation\PrerequisiteTranslation;
use App\Models\Translation\WebinarFileTranslation;
use App\Models\Translation\WebinarAssignmentTranslation;
use App\Models\Translation\WebinarQuizQuestionTranslation;
use App\Models\Translation\WebinarQuizTranslation;
use App\Models\Translation\TextLessonTranslation;
use App\Models\Translation\WebinarChapterTranslation;
use App\Models\Translation\FaqTranslation;
use App\Models\Translation\TicketTranslation;
use App\Models\Translation\WebinarExtraDescriptionTranslation;
use App\Models\Translation\QuizTranslation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sessions\Zoom;
use App\Sessions\ZoomOAuth;
use Illuminate\Support\Carbon;
use Validator;
use Bigbluebutton;

class WebinarsController extends Controller
{
    use VideoDemoTrait;

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
            'purchases' => $this->purchases(),
            'organizations' => $this->organizations(),
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
        abort_unless(!empty($course), 404);


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

    public function userssearch(Request $request)
    {
        $request->validate([
            'term' => 'required|string|min:1',
            'option' => 'nullable|string|in:just_teachers,just_student_role'
        ]);

        $user = auth('api')->user(); // for API guard (Sanctum/Passport)

        $query = User::query()
            ->select('id', 'full_name', 'email', 'mobile', 'avatar') // return only needed fields
            ->where('id', '!=', $user->id)
            ->whereNotIn('role_name', ['admin'])
            ->where(function ($q) use ($request) {
                $term = $request->term;

                $q->where('full_name', 'LIKE', "%$term%")
                    ->orWhere('email', 'LIKE', "%$term%")
                    ->orWhere('mobile', 'LIKE', "%$term%");
            });

        // 🎯 Filter by option
        if ($request->option === 'just_teachers') {
            $query->where('role_name', 'teacher');
        }

        if ($request->option === 'just_student_role') {
            $query->where('role_name', \App\Models\Role::$user);
        }

        $users = $query->get();

        return response()->json([
            'status' => true,
            'message' => 'User list fetched successfully',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {

        $user = apiAuth();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            // abort(403);
            return apiResponse2(0, 'Unauthorized. You do not have permission to create a course.', [], 403);
        }

        $userPackage = new UserPackage();
        $userCoursesCountLimited = $userPackage->checkPackageLimit('courses_count');

        if ($userCoursesCountLimited) {
            session()->put('registration_package_limited', $userCoursesCountLimited);
            return apiResponse2(0, 'fail', 'Package Limit Exceed');
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
            'start_date' => 'required|date',
            'teacher_id' => 'nullable|exists:users,id'
        ]);

        $data = $request->all();
        $data = $this->handleVideoDemoData($request, $data, "course_demo_" . time());

        if ($data['termsandCondition'] != 1) {
            return apiResponse2(0, 'fail', 'Please Agree with Terms and Conditions to proceed');
        }

        $thumbnail = $data['thumbnail'] ?? null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $this->uploadFile($request->file('thumbnail'), $user->id);
        }
        // Write resolved path back into $data so the bulk update below uses the
        // store path, not the raw UploadedFile / temp-file object.
        $data['thumbnail'] = $thumbnail;

        $imageCover = $data['image_cover'] ?? null;
        if ($request->hasFile('image_cover')) {
            $imageCover = $this->uploadFile($request->file('image_cover'), $user->id);
        }
        $data['image_cover'] = $imageCover;

        // video_demo: when source='upload' the client sends the file directly.
        // $request->all() puts an UploadedFile into $data['video_demo'], so we
        // resolve it to a store path and write it back (same pattern as thumbnail).
        $videodemo = $data['video_demo'] ?? null;
        if ($request->hasFile('video_demo')) {
            $videodemo = $this->uploadFile($request->file('video_demo'), $user->id);
        } elseif ($videodemo instanceof \Illuminate\Http\UploadedFile) {
            // Safety-net: UploadedFile leaked via $request->all()
            $videodemo = $this->uploadFile($videodemo, $user->id);
        }
        $data['video_demo'] = $videodemo;

        $webinar = Webinar::create([
            'teacher_id' => $user->isTeacher() ? $user->id : (!empty($data['teacher_id']) ? $data['teacher_id'] : $user->id),
            'creator_id' => $user->id,
            'slug' => Webinar::makeSlug($data['title']),
            'type' => $data['type'],
            'category_id' => $data['category_id'],
            'duration' => $data['duration'],
            'capacity' => $data['capacity'] ?? null,
            'price' => $data['price'] ?? null,
            'private' => (!empty($data['private']) and $data['private'] == 'on') ? true : false,
            'thumbnail' => $thumbnail,
            'image_cover' => $imageCover,
            'video_demo' => $videodemo,
            'video_demo_source' => !empty($videodemo) ? ($data['video_demo_source'] ?? null) : null,
            // 'video_demo_source' => $data['video_demo'] ? $data['video_demo_source'] : null,
            'status' => 'pending',
            'created_at' => time(),
            'message_for_reviewer' => $data['message_for_reviewer'] ?? null,
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

            // if (!empty($data['start_date'])) {
            //     if (empty($data['timezone']) or !getFeaturesSettings('timezone_in_create_webinar')) {
            //         $data['timezone'] = getTimezone();
            //     }

            //     $startDate = convertTimeToUTCzone($data['start_date'], $data['timezone']);

            //     $data['start_date'] = $startDate->getTimestamp();
            // }

            
            if (!empty($data['start_date'])) {
                if (empty($data['timezone']) || !getFeaturesSettings('timezone_in_create_webinar')) {
                    $data['timezone'] = getTimezone();
                }
                $startDate = convertTimeToUTCzone($data['start_date'], $data['timezone']);
                $data['start_date'] = $startDate->getTimestamp();
                // also persist it:
                // $data['timezone'] = $data['timezone']; (already set, just make sure it's a saved column)
            }

            $data['forum'] = (!empty($data['forum']) and ($data['forum'] == 'on' or $data['forum'] == 1)) ? 1 : 0;
            $data['support'] = (!empty($data['support']) and ($data['support'] == 'on' or $data['support'] == 1)) ? 1 : 0;
            $data['certificate'] = (!empty($data['certificate']) and ($data['certificate'] == 'on' or $data['certificate'] == 1)) ? 1 : 0;
            $data['downloadable'] = (!empty($data['downloadable']) and ($data['downloadable'] == 'on' or $data['downloadable'] == 1)) ? 1 : 0;
            $data['partner_instructor'] = (!empty($data['partner_instructor']) and ($data['partner_instructor'] == 'on' or $data['partner_instructor'] == 1)) ? 1 : 0;
            $data['subscribe'] = (!empty($data['subscribe']) and ($data['subscribe'] == 'on' or $data['subscribe'] == 1)) ? 1 : 0;
            $data['private'] = (!empty($data['private']) and ($data['private'] == 'on' or $data['private'] == 1)) ? 1 : 0;
            $data['access_days'] = !empty($data['access_days']) ?? 0;
            $data['earning_price'] = !empty($data['earning_price']) ? convertPriceToDefaultCurrency($data['earning_price']) : null;
            $data['platform_price'] = !empty($data['platform_price']) ? convertPriceToDefaultCurrency($data['platform_price']) : null;
            $data['price'] = !empty($data['price']) ? convertPriceToDefaultCurrency($data['price']) : null;
            $data['organization_price'] = !empty($data['organization_price']) ? convertPriceToDefaultCurrency($data['organization_price']) : null;
            $data['status'] = 'pending';
            
            if (empty($data['partner_instructor'])) {
                $data['partner_instructor'] = 0;
                WebinarPartnerTeacher::where('webinar_id', $webinar->id)->delete();
                unset($data['partners']);
            }

            if ($data['category_id'] !== $webinar->category_id) {
                WebinarFilterOption::where('webinar_id', $webinar->id)->delete();
            }

            $webinarColumns = [
                'teacher_id',
                'category_id',
                'type',
                'private',
                'slug',
                'start_date',
                'duration',
                'timezone',
                'thumbnail',
                'image_cover',
                'video_demo',
                'video_demo_source',
                'capacity',
                'earning_price',
                'platform_price',
                'price',
                'access_days',
                'organization_price',
                'support',
                'certificate',
                'downloadable',
                'partner_instructor',
                'subscribe',
                'forum',
                'message_for_reviewer',
                'status'
            ];
            $updateData = array_intersect_key($data, array_flip($webinarColumns));

            Webinar::where('id', $webinar->id)->update($updateData);

            // if (!empty($filters) and is_array($filters)) {
            //     WebinarFilterOption::where('webinar_id', $webinar->id)->delete();
            //     foreach ($filters as $filter) {
            //         WebinarFilterOption::create([
            //             'webinar_id' => $webinar->id,
            //             'filter_option_id' => $filter
            //         ]);
            //     }
            // }

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
            $locale = $data['locale'] ?? getDefaultLocale();
            if (!empty($webinar) and $webinar->canAccess($user)) {
                $status = (!empty($data['status']) and $data['status'] == true) ? WebinarChapter::$chapterActive : WebinarChapter::$chapterInactive;

                // $chapter = WebinarChapter::create([
                //     'user_id' => $user->id,
                //     'webinar_id' => $webinar->id,
                //     //'type' => $data['type'],
                //     'status' => $status,
                //     'check_all_contents_pass' => (!empty($data['check_all_contents_pass']) and $data['check_all_contents_pass'] == true),
                //     'created_at' => time(),
                // ]);

                // if (!empty($chapter)) {
                //     WebinarChapterTranslation::updateOrCreate([
                //         'webinar_chapter_id' => $chapter->id,
                //         'locale' => mb_strtolower($data['locale']),
                //     ], [
                //         'title' => $data['title'],
                //     ]);
                // }

                foreach ($request->get('chapters', []) as $chapterKey => $chapterData) {

                    $chapter = WebinarChapter::create([
                        'user_id' => $user->id,
                        'webinar_id' => $webinar->id,
                        'status' => (!empty($chapterData['status']) && $chapterData['status'] == 'on')
                            ? WebinarChapter::$chapterActive
                            : WebinarChapter::$chapterInactive,
                        'check_all_contents_pass' => (!empty($chapterData['check_all_contents_pass']) && $chapterData['check_all_contents_pass'] == true),
                        'created_at' => time(),
                    ]);

                    if ($chapter) {
                        WebinarChapterTranslation::updateOrCreate([
                            'webinar_chapter_id' => $chapter->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $chapterData['title'],
                        ]);
                    }


                    //webinar quizes
                    $locale = $data['locale'] ?? getDefaultLocale();

                    foreach ($chapterData['quizzes'] ?? [] as $quizData) {

                        $quiz = Quiz::create([
                            'webinar_id' => $webinar->id,
                            'chapter_id' => $chapter->id,
                            'creator_id' => $user->id,
                            'pass_mark' => $quizData['pass_mark'],
                            'attempt' => $quizData['attempt'] ?? null,
                            'time' => $quizData['time'] ?? null,
                            'status' => (!empty($quizData['status']) && $quizData['status'] == 'on')
                                ? Quiz::ACTIVE
                                : Quiz::INACTIVE,
                            'certificate' => (!empty($quizData['certificate']) && $quizData['certificate'] == 'on'),
                            'display_questions_randomly' => (!empty($quizData['display_questions_randomly']) && $quizData['display_questions_randomly'] == 'on'),
                            'expiry_days' => (!empty($quizData['expiry_days']) && $quizData['expiry_days'] > 0) ? $quizData['expiry_days'] : null,
                            'created_at' => time(),
                        ]);

                        if ($quiz) {
                            QuizTranslation::updateOrCreate([
                                'quiz_id' => $quiz->id,
                                'locale' => mb_strtolower($locale),
                            ], [
                                'title' => $quizData['title'],
                            ]);

                            if ($quiz->chapter_id) {
                                WebinarChapterItem::makeItem(
                                    $quiz->creator_id,
                                    $quiz->chapter_id,
                                    $quiz->id,
                                    WebinarChapterItem::$chapterQuiz
                                );
                            }

                            $webinar->sendNotificationToAllStudentsForNewQuizPublished($quiz);
                        }
                    }

                    // $quiz = Quiz::create([
                    //     'webinar_id' => !empty($webinar) ? $webinar->id : null,
                    //     'chapter_id' => !empty($chapter) ? $chapter->id : null,
                    //     'creator_id' => $user->id,
                    //     'attempt' => $data['attempt'] ?? null,
                    //     'pass_mark' => $data['pass_mark'],
                    //     'time' => $data['time'] ?? null,
                    //     'status' => (!empty($data['status']) and $data['status'] == 'on') ? Quiz::ACTIVE : Quiz::INACTIVE,
                    //     'certificate' => (!empty($data['certificate']) and $data['certificate'] == 'on'),
                    //     'display_questions_randomly' => (!empty($data['display_questions_randomly']) and $data['display_questions_randomly'] == 'on'),
                    //     'expiry_days' => (!empty($data['expiry_days']) and $data['expiry_days'] > 0) ? $data['expiry_days'] : null,
                    //     'created_at' => time(),
                    // ]);

                    // if (!empty($quiz)) {
                    //     QuizTranslation::updateOrCreate([
                    //         'quiz_id' => $quiz->id,
                    //         'locale' => mb_strtolower($locale),
                    //     ], [
                    //         'title' => $data['title'],
                    //     ]);

                    //     if (!empty($quiz->chapter_id)) {
                    //         WebinarChapterItem::makeItem($quiz->creator_id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
                    //     }
                    // }

                    // // Send Notification To All Students
                    // if (!empty($webinar)) {
                    //     $webinar->sendNotificationToAllStudentsForNewQuizPublished($quiz);
                    // }

                    foreach ($chapterData['sessions'] ?? [] as $sessionData) {

                        if ($webinar->type == 'webinar') {


                            // Skip empty placeholder objects (e.g. from Postman template)
                            if (empty($sessionData['title']) || empty($sessionData['date'])) {
                                continue;
                            }

                            $checkPreviousParts = false;
                            $accessAfterDay = null;
                            if (!empty($sessionData['sequence_content']) && $sessionData['sequence_content'] == 'on') {
                                $checkPreviousParts = (!empty($sessionData['check_previous_parts']) && $sessionData['check_previous_parts'] == 'on');
                                $accessAfterDay = !empty($sessionData['access_after_day']) ? $sessionData['access_after_day'] : null;
                            }

                            $sessionDate = convertTimeToUTCzone($sessionData['date'], $webinar->timezone ?? getTimezone());

                            $session = Session::create([
                                'creator_id' => $user->id,
                                'webinar_id' => $webinar->id,
                                'chapter_id' => $chapter->id,
                                'date' => $sessionDate->getTimestamp(),
                                'duration' => $sessionData['duration'],
                                'link' => $sessionData['link'] ?? null,
                                'session_api' => $sessionData['session_api'],
                                'api_secret' => $sessionData['api_secret'] ?? null,
                                'moderator_secret' => $sessionData['moderator_secret'] ?? null,
                                'check_previous_parts' => $checkPreviousParts,
                                'access_after_day' => $accessAfterDay,
                                'extra_time_to_join' => $sessionData['extra_time_to_join'] ?? null,
                                'status' => (!empty($sessionData['status']) && $sessionData['status'] == 'on')
                                    ? Session::$Active
                                    : Session::$Inactive,
                                'created_at' => time(),
                            ]);

                            if ($session) {
                                SessionTranslation::updateOrCreate([
                                    'session_id' => $session->id,
                                    'locale' => mb_strtolower($locale),
                                ], [
                                    'title' => $sessionData['title'],
                                    'description' => $sessionData['description'] ?? null,
                                ]);

                                // if ($sessionData['session_api'] == 'big_blue_button') {
                                //     $this->handleBigBlueButtonApi($session, $user);
                                // } elseif ($sessionData['session_api'] == 'zoom') {
                                //     $zoomResult = $this->handleZoomApi($session, $user);
                                //     if ($zoomResult != 'ok') {
                                //         \Log::warning('Zoom API error for session ' . $session->id . ' during course store.');
                                //     }
                                // } 
                                // elseif ($sessionData['session_api'] == 'agora') {
                                //     $session->agora_settings = json_encode([
                                //         'chat'       => (!empty($sessionData['agora_chat']) && $sessionData['agora_chat'] == 'on'),
                                //         'record'     => (!empty($sessionData['agora_record']) && $sessionData['agora_record'] == 'on'),
                                //         'users_join' => true,
                                //     ]);
                                //     $session->save();
                                // }

                                WebinarChapterItem::makeItem(
                                    $session->creator_id,
                                    $session->chapter_id,
                                    $session->id,
                                    WebinarChapterItem::$chapterSession
                                );
                            }
                        }
                    }

                    // ----------------------------------------------------------
                    // FILES inside chapter  →  chapters[].files[]
                    // ----------------------------------------------------------
                    foreach ($chapterData['files'] ?? [] as $fileKey => $fileData) {

                        // Skip empty placeholder objects.
                        // NOTE: when the client sends a real file upload, $fileData['file_path']
                        // (from $request->get()) is always empty because get() does not include
                        // file objects. So we must also check hasFile() before deciding to skip.
                        $hasUploadedFile = $request->hasFile("chapters.{$chapterKey}.files.{$fileKey}.file_path");
                        if (empty($fileData['title']) || (empty($fileData['file_path'] ?? null) && !$hasUploadedFile)) {
                            continue;
                        }

                        $storage = $fileData['storage'] ?? 'upload';

                        $sourceDefaultFileTypeAndVolume = ['youtube', 'vimeo', 'iframe', 'secure_host'];
                        if (in_array($storage, $sourceDefaultFileTypeAndVolume)) {
                            $fileData['file_type'] = 'video';
                            $fileData['volume'] = !empty($fileData['volume']) ? $fileData['volume'] : 0;
                        }

                        // Downloadable logic mirrors the original file store
                        $downloadable = !empty($fileData['downloadable']);
                        if (in_array($storage, ['youtube', 'vimeo', 'iframe', 'google_drive', 'upload_archive'])) {
                            $downloadable = false;
                        } elseif (in_array($storage, ['external_link', 's3']) && ($fileData['file_type'] ?? '') != 'video') {
                            $downloadable = true;
                        }

                        // Sequence-content flags
                        $checkPreviousParts = false;
                        $accessAfterDay = null;
                        if (!empty($fileData['sequence_content']) && $fileData['sequence_content'] == 'on') {
                            $checkPreviousParts = (!empty($fileData['check_previous_parts']) && $fileData['check_previous_parts'] == 'on');
                            $accessAfterDay = !empty($fileData['access_after_day']) ? $fileData['access_after_day'] : null;
                        }

                        $filePath = $fileData['file_path'] ?? null;
                        if ($request->hasFile("chapters.{$chapterKey}.files.{$fileKey}.file_path")) {
                            $fileObj = $request->file("chapters.{$chapterKey}.files.{$fileKey}.file_path");
                            $filePath = $this->uploadFile($fileObj, $user->id);
                        }

                        $file = File::create([
                            'creator_id' => $user->id,
                            'webinar_id' => $webinar->id,
                            'chapter_id' => $chapter->id,
                            'file' => $filePath,
                            'volume' => $fileData['volume'] ?? 0,
                            'file_type' => $fileData['file_type'] ?? null,
                            'accessibility' => $fileData['accessibility'] ?? 'paid',
                            'storage' => $storage,
                            'secure_host_upload_type' => $fileData['secure_host_upload_type'] ?? null,
                            // 'interactive_type'        => $fileData['interactive_type'] ?? null,
                            'interactive_file_name' => $fileData['interactive_file_name'] ?? null,
                            'interactive_file_path' => $fileData['interactive_file_path'] ?? null,
                            'online_viewer' => (!empty($fileData['online_viewer']) && $fileData['online_viewer'] == 'on'),
                            'downloadable' => $downloadable,
                            'check_previous_parts' => $checkPreviousParts,
                            'access_after_day' => $accessAfterDay,
                            'status' => (!empty($fileData['status']) && $fileData['status'] == 'on')
                                ? File::$Active
                                : File::$Inactive,
                            'created_at' => time(),
                        ]);

                        if ($file) {
                            FileTranslation::updateOrCreate([
                                'file_id' => $file->id,
                                'locale' => mb_strtolower($locale),
                            ], [
                                'title' => $fileData['title'],
                                'description' => $fileData['description'] ?? null,
                            ]);

                            WebinarChapterItem::makeItem(
                                $file->creator_id,
                                $file->chapter_id,
                                $file->id,
                                WebinarChapterItem::$chapterFile
                            );
                        }
                    }

                    // ----------------------------------------------------------
                    // TEXT LESSONS inside chapter
                    // Accepts keys: text_lessons | text-lessons | test-lesson | test_lesson
                    // ----------------------------------------------------------
                    $textLessonsData = $chapterData['text_lessons']
                        ?? $chapterData['text-lessons']
                        ?? $chapterData['test-lesson']
                        ?? $chapterData['test_lesson']
                        ?? [];

                    foreach ($textLessonsData as $lessonKey => $lessonData) {

                        // Skip empty placeholder objects
                        if (empty($lessonData['title'])) {
                            continue;
                        }

                        $checkPreviousParts = false;
                        $accessAfterDay = null;
                        if (!empty($lessonData['sequence_content']) && $lessonData['sequence_content'] == 'on') {
                            $checkPreviousParts = (!empty($lessonData['check_previous_parts']) && $lessonData['check_previous_parts'] == 'on');
                            $accessAfterDay = !empty($lessonData['access_after_day']) ? $lessonData['access_after_day'] : null;
                        }

                        $lessonsCount = TextLesson::where('webinar_id', $webinar->id)->count();

                        $textLesson = TextLesson::create([
                            'creator_id' => $user->id,
                            'webinar_id' => $webinar->id,
                            'chapter_id' => $chapter->id,
                            'image' => (function () use ($request, $chapterKey, $lessonKey, $lessonData, $user) {
                                if ($request->hasFile("chapters.{$chapterKey}.text_lessons.{$lessonKey}.image")) {
                                    return $this->uploadFile($request->file("chapters.{$chapterKey}.text_lessons.{$lessonKey}.image"), $user->id);
                                }
                                return $lessonData['image'] ?? null;
                            })(),
                            'study_time' => $lessonData['study_time'] ?? 0,
                            'accessibility' => $lessonData['accessibility'] ?? 'paid',
                            'order' => $lessonsCount + 1,
                            'check_previous_parts' => $checkPreviousParts,
                            'access_after_day' => $accessAfterDay,
                            'status' => (!empty($lessonData['status']) && $lessonData['status'] == 'on')
                                ? TextLesson::$Active
                                : TextLesson::$Inactive,
                            'created_at' => time(),
                        ]);

                        if ($textLesson) {
                            TextLessonTranslation::updateOrCreate([
                                'text_lesson_id' => $textLesson->id,
                                'locale' => mb_strtolower($locale),
                            ], [
                                'title' => $lessonData['title'],
                                'summary' => $lessonData['summary'] ?? null,
                                'content' => $lessonData['content'] ?? null,
                            ]);

                            if (!empty($lessonData['attachments'])) {
                                $this->saveAttachments($textLesson, $lessonData['attachments']);
                            }

                            WebinarChapterItem::makeItem(
                                $textLesson->creator_id,
                                $textLesson->chapter_id,
                                $textLesson->id,
                                WebinarChapterItem::$chapterTextLesson
                            );
                        }
                    }

                    // ----------------------------------------------------------
                    // ASSIGNMENTS inside chapter  →  chapters[].assignments[]
                    // ----------------------------------------------------------
                    foreach ($chapterData['assignments'] ?? [] as $assignmentKey => $assignmentData) {

                        // Skip empty placeholder objects
                        if (empty($assignmentData['title'])) {
                            continue;
                        }

                        $checkPreviousParts = false;
                        $accessAfterDay = null;
                        if (!empty($assignmentData['sequence_content']) && $assignmentData['sequence_content'] == 'on') {
                            $checkPreviousParts = (!empty($assignmentData['check_previous_parts']) && $assignmentData['check_previous_parts'] == 'on');
                            $accessAfterDay = !empty($assignmentData['access_after_day']) ? $assignmentData['access_after_day'] : null;
                        }

                        $assignment = WebinarAssignment::create([
                            'creator_id' => $user->id,
                            'webinar_id' => $webinar->id,
                            'chapter_id' => $chapter->id,
                            'grade' => $assignmentData['grade'] ?? null,
                            'pass_grade' => $assignmentData['pass_grade'] ?? null,
                            'deadline' => !empty($assignmentData['deadline']) ? strtotime($assignmentData['deadline']) : null,
                            'attempts' => $assignmentData['attempts'] ?? null,
                            'check_previous_parts' => $checkPreviousParts,
                            'access_after_day' => $accessAfterDay,
                            'status' => (!empty($assignmentData['status']) && $assignmentData['status'] == 'on')
                                ? File::$Active
                                : File::$Inactive,
                            'created_at' => time(),
                        ]);

                        if ($assignment) {
                            WebinarAssignmentTranslation::updateOrCreate([
                                'webinar_assignment_id' => $assignment->id,
                                'locale' => mb_strtolower($locale),
                            ], [
                                'title' => $assignmentData['title'],
                                'description' => $assignmentData['description'] ?? null,
                            ]);

                            if (!empty($assignmentData['attachments']) && is_array($assignmentData['attachments'])) {
                                $this->handleAssignmentAttachments(
                                    $assignmentData['attachments'],
                                    $user->id,
                                    $assignment->id,
                                    $assignmentKey,
                                    $chapterKey,
                                    $request
                                );
                            }

                            WebinarChapterItem::makeItem(
                                $assignment->creator_id,
                                $assignment->chapter_id,
                                $assignment->id,
                                WebinarChapterItem::$chapterAssignment
                            );
                        }
                    }
                }

                //new pricing plan (ticket store)
                // $canStore = true;

                // if (!empty($webinar->capacity)) {
                //     $sumTicketsCapacities = $webinar->tickets->sum('capacity');
                //     $capacity = $webinar->capacity - $sumTicketsCapacities;

                //     $rules['capacity'] = 'nullable|numeric|min:1|max:' . $capacity;
                // }

                // if ($canStore) {
                //     $ticket = Ticket::create([
                //         'creator_id' => $user->id,
                //         'webinar_id' => !empty($data['webinar_id']) ? $data['webinar_id'] : null,
                //         'bundle_id' => !empty($data['bundle_id']) ? $data['bundle_id'] : null,
                //         'start_date' => strtotime($data['start_date']),
                //         'end_date' => strtotime($data['end_date']),
                //         'discount' => $data['discount'],
                //         'capacity' => $data['capacity'] ?? null,
                //         'created_at' => time()
                //     ]);

                //     if (!empty($ticket)) {
                //         TicketTranslation::updateOrCreate([
                //             'ticket_id' => $ticket->id,
                //             'locale' => mb_strtolower($data['locale']),
                //         ], [
                //             'title' => $data['title'],
                //         ]);
                //     }

                // }

                foreach ($request->get('tickets', []) as $ticketData) {

                    // Capacity guard
                    $canStore = true;
                    if (!empty($webinar->capacity)) {
                        $used = $webinar->tickets()->sum('capacity');
                        $remaining = $webinar->capacity - $used;
                        if (!empty($ticketData['capacity']) && $ticketData['capacity'] > $remaining) {
                            $canStore = false;
                        }
                    }

                    if ($canStore) {
                        $ticket = Ticket::create([
                            'creator_id' => $user->id,
                            'webinar_id' => $webinar->id,
                            'bundle_id' => !empty($ticketData['bundle_id']) ? $ticketData['bundle_id'] : null,
                            'start_date' => strtotime($ticketData['start_date']),
                            'end_date' => strtotime($ticketData['end_date']),
                            'discount' => $ticketData['discount'],
                            'capacity' => $ticketData['capacity'] ?? null,
                            'created_at' => time(),
                        ]);

                        if ($ticket) {
                            TicketTranslation::updateOrCreate([
                                'ticket_id' => $ticket->id,
                                'locale' => mb_strtolower($locale),
                            ], [
                                'title' => $ticketData['title'],
                            ]);
                        }
                    }
                }


                //add faqs
                // $columnName = 'webinar_id';
                // $columnValue = $webinar->id;

                // $order = Faq::query()
                //     ->where(function ($query) use ($user, $columnName, $columnValue) {
                //         $query->where('creator_id', $user->id);
                //         $query->orWhere($columnName, $columnValue);
                //     })
                //     ->count() + 1;

                // $faq = Faq::create([
                //     'creator_id' => $user->id,
                //     'webinar_id' => !empty($webinar->id) ? $webinar->id : null,
                //     'bundle_id' => isset($data['bundle_id']) && !empty($data['bundle_id']) ? $data['bundle_id'] : null,
                //     'upcoming_course_id' => isset($data['upcoming_course_id']) && !empty($data['upcoming_course_id']) ? $data['upcoming_course_id'] : null,
                //     'order' => $order,
                //     'created_at' => time()
                // ]);

                // if (!empty($faq)) {
                //     FaqTranslation::updateOrCreate([
                //         'faq_id' => $faq->id,
                //         'locale' => mb_strtolower($data['locale']),
                //     ], [
                //         'title' => $data['title'],
                //         'answer' => $data['answer'],
                //     ]);
                // }

                foreach ($request->get('faqs', []) as $faqData) {

                    $order = Faq::where('webinar_id', $webinar->id)->count() + 1;

                    $faq = Faq::create([
                        'creator_id' => $user->id,
                        'webinar_id' => $webinar->id,
                        'bundle_id' => !empty($faqData['bundle_id']) ? $faqData['bundle_id'] : null,
                        'upcoming_course_id' => !empty($faqData['upcoming_course_id']) ? $faqData['upcoming_course_id'] : null,
                        'order' => $order,
                        'created_at' => time(),
                    ]);

                    if ($faq) {
                        FaqTranslation::updateOrCreate([
                            'faq_id' => $faq->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $faqData['title'],
                            'answer' => $faqData['answer'],
                        ]);
                    }
                }


                //webinar extra description
                // $columnName = 'webinar_id';
                // $columnValue = $webinar->id;

                // $order = WebinarExtraDescription::query()
                //     ->where($columnName, $columnValue)
                //     ->where('type', $data['type'])
                //     ->count() + 1;

                // if($data['type'] == 'company_logos'){
                //     $imageFile = $data['value'];
                //     $companyLogo = '';
                // }

                // $webinarExtraDescription = WebinarExtraDescription::create([
                //     'creator_id' => $user->id,
                //     'webinar_id' => !empty($webinar->id) ? $webinar->id : null,
                //     'upcoming_course_id' => isset($data['upcoming_course_id']) && !empty($data['upcoming_course_id']) ? $data['upcoming_course_id'] : null,
                //     'type' => $data['type'],
                //     'order' => $order,
                //     'created_at' => time()
                // ]);

                // if (!empty($webinarExtraDescription)) {
                //     WebinarExtraDescriptionTranslation::updateOrCreate([
                //         'webinar_extra_description_id' => $webinarExtraDescription->id,
                //         'locale' => mb_strtolower($data['locale']),
                //     ], [
                //         'value' => $data['type'] == 'company_logos' ? $companyLogo : $data['value'],
                //     ]);
                // }

                foreach ($request->get('extra_descriptions', []) as $extraKey => $extraData) {

                    $order = WebinarExtraDescription::where('webinar_id', $webinar->id)
                        ->where('type', $extraData['type'])
                        ->count() + 1;

                    // Handle company logo image upload if needed
                    $value = $extraData['value'] ?? '';
                    if ($extraData['type'] == 'company_logos') {
                        if ($request->hasFile("extra_descriptions.{$extraKey}.value")) {
                            $value = $this->uploadFile($request->file("extra_descriptions.{$extraKey}.value"), $user->id);
                        }
                    }

                    $extraDescription = WebinarExtraDescription::create([
                        'creator_id' => $user->id,
                        'webinar_id' => $webinar->id,
                        // 'upcoming_course_id' => !empty($extraData['upcoming_course_id']) ? $extraData['upcoming_course_id'] : null,
                        'type' => $extraData['type'],
                        'order' => $order,
                        'created_at' => time(),
                    ]);

                    if ($extraDescription) {
                        WebinarExtraDescriptionTranslation::updateOrCreate([
                            'webinar_extra_description_id' => $extraDescription->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'value' => $value,
                        ]);
                    }
                }

                //webinar quizes


            }

            //add sections
            // if (!empty($webinar) and $webinar->canAccess()) {

            //     $required = (!empty($data['required']) and $data['required'] == 'on') ? true : false;

            //     Prerequisite::create([
            //         'webinar_id' => $data['webinar_id'],
            //         'prerequisite_id' => $data['prerequisite_id'],
            //         'required' => $required,
            //         'created_at' => time()
            //     ]);

            // }

            if ($webinar->canAccess($user)) {

                foreach ($request->get('prerequisites', []) as $prereqData) {
                    Prerequisite::create([
                        'webinar_id' => $webinar->id,
                        'prerequisite_id' => $prereqData['prerequisite_id'],
                        'required' => (!empty($prereqData['required']) && $prereqData['required'] == 'on'),
                        'created_at' => time(),
                    ]);
                }
            }

            //add related course
            $type = 'App\Models\Webinar';

            // RelatedCourse::query()->updateOrCreate([
            //     'creator_id' => $user->id,
            //     'targetable_id' => $data['item_id'],
            //     'targetable_type' => $type,
            //     'course_id' => $webinar->id
            // ], [
            //     'order' => null,
            // ]);
            foreach ($request->get('related_courses', []) as $relatedData) {
                if (!empty($relatedData['item_id'])) {
                    RelatedCourse::updateOrCreate([
                        'creator_id' => $user->id,
                        'targetable_id' => $webinar->id,
                        'targetable_type' => 'App\Models\Webinar',
                        'course_id' => $relatedData['item_id'],
                    ], [
                        'order' => null,
                    ]);
                }
            }

            foreach ($request->get('quizzes', []) as $quizKey => $quizData) {

                if (empty($quizData['title']) || !isset($quizData['pass_mark'])) {
                    continue;
                }

                // Optional: link to a chapter if chapter_id supplied
                $standaloneChapterId = null;
                if (!empty($quizData['chapter_id'])) {
                    $matchedChapter = WebinarChapter::where('id', $quizData['chapter_id'])
                        ->where('webinar_id', $webinar->id)
                        ->first();
                    $standaloneChapterId = $matchedChapter ? $matchedChapter->id : null;
                }

                $quiz = Quiz::create([
                    'webinar_id' => $webinar->id,
                    'chapter_id' => $standaloneChapterId,
                    'creator_id' => $user->id,
                    'pass_mark' => $quizData['pass_mark'],
                    'attempt' => $quizData['attempt'] ?? null,
                    'time' => $quizData['time'] ?? null,
                    'status' => (!empty($quizData['status']) && $quizData['status'] == 'on')
                        ? Quiz::ACTIVE
                        : Quiz::INACTIVE,
                    'certificate' => (!empty($quizData['certificate']) && $quizData['certificate'] == 'on'),
                    'display_questions_randomly' => (!empty($quizData['display_questions_randomly']) && $quizData['display_questions_randomly'] == 'on'),
                    'expiry_days' => (!empty($quizData['expiry_days']) && $quizData['expiry_days'] > 0)
                        ? $quizData['expiry_days']
                        : null,
                    'created_at' => time(),
                ]);

                if (!$quiz) {
                    continue;
                }

                QuizTranslation::updateOrCreate([
                    'quiz_id' => $quiz->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $quizData['title'],
                ]);

                if ($quiz->chapter_id) {
                    WebinarChapterItem::makeItem(
                        $quiz->creator_id,
                        $quiz->chapter_id,
                        $quiz->id,
                        WebinarChapterItem::$chapterQuiz
                    );
                }

                $webinar->sendNotificationToAllStudentsForNewQuizPublished($quiz);

                // --------------------------------------------------------------
                // QUESTIONS  →  quizzes[].questions[]
                // --------------------------------------------------------------
                foreach ($quizData['questions'] ?? [] as $questionKey => $questionData) {

                    if (empty($questionData['title'])) {
                        continue;
                    }

                    // image and video are mutually exclusive
                    if (!empty($questionData['image']) && !empty($questionData['video'])) {
                        \Log::warning("Question skipped (both image+video) on quiz {$quiz->id}");
                        continue;
                    }

                    // For multiple-choice, require at least one correct answer
                    $qType = $questionData['type'] ?? QuizzesQuestion::$multiple;
                    if ($qType == QuizzesQuestion::$multiple && !empty($questionData['answers'])) {
                        $hasCorrect = false;
                        foreach ($questionData['answers'] as $ans) {
                            if (isset($ans['correct'])) {
                                $hasCorrect = true;
                                break;
                            }
                        }
                        if (!$hasCorrect) {
                            \Log::warning("Question skipped (no correct answer) on quiz {$quiz->id}");
                            continue;
                        }
                    }

                    $order = QuizzesQuestion::where('quiz_id', $quiz->id)->count() + 1;

                    $questionImage = $questionData['image'] ?? null;
                    if ($request->hasFile("quizzes.{$quizKey}.questions.{$questionKey}.image")) {
                        $questionImage = $this->uploadFile($request->file("quizzes.{$quizKey}.questions.{$questionKey}.image"), $user->id);
                    }

                    $questionVideo = $questionData['video'] ?? null;
                    if ($request->hasFile("quizzes.{$quizKey}.questions.{$questionKey}.video")) {
                        $questionVideo = $this->uploadFile($request->file("quizzes.{$quizKey}.questions.{$questionKey}.video"), $user->id);
                    }

                    $quizQuestion = QuizzesQuestion::create([
                        'quiz_id' => $quiz->id,
                        'creator_id' => $user->id,
                        'grade' => $questionData['grade'] ?? 1,
                        'type' => $qType,
                        'image' => $questionImage,
                        'video' => $questionVideo,
                        'order' => $order,
                        'created_at' => time(),
                    ]);

                    if (!$quizQuestion) {
                        continue;
                    }

                    QuizzesQuestionTranslation::updateOrCreate([
                        'quizzes_question_id' => $quizQuestion->id,
                        'locale' => mb_strtolower($locale),
                    ], [
                        'title' => $questionData['title'],
                        'correct' => $questionData['correct'] ?? null,
                    ]);

                    // Increment quiz total mark
                    $quiz->increaseTotalMark($quizQuestion->grade);

                    // ----------------------------------------------------------
                    // ANSWERS  →  quizzes[].questions[].answers[]
                    // Only for multiple-choice type questions
                    // ----------------------------------------------------------
                    // if ($quizQuestion->type == QuizzesQuestion::$multiple && !empty($questionData['answers'])) {
                    if ($qType == QuizzesQuestion::$multiple && !empty($questionData['answers'])) {

                        foreach ($questionData['answers'] ?? [] as $answerKey => $answer) {

                            if (empty($answer['title']) && empty($answer['file'])) {
                                continue;
                            }

                            $answerImage = $answer['file'] ?? null;
                            if ($request->hasFile("quizzes.{$quizKey}.questions.{$questionKey}.answers.{$answerKey}.file")) {
                                $answerImage = $this->uploadFile($request->file("quizzes.{$quizKey}.questions.{$questionKey}.answers.{$answerKey}.file"), $user->id);
                            }

                            $questionAnswer = QuizzesQuestionsAnswer::create([
                                'question_id' => $quizQuestion->id,
                                'creator_id' => $user->id,
                                'image' => $answerImage,
                                'correct' => isset($answer['correct']) ? true : false,
                                'created_at' => time(),
                            ]);

                            if ($questionAnswer) {
                                QuizzesQuestionsAnswerTranslation::updateOrCreate([
                                    'quizzes_questions_answer_id' => $questionAnswer->id,
                                    'locale' => mb_strtolower($locale),
                                ], [
                                    'title' => $answer['title'],
                                ]);
                            }
                        }
                    }
                }
            }

        }

        try{
            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[item_title]' => $webinar->title,
                '[content_type]' => trans('admin/main.course'),
            ];

            sendNotification("new_item_created", $notifyOptions, 1);

            sendNotification('course_created', ['[c.title]' => $webinar->title], $user->id);

            sendNotification("content_review_request", $notifyOptions, 1);
        }
        catch (\Throwable $e) {
            \Log::error('Course creation notification failed: ' . $e->getMessage(), [
                'webinar_id' => $webinar->id ?? null,
                'user_id' => $user->id ?? null,
            ]);
            // intentionally swallow — notification failure must not block course creation
        }

        $data = [
            "success" => true,
            "message" => "Course created successfully"
        ];

        return apiResponse2(1, $data, []);


    }

    private function handleZoomApi($session, $user)
    {
        try {
            if (!empty(getFeaturesSettings('zoom_client_id')) and !empty(getFeaturesSettings('zoom_client_secret'))) {

                $meeting = (new ZoomOAuth())->makeMeeting($session);

                if ($meeting) {
                    return "ok";
                } else {
                    $session->delete();
                }
            }
        } catch (\Exception $exception) {
            $session->delete();
            //dd($exception);
        }

        return response()->json([
            'code' => 422,
            'status' => 'zoom_token_invalid',
            'zoom_error_msg' => trans('update.zoom_error_msg')
        ], 422);
    }

    private function handleBigBlueButtonApi($session, $user)
    {
        $this->handleBigBlueButtonConfigs();

        $createMeeting = \Bigbluebutton::initCreateMeeting([
            'meetingID' => $session->id,
            'meetingName' => $session->title,
            'attendeePW' => $session->api_secret,
            'moderatorPW' => $session->moderator_secret,
        ]);

        $createMeeting->setDuration($session->duration);
        $response = \Bigbluebutton::create($createMeeting);

        return true;
    }

    private function handleBigBlueButtonConfigs()
    {
        $settings = getFeaturesSettings();

        \Config::set("bigbluebutton.BBB_SECURITY_SALT", !empty($settings['bigbluebutton_security_salt']) ? $settings['bigbluebutton_security_salt'] : '');
        \Config::set("bigbluebutton.BBB_SERVER_BASE_URL", !empty($settings['bigbluebutton_server_base_url']) ? $settings['bigbluebutton_server_base_url'] : '');
    }

    private function saveAttachments($textLesson, $attachments)
    {
        if (!empty($attachments)) {

            if (!is_array($attachments)) {
                $attachments = [$attachments];
            }

            foreach ($attachments as $attachment_id) {
                if (!empty($attachment_id)) {
                    TextLessonAttachment::create([
                        'text_lesson_id' => $textLesson->id,
                        'file_id' => $attachment_id,
                        'created_at' => time(),
                    ]);
                }
            }
        }
    }

    private function handleAssignmentAttachments(
        array $attachments,
        int $creatorId,
        int $assignmentId,
        int $assignmentKey,
        int $chapterKey,
        $request
    ) {

        WebinarAssignmentAttachment::where('creator_id', $creatorId)
            ->where('assignment_id', $assignmentId)
            ->delete();

        foreach ($attachments as $key => $attachment)
        {

            // Skip null or empty entries
            if (empty($attachment)) {
                continue;
            }

            $filePath = null;
            $title    = 'attachment'; // default title

            if (is_array($attachment)) {
                // ✅ New format: { "title": "...", "attach": "path/or/upload" }
                $title = !empty($attachment['title']) ? $attachment['title'] : 'attachment';

                // Check if a real file was uploaded via multipart
                $fileKey = "chapters.{$chapterKey}.assignments.{$assignmentKey}.attachments.{$key}.attach";
                if ($request->hasFile($fileKey)) {
                    $filePath = $this->uploadFile($request->file($fileKey), $creatorId);
                } else {
                    $filePath = $attachment['attach'] ?? null;
                }

            } elseif (is_string($attachment)) {
                // Legacy format: plain file path string or file ID
                $fileKey = "chapters.{$chapterKey}.assignments.{$assignmentKey}.attachments.{$key}";
                if ($request->hasFile($fileKey)) {
                    $filePath = $this->uploadFile($request->file($fileKey), $creatorId);
                } else {
                    $filePath = $attachment;
                }
            }

            if (!empty($filePath)) {
                try {
                    $record = WebinarAssignmentAttachment::create([
                        'creator_id'    => $creatorId,
                        'assignment_id' => $assignmentId,
                        'title'         => $title,
                        'attach'        => $filePath,
                        'created_at'    => time(), // ✅ required because timestamps = false
                    ]);

                    // ✅ debug: log if record failed to save
                    if (!$record) {
                        \Log::error("WebinarAssignmentAttachment failed to save", [
                            'creator_id'    => $creatorId,
                            'assignment_id' => $assignmentId,
                            'title'         => $title,
                            'attach'        => $filePath,
                        ]);
                    }

                } catch (\Exception $e) {
                    \Log::error("WebinarAssignmentAttachment exception: " . $e->getMessage(), [
                        'creator_id'    => $creatorId,
                        'assignment_id' => $assignmentId,
                        'filePath'      => $filePath,
                    ]);
                }
            }
        }
    }

    private function uploadFile($file, $userId)
    {
        if (!($file instanceof \Illuminate\Http\UploadedFile)) {
            // Return as-is if it's already a plain string path (or null)
            return $file;
        }

        $uploadPath = public_path('store/' . $userId);

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Use timestamp + original name so filenames stay human-readable and
        // do not collide (matches the pattern already visible in public/store/).
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($uploadPath, $fileName);

        return '/store/' . $userId . '/' . $fileName;
    }

}
