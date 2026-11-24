@extends('layouts.public')

@section('title', 'Donation Campaigns')

@section('content')
<section class="breadcrumb-section">
  <div class="container">
    <h2 class="breadcrumb-title">Donation Campaigns</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Campaigns</li>
      </ol>
    </nav>
  </div>
</section>

<div class="container my-5 animate-fadeInUp">
  <div class="row align-items-center mb-4">
    <div class="col-md-8">
      <h3 class="section-title mb-1">Support Active Campaigns</h3>
      <p class="section-subtitle">Select a campaign and contact us via WhatsApp to complete your donation.</p>
    </div>
    <div class="col-md-4 text-md-end">
      <a href="{{ route('masjids.index') }}" class="btn btn-outline-custom btn-sm">Browse Masajid</a>
    </div>
  </div>

  <div class="row g-4">
    @forelse($campaigns as $campaign)
      <div class="col-lg-4 col-md-6">
        <div class="masjid-card h-100">
          @if($campaign->image_path)
            <img src="{{ asset('storage/'.$campaign->image_path) }}" class="card-img-top" alt="{{ $campaign->title }}" onerror="this.onerror=null;this.src='{{ asset('assets/img/donation-placeholder.jpg') }}'">
          @else
            <div class="card-img-top">
              <i class="fas fa-hand-holding-heart"></i>
            </div>
          @endif
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge badge-sect badge-{{ $campaign->masjid->sect ? strtolower($campaign->masjid->sect) : 'sunni' }}">{{ $campaign->masjid->sect }}</span>
              <div class="text-end">
                @if($campaign->is_emergency)
                  <span class="badge bg-danger text-white">URGENT</span>
                @endif
                @if($campaign->is_featured)
                  <span class="badge bg-warning text-dark">Featured</span>
                @endif
              </div>
            </div>
            <h5 class="card-title mb-2">{{ $campaign->title }}</h5>
            <p class="text-muted small mb-3 line-clamp-3">{{ $campaign->description ?: 'No additional details provided for this campaign.' }}</p>

            <div class="progress mb-3" style="height: 12px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $campaign->percentage_completed }}%;" aria-valuenow="{{ $campaign->percentage_completed }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex justify-content-between small text-secondary mb-3">
              <div><strong>{{ $campaign->progress_label }}</strong> completed</div>
              <div><strong>Left:</strong> ₹{{ number_format($campaign->remaining_amount, 2) }}</div>
            </div>
            <div class="mb-3 small text-secondary">
              <div><strong>Masjid:</strong> {{ $campaign->masjid->name }}</div>
              <div><strong>Status:</strong> {{ ucfirst($campaign->status) }}</div>
            </div>
            <div class="mt-auto d-grid gap-2">
              <a href="{{ route('donations.show', $campaign) }}" class="btn btn-outline-custom w-100">View Campaign</a>
              <a href="https://wa.me/92338956547?text={{ urlencode("Assalamu Alaikum,\n\nI would like to donate.\n\nMasjid: {$campaign->masjid->name}\n\nCampaign: {$campaign->title}\n\nDonation Amount: PKR ") }}" class="btn btn-success w-100" target="_blank" rel="noopener noreferrer">
                Donate via WhatsApp
              </a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <i class="fas fa-heart fa-3x text-muted mb-3"></i>
        <h4 class="text-secondary fw-bold">No donation campaigns available</h4>
        <p class="text-muted">Please check back later or contact the administration for support opportunities.</p>
      </div>
    @endforelse
  </div>
</div>
@endsection
