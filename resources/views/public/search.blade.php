@extends('layouts.public')

@section('title', 'Search Results | Masjid Locator')

@section('content')
<div class="page-header bg-dark text-white py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Search Results</h1>
        @if($query)
            <p class="lead mb-0">Showing results for: <strong>"{{ $query }}"</strong></p>
        @else
            <p class="lead mb-0">Enter a keyword to search.</p>
        @endif
    </div>
</div>

<div class="container py-5">
    <form action="{{ route('search') }}" method="GET" class="mb-5 max-w-lg mx-auto d-flex gap-2">
        <input type="text" name="q" class="form-control form-control-lg rounded-pill px-4" placeholder="Search masjids, events, announcements..." value="{{ $query }}" required>
        <button type="submit" class="btn btn-success btn-lg rounded-pill px-4"><i class="fas fa-search"></i> Search</button>
    </form>

    @if($query)
        <div class="row g-4">
            <div class="col-12">
                <h4 class="fw-bold mb-3 border-bottom pb-2">Masjids ({{ $masjids->count() }})</h4>
                @if($masjids->count())
                    <div class="row g-3">
                        @foreach($masjids as $masjid)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <h5 class="card-title text-success fw-bold">{{ $masjid->name }}</h5>
                                        <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt"></i> {{ $masjid->area }}, {{ $masjid->city }}</p>
                                        <a href="{{ route('masjids.show', $masjid) }}" class="btn btn-sm btn-outline-custom mt-2">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No masjids found matching your query.</p>
                @endif
            </div>

            <div class="col-12 mt-5">
                <h4 class="fw-bold mb-3 border-bottom pb-2">Events ({{ $events->count() }})</h4>
                @if($events->count())
                    <div class="row g-3">
                        @foreach($events as $event)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0 border-start border-4 border-primary">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                                        <p class="small text-muted mb-2"><i class="fas fa-calendar"></i> {{ $event->event_date->format('M d, Y') }}</p>
                                        <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary mt-2">View Event</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No events found matching your query.</p>
                @endif
            </div>

            <div class="col-12 mt-5">
                <h4 class="fw-bold mb-3 border-bottom pb-2">Announcements ({{ $announcements->count() }})</h4>
                @if($announcements->count())
                    <div class="row g-3">
                        @foreach($announcements as $announcement)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0 bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-dark">{{ $announcement->title }}</h5>
                                        <p class="small text-muted mb-2"><i class="fas fa-clock"></i> {{ $announcement->created_at->diffForHumans() }}</p>
                                        <p class="small mb-0">{{ Str::limit(strip_tags($announcement->content), 80) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No announcements found matching your query.</p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
