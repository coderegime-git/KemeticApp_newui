@extends('web.default.layouts.app')

@section('content')
  <div class="shopdetail-wrap">
    <div class="shopdetail-top">
      <div class="shopdetail-member">
        <div class="shopdetail-chip">📦 CJ Dropshipping Product</div>
      </div>
    </div>

    <div class="shopdetail-grid">
      <!-- left: media + info -->
      <div class="shopdetail-panel">
        <div class="shopdetail-media">
          <div class="shopdetail-hero">
             <img src="{{ $product['productImage'] ?? '' }}" alt="{{ $product['productNameEn'] ?? '' }}" class="main-s-image img-cover rounded-lg" loading="lazy">
          </div>
          
          <div style="padding:12px 2px 0">
            <h1> {{ $product['productNameEn'] ?? '' }}</h1>
            
            <div class="shopdetail-stars">
                @for($i = 0; $i < 5; $i++)
                    ★
                @endfor
              <div class="shopdetail-muted" style="margin-left:8px;font-weight:800">5.0</div>
            </div>

            <div class="shopdetail-about">
              <strong>About this item</strong><br>
              {!! $product['description'] ?? 'No description available.' !!}
            </div>

            <div class="shopdetail-vendor">
              <img src="{{ $product['productImage'] ?? '' }}" alt="" style="width:50px; border-radius:50%">
              <div>CJ Dropshipping &nbsp;•&nbsp; <span class="shopdetail-muted">Global Sourcing</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- right: purchase card -->
      <aside class="shopdetail-purchase">
        <div class="shopdetail-muted" style="font-weight:800">Price</div>
        
        <div class="shopdetail-price">
            <span class="real">${{ $product['sellPrice'] ?? '0.00' }}</span>
        </div>

        <div class="text-warning d-block font-14 font-weight-500 mt-5">International Shipping Calculated at Checkout</div>

        <form action="{{ route('dropship.products.import_cart', $product['pid']) }}" method="post" id="cjAddToCartForm">
            @csrf
            <div class="mt-20">
                <label class="font-14 font-weight-500 text-dark-blue">Quantity</label>
                <input type="number" name="quantity" value="1" min="1" class="form-control mt-5" style="width: 80px">
            </div>

            <button type="submit" class="shopdetail-btn-buy mt-20">Buy Now</button>
            <button type="submit" class="shopdetail-btn-ghost mt-10">Add to Cart</button>
        </form>

        <div style="margin-top:16px;border-top:1px solid var(--edge);padding-top:12px">
          <div class="shopdetail-muted" style="font-weight:800;margin-bottom:8px">Includes</div>
          <ul style="margin:0 0 0 18px;line-height:1.7">
            <li>Sourced from CJ Dropshipping</li>
            <li>Global Quality Control</li>
            <li>Tracking provided after shipment</li>
          </ul>
        </div>
      </aside>
    </div>
  </div>

  <!-- Sticky bottom buy bar -->
  <div class="shopdetail-buybar">
    <div class="shopdetail-title"> {{ $product['productNameEn'] ?? '' }}</div>
    <div class="shopdetail-spacer"></div>
    <div style="font-weight:900;margin-right:10px">${{ $product['sellPrice'] ?? '0.00' }}</div>
    <button type="button" class="shopdetail-btn" onclick="document.getElementById('cjAddToCartForm').submit()">Add to Cart</button>
  </div>
@endsection
