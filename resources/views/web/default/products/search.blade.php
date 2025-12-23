@extends('web.default.layouts.app')

@section('content')
  <div class="shop-wrap">
      
    <!-- Membership banner -->
    <div class="shop-banner">
      <div class="shop-chip">💎 Full access to all products</div>
      <div class="shop-chip">€1/mo • €10/yr • €33 lifetime</div>
      
       @if(auth()->check())
            <button class="shop-cta"><a href="/membership">Upgrade</a></button>
          @else
           <button class="shop-cta"><a href="/login">Upgrade</a></button>
          @endif  
    </div>
    <h2>Trending Products</h2>

      <!-- Trending cards (horizontal) -->
      <div class="shop-trend">
        @foreach($trendingProducts as $product)
        <!-- {{ clean($product->title,'title') }} -->
        <article class="shop-card">
          <div class="shop-media">
            <a href="{{ $product->getUrl() }}" class="image-box__a"><img src="{{ $product->thumbnail }}" class="img-cover" alt="{{ $product->title }}"></a>
            <div class="shop-grad"></div>
            <div class="shop-meta">
              <div class="shop-vendor"><img src="{{ $product->creator->getAvatar() }}" style="width: 50px;" class="img-cover" alt="{{ $product->creator->full_name }}">
                <a href="{{ $product->creator->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $product->creator->full_name }}</a></div>
              <div style="font-weight:900">{{Str::limit($product->title, 13, '..') }}</div>
              <div class="shop-stars">
                <!-- Your existing star rating PHP code here -->
              </div>
              <div class="shop-price-row">
              <span class="shop-price">
                <!-- Your existing price display PHP code here -->
              </span>
              </div>
              <button type="button" data-id="{{ $product->id }}" class="shop-atk btn-add-product-to-cart">Add to Cart</button>
            </div>
            
            <div class="shop-rail">
              <div class="shop-circle" style="--clr:var(--red)">❤️</div>
              <div class="shop-tiny" style="--clr:var(--red)">{{ $product->like_count}}</div>
              <div class="shop-circle" style="--clr:var(--orange)">💬</div>
              <div class="shop-tiny" style="--clr:var(--orange)">{{ $product->comments_count}}</div>
              <div class="shop-circle" style="--clr:var(--green)">🎁</div>
              <div class="shop-tiny" style="--clr:var(--green)">{{ $product->gift_count}}</div>
              <div class="shop-circle" style="--clr:var(--blue)">🔖</div>
              <div class="shop-tiny" style="--clr:var(--blue)">{{ $product->saved_count}}</div>
              <div class="shop-circle" style="--clr:var(--violet)">↗</div>
              <div class="shop-tiny" style="--clr:var(--blue)">{{ $product->share_count}}</div>
            </div>
          </div>
        </article>
        @endforeach
      </div>

      <div class="shop-sp"></div>

    <!-- Search -->
    <form action="{{ (!empty($isRewardProducts) and $isRewardProducts) ? '/reward-products' : '/products' }}" method="get">
      <div class="shop-search">
        <span style="color:var(--gold);font-weight:900">🔎</span>
        <input type="text" name="search" class="form-control border-0" value="{{ request()->get('search') }}" placeholder="What are you looking for?"/>
        <button class="shop-ghost">{{ trans('home.find') }}</button>
      </div>
    </form>

    <div class="shop-sp"></div>
    
    <form action="{{ (!empty($isRewardProducts) and $isRewardProducts) ? '/reward-products' : '/products' }}" method="get" id="filtersForm">
      <!-- Category chips -->
      @if(!empty($productCategories))
        @if(!empty($selectedCategory))
            <input type="hidden" name="category_id" value="{{ $selectedCategory->id }}">
        @endif
        <div class="shop-chips">
          @foreach($productCategories as $productCategory)
            @if(!empty($productCategory->subCategories) and count($productCategory->subCategories))
              @foreach($productCategory->subCategories as $subCategory)
                <a href="{{ $subCategory->getUrl() }}" class="d-flex align-items-center font-14 font-weight-normal mt-20 {{ (!empty($selectedCategory) and $selectedCategory->id == $subCategory->id) ? 'text-primary' : '' }}">
                  <div class="shop-pill @if(!empty($selectedCategory) and $selectedCategory->id == $subCategory->id) active @endif">
                    {{ $subCategory->title }}
                  </div>
                </a>
              @endforeach
            @else
              <a href="{{ $productCategory->getUrl() }}" class="d-flex align-items-center font-14 font-weight-bold mt-20 {{ (!empty($selectedCategory) and $selectedCategory->id == $productCategory->id) ? 'text-primary' : '' }}">
                <div class="shop-pill @if(!empty($selectedCategory) and $selectedCategory->id == $productCategory->id) active @endif">
                  {{ $productCategory->title }}
                </div>
              </a>
            @endif
          @endforeach
        </div>
      @endif

      <h2>Popular</h2>
    
      <!-- Popular grid -->
      <section class="shop-grid">
        @foreach($products as $product)
        <!-- {{ clean($product->title,'title') }} -->
          <article class="shop-p">
            <div class="shop-ph"><img src="{{ $product->creator->getAvatar() }}" class="img-cover" alt="{{ $product->creator->full_name }}">
              <a href="{{ $product->creator->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $product->creator->full_name }}</a>
            </div>
            <div class="shop-img">
              <a href="{{ $product->getUrl() }}" class="image-box__a">
                <img src="{{ $product->thumbnail }}" class="img-cover" alt="{{ $product->title }}">
              </a>
            </div>
            <div class="shop-pd">
              <div style="font-weight:900"><a href="{{ $product->getUrl() }}">{{ Str::limit($product->title, 13, '..') }}</a></div>
              <div class="shop-stars">
                @php
                  $i = 5;
                  $rate = $product->getRate();
                @endphp
              
                @php
                    $rating = $rate ?? 0;
                    $filledStars = min(5, max(0, $rate));
                    $emptyStars = 5 - $filledStars;
                @endphp
                
                @for($i = 0; $i < $filledStars; $i++)
                    ★
                @endfor
                @for($i = 0; $i < $emptyStars; $i++)
                    ☆
                @endfor
                  
                <!-- Your existing star rating PHP code here -->
              </div> 
              <div class="shop-row-end">
                <div class="shop-price-row">
                    @if(!empty($product->price) and $product->price > 0)
                        @if($product->getPriceWithActiveDiscountPrice() < $product->price)
                            <span class="real">{{ handlePrice($product->getPriceWithActiveDiscountPrice(), true, true, false, null, true, 'store') }}</span>
                            <span class="off ml-10">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
                        @else
                            <span class="real">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
                        @endif
                    @else
                        <span class="real">{{ trans('public.free') }}</span>
                    @endif
                </div>
               @if($product->getAvailability() > 0)<button type="button" data-id="{{ $product->id }}" class="shop-atk btn-add-product-to-cart">Add to Cart</button>@endif
              </div>
            </div>
          </article>
        @endforeach
      </section>
      
      <div class="shop-sp"></div>
      <div class="mt-50 pt-30">
        {{ $products->appends(request()->input())->links('vendor.pagination.panel') }}
      </div>
    </form>
  </div>
