@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Lulu Books</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="mb-3">
        <a href="{{ route('books.create') }}" class="btn btn-primary">Create New Book</a>
    </div>
    
    <div class="row">
        @forelse($jobs['results'] ?? [] as $job)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $job['line_items'][0]['title'] ?? 'Untitled' }}</h5>
                        <p class="card-text">
                            Status: <span class="badge bg-info">{{ $job['status'] }}</span><br>
                            Created: {{ \Carbon\Carbon::parse($job['created'])->format('M d, Y') }}
                        </p>
                        <a href="{{ route('books.show', $job['id']) }}" class="btn btn-sm btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No books found. Create your first book!</div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if(isset($jobs['next']) || isset($jobs['previous']))
        <nav>
            <ul class="pagination">
                @if(isset($jobs['previous']))
                    <li class="page-item">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}">Previous</a>
                    </li>
                @endif
                @if(isset($jobs['next']))
                    <li class="page-item">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}">Next</a>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>
@endsection