@extends('layouts.public')

@section('title', 'Community Announcements | Masjid Locator')

@section('content')
<div class="page-header bg-dark text-white py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Community Announcements</h1>
        <p class="lead mb-0">Stay updated with the latest news and updates from your community.</p>
    </div>
</div>

<div class="container py-5">
    @if(isset($announcements) && $announcements->count() > 0)
        <div class="row g-4">
            @foreach($announcements as $announcement)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $announcement->priority === 'high' ? 'bg-danger' : ($announcement->priority === 'normal' ? 'bg-primary' : 'bg-secondary') }}">
                                {{ ucfirst($announcement->priority ?? 'Normal') }} Priority
                            </span>
                            <small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                        </div>
                        <h4 class="card-title">{{ $announcement->title }}</h4>
                        <p class="card-text text-secondary">{{ Str::limit(strip_tags($announcement->content), 120) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $announcements->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5 rounded-4 shadow-sm">
            <i class="fas fa-bullhorn fa-3x mb-3 text-info"></i>
            <h4>No Announcements</h4>
            <p class="mb-0">There are currently no active announcements from the community.</p>
        </div>
    @endif
</div>
@endsection
