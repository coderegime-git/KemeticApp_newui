@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Portals Categories</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">Portals Categories</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                @if(!empty($reelCategories) && $reelCategories->count() > 0)
                                    <li class="nav-item">
                                        <a class="nav-link {{ (!empty($errors) && $errors->has('title')) ? '' : 'active' }}" 
                                           id="categories-tab" data-toggle="tab" href="#categories" role="tab" 
                                           aria-controls="categories" aria-selected="true">
                                           {{ trans('admin/main.categories') }}
                                        </a>
                                    </li>
                                @endif
                        
                                <li class="nav-item">
                                    <a class="nav-link {{ ((!empty($errors) && $errors->has('title')) || !empty($editCategory)) ? 'active' : '' }}" 
                                       id="newCategory-tab" data-toggle="tab" href="#newCategory" role="tab" 
                                       aria-controls="newCategory" aria-selected="true">
                                       {{ trans('admin/main.create_category') }}
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                @if(!empty($reelCategories) && $reelCategories->count() > 0)
                                    <div class="tab-pane mt-3 fade {{ (!empty($errors) && $errors->has('title')) ? '' : 'active show' }}" 
                                         id="categories" role="tabpanel" aria-labelledby="categories-tab">
                                        <div class="table-responsive">
                                            <table class="table table-striped font-14">
                                                <tr>
                                                    <th class="text-left">{{ trans('admin/main.title') }}</th>
                                                    <th class="text-center">Portals</th>
                                                    <th>{{ trans('admin/main.action') }}</th>
                                                </tr>

                                                @foreach($reelCategories as $category)
                                                    <tr>
                                                        <td class="text-left">{{ $category->title }}</td>
                                                        <td class="text-center">{{ $category->reels_count }}</td>
                                                        <td>
                                                            <a href="{{ getAdminPanelUrl() }}/reel/categories/{{ $category->id }}/edit" 
                                                               class="btn-transparent text-primary" 
                                                               data-toggle="tooltip" 
                                                               data-placement="top" 
                                                               title="{{ trans('admin/main.edit') }}">
                                                                <i class="fa fa-edit"></i>
                                                            </a>

                                                            @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl('/reel/categories/'. $category->id .'/delete'), 
                                                                'btnClass' => ''
                                                            ])
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="tab-pane mt-3 fade {{ ((!empty($errors) && $errors->has('title')) || !empty($editCategory)) ? 'active show' : '' }}" 
                                     id="newCategory" role="tabpanel" aria-labelledby="newCategory-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <form action="{{ getAdminPanelUrl() }}/reel/categories/{{ !empty($editCategory) ? $editCategory->id.'/update' : 'store' }}" method="post">
                                                @csrf

                                                @if(!empty(getGeneralSettings('content_translate')) && !empty($userLanguages))
                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('auth.language') }}</label>
                                                        <select name="locale" class="form-control {{ !empty($editCategory) ? 'js-edit-content-locale' : '' }}">
                                                            @foreach($userLanguages as $lang => $language)
                                                                <option value="{{ $lang }}" 
                                                                        @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>
                                                                    {{ $language }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('locale')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @else
                                                    <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                                @endif

                                                <div class="form-group">
                                                    <label>{{ trans('admin/main.title') }}</label>
                                                    <input type="text" 
                                                           name="title"
                                                           class="form-control @error('title') is-invalid @enderror"
                                                           value="{{ !empty($editCategory) ? $editCategory->title : old('title') }}"
                                                           placeholder="{{ trans('admin/main.choose_title') }}" />
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-success">
                                                    {{ trans('admin/main.save_change') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script>
        $(document).ready(function() {
            // Add JavaScript for locale change if needed
            $('.js-edit-content-locale').on('change', function() {
                const locale = $(this).val();
                const currentUrl = window.location.href;
                const url = new URL(currentUrl);
                url.searchParams.set('locale', locale);
                window.location.href = url.toString();
            });
        });
    </script>
@endpush