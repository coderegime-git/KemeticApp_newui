@extends('web.default.layouts.app')

@section('content')
<style>

/* Video Player Modal */
/* #videoPlayerModal .modal-dialog {
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
} */

/* Membership Popup Styles */
/* BEAUTIFUL MOBILE POPUP - FIXED VERSION */

/* Desktop Popup Styles - Keep Original Beauty */
.kemetic-overlay{
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  padding-top: 140px; 
  background: rgba(0,0,0,.85);
  -webkit-backdrop-filter: blur(10px);
  backdrop-filter: blur(10px);
  z-index: 9999;
  overflow-y: auto;
}

.kemetic-modal{
  width: min(980px, 100%);
  border-radius: 24px;
  background: linear-gradient(180deg, #171728, #0F0F18);
  border: 1px solid rgba(255,255,255,.10);
  box-shadow: 0 22px 40px rgba(0,0,0,.55);
  position: relative;
  /* overflow: hidden;
  max-height: 90vh;
  overflow-y: auto; */
}

.kemetic-modal-inner{
  padding: 22px 26px 18px;
}

.kemetic-top{
  display: flex;
  gap: 14px;
  align-items: flex-start;
}

.kemetic-title{
  flex: 1;
  font-weight: 900;
  letter-spacing: .2px;
  font-size: 34px;
  line-height: 1.1;
  margin: 0;
  color: #FFFFFF;
}

.kemetic-close{
  border: 0;
  background: transparent;
  color: rgba(255,255,255,.8);
  font-size: 22px;
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 12px;
  flex-shrink: 0;
}
.kemetic-close:hover{ background: rgba(255,255,255,.06); }

.kemetic-subtitle{
  margin: 8px 0 0;
  color: rgba(255,255,255,.82);
  font-size: 16px;
  line-height: 1.35;
  max-width: 820px;
}

.chakra-divider{
  height: 2px;
  border-radius: 999px;
  margin: 16px 0 18px;
  background: linear-gradient(90deg,
    #FF3B30,
    #FF9500,
    #FFCC00,
    #34C759,
    #007AFF,
    #5856D6,
    #AF52DE
  );
  opacity: .95;
}

.kemetic-grid{
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
  align-items: start;
}

.panel{
  border: 1px solid rgba(255,255,255,.08);
  background: rgba(255,255,255,.03);
  border-radius: 18px;
  padding: 20px;
}

.panel h3{
  margin: 0 0 20px;
  font-size: 18px;
  font-weight: 900;
  color: rgba(255,255,255,.92);
}

.benefit{
  display: flex;
  gap: 10px;
  align-items: flex-start;
  margin: 12px 0;
}

.check{
  width: 22px;
  height: 22px;
  border-radius: 999px;
  display: grid;
  place-items: center;
  border: 1px solid rgba(255,255,255,.18);
  flex: 0 0 auto;
}
.check svg{ width: 14px; height: 14px; }

.benefit:nth-child(1) .check{ border-color: rgba(52, 199, 89, 0.7); background: rgba(52, 199, 89, 0.16); }
.benefit:nth-child(2) .check{ border-color: rgba(0, 122, 255, 0.7); background: rgba(0, 122, 255, 0.16); }
.benefit:nth-child(3) .check{ border-color: rgba(175, 82, 222, 0.7); background: rgba(175, 82, 222, 0.16); }
.benefit:nth-child(4) .check{ border-color: rgba(255, 204, 0, 0.7); background: rgba(255, 204, 0, 0.16); }
.benefit:nth-child(5) .check{ border-color: rgba(255, 149, 0, 0.7); background: rgba(255, 149, 0, 0.16); }

.benefit p{
  margin: 0;
  color: rgba(255,255,255,.86);
  line-height: 1.25;
  font-size: 15px;
}

.social-proof{
  margin-top: 20px;
  display: flex;
  gap: 10px;
  align-items: center;
  padding: 14px 16px;
  border-radius: 14px;
  border: 1px solid rgba(255,255,255,.10);
  background: rgba(255,255,255,.04);
  color: rgba(255,255,255,.82);
  font-weight: 700;
}

.social-proof .dot{
  width: 10px; 
  height: 10px; 
  border-radius: 50%;
  background: linear-gradient(180deg, #34C759, #007AFF);
  box-shadow: 0 0 18px rgba(52,199,89,.35);
}

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

.cta{
  margin-top: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-items: stretch;
}

.btn-primary{
  height: 55px;
  border-radius: 999px;
  border: 0;
  cursor: pointer;
  font-weight: 900;
  font-size: 17px;
  letter-spacing: .3px;
  color: #0B0B12;
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

.btn-secondary{
  background: transparent;
  border: 0;
  cursor: pointer;
  color: rgba(255,255,255,.78);
  font-weight: 700;
  font-size: 15px;
  padding: 10px 12px;
  border-radius: 12px;
  transition: background 0.2s ease;
}
.btn-secondary:hover{ background: rgba(255,255,255,.08); }

/* ===========================
   MOBILE RESPONSIVE - BEAUTIFUL VERSION
   =========================== */
@media (max-width: 600px) {
  .kemetic-overlay {
    padding: 0;
    align-items: flex-start;
  }

  .kemetic-modal {
    width: 100%;
    max-height: 100vh;
    border-radius: 0;
    display: flex;
    flex-direction: column;
  }

  .kemetic-modal-inner {
    padding: 16px;
    overflow-y: auto;
    flex: 1;
    -webkit-overflow-scrolling: touch;
  }

  .kemetic-top {
    gap: 10px;
    flex-shrink: 0;
  }

  .kemetic-title {
    font-size: 22px;
    line-height: 1.2;
  }

  .kemetic-close {
    padding: 6px 10px;
    font-size: 20px;
  }

  .kemetic-subtitle {
    font-size: 14px;
    line-height: 1.4;
  }

  .chakra-divider {
    margin: 12px 0 14px;
  }

  /* Stack panels */
  .kemetic-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .panel {
    padding: 16px;
    border-radius: 14px;
  }

  .panel h3 {
    font-size: 16px;
    margin-bottom: 14px;
  }

  .benefit {
    gap: 8px;
    margin: 10px 0;
  }

  .benefit p {
    font-size: 14px;
    line-height: 1.35;
  }

  /* Social proof */
  .social-proof {
    padding: 12px;
    font-size: 13px;
  }

  /* Plans grid becomes vertical */
  .plans-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }

  .plan-card {
    min-height: auto;
    padding: 16px;
    border-radius: 14px;
  }

  .plan-name {
    font-size: 15px;
    margin-bottom: 6px;
  }

  .plan-price {
    font-size: 22px;
  }

  .plan-period {
    font-size: 13px;
    margin-bottom: 10px;
  }

  .plan-hint {
    font-size: 12px;
  }

  /* CTA */
  .cta {
    gap: 10px;
    margin-top: 16px;
  }

  .btn-primary {
    height: 48px;
    font-size: 15px;
  }

  .btn-secondary {
    font-size: 14px;
    padding: 8px 10px;
  }
}

/* For tablets */
@media (max-width: 860px) and (min-width: 601px) {
  .kemetic-title { 
    font-size: 28px; 
  }
  
  .kemetic-grid { 
    grid-template-columns: 1fr; 
  }
}
/* COMPLETE CSS FIX FOR STICKY UNLOCK ON MOBILE */

/* Sticky Unlock Pill - Desktop & Mobile */
.sticky-unlock {
  position: sticky;
  top: 80px;
  background: linear-gradient(135deg, rgba(26, 10, 46, 0.95) 0%, rgba(45, 27, 78, 0.95) 100%);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 50px;
  padding: 12px 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 20px rgba(138, 43, 226, 0.3);
  margin: 20px auto 24px;
  max-width: fit-content;
  width: fit-content;
}

.sticky-unlock:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 30px rgba(138, 43, 226, 0.5);
  border-color: rgba(255, 255, 255, 0.4);
}

.pill {
  font-size: 15px;
  font-weight: 700;
  letter-spacing: 1px;
  background: linear-gradient(90deg, #ff6b9d, #ffa06b, #ffeb3b, #4caf50, #2196f3, #8a2be2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  background-size: 200% 100%;
  animation: shimmer 3s linear infinite;
  white-space: nowrap;
}

@keyframes shimmer {
  0% { background-position: 0% 50%; }
  100% { background-position: 200% 50%; }
}

.stars {
  display: flex;
  gap: 5px;
}

.stars span {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  animation: twinkle 2s ease-in-out infinite;
}

.stars span:nth-child(1) {
  background: #ff6b9d;
  animation-delay: 0s;
}

.stars span:nth-child(2) {
  background: #ffeb3b;
  animation-delay: 0.3s;
}

.stars span:nth-child(3) {
  background: #4caf50;
  animation-delay: 0.6s;
}

@keyframes twinkle {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.4; transform: scale(0.7); }
}

/* Main Membership CTA Card */
.home-membership-cta {
  background: linear-gradient(135deg, rgba(26, 10, 46, 0.9) 0%, rgba(15, 5, 25, 0.95) 50%, rgba(26, 10, 46, 0.9) 100%);
  border-radius: 24px;
  padding: 10px;
  text-align: center;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.15);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
  margin: 0 auto 32px;
  z-index: 0;
}

