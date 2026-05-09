@extends('web.default.layouts.newapp')

@push('styles_top')
  <style>
    /* Adjust container for panel */
    .shop-wrap {
      padding: 20px;
      background: #0b0b0b;
      border-radius: 20px;
      margin-top: 20px;
    }
  </style>
@endpush

@section('content')
  <div class="shop-wrap">

    {{-- ── Trending Carousel ──────────────────────────────────────── --}}
    @if(!empty($trendingProducts))
      <h2>Trending on Products</h2>
      <div class="shop-trend">
        @foreach($trendingProducts as $product)
          <article class="shop-card">
            <div class="shop-media">
              <a href="{{ route('panel.cj.products.show', $product['pid']) }}" class="image-box__a">
                <img src="{{ $product['productImage'] ?? '' }}" class="img-cover" alt="{{ $product['productNameEn'] ?? '' }}"
                  loading="lazy">
              </a>
              <div class="shop-grad"></div>
              <div class="shop-meta">
                <div style="font-weight:900">{{ Str::limit($product['productNameEn'] ?? 'Product', 20, '..') }}</div>
                @if(!empty($product['categoryName']))
                  <div class="shop-muted" style="font-size:11px">{{ Str::limit($product['categoryName'], 28, '..') }}</div>
                @endif
                <div class="shop-price-row">
                  <span class="shop-price">${{ number_format((float) ($product['sellPrice'] ?? 0), 2) }}</span>
                  @if(!empty($product['isFreeShipping']))
                    <span class="text-warning" style="font-size:11px;margin-left:6px">Free shipping</span>
                  @endif
                </div>
                <a href="{{ route('panel.cj.products.show', $product['pid']) }}" class="shop-atk"
                  style="display:inline-block;text-align:center">View</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
      <div class="shop-sp"></div>
    @endif

    {{-- ── Search ──────────────────────────────────────────────────── --}}
    <form action="{{ route('panel.cj.products.index') }}" method="GET">
      <div class="shop-search">
        <span style="color:var(--gold);font-weight:900">🔎</span>
        <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
        <input type="text" name="search" class="form-control border-0" value="{{ request('search') }}"
          placeholder="Search CJ products…">
        <button class="shop-ghost">Find</button>
      </div>
    </form>

    <div class="shop-sp"></div>

    {{-- ── Filters ─────────────────────────────────────────────────── --}}
    <form action="{{ route('panel.cj.products.index') }}" method="GET" id="cjFiltersForm">
      <input type="hidden" name="search" value="{{ request('search') }}">

      {{-- Category Chips --}}
      @if(!empty($categories))
        <div class="shop-chips">
          <a href="{{ route('panel.cj.products.index') }}{{ request('search') ? '?search=' . request('search') : '' }}">
            <div class="shop-pill {{ empty($selectedCategoryId) ? 'active' : '' }}">All</div>
          </a>
          @foreach($categories as $firstLevel)
            @if(!empty($firstLevel['categoryFirstList']))
              @foreach($firstLevel['categoryFirstList'] as $secondLevel)
                @if(!empty($secondLevel['categorySecondList']))
                  @foreach($secondLevel['categorySecondList'] as $thirdLevel)
                    <a
                      href="{{ route('panel.cj.products.index', ['category_id' => $thirdLevel['categoryId'], 'search' => request('search')]) }}">
                      <div class="shop-pill {{ $selectedCategoryId == $thirdLevel['categoryId'] ? 'active' : '' }}">
                        {{ $thirdLevel['categoryName'] }}
                      </div>
                    </a>
                  @endforeach
                @else
                  <a
                    href="{{ route('panel.cj.products.index', ['category_id' => $secondLevel['categorySecondId'] ?? '', 'search' => request('search')]) }}">
                    <div class="shop-pill">{{ $secondLevel['categorySecondName'] }}</div>
                  </a>
                @endif
              @endforeach
            @endif
          @endforeach
        </div>
      @endif

      {{-- Sort / filter bar --}}
      <!-- <div class="cj-filter-bar" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin:16px 0">
        <select name="order_by" onchange="this.form.submit()" class="cj-select">
          <option value="createAt" {{ request('order_by','createAt') === 'createAt' ? 'selected' : '' }}>Newest</option>
          <option value="listedNum" {{ request('order_by') === 'listedNum' ? 'selected' : '' }}>Most Listed</option>
        </select>
        <select name="sort" onchange="this.form.submit()" class="cj-select">
          <option value="desc" {{ request('sort','desc') === 'desc' ? 'selected' : '' }}>↓ Desc</option>
          <option value="asc"  {{ request('sort') === 'asc'  ? 'selected' : '' }}>↑ Asc</option>
        </select>
        <input type="number" name="min_price" value="{{ request('min_price') }}"
               placeholder="Min $" class="cj-price-input" style="width:80px">
        <input type="number" name="max_price" value="{{ request('max_price') }}"
               placeholder="Max $" class="cj-price-input" style="width:80px">
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
          <input type="checkbox" name="free_shipping" value="on" {{ request('free_shipping') === 'on' ? 'checked' : '' }}
                 onchange="this.form.submit()"> Free Shipping
        </label>
        @if(request()->except(['page']))
        <a href="{{ route('panel.cj.products.index') }}" class="cj-clear-btn">✕ Clear</a>
        @endif
      </div> -->

      {{-- ── Product Grid ─────────────────────────────────────────── --}}
      <h2>
        Products
        @if($total > 0)
          <span style="font-size:14px;font-weight:400;opacity:.6">({{ number_format($total) }} results)</span>
        @endif
      </h2>

      <section class="shop-grid">
        @if(!empty($products))
          @foreach($products as $product)
            <article class="shop-p">

              {{-- Seller / source row --}}
              <div class="shop-ph">
                <!-- <img src="https://cjdropshipping.com/favicon.ico" style="width:20px;height:20px;border-radius:50%" alt="CJ"> -->
                <span class="ml-5 font-14" style="opacity:.7">Dropshipping</span>
                @if(!empty($product['isFreeShipping']))
                  <span class="ml-auto" style="font-size:11px;color:var(--green,#43d477)">Free Ship</span>
                @endif
              </div>

              {{-- Image --}}
              <div class="shop-img">
                <a href="{{ route('panel.cj.products.show', $product['pid']) }}">
                  <img src="{{ $product['productImage'] ?? '' }}" class="img-cover"
                    alt="{{ $product['productNameEn'] ?? 'Product' }}" loading="lazy">
                </a>
              </div>

              {{-- Info --}}
              <div class="shop-pd">
                <div style="font-weight:900">
                  <a href="{{ route('panel.cj.products.show', $product['pid']) }}">
                    {{ Str::limit($product['productNameEn'] ?? 'Product', 40, '..') }}
                  </a>
                </div>

                {{-- Category --}}
                @if(!empty($product['categoryName']))
                  <div style="font-size:11px;opacity:.55;margin-bottom:4px">{{ Str::limit($product['categoryName'], 35, '..') }}
                  </div>
                @endif

                {{-- SKU --}}
                @if(!empty($product['productSku']))
                  <div style="font-size:10px;opacity:.45;letter-spacing:.04em">SKU: {{ $product['productSku'] }}</div>
                @endif

                {{-- Price + CTA row --}}
                <div class="shop-row-end" style="margin-top:6px">
                  <div class="shop-price-row">
                    <span class="real"
                      style="font-weight:900">${{ number_format((float) ($product['sellPrice'] ?? 0), 2) }}</span>
                  </div>
                  <a href="{{ route('panel.cj.products.show', $product['pid']) }}" class="shop-atk">View</a>
                </div>
              </div>

            </article>
          @endforeach
        @else
          <div style="grid-column:1/-1;text-align:center;padding:60px 20px;opacity:.5">
            <div style="font-size:48px">📦</div>
            <p>No products found. Try a different search or filter.</p>
          </div>
        @endif
      </section>

      {{-- ── Pagination ───────────────────────────────────────────── --}}
      @if($totalPages > 1)
        <div class="cj-pagination" style="display:flex;justify-content:center;gap:8px;padding:20px 10px;flex-wrap:wrap">
          @if($currentPage > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="cj-page-btn">‹ Prev</a>
          @endif

          @php
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $currentPage + 2);
          @endphp

          @if($start > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}" class="cj-page-btn">1</a>
            @if($start > 2)<span class="cj-page-btn" style="opacity:.4">…</span>@endif
          @endif

          @for($p = $start; $p <= $end; $p++)
            <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}"
              class="cj-page-btn {{ $p == $currentPage ? 'active' : '' }}">{{ $p }}</a>
          @endfor

          @if($end < $totalPages)
            @if($end < $totalPages - 1)<span class="cj-page-btn" style="opacity:.4">…</span>@endif
            <a href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}" class="cj-page-btn">{{ $totalPages }}</a>
          @endif

          @if($currentPage < $totalPages)
            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="cj-page-btn">Next ›</a>
          @endif
        </div>
      @endif

    </form>
  </div>

  <style>
    /* ── CJ-specific additions (inherits your existing shop-* classes) ── */
    .cj-select {
      background: var(--panel, #1a1a1a);
      border: 1px solid var(--edge, #333);
      color: var(--text, #eee);
      border-radius: 8px;
      padding: 6px 10px;
      font-size: 13px;
      cursor: pointer;
    }

    .cj-price-input {
      background: var(--panel, #1a1a1a);
      border: 1px solid var(--edge, #333);
      color: var(--text, #eee);
      border-radius: 8px;
      padding: 6px 8px;
      font-size: 13px;
    }

    .cj-clear-btn {
      font-size: 12px;
      opacity: .6;
      padding: 6px 10px;
      border: 1px solid var(--edge, #444);
      border-radius: 8px;
      text-decoration: none;
      color: inherit;
    }

    .cj-clear-btn:hover {
      opacity: 1;
    }

    .cj-page-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 36px;
      height: 36px;
      padding: 0 10px;
      border-radius: 8px;
      border: 1px solid var(--edge, #333);
      font-size: 13px;
      text-decoration: none;
      color: inherit;
      background: var(--panel, #111);
      transition: background .2s;
    }

    .cj-page-btn:hover,
    .cj-page-btn.active {
      background: var(--gold, #FFD700);
      color: #000;
      border-color: var(--gold, #FFD700);
      font-weight: 800;
    }
  </style>
@endsection