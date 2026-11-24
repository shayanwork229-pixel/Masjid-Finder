@extends('layouts.public')

@section('title', 'Hijri Islamic Calendar')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="card shadow-sm border-0 rounded-4 p-4">
          <h3 class="section-title">Islamic Calendar</h3>
          <p class="text-muted">Current Islamic date and upcoming community events.</p>
          <div class="p-4 rounded-4 bg-success text-white">
            <div class="small text-white-50">Today</div>
            <div class="h4 fw-bold">{{ $today->translatedFormat('d F Y') }}</div>
            <div class="small">Gregorian date</div>
          </div>
          <div class="mt-3 p-4 rounded-4 bg-light">
            <div class="small text-uppercase text-muted">Hijri Date</div>
            <div class="h5 fw-bold">{{ now()->format('d-m-Y') }}</div>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-4 p-4">
          <h4 class="mb-3">Upcoming Islamic Events</h4>
          <div class="list-group">
            @forelse($events as $event)
              <a href="{{ route('events.show', $event) }}" class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <h6 class="mb-1">{{ $event->title }}</h6>
                    <p class="small text-muted mb-0">{{ $event->description }}</p>
                  </div>
                  <span class="badge bg-success">{{ $event->event_date->format('d M Y') }}</span>
                </div>
              </a>
            @empty
              <div class="text-muted py-3">No upcoming events yet.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
