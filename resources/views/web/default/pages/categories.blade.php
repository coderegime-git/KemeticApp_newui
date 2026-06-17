@extends('web.default.layouts.app')

@section('content')

<div class="course-banner">
  <div class="course-wrap">
    <span>Unlock unlimited Courses, Reels & Livestreams — €10/year or €33/Lifetime</span>
      @if(auth()->check())
          <button class="course-btn"><a href="/membership">Join Now</a></button>
        @else
          <button class="course-btn"><a href="/login">Join Now</a></button>
        @endif  
  </div>
</div>


<main class="course-container">

  <!-- HERO -->
  <!-- <form action="/search" method="get">
      <div class="search">
        <span style="color:var(--gold);font-weight:900">🔎</span>
        <input  type="text" name="search"  value="{{ request()->get('search') }}" placeholder="{{ trans('home.slider_search_placeholder') }}">
        <button type="submit" class="pill">{{ trans('home.find') }}</button>
        ⚙️ Filters onclick="alert('Open filters')"
      </div>
    </form> -->

   @if(!empty($featureWebinars) and !$featureWebinars->isEmpty())
      @foreach($featureWebinars as $featureWebinar)
    <section class="course-hero">
        <div class="course-card">
            <img src="{{ $featureWebinar->getImage() }}" class="img-cover" alt="{{ $featureWebinar->title }}">
                
                @php
                
        
                $userId = auth()->id();
                $systemIp = getSystemIP();
        
                // Get total stats (for all users)
                $totalStats = DB::table('stats')
                ->where('webinar_id', $featureWebinar->id)
                ->selectRaw('SUM(likes) as total_likes, SUM(views) as total_views, SUM(shares) as total_shares')
                ->first();
        
                // Check if the system IP or logged-in user has interacted
                $userStats = DB::table('stats')
                ->where('webinar_id', $featureWebinar->id)
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
            <div class="course-hero-body">
                <div class="course-sub">Featured Course</div>
                <h1 class="course-title">{{ clean($featureWebinar->title,'title') }}</h1>
                <p class="course-sub">{{ convertMinutesToHourAndMinute($featureWebinar->duration) }} {{ trans('home.hours') }} · <a href="{{ $featureWebinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $featureWebinar->teacher->full_name }}</p>
                <div class="course-stars">★★★★★ <b>{{ $totalStats->total_likes ?? 0 }}</b> · 12.8k students</div>
                <a href="{{ $featureWebinar->getUrl() }}"><div style="margin-top:14px"><button class="course-cta">Enroll Now</button></div></a>
            </div>
        </div>
    </section>
       @endforeach
      @endif
  

  <div class="shop-sp"></div>
  <form action="/classes" method="get" id="searchSection">
    <div class="shop-search">
      <span style="color:var(--gold);font-weight:900">🔎</span>
      <input type="text" name="search" class="form-control border-0" value="{{ request()->get('search') }}" placeholder="What are you looking for?"/>
      @if(request()->get('search'))
        <button type="button" onclick="clearSearch(this)" class="shop-ghost" style="color:#999;">✕</button>
      @endif
      <button class="shop-ghost">{{ trans('home.find') }}</button>
    </div>
  </form>
  <div class="shop-sp"></div>
   <div class="shop-chips">
    <form action="/classes" method="get">

    @if(!empty($categories))
        @if(!empty($selectedCategory))
            <input type="hidden" name="category_id" value="{{ $selectedCategory->id }}">
        @endif
        <div class="shop-chips">
          @foreach($categories as $categorie)
            <a href="/classes?category_id={{$categorie->id}}" class="d-flex align-items-center font-14 font-weight-bold mt-20 {{ (!empty($selectedCategory) and $selectedCategory->id == $categorie->id) ? 'text-primary' : '' }}">
              <div class="shop-pill @if(!empty($selectedCategory) and $selectedCategory->id == $categorie->id) active @endif">
                {{ $categorie->title }}
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </form>
  </div>
  

  <!-- Trending Courses -->
  <section>
    <h2>🔥 Reward Courses</h2>
    <form action="/classes" method="get" id="filtersForm">
    <div class="course-row">
      <!-- card  -->
       
       @foreach($webinars as $webinar)
       <!-- {{ clean($webinar->title,'title') }} -->
        <article class="course-item">
          <div class="course-thumb">
            <a href="{{ $webinar->getUrl() }}"><img src="{{ $webinar->getImage() }}" class="img-cover" alt="{{ $webinar->title }}"></a>
          </div>
          <div class="course-name"><a href="{{ $webinar->getUrl() }}">{{ Str::limit($webinar->title, 13, '..') }}</a></div>
          <div class="course-meta">{{ convertMinutesToHourAndMinute($webinar->duration) }} {{ trans('home.hours') }} · <a href="{{ $webinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $webinar->teacher->full_name }}</a></div>
          <div class="course-bar">
            <div class="course-stars">
               @php
                      $i = 5;
                  @endphp
               @if($webinar->reviews->count() > 0)
                  @foreach($webinar->reviews as $review)
                      @php
                          $rating = $review->rates ?? 0;
                          $filledStars = min(5, max(0, $rating));
                          $emptyStars = 5 - $filledStars;
                      @endphp
                      
                      @for($i = 0; $i < $filledStars; $i++)
                          ★
                      @endfor
                      @for($i = 0; $i < $emptyStars; $i++)
                          ☆
                      @endfor
                  @endforeach
              @else
                  @for($i = 0; $i < 5; $i++)
                      ☆
                  @endfor
              @endif</div>
            <button class="course-cta"><a href="{{ $webinar->getUrl() }}">Enroll Now</a></button>
          </div>
        </article>
      @endforeach
      
    </div>
    </form>
    <div class="mt-50 pt-30">
      {{ $webinars->appends(request()->input())->links('vendor.pagination.panel') }}
  </div>
  </section>

</main>
@endsection
@push('scripts_bottom')
<script>
function clearSearch(button) {
    var form = button.closest('form');
    if (form) {
        var input = form.querySelector('input[name="search"]');
        if (input) {
            input.value = '';
        }
        form.submit();
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('search') || urlParams.has('category_id') || urlParams.has('page')) {
        const searchSection = document.getElementById('searchSection');
        if(searchSection) {
            searchSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});
</script>
@endpush