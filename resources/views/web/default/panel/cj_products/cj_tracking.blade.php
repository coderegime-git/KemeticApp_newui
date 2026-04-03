@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
:root {
    --k-bg: #0b0b0b;
    --k-card: #141414;
    --k-gold: #d4af37;
    --k-border: #262626;
    --k-text: #eaeaea;
    --k-muted: #9ca3af;
}

.kemetic-tracking {
    background: var(--k-bg);
    color: var(--k-text);
    min-height: 100vh;
    padding: 20px;
}

.k-card {
    background: var(--k-card);
    border: 1px solid var(--k-border);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
}

.k-gold-text {
    color: var(--k-gold);
}

.tracking-item {
    border-bottom: 1px solid var(--k-border);
    padding-bottom: 20px;
    margin-bottom: 20px;
}

.tracking-item:last-child {
    border-bottom: none;
}

.product-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-shipped { background: rgba(212,175,55,0.1); color: var(--k-gold); }
.status-delivered { background: rgba(16,185,129,0.1); color: #10b981; }
.status-pending { background: rgba(156,163,175,0.1); color: var(--k-muted); }

.tracking-timeline {
    position: relative;
    padding-left: 20px;
    margin-top: 15px;
}

.tracking-timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 5px;
    bottom: 5px;
    width: 2px;
    background: var(--k-border);
}

.timeline-event {
    position: relative;
    margin-bottom: 15px;
}

.timeline-event::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 6px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--k-gold);
    box-shadow: 0 0 10px rgba(212,175,55,0.4);
}
</style>
@endpush

@section('content')
<div class="kemetic-tracking">
    <div class="d-flex align-items-center justify-content-between mb-30">
        <h1 class="font-24 font-weight-bold text-white">Order Tracking #{{ $order->id }}</h1>
        <a href="/panel/store/orders" class="btn btn-sm btn-outline-secondary">Back to Orders</a>
    </div>

    @if(empty($trackingResults))
        <div class="k-card text-center py-50">
            <img src="/assets/default/img/no-result/cart.png" width="150" alt="no-result">
            <h3 class="font-20 mt-20 text-white">No CJ Products Found</h3>
            <p class="text-gray mt-10">This order does not contain any CJ Dropshipping items or they are not yet processed.</p>
        </div>
    @else
        @foreach($trackingResults as $result)
            <div class="k-card">
                <div class="row align-items-start">
                    <div class="col-auto">
                        <img src="{{ $result['product_image'] }}" class="product-img" alt="Product">
                    </div>
                    <div class="col">
                        <h4 class="font-18 font-weight-bold text-white">{{ $result['product_name'] }}</h4>
                        <div class="d-flex align-items-center mt-10">
                            <span class="text-gray mr-20">Quantity: {{ $result['quantity'] }}</span>
                            <span class="status-badge status-{{ $result['cj_status'] == 'shipped' ? 'shipped' : ($result['cj_status'] == 'delivered' ? 'delivered' : 'pending') }}">
                                {{ strtoupper($result['cj_status']) }}
                            </span>
                        </div>
                        
                        <div class="mt-15">
                            <span class="text-gray d-block font-12 font-weight-bold mb-5">TRACKING NUMBER</span>
                            @if($result['tracking_number'])
                                <span class="font-16 text-white font-weight-bold">{{ $result['tracking_number'] }}</span>
                                <a href="https://www.17track.net/en/track?nums={{ $result['tracking_number'] }}" target="_blank" class="ml-10 text-gold font-14"><i data-feather="external-link" width="16"></i> Track Externally</a>
                            @else
                                <span class="text-muted italic">Waiting for tracking number...</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(!empty($result['tracking']) && !empty($result['tracking']['list']))
                    <div class="mt-30 border-top pt-20">
                        <h5 class="font-16 text-white mb-20">Shipment Timeline</h5>
                        <div class="tracking-timeline">
                            @foreach($result['tracking']['list'] as $event)
                                <div class="timeline-event">
                                    <div class="d-flex flex-column flex-md-row">
                                        <div class="text-gold font-12 font-weight-bold mr-20" style="min-width: 130px;">{{ $event['time'] }}</div>
                                        <div class="text-white font-14">{{ $event['context'] }}</div>
                                    </div>
                                    <div class="text-gray font-12 mt-2">{{ $event['location'] ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($result['tracking_number'])
                    <div class="mt-30 border-top pt-20 text-center">
                        <p class="text-gray italic">No tracking events found yet. This is normal for new shipments. Please check back in 24-48 hours.</p>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection

@push('scripts_bottom')
<script>
    feather.replace();
</script>
@endpush