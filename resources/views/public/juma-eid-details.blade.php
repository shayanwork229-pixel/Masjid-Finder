@extends('layouts.public')

@section('title', 'Juma & Eid Details')

@php
  $directions = $masjid->latitude && $masjid->longitude
    ? 'https://www.google.com/maps/dir/?api=1&destination=' . $masjid->latitude . ',' . $masjid->longitude
    : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($masjid->address ?: $masjid->name);
  $facilityNames = $masjid->facilities?->pluck('name')->all() ?? [];
@endphp

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

<style>
.juma-detail-page {
  --emerald: #063f2e;
  --emerald-2: #0b6b4f;
  --gold: #d7b56d;
  --soft: #f3faf5;
  background: linear-gradient(180deg, #f8fcf9, #eef7f1 55%, #fff);
}
.juma-detail-hero {
  position: relative;
  overflow: hidden;
  min-height: 520px;
  display: flex;
  align-items: end;
  background:
    linear-gradient(120deg, rgba(3,45,32,.92), rgba(9,103,74,.76)),
    url('https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=1800&q=80') center/cover;
}
.juma-detail-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.2) 1px, transparent 0);
  background-size: 24px 24px;
  opacity: .5;
}
.detail-glass {
  position: relative;
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 32px;
  background: rgba(255,255,255,.14);
  backdrop-filter: blur(18px);
  box-shadow: 0 28px 80px rgba(0,0,0,.24);
}
.detail-title {
  font-size: clamp(2.2rem, 4vw, 4.6rem);
  color: #fff;
  font-weight: 900;
}
.detail-card {
  border: 1px solid rgba(6,63,46,.08);
  border-radius: 28px;
  background: rgba(255,255,255,.9);
  box-shadow: 0 24px 70px rgba(6,63,46,.1);
}
.time-tile {
  border-radius: 24px;
  background: linear-gradient(135deg, #073d2e, #0b7756);
  color: #fff;
  padding: 1.5rem;
  min-height: 160px;
  position: relative;
  overflow: hidden;
}
.time-tile::after {
  content: "";
  position: absolute;
  width: 170px;
  height: 170px;
  right: -70px;
  bottom: -70px;
  border-radius: 50%;
  background: rgba(255,255,255,.1);
}
.info-pill {
  display: inline-flex;
  align-items: center;
  gap: .45rem;
  padding: .55rem .85rem;
  border-radius: 999px;
  background: #eef8f2;
  color: #31594b;
  font-weight: 800;
  font-size: .84rem;
}
.map-panel {
  min-height: 340px;
  border-radius: 28px;
  overflow: hidden;
  background:
    linear-gradient(135deg, rgba(6,63,46,.88), rgba(10,115,83,.76)),
    url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80') center/cover;
}
</style>

<div class="juma-detail-page">
  <section class="juma-detail-hero">
    <div class="container position-relative py-5">
      <div class="detail-glass p-4 p-lg-5 animate__animated animate__fadeInUp">
        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 mb-3"><i class="fas fa-star-and-crescent me-2"></i>Juma & Eid Schedule</span>
        <h1 class="detail-title mb-3">{{ $masjid->name }}</h1>
        <p class="text-white-50 fs-5 mb-4"><i class="fas fa-location-dot me-2"></i>{{ $masjid->address }}</p>
        <div class="d-flex flex-wrap gap-2">
          <span class="info-pill"><i class="fas fa-users"></i>{{ $masjid->sect ?? 'Community' }}</span>
          <span class="info-pill"><i class="fas fa-shield-halved"></i>Verified Schedule</span>
          @if($masjid->is_featured)
            <span class="info-pill"><i class="fas fa-star text-warning"></i>Featured Masjid</span>
          @endif
        </div>
      </div>
    </div>
  </section>

  <section class="container py-5">
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="detail-card p-4 p-lg-5" data-aos="fade-up">
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="time-tile">
                <i class="fas fa-people-group fa-2x text-warning mb-3"></i>
                <div class="text-white-50 small text-uppercase fw-bold">Juma Friday Prayer</div>
                <div class="display-6 fw-black">{{ $masjid->juma_time ? date('h:i A', strtotime($masjid->juma_time)) : 'Time not available' }}</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="time-tile">
                <i class="fas fa-moon fa-2x text-warning mb-3"></i>
                <div class="text-white-50 small text-uppercase fw-bold">Eid Prayer</div>
                <div class="display-6 fw-black">{{ $masjid->eid_time ? date('h:i A', strtotime($masjid->eid_time)) : 'Time not available' }}</div>
              </div>
            </div>
          </div>

          <h2 class="h4 fw-black text-success mb-3">Masjid Information</h2>
          <p class="text-secondary mb-4">{{ $masjid->description ?? 'No additional description is available for this masjid yet.' }}</p>

          <div class="d-flex flex-wrap gap-2 mb-4">
            <span class="info-pill"><i class="fas fa-city"></i>{{ $masjid->city ?: 'City not listed' }}</span>
            <span class="info-pill"><i class="fas fa-map"></i>{{ $masjid->area ?: 'Area not listed' }}</span>
            <span class="info-pill"><i class="fas fa-square-parking"></i>{{ $masjid->parking ? 'Parking Available' : 'Parking info unavailable' }}</span>
            <span class="info-pill"><i class="fas fa-wheelchair"></i>{{ $masjid->wheelchair ? 'Wheelchair Access' : 'Accessibility info unavailable' }}</span>
            @foreach($facilityNames as $facility)
              <span class="info-pill"><i class="fas fa-check"></i>{{ $facility }}</span>
            @endforeach
          </div>

          <div class="d-flex flex-wrap gap-2">
            <a href="{{ $directions }}" target="_blank" class="btn btn-success rounded-pill px-4"><i class="fas fa-route me-2"></i>Get Directions</a>
            <a href="{{ $masjid->phone ? 'tel:' . $masjid->phone : '#' }}" class="btn btn-outline-success rounded-pill px-4 {{ $masjid->phone ? '' : 'disabled' }}"><i class="fas fa-phone me-2"></i>{{ $masjid->phone ?: 'No Phone' }}</a>
            <button type="button" class="btn btn-outline-dark rounded-pill px-4" onclick="navigator.share ? navigator.share({title: '{{ addslashes($masjid->name) }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href)"><i class="fas fa-share-nodes me-2"></i>Share</button>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="detail-card p-4 h-100" data-aos="fade-left">
          <div class="map-panel d-flex align-items-end p-4 text-white mb-4">
            <div>
              <i class="fas fa-location-crosshairs fa-2x text-warning mb-3"></i>
              <h3 class="h5 fw-black">Plan your visit</h3>
              <p class="text-white-50 mb-0">Open directions and confirm prayer timing with the masjid when needed.</p>
            </div>
          </div>
          <a href="{{ route('timings.juma') }}" class="btn btn-dark rounded-pill w-100"><i class="fas fa-arrow-left me-2"></i>Back to all timings</a>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>document.addEventListener('DOMContentLoaded', () => AOS.init({ duration: 700, once: true }));</script>
@endsection