@endsection

<!-- Your existing JavaScript remains unchanged -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Fallback to CDN if local files don't work -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
  <script>
  // Cart and payment functionality
  $(document).ready(function() {
    $('body').on('click', '.btn-add-product-to-cart', function (e) {
        e.preventDefault();

        const item_id = $(this).attr('data-id');

        const html = `
            <form action="/cart/store" method="post" class="" id="productAddToCartForm">
                <input type="hidden" name="_token" value="${window.csrfToken}">
                <input type="hidden" name="item_id" value="${item_id}">
                <input type="hidden" name="item_name" value="product_id">
            </form>
        `;

        $('body').append(html);

        $(this).addClass('loadingbar primary').prop('disabled', true);

        const $form = $('#productAddToCartForm');

        $form.trigger('submit');
    });
    
    $('body').on('click', '.js-course-direct-payment', function (e) {
      const $this = $(this);
      $this.addClass('loadingbar danger').prop('disabled', true);

      const $form = $this.closest('form');
      $form.attr('action', '/course/direct-payment');
      $form.trigger('submit');
    });
  });

   @if(session()->has('toast'))
    (function() {
        const toastData = @json(session()->get('toast'));
        $.toast({
            heading: toastData.title || '',
            text: toastData.msg || '',
            bgColor: toastData.status === 'success' ? '#43d477' : '#f63c3c',
            textColor: 'white',
            hideAfter: 10000,
            position: 'bottom-right',
            icon: toastData.status
        });
    })();
    @endif
  </script>
<script src="/assets/default/js/parts/main.min.js"></script>
  <link rel="stylesheet" href="{{ url('/assets/default/vendors/toast/jquery.toast.min.css') }}">
<script src="{{ url('/assets/default/vendors/toast/jquery.toast.min.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/time-counter-down.min.js') }}"></script>
<script src="{{ url('/assets/default/vendors/barrating/jquery.barrating.min.js') }}"></script>
<script src="{{ url('/assets/default/vendors/video/video.min.js') }}"></script>
<script src="{{ url('/assets/default/vendors/video/youtube.min.js') }}"></script>
<script src="{{ url('/assets/default/vendors/video/vimeo.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/comment.min.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/video_player_helpers.min.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/webinar_show.min.js') }}"></script>
<script src="{{ url('/assets/default/js/parts/blog.min.js') }}"></script>
</body>

</html>
