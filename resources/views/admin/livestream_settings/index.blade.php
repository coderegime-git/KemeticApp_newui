@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Livestream Settings</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item">Livestream Settings</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if($settings->count() == 0)
                                <a href="{{ getAdminPanelUrl() }}/livestream-settings/create" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Add Setting
                                </a>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">App ID</th>
                                        <th class="text-left">App Sign</th>
                                        <th class="text-center">Created At</th>
                                        <th>Actions</th>
                                    </tr>

                                    @foreach($settings as $setting)
                                        <tr>
                                            <td>{{ $setting->id }}</td>
                                            <td class="text-left">
                                                {{ $setting->app_id }}
                                            </td>
                                            <td class="text-left">
                                                <div class="input-group input-group-sm">
                                                    <input type="password" class="form-control form-control-sm" 
                                                           id="appSign_{{ $setting->id }}" 
                                                           value="{{ $setting->app_sign }}" readonly style="max-width: 250px;">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary btn-sm" 
                                                                type="button" 
                                                                onclick="toggleAppSign({{ $setting->id }})">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary btn-sm" 
                                                                type="button" 
                                                                onclick="copyToClipboard('appSign_{{ $setting->id }}')">
                                                            <i class="fa fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ dateTimeFormat($setting->created_at, 'Y M j | H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    
                                                        <a href="{{ getAdminPanelUrl() }}/livestream-settings/{{ $setting->id }}/edit" 
                                                           class="btn-transparent text-primary" 
                                                           data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl().'/livestream-settings/'. $setting->id.'/delete',
                                                            'btnClass' => 'btn-transparent text-danger ml-1',
                                                            'tooltip' => 'Delete',
                                                            'icon' => 'fa fa-trash'
                                                        ])
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if($settings->count() == 0)
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <div class="text-muted">No livestream settings found.</div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $settings->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
<script>
function toggleAppSign(settingId) {
    const input = document.getElementById(`appSign_${settingId}`);
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices
    document.execCommand('copy');
    
    // Show toast notification
    if(typeof toastr !== 'undefined') {
        toastr.success('Copied to clipboard!');
    }
}
</script>
@endpush
