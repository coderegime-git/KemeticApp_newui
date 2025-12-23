@extends('web.default.layouts.newapp')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">

<style>
/* ===============================
   KEMETIC BASE STYLES
================================ */
.kemetic-card {
    background: #141414;
    border: 1px solid #2a2a2a;
    border-radius: 14px;
    padding: 22px;
}

.kemetic-title {
    color: #f3f3f3;
}

/* ===============================
   STATS CARDS
================================ */
.kemetic-stat {
    background: #1e1e1e;
    border-radius: 14px;
    padding: 20px;
    text-align: center;
    transition: 0.3s;
}
.kemetic-stat:hover {
    background: #242424;
}
.kemetic-stat strong {
    font-size: 30px;
    color: #ffffff;
}
.kemetic-stat span {
    font-size: 15px;
    color: #aaaaaa;
}

/* ===============================
   TABLE
================================ */
.kemetic-table th {
    color: #9a9a9a;
    border-bottom: 1px solid #2a2a2a;
}
.kemetic-table td {
    color: #f1f1f1;
    border-top: 1px solid #1f1f1f;
}
.kemetic-table tr:hover {
    background: #1e1e1e;
}

/* ===============================
   AVATAR
================================ */
.kemetic-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    overflow: hidden;
    background: #2a2a2a;
}
.kemetic-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===============================
   DROPDOWN
================================ */
.dropdown-menu {
    background: #1e1e1e;
    border-radius: 10px;
    border: 1px solid #2a2a2a;
}
.dropdown-menu a {
    color: #f3f3f3;
}
.dropdown-menu a:hover {
    color: #ff6b6b;
}

/* ===============================
   STATUS
================================ */
.kemetic-active {
    color: #4caf50;
}
.kemetic-inactive {
    color: #ff6b6b;
}
</style>
@endpush

@section('content')

<!-- ===============================
     STATS
================================ -->
<section>
    <h2 class="section-title kemetic-title">{{ trans('quiz.students') }}</h2>

    <div class="activities-container mt-25 p-20 p-lg-35">
        <div class="row">
            <div class="col-4">
                <div class="kemetic-stat">
                    <img src="/assets/default/img/activity/48.svg" width="56">
                    <strong>{{ $users->count() }}</strong>
                    <span>{{ trans('quiz.students') }}</span>
                </div>
            </div>

            <div class="col-4">
                <div class="kemetic-stat">
                    <img src="/assets/default/img/activity/49.svg" width="56">
                    <strong>{{ $activeCount }}</strong>
                    <span>{{ trans('public.active') }}</span>
                </div>
            </div>

            <div class="col-4">
                <div class="kemetic-stat">
                    <img src="/assets/default/img/activity/60.svg" width="56">
                    <strong>{{ $inActiveCount }}</strong>
                    <span>{{ trans('public.inactive') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===============================
     FILTER
================================ -->
<section class="mt-35">
    <h2 class="section-title kemetic-title">{{ trans('panel.filter_students') }}</h2>
    @include('web.default.panel.manage.filters')
</section>

<!-- ===============================
     STUDENTS TABLE
================================ -->
<section class="mt-35">
    <h2 class="section-title kemetic-title">{{ trans('panel.students_list') }}</h2>

    @if(!empty($users) and !$users->isEmpty())
        <div class="kemetic-card mt-20">
            <div class="table-responsive">
                <table class="table kemetic-table text-center">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('auth.name') }}</th>
                        <th class="text-left">{{ trans('auth.email') }}</th>
                        <th>{{ trans('public.phone') }}</th>
                        <th>{{ trans('webinars.webinars') }}</th>
                        <th>{{ trans('quiz.quizzes') }}</th>
                        <th>{{ trans('panel.certificates') }}</th>
                        <th>{{ trans('public.date') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($users as $user)
                        <tr>
                            <!-- NAME -->
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <div class="kemetic-avatar mr-10">
                                        <img src="{{ $user->getAvatar() }}">
                                    </div>
                                    <div>
                                        <div class="font-weight-500">{{ $user->full_name }}</div>
                                        <div class="font-12 {{ $user->status == 'active' ? 'kemetic-active' : 'kemetic-inactive' }}">
                                            {{ trans('public.' . $user->status) }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- EMAIL -->
                            <td class="text-left">
                                <div class="font-weight-500">{{ $user->email }}</div>
                                <div class="font-12 text-muted">ID : {{ $user->id }}</div>
                            </td>

                            <!-- PHONE -->
                            <td>{{ $user->mobile }}</td>

                            <!-- COURSES -->
                            <td>{{ count($user->getPurchasedCoursesIds()) }}</td>

                            <!-- QUIZZES -->
                            <td>{{ count($user->getActiveQuizzesResults()) }}</td>

                            <!-- CERTIFICATES -->
                            <td>{{ count($user->certificates) }}</td>

                            <!-- DATE -->
                            <td>{{ dateTimeFormat($user->created_at,'j M Y | H:i') }}</td>

                            <!-- ACTIONS -->
                            <td class="text-right">
                                <div class="btn-group dropdown">
                                    <button class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{{ $user->getProfileUrl() }}" class="d-block mt-10">{{ trans('public.profile') }}</a>
                                        <a href="/panel/manage/students/{{ $user->id }}/edit" class="d-block mt-10">{{ trans('public.edit') }}</a>
                                        <a href="/panel/manage/students/{{ $user->id }}/delete" class="d-block mt-10 delete-action">{{ trans('public.delete') }}</a>
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
        @include(getTemplate().'.includes.no-result',[
            'file_name' => 'studentt.png',
            'title' => trans('panel.students_no_result'),
            'hint' => nl2br(trans('panel.students_no_result_hint')),
            'btn' => ['url' => '/panel/manage/students/new','text' => trans('panel.add_an_student')]
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
