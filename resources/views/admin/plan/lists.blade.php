@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Subscription Plans</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item">Subscription Plans</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ getAdminPanelUrl() }}/plan/create" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create New Plan
                            </a>
                            <!-- @can('admin_plan_create') -->
                            <!-- @endcan -->
                        </div>

                        <section class="card">
                            <div class="card-body">
                                <form action="{{ getAdminPanelUrl() }}/plan" method="get" class="mb-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                                <input name="search" type="text" class="form-control" 
                                                       value="{{ request()->get('search') }}" 
                                                       placeholder="Search by code, title or description...">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                                <div class="input-group">
                                                    <input type="date" id="from" class="text-center form-control" 
                                                           name="from" value="{{ request()->get('from') }}" 
                                                           placeholder="Start Date">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                                <div class="input-group">
                                                    <input type="date" id="to" class="text-center form-control" 
                                                           name="to" value="{{ request()->get('to') }}" 
                                                           placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.status') }}</label>
                                                <select name="status" class="form-control">
                                                    <option value="">All Status</option>
                                                    <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label">Min Price ({{ $currency }})</label>
                                                <input name="min_price" type="number" class="form-control" 
                                                       value="{{ request()->get('min_price') }}" 
                                                       min="0" step="0.01">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label">Max Price ({{ $currency }})</label>
                                                <input name="max_price" type="number" class="form-control" 
                                                       value="{{ request()->get('max_price') }}" 
                                                       min="0" step="0.01">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mt-1">
                                                <label class="input-label mb-4"> </label>
                                                <input type="submit" class="text-center btn btn-primary w-100" 
                                                       value="{{ trans('admin/main.show_results') }}">
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
                                        <!-- <th>#</th> -->
                                        <th class="text-left">Title</th>
                                        <th class="text-left">Code</th>
                                        <th class="text-left">Price</th>
                                        <th class="text-center">Duration</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">Updated At</th>
                                        <th>Actions</th>
                                    </tr>

                                    @foreach($plans as $plan)
                                        <tr>
                                            <!-- <td>{{ $plan->id }}</td> -->
                                            <td class="text-left">
                                                <strong>{{ $plan->code }}</strong>
                                            </td>
                                            <td class="text-left">{{ $plan->title }}</td>
                                            <td class="text-left">{{ $currency }}{{ number_format($plan->price, 2) }}</td>
                                            <td class="text-center">{{ $plan->duration_days }} days</td>
                                            <td class="text-center">
                                                @if($plan->is_membership)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ dateTimeFormat($plan->created_at, 'Y M j | H:i') }}</td>
                                            <td class="text-center">{{ dateTimeFormat($plan->updated_at, 'Y M j | H:i') }}</td>
                                            <td>
                                                <a href="{{ getAdminPanelUrl() }}/plan/{{ $plan->id }}/edit" 
                                                   class="btn-sm" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                
                                                <!-- <a href="{{ getAdminPanelUrl() }}/plan/{{ $plan->id }}/toggle-status" 
                                                   class="btn-sm" data-toggle="tooltip" data-placement="top" 
                                                   title="{{ $plan->is_membership ? 'Deactivate' : 'Activate' }}"
                                                   onclick="return confirm('Are you sure?')">
                                                    @if($plan->is_membership)
                                                        <i class="fa fa-times text-danger"></i>
                                                    @else
                                                        <i class="fa fa-check text-success"></i>
                                                    @endif
                                                </a> -->
                                                
                                                @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/plan/'. $plan->id.'/delete',
                                                    'btnClass' => 'btn-sm'
                                                ])
                                                <!-- @can('admin_plan_edit') -->
                                                <!-- @endcan -->
                                                <!-- @can('admin_plan_delete') -->
                                                <!-- @endcan -->
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if($plans->count() == 0)
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                <div class="text-muted">No plans found.</div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $plans->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection