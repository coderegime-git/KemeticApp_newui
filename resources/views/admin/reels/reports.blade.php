@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ $pageTitle }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
            </div>
            <div class="breadcrumb-item">{{ $pageTitle }}</div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped font-14">
                                <thead>
                                    <tr>
                                        <th>Portals</th>
                                        <th>Uploader</th>
                                        <th class="text-center">Reports</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">{{ trans('admin/main.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $reelId => $reelReports)
                                        @php
                                            $reel       = $reelReports->first()->reel;
                                            $count      = $reelReports->count();
                                            $flagged    = $count >= 15;
                                        @endphp
                                        <tr class="{{ $flagged ? 'table-danger' : '' }}">
                                            <td>
                                                @if($reel)
                                                    {{ $reel->title ?: '(no title) #'.$reelId }}
                                                @else
                                                    <span class="text-danger">Deleted Reel #{{ $reelId }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($reel && $reel->user)
                                                    {{ $reel->user->id }} – {{ $reel->user->full_name }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-{{ $flagged ? 'danger' : 'warning' }}">
                                                    {{ $count }} report{{ $count !== 1 ? 's' : '' }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                @if($flagged)
                                                    <span class="badge badge-danger">Needs review</span>
                                                @else
                                                    <span class="badge badge-secondary">Watching</span>
                                                @endif
                                            </td>

                                            <td class="text-center" style="white-space:nowrap">
                                                {{-- View individual reports --}}
                                                <a href="{{ route('admin.reports.reels.show', $reelId) }}"
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye mr-1"></i> View
                                                </a>

                                                @if($reel && $flagged)
                                                    {{-- Accept = delete reel --}}
                                                    <form method="POST"
                                                          action="{{ route('admin.reports.reels.accept', $reelId) }}"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Delete this reel?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-check mr-1"></i> Accept
                                                        </button>
                                                    </form>

                                                    {{-- Decline = dismiss all reports --}}
                                                    <form method="POST"
                                                          action="{{ route('admin.reports.reels.decline', $reelId) }}"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Dismiss all reports for this reel?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-times mr-1"></i> Decline
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No reel reports found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection