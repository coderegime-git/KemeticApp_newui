@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<style>
    /* Kemetic Cards */
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

    /* Statistics section */
    .kemetic-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 15px;
        border-radius: 12px;
        background: #2a2a2a;
        transition: 0.3s;
    }
    .kemetic-stat strong {
        font-size: 30px;
        color: #F3F3F3;
        margin-top: 5px;
    }
    .kemetic-stat span {
        font-size: 16px;
        color: #AAAAAA;
    }

    /* Table styles */
    .kemetic-table th, .kemetic-table td {
        border: none;
        color: #F3F3F3;
        vertical-align: middle !important;
    }
    .kemetic-table th {
        color: #AAAAAA;
    }
    .kemetic-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 10px;
    }
    .kemetic-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Dropdown buttons */
    .btn-transparent {
        background: transparent;
        border: none;
        color: #F3F3F3;
        padding: 5px;
    }
    .dropdown-menu {
        background: #2a2a2a;
        border-radius: 8px;
        padding: 10px;
    }
    .dropdown-menu a {
        color: #F3F3F3;
    }
    .dropdown-menu a:hover {
        color: #FF6B6B;
    }

    /* Buttons */
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
@endpush

@section('content')
<!-- Statistics -->
<section>
    <h2 class="section-title text-light">{{ trans('public.instructors') }}</h2>
    <div class="activities-container mt-25 p-20 p-lg-35 row">
        <div class="col-4">
            <div class="kemetic-stat">
                <img src="/assets/default/img/activity/48.svg" width="64" height="64" alt="">
                <strong>{{ $users->count() }}</strong>
                <span>{{ trans('public.instructors') }}</span>
            </div>
        </div>
        <div class="col-4">
            <div class="kemetic-stat">
                <img src="/assets/default/img/activity/49.svg" width="64" height="64" alt="">
                <strong>{{ $activeCount }}</strong>
                <span>{{ trans('public.active') }}</span>
            </div>
        </div>
        <div class="col-4">
            <div class="kemetic-stat">
                <img src="/assets/default/img/activity/89.svg" width="64" height="64" alt="">
                <strong>{{ $verifiedCount }}</strong>
                <span>{{ trans('public.verified') }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Filter Form -->
<section class="mt-35">
    <h2 class="section-title text-light">{{ trans('panel.filter_instructors') }}</h2>
    @include('web.default.panel.manage.filters') {{-- Make sure the included filter partial uses Kemetic styles --}}
</section>

<!-- Instructors Table -->
<section class="mt-35">
    <h2 class="section-title text-light">{{ trans('panel.instructors_list') }}</h2>

    @if(!empty($users) and !$users->isEmpty())
        <div class="panel-section-card py-20 px-25 mt-20 kemetic-card">
            <div class="table-responsive">
                <table class="table custom-table text-center kemetic-table">
                    <thead>
                        <tr>
                            <th class="text-left">{{ trans('auth.name') }}</th>
                            <th class="text-left">{{ trans('auth.email') }}</th>
                            <th>{{ trans('public.phone') }}</th>
                            <th>{{ trans('webinars.webinars') }}</th>
                            <th>{{ trans('panel.sales') }}</th>
                            <th>{{ trans('panel.sales_amount') }}</th>
                            <th>{{ trans('public.date') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="text-left d-flex align-items-center">
                                    <div class="kemetic-avatar">
                                        <img src="{{ $user->getAvatar() }}" alt="Avatar">
                                    </div>
                                    <div>
                                        <span class="d-block text-light font-weight-500">{{ $user->full_name }}</span>
                                        <span class="d-block font-12 {{ $user->verified ? 'text-primary' : 'text-warning' }}">
                                            {{ trans('public.'.($user->verified ? 'verified' : 'not_verified')) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-left">
                                    <span class="d-block text-light font-weight-500">{{ $user->email }}</span>
                                    <span class="d-block font-12 text-gray">id : {{ $user->id }}</span>
                                </td>
                                <td>{{ $user->mobile }}</td>
                                <td>{{ $user->webinars->count() }}</td>
                                <td>{{ $user->salesCount() }}</td>
                                <td>{{ handlePrice($user->sales()) }}</td>
                                <td>{{ dateTimeFormat($user->created_at,'j M Y | H:i') }}</td>
                                <td class="text-right">
                                    <div class="btn-group dropdown table-actions">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <i data-feather="more-vertical" height="20"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ $user->getProfileUrl() }}" class="d-block mt-10">{{ trans('public.profile') }}</a>
                                            @can('panel_organization_instructors_edit')
                                                <a href="/panel/manage/instructors/{{ $user->id }}/edit" class="d-block mt-10">{{ trans('public.edit') }}</a>
                                            @endcan
                                            @can('panel_organization_instructors_delete')
                                                <a href="/panel/manage/instructors/{{ $user->id }}/delete" class="d-block mt-10 delete-action">{{ trans('public.delete') }}</a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        @include(getTemplate() . '.includes.no-result',[
            'file_name' => 'teachers.png',
            'title' => trans('panel.instructors_no_result'),
            'hint' => nl2br(trans('panel.instructors_no_result_hint')),
            'btn' => ['url' => '/panel/manage/instructors/new','text' => trans('panel.add_an_instructor')]
        ])
    @endif
</section>

<div class="my-30">
    {{ $users->appends(request()->input())->links('vendor.pagination.panel') }}
</div>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/moment.min.js"></script>
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script>
    feather.replace();
</script>
@endpush
