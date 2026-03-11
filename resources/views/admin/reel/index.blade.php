@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <style>
        .reel-thumbnail {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-published {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }
        .status-pending {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }
        .status-rejected {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }
        .filter-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">Portals</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <!-- Filter Section -->
                            <div class="filter-section">
                                <form action="{{ getAdminPanelUrl() }}/reel" method="get">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Search by Title</label>
                                                <input type="text" name="title" class="form-control" value="{{ request()->get('title') }}" placeholder="Enter Portals title...">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select name="category_id" class="form-control select2">
                                                    <option value="">All Categories</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category['id'] }}" {{ request()->get('category_id') == $category['id'] ? 'selected' : '' }}>
                                                            {{ $category['title'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="">All</option>
                                                    <option value="published" {{ request()->get('status') == 'published' ? 'selected' : '' }}>Published</option>
                                                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="rejected" {{ request()->get('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Date Range</label>
                                                <input type="text" name="date_range" class="form-control" id="date_range" value="{{ request()->get('date_range') }}" placeholder="Select date range">
                                                <input type="hidden" name="from_date" id="from_date" value="{{ request()->get('from_date') }}">
                                                <input type="hidden" name="to_date" id="to_date" value="{{ request()->get('to_date') }}">
                                            </div>
                                        </div> -->
                                        <div class="col-md-3">
                                            <div class="form-group mt-1">
                                                <label class="input-label mb-4"> </label>
                                                <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('admin/main.show_results') }}">
                                            </div>
                                        </div>

                                        
                                    </div>
                                </form>
                            </div>

                            <!-- Add New Button -->
                            <div class="mb-3 text-right">
                                <a href="{{ getAdminPanelUrl() }}/reel/create" class="btn btn-success">
                                    <i class="fa fa-plus"></i> Add New Portals
                                </a>
                            </div>

                            <!-- Portals Table -->
                            @if($reels->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped font-14">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Video</th>
                                                <th>Stats</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reels as $reel)
                                                <tr>
                                                    <td>{{ $reels->firstItem() + $loop->index }}</td>
                                                   
                                                    <td>
                                                        <span class="font-weight-bold">{{ $reel->title }}</span>
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($reel->caption, 50) }}</small>
                                                    </td>
                                                    <td> @if($reel->category)
                                                            @php
                                                                // Get the category details with translation
                                                                $categoryDetails = $reel->category->details;
                                                            @endphp
                                                            {{ $categoryDetails->title ?? $reel->category->title ?? 'Uncategorized' }}
                                                        @else
                                                            <span class="text-muted">Uncategorized</span>
                                                        @endif</td>
                                                    <td style="width: 300px;padding: 10px;">
                                                            <video class="plyr reel-video" controls preload="metadata"
                                                                poster="{{ $reel->thumbnail_url }}">
                                                                <source src="{{ $reel->video_url }}" type="video/mp4"/>
                                                                {{ trans('public.browser_not_support_video') }}
                                                            </video>
                                                        </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span><i class="fa fa-eye"></i> {{ $reel->views_count }}</span>
                                                            <span><i class="fa fa-heart text-danger"></i> {{ $reel->likes_count }}</span>
                                                            <span><i class="fa fa-comment"></i> {{ $reel->comments_count }}</span>
                                                            <span><i class="fa fa-pen"></i> {{ $reel->getrate() }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ dateTimeFormat($reel->created_at, 'Y M j | H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <!-- Toggle Status -->
                                                            <!-- <a href="{{ getAdminPanelUrl() }}/reel/{{ $reel->id }}/toggle-status" 
                                                               class="btn-transparent text-warning mr-2" 
                                                               data-toggle="tooltip" 
                                                               title="Toggle Status">
                                                                <i class="fa fa-sync-alt"></i>
                                                            </a> -->

                                                            <!-- Edit -->
                                                            <a href="{{ getAdminPanelUrl() }}/reel/{{ $reel->id }}/edit" 
                                                               class="btn-transparent text-primary mr-2" 
                                                               data-toggle="tooltip" 
                                                               title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>

                                                            <!-- Delete -->
                                                            @include('admin.includes.delete_button', [
                                                                'url' => getAdminPanelUrl() . '/reel/' . $reel->id . '/delete',
                                                                'btnClass' => 'btn-transparent text-danger',
                                                                'tooltip' => 'Delete'
                                                            ])
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="mt-4">
                                    {{ $reels->appends(request()->input())->links() }}
                                </div>
                            @else
                                <div class="text-center p-5">
                                    <img src="/assets/default/img/no-data.png" alt="No data" style="width: 200px; opacity: 0.5;">
                                    <h4 class="mt-3">No Portals Found</h4>
                                    <p class="text-muted">Start by creating your first portals.</p>
                                    <a href="{{ getAdminPanelUrl() }}/reel/create" class="btn btn-success mt-2">
                                        <i class="fa fa-plus"></i> Create Portals
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                theme: 'default'
            });

            const players = Plyr.setup('.plyr', {
                controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
                settings: ['quality', 'speed'],
                quality: { default: 576, options: [4320, 2880, 2160, 1440, 1080, 720, 576, 480, 360, 240] }
            });

            players.forEach(function(player) {
                player.on('play', function() {
                    players.forEach(function(otherPlayer) {
                        if (otherPlayer !== player && !otherPlayer.paused) {
                            otherPlayer.pause();
                        }
                    });
                });
            });

            // Initialize Date Range Picker
            $('#date_range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                $('#from_date').val(picker.startDate.format('YYYY-MM-DD'));
                $('#to_date').val(picker.endDate.format('YYYY-MM-DD'));
            });

            $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#from_date').val('');
                $('#to_date').val('');
            });
        });
    </script>
@endpush