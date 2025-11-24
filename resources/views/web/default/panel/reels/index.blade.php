@extends('web.default.panel.layouts.panel_layout')

@push('styles_top')
<link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
<link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<!-- Plyr CSS -->
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<!-- Plyr JS -->
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
@endpush

@section('content')
<section class="mt-25">
    <h2 class="section-title">Reels</h2>
    <div class="mt-3">
        <a href="{{ route('reels.index') }}" class="btn btn-primary">Create Reel</a>
        <table class="table table-bordered bg-white mt-4">
            <tr>
                <th>Sl No</th>
                <th>Title</th>
                <th>Video</th>
                <th>Likes</th>
                <th>Comments</th>
                <th>Action</th>
            </tr>
            @foreach ($reels as $reel)
                <tr>
                    <td>{{ $reels->firstItem() + $loop->index }}</td>
                    <td>{{ $reel->title }}</td>
                    <td width=250>
                        <video class="reel-video plyr" controls preload="metadata" poster="{{ $reel->thumbnail_url }}">
                            <source src="{{ $reel->video_url }}" type="video/mp4" />
                            {{ trans('public.browser_not_support_video') }}
                        </video>
                    </td>                    
                    <td>{{ $reel->likes_count }}</td>
                    <td>{{ $reel->comments_count }}</td>
                    <td>
                        <form action="{{ route('dashboardreels.destroy', $reel->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reel?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i data-feather="trash-2" width="18"></i></button>
                        </form>                        
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="paginations">
            {{ $reels->links() }}
        </div>
    </div>
</section>
@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/select2/select2.min.js"></script>
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const players = Plyr.setup('.plyr');
    });
</script>

@endpush