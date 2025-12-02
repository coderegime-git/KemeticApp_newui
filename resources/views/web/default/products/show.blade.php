@extends('web.default.layouts.app')

@section('content')
  <div class="shopdetail-wrap">
    <div class="shopdetail-top">
      <!-- <div class="shopdetail-logo">KA</div> -->
      <!-- <div class="shopdetail-crumbs"><a href="#">Shop</a> • Jewelry • Tiger Eye Necklace</div> -->
      <div class="shopdetail-member">
        <div class="shopdetail-chip">€1/mo • €10/yr • €33 lifetime</div>
         @if(auth()->check())
          <button class="shopdetail-cta"><a href="/membership">Upgrade</a></button>
        @else
          <button class="shopdetail-cta"><a href="/login">Upgrade</a></button>
        @endif  
      </div>
    </div>
  <form action="/cart/store" method="post" id="productAddToCartForm">
    {{ csrf_field() }}
    <input type="hidden" name="item_id" value="{{ $product->id }}">
    <input type="hidden" name="item_name" value="product_id">
    <div class="shopdetail-grid">
      <!-- left: media + info -->
      <div class="shopdetail-panel">
        <div class="shopdetail-media">
          <div class="shopdetail-hero">
             <img src="{{ $product->thumbnail }}" alt="{{ $product->title }}" class="main-s-image img-cover rounded-lg" loading="lazy">
          </div>
          @if(!empty($product->images) and count($product->images))
          <div class="shopdetail-thumbs">
            @foreach($product->images as $image)
             <div class="shopdetail-thumb"><img src="{{ $image->path }}" alt=""></div>
            @endforeach
          </div>
          @endif

          <div style="padding:12px 2px 0">
            <h1> {{ clean($product->title, 't') }}</h1>
             @php
              $total = $product->like_count + $product->comments_count + $product->gift_count + $product->saved_count + $product->share_count;
            @endphp
            <div class="shopdetail-stars">
              <div class="shopdetail-star" style="background:var(--red);"><span>{{ $product->like_count}}</span></div>
              <div class="shopdetail-star" style="background:var(--orange)">{{ $product->comments_count}}</div>
              <div class="shopdetail-star" style="background:var(--yellow)">{{ $product->gift_count}}</div>
              <div class="shopdetail-star" style="background:var(--green)">{{ $product->saved_count}}</div>
              <div class="shopdetail-star" style="background:var(--blue)">{{ $product->share_count}}</div>
              <div class="shopdetail-muted" style="margin-left:8px;font-weight:800">{{$total}} total</div>
            </div>

            <div class="shopdetail-about">
              <strong>About this item</strong><br>
              {!! $product->description !!}
            </div>

            <div class="shopdetail-vendor">
              <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200" alt="">
              <div>@TigerEyeCrystals &nbsp;•&nbsp; <a href="{{ $product->category->getUrl() }}" target="_blank"><span class="shopdetail-muted">{{ $product->category->title }}</span></a></div>
              <div style="margin-left:auto">❤️ {{ $product->reviews->pluck('creator_id')->count() }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- right: sticky purchase card -->
      <aside class="shopdetail-purchase">
        <div class="shopdetail-muted" style="font-weight:800">Price</div>
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
        <div class="shopdetail-price">
          
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

        @if($product->isPhysical())
            @if(!empty($product->delivery_fee) and $product->delivery_fee > 0)
                <span class="shipping-price d-block mt-5">+ {{ handlePrice($product->delivery_fee) }} {{ trans('update.shipping') }}</span>
            @else
                <span class="text-warning d-block font-14 font-weight-500 mt-5">{{ trans('update.free_shipping') }}</span>
            @endif
        @endif
      </div>
      @php
        $productAvailability = $product->getAvailability();
      @endphp
        <!-- <div class="shopdetail-subnote">Get with €1/mo membership</div> -->
        <button type="button" class="shopdetail-btn-buy js-product-direct-payment" {{ ($productAvailability < 1) || ($product->price == 0) ? 'disabled' : '' }}>Buy Now</button>
        <button type="submit" class="shopdetail-btn-ghost " {{ ($productAvailability < 1) || ($product->price == 0) ? 'disabled' : '' }}> {{ ($productAvailability > 0) ? trans('public.add_to_cart') : trans('update.out_of_stock') }}</button>

        <div style="margin-top:16px;border-top:1px solid var(--edge);padding-top:12px">
          <div class="shopdetail-muted" style="font-weight:800;margin-bottom:8px">Includes</div>
          <ul style="margin:0 0 0 18px;line-height:1.7">
            <li>30-day returns</li>
            <li>Secure worldwide shipping</li>
            <li>Member perks & gifts</li>
          </ul>
        </div>
      </aside>
    </div>
  </div>

  <!-- Sticky bottom buy bar -->
  <div class="shopdetail-buybar">
    <div class="shopdetail-title"> {{ clean($product->title, 't') }}</div>
    <!-- <div class="shopdetail-line">€1/mo membership available</div> -->
    <div class="shopdetail-spacer"></div>
    <div style="font-weight:900;margin-right:10px">@if(!empty($product->price) and $product->price > 0)
            @if($product->getPriceWithActiveDiscountPrice() < $product->price)
                <span>{{ handlePrice($product->getPriceWithActiveDiscountPrice(), true, true, false, null, true, 'store') }}</span>
                <span>{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
            @else
                <span>{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
            @endif
        @else
            <span>{{ trans('public.free') }}</span>
        @endif

        @if($product->isPhysical())
            @if(!empty($product->delivery_fee) and $product->delivery_fee > 0)
                <span">+ {{ handlePrice($product->delivery_fee) }} {{ trans('update.shipping') }}</span>
            @else
                <span>{{ trans('update.free_shipping') }}</span>
            @endif
        @endif</div>
    <a href="/cart"><button type="button" class="shopdetail-btn js-product-direct-payment" {{ ($productAvailability < 1) || ($product->price == 0) ? 'disabled' : '' }}>Buy Now</button></a>
  </div>
  </form>
  @endsection
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Fallback to CDN if local files don't work -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

<script>
   $(document).ready(function() {
    $('body').on('click', '.js-course-add-to-cart-btn', function (e) {
      const $this = $(this);
      $this.addClass('loadingbar primary').prop('disabled', true);

      const $form = $this.closest('form');
      $form.attr('action', '/cart/store');
      $form.trigger('submit');
    });

    $('body').on('click', '.js-product-direct-payment', function (e) {
      const $this = $(this);
      $this.addClass('loadingbar danger').prop('disabled', true);

      const $form = $this.closest('form');
      $form.attr('action', '/products/direct-payment');
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
  <script src="/assets/default/js/parts/webinar_show.min.js"></script>
    <script src="/assets/default/js/parts/time-counter-down.min.js"></script>
    <script src="/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script src="/assets/default/js/parts/comment.min.js"></script>
    <script src="/assets/default/js/parts/profile.min.js"></script>
    <script src="/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/default/js/parts/product_show.min.js"></script>