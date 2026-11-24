@extends('layouts.public')

@section('title', 'Quran Classes | Masjid Locator')

@section('content')
<div class="page-header bg-success text-white py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Quran Classes</h1>
        <p class="lead mb-0">Learn to read and recite the Holy Quran with proper Tajweed.</p>
    </div>
</div>

<div class="container py-5">
    @if(isset($classes) && $classes->count())
      <div class="row g-4">
        @foreach($classes as $class)
          <div class="col-md-6 col-lg-4">
            <div class="masjid-card h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h5 class="card-title mb-0">{{ $class->title }}</h5>
                  @if($class->is_online)
                    <span class="badge bg-info">Online</span>
                  @endif
                </div>
                @if($class->teacher_name)
                  <p class="small text-muted mb-2"><i class="fas fa-chalkboard-teacher me-1"></i>{{ $class->teacher_name }}</p>
                @endif
                @if($class->schedule)
                  <p class="small text-muted mb-2"><i class="fas fa-clock me-1"></i>{{ $class->schedule }}</p>
                @endif
                @if($class->location)
                  <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1"></i>{{ $class->location }}</p>
                @endif
                @if($class->target_audience)
                  <p class="small mb-2"><span class="badge bg-success-subtle text-success">{{ $class->target_audience }}</span></p>
                @endif
                <p class="card-text text-secondary small mb-0">{{ $class->description ?: 'Class details will be shared by the instructor.' }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-center">
        <div class="alert alert-success py-5 shadow-sm rounded-4">
            <i class="fas fa-book-quran fa-4x mb-4 text-success"></i>
            <h3>Quran Classes Registration</h3>
            <p class="lead">We are currently organizing our upcoming Quran classes for adults and children.</p>
            <p>Please check back soon for the schedule and registration details, or contact your local masjid for immediate inquiries.</p>
            <a href="{{ route('contact') }}" class="btn btn-success mt-3 px-4 py-2 rounded-pill"><i class="fas fa-envelope me-2"></i>Contact Us</a>
        </div>
      </div>
    @endif
</div>
@endsection
