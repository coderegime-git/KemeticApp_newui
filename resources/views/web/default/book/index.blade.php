@extends('web.default.layouts.app')

@section('content')
 <div class="book-page">

    <!-- Membership Banner -->
    <div class="book-membership-banner">
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
          <span class="book-hero-secondary-pill">
            or <strong>Add to Cart</strong> and read instantly on Kemetic App.
          </span>
        </div>

        <div style="margin-top: 10px;">
          <span class="book-hero-badge">
            <span class="book-hero-badge-dot"></span>
            Included free with Kemetic Membership
          </span>
        </div>
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

    <!-- Categories -->
    <div class="book-categories">
      <a href="{{ url('/books') }}" class="book-chip @if(empty($selectedCategory)) book-chip--active @endif">All</a>
      @foreach($bookCategories as $bookCategory)
        <a href="{{ url('/books/category/' . $bookCategory->slug) }}" class="book-chip @if(!empty($selectedCategory) && $selectedCategory->id == $bookCategory->id) book-chip--active @endif">
          {{ $bookCategory->title }}
        </a>
      @endforeach
    </div>

    <!-- Global Books Row -->
    <section>
      <div class="book-section-header">
        <div class="book-section-title">Global Scrolls</div>
        <div class="book-section-caption">Rated & ranked by the global Kemetic community.</div>
      </div>

      <div class="book-horizontal-scroll">
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
          <div class="book-book-title">{{ Str::limit($book->title, 40) }}</div>
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
      </div>

      <!-- Pagination -->
      @if($books->hasPages())
      <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $books->links() }}
      </div>
      @endif
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
          <article class="book-book-card">
            <div class="book-book-cover">
              <img src="{{ $book->getImage()}}" alt="{{ $book->title }}">
              <span class="book-badge-tag">Trending</span>
            </div>
            <div class="book-book-title">{{ $book->title }}</div>
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