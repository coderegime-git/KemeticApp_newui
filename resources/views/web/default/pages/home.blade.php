@extends('web.default.layouts.app')

@section('content')
<style>

/* Video Player Modal */
#videoPlayerModal .modal-dialog {
  max-width: 90%;
  max-height: 90vh;
}

#videoPlayerModal .modal-content {
  background: #000;
  border-radius: 0;
}

#videoPlayerModal .modal-body {
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #000;
}

#videoPlayerModal #videoPlayer {
  width: 100%;
  height: auto;
  max-height: 80vh;
}

#videoPlayerModal .modal-header {
  background: rgba(0, 0, 0, 0.8);
  border-bottom: 1px solid #333;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  z-index: 10;
}

#videoPlayerModal .modal-title {
  color: white;
}

#videoPlayerModal .btn-close {
  filter: brightness(0) invert(1);
}

/* Membership Popup Styles */
.kemetic-overlay{
  position:fixed;
  inset:0;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:24px;
  background: rgba(0,0,0,.85);
  -webkit-backdrop-filter: blur(10px);
  backdrop-filter: blur(10px);
  z-index:9999;
}

.kemetic-modal{
  width:min(980px, 100%);
  border-radius: 24px;
  background: linear-gradient(180deg, #171728, #0F0F18);
  border:1px solid rgba(255,255,255,.10);
  box-shadow: 0 22px 40px rgba(0,0,0,.55);
  overflow:hidden;
  position:relative;
}

.kemetic-modal-inner{
  padding:22px 26px 18px;
}

.kemetic-top{
  display:flex;
  gap:14px;
  align-items:flex-start;
}

.kemetic-title{
  flex:1;
  font-weight:900;
  letter-spacing:.2px;
  font-size:34px;
  line-height:1.1;
  margin:0;
  color:#FFFFFF;
}

.kemetic-close{
  border:0;
  background:transparent;
  color:rgba(255,255,255,.8);
  font-size:22px;
  cursor:pointer;
  padding:8px 80px;
  border-radius:12px;
}
.kemetic-close:hover{ background: rgba(255,255,255,.06); }

.kemetic-subtitle{
  margin:8px 0 0;
  color:rgba(255,255,255,.82);
  font-size:16px;
  line-height:1.35;
  max-width: 820px;
}

.chakra-divider{
  height:2px;
  border-radius:999px;
  margin:16px 0 18px;
  background: linear-gradient(90deg,
    #FF3B30,
    #FF9500,
    #FFCC00,
    #34C759,
    #007AFF,
    #5856D6,
    #AF52DE
  );
  opacity:.95;
}

.kemetic-grid{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:30px;
  align-items:start;
}

@media (max-width: 860px){
  .kemetic-title{ font-size:28px; }
  .kemetic-grid{ grid-template-columns: 1fr; }
}

.panel{
  border:1px solid rgba(255,255,255,.08);
  background: rgba(255,255,255,.03);
  border-radius: 18px;
  padding:20px;
}

.panel h3{
  margin:0 0 20px;
  font-size:18px;
  font-weight:900;
  color:rgba(255,255,255,.92);
}

.benefit{
  display:flex;
  gap:10px;
  align-items:flex-start;
  margin:12px 0;
}

.check{
  width:22px;
  height:22px;
  border-radius:999px;
  display:grid;
  place-items:center;
  border:1px solid rgba(255,255,255,.18);
  flex:0 0 auto;
}
.check svg{ width:14px; height:14px; }

/* Rotate chakra colors through the check bullets */
.benefit:nth-child(1) .check{ border-color: rgba(52, 199, 89, 0.7); background: rgba(52, 199, 89, 0.16); }
.benefit:nth-child(2) .check{ border-color: rgba(0, 122, 255, 0.7); background: rgba(0, 122, 255, 0.16); }
.benefit:nth-child(3) .check{ border-color: rgba(175, 82, 222, 0.7); background: rgba(175, 82, 222, 0.16); }
.benefit:nth-child(4) .check{ border-color: rgba(255, 204, 0, 0.7); background: rgba(255, 204, 0, 0.16); }
.benefit:nth-child(5) .check{ border-color: rgba(255, 149, 0, 0.7); background: rgba(255, 149, 0, 0.16); }

.benefit p{
  margin:0;
  color:rgba(255,255,255,.86);
  line-height:1.25;
  font-size:15px;
}

.social-proof{
  margin-top:20px;
  display:flex;
  gap:10px;
  align-items:center;
  padding:14px 16px;
  border-radius:14px;
  border:1px solid rgba(255,255,255,.10);
  background: rgba(255,255,255,.04);
  color:rgba(255,255,255,.82);
  font-weight:700;
}

.social-proof .dot{
  width:10px; height:10px; border-radius:50%;
  background: linear-gradient(180deg, #34C759, #007AFF);
  box-shadow: 0 0 18px rgba(52,199,89,.35);
}

/* Plans Grid Layout - Like Table */
.plans-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
  margin-bottom: 25px;
}

.plan-card {
  cursor: pointer;
  border-radius: 16px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(255,255,255,.04);
  padding: 20px 15px;
  text-align: center;
  transition: all 0.2s ease;
  position: relative;
  user-select: none;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 180px;
}

.plan-card:hover {
  transform: translateY(-2px);
  background: rgba(255,255,255,.06);
  border-color: rgba(255,255,255,.2);
}

.plan-card.selected {
  border: 2px solid;
  background: rgba(255,255,255,.08);
  box-shadow: 0 8px 25px rgba(0,0,0,.3);
}

.plan-card[data-plan="monthly"].selected {
  border-color: #34C759;
}

.plan-card[data-plan="yearly"].selected {
  border-color: #007AFF;
}

.plan-card[data-plan="lifetime"].selected {
  border-color: #AF52DE;
}

.plan-name {
  font-weight: 900;
  color: rgba(255,255,255,.92);
  font-size: 18px;
  margin: 0 0 10px 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.plan-price {
  font-weight: 900;
  font-size: 28px;
  color: rgba(255,255,255,.95);
  margin: 0 0 5px 0;
}

.plan-period {
  font-size: 14px;
  font-weight: 700;
  color: rgba(255,255,255,.75);
  margin-bottom: 15px;
}

.plan-hint {
  color: rgba(255,255,255,.70);
  font-size: 13px;
  margin: 0;
  line-height: 1.4;
}

.badge {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 4px 12px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 900;
  letter-spacing: .3px;
  color: rgba(255,255,255,.95);
  background: linear-gradient(90deg, #FF9500, #FFCC00);
  box-shadow: 0 4px 12px rgba(255, 149, 0, 0.3);
}

/* No checkbox style - removed */

/* CTA area */
.cta{
  margin-top: 20px;
  display:flex;
  flex-direction:column;
  gap:12px;
  align-items:stretch;
}

.btn-primary{
  height:55px;
  border-radius:999px;
  border:0;
  cursor:pointer;
  font-weight:900;
  font-size:17px;
  letter-spacing:.3px;
  color:#0B0B12;
  background: linear-gradient(90deg,
    #FF3B30,
    #FF9500,
    #FFCC00,
    #34C759,
    #007AFF,
    #5856D6,
    #AF52DE
  );
  box-shadow: 0 14px 28px rgba(0,0,0,.35);
  transition: all 0.2s ease;
}
.btn-primary:hover{ 
  filter: brightness(1.1);
  transform: translateY(-1px);
  box-shadow: 0 18px 32px rgba(0,0,0,.4);
}
.btn-primary:active{ transform: translateY(0px); }

.btn-secondary{
  background:transparent;
  border:0;
  cursor:pointer;
  color:rgba(255,255,255,.78);
  font-weight:700;
  font-size:15px;
  padding:10px 12px;
  border-radius:12px;
  transition: background 0.2s ease;
}
.btn-secondary:hover{ background: rgba(255,255,255,.08); }
</style>
    
<header class="home-topnav">
  <div class="home-nav-row">
    <!-- <div class="home-brand">Kemetic.app</div>
    <nav class="home-nav-links">
      <a href="#">Livestreams</a>
      <a href="#">Courses</a>
      <a href="#">Reels</a>
      <a href="#">Shop</a>
      <a href="#">Television</a>
      <a href="#">Articles</a>
    </nav> -->
    <div class="home-nav-cta">
       @if(auth()->check())
        <a href="/membership"><span class="home-chip">{{ trans('Free') }} • {{ trans('Join') }}</span></a>
        <a href="/membership"><button class="home-btn" style="margin-left:10px">€1/mo or €10/yr</button></a>
      @else
        <a href="/login"><span class="home-chip">{{ trans('Free') }} • {{ trans('Join') }}</span></a>
        <a href="/login"><button class="home-btn" style="margin-left:10px">€1/mo or €10/yr</button></a>
      @endif
    </div>
  </div>
</header>

    <main class="home-container">
      <!-- HERO: Course Spotlight -->
      @if(!empty($bestRateWebinars))
      @foreach($bestRateWebinars as $bestRateWebinar)
      <section class="home-hero">
        <div class="home-hero-wrap">
          <img src="{{ $bestRateWebinar->getImage() }}" class="img-cover" alt="{{ $bestRateWebinar->title }}">
            
            @php
            
    
            $userId = auth()->id();
            $systemIp = getSystemIP();
    
            // Get total stats (for all users)
            $totalStats = DB::table('stats')
            ->where('webinar_id', $bestRateWebinar->id)
            ->selectRaw('SUM(likes) as total_likes, SUM(views) as total_views, SUM(shares) as total_shares')
            ->first();
    
            // Check if the system IP or logged-in user has interacted
            $userStats = DB::table('stats')
            ->where('webinar_id', $bestRateWebinar->id)
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

          <div class="home-rail">
            <div class="home-pill"><span class="home-icon home-like">❤️</span><span class="home-count">{{ $totalStats->total_likes ?? 0 }}</span></div>
            <div class="home-pill"><span class="home-icon home-play">▶︎</span><span class="home-count">{{ $totalStats->total_views ?? 0 }}</span></div>
            <div class="home-pill"><span class="home-icon home-share">↗︎</span><span class="home-count">{{ $totalStats->total_shares ?? 0 }}</span></div>
          </div>
          <div class="home-hero-content">
            <h1 class="home-hero-title">{{ clean($bestRateWebinar->title,'title') }}</h1>
            <p class="home-hero-sub">{{ convertMinutesToHourAndMinute($bestRateWebinar->duration) }} {{ trans('home.hours') }} · <a href="{{ $bestRateWebinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $bestRateWebinar->teacher->full_name }}</p>
            <div class="home-stars">
              <span class="home-star home-s1"></span><span class="home-star home-s2"></span>
              <span class="home-star home-s3"></span><span class="home-star home-s4"></span>
              <span class="home-star home-s5"></span>
              <span style="margin-left:10px;opacity:.85;font-weight:800">{{ $totalStats->total_likes ?? 0 }}+</span>
            </div>
            <a href="{{ $bestRateWebinar->getUrl() }}" ><button class="home-btn">{{ trans('Join Course') }}</button></a>
          </div>
        </div>
      </section>
      
      @endforeach
      @endif

      <!-- Marquee Section -->
      <!-- <section class="slider-all">
        <div class="container overflow-hidden" data-aos="fade-up">
          <div class="row">
            <div class="col-12">
              <div class="marquee-block marquee1">
                <ul class="marquee-item-list ps-0">
                  <li>
                    <a href="https://apps.apple.com/nl/app/kemetic-app/id6479200304?l=en-GB" target="_blank"><img src="/assets/default/img/home/apple-pay.svg" class="" alt="apple-pay"></a>
                  </li>
                  <li>
                    <a href="https://play.google.com/store/apps/details?id=com.app.kemeticapp&pcampaignid=web_share&pli=1/" target="_blank"><img src="/assets/default/img/home/google-pay.svg" class="" alt="google-pay"></a>
                  </li>
                  <li>
                    <img src="/assets/default/img/home/connection.svg" class="" alt="connection-img">
                  </li>
                  <li> 
                    <img src="/assets/default/img/home/payment.svg" class="" alt="connection-img">
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section> -->

      <!-- GLOBAL TOP RANKED REELS (Featured Classes) -->
      @if(!empty($reels) and !$reels->isEmpty())
      <section>
        <div class="home-row-head">
          <h2>{{ trans('Global Top Ranked Portals') }}</h2>
          <span class="home-chip">#1–#10</span>
        </div>
        <div class="home-scroller" id="reels">
          @foreach($reels as $index => $reel)
          <article class="home-card" aria-label="Reel">
            <div class="home-thumb-round">
               <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video>
              <!-- <span class="home-badge-live">#{{ $index + 1 }}</span> -->
            </div>
            <div class="home-meta">
              <span>⭐</span><span class="home-gold">{{ $reel->likes_count ?: 0 }}</span>
            </div>
            <div class="home-actions">
                <button class="home-btn play-video-btn" 
                    data-video-url="{{ $reel->video_url }}" 
                    data-title="{{ $reel->title }}" 
                    data-thumbnail="{{ $reel->thumbnail_url }}">
              Watch
            </button>
              <!-- <a href="{{ $reel->id }}"><button class="home-btn">{{ trans('Watch') }}</button></a> -->
              <div class="home-chakra">
                <span class="home-dot"></span><span class="home-dot"></span><span class="home-dot"></span><span class="home-dot"></span><span class="home-dot"></span>
              </div>
            </div>
          </article>
          @endforeach
        </div>
      </section>
      @endif

      <!-- CLASSES (Latest Classes) -->
      @if(!empty($latestWebinars) and !$latestWebinars->isEmpty())
      <section>
        <div class="home-row-head"><h2>{{ trans('Courses') }}</h2><a href="/classes" class="home-chip">{{ trans('View all') }}</a></div>
        <div class="home-grid">
          @foreach($latestWebinars as $latestWebinar)
          <div class="home-tile">
            <div class="home-thumb"><img src="{{ $latestWebinar->getImage() }}" alt="{{ $latestWebinar->title }}"></div>
            <div class="home-title">{{ Str::limit($latestWebinar->title, 13, '..') }}</div>
            <div class="home-meta"><span>⏱</span><span>{{ convertMinutesToHourAndMinute($latestWebinar->duration) }} {{ trans('hours') }}</span></div>
            <div class="home-actions">
              <a href="{{ $latestWebinar->getUrl() }}"><button class="home-btn">{{ trans('Enroll Now') }}</button></a>
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- SHOP (Products) -->
      @if(!empty($newProducts) and !$newProducts->isEmpty())
      <section>
        <div class="home-row-head"><h2>{{ trans('Shop') }}</h2><a href="/products" class="home-chip">{{ trans('See all') }}</a></div>
        <div class="home-grid">
          @foreach($newProducts as $product)
          <div class="home-tile">
            <div class="home-thumb"><img src="{{ $product->thumbnail }}" alt="{{ $product->title }}"></div>
            <div class="home-title">{{ Str::limit($product->title, 13, '..') }} </div>
            <div class="home-price">{{ handlePrice($product->price, true, true, false, null, true) }}</div>
            <div class="home-actions">
                <a href="{{ $product->getUrl() }}"><button class="home-btn">{{ trans('Add to Cart') }}</button></a>
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- BOOKS (Bundles) -->
      @if(!empty($books) and !$books->isEmpty())
      <section>
        <div class="home-row-head"><h2>Scrolls</h2><a href="/classes?type[]=bundle" class="home-chip">{{ trans('Browse') }}</a></div>
        <div class="home-grid">
          @foreach($books as $book)
          <div class="home-tile">
            <div class="home-thumb"><img src="{{ $book->getImage() }}" alt="{{ $book->title }}"></div>
            <div class="home-title">{{ Str::limit($book->title, 13, '..') }} </div>
            <div class="home-meta">
              @if($book->price == 0)
                <span class="home-chip">{{ trans('Free with Membership') }}</span>
              @endif
            </div>
            <div class="home-actions">
              @if($book->price == 0)
                <a href="{{ $book->getUrl() }}"><button class="home-btn">{{ trans('Read eBook') }}</button></a>
              @else
                <a href="{{ $book->getUrl() }}"><button class="home-btn">{{ trans('Buy Audiobook') }}</button></a>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- LIVE NOW (Livestreams) -->
      <section>
        <div class="home-row-head"><h2>{{ trans('Live Now') }}</h2><a class="home-chip" href="#">{{ trans('Join Live') }}</a></div>
        <div class="home-tv">
          <div class="home-tile" style="position:relative;text-align:center">
            <div class="home-thumb-round"><img src="https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=900" alt="image"></div>
            <div class="home-badge-live">LIVE</div>
            <div class="home-meta" style="justify-content:center"><span>👁️</span><span class="home-gold">9.2k</span></div>
            <button class="home-btn">{{ trans('Join Live') }}</button>
          </div>
          <div class="home-tile" style="text-align:center">
            <div class="home-thumb-round"><img src="https://images.unsplash.com/photo-1516826957135-700dedea698c?q=80&w=900" alt="image"></div>
            <div class="home-meta" style="justify-content:center"><span>⭐</span><span class="home-gold">{{ trans('preview') }}</span></div>
            <button class="home-btn">{{ trans('Set Reminder') }}</button>
          </div>
        </div>
      </section>

      <!-- ARTICLES (Blog) -->
      @if(!empty($blog) and !$blog->isEmpty())
      <section>
        <div class="home-row-head"><h2>{{ trans('Articles') }}</h2><a href="/blog" class="home-chip">{{ trans('Explore') }}</a></div>
        <div class="home-grid">
          @foreach($blog as $post)
          <div class="home-tile">
            <div class="home-thumb"><img src="{{ $post->image }}" alt="{{ $post->title }}"></div>
            <div class="home-title">{{ Str::limit($post->title, 13, '..') }} </div>
            <div class="home-meta"><span>❤️ {{ $post->likes_count }}</span><span style="margin-left:10px">💬 {{ $post->comments_count }}</span></div>
            <div class="home-actions">
              <a href="{{ $post->getUrl() }}"><button class="home-btn">{{ trans('Read') }}</button></a>
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- Footer -->
      <section style="padding-bottom:60px">
        <div class="home-row-head">
          <span class="home-chip">© Kemetic.app</span>
          <span class="home-chip">{{ trans('membership') }} €1/mo or €10/yr</span>
        </div>
      </section>

      <div class="modal fade" id="videoPlayerModal" tabindex="-1" aria-labelledby="videoPlayerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="videoPlayerModalLabel">Video Player</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <video id="videoPlayer" controls style="width: 100%; border-radius: 8px;">
                Your browser does not support the video tag.
              </video>
            </div>
          </div>
        </div>
      </div>

      <!-- Membership Popup -->
      <div id="kemeticOverlay" class="kemetic-overlay" role="dialog" aria-modal="true" aria-labelledby="kmTitle" style="display:none;">
        <div class="kemetic-modal">
          <div class="kemetic-modal-inner">
            <div class="kemetic-top">
              <div>
                <h1 id="kmTitle" class="kemetic-title">Become a Member</h1>
                <p class="kemetic-subtitle">
                  Get full access to all Kemetic App courses, PDFs, articles, videos, and more – included with membership.
                </p>
              </div>
              <button type="button" class="kemetic-close" aria-label="Close" id="kmClose">✕</button>
            </div>

            <div class="chakra-divider"></div>

            <div class="kemetic-grid">
              <!-- Left: Benefits -->
              <div class="panel">
                <h3>What you get</h3>

                <div class="benefit">
                  <div class="check">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M20 6L9 17l-5-5" stroke="white" stroke-opacity=".9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <p>Unlimited access to all courses</p>
                </div>

                <div class="benefit">
                  <div class="check">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M20 6L9 17l-5-5" stroke="white" stroke-opacity=".9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <p>Download premium PDFs</p>
                </div>

                <div class="benefit">
                  <div class="check">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M20 6L9 17l-5-5" stroke="white" stroke-opacity=".9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <p>Read all articles & watch member videos</p>
                </div>

                <div class="benefit">
                  <div class="check">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M20 6L9 17l-5-5" stroke="white" stroke-opacity=".9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <p>Join live classes and special events</p>
                </div>

                <div class="benefit">
                  <div class="check">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M20 6L9 17l-5-5" stroke="white" stroke-opacity=".9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <p>New content added regularly</p>
                </div>

                <div class="social-proof">
                  <span class="dot"></span>
                  <span><strong>100,000+</strong> members are already joining the enlightened movement.</span>
                </div>
              </div>
              
              <!-- Right: Plans + CTA -->
              <div class="panel">
                <form action="/panel/financial/recurringPay-subscribes?auto_redirect=1" method="post" class="membership-w-100">
                {{ csrf_field() }}
                <h3>Choose your plan</h3>

                <!-- Plans Grid - 3 cards side by side -->
                <div class="plans-grid">
                  @foreach($subscribes as $subscribe)
                    @php
                      $membershipType = '';
                      $planType = '';
                      $badgeText = '';
                      if ($subscribe->days == 31) {
                        $membershipType = 'Monthly Membership';
                        $planType = 'monthly';
                        $planColor = '#34C759';
                      } elseif ($subscribe->days == 365) {
                        $membershipType = 'Yearly Membership';
                        $planType = 'yearly';
                        $planColor = '#007AFF';
                        $badgeText = 'Most Popular';
                      } elseif ($subscribe->days == 100000) {
                        $membershipType = 'Lifetime access to the full platform';
                        $planType = 'lifetime';
                        $planColor = '#AF52DE';
                      } else {
                        $membershipType = $subscribe->days . ' days';
                        $planType = 'custom';
                        $planColor = '#5856D6';
                      }

                      $isSelected = ($planType == 'yearly');
                    @endphp
                  
                      <input name="amount" value="{{ $subscribe->price }}" type="hidden">
                      <input name="id" value="{{ $subscribe->id }}" type="hidden">
                      
                      <div class="plan-card" {{ $isSelected ? 'selected' : '' }}" 
                        data-plan="{{ $planType }}" 
                        data-eur="€{{ $subscribe->price }}" 
                        data-usd="${{ $subscribe->price }}"
                        data-price="{{ $subscribe->price }}"
                        data-id="{{ $subscribe->id }}"
                        style="{{ $isSelected ? 'border-color: ' . $planColor . ';' : '' }}">
                        <div class="plan-name">{{ $subscribe->title }}</div>
                        <div class="plan-price">€{{ $subscribe->price }}</div>
                        <div class="plan-period">{{ $membershipType }}</div>
                        <p class="plan-hint">@if($planType == 'monthly')
                          Cancel anytime
                          @elseif($planType == 'yearly')
                              Best value
                          @elseif($planType == 'lifetime')
                              One-time payment
                          @else
                              {{ $subscribe->days }} days access
                          @endif</p>
                      </div>
                  @endforeach 
                  
                </div>

                <div class="cta">
                  <button type="submit" class="btn-primary" id="kmJoinBtn">Become a Member</button>
                  <button type="button" class="btn-secondary" id="kmLaterBtn">Maybe later</button>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";

            $.toast({
                heading: '{{ session()->get('toast')['title'] ?? '' }}',
                text: '{{ session()->get('toast')['msg'] ?? '' }}',
                bgColor: '@if(session()->get('toast')['status'] == 'success') #43d477 @else #f63c3c @endif',
                textColor: 'white',
                hideAfter: 10000,
                position: 'bottom-right',
                icon: '{{ session()->get('toast')['status'] }}'
            });
        })(jQuery)
    </script>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const FIRST_DELAY_MS = 1 * 60 * 1000;     // 5 minutes - first show
    const REPEAT_MS = 60 * 60 * 1000;         // 1 hour - repeat interval

    const overlay = document.getElementById('kemeticOverlay');
    const kmClose = document.getElementById('kmClose');
    const laterBtn = document.getElementById('kmLaterBtn');
    const joinBtn = document.getElementById('kmJoinBtn');

    let selectedPlan = 'yearly';
    let firstTimer = null;
    let repeatTimer = null;

    // Check if user is already a member (from database)
    function isMember() {
        // Check localStorage for demo
        return localStorage.getItem('isMember') === 'true';
        
        // For production, you should check from your database:
       
        // return false;
    }

    // Mark user as member (for demo - localStorage)
    function markMember() {
        localStorage.setItem('isMember', 'true');
        
        // For production, you should call an API to update the database
        // fetch('/api/become-member', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //     },
        //     body: JSON.stringify({ plan: selectedPlan })
        // })
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         stopSchedule();
        //         hidePopup();
        //     }
        // });
        
        stopSchedule();
        hidePopup();
    }

    // Plan selection - NO CHECKBOXES, just card highlighting
    const planCards = Array.from(document.querySelectorAll('.plan-card'));
    function setSelected(plan) {
        selectedPlan = plan;
        planCards.forEach(card => {
            const isSel = card.dataset.plan === plan;
            card.classList.toggle('selected', isSel);
        });
    }
    
    planCards.forEach(card => {
        card.addEventListener('click', () => setSelected(card.dataset.plan));
        card.addEventListener('keydown', (e) => {
            if(e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                setSelected(card.dataset.plan);
            }
        });
    });

    // Popup show/hide
    function showPopup() {
        if(isMember()) return;
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function hidePopup() {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
        
        // Set a cookie or localStorage to remember the close
        localStorage.setItem('popupClosed', Date.now().toString());
    }

    // Event listeners
    kmClose.addEventListener('click', hidePopup);
    laterBtn.addEventListener('click', hidePopup);

    // Click outside to close
    overlay.addEventListener('click', (e) => {
        if(e.target === overlay) hidePopup();
    });

    // Schedule timers
    function stopSchedule() {
        if(firstTimer) clearTimeout(firstTimer);
        if(repeatTimer) clearInterval(repeatTimer);
        firstTimer = null;
        repeatTimer = null;
    }

    function tryShow() {
        if(isMember()) { 
            stopSchedule(); 
            return; 
        }
        
        // Check if user recently closed the popup (within 1 hour)
        const lastClose = localStorage.getItem('popupClosed');
        if(lastClose) {
            const timeSinceClose = Date.now() - parseInt(lastClose);
            if(timeSinceClose < REPEAT_MS) {
                return; // Don't show if closed within the last hour
            }
        }
        
        if(overlay.style.display === 'flex') return; // already open
        showPopup();
    }

    function startSchedule() {
        if(isMember()) return;

        // First show after 5 minutes
        firstTimer = setTimeout(() => {
            tryShow();
            
            // Then show every hour
            repeatTimer = setInterval(() => {
                tryShow();
            }, REPEAT_MS);
        }, FIRST_DELAY_MS);
    }

    // Join button handler
    // joinBtn.addEventListener('click', async () => {
    //     // For demo: mark as member immediately
    //     // For production: redirect to payment page
    //     console.log('Selected plan:', selectedPlan);
        
    //     // Redirect to membership page with selected plan
    //     window.location.href = '/membership?plan=' + selectedPlan;
        
    //     // Or mark directly if free membership
    //     // markMember();
    // });

    // Initialize with yearly plan selected
    setSelected('yearly');

    // Start the schedule
    startSchedule();
});

