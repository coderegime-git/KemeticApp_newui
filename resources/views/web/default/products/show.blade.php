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
        $productType = $product->type ?? 'physical';

       $downloadurl = $product->files->first()->path ?? null;
      @endphp
        <!-- <div class="shopdetail-subnote">Get with €1/mo membership</div> -->
      @if($hasBought or $product->price == 0 or $activeSubscribe and $productType == 'virtual')
        <button type="button" data-product-type="{{ $productType }}" data-product-title="{{ $product->title }}" class="shopdetail-btn-buy" onclick="previewPdf('{{ url($downloadurl) ?? '#' }}')">Download</button>
      @else
        <button type="button" data-product-type="{{ $productType }}" data-product-title="{{ $product->title }}" class="shopdetail-btn-buy js-product-direct-payment" {{ ($productAvailability < 1) || ($product->price == 0) ? 'disabled' : '' }}>Buy Now</button>
        
        <button type="button" data-product-type="{{ $productType }}" data-product-title="{{ $product->title }}" class="shopdetail-btn-ghost btn-add-product-to-carts" {{ ($productAvailability < 1) || ($product->price == 0) ? 'disabled' : '' }}> {{ ($productAvailability > 0) ? trans('public.add_to_cart') : trans('update.out_of_stock') }}</button>
      @endif
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
    <!-- <a href="/cart"> -->
       @if($hasBought or $product->price == 0 or $activeSubscribe and $productType == 'virtual')
        <button type="button" data-product-type="{{ $productType }}" data-product-title="{{ $product->title }}" class="shopdetail-btn" onclick="previewPdf('{{ url($downloadurl) ?? '#' }}')">Download</button>
      @else
      <button type="button" data-product-type="{{ $productType }}" data-product-title="{{ $product->title }}" class="shopdetail-btn js-product-direct-payment-buybar" {{ ($productAvailability < 1) || ($product->price == 0) ? 'disabled' : '' }}>Buy Now</button>
      @endif
    <!-- </a> -->
  </div>
  </form>

  <div id="productConfirmationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--panel, #1a1a1a); padding: 30px; border-radius: 16px; max-width: 500px; width: 90%; border: 2px solid var(--chakra-gold, #FFD700);">
      <div style="text-align: center; margin-bottom: 25px;">
        <div style="font-size: 48px; margin-bottom: 15px;">📦</div>
        <h2 style="color: var(--chakra-gold, #FFD700); margin-bottom: 10px;">Virtual Product</h2>
        <p id="productConfirmationMessage" style="color: #ccc; line-height: 1.6; margin-bottom: 25px;">
          This is a virtual product. After purchase, you can download it immediately.
        </p>
      </div>
      
      <div style="display: flex; gap: 15px; justify-content: center;">
        <button id="productConfirmCancel" 
                style="padding: 12px 30px; background: transparent; border: 2px solid #666; color: #ccc; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
          Cancel
        </button>
        <button id="productConfirmProceed" 
                style="padding: 12px 30px; background: var(--chakra-gold, #FFD700); border: none; color: #000; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
          Continue
        </button>
      </div>
    </div>
  </div>
  @endsection
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Fallback to CDN if local files don't work -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