.home-membership-cta::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: 
    radial-gradient(circle at 30% 40%, rgba(138, 43, 226, 0.2) 0%, transparent 50%),
    radial-gradient(circle at 70% 60%, rgba(255, 107, 157, 0.2) 0%, transparent 50%);
  animation: pulse 8s ease-in-out infinite;
  pointer-events: none;
}

@keyframes pulse {
  0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; }
  50% { transform: scale(1.1) rotate(10deg); opacity: 0.8; }
}

.home-membership-cta .title {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 16px;
  background: linear-gradient(135deg, #ffffff 0%, #e0c3fc 50%, #8ec5fc 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  position: relative;
  z-index: 1;
  line-height: 1.2;
}

.home-membership-cta .desc {
  font-size: 16px;
  line-height: 1.6;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 28px;
  position: relative;
  z-index: 1;
}

.home-membership-cta .btn {
  background: linear-gradient(135deg, #8a2be2 0%, #ff6b9d 100%);
  color: white;
  border: none;
  padding: 16px 40px;
  font-size: 15px;
  font-weight: 700;
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  z-index: 1;
  letter-spacing: 1.2px;
  box-shadow: 0 8px 24px rgba(138, 43, 226, 0.4);
  text-transform: uppercase;
  width: 100%;
  max-width: 280px;
}

.home-membership-cta .btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 32px rgba(138, 43, 226, 0.6);
  background: linear-gradient(135deg, #9d3ef5 0%, #ff7ba8 100%);
}

.home-membership-cta .btn:active {
  transform: translateY(0);
}

.home-membership-cta .price {
  margin-top: 20px;
  font-size: 13px;
  color: rgba(255, 255, 255, 0.6);
  position: relative;
  z-index: 1;
}

/* ===========================
   MOBILE RESPONSIVE
   =========================== */
@media (max-width: 600px) {
  .sticky-unlock {
    position: sticky;
    top: 10px;
    padding: 8px 16px;
    margin: 10px 16px 16px;
    width: auto;
    max-width: calc(100% - 32px);
  }

  .pill {
    font-size: 12px;
  }

  .stars {
    gap: 4px;
  }

  .stars span {
    width: 7px;
    height: 7px;
  }

  .home-membership-cta {
    /* padding: 32px 20px;
    border-radius: 20px;
    margin: 0 16px 24px; */
    width: 100%;
  }

  .home-membership-cta .title {
    font-size: 26px;
  }

  .home-membership-cta .desc {
    font-size: 15px;
    margin-bottom: 24px;
  }

  .home-membership-cta .btn {
    padding: 15px 32px;
    font-size: 14px;
    max-width: 100%;
  }

  .home-membership-cta .price {
    font-size: 12px;
    margin-top: 16px;
  }
}

/* Extra small devices */
@media (max-width: 380px) {
  .sticky-unlock {
    padding: 6px 12px;
    top: 8px;
    margin: 8px 12px 12px;
  }

  .pill {
    font-size: 11px;
  }

  .stars span {
    width: 6px;
    height: 6px;
  }

  .home-membership-cta {
    /* margin: 0 12px 20px;
    padding: 28px 16px; */
    width: 100%;
  }

  .home-membership-cta .title {
    font-size: 22px;
  }

  .home-membership-cta .desc {
    font-size: 14px;
  }

  .home-membership-cta .btn {
    font-size: 13px;
    padding: 14px 28px;
  }
}

/* Replace the existing .sticky-unlock mobile styles with this fixed version */
@media (max-width: 600px) {
  .sticky-unlock {
    position: sticky;
    top: 10px;
    padding: 8px 16px;
    margin: 10px 16px 16px;
    width: auto;
    max-width: calc(100% - 32px);
    /* Ensure sticky works properly */
    z-index: 1000;
    will-change: transform; /* Optimize for sticky */
    -webkit-transform: translateZ(0); /* Force hardware acceleration */
    transform: translateZ(0);
  }

  /* Ensure the parent container doesn't interfere */
  main.home-container {
    position: relative;
  }
}

/* Add this for extra small devices */
@media (max-width: 380px) {
  .sticky-unlock {
    position: sticky;
    top: 8px;
    padding: 6px 12px;
    margin: 8px 12px 12px;
    max-width: calc(100% - 24px);
  }
}

/* Add this to fix potential issues with parent elements */
body, html {
  overflow-x: hidden;
  height: 100%;
}

main.home-container {
  min-height: 100vh;
  /* Ensure it's a stacking context */
  isolation: isolate;
}

/* Optional: Add a smooth transition for better UX */
.sticky-unlock {
  transition: all 0.3s ease;
}

@supports (-webkit-overflow-scrolling: touch) {
  .sticky-unlock {
    position: -webkit-sticky;
  }
}

#videoPlayerModal {
    z-index: 100050 !important;
  }
  /* Backdrop always below modal */
  .modal-backdrop {
    z-index: 100040 !important;
  }
  /* Every child element fully interactive */
  #videoPlayerModal .modal-dialog,
  #videoPlayerModal .modal-content,
  #videoPlayerModal .modal-header,
  #videoPlayerModal .modal-body,
  #videoPlayerModal #videoPlayer,
  #videoPlayerModal .modal-title,
  #videoPlayerModal .btn-close {
    position: relative;
    z-index: 100051 !important;
    pointer-events: auto !important;
  }

  /* Styling */
  #videoPlayerModal .modal-dialog  { max-width: 860px; }
  #videoPlayerModal .modal-content {
    background: #000;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 14px;
    overflow: hidden;
  }
  #videoPlayerModal .modal-header {
    background: rgba(10,10,20,.97);
    border-bottom: 1px solid rgba(255,255,255,.10);
    padding: 14px 20px;
  }
  #videoPlayerModal .modal-title { color: #fff; font-size: 16px; font-weight: 700; }
  #videoPlayerModal .btn-close   { filter: brightness(0) invert(1); opacity: .75; }
  #videoPlayerModal .btn-close:hover { opacity: 1; }
  #videoPlayerModal .modal-body  { padding: 0; background: #000; line-height: 0; }
  #videoPlayerModal #videoPlayer { width: 100%; max-height: 75vh; display: block; background: #000; }

  /* Fix note box */
  .fix-note {
    max-width: 860px;
    margin: 0 auto 32px;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 14px;
    padding: 18px 22px;
    font-size: 14px;
    line-height: 1.8;
    color: rgba(255,255,255,.75);
  }
  .fix-note code {
    background: rgba(255,255,255,.10);
    border-radius: 5px;
    padding: 2px 8px;
    color: #ffd60a;
    font-size: 13px;
  }
  .fix-note strong { color: #fff; }
  .badge-bug  { display:inline-block; background:#ff3b30; color:#fff;  font-size:11px; font-weight:800; padding:2px 8px; border-radius:999px; margin-right:4px; }
  .badge-fix  { display:inline-block; background:#34c759; color:#000; font-size:11px; font-weight:800; padding:2px 8px; border-radius:999px; margin-right:4px; }
</style>
    
<!-- <header class="home-topnav"> -->
  <!-- <div class="home-nav-row"> -->
    <!-- <div class="home-brand">Kemetic.app</div>
    <nav class="home-nav-links">
      <a href="#">Livestreams</a>
      <a href="#">Courses</a>
      <a href="#">Reels</a>
      <a href="#">Shop</a>
      <a href="#">Television</a>
      <a href="#">Articles</a>
    </nav> -->
    <!-- <div class="home-nav-cta">
       @if(auth()->check())
        <a href="/membership"><span class="home-chip">{{ trans('Free') }} • {{ trans('Join') }}</span></a>
        <a href="/membership"><button class="home-btn" style="margin-left:10px">€1/mo or €10/yr</button></a>
      @else
        <a href="/login"><span class="home-chip">{{ trans('Free') }} • {{ trans('Join') }}</span></a>
        <a href="/login"><button class="home-btn" style="margin-left:10px">€1/mo or €10/yr</button></a>
      @endif
    </div> -->
  <!-- </div> -->
<!-- </header> -->
    <!-- <div class="sticky-unlock" onclick="location.href='/membership'">
      <div class="pill">UNLOCK ∞</div>
      <div class="stars">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div> -->

    <div class="home-membership-cta">
      <div class="title">Awaken and Ascend</div>
      <div class="desc">
        Dive into the ancient wisdom of Kemet. Align your chakras and join a global community
        of Seekers and Wisdom Keepers.
      </div>
       @if(auth()->check())
      <button class="btn" onclick="location.href='/membership'">UPGRADE</button>
      @else
      <button class="btn" onclick="location.href='/login'">BECOME A MEMBER</button>
      @endif
      <div class="price">€1 / €10 / €33 • Unlimited access</div>
    </div>

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
              <span style="margin-left:10px;opacity:.85;font-weight:800;margin-top: -4px;">{{ $totalStats->total_likes ?? 0 }}+</span>
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

      <!-- SHOP (Products) class="home-btn btn-add-product-to-cart" -->
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
                <a href="{{ $product->getUrl() }}"><button type="button" class="home-btn" data-id="{{ $product->id }}" 
                    data-product-type="{{ $product->type ?? 'physical' }}" 
                    data-product-title="{{ $product->title }}" >{{ trans('Add to Cart') }}</button></a>
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
              <a href="{{ $book->getUrl() }}"><button class="home-btn">Read Scrolls</button></a>
              <!-- @if($book->price == 0)
                <a href="{{ $book->getUrl() }}"><button class="home-btn">Read Scrolls</button></a>
              @else
                <a href="{{ $book->getUrl() }}"><button class="home-btn">{{ trans('Buy Audiobook') }}</button></a>
              @endif -->
            </div>
          </div>
          @endforeach
        </div>
      </section>
      @endif

      <!-- LIVE NOW (Livestreams) -->
      <!-- <section>
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
      </section> -->

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
      <section>
        <div class="home-row-head">
          <span class="home-chip">© Kemetic.app</span>
          <span class="home-chip">{{ trans('membership') }} €1/mo or €10/yr</span>
        </div>
      </section>

      <div class="modal fade" id="videoPlayerModal" tabindex="-1"
       aria-labelledby="videoPlayerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="videoPlayerModalLabel">Video Player</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <video id="videoPlayer" controls>
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
                <!-- <form action="/panel/financial/recurringPay-subscribes?auto_redirect=1" method="post" class="membership-w-100"> -->
                <!-- <form action="/membership" method="post" class="membership-w-100"> -->
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
                  @if(auth()->check())
                  <button type="button" class="btn-primary" id="kmupgrade">Upgrade</button></a>
                  @else
                  <button type="button" class="btn-primary" id="kmJoinBtn">Become a Member</button></a>
                  @endif
                  <button type="button" class="btn-secondary" id="kmLaterBtn">Maybe later</button>
                </div>
                <!-- </form> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- <div id="virtualProductConfirmationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; align-items: center; justify-content: center;">
      <div style="background: var(--panel, #1a1a1a); padding: 30px; border-radius: 16px; max-width: 500px; width: 90%; border: 2px solid var(--chakra-gold, #FFD700);">
        <div style="text-align: center; margin-bottom: 25px;">
          <div style="font-size: 48px; margin-bottom: 15px;">📦</div>
          <h2 style="color: var(--chakra-gold, #FFD700); margin-bottom: 10px;">Virtual Product</h2>
          <p id="virtualConfirmationMessage" style="color: #ccc; line-height: 1.6; margin-bottom: 25px;">
            This is a virtual product. After purchase, you can download it immediately.
          </p>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center;">
          <button id="virtualConfirmCancel" 
                  style="padding: 12px 30px; background: transparent; border: 2px solid #666; color: #ccc; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
            Cancel
          </button>
          <button id="virtualConfirmProceed" 
                  style="padding: 12px 30px; background: var(--chakra-gold, #FFD700); border: none; color: #000; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
            Continue to Cart
          </button>
        </div>
      </div>
    </div> -->
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

document.addEventListener('DOMContentLoaded', function () {

  let currentReelVideo = null;
  
  document.querySelectorAll('.reel-video').forEach(function (video) {
    video.addEventListener('play', function () {
      // Pause any other reel video that's playing
      if (currentReelVideo && currentReelVideo !== video) {
        currentReelVideo.pause();
      }
      currentReelVideo = video;
    });
  });
  

  const videoPlayerModal = document.getElementById('videoPlayerModal');
  const videoPlayer      = document.getElementById('videoPlayer');
  const videoModalLabel  = document.getElementById('videoPlayerModalLabel');
  let bsModal = null;

  // Auto-remove any backdrop Bootstrap injects
  const backdropObserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      mutation.addedNodes.forEach(function(node) {
        if (node.classList && node.classList.contains('modal-backdrop')) {
          node.remove();
          document.body.classList.remove('modal-open');
          document.body.style.overflow    = '';
          document.body.style.paddingRight = '';
        }
      });
    });
  });
  backdropObserver.observe(document.body, { childList: true });

  function playVideo(url, title) {

    if (currentReelVideo) {
      currentReelVideo.pause();
      currentReelVideo = null;
    }
    videoPlayer.src = url;
    videoModalLabel.textContent = title || 'Video Player';
    if (!bsModal) {
      bsModal = new bootstrap.Modal(videoPlayerModal, {
        backdrop: false,  // ← Bootstrap will NOT create .modal-backdrop at all
        keyboard: true,
        focus: true
      });
    }
    bsModal.show();
  }

  videoPlayerModal.addEventListener('shown.bs.modal', function () {
    videoPlayer.play().catch(() => {});
  });

  videoPlayerModal.addEventListener('hide.bs.modal', function () {
    videoPlayer.pause();
  });

  videoPlayerModal.addEventListener('hidden.bs.modal', function () {
    videoPlayer.pause();
    videoPlayer.currentTime = 0;
    videoPlayer.src = '';
    // Safety net — remove any backdrop that slipped through
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow     = '';
    document.body.style.paddingRight = '';
  });

  document.querySelectorAll('.play-video-btn').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      playVideo(
        this.getAttribute('data-video-url'),
        this.getAttribute('data-title')
      );
    });
  });
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const videoModal = document.getElementById('videoPlayerModal');
    if (videoModal.classList.contains('show')) {
      const bsModal = bootstrap.Modal.getInstance(videoModal);
      if (bsModal) {
        bsModal.hide();
      }
    }
  }
});

