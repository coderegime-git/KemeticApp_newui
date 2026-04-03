@extends('web.default.layouts.app')

@section('content')
@php
  // Helpers
  $images = $product['productImageSet'] ?? [];
  if (empty($images) && !empty($product['bigImage'])) $images[] = $product['bigImage'];
  $mainImage  = $images[0] ?? '';
  $variants   = $product['variants'] ?? [];
  $firstVid   = $variants[0]['vid'] ?? null;

  // Parse variant keys for swatch/select display
  $variantKeys = [];
  foreach ($variants as $v) {
    if (!empty($v['variantKey'])) {
      $parts = explode('-', $v['variantKey']);
      foreach ($parts as $i => $part) {
        $variantKeys[$i][] = trim($part);
      }
    }
  }
  foreach ($variantKeys as &$arr) { $arr = array_values(array_unique($arr)); }
  unset($arr);

  // Inventory by warehouse
  $warehouses = $inventoryData['inventories'] ?? [];
@endphp

<div class="shopdetail-wrap">

  {{-- ── Top bar ─────────────────────────────────────────────── --}}
  <div class="shopdetail-top">
    <div class="shopdetail-member">
      <div class="shopdetail-chip">€1/mo • €10/yr • €33 lifetime</div>
      @if(auth()->check())
        <button class="shopdetail-cta"><a href="/membership">Upgrade</a></button>
      @else
        <button class="shopdetail-cta"><a href="/login">Upgrade</a></button>
      @endif
    </div>
  </div>

  <div class="shopdetail-grid">

    {{-- ──────────────────── LEFT PANEL ──────────────────────────── --}}
    <div class="shopdetail-panel">
      <div class="shopdetail-media">

        {{-- Hero image --}}
        <div class="shopdetail-hero">
          <img id="cjMainImage" src="{{ $mainImage }}" alt="{{ $product['productNameEn'] ?? '' }}"
               class="main-s-image img-cover rounded-lg" loading="lazy">
        </div>

        {{-- Thumbnails --}}
        @if(count($images) > 1)
        <div class="shopdetail-thumbs">
          @foreach($images as $img)
          <div class="shopdetail-thumb cj-thumb" data-src="{{ $img }}" style="cursor:pointer">
            <img src="{{ $img }}" alt="" loading="lazy">
          </div>
          @endforeach
        </div>
        @endif

        <div style="padding:12px 2px 0">

          <h1>{{ $product['productNameEn'] ?? 'Product' }}</h1>

          {{-- Rating stars --}}
          <div class="shopdetail-stars">
            @php
              $filled = min(5, round($avgRating));
              $empty  = 5 - $filled;
            @endphp
            @for($i=0;$i<$filled;$i++) ★ @endfor
            @for($i=0;$i<$empty;$i++) ☆ @endfor
            <div class="shopdetail-muted" style="margin-left:8px;font-weight:800">
              {{ $avgRating }} <span style="opacity:.5">({{ $reviewTotal }} reviews)</span>
            </div>
          </div>

          {{-- About / Description --}}
          @if(!empty($product['description']))
          <div class="shopdetail-about">
            <strong>About this item</strong><br>
            {!! nl2br(e(Str::limit($product['description'], 600, '…'))) !!}
          </div>
          @endif

          {{-- Meta details table --}}
          <div class="cj-meta-table">
            @if(!empty($product['productSku']))
            <div class="cj-meta-row"><span>SKU</span><span>{{ $product['productSku'] }}</span></div>
            @endif
            @if(!empty($product['categoryName']))
            <div class="cj-meta-row"><span>Category</span><span>{{ $product['categoryName'] }}</span></div>
            @endif
            @if(!empty($product['productWeight']) && is_numeric($product['productWeight']))
              <div class="cj-meta-row">
                  <span>Weight</span>
                  <span>{{ number_format($product['productWeight'] / 1000, 2) }} kg</span>
              </div>
            @endif
            @if(!empty($product['productType']))
            <div class="cj-meta-row"><span>Type</span><span>{{ str_replace('_', ' ', $product['productType']) }}</span></div>
            @endif
            @if(!empty($product['listedNum']))
            <div class="cj-meta-row"><span>Listed</span><span>{{ number_format($product['listedNum']) }}×</span></div>
            @endif
            @if(!empty($product['supplierName']))
            <div class="cj-meta-row"><span>Supplier</span><span>{{ $product['supplierName'] }}</span></div>
            @endif
          </div>

          {{-- Warehouse inventory badges --}}
          @if(!empty($warehouses))
          <div class="cj-warehouse-row">
            @foreach($warehouses as $wh)
            <div class="cj-wh-badge">
              <span>🏭 {{ $wh['areaEn'] ?? $wh['countryCode'] }}</span>
              <strong>{{ number_format($wh['totalInventoryNum'] ?? 0) }}</strong> in stock
            </div>
            @endforeach
          </div>
          @endif

          {{-- Source badge --}}
          <div class="shopdetail-vendor">
            <img src="https://cjdropshipping.com/favicon.ico" style="width:28px;height:28px;border-radius:50%" alt="CJ">
            <div style="margin-left:8px">
              <strong>CJDropshipping</strong>
              <span class="shopdetail-muted"> • Verified Dropship Supplier</span>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- ──────────────────── RIGHT PANEL ──────────────────────────── --}}
    <aside class="shopdetail-purchase">

      <div class="shopdetail-muted" style="font-weight:800">Price (USD)</div>

      {{-- Price display (updates on variant select) --}}
      <div class="shopdetail-price">
        <span class="real" id="cjDisplayPrice">
          ${{ number_format((float)($variants[0]['variantSellPrice'] ?? $product['sellPrice'] ?? 0), 2) }}
        </span>
        @if(!empty($product['suggestSellPrice']))
        <span style="font-size:12px;opacity:.5;margin-left:8px">Suggested retail: ${{ $product['suggestSellPrice'] }}</span>
        @endif
      </div>

      {{-- ── Variant Selectors ── --}}
      @if(!empty($variants))
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
          {{-- Simple variant select --}}
          <div class="shopdetail-muted" style="font-size:12px;font-weight:700;margin-bottom:6px">Variant</div>
          <select id="cjVariantSelect" class="cj-select" style="width:100%">
            @foreach($variants as $v)
            <option value="{{ $v['vid'] }}" data-price="{{ $v['variantSellPrice'] ?? 0 }}"
                    data-sku="{{ $v['variantSku'] ?? '' }}">
              {{ $v['variantNameEn'] ?? $v['variantKey'] ?? 'Variant' }}
              — ${{ number_format((float)($v['variantSellPrice'] ?? 0), 2) }}
            </option>
            @endforeach
          </select>
        @endif
        <div id="cjSelectedVid" style="display:none">{{ $firstVid }}</div>
        <div id="cjSelectedSku" style="font-size:11px;opacity:.45;margin-top:4px">
          SKU: {{ $variants[0]['variantSku'] ?? '' }}
        </div>
      </div>
      @endif

      {{-- Quantity --}}
      <div style="display:flex;align-items:center;gap:8px;margin:14px 0">
        <span class="shopdetail-muted" style="font-weight:700;font-size:12px">Qty</span>
        <div class="cj-qty-wrap">
          <button type="button" class="cj-qty-btn" id="cjQtyMinus">−</button>
          <input type="number" id="cjQtyInput" value="1" min="1" class="cj-qty-field">
          <button type="button" class="cj-qty-btn" id="cjQtyPlus">+</button>
        </div>
      </div>

      {{-- Hidden form — submitted by Add to Cart and Buy Now --}}
      <form id="cjCartForm" action="/cart/store" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="item_name"   value="cj_product_id">
        <input type="hidden" name="item_id"     id="cjFormPid"    value="{{ $product['pid'] ?? '' }}">
        <input type="hidden" name="cj_vid"      id="cjFormVid"    value="{{ $variants[0]['vid'] ?? '' }}">
        <input type="hidden" name="cj_sku"      id="cjFormSku"    value="{{ $variants[0]['variantSku'] ?? '' }}">
        <input type="hidden" name="cj_name"     value="{{ e($product['productNameEn'] ?? 'CJ Product') }}">
        <input type="hidden" name="cj_price"    id="cjFormPrice"  value="{{ $variants[0]['variantSellPrice'] ?? $product['sellPrice'] ?? 0 }}">
        <input type="hidden" name="cj_image"    value="{{ $product['bigImage'] ?? ($images[0] ?? '') }}">
        <input type="hidden" name="cj_logistic" value="PostNL">
        <input type="hidden" name="quantity"    id="cjFormQty"    value="1">
        <input type="hidden" name="buy_now"        id="cjFormBuyNow"      value="0">
        <input type="hidden" name="redirect_after" id="cjFormRedirect"    value="">
      </form>

      {{-- Action buttons --}}
      <button type="button" class="shopdetail-btn-buy" id="cjBuyNow">
        <span id="cjBuyNowText">Buy Now</span>
      </button>
      <button type="button" class="shopdetail-btn-ghost" id="cjAddToCart">
        <span id="cjAddToCartText">Add to Cart</span>
      </button>

      {{-- Includes --}}
      <div style="margin-top:16px;border-top:1px solid var(--edge);padding-top:12px">
        <div class="shopdetail-muted" style="font-weight:800;margin-bottom:8px">Includes</div>
        <ul style="margin:0 0 0 18px;line-height:1.7">
          <li>CJ verified dropship supplier</li>
          <li>Worldwide shipping available</li>
          <li>Inventory tracked in real-time</li>
          @if(!empty($product['isFreeShipping']))<li>✅ Free shipping</li>@endif
        </ul>
      </div>

    </aside>
  </div>{{-- /shopdetail-grid --}}

  {{-- ── Sticky Buy Bar ─────────────────────────────────────── --}}
  <div class="shopdetail-buybar">
    <div class="shopdetail-title">{{ Str::limit($product['productNameEn'] ?? 'Product', 30, '..') }}</div>
    <div class="shopdetail-spacer"></div>
    <div style="font-weight:900;margin-right:10px" id="cjBuyBarPrice">
      ${{ number_format((float)($variants[0]['variantSellPrice'] ?? $product['sellPrice'] ?? 0), 2) }}
    </div>
    <button type="button" class="shopdetail-btn" id="cjBuyNowBar">
      <span id="cjBuyNowBarText">Buy Now</span>
    </button>
  </div>

  {{-- ── Reviews Section ─────────────────────────────────────── --}}
  @if(!empty($reviews))
  <div class="cj-reviews-wrap" style="margin-top:40px;padding:0 0 40px">
    <h2 style="margin-bottom:20px">Customer Reviews
      <span style="font-size:14px;font-weight:400;opacity:.5">({{ $reviewTotal }})</span>
    </h2>

    {{-- Avg rating bar --}}
    <div class="cj-avg-rating" style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
      <div style="font-size:48px;font-weight:900;line-height:1">{{ $avgRating }}</div>
      <div>
        <div style="font-size:20px;letter-spacing:3px">
          @for($i=0;$i<min(5,round($avgRating));$i++) ★ @endfor
          @for($i=0;$i<(5-min(5,round($avgRating)));$i++) ☆ @endfor
        </div>
        <div style="font-size:12px;opacity:.5">out of 5 stars</div>
      </div>
    </div>

    <div class="cj-reviews-grid">
      @foreach($reviews as $review)
      <div class="cj-review-card">
        <div class="cj-review-header">
          <div class="cj-review-user">
            @if(!empty($review['flagIconUrl']))
            <img src="{{ $review['flagIconUrl'] }}" style="width:20px;height:14px;object-fit:cover;border-radius:2px" alt="">
            @endif
            <strong>{{ $review['commentUser'] ?? 'User' }}</strong>
          </div>
          <div class="cj-review-stars">
            @for($i=0;$i<($review['score']??5);$i++) ★ @endfor
            @for($i=0;$i<(5-($review['score']??5));$i++) ☆ @endfor
          </div>
        </div>
        <div class="cj-review-body">{{ $review['comment'] ?? '' }}</div>
        @if(!empty($review['commentUrls']))
        <div class="cj-review-images">
          @foreach($review['commentUrls'] as $imgUrl)
          <img src="{{ $imgUrl }}" alt="review image" loading="lazy">
          @endforeach
        </div>
        @endif
        <div class="cj-review-date" style="font-size:11px;opacity:.4;margin-top:8px">
          {{ \Carbon\Carbon::parse($review['commentDate'])->format('M j, Y') }}
        </div>
      </div>
      @endforeach
    </div>

    {{-- Load more reviews via AJAX --}}
    @if($reviewTotal > 10)
    <div style="text-align:center;margin-top:20px">
      <button type="button" class="shopdetail-btn-ghost" id="cjLoadMoreReviews"
              data-pid="{{ $product['pid'] ?? '' }}" data-page="2">
        Load More Reviews
      </button>
    </div>
    @endif
  </div>
  @endif

