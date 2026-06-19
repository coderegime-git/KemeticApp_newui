@extends('web.default.layouts.app')

@section('content')
 <div class="book-page">

    <!-- Membership Banner -->
    <!-- <div class="book-membership-banner">
      <div class="book-membership-badge">∞</div>
      <div>
        <div class="book-membership-text-main">
          Unlock Unlimited Kemetic Knowledge – €10/yr or €33/Lifetime
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
      <div class="article-chip">€10/yr • €33 lifetime</div>
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
            @php
                $rate = $popularBook->getRate();
                $i = 5;
              @endphp
              @while(--$i >= 5 - $rate)
                ★
              @endwhile
              @while($i-- >= 0)
                ☆
              @endwhile
            <!-- <span class="book-star"></span>
            <span class="book-star"></span>
            <span class="book-star"></span>
            <span class="book-star"></span>
            <span class="book-star"></span> -->
          </div>
          <span class="book-hero-rating-count">
            {{$popularBook->reviews->count()}} + 
            <!-- global engagements -->
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
          <img src="{{ $popularBook->creator->getAvatar(190) 
            ? (Str::startsWith($popularBook->creator->getAvatar(190), '/') ? $popularBook->creator->getAvatar(190) : '/' . $popularBook->creator->getAvatar(190))
            : url('/getDefaultAvatar?item=' . ($popularBook->creator['id'] ?? '') . '&name=' . urlencode($popularBook->creator['full_name']) . '&size=190') 
        }}" 
            alt="{{ $popularBook->creator['full_name'] }}">
          
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
      <form action="/book" method="get" id="searchSection">
        @if(!empty($selectedCategory))
          <input type="hidden" name="category_id" value="{{ $selectedCategory->id }}">
        @endif
        <div class="article-search">
          <span style="color:var(--gold);font-weight:900">🔎</span>
          <input  type="text" name="search"  value="{{ request()->get('search') }}" placeholder="Serach for Scrolls">
          @if(request()->get('search'))
            <button type="button" onclick="clearSearch(this)" style="background: transparent; border: none; color: #999; font-size: 16px; cursor: pointer; padding: 0 10px; margin-right: 5px;">✕</button>
          @endif
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
            <!-- @if($book->id === optional($popularBook)->id)
              <span class="book-badge-tag">Global #1</span>
            @elseif($book->likes_count > 100)
              <span class="book-badge-tag">Bestseller</span>
            @else
              <span class="book-badge-tag">New</span>
            @endif -->
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
              @php
                $rate = $book->getRate();
                $i = 5;
              @endphp
              @while(--$i >= 5 - $rate)
                ★
              @endwhile
              @while($i-- >= 0)
                ☆
              @endwhile
            </div>
            <span>{{$book->reviews->count()}} ratings</span>
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
    <!-- <section class="book-trending-strip">
      <div class="book-section-header">
        <div class="book-section-title">Trending Collections</div>
        <div class="book-section-caption">Bundles & series everyone is reading this week.</div>
      </div>

      <div class="book-horizontal-scroll">
        @foreach($books->take(3) as $book)
          @if($book->likes_count > 50)
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
    </section> -->

  </div>
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