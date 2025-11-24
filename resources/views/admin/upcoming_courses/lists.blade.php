@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.upcoming_courses') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ trans('update.total_courses') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalCourses }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-calendar-check"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ trans('update.released_courses') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $releasedCourses }}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-clock"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ trans('update.not_released') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $notReleased }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ trans('update.followers') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $followers }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.release_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('public.all') }}</option>
                                        <option value="newest" @if(request()->get('sort', null) == 'newest') selected="selected" @endif>{{ trans('public.newest') }}</option>
                                        <option value="earliest_publish_date" @if(request()->get('sort', null) == 'earliest_publish_date') selected="selected" @endif>{{ trans('update.earliest_publish_date') }}</option>
                                        <option value="farthest_publish_date" @if(request()->get('sort', null) == 'farthest_publish_date') selected="selected" @endif>{{ trans('update.farthest_publish_date') }}</option>
                                        <option value="highest_price" @if(request()->get('sort', null) == 'highest_price') selected="selected" @endif>{{ trans('update.highest_price') }}</option>
                                        <option value="lowest_price" @if(request()->get('sort', null) == 'lowest_price') selected="selected" @endif>{{ trans('update.lowest_price') }}</option>
                                        <option value="only_not_released_courses" @if(request()->get('sort', null) == 'only_not_released_courses') selected="selected" @endif>{{ trans('update.only_not_released_courses') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.instructor')}}</label>
                                    <select name="teacher_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="Search teachers">

                                        @if(!empty($teachers) and $teachers->count() > 0)
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" selected>{{ $teacher->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.category')}}</label>
                                    <select name="category_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_categories')}}</option>

                                        @foreach($categories as $category)
                                            @if(!empty($category->subCategories) and count($category->subCategories))
                                                <optgroup label="{{ $category->title }}">
                                                    @foreach($category->subCategories as $subCategory)
                                                        <option value="{{ $subCategory->id }}" @if(request()->get('category_id') == $subCategory->id) selected="selected" @endif>{{ $subCategory->title }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_status')}}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{trans('admin/main.pending_review')}}</option>
                                        <option value="active" @if(request()->get('status') == 'active') selected @endif>{{trans('admin/main.published')}}</option>
                                        <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>{{trans('admin/main.rejected')}}</option>
                                        <option value="is_draft" @if(request()->get('status') == 'is_draft') selected @endif>{{trans('admin/main.draft')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{trans('admin/main.show_results')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('admin_upcoming_courses_export_excel')
                                <div class="text-right">
                                    <a href="{{ getAdminPanelUrl('/upcoming_courses/excel?'. http_build_query(request()->all())) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th>{{trans('admin/main.id')}}</th>
                                        <th class="text-left">{{trans('admin/main.title')}}</th>
                                        <th class="text-left">{{trans('admin/main.instructor')}}</th>
                                        <th>{{trans('admin/main.type')}}</th>
                                        <th>{{trans('admin/main.price')}}</th>
                                        <th>{{trans('update.followers')}}</th>
                                        <th>{{trans('admin/main.start_date')}}</th>
                                        <th>{{trans('admin/main.created_at')}}</th>
                                        <th>{{trans('admin/main.status')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($upcomingCourses as $upcomingCourse)
                                        <tr class="text-center">
                                            <td>{{ $upcomingCourse->id }}</td>

                                            <td width="18%" class="text-left">
                                                <a class="text-primary mt-0 mb-1 font-weight-bold" href="{{ $upcomingCourse->getUrl() }}">{{ $upcomingCourse->title }}</a>
                                                @if(!empty($upcomingCourse->category->title))
                                                    <div class="text-small">{{ $upcomingCourse->category->title }}</div>
                                                @else
                                                    <div class="text-small text-warning">{{trans('admin/main.no_category')}}</div>
                                                @endif
                                            </td>

                                            <td class="text-left">{{ $upcomingCourse->teacher->full_name }}</td>

                                            <td class="">{{ trans('admin/main.'.$upcomingCourse->type) }}</td>

                                            <td>
                                                @if(!empty($upcomingCourse->price) and $upcomingCourse->price > 0)
                                                    <span class="mt-0 mb-1">
                                                        {{ handlePrice($upcomingCourse->price, true, true) }}
                                                    </span>
                                                @else
                                                    {{ trans('public.free') }}
                                                @endif
                                            </td>

                                            <td class="font-12">
                                                <a href="{{ getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/followers') }}" target="_blank" class="">{{ $upcomingCourse->followers_count }}</a>
                                            </td>

                                            <td class="font-12">{{ dateTimeFormat($upcomingCourse->publish_date, 'Y M j | H:i') }}</td>

                                            <td class="font-12">{{ dateTimeFormat($upcomingCourse->created_at, 'Y M j | H:i') }}</td>

                                            <td>
                                                @if(!empty($upcomingCourse->webinar_id))
                                                    <div class="text-success font-600-bold">{{ trans('update.released') }}</div>
                                                @else
                                                    @switch($upcomingCourse->status)
                                                        @case(\App\Models\Webinar::$active)
                                                            <div class="text-primary">{{ trans('admin/main.published') }}</div>
                                                            @break
                                                        @case(\App\Models\Webinar::$isDraft)
                                                            <span class="text-dark">{{ trans('admin/main.is_draft') }}</span>
                                                            @break
                                                        @case(\App\Models\Webinar::$pending)
                                                            <span class="text-warning">{{ trans('admin/main.waiting') }}</span>
                                                            @break
                                                        @case(\App\Models\Webinar::$inactive)
                                                            <span class="text-danger">{{ trans('public.rejected') }}</span>
                                                            @break
                                                    @endswitch
                                                @endif
                                            </td>

                                            <td width="200" class="">
                                                <div class="btn-group dropdown table-actions">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu text-left webinars-lists-dropdown">

                                                        @can('admin_upcoming_courses_edit')
                                                            @if($upcomingCourse->status != \App\Models\Webinar::$active)
                                                                @include('admin.includes.delete_button',[
                                                                    'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/approve'),
                                                                    'btnClass' => 'd-flex align-items-center text-success text-decoration-none btn-transparent btn-sm mt-1',
                                                                    'btnText' => '<i class="fa fa-times"></i><span class="ml-2">'. trans("admin/main.approve") .'</span>'
                                                                    ])
                                                            @endif

                                                            @if($upcomingCourse->status == \App\Models\Webinar::$pending)
                                                                @include('admin.includes.delete_button',[
                                                                    'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/reject'),
                                                                    'btnClass' => 'd-flex align-items-center text-danger text-decoration-none btn-transparent btn-sm mt-1',
                                                                    'btnText' => '<i class="fa fa-times"></i><span class="ml-2">'. trans("admin/main.reject") .'</span>'
                                                                    ])
                                                            @endif

                                                            @if($upcomingCourse->status == \App\Models\Webinar::$active)
                                                                @include('admin.includes.delete_button',[
                                                                    'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/unpublish'),
                                                                    'btnClass' => 'd-flex align-items-center text-danger text-decoration-none btn-transparent btn-sm mt-1',
                                                                    'btnText' => '<i class="fa fa-times"></i><span class="ml-2">'. trans("admin/main.unpublish") .'</span>'
                                                                    ])
                                                            @endif
                                                        @endcan

                                                        @can('admin_upcoming_courses_followers')
                                                            <a href="{{ getAdminPanelUrl() }}/upcoming_courses/{{ $upcomingCourse->id }}/followers" target="_blank" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm text-primary mt-1 " title="{{ trans('update.followers') }}">
                                                                <i class="fa fa-users"></i>
                                                                <span class="ml-2">{{ trans('update.followers') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_upcoming_courses_edit')
                                                            <a href="{{ getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/edit') }}" target="_blank" class="d-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm text-primary mt-1 " title="{{ trans('admin/main.edit') }}">
                                                                <i class="fa fa-edit"></i>
                                                                <span class="ml-2">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinars_delete')
                                                            @include('admin.includes.delete_button',[
                                                                    'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/delete'),
                                                                    'btnClass' => 'd-flex align-items-center text-dark text-decoration-none btn-transparent btn-sm mt-1',
                                                                    'btnText' => '<i class="fa fa-times"></i><span class="ml-2">'. trans("admin/main.delete") .'</span>'
                                                                    ])
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $upcomingCourses->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
