<section class="card">
    <div class="card-body">
        <form method="get" class="mb-0">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.search')}}</label>
                        <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.role')}}</label>

                        <select name="role" class="form-control populate">
                            <option value="">{{trans('admin/main.all_roles')}}</option>
                            @foreach(['instructor', 'organization', 'student'] as $role)
                                <option value="{{ $role }}" {{ (request()->get('role') == $role) ? 'selected' : '' }}>{{ trans('admin/main.'.$role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{--<div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('update.minimum_amount') }}</label>

                        <input type="number" class="text-center form-control" name="minimum_amount" value="{{ request()->get('minimum_amount') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('update.maximum_amount') }}</label>

                        <input type="number" class="text-center form-control" name="maximum_amount" value="{{ request()->get('maximum_amount') }}">
                    </div>
                </div>--}}


                @php
                    $filters = ['items_asc', 'items_desc']; /* 'amount_asc', 'amount_desc', */
                    // It is not possible to specify the minimum and maximum amount of shopping cart items for each user in the query
                @endphp
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.filters')}}</label>
                        <select name="sort" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{trans('admin/main.all')}}</option>

                            @foreach($filters as $filter)
                                <option value="{{ $filter }}" @if(request()->get('sort') == $filter) selected @endif>{{trans('update.'.$filter)}}</option>
                            @endforeach
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
