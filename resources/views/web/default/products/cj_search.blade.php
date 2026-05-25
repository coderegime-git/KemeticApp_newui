@extends('web.default.layouts.app')

@section('content')
  <div class="shop-wrap">
      
    <!-- CJ Dropshipping banner -->
    <div class="shop-banner">
      <div class="shop-chip">📦 Global Dropshipping Products</div>
      <div class="shop-chip">Millions of products from CJ Dropshipping</div>
    </div>
    
    <h2>Browse Dropshipping Products</h2>

    <!-- Search -->
    <form action="{{ route('dropship.products.index') }}" method="get">
      <div class="shop-search">
        <span style="color:var(--gold);font-weight:900">🔎</span>
        <input type="text" name="search" class="form-control border-0" value="{{ $search }}" placeholder="Search millions of products..."/>
        <button class="shop-ghost">Search</button>
      </div>

      <!-- Categories & Filters -->
      <div class="shop-filters mt-15">
        <div class="d-flex flex-wrap align-items-center">
            <a href="{{ route('dropship.products.index', ['search' => $search]) }}" class="shop-cat-pill {{ empty($selectedCategory) ? 'active' : '' }}">All Categories</a>
            @if(!empty($cjCategories))
                @foreach(array_slice($cjCategories, 0, 8) as $cat)
                    <a href="{{ route('dropship.products.index', ['category_id' => $cat['categoryId'], 'search' => $search]) }}" 
                       class="shop-cat-pill {{ $selectedCategory == $cat['categoryId'] ? 'active' : '' }}">
                        {{ $cat['categoryName'] }}
                    </a>
                @endforeach
            @endif
        </div>
      </div>
    </form>

    <div class="shop-sp"></div>
    
    <h2>Results ({{ $total }})</h2>
  
    <!-- Grid -->
    <section class="shop-grid">
      @if(!empty($products) && count($products) > 0)
        @foreach($products as $product)
          <article class="shop-p">
            <div class="shop-img">
              <a href="{{ route('dropship.products.show', $product['pid']) }}" class="image-box__a">
                <img src="{{ $product['productImage'] }}" class="img-cover" alt="{{ $product['productNameEn'] }}">
              </a>
            </div>
            <div class="shop-pd">
              <div style="font-weight:900">
                <a href="{{ route('dropship.products.show', $product['pid']) }}">
                  {{ Str::limit($product['productNameEn'], 30, '..') }}
                </a>
              </div>
              
              <div class="shop-row-end">
                <div class="shop-price-row">
                  <span class="real">${{ $product['sellPrice'] ?? '0.00' }}</span>
                </div>
                
                <form action="{{ route('dropship.products.import_cart', $product['pid']) }}" method="post">
                    @csrf
                    <button type="submit" class="shop-atk">Add to Cart</button>
                </form>
              </div>
            </div>
          </article>
        @endforeach
      @else
        <div class="col-12 text-center py-5">
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'webinar.png',
                'title' => 'No Dropshipping Products Found',
                'hint' => 'Try a different search term',
            ])
        </div>
      @endif
    </section>

    <!-- Pagination (Simplified for now) -->
    @if(isset($totalPages) && $totalPages > 1)
    <div class="mt-4 d-flex justify-content-center">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                @if($pageNum > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ route('dropship.products.index', ['page' => $pageNum - 1, 'search' => $search]) }}">Previous</a>
                </li>
                @endif
                <li class="page-item active"><a class="page-link" href="#">{{ $pageNum }}</a></li>
                @if($pageNum < $totalPages)
                <li class="page-item">
                    <a class="page-link" href="{{ route('dropship.products.index', ['page' => $pageNum + 1, 'search' => $search]) }}">Next</a>
                </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
  </div>

  <style>
      .shop-cat-pill {
          background: #1a1a1a;
          color: #ccc;
          padding: 6px 16px;
          border-radius: 20px;
          margin-right: 8px;
          margin-bottom: 8px;
          font-size: 13px;
          font-weight: 600;
          border: 1px solid #333;
          transition: all 0.3s ease;
      }
      .shop-cat-pill:hover, .shop-cat-pill.active {
          background: var(--gold);
          color: #000;
          border-color: var(--gold);
          text-decoration: none;
      }
      .pagination .page-link {
          background: #1a1a1a;
          border-color: #333;
          color: #fff;
      }
      .pagination .page-item.active .page-link {
          background: var(--gold);
          border-color: var(--gold);
          color: #000;
      }
  </style>
@endsection
