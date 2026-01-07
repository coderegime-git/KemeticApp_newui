@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ !empty($channel) ? 'Edit Live Stream Channel' : 'Create New Live Stream Channel' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl() }}/livestream">Live Stream Channels</a></div>
                <div class="breadcrumb-item">{{ !empty($channel) ? 'Edit' : 'Create' }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl() }}/livestream/{{ !empty($channel) ? $channel->id.'/update' : 'store' }}" method="post">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label>Channel Name *</label>
                                            <input type="text" name="channel_name"
                                                   class="form-control  @error('channel_name') is-invalid @enderror"
                                                   value="{{ !empty($channel) ? $channel->channel_name : old('channel_name') }}"
                                                   placeholder="Enter channel name"/>
                                            @error('channel_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <small class="form-text text-muted">A descriptive name for your live stream channel</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Channel Type *</label>
                                            <select class="form-control @error('type') is-invalid @enderror" name="type">
                                                <option value="" {{ empty($channel) ? 'selected' : '' }}>Select channel type</option>
                                                <option value="BASIC" {{ (!empty($channel) && $channel->type == 'BASIC') || old('type') == 'BASIC' ? 'selected' : '' }}>
                                                    Basic 
                                                    <!-- ($0.20/hr) - 480p, 30fps -->
                                                </option>
                                                <option value="STANDARD" {{ (!empty($channel) && $channel->type == 'STANDARD') || old('type') == 'STANDARD' ? 'selected' : '' }}>
                                                    Standard 
                                                    <!-- ($2.00/hr) - 1080p, 60fps -->
                                                </option>
                                                <option value="ADVANCED" {{ (!empty($channel) && $channel->type == 'ADVANCED') || old('type') == 'ADVANCED' ? 'selected' : '' }}>
                                                    Advanced 
                                                    <!-- ($20.00/hr) - 4K, 60fps -->
                                                </option>
                                            </select>
                                            @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Latency Mode</label>
                                            <select class="form-control @error('latency_mode') is-invalid @enderror" name="latency_mode">
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
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        @if(!empty($channel))
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control @error('is_active') is-invalid @enderror" name="is_active">
                                                    <option value="1" {{ $channel->is_active ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !$channel->is_active ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                @error('is_active')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        @endif

                                        <!-- <div class="form-group">
                                            <label>Recording Configuration ARN</label>
                                            <input type="text" name="recording_configuration_arn"
                                                   class="form-control  @error('recording_configuration_arn') is-invalid @enderror"
                                                   value="{{ !empty($channel) ? $channel->recording_configuration_arn : old('recording_configuration_arn') }}"
                                                   placeholder="arn:aws:ivs:region:account:recording-configuration/xxx"/>
                                            @error('recording_configuration_arn')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <small class="form-text text-muted">Optional: ARN for automatic recording configuration</small>
                                        </div> -->

                                        @if(!empty($channel))
                                            <div class="form-group">
                                                <label>Channel ARN</label>
                                                <input type="text" class="form-control" value="{{ $channel->channel_arn }}" readonly>
                                                <small class="form-text text-muted">AWS Channel ARN (Read-only)</small>
                                            </div>

                                            <div class="form-group">
                                                <label>Region</label>
                                                <input type="text" class="form-control" value="{{ $channel->region }}" readonly>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($channel))
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <h6><i class="fa fa-info-circle"></i> Streaming Information</h6>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>RTMPS URL</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" value="{{ $channel->rtmps_url }}" readonly>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard(this.previousElementSibling)">
                                                                        <i class="fa fa-copy"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <small class="form-text text-muted">Use this for OBS/Streaming software</small>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Playback HLS URL</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" value="{{ $channel->full_playback_url }}" readonly>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard(this.previousElementSibling)">
                                                                        <i class="fa fa-copy"></i>
                                                                    </button>
                                                                    <a href="{{ $channel->full_playback_url }}" target="_blank" class="btn btn-outline-info">
                                                                        <i class="fa fa-external-link-alt"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <small class="form-text text-muted">Viewers will use this URL</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Stream Key</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="currentStreamKey" value="{{ $channel->stream_key }}" readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary" type="button" onclick="toggleCurrentStreamKey()">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('currentStreamKey')">
                                                                <i class="fa fa-copy"></i>
                                                            </button>
                                                            <a href="{{ getAdminPanelUrl() }}/livestream/{{ $channel->id }}/create-stream-key" 
                                                               class="btn btn-outline-warning"
                                                               onclick="return confirm('Create a new stream key? The old key will remain active.')">
                                                                <i class="fa fa-key"></i> New Key
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">Keep this secret! This is used to broadcast to the channel</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                    @if(!empty($channel))
                                        <a href="{{ getAdminPanelUrl() }}/livestream/{{ $channel->id }}/sync" 
                                           class="btn btn-info"
                                           onclick="return confirm('Sync this channel with AWS?')">
                                            <i class="fa fa-sync"></i> Sync with AWS
                                        </a>
                                    @endif
                                    <a href="{{ getAdminPanelUrl() }}/livestream" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
<script>
function copyToClipboard(element) {
    if (typeof element === 'string') {
        element = document.getElementById(element);
    }
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    toastr.success('Copied to clipboard!');
}

function toggleCurrentStreamKey() {
    const input = document.getElementById('currentStreamKey');
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;
}
</script>
@endpush