document.addEventListener('DOMContentLoaded', function() {
    const FIRST_DELAY_MS = 5 * 60 * 1000;     // 5 minutes - first show
    const REPEAT_MS = 60 * 60 * 1000;         // 1 hour - repeat interval

    const overlay = document.getElementById('kemeticOverlay');
    const kmClose = document.getElementById('kmClose');
    const laterBtn = document.getElementById('kmLaterBtn');
    const joinBtn = document.getElementById('kmJoinBtn');
    const upgrade = document.getElementById('kmupgrade');

    let selectedPlan = 'yearly';
    let firstTimer = null;
    let repeatTimer = null;

  const stickyUnlock = document.querySelector('.sticky-unlock');
  
  if (window.innerWidth <= 600) {
    // Check if sticky positioning is supported
    const isStickySupported = CSS.supports('position', 'sticky') || 
                             CSS.supports('position', '-webkit-sticky');
    
    if (!isStickySupported) {
      // Fallback to fixed positioning for browsers that don't support sticky
      stickyUnlock.style.position = 'fixed';
      stickyUnlock.style.top = '10px';
      stickyUnlock.style.left = '50%';
      stickyUnlock.style.transform = 'translateX(-50%)';
      stickyUnlock.style.zIndex = '1000';
      stickyUnlock.style.margin = '0';
      
      // Adjust when scrolling
      window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const containerTop = document.querySelector('main.home-container').offsetTop;
        
        if (scrollTop > containerTop) {
          stickyUnlock.style.top = '10px';
        } else {
          stickyUnlock.style.top = (containerTop - scrollTop + 10) + 'px';
        }
      });
    }
  }
  
  // Optional: Add shadow when scrolled
  window.addEventListener('scroll', function() {
    if (window.pageYOffset > 50) {
      stickyUnlock.style.boxShadow = '0 4px 20px rgba(138, 43, 226, 0.5)';
    } else {
      stickyUnlock.style.boxShadow = '0 4px 20px rgba(138, 43, 226, 0.3)';
    }
  });

    // let currentProductButton = null;
    // let currentProductId = null;

    //  document.addEventListener('click', function(e) {
    //     if (e.target.classList.contains('btn-add-product-to-cart') || 
    //         e.target.closest('.btn-add-product-to-cart')) {
    //         e.preventDefault();
            
    //         const button = e.target.classList.contains('btn-add-product-to-cart') ? 
    //                      e.target : e.target.closest('.btn-add-product-to-cart');
            
    //         const item_id = button.getAttribute('data-id');
    //         const productType = button.getAttribute('data-product-type') || 'physical';
    //         const productTitle = button.getAttribute('data-product-title') || 'this product';

    //         currentProductButton = button;
    //         currentProductId = item_id;
            
    //         // Check if it's a virtual/digital product
    //         const isVirtual = ['virtual', 'digital', 'downloadable', 'e-book', 'pdf', 'audio'].includes(productType.toLowerCase());

    //         if (isVirtual) {
    //             // Show confirmation modal for virtual products
    //             const message = `"${productTitle}" is a virtual product. After purchase, you can download it immediately.`;
                
    //             document.getElementById('virtualConfirmationMessage').textContent = message;
    //             document.getElementById('virtualProductConfirmationModal').style.display = 'flex';
    //         } else {
    //             // For physical products, proceed directly
    //             proceedWithAddToCart(item_id, button);
    //         }
    //     }
    // });

    // // Handle proceed for virtual products
    // document.getElementById('virtualConfirmProceed').addEventListener('click', function() {
    //     document.getElementById('virtualProductConfirmationModal').style.display = 'none';
    //     if (currentProductButton && currentProductId) {
    //         proceedWithAddToCart(currentProductId, currentProductButton);
    //         currentProductButton = null;
    //         currentProductId = null;
    //     }
    // });
    
    // // Handle cancel for virtual products
    // document.getElementById('virtualConfirmCancel').addEventListener('click', function() {
    //     document.getElementById('virtualProductConfirmationModal').style.display = 'none';
    //     currentProductButton = null;
    //     currentProductId = null;
    // });
    
    // // Also close modal when clicking outside
    // document.getElementById('virtualProductConfirmationModal').addEventListener('click', function(e) {
    //     if (e.target === this) {
    //         this.style.display = 'none';
    //         currentProductButton = null;
    //         currentProductId = null;
    //     }
    // });
    
    // function proceedWithAddToCart(item_id, button) {
    //     // Add loading state
    //     button.classList.add('loadingbar', 'primary');
    //     button.disabled = true;
    //     const originalText = button.textContent;
    //     button.textContent = 'Adding...';

    //     // Create and submit form
    //     const form = document.createElement('form');
    //     form.style.display = 'none';
    //     form.method = 'POST';
    //     form.action = '/cart/store';
        
    //     const csrfToken = document.createElement('input');
    //     csrfToken.type = 'hidden';
    //     csrfToken.name = '_token';
    //     csrfToken.value = window.csrfToken || '{{ csrf_token() }}';
        
    //     const itemIdInput = document.createElement('input');
    //     itemIdInput.type = 'hidden';
    //     itemIdInput.name = 'item_id';
    //     itemIdInput.value = item_id;
        
    //     const itemNameInput = document.createElement('input');
    //     itemNameInput.type = 'hidden';
    //     itemNameInput.name = 'item_name';
    //     itemNameInput.value = 'product_id';
        
    //     form.appendChild(csrfToken);
    //     form.appendChild(itemIdInput);
    //     form.appendChild(itemNameInput);
        
    //     document.body.appendChild(form);
    //     form.submit();
    // }

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

    joinBtn.addEventListener('click', async () => {
      window.location.href = '/login';
    });

    upgrade.addEventListener('click', async () => {
      window.location.href = '/membership';
    });

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
