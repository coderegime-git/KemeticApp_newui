@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>App Version Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
            </div>
            <div class="breadcrumb-item">App Versions</div>
        </div>
    </div>

    <div class="section-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @php $hasRecord = !empty($versions) && $versions->count() > 0; @endphp

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <ul class="nav nav-pills mb-3" role="tablist">

                            {{-- Show "Current Version" tab only when a record exists --}}
                            @if($hasRecord)
                                <li class="nav-item">
                                    <a class="nav-link {{ empty($editVersion) ? 'active' : '' }}"
                                       data-toggle="tab" href="#versionList" role="tab">
                                        <i class="fa fa-list mr-1"></i> Current Version
                                    </a>
                                </li>
                            @endif

                            {{-- Show "Add Version" tab ONLY when no record exists yet --}}
                            @if(!$hasRecord)
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#versionForm" role="tab">
                                        <i class="fa fa-plus mr-1"></i> Add Version
                                    </a>
                                </li>
                            @endif

                            {{-- Show "Edit Version" tab ONLY when editing --}}
                            @if(!empty($editVersion))
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#versionForm" role="tab">
                                        <i class="fa fa-edit mr-1"></i> Edit Version
                                    </a>
                                </li>
                            @endif

                        </ul>

                        <div class="tab-content">

                            {{-- CURRENT VERSION TABLE --}}
                            @if($hasRecord)
                                <div class="tab-pane fade {{ empty($editVersion) ? 'active show' : '' }}"
                                     id="versionList" role="tabpanel">

                                    <div class="table-responsive">
                                        <table class="table table-striped font-14">
                                            <thead>
                                                <tr>
                                                    <th>App Version</th>
                                                    <th class="text-center">Force Update</th>
                                                    <th>Update Message</th>
                                                    <th class="text-center">Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($versions as $version)
                                                    <tr>
                                                        <td>
                                                            <strong class="text-primary">{{ $version->app_version }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($version->force_update)
                                                                <span class="badge badge-danger">Force Update ON</span>
                                                            @else
                                                                <span class="badge badge-secondary">OFF</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $version->update_message ?? '—' }}</td>
                                                        <td class="text-center">
                                                            @if($version->status)
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ getAdminPanelUrl() }}/app_version/{{ $version->id }}/edit"
                                                               class="btn-transparent text-primary"
                                                               data-toggle="tooltip" title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            @endif

                            {{-- ADD / EDIT FORM — only rendered when no record OR editing --}}
                            @if(!$hasRecord || !empty($editVersion))
                                <div class="tab-pane fade active show" id="versionForm" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <form action="{{ getAdminPanelUrl() }}/app_version/{{ !empty($editVersion) ? $editVersion->id.'/update' : 'store' }}"
                                                  method="POST">
                                                @csrf

                                                {{-- App Version --}}
                                                <div class="form-group">
                                                    <label class="input-label">
                                                        App Version <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="app_version"
                                                           class="form-control @error('app_version') is-invalid @enderror"
                                                           value="{{ old('app_version', $editVersion->app_version ?? '') }}"
                                                           placeholder="e.g. 1.1.0 or 3.2.1" />
                                                    <small class="form-text text-muted">
                                                        API compares this with the client version. If not equal → update needed.
                                                    </small>
                                                    @error('app_version')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                {{-- Force Update --}}
                                                <div class="form-group">
                                                    <label class="input-label d-block">Force Update</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="force_update" value="0">
                                                        <input type="checkbox" class="custom-control-input" id="force_update"
                                                               name="force_update" value="1"
                                                               @if(old('force_update', $editVersion->force_update ?? 0)) checked @endif>
                                                        <label class="custom-control-label" for="force_update">
                                                            Enable Force Update
                                                        </label>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        When ON: clients with a different version receive <code>force_update: true</code>.
                                                    </small>
                                                </div>

                                                {{-- Update Message --}}
                                                <div class="form-group">
                                                    <label class="input-label">Update Message</label>
                                                    <textarea name="update_message" rows="3"
                                                              class="form-control @error('update_message') is-invalid @enderror"
                                                              placeholder="Please update your app to the latest version to continue.">{{ old('update_message', $editVersion->update_message ?? '') }}</textarea>
                                                    @error('update_message')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                {{-- Status --}}
                                                <div class="form-group">
                                                    <label class="input-label d-block">Status</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="status" value="0">
                                                        <input type="checkbox" class="custom-control-input" id="status"
                                                               name="status" value="1"
                                                               @if(old('status', $editVersion->status ?? 1)) checked @endif>
                                                        <label class="custom-control-label" for="status">Active</label>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-save mr-1"></i>
                                                    {{ !empty($editVersion) ? 'Update Version' : 'Save Version' }}
                                                </button>

                                                @if(!empty($editVersion))
                                                    <a href="{{ getAdminPanelUrl() }}/app_version" class="btn btn-secondary ml-2">
                                                        Cancel
                                                    </a>
                                                @endif

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>{{-- end tab-content --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection