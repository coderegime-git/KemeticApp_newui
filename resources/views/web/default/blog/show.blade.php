@extends('web.default.layouts.app')

@section('content')

 
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

<!-- Reading progress -->
<div class="articledetail-progress" id="articledetail-progress"></div>

<header class="articledetail-hero">
  <div class="articledetail-bg"></div>
  <div class="articledetail-inner">
    <div>
      <h1 class="articledetail-title">{{$post->title}}</h1>
      <div class="articledetail-meta">
         @php
        $rate = $post->getRate();
            $i = 5;
        @endphp

        <div style="color:#ffd769;font-weight:900">
            @while(--$i >= 5 - $rate)
                ★
            @endwhile
            @while($i-- >= 0)
                ☆
            @endwhile
            <!-- <span style="color:#fff;font-weight:900;margin-left:6px">{{$post->reviews->count()}} reviews</span> -->
           </div>
        <span>{{$post->reviews->count()}} reviews</span>
        <!-- <span class="articledetail-badge">12 min read</span> -->
        <span class="articledetail-badge">{{$post->category->title}}</span>
      </div>
    </div>
    <!-- <div class="articledetail-cta">
      <button class="articledetail-btn-gold"><span class="material-symbols-outlined">book_2</span> Start Reading</button>
    </div> -->
  </div>
</header>

<!-- FLOATING ACTION RAIL -->
<!-- <div class="articledetail-rail">
  <button class="articledetail-like" title="Like"><span class="material-symbols-outlined">favorite</span></button>
  <button class="articledetail-save" title="Save"><span class="material-symbols-outlined">bookmark</span></button>
  <button class="articledetail-gift" title="Gift"><span class="material-symbols-outlined">card_giftcard</span></button>
  <button class="articledetail-share" title="Share"><span class="material-symbols-outlined">ios_share</span></button>
</div> -->

<!-- CONTENT -->
<main class="articledetail-wrap">
  <div class="articledetail-grid">

    <!-- ARTICLE -->
    <article class="articledetail-article" id="articledetail-article">
      <div class="articledetail-body">
         <div class="course-description">
        {!! nl2br($post->content) !!}
    </div>
        
        <!-- <div class="articledetail-sticky">
          <span class="articledetail-pill">Free with Membership • €1/mo</span>
          <button class="articledetail-btn-gold"><span class="material-symbols-outlined">book_2</span> Continue Reading</button>
        </div> -->
      </div>
    </article>

    <!-- SIDEBAR -->
    <aside class="articledetail-side">
      <div class="articledetail-card">
        <div class="articledetail-hd">Author</div>
        <div class="articledetail-bd">
          <div class="articledetail-author">
            <img src="{{ $post->author->getAvatar(100) }}" class="img-cover" alt="">
            <div>
              <div style="font-weight:900">{{ $post->author->full_name }}</div>
              <div class="articledetail-badge" style="margin-top:6px">{{ $post->author->role->caption }}</div>
            </div>
          </div>
          <p style="color:#ffffffbb; font-size:14px; margin:12px 0 0">Researcher & guide exploring sacred tech, acoustics, and lost engineering.</p>
        </div>
      </div>

      <div class="articledetail-card">
        <div class="articledetail-hd">Membership</div>
        <div class="articledetail-bd">
          <p class="muted" style="color:#ffffffcc">Unlock unlimited Articles, Reels, Courses & Live for just €1/mo.</p>
          <button class="articledetail-btn-gold" style="width:100%; justify-content:center; margin-top:8px">
            @if(auth()->check())
            <a href="/membership"><span class="material-symbols-outlined">workspace_premium</span> Join Now</a>
          @else
            <a href="/register"><span class="material-symbols-outlined">workspace_premium</span> Join Now</a>
          @endif
            
          </button>
        </div>
      </div>

      <div class="articledetail-card">
        <div class="articledetail-hd">Related</div>
        <div class="articledetail-bd articledetail-rel">
            @foreach($popularPosts as $popularPost)
          <a href="{{ $popularPost->getUrl() }}">
            <img src="{{ $popularPost->image }}" class="img-cover rounded" alt="{{ $popularPost->title }}">
            <div>
              <div style="font-weight:900">{{ truncate($popularPost->title,40) }}</div>
              <div style="font-size:12px; color:#ffffff99">{{$popularPost->category->title}}</div>
            </div>
          </a>
          @endforeach
          
        </div>
      </div>
    </aside>
  </div>
