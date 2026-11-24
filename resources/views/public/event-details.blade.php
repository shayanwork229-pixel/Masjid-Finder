@extends('layouts.public')

@section('title', $event->title)

@php
  $masjid = $event->masjid;
  $location = $masjid?->address ?? $masjid?->city ?? 'Location to be announced';
  $directions = $masjid && $masjid->latitude && $masjid->longitude
    ? 'https://www.google.com/maps/dir/?api=1&destination=' . $masjid->latitude . ',' . $masjid->longitude
    : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($location);
  $relatedEvents = $masjid?->events?->where('id', '!=', $event->id)->take(3) ?? collect();
@endphp

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

<style>
.event-detail-page {
  --emerald: #063f2e;
  --emerald-2: #0b7655;
  --gold: #d9b86c;
  --soft: #eef8f2;
  --ink: #10231d;
  background: linear-gradient(180deg, #f8fcf9, #eef8f2 48%, #fff);
  color: var(--ink);
}
.event-detail-hero {
  min-height: 620px;
  display: flex;
  align-items: end;
  position: relative;
  overflow: hidden;
  background:
    linear-gradient(120deg, rgba(3,45,32,.94), rgba(10,111,80,.76)),
    url('https://images.unsplash.com/photo-1519817650390-64a93db51149?auto=format&fit=crop&w=1800&q=80') center/cover;
}
.event-detail-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.2) 1px, transparent 0);
  background-size: 24px 24px;
  opacity: .55;
}
.hero-panel {
  position: relative;
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 34px;
  background: rgba(255,255,255,.14);
  backdrop-filter: blur(18px);
  box-shadow: 0 30px 90px rgba(0,0,0,.24);
}
.event-detail-title {
  color: #fff;
  font-size: clamp(2.35rem, 5vw, 5.5rem);
  line-height: 1;
  font-weight: 900;
}
.detail-chip {
  display: inline-flex;
  align-items: center;
  gap: .45rem;
  border-radius: 999px;
  padding: .55rem .85rem;
  background: rgba(255,255,255,.9);
  color: var(--emerald);
  font-size: .82rem;
  font-weight: 900;
}
.detail-card {
  border-radius: 30px;
  border: 1px solid rgba(6,63,46,.08);
  background: rgba(255,255,255,.92);
  box-shadow: 0 24px 70px rgba(6,63,46,.1);
}
.section-kicker {
  color: var(--emerald-2);
  text-transform: uppercase;
  letter-spacing: .08em;
  font-size: .78rem;
  font-weight: 900;
}
.gallery-tile {
  min-height: 170px;
  border-radius: 24px;
  background:
    linear-gradient(180deg, transparent, rgba(3,45,32,.68)),
    url('{{ asset('assets/img/gallery/sample6.jpg') }}') center/cover;
}
.speaker-card {
  border-radius: 24px;
  background: linear-gradient(135deg, #f7fff9, #fff);
  border: 1px solid rgba(6,63,46,.08);
  padding: 1rem;
}
.speaker-avatar {
  width: 64px;
  height: 64px;
  border-radius: 20px;
  display: grid;
  place-items: center;
  color: #fff;
  background: linear-gradient(135deg, var(--emerald), var(--emerald-2));
}
.schedule-row {
  display: grid;
  grid-template-columns: 92px 1fr;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid rgba(6,63,46,.08);
}
.map-panel {
  min-height: 360px;
  border-radius: 28px;
  overflow: hidden;
  background:
    linear-gradient(135deg, rgba(6,63,46,.88), rgba(10,115,83,.76)),
    url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80') center/cover;
}
.floating-action {
  position: sticky;
  top: 100px;
}
@media (max-width: 767px) {
  .event-detail-hero { min-height: 740px; }
  .schedule-row { grid-template-columns: 1fr; }
}
</style>

<div class="event-detail-page">
  <section class="event-detail-hero">
    <div class="container position-relative py-5">
      <div class="hero-panel p-4 p-lg-5 animate__animated animate__fadeInUp">
        <div class="d-flex flex-wrap gap-2 mb-4">
          <span class="detail-chip"><i class="fas fa-calendar-star"></i>{{ $event->category ?? 'Event' }}</span>
          <span class="detail-chip"><i class="fas fa-users"></i>{{ $event->sect ?? 'Community' }}</span>
          @if($event->is_featured)
            <span class="detail-chip"><i class="fas fa-star text-warning"></i>Featured Event</span>
          @endif
          <span class="detail-chip"><i class="fas fa-ticket"></i>Free</span>
          <span class="detail-chip"><i class="fas fa-video"></i>Live</span>
        </div>
        <h1 class="event-detail-title mb-4">{{ $event->title }}</h1>
        <p class="text-white-50 fs-5 mb-4">{{ Str::limit($event->description, 180) }}</p>
        <div class="d-flex flex-wrap gap-2">
          <span class="detail-chip"><i class="fas fa-calendar-days"></i>{{ $event->event_date->format('d M Y') }}</span>
          <span class="detail-chip"><i class="fas fa-clock"></i>{{ $event->event_time ?: 'Time TBA' }}</span>
          <span class="detail-chip"><i class="fas fa-mosque"></i>{{ $masjid?->name ?? 'Community Venue' }}</span>
          <span class="detail-chip"><i class="fas fa-location-dot"></i>{{ Str::limit($location, 34) }}</span>
        </div>
      </div>
    </div>
  </section>

  <section class="container py-5">
    <div class="row g-4">
      <div class="col-lg-8">
        <article class="detail-card p-4 p-lg-5 mb-4" data-aos="fade-up">
          <div class="section-kicker">Event overview</div>
          <h2 class="fw-black text-success mb-3">About this event</h2>
          <p class="text-secondary fs-5 mb-0">{{ $event->description }}</p>
        </article>

        <section class="detail-card p-4 p-lg-5 mb-4" data-aos="fade-up">
          <div class="section-kicker">Gallery</div>
          <h2 class="fw-black text-success mb-4">Event atmosphere</h2>
          <div class="row g-3">
            <div class="col-md-7"><div class="gallery-tile h-100"></div></div>
            <div class="col-md-5">
              <div class="gallery-tile mb-3" style="min-height: 150px; background-image: linear-gradient(180deg, transparent, rgba(3,45,32,.68)), url('{{ asset('assets/img/gallery/sample1.jpg') }}');"></div>
              <div class="gallery-tile" style="min-height: 150px; background-image: linear-gradient(180deg, transparent, rgba(3,45,32,.68)), url('{{ asset('assets/img/gallery/sample2.jpg') }}');"></div>
            </div>
          </div>
        </section>

        <section class="detail-card p-4 p-lg-5 mb-4" data-aos="fade-up">
          <div class="section-kicker">Speaker profiles</div>
          <h2 class="fw-black text-success mb-4">Speakers and hosts</h2>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="speaker-card d-flex gap-3 align-items-center">
                <div class="speaker-avatar"><i class="fas fa-user-graduate fa-lg"></i></div>
                <div>
                  <h3 class="h6 fw-black mb-1">Scholar TBA</h3>
                  <p class="text-secondary small mb-0">Main speaker for {{ $event->category ?? 'community program' }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="speaker-card d-flex gap-3 align-items-center">
                <div class="speaker-avatar"><i class="fas fa-mosque fa-lg"></i></div>
                <div>
                  <h3 class="h6 fw-black mb-1">{{ $masjid?->name ?? 'Hosting Masjid' }}</h3>
                  <p class="text-secondary small mb-0">Community host and venue team</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="detail-card p-4 p-lg-5 mb-4" data-aos="fade-up">
          <div class="section-kicker">Schedule</div>
          <h2 class="fw-black text-success mb-4">Program flow</h2>
          <div class="schedule-row">
            <strong class="text-success">{{ $event->event_time ?: 'TBA' }}</strong>
            <div><h3 class="h6 fw-black mb-1">Arrival and welcome</h3><p class="text-secondary mb-0">Guests arrive, settle in, and prepare for the program.</p></div>
          </div>
          <div class="schedule-row">
            <strong class="text-success">Session</strong>
            <div><h3 class="h6 fw-black mb-1">{{ $event->title }}</h3><p class="text-secondary mb-0">{{ Str::limit($event->description, 110) }}</p></div>
          </div>
          <div class="schedule-row border-0">
            <strong class="text-success">Closing</strong>
            <div><h3 class="h6 fw-black mb-1">Dua and community networking</h3><p class="text-secondary mb-0">Program closes with reflection and community connection.</p></div>
          </div>
        </section>

        <section class="detail-card p-4 p-lg-5" data-aos="fade-up">
          <div class="section-kicker">Location map</div>
          <h2 class="fw-black text-success mb-4">Venue and directions</h2>
          <div class="map-panel d-flex align-items-end p-4 text-white">
            <div>
              <i class="fas fa-map-location-dot fa-2x text-warning mb-3"></i>
              <h3 class="h5 fw-black">{{ $masjid?->name ?? 'Community Venue' }}</h3>
              <p class="text-white-50 mb-3">{{ $location }}</p>
              <a href="{{ $directions }}" target="_blank" class="btn btn-light rounded-pill px-4"><i class="fas fa-route me-2"></i>Open Directions</a>
            </div>
          </div>
        </section>
      </div>

      <div class="col-lg-4">
        <aside class="floating-action">
          <div class="detail-card p-4 mb-4" data-aos="fade-left">
            <div class="section-kicker">Registration</div>
            <h2 class="h4 fw-black text-success mb-3">Reserve your place</h2>
            <div class="d-grid gap-2 mb-3">
              <a href="{{ route('events.show', $event) }}" class="btn btn-success btn-lg rounded-pill"><i class="fas fa-user-plus me-2"></i>Register</a>
              <button type="button" id="detailShare" class="btn btn-outline-success rounded-pill"><i class="fas fa-share-nodes me-2"></i>Share</button>
              <button type="button" id="detailBookmark" class="btn btn-outline-dark rounded-pill"><i class="far fa-bookmark me-2"></i>Bookmark</button>
            </div>
            <div class="d-flex flex-wrap gap-2">
              <span class="detail-chip"><i class="fas fa-chair"></i>Seats open</span>
              <span class="detail-chip"><i class="fas fa-users"></i>Registration open</span>
              <span class="detail-chip"><i class="fas fa-hourglass-half"></i><span id="detailCountdown" data-date="{{ $event->event_date->format('Y-m-d') }}" data-time="{{ $event->event_time ?: '09:00' }}">Soon</span></span>
            </div>
          </div>

          <div class="detail-card p-4" data-aos="fade-left" data-aos-delay="100">
            <div class="section-kicker">Related events</div>
            <h2 class="h5 fw-black text-success mb-3">More at this venue</h2>
            @forelse($relatedEvents as $related)
              <a href="{{ route('events.show', $related) }}" class="d-block text-decoration-none text-dark border-bottom py-3">
                <strong>{{ $related->title }}</strong>
                <div class="text-secondary small">{{ $related->event_date->format('d M Y') }}</div>
              </a>
            @empty
              <p class="text-secondary small mb-0">No related events listed yet.</p>
            @endforelse
          </div>
        </aside>
      </div>
    </div>
  </section>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  AOS.init({ duration: 700, once: true, offset: 80 });
  const share = document.getElementById('detailShare');
  const bookmark = document.getElementById('detailBookmark');
  const bookmarkKey = 'event-detail-{{ $event->id }}';

  if (localStorage.getItem(bookmarkKey)) {
    bookmark.innerHTML = '<i class="fas fa-bookmark me-2 text-warning"></i>Bookmarked';
  }
  bookmark.addEventListener('click', () => {
    if (localStorage.getItem(bookmarkKey)) {
      localStorage.removeItem(bookmarkKey);
      bookmark.innerHTML = '<i class="far fa-bookmark me-2"></i>Bookmark';
    } else {
      localStorage.setItem(bookmarkKey, '1');
      bookmark.innerHTML = '<i class="fas fa-bookmark me-2 text-warning"></i>Bookmarked';
    }
  });

  share.addEventListener('click', () => {
    const payload = { title: @json($event->title), url: window.location.href };
    if (navigator.share) navigator.share(payload).catch(() => {});
    else navigator.clipboard.writeText(window.location.href);
  });

  function renderCountdown() {
    const node = document.getElementById('detailCountdown');
    const target = new Date(`${node.dataset.date}T${node.dataset.time || '09:00'}`);
    const diff = target - new Date();
    if (diff <= 0) {
      node.textContent = 'Live now';
      return;
    }
    const days = Math.floor(diff / 86400000);
    const hours = Math.floor(diff % 86400000 / 3600000);
    node.textContent = days ? `${days}d ${hours}h` : `${hours}h`;
  }
  renderCountdown();
  setInterval(renderCountdown, 60000);
});
</script>
@endsection
