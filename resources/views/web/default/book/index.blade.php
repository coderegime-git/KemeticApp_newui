@extends('web.default.layouts.app')

@section('content')
 <div class="book-page">

    <!-- Membership Banner -->
    <!-- <div class="book-membership-banner">
      <div class="book-membership-badge">∞</div>
      <div>
        <div class="book-membership-text-main">
          Unlock Unlimited Kemetic Knowledge – €1/mo or €10/yr
        </div>
        <div class="book-membership-text-sub">
          Access all ebooks, PDFs, courses, portals, livestreams & premium articles with one membership.
        </div>
      </div>
      <div class="book-membership-actions">
        @if(auth()->check())
          <button class="book-btn-pill"><a href="/membership">Learn More</a></button>
          <button class="book-btn-pill book-btn-pill--gold"><a href="/membership">Become a Member</a></button>
        @else
          <button class="book-btn-pill"><a href="/login">Learn More</a></button>
          <button class="book-btn-pill book-btn-pill--gold"><a href="/login">Become a Member</a></button>
        @endif  
      </div>
    </div> -->

    <div class="shop-banner">
      <div class="article-chip">💎 Unlock Unlimited Articles, Reels & Live</div>
      <div class="article-chip">€1/mo • €10/yr • €33 lifetime</div>
       @if(auth()->check())
          <button class="shop-cta"><a href="/membership">Upgrade</a></button>
        @else
          <button class="shop-cta"><a href="/login">Upgrade</a></button>
        @endif
    </div>

    <!-- Hero: Global #1 Book -->
    @if($popularBook)
    <section class="book-hero">
      <div class="book-hero-inner">
        <div class="book-hero-eyebrow">
          <span class="book-dot"></span>
          Global #1 Book • Featured by the Community
        </div>
        <h1 class="book-hero-title">{{ $popularBook->title }}</h1>
        <p class="book-hero-subtitle">
          @if($popularBook->categories)
                {{ $popularBook->categories->title }}
              @else
                Spiritual Collection
              @endif
        </p>

        <div class="book-hero-meta">
          <div class="book-chakra-stars">
            <span class="book-star"></span>
            <span class="book-star"></span>
            <span class="book-star"></span>
            <span class="book-star"></span>
            <span class="book-star"></span>
          </div>
          <span class="book-hero-rating-count">
            {{ $popularBook->likes_count + $popularBook->comments_count + $popularBook->saved_items_count }}+ global engagements
          </span>
        </div>

        <div class="book-hero-cta-row">
          <a href="{{ $popularBook->getUrl() }}">
            <button class="book-btn-hero-main">View Scrolls</button>
          </a>
          <!-- <span class="book-hero-secondary-pill">
            or <strong>Add to Cart</strong> and read instantly on Kemetic App.
          </span> -->
        </div>

        <!-- <div style="margin-top: 10px;">
          <span class="book-hero-badge">
            <span class="book-hero-badge-dot"></span>
            Included free with Kemetic Membership
          </span>
        </div> -->
      </div>

      <aside class="book-hero-side">
        @if($popularBook->creator)
         <a href="{{ $popularBook->creator->getProfileUrl() }}">
        <div class="book-hero-avatar">
         
          <img src="{{ $popularBook->creator->avatar ?? 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=200' }}" alt="Creator avatar">
          <span>{{ $popularBook->creator->full_name }}</span>
        </div></a>
        @endif

        <div class="book-hero-side-card">
          <strong>Why this Scrolls?</strong><br/>
          • Most popular in our collection<br/>
          • High engagement from community<br/>
          • Perfect for spiritual seekers<br/>
          • Read on any device
        </div>
      </aside>
    </section>
    @endif

    <div class="article-row">
      <form action="/book" method="get">
        @if(!empty($selectedCategory))
          <input type="hidden" name="category_id" value="{{ $selectedCategory->id }}">
        @endif
        <div class="article-search">
          <span style="color:var(--gold);font-weight:900">🔎</span>
          <input  type="text" name="search"  value="{{ request()->get('search') }}" placeholder="Serach for Scrolls">
          <button type="submit" class="article-pill">{{ trans('home.find') }}</button>
          <!-- ⚙️ Filters  onclick="alert('Open filters')" -->
        </div>
      </form>
    </div>

    <!-- Categories -->
    <div class="book-categories">
      <a href="{{ url('/book') }}@if(request()->get('search'))?search={{ request()->get('search') }}@endif" class="book-chip @if(empty($selectedCategory)) book-chip--active @endif">All</a>
      @foreach($bookCategories as $bookCategory)
        <a href="/book?category_id={{$bookCategory->id}}@if(request()->get('search'))&search={{ request()->get('search') }}@endif" class="book-chip @if(!empty($selectedCategory) && $selectedCategory->id == $bookCategory->id) book-chip--active @endif">
          {{ $bookCategory->title }}
        </a>
      @endforeach
    </div>

    <!-- Global Books Row -->
    <section style="padding-left: 13px;">
      <div class="book-section-header">
        <div class="book-section-title">Global Scrolls</div>
        <div class="book-section-caption">Rated & ranked by the global Kemetic community.</div>
      </div>

      <div class="book-horizontal-scroll">
        @if(!empty($books) and !$books->isEmpty())
        @foreach($books as $book)
        <article class="book-book-card">
          <div class="book-book-cover">
            <img src="{{ $book->getImage() ?? 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=800' }}" alt="{{ $book->title }}">
            @if($book->id === optional($popularBook)->id)
              <span class="book-badge-tag">Global #1</span>
            @elseif($book->likes_count > 100)
              <span class="book-badge-tag">Bestseller</span>
            @else
              <span class="book-badge-tag">New</span>
            @endif
          </div>
          <div class="book-book-title">{{ Str::limit($book->title, 13, '..') }}</div>
          <div class="book-book-category">
            @if($book->categories)
              {{ $book->categories->title }}
            @else
              Spiritual • Kemetic History
            @endif
          </div>
          <div class="book-book-rating-row">
            <div class="book-chakra-stars-small">
              <span></span><span></span><span></span><span></span><span></span>
            </div>
            <span>{{ $book->likes_count + $book->comments_count }} ratings</span>
          </div>
          <div class="book-book-footer">
            <a href="{{ $book->getUrl() }}">
              <button class="book-btn-book">View Scrolls</button>
            </a>
            <span class="book-price-tag">
              @if($book->is_free)
                FREE
              @else
                €{{ $book->formatted_price }}
              @endif
            </span>
          </div>
        </article>
        @endforeach
        @else
          @include(getTemplate() . '.includes.no-result',[
              'file_name' => 'webinar.png',
              'title' => trans('No Scrolls Found'),
              'hint' => '',
          ])
        @endif
      </div>


      <div class="mt-50 pt-30" style="padding:10px;">
        {{ $books->appends(request()->input())->links('vendor.pagination.panel') }}
      </div>
    </section>
    

    <!-- Trending Strip -->
    <section class="book-trending-strip">
      <div class="book-section-header">
        <div class="book-section-title">Trending Collections</div>
        <div class="book-section-caption">Bundles & series everyone is reading this week.</div>
      </div>

      <div class="book-horizontal-scroll">
        <!-- You can add trending collections logic here -->
        @foreach($books->take(3) as $book)
          @if($book->likes_count > 50)
          <!-- {{ $book->title }} -->
          <article class="book-book-card">
            <div class="book-book-cover">
              <img src="{{ $book->getImage()}}" alt="{{ $book->title }}">
              <span class="book-badge-tag">Trending</span>
            </div>
            <div class="book-book-title">{{ Str::limit(clean($book->title,'title'), 13, '..') }}</div>
            <div class="book-book-category">
              @if($book->categories)
                {{ $book->categories->title }}
              @else
                Spiritual Collection
              @endif
            </div>
            <div class="book-book-rating-row">
              <div class="book-chakra-stars-small">
                <span></span><span></span><span></span><span></span><span></span>
              </div>
              <span>{{ $book->likes_count }} likes</span>
            </div>
            <div class="book-book-footer">
              <a href="{{ $book->getUrl() }}">
                <button class="book-btn-book">View Scrolls</button>
              </a>
              <span class="book-price-tag">
                @if($book->is_free)
                  FREE
                @else
                  €{{ $book->formatted_price }}
                @endif
              </span>
            </div>
          </article>
          @endif
        @endforeach
      </div>
    </section>

  </div>
@endsection