</main>

<div class="modal fade" style="display:none" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shareModalLabel">Share This Article</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class='mb-3'>Share this article on social media:</p>
        <div class="d-flex justify-content-center gap-3">
          <a id="facebook-share" target="_blank" class="btn btn_facebook_share">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a id="twitter-share" target="_blank" class="btn btn_twitter_share">
            <i class="fab fa-twitter"></i>
          </a>
          <a id="linkedin-share" target="_blank" class="btn btn_linkedin_share">
            <i class="fab fa-linkedin"></i>
          </a>
          <a id="whatsapp-share" target="_blank" class="btn btn_whatsapp_share">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Reading progress bar
  const progress = document.getElementById('articledetail-progress');
  const article = document.getElementById('articledetail-article');
  const onScroll = () => {
    const rect = article.getBoundingClientRect();
    const win = window.innerHeight;
    const total = article.scrollHeight - win * 0.6; // after header overlap
    const scrolled = Math.min(Math.max(window.scrollY - article.offsetTop + win * 0.4, 0), total);
    const pct = (scrolled / total) * 100;
    progress.style.width = pct + '%';
  };
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();

  // Dummy actions for rail buttons
  document.querySelectorAll('.articledetail-rail button').forEach(b=>{
    b.addEventListener('click', ()=> {
      b.animate([{transform:'scale(1)'},{transform:'scale(1.15)'},{transform:'scale(1)'}], {duration:220});
    });
  });

   $(document).ready(function() {
    // Track view on page load
    $.ajax({
      url: "/update-stats",
      type: 'POST',
      data: {
        post_id: "{{ $post->id }}",
        type: 'view',
        action: 'add',
        _token: "{{ csrf_token() }}"
      },
      success: function(response) {
        if (response.success) {
          let countSpan = $('.view-count-{{ $post->id }}');
          countSpan.text(response.updated_views);
          $('.stat-item.view').addClass('active');
        }
      }
    });
    
    // Handle like, save, and share interactions
    $('.stat-item, .articledetail-rail button.articledetail-like, .articledetail-rail button.articledetail-save').click(function() {
      let element = $(this);
      let type = element.data('type'); // 'like', 'save'
      let isActive = element.hasClass('active');
      
      // For rail buttons, update corresponding stat item
      if (element.hasClass('articledetail-rail')) {
        element = $('.stat-item.' + type);
        isActive = element.hasClass('active');
      }
      
      let countSpan = element.find('span:last');
      let count = parseInt(countSpan.text());
      
      $.ajax({
        url: "/update-stats",
        type: 'POST',
        data: {
          post_id: "{{ $post->id }}",
          type: type,
          action: isActive ? 'remove' : 'add',
          _token: "{{ csrf_token() }}"
        },
        success: function(response) {
          if (response.success) {
            // Toggle active class
            element.toggleClass('active');
            
            // Update rail button if needed
            $('.articledetail-rail button.' + type).toggleClass('active');
            
            // Update count
            countSpan.text(isActive ? count - 1 : count + 1);
          }
        }
      });
    });
    
    // Share modal setup
    $('#shareModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var url = button.data('url');
      $('#facebook-share').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url));
      $('#twitter-share').attr('href', 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(url));
      $('#linkedin-share').attr('href', 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(url));
      $('#whatsapp-share').attr('href', 'https://wa.me/?text=' + encodeURIComponent(url));
    });
    
    // Handle share count
    $('.stat-item.share, .articledetail-rail button.articledetail-share').click(function() {
      let element = $('.stat-item.share');
      let isActive = element.hasClass('active');
      let countSpan = element.find('span:last');
      let count = parseInt(countSpan.text());
      
      if (!isActive) {
        $.ajax({
          url: "/update-stats",
          type: 'POST',
          data: {
            post_id: "{{ $post->id }}",
            type: 'share',
            action: 'add',
            _token: "{{ csrf_token() }}"
          },
          success: function(response) {
            if (response.success) {
              element.addClass('active');
              $('.articledetail-rail button.articledetail-share').addClass('active');
              countSpan.text(count + 1);
            }
          }
        });
      }
    });
  });
</script>