@extends('web.default.layouts.app')

@section('content')

<!-- membership banner -->
<div class="reels-banner">
  <div class="reels-wrap">
    <span>Unlock Unlimited Reels, Courses & Livestreams — €1/month or €10/year</span>
     @if(auth()->check())
          <button class="reels-btn"><a href="/membership">Join Now</a></button>
        @else
          <button class="reels-btn"><a href="/login">Join Now</a></button>
        @endif  
  </div>
</div>

<main class="reels-container">

  <!-- HERO: last month global #1 -->
  <section class="reels-hero">
    <div class="reels-hero-card">
      <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=1600" alt="">
      <div class="reels-rail">
        <div class="reels-pill"><span class="reels-circle reels-bg-red">❤</span><b>{{ $heroreels->likes_count }}</b></div>
        <div class="reels-pill"><span class="reels-circle reels-bg-blue">▶</span><b>{{ $heroreels->views_count }}</b></div>
        <div class="reels-pill"><span class="reels-circle reels-bg-yellow">↗</span><b>{{ $heroreels->comments_count }}</b></div>
      </div>
      <div class="reels-hero-body">
        <div class="reels-sub">Last month's Global #1</div>
        <h1 class="reels-title">{{ $heroreels->title }}</h1>
        <div class="reels-sub">{{ $heroreels->caption }}</div>
        <div class="reels-chakra-stars">
          <span class="reels-dot reels-bg-red"></span><span class="reels-dot reels-bg-orange"></span>
          <span class="reels-dot reels-bg-yellow"></span><span class="reels-dot reels-bg-green"></span>
          <span class="reels-dot reels-bg-blue"></span><b style="margin-left:10px">3,255+</b>
        </div>
        <button class="reels-btn">Watch Reel</button>
        <a class="reels-go-profile" href="/user/1066/profile">→ View profile</a>
      </div>
    </div>
    <div>
      <h2>Reels</h2>
      <div class="reels-sub">{{ $heroreels->title }}</div>
      <p class="reels-sub">{{ $heroreels->caption }}</p>
    </div>
  </section>

  <!-- GLOBAL -->
  <section>
    <h2>🌍 Global Reels</h2>
    <div class="reels-scroller">
      <!-- repeat cards -->
       @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach

      
    </div>
  </section>

  <!-- TRENDING -->
  <section>
    <h2>🔥 Trending Reels</h2>
    <div class="reels-scroller">
     @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach
    </div>
  </section>

  <!-- LIVE NOW -->
  <section>
    <h2>🔴 Live Now</h2>
    <div class="reels-scroller">
     @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach
    </div>
  </section>

  <!-- FOR ME -->
  <section>
    <h2>💫 For Me</h2>
    <div class="reels-scroller">
      @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach
    </div>
  </section>

  <!-- CLASSES -->
  <section>
    <h2>🎓 Classes / Courses</h2>
    <div class="reels-scroller">
     @foreach($reels as $reel)
      <article class="reels-card">
        <span class="reels-rank">{{ $reel->title }}</span>
        <div class="reels-thumb"> <video class="reel-video" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                                    <source src="{{ $reel->video_url }}" type="video/mp4">
                                    {{ trans('public.browser_not_support_video') }}
                                </video></div>
        <div class="reels-rail-vert">
            <form action="{{ route('reels.like', $reel->id) }}" method="POST" class="d-inline like-form">
            @csrf
                <button type="submit" class="action-btn d-flex align-items-center {{ $reel->likes->count() > 0 ? 'liked' : '' }}">
                <i data-feather="heart" width="20" height="20"></i>
                <span class="reels-circle reels-bg-red">{{ $reel->likes_count }}</span> </button>
            </form>

          <span class="reels-circle reels-bg-yellow">{{ $reel->comments_count }}</span>
          <span class="reels-circle reels-bg-green">↗</span>
          <span class="reels-circle reels-bg-blue">🎁</span>
        </div>
        <div class="reels-meta">
          <div class="reels-dot reels-bg-red"></div><div class="reels-dot reels-bg-orange"></div><div class="reels-dot reels-bg-yellow"></div>
          <div class="reels-dot reels-bg-green"></div><div class="reels-dot reels-bg-blue"></div>
          <span class="reels-count">5,340+</span>
        </div>
        <div class="reels-cta"><button class="reels-btn-sm">Watch</button></div>
      </article>
      @endforeach
    </div>
  </section>

</main>
@endsection
<script>
  // smooth mouse-wheel horizontal scrolling for carousels
  document.querySelectorAll('.reels-scroller').forEach(s=>{
    s.addEventListener('wheel', e=>{
      if(Math.abs(e.deltaY)>Math.abs(e.deltaX)){ s.scrollLeft+=e.deltaY; e.preventDefault(); }
    }, {passive:false});
  });
</script>