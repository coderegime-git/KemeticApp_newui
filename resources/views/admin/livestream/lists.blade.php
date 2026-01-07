@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Live Stream Channels</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item">Live Stream Channels</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if($channels->count() == 0)
                                <a href="{{ getAdminPanelUrl() }}/livestream/create" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Create New Channel
                                </a>
                            @endif
                        </div>

                        <section class="card">
                            <div class="card-body">
                                <form action="{{ getAdminPanelUrl() }}/livestream" method="get" class="mb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                                <input name="channel_name" type="text" class="form-control" value="{{ request()->get('channel_name') }}" placeholder="Channel name">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                                <div class="input-group">
                                                    <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                                <div class="input-group">
                                                    <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="input-label">Type</label>
                                                <select name="type" class="form-control populate">
                                                    <option value="all">All Types</option>
                                                    <option value="BASIC" @if(request()->get('type') == 'BASIC') selected="selected" @endif>Basic</option>
                                                    <option value="STANDARD" @if(request()->get('type') == 'STANDARD') selected="selected" @endif>Standard</option>
                                                    <option value="ADVANCED" @if(request()->get('type') == 'ADVANCED') selected="selected" @endif>Advanced</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="input-label">Status</label>
                                                <select name="is_active" class="form-control populate">
                                                    <option value="all">All Status</option>
                                                    <option value="active" @if(request()->get('is_active') == 'active') selected="selected" @endif>Active</option>
                                                    <option value="inactive" @if(request()->get('is_active') == 'inactive') selected="selected" @endif>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mt-1">
                                                <label class="input-label mb-4"> </label>
                                                <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('admin/main.show_results') }}">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">Channel Name</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Stream Key</th>
                                        <th class="text-center">Playback URL</th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">Updated At</th>
                                        <th>Actions</th>
                                    </tr>

                                    @foreach($channels as $channel)
                                        <tr>
                                            <td>{{ $channel->id }}</td>
                                            <td class="text-left">
                                                {{ $channel->channel_name }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $channel->type == 'STANDARD' ? 'info' : ($channel->type == 'ADVANCED' ? 'warning' : 'secondary') }}">
                                                    {{ $channel->type }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $channel->is_active ? 'success' : 'danger' }}">
                                                    {{ $channel->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group input-group-sm">
                                                    <input type="password" class="form-control form-control-sm" 
                                                           id="streamKey_{{ $channel->id }}" 
                                                           value="{{ $channel->stream_key }}" readonly style="max-width: 150px;">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary btn-sm" 
                                                                type="button" 
                                                                onclick="toggleStreamKey({{ $channel->id }})">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary btn-sm" 
                                                                type="button" 
                                                                onclick="copyToClipboard('streamKey_{{ $channel->id }}')">
                                                            <i class="fa fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control form-control-sm" 
                                                           id="playbackUrl_{{ $channel->id }}" 
                                                           value="{{ $channel->full_playback_url }}" readonly style="max-width: 200px;">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary btn-sm" 
                                                                type="button" 
                                                                onclick="copyToClipboard('playbackUrl_{{ $channel->id }}')">
                                                            <i class="fa fa-copy"></i>
                                                        </button>
                                                        <a href="{{ $channel->full_playback_url }}" 
                                                           target="_blank" 
                                                           class="btn btn-outline-info btn-sm">
                                                            <i class="fa fa-external-link-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ dateTimeFormat($channel->created_at, 'Y M j | H:i') }}</td>
                                            <td class="text-center">{{ dateTimeFormat($channel->updated_at, 'Y M j | H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    
                                                        <a href="{{ getAdminPanelUrl() }}/livestream/{{ $channel->id }}/edit" 
                                                           class="btn-transparent text-primary" 
                                                           data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <!-- <a href="{{ getAdminPanelUrl() }}/livestream/{{ $channel->id }}/create-stream-key" 
                                                           class="btn-transparent text-warning ml-1"
                                                           data-toggle="tooltip" data-placement="top" title="Create New Stream Key"
                                                           onclick="return confirm('Create a new stream key? The old key will remain active.')">
                                                            <i class="fa fa-key"></i>
                                                        </a>
                                                        <a href="{{ getAdminPanelUrl() }}/livestream/{{ $channel->id }}/sync" 
                                                           class="btn-transparent text-info ml-1"
                                                           data-toggle="tooltip" data-placement="top" title="Sync with AWS"
                                                           onclick="return confirm('Sync this channel with AWS?')">
                                                            <i class="fa fa-sync"></i>
                                                        </a> -->
                                                        @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl().'/livestream/'. $channel->id.'/delete',
                                                            'btnClass' => 'btn-transparent text-danger ml-1',
                                                            'tooltip' => 'Delete',
                                                            'icon' => 'fa fa-trash'
                                                        ])
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if($channels->count() == 0)
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <div class="text-muted">No live stream channels found.</div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $channels->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
<script>
function toggleStreamKey(channelId) {
    const input = document.getElementById(`streamKey_${channelId}`);
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices
    document.execCommand('copy');
    
    // Show toast notification
    toastr.success('Copied to clipboard!');
}
</script>
@endpush