// Video playback functionality
document.addEventListener('DOMContentLoaded', function() {
  // Get modal elements
  const videoPlayerModal = document.getElementById('videoPlayerModal');
  const videoPlayer = document.getElementById('videoPlayer');
  const videoPlayerModalLabel = document.getElementById('videoPlayerModalLabel');
  
  // Function to play video
  function playVideo(videoUrl, title) {
    // Set video source and title
    videoPlayer.src = videoUrl;
    videoPlayerModalLabel.textContent = title;
    
    // Show modal
    const modal = new bootstrap.Modal(videoPlayerModal);
    modal.show();
    
    // Play video when modal is shown
    videoPlayerModal.addEventListener('shown.bs.modal', function() {
      videoPlayer.play().catch(function(error) {
        console.log('Autoplay failed:', error);
        // If autoplay fails, show controls and let user play manually
        videoPlayer.controls = true;
      });
    });
  }
  
  // Add click event to all "Watch" buttons
  document.querySelectorAll('.play-video-btn').forEach(function(button) {
    button.addEventListener('click', function() {
      const videoUrl = this.getAttribute('data-video-url');
      const title = this.getAttribute('data-title');
      playVideo(videoUrl, title);
    });
  });
  
  // Add click event to video thumbnails
  document.querySelectorAll('.video-thumbnail').forEach(function(thumbnail) {
    thumbnail.addEventListener('click', function() {
      const card = this.closest('.reels-card');
      const watchButton = card.querySelector('.play-video-btn');
      if (watchButton) {
        const videoUrl = watchButton.getAttribute('data-video-url');
        const title = watchButton.getAttribute('data-title');
        playVideo(videoUrl, title);
      }
    });
  });
  
  // Pause video when modal is closed
  videoPlayerModal.addEventListener('hidden.bs.modal', function() {
    videoPlayer.pause();
    videoPlayer.currentTime = 0;
  });
  
  // Handle video upload preview
  const videoFileInput = document.getElementById('videoFile');
  const videoPreview = document.getElementById('videoPreview');
  
  if (videoFileInput) {
    videoFileInput.addEventListener('change', function() {
      const file = this.files[0];
      if (file && file.type.startsWith('video/')) {
        const videoElement = videoPreview.querySelector('video');
        const sourceElement = videoElement.querySelector('source');
        
        const url = URL.createObjectURL(file);
        sourceElement.src = url;
        videoElement.load();
        
        videoPreview.classList.remove('d-none');
        
        // Revoke object URL after video is loaded
        videoElement.onloadeddata = function() {
          URL.revokeObjectURL(url);
        };
      }
    });
  }
  
  // Handle form submission with progress
  const uploadForm = document.getElementById('uploadForm');
  const uploadProgress = document.getElementById('uploadProgress');
  
  if (uploadForm) {
    uploadForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const xhr = new XMLHttpRequest();
      
      xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
          const percentComplete = (e.loaded / e.total) * 100;
          uploadProgress.style.width = percentComplete + '%';
          uploadProgress.textContent = Math.round(percentComplete) + '%';
        }
      });
      
      xhr.addEventListener('load', function() {
        if (xhr.status === 200) {
          uploadProgress.style.width = '100%';
          uploadProgress.textContent = '100%';
          
          // Show success message
          alert('Video uploaded successfully!');
          
          // Close modal and reload page
          const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
          modal.hide();
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          alert('Error uploading video. Please try again.');
        }
      });
      
      xhr.addEventListener('error', function() {
        alert('Error uploading video. Please check your connection.');
      });
      
      xhr.open('POST', this.action);
      xhr.send(formData);
    });
  }
  
  // Smooth mouse-wheel horizontal scrolling for carousels
  document.querySelectorAll('.reels-scroller').forEach(function(scroller) {
    scroller.addEventListener('wheel', function(e) {
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
        this.scrollLeft += e.deltaY;
        e.preventDefault();
      }
    }, { passive: false });
  });
});

// Initialize Feather icons
if (typeof feather !== 'undefined') {
  feather.replace();
}


</script>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        // Marquee animation
        var $list = $('.marquee-item-list');
        var $clone = $list.children('li').clone();
        $list.append($clone);

        // Smooth wheel for horizontal scrollers
        for (const s of document.querySelectorAll('.home-scroller')) {
            s.addEventListener('wheel', (e) => {
                if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
                    s.scrollLeft += e.deltaY;
                    e.preventDefault();
                }
            }, {passive:false});
        }

        // Membership section scroll
        if (window.location.hash === '#memberShip') {
            setTimeout(function() {
                const targetElement = document.querySelector('#memberShip');
                if (targetElement) {
                    const offset = 75;
                    const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                }
            }, 300);
        }
    });
</script>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
