@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Books</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
                <div class="breadcrumb-item">Books</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                           
                                <a href="{{ getAdminPanelUrl() }}/book/create" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Create New Book
                                </a>
                                 <!-- @can('admin_book_create') -->
                            <!-- @endcan -->
                        </div>


                        <section class="card">
                            <div class="card-body">
                                <form action="{{ getAdminPanelUrl() }}/book" method="get" class="mb-0">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                                <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                                <div class="input-group">
                                                    <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                                <div class="input-group">
                                                    <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.category') }}</label>
                                                <select name="category_id" data-plugin-selectTwo class="form-control populate">
                                                    <option value="">{{ trans('admin/main.all_categories') }}</option>

                                                    @foreach($bookCategories as $category)
                                                        <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.author') }}</label>
                                                <select name="author_id" data-plugin-selectTwo class="form-control populate">
                                                    <option value="">{{ trans('admin/main.all_authors') }}</option>

                                                    @foreach($authors as $author)
                                                        <option value="{{ $author->id }}" @if(request()->get('author_id') == $author->id) selected="selected" @endif>{{ $author->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                
                                        <div class="col-md-3">
                                            <div class="form-group mt-1">
                                                <label class="input-label mb-4"> </label>
                                                <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('admin/main.show_results') }}">
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
                                        <th>#</th>
                                        <th class="text-left">Title</th>
                                        <th class="text-left">Price</th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">Updated At</th>
                                        <th>Actions</th>
                                    </tr>

                                    @foreach($books as $book)
                                        @php
                                            $translation = $book->translation;
                                        @endphp
                                        <tr>
                                            <td>{{ $book->id }}</td>
                                            <td class="text-left">
                                                {{ $translation ? $translation->title : 'No translation' }}
                                            </td>
                                            <td class="text-left">{{ $book->price }}</td>
                                            <td class="text-center">{{ dateTimeFormat($book->created_at, 'Y M j | H:i') }}</td>
                                            <td class="text-center">{{ dateTimeFormat($book->updated_at, 'Y M j | H:i') }}</td>
                                            <td>
                                                <a href="{{ getAdminPanelUrl() }}/book/{{ $book->id }}/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                </a>
                                                @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/book/'. $book->id.'/delete',
                                                    'btnClass' => 'btn-sm'
                                                ])
                                                <!-- @can('admin_book_edit') -->
                                                    
                                                <!-- @endcan -->

                                                <!-- @can('admin_book_delete') -->
                                                   
                                                <!-- @endcan -->
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if($books->count() == 0)
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="text-muted">No books found.</div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $books->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection