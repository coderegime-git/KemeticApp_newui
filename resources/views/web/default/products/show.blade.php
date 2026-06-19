@extends('web.default.layouts.app')

@section('content')
  <div class="shopdetail-wrap">
    <div class="shopdetail-top">
      <!-- <div class="shopdetail-logo">KA</div> -->
      <!-- <div class="shopdetail-crumbs"><a href="#">Shop</a> • Jewelry • Tiger Eye Necklace</div> -->
      <div class="shopdetail-member">
        <div class="shopdetail-chip">€10/yr • €33 lifetime</div>
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
              <!-- <div class="shopdetail-star" style="background:var(--red);"><span>{{ $product->like_count}}</span></div>
              <div class="shopdetail-star" style="background:var(--orange)">{{ $product->comments_count}}</div>
              <div class="shopdetail-star" style="background:var(--yellow)">{{ $product->gift_count}}</div>
              <div class="shopdetail-star" style="background:var(--green)">{{ $product->saved_count}}</div>
              <div class="shopdetail-star" style="background:var(--blue)">{{ $product->share_count}}</div> -->
              <div class="shopdetail-muted" style="margin-left:8px;font-weight:800">{{$rate}} </div>
            </div>

            <div class="shopdetail-about">
              <strong>About this item</strong><br>
              {!! $product->description !!}
            </div>

            <div class="shopdetail-vendor">
              
              <img src="{{ $product->creator->getAvatar() 
                    ? (Str::startsWith($product->creator->getAvatar(), '/') ? $product->creator->getAvatar() : '/' . $product->creator->getAvatar())
                    : url('/getDefaultAvatar?item=' . ($product->creator->id ?? '') . '&name=' . urlencode($product->creator->full_name) . '&size=36') 
                }}" class="img-cover" alt="{{ $product->creator->full_name }}">
              <div><a href="{{ $product->creator->getProfileUrl() }}" target="_blank" class="user-name ml-5 font-14">{{ $product->creator->full_name }}</a> &nbsp;•&nbsp; <a href="{{ $product->category->getUrl() }}" target="_blank"><span class="shopdetail-muted">{{ $product->category->title }}</span></a></div>
              <!-- <div style="margin-left:auto">❤️ {{ $product->reviews->pluck('creator_id')->count() }}</div> -->
            </div>
          </div>
        </div>
      </div>

      <!-- right: sticky purchase card -->
      <aside class="shopdetail-purchase">
        <div class="shopdetail-muted" style="font-weight:800">Price</div>
        
        <div class="shopdetail-price">
          
          @if(!empty($product->price) and $product->price > 0)
            @if($product->getPriceWithActiveDiscountPrice() < $product->price)
                <span class="real" id="mainVariantPrice">{{ handlePrice($product->getPriceWithActiveDiscountPrice(), true, true, false, null, true, 'store') }}</span>
                <span class="off ml-10">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
            @else
                <span class="real" id="mainVariantPrice">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
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

      {{-- CJ Variants Section --}}
      @if(isset($product->is_cj_product) && $product->is_cj_product && $product->cjVariants->count() > 0)
      @php
        $cjVariants = $product->cjVariants;
        $firstVid = $cjVariants->first()->vid;
        $variantKeys = [];
        foreach ($cjVariants as $v) {
          if (!empty($v->variant_key)) {
            $parts = explode('-', $v->variant_key);
            foreach ($parts as $i => $part) {
              $variantKeys[$i][] = trim($part);
            }
          }
        }
        foreach ($variantKeys as &$arr) { $arr = array_values(array_unique($arr)); }
        unset($arr);
      @endphp
      <div id="cjVariantSelectors" style="margin:14px 0">
        @if(!empty($variantKeys))
          @foreach($variantKeys as $idx => $options)
          <div style="margin-bottom:10px">
            <div class="shopdetail-muted" style="font-size:12px;font-weight:700;margin-bottom:6px">
              Option {{ $idx + 1 }}
            </div>
            <div class="cj-swatch-row" data-key-index="{{ $idx }}">
              @foreach($options as $opt)
              <button type="button" class="cj-swatch {{ $loop->first ? 'active' : '' }}"
                      data-value="{{ $opt }}" data-key-index="{{ $idx }}">
                {{ $opt }}
              </button>
              @endforeach
            </div>
          </div>
          @endforeach
        @else
          <div class="shopdetail-muted" style="font-size:12px;font-weight:700;margin-bottom:6px">Variant</div>
          <select id="cjVariantSelect" class="cj-select" style="width:100%" name="cj_variant_id">
            @foreach($cjVariants as $v)
            <option value="{{ $v->vid }}" data-price="{{ $v->sell_price }}"
                    data-sku="{{ $v->variant_sku }}" data-image="{{ $v->variant_image }}">
              {{ $v->variant_name }} — ${{ number_format((float)($v->sell_price), 2) }}
            </option>
            @endforeach
          </select>
        @endif
        <input type="hidden" name="cj_variant_id" id="cjSelectedVid" value="{{ $firstVid }}">
      </div>
      
      <style>
      .cj-swatch-row { display: flex; flex-wrap: wrap; gap: 8px; }
      .cj-swatch {
        padding: 5px 12px; border-radius: 8px; font-size: 12px; cursor: pointer;
        border: 1.5px solid var(--edge,#444); background: transparent; color: inherit;
        transition: all .2s;
      }
      .cj-swatch.active, .cj-swatch:hover {
        border-color: var(--gold,#FFD700);
        color: var(--gold,#FFD700);
        background: rgba(255,215,0,.06);
      }
      .cj-select {
        background: var(--panel,#1a1a1a); border: 1px solid var(--edge,#333);
        color: var(--text,#eee); border-radius: 8px; padding: 8px 10px;
        font-size: 13px; cursor: pointer;
      }
      /* Constrain main image and description images */
      .shopdetail-hero {
          max-height: 500px;
          display: flex;
          align-items: center;
          justify-content: center;
          background: #000;
      }
      .shopdetail-hero img {
          max-height: 500px;
          width: auto !important;
          object-fit: contain !important;
      }
      .shopdetail-about img {
          max-width: 100% !important;
          height: auto !important;
          border-radius: 8px;
          margin: 10px 0;
      }
      .shopdetail-thumb.active {
          border-color: var(--gold, #FFD700) !important;
          opacity: 1;
      }
      </style>
      
      <script>
      document.addEventListener('DOMContentLoaded', function() {
          const variants = @json($cjVariants);
          const variantKeysLength = {{ count($variantKeys) }};
          const selectedOptions = {};
          const currencySymbol = "{{ $currency ?? '$' }}";

          document.querySelectorAll('.cj-swatch').forEach(function(swatch) {
              if (swatch.classList.contains('active')) {
                  selectedOptions[swatch.dataset.keyIndex] = swatch.dataset.value;
              }
              
              swatch.addEventListener('click', function(e) {
                  e.preventDefault();
                  const idx = this.dataset.keyIndex;
                  selectedOptions[idx] = this.dataset.value;

                  document.querySelectorAll('.cj-swatch[data-key-index="' + idx + '"]')
                      .forEach(s => s.classList.remove('active'));
                  this.classList.add('active');

                  updateSelectedVariant();
              });
          });

          // Run once on load
          updateSelectedVariant();

          function updateSelectedVariant() {
              if (!variants.length) return;
              
              // Build search key from selected options in order
              let match = null;
              if (variantKeysLength > 0) {
                  const selParts = [];
                  for (let i = 0; i < variantKeysLength; i++) {
                      selParts.push(selectedOptions[i] || '');
                  }
                  
                  match = variants.find(function(v) {
                      if (!v.variant_key) return false;
                      const parts = v.variant_key.split('-').map(s => s.replace(/\s+/g, '').toLowerCase());
                      return selParts.every(function(sp, i) { 
                          const normalizedSp = (sp || '').replace(/\s+/g, '').toLowerCase();
                          return parts[i] === normalizedSp; 
                      });
                  });
              }

              if (!match && variants.length > 0) match = variants[0];

              if (match) {
                  const hiddenInput = document.getElementById('cjSelectedVid');
                  if (hiddenInput) hiddenInput.value = match.vid;
                  
                  const priceFormatted = currencySymbol + parseFloat(match.sell_price).toFixed(2);
                  
                  // Update Main Price
                  const mainPrice = document.getElementById('mainVariantPrice');
                  if (mainPrice) mainPrice.textContent = priceFormatted;
                  
                  // Update Buy Bar Price
                  const barPrice = document.getElementById('barVariantPrice');
                  if (barPrice) barPrice.textContent = priceFormatted;

                  // Update Image
                  const mainImageNode = document.querySelector('.main-s-image');
                  if (mainImageNode && match.variant_image) {
                      mainImageNode.src = match.variant_image;
                  }
              }
          }

          const varSelect = document.getElementById('cjVariantSelect');
          if (varSelect) {
              varSelect.addEventListener('change', function() {
                  const opt = this.options[this.selectedIndex];
                  const price = parseFloat(opt.dataset.price || 0).toFixed(2);
                  const priceFormatted = currencySymbol + price;
                  
                  const mainPrice = document.getElementById('mainVariantPrice');
                  if (mainPrice) mainPrice.textContent = priceFormatted;
                  
                  const barPrice = document.getElementById('barVariantPrice');
                  if (barPrice) barPrice.textContent = priceFormatted;

                  const vImg = opt.dataset.image;
                  const mainImageNode = document.querySelector('.main-s-image');
                  if (mainImageNode && vImg) {
                      mainImageNode.src = vImg;
                  }
              });
              varSelect.dispatchEvent(new Event('change'));
          }
      });
      </script>
      @endif

      @php
        $productAvailability = $product->getAvailability();
        $productType = $product->type ?? 'physical';

       $downloadurl = $product->files->first()->path ?? null;
      @endphp
        <!-- <div class="shopdetail-subnote">Get with €1/mo membership</div> -->
      @if($hasBought && $product->price == 0 && $activeSubscribe && $productType == 'virtual')
        <button type="button" data-product-type="{{ $productType }}" data-product-title="{{ $product->title }}" class="shopdetail-btn-buy" onclick="previewPdf('{{ url($downloadurl) ?? '#' }}')">Download</button>
      @else
        <button type="button" data-product-type="{{ $productType }}" data-product-title="{{ $product->title }}" class="shopdetail-btn-buy js-product-direct-payment" {{ ($productAvailability < 1) || ($product->price == 0) ? 'disabled' : '' }}>Buy Now</button>
        
        <button type="button" data-product-type="{{ $productType }}" data-product-title="{{ $product->title }}" class="shopdetail-btn-ghost btn-add-product-to-carts" {{ ($productAvailability < 1) || ($product->price == 0) ? 'disabled' : '' }}> {{ ($productAvailability > 0) ? trans('public.add_to_cart') : trans('update.out_of_stock') }}</button>
      @endif
        <div style="margin-top:16px;border-top:1px solid var(--edge);padding-top:12px">
          </ul>
        </div>

        {{-- Variant Price List --}}
        @if($product->cjVariants->count() > 0)
        <div style="margin-top:20px; border-top:1px solid var(--edge); padding-top:12px">
            <div class="shopdetail-muted" style="font-weight:800; margin-bottom:8px">Variant Prices</div>
            <div style="max-height: 150px; overflow-y: auto; background: rgba(255,255,255,0.03); border-radius: 8px; border: 1px solid var(--edge);">
                <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                    @foreach($product->cjVariants as $variant)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 6px 10px; opacity: 0.8;">{{ $variant->variant_name }}</td>
                            <td style="padding: 6px 10px; text-align: right; font-weight: bold; color: var(--gold, #F2C94C);">
                                {{ handlePrice($variant->sell_price, true, true, false, null, true, 'store') }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif
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
                <span id="barVariantPrice">{{ handlePrice($product->getPriceWithActiveDiscountPrice(), true, true, false, null, true, 'store') }}</span>
                <span style="text-decoration: line-through; opacity: 0.6; font-size: 0.8em; margin-left: 5px;">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
            @else
                <span id="barVariantPrice">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
            @endif
        @else
            <span>{{ trans('public.free') }}</span>
        @endif

        @if($product->isPhysical() && $product->is_cj_product == '0')
          @if(!empty($product->delivery_fee) and $product->delivery_fee > 0)
              <span>+ {{ handlePrice($product->delivery_fee) }} {{ trans('update.shipping') }}</span>
          @else
              <span>{{ trans('update.free_shipping') }}</span>
          @endif
        @endif</div>
    <!-- <a href="/cart"> -->
      @if($hasBought && $product->price == 0 && $activeSubscribe && $productType == 'virtual')
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

    // Manual thumbnail click
    $('body').on('click', '.shopdetail-thumb img', function() {
        const src = $(this).attr('src');
        $('.main-s-image').attr('src', src);
        $('.shopdetail-thumb').removeClass('active');
        $(this).parent().addClass('active');
    });

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