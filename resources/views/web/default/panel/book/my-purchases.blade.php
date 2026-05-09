@extends('web.default.layouts.newapp')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

    <style>
        :root {
            --k-bg: #0b0b0b;
            --k-card: #141414;
            --k-gold: #d4af37;
            --k-gold-soft: rgba(212,175,55,.25);
            --k-border: rgba(212,175,55,.15);
            --k-text: #f5f5f5;
            --k-muted: #9a9a9a;
            --k-radius: 18px;
            --k-shadow: 0 12px 40px rgba(0,0,0,.65);
        }

        .kemetic-page {
            background: radial-gradient(circle at top, #1a1a1a, #000);
            min-height: 100vh;
            padding: 25px;
            color: var(--k-text);
        }

        .section-title {
            color: var(--k-gold);
            font-weight: 700;
            letter-spacing: .6px;
            position: relative;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .section-title::after {
            content: "";
            display: block;
            width: 70px;
            height: 1px;
            margin-top: 6px;
            background: linear-gradient(to right, var(--k-gold), transparent);
        }

        .activities-container {
            background: linear-gradient(145deg, #161616, #0c0c0c);
            border-radius: var(--k-radius);
            border: 1px solid var(--k-border);
            box-shadow: var(--k-shadow);
            padding: 20px;
        }

        .text-dark-blue {
            color: var(--k-gold) !important;
            font-weight: 700;
            font-size: 30px;
        }

        .text-gray {
            color: var(--k-muted) !important;
            font-size: 16px;
        }

        .panel-section-card {
            background: linear-gradient(180deg, #121212, #0a0a0a);
            border: 1px solid var(--k-border);
            border-radius: var(--k-radius);
            padding: 25px;
            box-shadow: var(--k-shadow);
        }

        .form-control, .select2-container--default .select2-selection--single {
            background: #1a1a1a !important;
            color: var(--k-text) !important;
            border: 1px solid var(--k-border) !important;
            border-radius: 12px !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d4af37, #b8962e) !important;
            color: #000 !important;
            font-weight: 700;
            border-radius: 12px;
            border: none;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table thead th {
            color: var(--k-gold);
            font-size: 13px;
            padding: 15px;
        }

        .custom-table tbody tr {
            background: #0f0f0f;
            border: 1px solid var(--k-border);
            transition: all 0.3s ease;
        }

        .custom-table tbody tr:hover {
            background: #151515;
        }

        .custom-table tbody td {
            padding: 16px;
            color: var(--k-text);
            vertical-align: middle;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-waiting_delivery { background: rgba(241,196,15,0.15); color: #f1c40f; }
        .status-shipped { background: rgba(241,196,15,0.15); color: #f1c40f; }
        .status-success { background: rgba(46,204,113,0.15); color: #2ecc71; }
        .status-canceled { background: rgba(231,76,60,0.15); color: #e74c3c; }

        .btn-track {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: rgba(212,175,55,0.1);
            border: 1px solid var(--k-gold);
            color: var(--k-gold);
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-track:hover {
            background: var(--k-gold);
            color: #000;
        }

        /* Tracking Modal */
        .track-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9000;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }

        .track-overlay.open { display: flex; }

        .track-modal {
            background: #111;
            border: 1px solid var(--k-border);
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            box-shadow: var(--k-shadow);
        }

        .track-modal-head {
            padding: 15px 20px;
            border-bottom: 1px solid var(--k-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .track-modal-body {
            padding: 20px;
        }

        .track-info-item {
            margin-bottom: 15px;
        }

        .track-info-label {
            font-size: 11px;
            color: var(--k-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .track-info-value {
            font-size: 14px;
            color: #fff;
            font-weight: 600;
        }

        .tracking-link {
            color: var(--k-gold);
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="kemetic-page">
        <section>
            <h2 class="section-title">Scroll Purchases Statistics</h2>
            <div class="activities-container mt-25">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-4">
                        <strong class="text-dark-blue mt-2">{{ $totalOrders }}</strong>
                        <span class="d-block text-gray">Total Orders</span>
                    </div>
                    <div class="col-6 col-md-3 mb-4">
                        <strong class="text-dark-blue mt-2">{{ $pendingOrders }}</strong>
                        <span class="d-block text-gray">Pending Orders</span>
                    </div>
                    <div class="col-6 col-md-3 mb-4">
                        <strong class="text-dark-blue mt-2">{{ $canceledOrders }}</strong>
                        <span class="d-block text-gray">Canceled Orders</span>
                    </div>
                    <div class="col-6 col-md-3 mb-4">
                        <strong class="text-dark-blue mt-2">{{ handlePrice($totalPurchase) }}</strong>
                        <span class="d-block text-gray">Total Spent</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-35">
            <h2 class="section-title">Purchase History</h2>
            <div class="panel-section-card mt-20">
                <div class="table-responsive">
                    <table class="custom-table text-center">
                        <thead>
                            <tr>
                                <th class="text-left">Scroll</th>
                                <th>Order ID</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="text-left">
                                        <div class="d-flex align-items-center">
                                            @if($order->book)
                                                <img src="{{ $order->book->image_cover }}" width="40" height="55" style="border-radius: 4px; object-fit: cover;">
                                                <div class="ml-10">
                                                    <span class="font-weight-600 d-block text-white">{{ $order->book->title }}</span>
                                                    <span class="font-12 text-gray">By {{ $order->seller->full_name ?? 'Unknown' }}</span>
                                                </div>
                                            @else
                                                <div class="ml-10">
                                                    <span class="font-weight-600 d-block text-white">Deleted Book</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td><span class="text-white">#{{ $order->id }}</span></td>
                                    <td><span class="text-white">{{ handlePrice($order->sale->total_amount) }}</span></td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                        </span>
                                    </td>
                                    <td><span class="text-gray">{{ dateTimeFormat($order->created_at, 'j M Y') }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-10">
                                            @if($order->printjob_id)
                                                <button class="btn-track" onclick="trackLuluOrder({{ $order->id }})">
                                                    <i data-feather="truck" width="14"></i> Track
                                                </button>
                                            @endif
                                            <a href="/panel/book/purchases/{{ $order->id }}/invoice" class="text-gray" title="Invoice">
                                                <i data-feather="file-text" width="18"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="mt-20">
                        {{ $orders->appends(request()->input())->links('vendor.pagination.panel') }}
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Tracking Modal -->
    <div class="track-overlay" id="trackOverlay">
        <div class="track-modal">
            <div class="track-modal-head">
                <h5 class="m-0 text-white">Order Tracking</h5>
                <button class="btn btn-transparent p-0 text-white font-24" onclick="closeTrackModal()">&times;</button>
            </div>
            <div class="track-modal-body" id="trackModalBody">
                <!-- Content injected by JS -->
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        function trackLuluOrder(orderId) {
            const overlay = document.getElementById('trackOverlay');
            const body = document.getElementById('trackModalBody');
            
            body.innerHTML = '<div class="text-center p-20"><div class="spinner-border text-gold" role="status"></div><p class="mt-10 text-gray">Fetching tracking details...</p></div>';
            overlay.classList.add('open');

            fetch(`/panel/book/purchases/${orderId}/track`)
                .then(res => res.json())
                .then(res => {
                    if (res.code === 200) {
                        const data = res.data;
                        let html = `
                            <div class="track-info-item">
                                <div class="track-info-label">Order Status</div>
                                <div class="track-info-value">${data.status.name}</div>
                            </div>
                            <div class="track-info-item">
                                <div class="track-info-label">Lulu Order ID</div>
                                <div class="track-info-value">#${data.id}</div>
                            </div>
                        `;

                        if (data.line_items && data.line_items[0].tracking_url) {
                            html += `
                                <div class="track-info-item">
                                    <div class="track-info-label">Tracking Link</div>
                                    <div class="track-info-value">
                                        <a href="${data.line_items[0].tracking_url}" target="_blank" class="tracking-link">
                                            Click here to track your shipment
                                        </a>
                                    </div>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="track-info-item">
                                    <div class="track-info-label">Tracking</div>
                                    <div class="track-info-value">Shipment has not been dispatched yet.</div>
                                </div>
                            `;
                        }

                        body.innerHTML = html;
                    } else {
                        body.innerHTML = `<div class="text-center p-20"><p class="text-danger">${res.message || 'Error fetching tracking details'}</p></div>`;
                    }
                })
                .catch(err => {
                    body.innerHTML = `<div class="text-center p-20"><p class="text-danger">Failed to connect to tracking service.</p></div>`;
                });
        }

        function closeTrackModal() {
            document.getElementById('trackOverlay').classList.remove('open');
        }

        window.onclick = function(event) {
            const overlay = document.getElementById('trackOverlay');
            if (event.target == overlay) {
                closeTrackModal();
            }
        }
    </script>
@endpush
