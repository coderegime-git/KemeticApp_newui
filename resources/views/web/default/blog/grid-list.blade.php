@php
use Illuminate\Support\Facades\DB;

$userId = auth()->id();
$systemIp = getSystemIP();

// Get total stats (for all users)
$totalStats = DB::table('stats')
->where('blog_id', $post->id)
->selectRaw('SUM(likes) as total_likes, SUM(views) as total_views, SUM(shares) as total_shares')
->first();

// Check if the system IP or logged-in user has interacted
$userStats = DB::table('stats')
->where('blog_id', $post->id)
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

<div class="blog-grid-card h-100">
    <div class="blog-grid-image">

        <div class="badges-lists">
            @include('web.default.includes.product_custom_badge', ['itemTarget' => $post])
        </div>


        <img src="{{ $post->image }}" class="img-cover" alt="{{ $post->title }}">

        <span class="badge created-at d-flex align-items-center">
            <i data-feather="calendar" width="20" height="20" class="mr-5"></i>
            <span>{{ dateTimeFormat($post->created_at, 'j M Y') }}</span>
        </span>
    </div>

    <div class="blog-grid-detail">
        <div class="mt-10 mb-20 align-items-center d-flex flex-fill gap-2 justify-content-around">
            <div class="d-flex align-items-center pointer">
                <i class="fas fa-thumbs-up interaction-icon{{ $post->id }} {{ $likeIconClass }}" data-type="like"></i>
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
        <a href="{{ $post->getUrl() }}">
            <h3 class="blog-grid-title mt-10">{{ $post->title }}</h3>
        </a>

        <div class="mt-20 blog-grid-desc">{!! truncate(strip_tags($post->description), 160) !!}</div>

        <div class="blog-grid-footer d-flex align-items-center justify-content-between mt-15">
            <span>
                <i data-feather="user" width="20" height="20" class=""></i>
                 @if(!empty($post->author->full_name))
                <span class="ml-5">{{ $post->author->full_name }}</span>
                 @endif
              </span>

            <span class="d-flex align-items-center">
                <i data-feather="message-square" width="20" height="20" class=""></i>
                <span class="ml-5">{{ $post->comments_count }}</span>
            </span>
        </div>
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
                        <i class="fa-facebook-f fab"></i>
                    </a>
                    <a id="twitter-share" target="_blank" class="btn btn_twitter_share mx-2">
                        <i class="fa-twitter fab"></i>
                    </a>
                    <a id="linkedin-share" target="_blank" class="btn btn_linkedin_share mx-2">
                        <i class="fa-linkedin fab"></i>
                    </a>
                    <a id="whatsapp-share" target="_blank" class="btn btn_whatsapp_share mx-2">
                        <i class="fa-whatsapp fab"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
 @include('web.default.blog.share_modal')
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

<!-- jQuery (Google CDN) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script>
    $(document).ready(function() {
        $('.interaction-icon{{ $post->id }}').click(function() {
            let icon = $(this);
            let type = icon.data('type'); // 'like', 'view', 'share'
            let countSpan = icon.siblings('span');
            let count = parseInt(countSpan.text()); // Get current count
            let isActive = icon.hasClass('text-primary'); // Check if it's already active

            $.ajax({
                url: "/update-stats",
                type: 'POST',
                data: {
                    post_id: "{{ $post->id }}",
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
