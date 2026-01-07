@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* ==============================
   KEMETIC BLACK GOLD THEME
============================== */

.kemetic-section {
    color: #EAEAEA;
}

/* Title */
.kemetic-title {
    color: #D4AF37;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* Card */
.kemetic-card {
    background: linear-gradient(145deg, #0b0b0b, #151515);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 14px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.6);
}

/* Table */
.kemetic-table {
    color: #EAEAEA;
}

.kemetic-table thead th {
    background: transparent;
    color: #D4AF37;
    font-weight: 600;
    border-bottom: 1px solid rgba(212,175,55,0.4);
}

.kemetic-table tbody tr {
    transition: all 0.25s ease;
}

.kemetic-table tbody tr:hover {
    background: rgba(212, 175, 55, 0.05);
}

/* Status Badges */
.kemetic-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-gold {
    background: rgba(212,175,55,0.15);
    color: #D4AF37;
    border: 1px solid rgba(212,175,55,0.4);
}

.badge-success {
    background: rgba(46,204,113,0.15);
    color: #2ecc71;
    border: 1px solid rgba(46,204,113,0.4);
}

.badge-danger {
    background: rgba(231,76,60,0.15);
    color: #e74c3c;
    border: 1px solid rgba(231,76,60,0.4);
}

/* Input groups for stream key */
.input-group-sm {
    max-width: 250px;
}

.input-group-sm .form-control {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,0.3);
    color: #fff;
}

.input-group-sm .btn-outline-secondary {
    border-color: rgba(212,175,55,0.3);
    color: #D4AF37;
}

.input-group-sm .btn-outline-secondary:hover {
    background: rgba(212,175,55,0.1);
    border-color: #D4AF37;
}

/* Copy buttons */
.btn-copy {
    background: transparent;
    border: 1px solid rgba(212,175,55,0.3);
    color: #D4AF37;
    transition: all 0.2s ease;
}

.btn-copy:hover {
    background: rgba(212,175,55,0.1);
    border-color: #D4AF37;
}

/* Create button */
.btn-create {
    background: linear-gradient(135deg, #D4AF37, #B8962E);
    color: #000;
    font-weight: 700;
    border-radius: 10px;
    padding: 8px 20px;
    transition: all 0.2s ease;
}

.btn-create:hover {
    background: linear-gradient(135deg, #E6C45C, #D4AF37);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(212,175,55,0.3);
}

/* Filter form */
.kemetic-filter-card {
    background: linear-gradient(180deg, #121212, #0a0a0a);
    border: 1px solid #262626;
    border-radius: 18px;
    padding: 20px;
}

.kemetic-label {
    color: #D4AF37;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
}

.kemetic-input {
    background: #0f0f0f;
    border: 1px solid rgba(212,175,55,0.3);
    color: #fff;
    border-radius: 8px;
}

.kemetic-input:focus {
    border-color: #D4AF37;
    box-shadow: 0 0 0 2px rgba(212,175,55,0.2);
    background: #0f0f0f;
}

.kemetic-btn {
    background: linear-gradient(135deg, #D4AF37, #B8962E);
    color: #000;
    font-weight: 700;
    border-radius: 10px;
    padding: 8px 25px;
    transition: all 0.2s ease;
}

.kemetic-btn:hover {
    background: linear-gradient(135deg, #E6C45C, #D4AF37);
    transform: translateY(-1px);
}
</style>
@endpush

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <section class="mt-25 kemetic-section">
                <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row mb-20">
                    <h2 class="section-title kemetic-title">
                        My Live Stream Channels
                    </h2>
                    @if($channels->count() == 0)
                        <a href="/panel/livestream/create" class="btn btn-create mt-2 mt-md-0">
                            <i class="fa fa-plus mr-2"></i> Create New Channel
                        </a>
                    @endif
                </div>

                <!-- Filter Section -->
                <div class="card kemetic-filter-card mb-25">
                    <div class="card-body">
                        <form action="/panel/livestream" method="get" class="mb-0">
                            <div class="row">
                                <!-- Search -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="kemetic-label">Search</label>
                                        <input name="channel_name" type="text" 
                                               class="form-control kemetic-input" 
                                               value="{{ request()->get('channel_name') }}" 
                                               placeholder="Channel name">
                                    </div>
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="kemetic-label">Start Date</label>
                                        <input type="date" 
                                               class="form-control kemetic-input text-center" 
                                               name="from" 
                                               value="{{ request()->get('from') }}">
                                    </div>
                                </div>
                                
                                <!-- End Date -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="kemetic-label">End Date</label>
                                        <input type="date" 
                                               class="form-control kemetic-input text-center" 
                                               name="to" 
                                               value="{{ request()->get('to') }}">
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="kemetic-label">Type</label>
                                        <select name="type" class="form-control kemetic-input">
                                            <option value="all">All Types</option>
                                            <option value="BASIC" @if(request()->get('type') == 'BASIC') selected @endif>Basic</option>
                                            <option value="STANDARD" @if(request()->get('type') == 'STANDARD') selected @endif>Standard</option>
                                            <option value="ADVANCED" @if(request()->get('type') == 'ADVANCED') selected @endif>Advanced</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="kemetic-label">Status</label>
                                        <select name="is_active" class="form-control kemetic-input">
                                            <option value="all">All Status</option>
                                            <option value="active" @if(request()->get('is_active') == 'active') selected @endif>Active</option>
                                            <option value="inactive" @if(request()->get('is_active') == 'inactive') selected @endif>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn kemetic-btn w-100">
                                        <i class="fa fa-filter mr-2"></i> Show Results
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Channels Table -->
                <div class="card kemetic-card">
                    <div class="card-body">
                        @if($channels->count() > 0)
                            <div class="table-responsive">
                                <table class="table kemetic-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-left">Channel Name</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Stream Key</th>
                                            <th class="text-center">Playback URL</th>
                                            <th class="text-center">Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($channels as $channel)
                                            <tr>
                                                <td>{{ $channel->id }}</td>
                                                <td class="text-left">
                                                    <strong>{{ $channel->channel_name }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="kemetic-badge badge-gold">
                                                        {{ $channel->type }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="kemetic-badge {{ $channel->is_active ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $channel->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="input-group input-group-sm">
                                                        <input type="password" 
                                                               class="form-control form-control-sm" 
                                                               id="streamKey_{{ $channel->id }}" 
                                                               value="{{ $channel->stream_key }}" 
                                                               readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-copy btn-sm" 
                                                                    type="button" 
                                                                    onclick="toggleStreamKey({{ $channel->id }})"
                                                                    title="Show/Hide">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-copy btn-sm" 
                                                                    type="button" 
                                                                    onclick="copyToClipboard('streamKey_{{ $channel->id }}')"
                                                                    title="Copy">
                                                                <i class="fa fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" 
                                                               class="form-control form-control-sm" 
                                                               id="playbackUrl_{{ $channel->id }}" 
                                                               value="{{ $channel->full_playback_url }}" 
                                                               readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-copy btn-sm" 
                                                                    type="button" 
                                                                    onclick="copyToClipboard('playbackUrl_{{ $channel->id }}')"
                                                                    title="Copy">
                                                                <i class="fa fa-copy"></i>
                                                            </button>
                                                            <a href="{{ $channel->full_playback_url }}" 
                                                               target="_blank" 
                                                               class="btn btn-copy btn-sm"
                                                               title="Open">
                                                                <i class="fa fa-external-link-alt"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    {{ dateTimeFormat($channel->created_at, 'Y M j') }}
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-copy btn-sm dropdown-toggle" 
                                                                type="button" 
                                                                data-toggle="dropdown" 
                                                                aria-haspopup="true" 
                                                                aria-expanded="false">
                                                            <i class="fa fa-ellipsis-h"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" 
                                                               href="/panel/livestream/{{ $channel->id }}/edit">
                                                                <i class="fa fa-edit mr-2"></i> Edit
                                                            </a>
                                                            <!-- <a class="dropdown-item" 
                                                               href="/panel/livestream/{{ $channel->id }}/create-stream-key"
                                                               onclick="return confirm('Create a new stream key? The old key will remain active.')">
                                                                <i class="fa fa-key mr-2"></i> New Stream Key
                                                            </a>
                                                            <a class="dropdown-item" 
                                                               href="/panel/livestream/{{ $channel->id }}/sync"
                                                               onclick="return confirm('Sync this channel with AWS?')">
                                                                <i class="fa fa-sync mr-2"></i> Sync with AWS
                                                            </a> -->
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-danger" 
                                                               href="/panel/livestream/{{ $channel->id }}/delete"
                                                               onclick="return confirm('Are you sure you want to delete this channel? This action cannot be undone.')">
                                                                <i class="fa fa-trash mr-2"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $channels->appends(request()->input())->links('vendor.pagination.panel') }}
                            </div>

                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fa fa-video-slash" style="font-size: 60px; color: #D4AF37;"></i>
                                </div>
                                <h5 class="text-muted mb-3">No Live Stream Channels Found</h5>
                                <p class="text-muted">You haven't created any live stream channels yet.</p>
                                <a href="/panel/livestream/create" class="btn btn-create mt-3">
                                    <i class="fa fa-plus mr-2"></i> Create Your First Channel
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection

@push('scripts_bottom')
<script>
function toggleStreamKey(channelId) {
    const input = document.getElementById(`streamKey_${channelId}`);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            toastr.success('Copied to clipboard!');
        } else {
            toastr.error('Failed to copy');
        }
    } catch (err) {
        console.error('Copy failed:', err);
        toastr.error('Failed to copy');
    }
}
</script>
@endpush