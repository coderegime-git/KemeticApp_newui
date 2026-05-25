@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Reports for: {{ $reel->title ?: 'Portal #'.$reel->id }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('admin.reports.reels') }}">Portal Reports</a>
            </div>
            <div class="breadcrumb-item">Detail</div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Reel summary card --}}
        <div class="card mb-3">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">{{ $reel->title ?: '(no title)' }}</h5>
                    <small class="text-muted">
                        Uploaded by: {{ $reel->user->full_name ?? 'Unknown' }}
                        &nbsp;·&nbsp; Total reports: <strong>{{ $reports->total() }}</strong>
                    </small>
                </div>

                @can('admin_reel_reports_delete')
                <div class="d-flex gap-2">
                    <form method="POST"
                          action="{{ route('admin.reports.reels.accept', $reel->id) }}"
                          onsubmit="return confirm('Delete reel?')">
                        @csrf
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-check mr-1"></i> Accept (delete reel)
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('admin.reports.reels.decline', $reel->id) }}"
                          onsubmit="return confirm('Dismiss all reports?')">
                        @csrf
                        <button class="btn btn-success btn-sm">
                            <i class="fas fa-times mr-1"></i> Decline (dismiss all)
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>

        {{-- Individual reports --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped font-14">
                        <thead>
                            <tr>
                                <th>{{ trans('admin/main.user') }}</th>
                                <th class="text-center">{{ trans('product.reason') }}</th>
                                <th class="text-center">Message</th>
                                <th class="text-center">{{ trans('public.date') }}</th>
                                <th class="text-center">{{ trans('admin/main.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td>
                                    @if($report->user)
                                        {{ $report->user->id }} – {{ $report->user->full_name }}
                                    @else
                                        <span class="text-danger">Deleted User</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ $report->reason ?? '—' }}
                                </td>

                                <td class="text-center" style="max-width:260px">
                                    @if(!empty($report->message))
                                        <button type="button"
                                                class="js-show-description btn btn-sm btn-outline-primary">
                                            {{ trans('admin/main.show') }}
                                        </button>
                                        <input type="hidden" class="report-reason"
                                               value="{{ nl2br($report->reason ?? '') }}">
                                        <input type="hidden" class="report-description"
                                               value="{{ nl2br($report->message) }}">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ dateTimeFormat($report->created_at, 'j M Y | H:i') }}
                                </td>

                                <td class="text-center">
                                        @include('admin.includes.delete_button', [
                                            'url'      => route('admin.reports.reels.delete', $report->id),
                                            'btnClass' => 'btn-sm',
                                        ])
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                {{ $reports->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</section>

{{-- Reason/Message Modal (reuse same JS) --}}
<div class="modal fade" id="reportMessage" tabindex="-1" aria-labelledby="reportMessageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportMessageLabel">{{ trans('panel.report') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="font-weight-bold js-reason">
                    {{ trans('product.reason') }}: <span class="font-weight-light"></span>
                </h5>
                <div class="mt-2 js-description">
                    <h5 class="font-weight-bold">{{ trans('site.message') }}:</h5>
                    <p class="mt-2"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('admin/main.close') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/admin/webinar_reports.min.js"></script>
@endpush