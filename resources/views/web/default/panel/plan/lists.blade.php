@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* =========================
   KEMETIC THEME VARIABLES
========================= */
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

/* =========================
   PAGE
========================= */
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
}

/* =========================
   STATS
========================= */
.kemetic-stats {
    background: linear-gradient(145deg, #161616, #0c0c0c);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
}

.stat-value {
    font-size: 34px;
    color: var(--k-gold);
}

.stat-label {
    color: var(--k-muted);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* =========================
   TABLE CARD
========================= */
.kemetic-table-card {
    background: var(--k-card);
    border-radius: var(--k-radius);
    border: 1px solid var(--k-border);
    box-shadow: var(--k-shadow);
}

.custom-table thead {
    background: rgba(212,175,55,.1);
}

.custom-table th {
    color: var(--k-gold);
    font-weight: 600;
    border-bottom: 1px solid var(--k-border);
}

.custom-table td {
    color: var(--k-text);
    border-top: 1px solid rgba(255,255,255,.05);
}

.custom-table tr:hover {
    background: rgba(212,175,55,.05);
}

/* =========================
   STATUS BADGES
========================= */
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-active {
    background: rgba(46, 204, 113, 0.15);
    color: #2ecc71;
    border: 1px solid rgba(46, 204, 113, 0.3);
}

.status-inactive {
    background: rgba(231, 76, 60, 0.15);
    color: #e74c3c;
    border: 1px solid rgba(231, 76, 60, 0.3);
}

/* =========================
   ACTIONS
========================= */
.btn-transparent {
    color: var(--k-gold);
}

.dropdown-menu {
    background: #0f0f0f;
    border: 1px solid var(--k-border);
}

.dropdown-menu a {
    color: var(--k-text);
}

.dropdown-menu a:hover {
    background: rgba(212,175,55,.12);
    color: var(--k-gold);
}

/* =========================
   CREATE BUTTON
========================= */
.kemetic-create-btn {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    font-weight: 700;
    border-radius: 12px;
    padding: 10px 20px;
    letter-spacing: 0.6px;
    transition: all 0.25s ease;
    box-shadow: 0 6px 18px rgba(212,175,55,0.35);
}

.kemetic-create-btn:hover {
    background: linear-gradient(135deg, #e6c45c, #d4af37);
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(212,175,55,0.45);
    color: #000;
}

/* =========================
   FILTER CARD
========================= */
.kemetic-filter-card {
    background: linear-gradient(180deg, #0b0b0b, #121212);
    border: 1px solid rgba(212,175,55,0.25);
    border-radius: 14px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6);
    padding: 20px;
}

.kemetic-label {
    color: #d4af37;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.kemetic-input {
    background-color: #0f0f0f !important;
    color: #f5f5f5 !important;
    border: 1px solid rgba(212,175,55,0.35);
    border-radius: 10px;
    height: 44px;
}

.kemetic-input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212,175,55,0.25);
}

/* =========================
   TABLE STYLES
========================= */
.kemetic-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.kemetic-table thead th {
    padding: 15px;
    text-align: left;
    border-bottom: 2px solid rgba(212,175,55,0.3);
}

.kemetic-table tbody td {
    padding: 15px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.kemetic-table tbody tr:hover {
    background: rgba(212,175,55,0.05);
}

/* =========================
   PRICE STYLES
========================= */
.price-badge {
    background: rgba(212,175,55,0.1);
    color: #d4af37;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    border: 1px solid rgba(212,175,55,0.2);
}

/* =========================
   ACTION DROPDOWN
========================= */
.kemetic-actions button {
    background: none;
    border: none;
    color: #d4af37;
    cursor: pointer;
    padding: 5px;
}

.kemetic-actions .dropdown-menu {
    min-width: 120px;
}

.kemetic-actions a {
    color: #fff;
    display: block;
    padding: 8px 15px;
    text-decoration: none;
}

.kemetic-actions a:hover {
    background: rgba(212,175,55,0.1);
}
</style>
@endpush

@section('content')
<div class="kemetic-page">

    {{-- FILTER SECTION --}}
    <section class="card kemetic-filter-card mb-4">
        <div class="card-body">
            <form action="/panel/plans" method="get" class="mb-0">
                <div class="row">
                    {{-- SEARCH BY NAME --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('admin/main.search') }}</label>
                            <input name="search" type="text"
                                class="form-control kemetic-input"
                                value="{{ request()->get('search') }}"
                                placeholder="Search plan name...">
                        </div>
                    </div>

                    {{-- STATUS FILTER --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="kemetic-label">{{ trans('admin/main.status') }}</label>
                            <select name="status"
                                    class="form-control kemetic-input">
                                <option value="">All Status</option>
                                <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- PRICE RANGE --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="kemetic-label">Min Price</label>
                            <input type="number"
                                class="form-control kemetic-input"
                                name="min_price"
                                value="{{ request()->get('min_price') }}"
                                placeholder="0">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="kemetic-label">Max Price</label>
                            <input type="number"
                                class="form-control kemetic-input"
                                name="max_price"
                                value="{{ request()->get('max_price') }}"
                                placeholder="10000">
                        </div>
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn kemetic-create-btn w-100">
                            <i data-feather="filter" width="16"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- HEADER SECTION --}}
    <section class="mt-35 kemetic-section">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title kemetic-title">
                Subscription Plans
            </h2>
            
            {{-- CREATE BUTTON --}}
            <a href="/panel/plan/create" class="btn kemetic-create-btn mt-3 mt-md-0">
                <i data-feather="plus" width="18" class="mr-2"></i>
                Create New Plan
            </a>
        </div>

        {{-- PLANS TABLE --}}
        @if($plans->count() > 0)
            <div class="kemetic-table-card mt-25">
                <div class="table-responsive">
                    <table class="kemetic-table">
                        <thead>
                            <tr>
                                <!-- <th>#</th> -->
                                <th>Plan Name</th>
                                <th>Plan Code</th>
                                <th>Price</th>
                                <th>Duration (Days)</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($plans as $plan)
                                <tr>
                                    <!-- <td>{{ $plan->id }}</td> -->
                                    <td>
                                        <strong>{{ $plan->title }}</strong>
                                    </td>
                                    <td>
                                        <span class="price-badge">{{ $plan->code }}</span>
                                    </td>
                                    <td>
                                        <span class="price-badge">
                                            {{ number_format($plan->price) }} {{ $currency }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="price-badge">{{ $plan->duration_days }} days</span>
                                    </td>
                                    <td>
                                        @if($plan->is_membership)
                                            <span class="status-badge status-active">Active</span>
                                        @else
                                            <span class="status-badge status-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ dateTimeFormat($plan->created_at, 'Y-m-d') }}
                                    </td>
                                    <td>
                                        <div class="dropdown kemetic-actions">
                                            <button data-toggle="dropdown">
                                                <i data-feather="more-vertical" width="18"></i>
                                            </button>
                                            
                                            <div class="dropdown-menu">
                                                <a href="/panel/plan/{{ $plan->id }}/edit"
                                                   class="dropdown-item kemetic-dropdown-item">
                                                    <i data-feather="edit" width="14" class="mr-2"></i>
                                                    Edit
                                                </a>
                                                
                                                <form action="/panel/plan/{{ $plan->id }}/delete" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="dropdown-item kemetic-dropdown-item text-danger">
                                                        <i data-feather="trash-2" width="14" class="mr-2"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                                
                                                {{-- TOGGLE STATUS --}}
                                                <!-- <form action="/panel/plan/{{ $plan->id }}/toggle-status" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="dropdown-item kemetic-dropdown-item">
                                                        <i data-feather="{{ $plan->is_membership ? 'pause' : 'play' }}" 
                                                           width="14" class="mr-2"></i>
                                                        {{ $plan->is_membership ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form> -->
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- NO RESULTS --}}
            <div class="kemetic-table-card mt-25">
                <div class="text-center py-5">
                    <i data-feather="package" width="48" height="48" class="text-muted"></i>
                    <h4 class="mt-3" style="color: var(--k-gold);">No Plans Found</h4>
                    <p class="text-muted">Create your first subscription plan to get started.</p>
                    <a href="/panel/plans/create" class="btn kemetic-create-btn mt-3">
                        <i data-feather="plus" width="18" class="mr-2"></i>
                        Create First Plan
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- PAGINATION --}}
    @if($plans->count() > 0)
        <div class="my-30">
            {{ $plans->appends(request()->input())->links('vendor.pagination.panel') }}
        </div>
    @endif

</div>
@endsection

@push('scripts_bottom')
<script>
    // Initialize Feather Icons
    feather.replace();

    // Status toggle confirmation
    document.querySelectorAll('form[action*="toggle-status"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const isActive = this.querySelector('button').textContent.includes('Deactivate');
            const message = isActive 
                ? 'Are you sure you want to deactivate this plan?' 
                : 'Are you sure you want to activate this plan?';
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush