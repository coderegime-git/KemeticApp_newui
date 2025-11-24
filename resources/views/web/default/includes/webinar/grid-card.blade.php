<div class="webinar-card card h-100">
    <div class="border-0 card-header p-0">
        <div class="image-box">
            <div class="badges-lists">
                @if($webinar->bestTicket() < $webinar->price)
                    <span class="badge badge-danger">{{ trans('public.offer',['off' => $webinar->bestTicket(true)['percent']]) }}</span>
                    @elseif(empty($isFeature) and !empty($webinar->feature))
                    <span class="badge badge-warning">{{ trans('home.featured') }}</span>
                    @elseif($webinar->type == 'webinar')
                    @if($webinar->start_date > time())
                    <span class="badge badge-primary">{{ trans('panel.not_conducted') }}</span>
                    @elseif($webinar->isProgressing())
                    <span class="badge badge-secondary">{{ trans('webinars.in_progress') }}</span>
                    @else
                    <span class="badge badge-secondary">{{ trans('public.finished') }}</span>
                    @endif
                    @elseif(!empty($webinar->type))
                    <span class="badge badge-primary">{{ trans('webinars.'.$webinar->type) }}</span>
                    @endif

                    @include('web.default.includes.product_custom_badge', ['itemTarget' => $webinar])
            </div>

            <a href="{{ $webinar->getUrl() }}">
                <img src="{{ $webinar->getImage() }}" class="img-cover" alt="{{ $webinar->title }}">
            </a>


                {{-- @if($webinar->checkShowProgress())
                        <div class="progress">
                            <span class="progress-bar" style="width: {{ $webinar->getProgress() }}%"></span>
            </div>
            @endif
            @if($webinar->type == 'webinar')
            <a href="{{ $webinar->addToCalendarLink() }}" target="_blank" class="webinar-notify d-flex align-items-center justify-content-center">
                <i data-feather="bell" width="20" height="20" class="webinar-icon"></i>
            </a>
            @endif --}}
        </div>
    </div>
    <div class="card-body webinar-card-body">
    <div class="align-items-center d-flex flex-wrap gap10 justify-content-between">
            @include(getTemplate() . '.includes.webinar.rate',['rate' => $webinar->getRate()])
            
            @php
            use Illuminate\Support\Facades\DB;
    
            $userId = auth()->id();
            $systemIp = getSystemIP();
    
            // Get total stats (for all users)
            $totalStats = DB::table('stats')
            ->where('webinar_id', $webinar->id)
            ->selectRaw('SUM(likes) as total_likes, SUM(views) as total_views, SUM(shares) as total_shares')
            ->first();
    
            // Check if the system IP or logged-in user has interacted
            $userStats = DB::table('stats')
            ->where('webinar_id', $webinar->id)
            ->when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
            }, function ($query) use ($systemIp) {
            return $query->where('ip_address', $systemIp);
            })
            ->selectRaw('SUM(likes) as user_likes, SUM(views) as user_views, SUM(shares) as user_shares')
            ->first();
    
            // Define icon classes based on user/system IP interaction
            $likeIconClass = ($userStats->user_likes ?? 0) > 0 ? 'text-primary' : 'stats_icon';
            $viewIconClass = ($userStats->user_views ?? 0) > 0 ? 'text-primary' : 'stats_icon';
            $shareIconClass = ($userStats->user_shares ?? 0) > 0 ? 'text-primary' : 'stats_icon';
            @endphp
    
            <div class="align-items-center d-flex flex-fill gap-2 justify-content-around">
                <div class="d-flex align-items-center pointer">
                    <i class="fas fa-thumbs-up interaction-icon{{ $webinar->id }} {{ $likeIconClass }}" data-type="like"></i>
                    <span class="ml-1 font-14">{{ $totalStats->total_likes ?? 0 }}</span>
                </div>
    
                <div class="d-flex align-items-center">
                    <i class="fas fa-eye {{ $viewIconClass }}" data-type="view"></i>
                    <span class="ml-1 font-14">{{ $totalStats->total_views ?? 0 }}</span>
                </div>
    
                <div class="js-share-blog d-flex align-items-center cursor-pointer">
                    <div class="icon-box ">
                        <i class="fa-share fas text-primary" width="20" height="20"></i>
                    </div>
                </div>
            </div>
            
            <div class="webinar-price-box ">
                @if(!empty($isRewardCourses) and !empty($webinar->points))
                <span class="text-warning real font-14">{{ $webinar->points }} {{ trans('update.points') }}</span>
                @elseif(!empty($webinar->price) and $webinar->price > 0)
                @if($webinar->bestTicket() < $webinar->price)
                    <span class="real">{{ handlePrice($webinar->bestTicket(), true, true, false, null, true) }}</span>
                    <span class="off ml-10">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                    @else
                    <span class="real">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                    @endif
                    @else
                    <span class="real font-14">{{ trans('public.free') }}</span>
                    @endif
            </div>
        </div>
        <a href="{{ $webinar->getUrl() }}">
            <h3 class="mt-15 webinar-title font-weight-bold font-16 text-dark-blue">{{ clean($webinar->title,'title') }}</h3>
        </a>
        @if(!empty($webinar->category))
        <span class="d-block font-14 mt-10">{{ trans('public.in') }} <a href="{{ $webinar->category->getUrl() }}" target="_blank" class="text-decoration-underline">{{ $webinar->category->title }}</a></span>
        @endif

        <div class="d-flex justify-content-between mt-20">
            <div class="d-flex align-items-center">
                <i data-feather="clock" width="20" height="20" class="webinar-icon"></i>
                <span class="duration font-14 ml-5">{{ convertMinutesToHourAndMinute($webinar->duration) }} {{ trans('home.hours') }}</span>
            </div>

            <div class="vertical-line mx-15"></div>

            <div class="d-flex align-items-center">
                <i data-feather="calendar" width="20" height="20" class="webinar-icon"></i>
                <span class="date-published font-14 ml-5">{{ dateTimeFormat(!empty($webinar->start_date) ? $webinar->start_date : $webinar->created_at,'j M Y') }}</span>
            </div>
        </div>
        <div class="user-inline-avatar d-flex align-items-center mt-15">
            <div class="avatar bg-gray200">
                <img src="{{ $webinar->teacher->getAvatar() }}" class="img-cover" alt="{{ $webinar->teacher->full_name }}">
            </div>
            <a href="{{ $webinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $webinar->teacher->full_name }}</a>
        </div>
    </div>
