@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Gift Reels</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item">Gift Reels</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            
                                <a href="{{ getAdminPanelUrl() }}/giftreel/create" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Create New Gift Reel
                                </a>
                                <!-- @can('admin_gift_reel_create') -->
                            <!-- @endcan -->
                        </div>
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>#</th>
                                        <!-- <th class="text-left">Thumbnail</th> -->
                                        <th class="text-left">Title</th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">Updated At</th>
                                        <th>Actions</th>
                                    </tr>

                                    @foreach($giftReels as $giftReel)
                                        <tr>
                                            <td>{{ $giftReel->id }}</td>
                                            <!-- <td class="text-left">
                                                @if($giftReel->thumbnail)
                                                    <img src="{{ $giftReel->thumbnail }}" width="80" height="60" alt="{{ $giftReel->title }}" style="object-fit: cover; border-radius: 8px;">
                                                @else
                                                    <span class="text-muted">No thumbnail</span>
                                                @endif
                                            </td> -->
                                            <td class="text-left">{{ $giftReel->title }}</td>
                                            <td class="text-center">{{ dateTimeFormat($giftReel->created_at, 'Y M j | H:i') }}</td>
                                            <td class="text-center">{{ dateTimeFormat($giftReel->updated_at, 'Y M j | H:i') }}</td>
                                            <td>
                                                 <a href="{{ getAdminPanelUrl() }}/giftreel/{{ $giftReel->id }}/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                     @include('admin.includes.delete_button',['url' => getAdminPanelUrl().'/giftreel/'. $giftReel->id.'/delete','btnClass' => 'btn-sm'])
                                                <!-- @can('admin_gift_reel_edit') -->
                                                 
                                                <!-- @endcan

                                                @can('admin_gift_reel_delete') -->
                                               
                                                <!-- @endcan -->
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if($giftReels->count() == 0)
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="text-muted">No gift reels found.</div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $giftReels->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection