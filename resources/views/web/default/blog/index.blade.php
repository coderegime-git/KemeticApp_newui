@extends('web.default.layouts.app')

@section('content')

  <div class="article-wrap">
    <!-- Membership banner -->
    <div class="article-banner">
      <div class="article-chip">💎 Unlock Unlimited Articles, Reels & Live</div>
      <div class="article-chip">€1/mo • €10/yr • €33 lifetime</div>
       @if(auth()->check())
          <button class="article-cta"><a href="/membership">Upgrade</a></button>
        @else
          <button class="article-cta"><a href="/login">Upgrade</a></button>
        @endif
    </div>
    
    @php
        use Illuminate\Support\Facades\DB;

        $userId = auth()->id();
        $systemIp = getSystemIP();

        // Get total stats (for all users)
        $totalStats = DB::table('stats')
        ->where('blog_id', $popularPosts[0]->id)
        ->selectRaw('SUM(likes) as total_likes, SUM(views) as total_views, SUM(shares) as total_shares')
        ->first();

        // Check if the system IP or logged-in user has interacted
        $userStats = DB::table('stats')
        ->where('blog_id', $popularPosts[0]->id)
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
    <section class="article-hero">
      <div class="article-media">
        <img src="{{ $popularPosts[0]->image }}" class="img-cover rounded" alt="{{ $popularPosts[0]->title }}">
        <div class="article-grad"></div>
        <div class="article-meta">
          <div class="article-k-badges">
            <div class="article-k-star" style="background:var(--red)"></div>
            <div class="article-k-star" style="background:var(--orange)"></div>
            <div class="article-k-star" style="background:var(--yellow)"></div>
            <div class="article-k-star" style="background:var(--green)"></div>
            <div class="article-k-star" style="background:var(--blue)"></div>
          </div>
          <div style="font-size:28px;font-weight:900">{{ $popularPosts[0]->title }}</div>
          <div style="color:#fff;font-weight:800">{{$userStats->user_views}} reads</div>
        </div>
         <a href="{{ $popularPosts[0]->getUrl() }}"><button class="article-btn">Read Now</button></a>

        <!-- Chakra rail -->
        <div class="article-rail">
          <div class="article-circle" style="--clr:var(--red)">❤️</div>
          <div class="article-circle" style="--clr:var(--orange)">💬</div>
          <div class="article-circle" style="--clr:var(--green)">🎁</div>
          <div class="article-circle" style="--clr:var(--blue)">🔖</div>
          <div class="article-circle" style="--clr:var(--violet)">↗</div>
        </div>
      </div>
    </section>

    <div class="article-sp"></div>

    <!-- Search + Categories -->
    <div class="article-row">
      <form action="/blog" method="get">
        <div class="article-search">
          <span style="color:var(--gold);font-weight:900">🔎</span>
          <input  type="text" name="search"  value="{{ request()->get('search') }}" placeholder="{{ trans('home.blog_search_placeholder') }}">
          <button type="submit" class="article-pill">{{ trans('home.find') }}</button>
          <!-- ⚙️ Filters  onclick="alert('Open filters')" -->
        </div>
      </form>
    </div>

    <div class="shop-chips">
      @foreach($blogCategories as $blogCategory)
      <a href="{{ $blogCategory->getUrl() }}" class="font-14 text-dark-blue d-block mt-15">
          <div class="article-pill @if(!empty($selectedCategory) and $selectedCategory == $blogCategory->slug) active @endif">
              {{ $blogCategory->title }}
          </div>
      </a>
      @endforeach
    </div>

    <div class="article-sp"></div>

    <h2>Global Articles</h2>
    <!-- <div class="article-tabs">
      <div class="article-tab active">All</div>
      <div class="article-tab">Editors' Picks</div>
      <div class="article-tab">Ancient Kemet</div>
      <div class="article-tab">Esoteric</div>
      <div class="article-tab">Health & Herbs</div>
      <div class="article-tab">Energy & Sound</div>
    </div> -->

    <section class="article-grid">
        @foreach($blog as $post)
        <!-- {{ $post->title }} -->
            <article class="article-card">
                <div class="article-thumb">
                <img src="{{ $post->image }}" class="img-cover" alt="{{ $post->title }}">
                <div class="article-badge">
                    <!-- <span class="article-dot" style="background:var(--red)"></span> -->
                </div>
                </div>
                <div class="article-body">
                <div class="article-title"><a href="{{ $post->getUrl() }}">{{ Str::limit($post->title, 13, '..') }}</a></div>
                <div class="article-meta"><img src="{{ $post->author->getAvatar() }}" class="img-cover" alt="{{ $post->author->full_name }}">{{ $post->author->full_name }} </div>
                <!-- • 12 min -->
                <div class="article-row-end">
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
                    <span style="color:#fff;margin-left:6px">{{$post->reviews->count()}} </span></div>
                    <a href="{{ $post->getUrl() }}"><button class="article-read">Read</button></a>
                </div>
                </div>
            </article>
        @endforeach

         
    </section>
    <div class="mt-50 pt-30">
      {{ $blog->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>

    <div class="article-sp"></div>
  </div>
@endsection