<div class="webinar-card-body border-0 pt-0">
<a href="{{ $webinar->getUrl() }}" class="btn btn-primary btn-block btn-sm">Enroll Course</a>
</div>
</div>

<!-- Share modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share This Webinar</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class='mb-10'>Share this webinar on social media:</p>
                <div class="d-flex justify-content-center gap10">
                    <a id="facebook-share" target="_blank" class="btn btn_facebook_share mx-2">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a id="twitter-share" target="_blank" class="btn btn_twitter_share mx-2">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a id="linkedin-share" target="_blank" class="btn btn_linkedin_share mx-2">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a id="whatsapp-share" target="_blank" class="btn btn_whatsapp_share mx-2">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

 @include('web.default.blog.share_webinar_modal')
<script>
    $(document).ready(function() {
        $(' #shareModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var url = button.data('url');
            $('#facebook-share').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url));
            $('#twitter-share').attr('href', 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(url));
            $('#linkedin-share').attr('href', 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(url));
            // $('#whatsapp-share').attr('href', 'https://wa.me/?text=' + encodeURIComponent(url));
            $('#whatsapp-share').attr('href', 'https://web.whatsapp.com/send?text=' + encodeURIComponent(url));
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.interaction-icon{{ $webinar->id }}').click(function() {
            let icon = $(this);
            let type = icon.data('type'); // 'like', 'view', 'share'
            let countSpan = icon.siblings('span');
            let count = parseInt(countSpan.text()); // Get current count
            let isActive = icon.hasClass('text-primary'); // Check if it's already active

            $.ajax({
                url: "/update-stats",
                type: 'POST',
                data: {
                    webinar_id: "{{ $webinar->id }}",
                    type: type,
                    action: isActive ? 'remove' : 'add', // Toggle between add/remove
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        // Toggle class
                        icon.toggleClass('text-primary stats_icon');

                        // Toggle count
                        countSpan.text(isActive ? count - 1 : count + 1);
                    }
                }
            });
        });
    });
</script>
    <script src="/assets/default/js/parts/blog.min.js"></script>