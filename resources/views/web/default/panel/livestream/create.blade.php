@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
/* ===============================
   KEMETIC LIVESTREAM FORM THEME
================================ */

.kemetic-form-card {
    background: linear-gradient(180deg, #0b0b0b, #121212);
    border: 1px solid rgba(242,201,76,0.25);
    border-radius: 20px;
    padding: 25px;
}

.kemetic-title {
    color: #f2c94c;
    font-weight: 600;
    margin-bottom: 25px;
}

.kemetic-form-card .input-label {
    color: #c9b26d;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
}

.kemetic-form-card .form-control,
.kemetic-form-card .form-select {
    background: #0e0e0e;
    border: 1px solid rgba(242,201,76,0.3);
    color: #fff;
    border-radius: 12px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.kemetic-form-card .form-control:focus,
.kemetic-form-card .form-select:focus {
    border-color: #f2c94c;
    box-shadow: 0 0 0 2px rgba(242,201,76,0.15);
    background: #0e0e0e;
}

.kemetic-form-card .form-text {
    color: #8a8a8a;
    font-size: 12px;
}

/* Streaming Info Panel */
.streaming-info-panel {
    background: linear-gradient(145deg, #0a0a0a, #151515);
    border: 1px solid rgba(242,201,76,0.2);
    border-radius: 15px;
    padding: 20px;
    margin-top: 25px;
}

.streaming-info-panel h6 {
    color: #f2c94c;
    font-weight: 600;
    margin-bottom: 20px;
}

.streaming-info-panel .input-group {
    margin-bottom: 15px;
}

.streaming-info-panel .input-group-text {
    background: rgba(242,201,76,0.1);
    border: 1px solid rgba(242,201,76,0.3);
    color: #f2c94c;
}

/* Buttons */
.kemetic-save-btn {
    background: linear-gradient(135deg, #f2c94c, #caa63c);
    color: #000;
    font-weight: 600;
    border: none;
    border-radius: 14px;
    padding: 12px 30px;
    transition: all 0.3s ease;
}

.kemetic-save-btn:hover {
    background: linear-gradient(135deg, #ffd95c, #d4af37);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(242,201,76,0.3);
}

.kemetic-sync-btn {
    background: transparent;
    border: 1px solid rgba(242,201,76,0.3);
    color: #f2c94c;
    border-radius: 14px;
    padding: 12px 25px;
    transition: all 0.3s ease;
}

.kemetic-sync-btn:hover {
    background: rgba(242,201,76,0.1);
    border-color: #f2c94c;
}

.kemetic-cancel-btn {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.1);
    color: #8a8a8a;
    border-radius: 14px;
    padding: 12px 25px;
    transition: all 0.3s ease;
}

.kemetic-cancel-btn:hover {
    background: rgba(255,255,255,0.05);
    color: #fff;
}

/* Read-only inputs */
.readonly-input {
    background: #111111 !important;
    color: #8a8a8a !important;
    border-color: rgba(242,201,76,0.1) !important;
    cursor: not-allowed;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .kemetic-form-card {
        padding: 15px;
    }
    
    .streaming-info-panel {
        padding: 15px;
    }
}
</style>
@endpush

@section('content')

<section class="mt-25">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <h2 class="section-title kemetic-title mb-20 text-center">
                    {{ !empty($channel) ? 'Edit Live Stream Channel' : 'Create New Live Stream Channel' }}
                </h2>

                <form action="/panel/livestream/{{ !empty($channel) ? $channel->id.'/update' : 'store' }}" method="post">
                    {{ csrf_field() }}

                    <div class="kemetic-form-card">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <!-- Channel Name -->
                                <div class="form-group mb-4">
                                    <label class="input-label kemetic-label">Channel Name *</label>
                                    <input type="text" 
                                           name="channel_name"
                                           class="form-control @error('channel_name') is-invalid @enderror"
                                           value="{{ !empty($channel) ? $channel->channel_name : old('channel_name') }}"
                                           placeholder="Enter channel name"
                                           required>
                                    @error('channel_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <small class="form-text">A descriptive name for your live stream channel</small>
                                </div>

                                <!-- Channel Type -->
                                <div class="form-group mb-4">
                                    <label class="input-label kemetic-label">Channel Type *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            name="type" required>
                                        <option value="">Select channel type</option>
                                        <option value="BASIC" {{ (!empty($channel) && $channel->type == 'BASIC') || old('type') == 'BASIC' ? 'selected' : '' }}>
                                            Basic - 480p, 30fps
                                        </option>
                                        <option value="STANDARD" {{ (!empty($channel) && $channel->type == 'STANDARD') || old('type') == 'STANDARD' ? 'selected' : '' }}>
                                            Standard - 1080p, 60fps
                                        </option>
                                        <option value="ADVANCED" {{ (!empty($channel) && $channel->type == 'ADVANCED') || old('type') == 'ADVANCED' ? 'selected' : '' }}>
                                            Advanced - 4K, 60fps
                                        </option>
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <small class="form-text">Select the quality level for your stream</small>
                                </div>

                                <!-- Latency Mode -->
                                <div class="form-group mb-4">
                                    <label class="input-label kemetic-label">Latency Mode</label>
                                    <select class="form-control @error('latency_mode') is-invalid @enderror" 
                                            name="latency_mode">
                                        <option value="NORMAL" {{ (!empty($channel) && $channel->latency_mode == 'NORMAL') || old('latency_mode') == 'NORMAL' ? 'selected' : '' }}>
                                            Normal (5-30 seconds delay)
                                        </option>
                                        <option value="LOW" {{ (!empty($channel) && $channel->latency_mode == 'LOW') || old('latency_mode') == 'LOW' ? 'selected' : '' }}>
                                            Low (2-5 seconds delay)
                                        </option>
                                    </select>
                                    @error('latency_mode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <small class="form-text">Choose based on your need for real-time interaction</small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                @if(!empty($channel))
                                    <!-- Status -->
                                    <div class="form-group mb-4">
                                        <label class="input-label kemetic-label">Status</label>
                                        <select class="form-control @error('is_active') is-invalid @enderror" 
                                                name="is_active">
                                            <option value="1" {{ $channel->is_active ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ !$channel->is_active ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('is_active')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Read-only Info -->
                                    <div class="form-group mb-4">
                                        <label class="input-label kemetic-label">Channel ARN</label>
                                        <input type="text" 
                                               class="form-control readonly-input" 
                                               value="{{ $channel->channel_arn }}" 
                                               readonly>
                                        <small class="form-text">AWS Channel ARN (Read-only)</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="input-label kemetic-label">Region</label>
                                        <input type="text" 
                                               class="form-control readonly-input" 
                                               value="{{ $channel->region }}" 
                                               readonly>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="input-label kemetic-label">Channel ID</label>
                                        <input type="text" 
                                               class="form-control readonly-input" 
                                               value="{{ $channel->channel_id }}" 
                                               readonly>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(!empty($channel))
                            <!-- Streaming Information Section -->
                            <div class="streaming-info-panel">
                                <h6>
                                    <i class="fa fa-broadcast-tower mr-2"></i> Streaming Information
                                </h6>
                                
                                <div class="row mt-3">
                                    <!-- RTMPS URL -->
                                    <div class="col-md-12 mb-3">
                                        <label class="input-label kemetic-label">RTMPS URL</label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control readonly-input" 
                                                   value="{{ $channel->rtmps_url }}" 
                                                   readonly
                                                   id="rtmpsUrl">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        onclick="copyToClipboard('rtmpsUrl')"
                                                        title="Copy RTMPS URL">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text">Use this URL in OBS or other streaming software</small>
                                    </div>
                                    
                                    <!-- Playback HLS URL -->
                                    <div class="col-md-12 mb-3">
                                        <label class="input-label kemetic-label">Playback HLS URL</label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control readonly-input" 
                                                   value="{{ $channel->full_playback_url }}" 
                                                   readonly
                                                   id="playbackUrl">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        onclick="copyToClipboard('playbackUrl')"
                                                        title="Copy Playback URL">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                                <a href="{{ $channel->full_playback_url }}" 
                                                   target="_blank" 
                                                   class="btn btn-outline-info"
                                                   title="Open in new tab">
                                                    <i class="fa fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <small class="form-text">Share this URL with your viewers</small>
                                    </div>

                                    <!-- Stream Key -->
                                    <div class="col-md-12">
                                        <label class="input-label kemetic-label">Stream Key</label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control readonly-input" 
                                                   id="currentStreamKey" 
                                                   value="{{ $channel->stream_key }}" 
                                                   readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        onclick="toggleCurrentStreamKey()"
                                                        title="Show/Hide">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        onclick="copyToClipboard('currentStreamKey')"
                                                        title="Copy">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                                <a href="/panel/livestream/{{ $channel->id }}/create-stream-key" 
                                                   class="btn btn-outline-warning"
                                                   onclick="return confirm('Create a new stream key? The old key will remain active.')"
                                                   title="Generate New Key">
                                                    <i class="fa fa-key"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger">
                                            <i class="fa fa-exclamation-triangle"></i> Keep this secret! This is used to broadcast to the channel
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-4 pt-3 border-top border-secondary">
                            <div class="d-flex flex-column flex-md-row justify-content-between">
                                <div class="mb-3 mb-md-0">
                                    <button type="submit" class="btn kemetic-save-btn">
                                        <i class="fa fa-save mr-2"></i>
                                        {{ !empty($channel) ? 'Update Channel' : 'Create Channel' }}
                                    </button>
                                    
                                    @if(!empty($channel))
                                        <a href="/panel/livestream/{{ $channel->id }}/sync" 
                                           class="btn kemetic-sync-btn ml-2"
                                           onclick="return confirm('Sync this channel with AWS?')">
                                            <i class="fa fa-sync mr-2"></i> Sync with AWS
                                        </a>
                                    @endif
                                </div>
                                
                                <div>
                                    <a href="/panel/livestream" class="btn kemetic-cancel-btn">
                                        <i class="fa fa-times mr-2"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts_bottom')
<script>
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

function toggleCurrentStreamKey() {
    const input = document.getElementById('currentStreamKey');
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;
}
</script>
@endpush