<script>
   $(document).ready(function() {

    let currentAction = null; // 'buy-now' or 'add-to-cart'
    let currentButton = null;

    function isVirtualProduct(productType) {
        const virtualTypes = ['virtual', 'digital', 'downloadable', 'e-book', 'pdf'];
        return virtualTypes.includes((productType || '').toLowerCase());
    }
    
    // Show confirmation modal
    function showConfirmationModal(productTitle, productType, actionType) {
        const isVirtual = isVirtualProduct(productType);
        
        if (isVirtual) {
            const message = `"${productTitle}" is a virtual product. After purchase, you can download it immediately.`;
            const actionText = actionType === 'buy-now' ? 'Continue Purchase' : 'Continue to Cart';
            
            $('#productConfirmationMessage').text(message);
            $('#productConfirmProceed').text(actionText);
            $('#productConfirmationModal').css('display', 'flex');
        }
        
        return isVirtual; // Return whether modal was shown
    }

    $('body').on('click', '.btn-add-product-to-carts', function (e) {

      e.preventDefault();

      const $this = $(this);
      const productType = $this.data('product-type');
      const productTitle = $this.data('product-title');
      
      currentAction = 'add-to-cart';
      currentButton = $this;
      
      // Check if virtual product and show modal
      const isVirtual = showConfirmationModal(productTitle, productType, currentAction);
      
      if (!isVirtual)
      {
        proceedWithAddToCart();
      }
      // const $this = $(this);
      // $this.addClass('loadingbar primary').prop('disabled', true);

      // const $form = $this.closest('form');
      // $form.attr('action', '/cart/store');
      // $form.trigger('submit');
    });

     $('body').on('click', '.js-product-direct-payment, .js-product-direct-payment-buybar', function (e) {
        const $this = $(this);
        const productType = $this.data('product-type');
        const productTitle = $this.data('product-title');
        
        currentAction = 'buy-now';
        currentButton = $this;
        
        // Check if virtual product and show modal
        const isVirtual = showConfirmationModal(productTitle, productType, currentAction);
        
        if (!isVirtual) {
            // Directly proceed for physical products
            proceedWithBuyNow();
        }
    });
    
    // Handle confirm proceed
    $('#productConfirmProceed').click(function() {
        $('#productConfirmationModal').hide();
        
        if (currentAction === 'add-to-cart') {
            proceedWithAddToCart();
        } else if (currentAction === 'buy-now') {
            proceedWithBuyNow();
        }
        
        currentAction = null;
        currentButton = null;
    });
    
    // Handle cancel
    $('#productConfirmCancel').click(function() {
        $('#productConfirmationModal').hide();
        currentAction = null;
        currentButton = null;
    });
    
    // Also close modal when clicking outside
    $('#productConfirmationModal').click(function(e) {
        if (e.target === this) {
            $(this).hide();
            currentAction = null;
            currentButton = null;
        }
    });
    
    // Proceed with Add to Cart
    function proceedWithAddToCart() {
        if (currentButton) {
            currentButton.addClass('loadingbar primary').prop('disabled', true);
            
            // Trigger form submit
            const $form = $('#productAddToCartForm');
            $form.attr('action', '/cart/store');
            $form.trigger('submit');
        }
    }
    
    // Proceed with Buy Now
    function proceedWithBuyNow() {
        if (currentButton) {
            currentButton.addClass('loadingbar danger').prop('disabled', true);
            
            // Trigger form submit for direct payment
            const $form = $('#productAddToCartForm');
            $form.attr('action', '/products/direct-payment');
            $form.trigger('submit');
        }
    }

    // $('body').on('click', '.js-product-direct-payment-buybar', function (e) {
    //   const $this = $(this);
    //   const productType = $this.data('product-type');
    //   const productTitle = $this.data('product-title');
      
    //   if (showConfirmationModal(productTitle, productType, 'buy-now')) {
    //     currentAction = 'buy-now';
    //     currentButton = $this;
    //     return false; // Prevent default action
    //   }

    //   $this.addClass('loadingbar danger').prop('disabled', true);

    //   const $form = $this.closest('form');
    //   $form.attr('action', '/products/direct-payment');
    //   $form.trigger('submit');
    // });
  });

  function previewPdf(pdfUrl) {
      if (pdfUrl === '#') {
          alert('PDF preview not available yet.');
          return;
      }
      // Open PDF in new tab or modal
      window.open(pdfUrl, '_blank');
  }


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
  <!-- <script src="/assets/default/js/parts/webinar_show.min.js"></script>
    <script src="/assets/default/js/parts/time-counter-down.min.js"></script>
    <script src="/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script src="/assets/default/js/parts/comment.min.js"></script>
    <script src="/assets/default/js/parts/profile.min.js"></script>
    <script src="/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/default/js/parts/product_show.min.js"></script> -->