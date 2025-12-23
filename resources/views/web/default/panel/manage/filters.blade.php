<style>
    .kemetic-card {
        background: #1E1E1E;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: 0.3s;
    }
    .kemetic-card:hover {
        background: #2a2a2a;
    }

    .kemetic-form .form-control {
        background: #2a2a2a;
        border: 1px solid #444;
        color: #F3F3F3;
        border-radius: 8px;
    }
    .kemetic-form .form-control:focus {
        border-color: #666;
        box-shadow: none;
    }
    .kemetic-form label {
        color: #F3F3F3;
        font-weight: 500;
    }

    .btn-kemetic-primary {
        background: #FF6B6B;
        border: none;
        color: #FFF;
        border-radius: 8px;
        transition: 0.3s;
    }
    .btn-kemetic-primary:hover {
        background: #FF5252;
    }
</style>
<div class="panel-section-card py-20 px-25 mt-20 kemetic-card">
    <form action="/panel/manage/{{ $user_type }}" method="get" class="row kemetic-form">
        <!-- Date Filters -->
        <div class="col-12 col-lg-4">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label>{{ trans('public.from') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i data-feather="calendar" width="18" height="18"></i>
                                </span>
                            </div>
                            <input type="text" name="from" autocomplete="off" value="{{ request()->get('from') }}" 
                                class="form-control {{ !empty(request()->get('from')) ? 'datepicker' : 'datefilter' }}"/>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label>{{ trans('public.to') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i data-feather="calendar" width="18" height="18"></i>
                                </span>
                            </div>
                            <input type="text" name="to" autocomplete="off" value="{{ request()->get('to') }}" 
                                class="form-control {{ !empty(request()->get('to')) ? 'datepicker' : 'datefilter' }}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info Filters -->
        <div class="col-12 col-lg-6">
            <div class="row">
                <div class="col-12 col-lg-5">
                    <div class="form-group">
                        <label>{{ trans('auth.name') }}</label>
                        <input type="text" name="name" value="{{ request()->get('name',null) }}" class="form-control"/>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label>{{ trans('auth.email') }}</label>
                        <input type="text" name="email" value="{{ request()->get('email',null) }}" class="form-control"/>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="form-group">
                        <label>{{ trans('public.type') }}</label>
                        <select name="type" class="form-control">
                            <option>{{ trans('public.all') }}</option>
                            <option value="active" @if(request()->get('type',null) == 'active') selected @endif>{{ trans('public.active') }}</option>
                            <option value="inactive" @if(request()->get('type',null) == 'inactive') selected @endif>{{ trans('public.inactive') }}</option>
                            <option value="verified" @if(request()->get('type',null) == 'verified') selected @endif>{{ trans('public.verified') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
            <button type="submit" class="btn btn-kemetic-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
        </div>
    </form>
</div>

@push('scripts_bottom')
<script>
    feather.replace();
</script>
@endpush
