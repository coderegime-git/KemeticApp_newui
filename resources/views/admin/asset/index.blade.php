@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Assets Management</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">Assets</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <form action="{{ getAdminPanelUrl() }}/asset/store" method="post" enctype="multipart/form-data" id="uploadForm">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="title"
                                                   class="form-control @error('title') is-invalid @enderror"
                                                   value="{{ old('title') }}"
                                                   placeholder="Enter asset title" required/>
                                            @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Type</label>
                                            <input type="text" name="type"
                                                   class="form-control @error('type') is-invalid @enderror"
                                                   value="{{ old('type') }}"
                                                   placeholder="Enter asset type" required/>
                                            @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>File</label>
                                            <input type="file" name="file" 
                                                   class="form-control-file @error('file') is-invalid @enderror" 
                                                   required>
                                            @error('file')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <!-- <small class="text-muted">Max file size: 10MB</small> -->
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-upload"></i> Upload Asset
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="table-responsive mt-4">
                                <table class="table table-striped font-14">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>File</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assets as $asset)
                                            <tr>
                                                <td>{{ $asset->id }}</td>
                                                <td>{{ $asset->title }}</td>
                                                <td>{{ $asset->type }}</td>
                                                <td>
                                                    @if($asset->path)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fa fa-file mr-2"></i>
                                                            <span class="text-truncate" style="max-width: 200px;">
                                                                {{ $asset->file_name }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No file</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- <div class="d-flex align-items-center"> -->
                                                        
                                                        
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-warning mr-2 edit-asset"
                                                                data-toggle="modal" 
                                                                data-target="#editModal"
                                                                data-id="{{ $asset->id }}"
                                                                data-title="{{ $asset->title }}"
                                                                title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        
                                                        <form action="{{ getAdminPanelUrl() }}/asset/{{ $asset->id }}/delete" 
                                                              method="POST" 
                                                              class="d-inline delete-form">
                                                            @csrf
                                                            @method('GET')
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-danger delete-btn"
                                                                    title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <!-- </div> -->
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <div class="empty-state" data-height="200">
                                                        <div class="empty-state-icon">
                                                            <i class="fas fa-file"></i>
                                                        </div>
                                                        <h2>No assets found</h2>
                                                        <p class="lead">
                                                            You haven't uploaded any assets yet.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                     @method('POST')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="editTitle" 
                                   class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" name="type" id="editType" 
                                   class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Replace File (Optional)</label>
                            <input type="file" name="file" 
                                   class="form-control-file">
                            <small class="text-muted">Leave empty to keep current file</small>
                        </div>
                        
                        <div id="currentFile" class="mt-2">
                            <small class="text-muted">Current file will be shown here</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        $(document).ready(function() {
            // Handle edit button click
            $('.edit-asset').on('click', function() {
                const assetId = $(this).data('id');
                const assetTitle = $(this).data('title');
                const assetType = $(this).data('type');
                const assetFileName = $(this).closest('tr').find('.text-truncate').text() || 'No file';
                
                // Update modal form
                $('#editForm').attr('action', '{{ getAdminPanelUrl() }}/asset/' + assetId + '/update');
                $('#editTitle').val(assetTitle);
                $('#editType').val(assetType);

                // Show current file info
                $('#currentFile').html(`
                    <div class="alert alert-light">
                        <i class="fa fa-file mr-2"></i>
                        <strong>Current File:</strong> ${assetFileName}
                    </div>
                `);
            });
            
            // Handle delete button click
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                if (confirm('Are you sure you want to delete this asset?')) {
                    form.submit();
                }
            });
            
            // Clear form on modal hide
            $('#editModal').on('hidden.bs.modal', function() {
                $('#editForm')[0].reset();
                $('#currentFile').html('<small class="text-muted">Current file will be shown here</small>');
            });
            
            // Form validation
            $('#uploadForm, #editForm').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            });
        });
    </script>
@endpush