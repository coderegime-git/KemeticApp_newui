@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ !empty($giftReel) ? 'Edit Gift Reel' : 'Create New Gift Reel' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl() }}/gift-reels">Gift Reels</a></div>
                <div class="breadcrumb-item">{{ !empty($giftReel) ? 'Edit' : 'Create' }}</div>
            </div>
        </div>

        <div class="section-body card">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card-body">
                        <form action="{{ getAdminPanelUrl() }}/financial/giftreel/{{ !empty($giftReel) ? $giftReel->id.'/update' : 'store' }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Title *</label>
                                <input type="text" name="title"
                                       class="form-control  @error('title') is-invalid @enderror"
                                       value="{{ !empty($giftReel) ? $giftReel->title : old('title') }}"
                                       placeholder="Enter gift reel title"
                                       required/>
                                @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group mt-15">
                                <label class="input-label">{{ trans('public.thumbnail_image') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="input-group-text admin-file-manager" data-input="thumbnail" data-preview="holder">
                                            <i class="fa fa-upload"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="thumbnail" id="thumbnail" value="{{ !empty($giftReel) ? $giftReel->thumbnail : old('thumbnail') }}" class="form-control @error('thumbnail')  is-invalid @enderror"/>
                                    <div class="input-group-append">
                                        <button type="button" class="input-group-text admin-file-view" data-input="thumbnail">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('thumbnail')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class=" mt-4">
                                <button type="submit" class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
