@extends('web.default.layouts.app')

@section('content')

<div class="course-banner">
  <div class="course-wrap">
    <span>Unlock unlimited Courses, Reels & Livestreams — €1/month or €10/year</span>
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

    @if(!empty($bestRateWebinars))
      @foreach($bestRateWebinars as $bestRateWebinar)
  <section class="course-hero">
    <div class="course-card">
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
      <div class="course-hero-body">
        <div class="course-sub">Featured Course</div>
        <h1 class="course-title">{{ clean($bestRateWebinar->title,'title') }}</h1>
        <p class="course-sub">{{ convertMinutesToHourAndMinute($bestRateWebinar->duration) }} {{ trans('home.hours') }} · <a href="{{ $bestRateWebinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $bestRateWebinar->teacher->full_name }}</p>
        <div class="course-stars">★★★★★ <b>{{ $totalStats->total_likes ?? 0 }}</b> · 12.8k students</div>
        <a href="{{ $bestRateWebinar->getUrl() }}"><div style="margin-top:14px"><button class="course-cta">Enroll Now</button></div></a>
      </div>
    </div>
    <div>
       @endforeach
      @endif
      <!-- <div class="course-chips">
        @foreach($categories as $categorie)
        <span class="course-chip">{{$categorie->title}}</span>
        @endforeach
      </div> -->
     
    </div>
  </section>
  <div class="shop-sp"></div>
  <form action="/classes" method="get">
    <div class="shop-search">
      <span style="color:var(--gold);font-weight:900">🔎</span>
      <input type="text" name="search" class="form-control border-0" value="{{ request()->get('search') }}" placeholder="What are you looking for?"/>
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
    <h2>🔥 Trending Courses</h2>
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

  <!-- Live Classes -->
  <section>
    <div class="course-row-top">
      <h2 style="margin:0">🔴 Live Classes</h2>
       <span class="course-subfilter active"  onclick="filterLiveClasses('newest')"  data-filter="newest">Newest</span>
        <span class="course-subfilter"  onclick="filterLiveClasses('topRated')" data-filter="topRated">Top Rated</span>
    </div>
     <div class="course-row" id="newestLiveClasses">
        @foreach($newestLiveClasses as $webinar)
            <article class="course-item">
                <div class="course-thumb">
                    <a href="{{ $webinar->getUrl() }}"><img src="{{ $webinar->getImage() }}" class="img-cover" alt="{{ $webinar->title }}"></a>
                </div>
                <div class="course-name"><a href="{{ $webinar->getUrl() }}">{{ Str::limit($webinar->title, 13, '..') }}</a></div>
                <div class="course-meta">{{ convertMinutesToHourAndMinute($webinar->duration) }} {{ trans('home.hours') }} · <a href="{{ $webinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $webinar->teacher->full_name }}</a></div>
                <div class="course-bar">
                    <div class="course-stars">
                        @php
                            $rating = $webinar->reviews->avg('rates') ?? 0;
                            $filledStars = min(5, max(0, round($rating)));
                            $emptyStars = 5 - $filledStars;
                        @endphp
                        @for($i = 0; $i < $filledStars; $i++)
                            ★
                        @endfor
                        @for($i = 0; $i < $emptyStars; $i++)
                            ☆
                        @endfor
                    </div>
                    <a href="{{ $webinar->getUrl() }}"><button class="course-cta">Join Live</button></a>
                </div>
            </article>
        @endforeach
    </div>

    <div class="course-row" id="topRatedLiveClasses" style="display: none;">
        @foreach($topRatedLiveClasses as $webinar)
            <article class="course-item">
                <div class="course-thumb">
                    <a href="{{ $webinar->getUrl() }}"><img src="{{ $webinar->getImage() }}" class="img-cover" alt="{{ $webinar->title }}"></a>
                </div>
                <div class="course-name"><a href="{{ $webinar->getUrl() }}">{{ Str::limit($webinar->title, 13, '..') }}</a></div>
                <div class="course-meta">{{ convertMinutesToHourAndMinute($webinar->duration) }} {{ trans('home.hours') }} · <a href="{{ $webinar->teacher->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $webinar->teacher->full_name }}</a></div>
                <div class="course-bar">
                    <div class="course-stars">
                        @php
                            $rating = $webinar->avg_rates ?? $webinar->reviews->avg('rates') ?? 0;
                            $filledStars = min(5, max(0, round($rating)));
                            $emptyStars = 5 - $filledStars;
                        @endphp
                        @for($i = 0; $i < $filledStars; $i++)
                            ★
                        @endfor
                        @for($i = 0; $i < $emptyStars; $i++)
                            ☆
                        @endfor
                    </div>
                    <a href="{{ $webinar->getUrl() }}"><button class="course-cta">Join Live</button></a>
                </div>
            </article>
        @endforeach
    </div>
  </section>

</main>
@endsection
@push('scripts_bottom')
<script>
function filterLiveClasses(filterType) {
    console.log('Filtering:', filterType);
    
    // Remove active class from all filters
    document.querySelectorAll('.course-subfilter').forEach(f => f.classList.remove('active'));
    
    // Add active class to clicked filter
    event.target.classList.add('active');
    
    // Show/hide sections
    if (filterType === 'newest') {
        document.getElementById('newestLiveClasses').style.display = 'grid';
        document.getElementById('topRatedLiveClasses').style.display = 'none';
    } else if (filterType === 'topRated') {
        document.getElementById('newestLiveClasses').style.display = 'none';
        document.getElementById('topRatedLiveClasses').style.display = 'grid';
    }
}
</script>
@endpush