</div>{{-- /shopdetail-wrap --}}

{{-- ──────────────── STYLES ──────────────────────────────────────── --}}
<style>
.cj-meta-table { margin: 14px 0; border-radius: 10px; overflow: hidden; border: 1px solid var(--edge,#333); }
.cj-meta-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 8px 14px; font-size: 13px;
  border-bottom: 1px solid var(--edge,#2a2a2a);
}
.cj-meta-row:last-child { border-bottom: none; }
.cj-meta-row span:first-child { opacity: .55; min-width: 80px; }
.cj-meta-row span:last-child  { font-weight: 600; text-align: right; }

.cj-warehouse-row { display: flex; flex-wrap: wrap; gap: 8px; margin: 14px 0; }
.cj-wh-badge {
  display: flex; align-items: center; gap: 6px;
  background: var(--panel,#1a1a1a); border: 1px solid var(--edge,#333);
  border-radius: 8px; padding: 6px 12px; font-size: 12px;
}
.cj-wh-badge strong { color: var(--gold,#FFD700); }

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

.cj-qty-wrap { display: flex; align-items: center; border: 1px solid var(--edge,#333); border-radius: 8px; overflow: hidden; }
.cj-qty-btn  { width: 32px; height: 32px; border: none; background: transparent; color: inherit; font-size: 18px; cursor: pointer; }
.cj-qty-btn:hover { background: var(--edge,#222); }
.cj-qty-field { width: 48px; text-align: center; background: transparent; border: none; color: inherit; font-size: 14px; font-weight: 700; }
input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; }

.cj-reviews-grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
.cj-review-card {
  background: var(--panel,#1a1a1a); border: 1px solid var(--edge,#2a2a2a);
  border-radius: 12px; padding: 16px;
}
.cj-review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.cj-review-user   { display: flex; align-items: center; gap: 8px; font-size: 13px; }
.cj-review-stars  { color: var(--gold,#FFD700); letter-spacing: 2px; font-size: 14px; }
.cj-review-body   { font-size: 13px; line-height: 1.6; opacity: .85; }
.cj-review-images { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
.cj-review-images img { width: 64px; height: 64px; object-fit: cover; border-radius: 6px; }

.cj-select {
  background: var(--panel,#1a1a1a); border: 1px solid var(--edge,#333);
  color: var(--text,#eee); border-radius: 8px; padding: 8px 10px;
  font-size: 13px; cursor: pointer;
}
</style>

{{-- ──────────────── SCRIPTS ──────────────────────────────────────── --}}
<script>
(function () {
  // ─── Thumbnail swap ───────────────────────────────────────────
  document.querySelectorAll('.cj-thumb').forEach(function(thumb) {
    thumb.addEventListener('click', function() {
      document.getElementById('cjMainImage').src = this.dataset.src;
      document.querySelectorAll('.cj-thumb').forEach(t => t.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // ─── Variant data from PHP ────────────────────────────────────
  const variants = @json($variants);

  // ─── Swatch selection ─────────────────────────────────────────
  const selectedOptions = {};

  document.querySelectorAll('.cj-swatch').forEach(function(swatch) {
    swatch.addEventListener('click', function() {
      const idx = this.dataset.keyIndex;
      selectedOptions[idx] = this.dataset.value;

      // Deactivate siblings, activate this
      document.querySelectorAll('.cj-swatch[data-key-index="' + idx + '"]')
        .forEach(s => s.classList.remove('active'));
      this.classList.add('active');

      updateSelectedVariant();
    });
  });

  function updateSelectedVariant() {
    if (!variants.length) return;
    // Find variant whose variantKey matches all selected options
    const selParts = Object.values(selectedOptions);
    const match = variants.find(function(v) {
      if (!v.variantKey) return false;
      const parts = v.variantKey.split('-').map(s => s.trim());
      return selParts.every(function(sp, i) { return parts[i] === sp; });
    }) || variants[0];

    if (match) {
      setVariantDisplay(match);
    }
  }

  // ─── Simple <select> variant ──────────────────────────────────
  const varSelect = document.getElementById('cjVariantSelect');
  if (varSelect) {
    varSelect.addEventListener('change', function() {
      const opt   = this.options[this.selectedIndex];
      const price = parseFloat(opt.dataset.price || 0).toFixed(2);
      document.getElementById('cjDisplayPrice').textContent = '$' + price;
      document.getElementById('cjBuyBarPrice').textContent  = '$' + price;
      document.getElementById('cjSelectedSku').textContent  = 'SKU: ' + (opt.dataset.sku || '');
      document.getElementById('cjSelectedVid').textContent  = this.value;
      // Keep hidden form in sync
      document.getElementById('cjFormVid').value   = this.value;
      document.getElementById('cjFormSku').value   = opt.dataset.sku || '';
      document.getElementById('cjFormPrice').value = price;
    });
  }

  function setVariantDisplay(v) {
    const price = parseFloat(v.variantSellPrice || 0).toFixed(2);
    document.getElementById('cjDisplayPrice').textContent = '$' + price;
    document.getElementById('cjBuyBarPrice').textContent  = '$' + price;
    document.getElementById('cjSelectedSku').textContent  = 'SKU: ' + (v.variantSku || '');
    document.getElementById('cjSelectedVid').textContent  = v.vid || '';
    // Keep hidden form fields in sync
    document.getElementById('cjFormVid').value   = v.vid   || '';
    document.getElementById('cjFormSku').value   = v.variantSku || '';
    document.getElementById('cjFormPrice').value = price;
    // Swap variant image if available
    if (v.variantImage) {
      document.getElementById('cjMainImage').src = v.variantImage;
    }
  }

  // ─── Quantity ─────────────────────────────────────────────────
  const qtyInput = document.getElementById('cjQtyInput');
  document.getElementById('cjQtyMinus').addEventListener('click', function() {
    if (parseInt(qtyInput.value) > 1) qtyInput.value = parseInt(qtyInput.value) - 1;
  });
  document.getElementById('cjQtyPlus').addEventListener('click', function() {
    qtyInput.value = parseInt(qtyInput.value) + 1;
  });

  // ─── Sync form hidden fields from current UI state ──────────
  function syncFormFields() {
    document.getElementById('cjFormVid').value   = document.getElementById('cjSelectedVid').textContent.trim();
    document.getElementById('cjFormSku').value   = (document.getElementById('cjSelectedSku').textContent || '').replace('SKU: ', '').trim();
    document.getElementById('cjFormQty').value   = parseInt(qtyInput.value) || 1;
    // Price — strip the leading "$"
    const priceText = document.getElementById('cjDisplayPrice').textContent.trim().replace('$', '');
    document.getElementById('cjFormPrice').value = priceText || '0';
  }

  // ─── Add to Cart ─────────────────────────────────────────────
  // Normal form submit → Laravel does back() → same page shows toast
  function submitAddToCart(btn, labelId) {
    syncFormFields();
    document.getElementById('cjFormRedirect').value = '';   // no redirect
    document.getElementById('cjFormBuyNow').value   = '0';
    document.getElementById('cjCartForm').action    = '/cart/store';

    if (btn) {
      btn.disabled = true;
      document.getElementById(labelId).textContent = 'Adding…';
    }

    document.getElementById('cjCartForm').submit();
  }

  // ─── Buy Now ─────────────────────────────────────────────────
  // Sets redirect_after=/cart so CartManagerController redirects there
  // after storing — no fetch, no race conditions, fully reliable.
  function submitBuyNow(btn, labelId) {
    syncFormFields();
    document.getElementById('cjFormRedirect').value = '/cart'; // redirect after store
    document.getElementById('cjFormBuyNow').value   = '1';
    document.getElementById('cjCartForm').action    = '/cart/store';

    if (btn) {
      btn.disabled = true;
      document.getElementById(labelId).textContent = 'Processing…';
    }

    document.getElementById('cjCartForm').submit();
  }

  // ─── Wire up buttons ─────────────────────────────────────────
  const buyBtn    = document.getElementById('cjBuyNow');
  const cartBtn   = document.getElementById('cjAddToCart');
  const buyBarBtn = document.getElementById('cjBuyNowBar');

  if (cartBtn)   cartBtn.addEventListener('click',   () => submitAddToCart(cartBtn,   'cjAddToCartText'));
  if (buyBtn)    buyBtn.addEventListener('click',    () => submitBuyNow(buyBtn,        'cjBuyNowText'));
  if (buyBarBtn) buyBarBtn.addEventListener('click', () => submitBuyNow(buyBarBtn,     'cjBuyNowBarText'));

  // ─── Load More Reviews ────────────────────────────────────────
  const loadMoreBtn = document.getElementById('cjLoadMoreReviews');
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function() {
      const pid  = this.dataset.pid;
      const page = parseInt(this.dataset.page);
      this.textContent = 'Loading…';

      fetch('/api/cj/reviews/' + encodeURIComponent(pid) + '?page=' + page + '&size=10')
        .then(r => r.json())
        .then(function(data) {
          const list = data?.data?.list || [];
          const grid = document.querySelector('.cj-reviews-grid');
          if (!grid || !list.length) { loadMoreBtn.remove(); return; }

          list.forEach(function(review) {
            const stars  = '★'.repeat(review.score || 5) + '☆'.repeat(5 - (review.score || 5));
            const images = (review.commentUrls || []).map(function(u) {
              return '<img src="' + u + '" alt="" loading="lazy">';
            }).join('');

            const card = document.createElement('div');
            card.className = 'cj-review-card';
            card.innerHTML = `
              <div class="cj-review-header">
                <div class="cj-review-user"><strong>${review.commentUser || 'User'}</strong></div>
                <div class="cj-review-stars" style="color:var(--gold,#FFD700)">${stars}</div>
              </div>
              <div class="cj-review-body">${review.comment || ''}</div>
              ${images ? '<div class="cj-review-images">' + images + '</div>' : ''}
              <div class="cj-review-date" style="font-size:11px;opacity:.4;margin-top:8px">${review.commentDate || ''}</div>
            `;
            grid.appendChild(card);
          });

          loadMoreBtn.dataset.page = page + 1;
          loadMoreBtn.textContent  = 'Load More Reviews';
          if (list.length < 10) loadMoreBtn.remove();
        })
        .catch(function() {
          loadMoreBtn.textContent = 'Load More Reviews';
        });
    });
  }

})();
</script>
@endsection