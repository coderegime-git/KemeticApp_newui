@extends('admin.layouts.app')

@push('styles_top')
<style>
    .fade:not(.show) {
        opacity: 1 !important;
    }
</style>


@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{trans('admin/main.media_kits')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
            </div>
            <div class="breadcrumb-item">{{trans('admin/main.media_kits')}}</div>
        </div>
    </div>

    <div class="row">
        <div class='col-12 mb-3' style="text-align: end;">
            <!--<div data-bs-toggle="modal" data-bs-target="#toolModal" class=" text-center btn btn-primary" data-toggle="tooltip" title="Add Tool">-->
            <!--    <i class="fas fa-plus-circle fa-lg"></i>-->
            <!--    Add Tool-->
            <!--</div> -->
            <div data-bs-toggle="modal" data-bs-target="#uploadModal" class=" text-center btn btn-primary" data-toggle="tooltip" title="Add New">
                <i class="fas fa-plus-circle fa-lg"></i>
                Add New
            </div> 
        </div>
        
        {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{trans('admin/main.total_media_kit')}}</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalReviews }}
                        0
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-eye"></i></div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{trans('admin/main.published_media_kit')}}</h4>
                    </div>
                    <div class="card-body">
                        {{ $publishedReviews }}
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="card-wrap">
                      <div class="card-header">
                          <div class='d-flex gap_10 ' style="gap: 12px;">
                                <h4>{{ trans('admin/main.admin_media_kit') }}</h4>
                                 <a href="#" class="text-success" data-toggle="tooltip" title="Add New">
                                    <i class="fas fa-plus-circle fa-lg" style="font-size: 24px;"></i>
                                </a>
                          </div>
                     
                    </div>
                    <div class="card-body">
                       0
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>{{ trans('admin/main.admin_media_kit') }}</h4>
                        <a href="#" class="text-success" data-toggle="tooltip" title="Add New">
                            <i class="fas fa-plus-circle fa-lg"></i>
                        </a>
                    </div>
                    <div class="card-body">
                      0
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="section-body">
        {{-- <section class="card">
            <div class="card-body">
                <form method="get" class="mb-0">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.search')}}</label>
                                <input type="text" class="form-control" name="search" placeholder="" value="{{ request()->get('search') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                <div class="input-group">
                                    <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                <div class="input-group">
                                    <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.class')}}</label>
                                <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                        data-placeholder="Search classes">

                                    @if(!empty($webinars) and $webinars->count() > 0)
                                        @foreach($webinars as $webinar)
                                            <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.status')}}</label>
                                <select name="status" class="form-control populate">
                                    <option value="">{{trans('admin/main.all_status')}}</option>
                                    <option value="active" @if(request()->get('status') == 'active') selected @endif>{{trans('admin/main.published')}}</option>
                                    <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{trans('admin/main.hidden')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group mt-1">
                                <label class="input-label mb-4"> </label>
                                <input type="submit" class="text-center btn btn-primary w-100" value="{{trans('admin/main.show_results')}}">
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </section> --}}
        <section class="card">
            <div class="card-body overflow-auto">
                <table class="table table-striped font-14" id="datatable-details">

                    <tr>
                        <th class="text-left">{{trans('admin/main.user_name')}}</th>
                        <th class="text-left">{{trans('admin/main.title')}}</th>
                        <th class="">{{trans('admin/main.category')}}</th>
                        <th class="">{{trans('admin/main.course_link')}}</th>
                        <th class="">{{trans('admin/main.description')}}</th>
                        <th class="">{{trans('admin/main.video')}}</th>
                        <th class="">{{trans('admin/main.created_at')}}</th>
                        <th class="">{{trans('admin/main.status')}}</th>
                        <th class="">{{trans('admin/main.actions')}}</th>
                    </tr>
                    @foreach($mediaKit as $media)
                    @php
                    $user = App\Models\Api\User::find($media->user_id);
                    $category = App\Models\Translation\CategoryTranslation::find($media->category_id);
                    @endphp
                    <tr>
                        <td class="text-left">{{ $user->full_name }}</td>
                        <td class="text-left">{{ $media->title }}</td>
                        <td class="text-left">{{ $category->title }}</td>
                        <td><a href="{{ $media->course_link }}" target="_blank">View Course</a></td>
                       <td>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#descriptionModal" onclick="showDescription(`{{ htmlspecialchars($media->description, ENT_QUOTES, 'UTF-8') }}`)">
                                Show
                            </button>
                        </td>
                        <td>
                            <video width="200" controls>
                                <source src="{{asset($media->video_path)}}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($media->created_at)->format('d M Y | H:i') }}</td>
                        <td>
                            @if($media->status == 'active')
                                <b class="text-success">Published</b>
                            @else
                                <b class="text-warning">Hidden</b>
                            @endif
                        </td>

                        <td class="" width="50">
                            @can('admin_reviews_status_toggle')
                                <a href="{{ getAdminPanelUrl() }}/media/{{ $media->id }}/toggleStatus" class="btn-transparent text-primary mr-1" data-toggle="tooltip" data-placement="top" title="{{ ($media->status == 'active') ? 'Hidden' : 'Publish' }}">
                                    @if($media->status == 'active')
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    @else
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    @endif
                                </a>
                            @endcan
    
                            @can('admin_reviews_delete')
                                <form action="{{ getAdminPanelUrl() }}/media/{{ $media->id }}/delete" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-transparent" data-toggle="tooltip" title="Delete">
                                        <i class="fa fa-times text-primary"></i>
                                    </button>
                                </form>
                                {{-- @include('admin.includes.delete_button',['url' => getAdminPanelUrl().'/media/'. $media->id.'/delete','btnClass' => '']) --}}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>

            {{-- <div class="card-footer text-center">
                {{ $reviews->appends(request()->input())->links() }}
            </div> --}}
        </section>
    </div>
</section>

<div class="modal fade" id="reviewRateDetail" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactMessageLabel">{{trans('admin/main.view_rates_details')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                    <span class="font-weight-bold">{{ trans('product.content_quality') }}:</span>
                    <span class="js-content_quality"></span>
                </div>

                <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                    <span class="font-weight-bold">{{ trans('product.instructor_skills') }}:</span>
                    <span class="js-instructor_skills"></span>
                </div>

                <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                    <span class="font-weight-bold">{{ trans('product.purchase_worth') }}:</span>
                    <span class="js-purchase_worth"></span>
                </div>

                <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                    <span class="font-weight-bold">{{ trans('product.support_quality') }}:</span>
                    <span class="js-support_quality"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="contactMessage" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactMessageLabel">{{ trans('admin/main.message') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalDescription">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Media</h5>
                <button type="button" class="btn-close border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form Inside Modal -->
                <form action="/create-media" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-12 mt-10">
                            <label for="category" class="upload-kit-label">Category:</label>
                            <select id="category" name="category" class="upload-kit-input form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 col-12 mt-10">
                            <label for="title" class="upload-kit-label">Title:</label>
                            <input type="text" id="title" name="title" class="upload-kit-input form-control" required>
                        </div>

                        <div class="col-12 mt-10">
                            <label for="description" class="upload-kit-label">Description:</label>
                            <textarea id="description" name="description" class="upload-kit-input form-control" rows="4" required></textarea>
                        </div>

                        <div class="col-12 mt-10">
                            <label for="courseLink" class="upload-kit-label">Course Link:</label>
                            <input type="text" id="courseLink" name="courseLink" class="upload-kit-input form-control" required>
                        </div>

                        <div class="col-12 mt-10">
                            <label for="video" class="upload-kit-label">Upload Video:</label>
                            <input type="file" required id="video" name="video" class="upload-kit-input form-control" accept="video/mp4,video/webm,video/ogg">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="toolModal" tabindex="-1" aria-labelledby="toolModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Add New Tool</h5>
                <button type="button" class="btn-close border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form Inside Modal -->
                <form action="/admin/media-tools/create-mediaTool" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Tool Name Field -->
                        <div class="col-12 mt-10">
                            <label for="tool_name" class="upload-kit-label">Tool Name:</label>
                            <input type="text" id="tool_name" name="tool_name" class="upload-kit-input form-control">
                        </div>
                        
                        <!-- Tool Link Field -->
                        <div class="col-12 mt-10">
                            <label for="tool_link" class="upload-kit-label">Tool Link:</label>
                            <input type="text" id="tool_link" name="tool_link" class="upload-kit-input form-control">
                        </div>

                        <!-- Tool Icon Picker -->
                        <div class="col-12 mt-10">
                            <label class="upload-kit-label">Tool Icon:</label>
                            <div class="input-group">
                                <input class="form-control icp icp-auto" placeholder="Select Icon" type="text" id="icon" name="tool_icon"/>
                                <span class="input-group-text">
                                    <i class="fas fa-anchor" id="icon-preview"></i>
                                </span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
<link rel="stylesheet" href="https://itsjavi.com/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css">
<script src="https://itsjavi.com/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $('.icp-auto').iconpicker();
         $('.icp-auto').on('iconpickerSelected', function (event) {
            $('#icon-preview').attr('class', event.iconpickerValue);
        });
    });
</script>

<script>
    function showDescription(description) {
        document.getElementById("modalDescription").innerText = description;
    }
    
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-form").forEach((form) => {
            form.addEventListener("submit", function (e) {
                if (!confirm("Are you sure you want to delete this item?")) {
                    e.preventDefault();
                }
            });
        });
    });

</script>
@endpush
