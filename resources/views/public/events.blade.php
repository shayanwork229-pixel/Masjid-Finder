@extends('layouts.public')

@section('title', 'Upcoming Events')

@php
  $eventCollection = $events->getCollection();
  $featuredEvents = $eventCollection->where('is_featured', true);
  if ($featuredEvents->isEmpty()) {
    $featuredEvents = $eventCollection->take(4);
  }
  $categories = $eventCollection->pluck('category')->filter()->unique()->values();
  $todayEvents = $eventCollection->filter(fn($event) => $event->event_date?->isToday());
  $tomorrowEvents = $eventCollection->filter(fn($event) => $event->event_date?->isTomorrow());
  $weekEvents = $eventCollection->filter(fn($event) => $event->event_date && $event->event_date->between(now()->startOfDay(), now()->copy()->addWeek()->endOfDay()) && !$event->event_date->isToday() && !$event->event_date->isTomorrow());
  $monthEvents = $eventCollection->filter(fn($event) => $event->event_date && $event->event_date->greaterThan(now()->copy()->addWeek()->endOfDay()));
@endphp

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

<style>
.events-premium-page {
  --emerald: #063f2e;
  --emerald-2: #0a7655;
  --mint: #e6f8ee;
  --gold: #d9b86c;
  --ink: #10231d;
  background: linear-gradient(180deg, #f8fcf9 0%, #eef8f2 44%, #fff 100%);
  color: var(--ink);
}
.events-hero {
  position: relative;
  overflow: hidden;
  min-height: 650px;
  display: flex;
  align-items: center;
  background:
    linear-gradient(120deg, rgba(3,45,32,.94), rgba(10,114,82,.78)),
    url('https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?auto=format&fit=crop&w=1800&q=80') center/cover;
}
.events-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.18) 1px, transparent 0);
  background-size: 24px 24px;
  opacity: .55;
}
.hero-wave { position: absolute; left: 0; right: 0; bottom: -1px; color: #f8fcf9; }
.event-title {
  color: #fff;
  font-size: clamp(2.5rem, 5vw, 5.7rem);
  line-height: 1;
  font-weight: 900;
}
.event-subtitle {
  max-width: 760px;
  color: rgba(255,255,255,.82);
  font-size: 1.15rem;
}
.event-glass {
  border: 1px solid rgba(255,255,255,.18);
  border-radius: 30px;
  background: rgba(255,255,255,.12);
  backdrop-filter: blur(18px);
  box-shadow: 0 26px 80px rgba(0,0,0,.24);
}
.event-illustration {
  min-height: 390px;
  border-radius: 32px;
  background:
    linear-gradient(180deg, rgba(255,255,255,.2), rgba(255,255,255,.08)),
    radial-gradient(circle at 75% 20%, rgba(217,184,108,.42), transparent 28%);
  display: grid;
  place-items: center;
  color: #fff;
}
.event-illustration i {
  font-size: 8rem;
  color: var(--gold);
  filter: drop-shadow(0 18px 26px rgba(0,0,0,.25));
}
.event-search {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: .7rem;
  border-radius: 22px;
  background: #fff;
  box-shadow: 0 20px 56px rgba(0,0,0,.15);
}
.event-search input {
  flex: 1;
  border: 0;
  outline: 0;
  min-width: 0;
}
.category-filter {
  border: 1px solid rgba(255,255,255,.22);
  background: rgba(255,255,255,.11);
  color: #fff;
  border-radius: 999px;
  padding: .75rem 1rem;
  font-weight: 800;
  transition: .25s ease;
}
.category-filter.active,
.category-filter:hover {
  color: var(--emerald);
  background: #fff;
  transform: translateY(-2px);
}
.section-kicker {
  color: var(--emerald-2);
  text-transform: uppercase;
  letter-spacing: .08em;
  font-size: .78rem;
  font-weight: 900;
}
.featured-strip {
  display: flex;
  gap: 1rem;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  padding-bottom: .75rem;
}
.featured-slide {
  min-width: min(430px, 88vw);
  scroll-snap-align: start;
  border-radius: 28px;
  overflow: hidden;
  background:
    linear-gradient(135deg, rgba(6,63,46,.92), rgba(10,118,85,.78)),
    url('https://images.unsplash.com/photo-1519817650390-64a93db51149?auto=format&fit=crop&w=1100&q=80') center/cover;
  color: #fff;
  box-shadow: 0 24px 70px rgba(6,63,46,.18);
}
.event-card {
  position: relative;
  border-radius: 30px;
  padding: 1px;
  background: linear-gradient(145deg, rgba(217,184,108,.85), rgba(10,118,85,.48), rgba(255,255,255,.82));
  box-shadow: 0 24px 70px rgba(6,63,46,.12);
  transition: transform .25s ease, box-shadow .25s ease;
}
.event-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 34px 88px rgba(6,63,46,.2);
}
.event-card-inner {
  border-radius: 29px;
  overflow: hidden;
  height: 100%;
  background: rgba(255,255,255,.92);
  backdrop-filter: blur(16px);
}
.event-cover {
  position: relative;
  min-height: 240px;
  overflow: hidden;
  background:
    linear-gradient(180deg, transparent, rgba(3,45,32,.8)),
    url('/assets/img/gallery/sample1.jpg') center/cover;
}
.event-card:hover .event-cover {
  background-size: auto, 112%;
}
.date-badge {
  position: absolute;
  top: 1rem;
  left: 1rem;
  width: 76px;
  min-height: 76px;
  display: grid;
  place-items: center;
  border-radius: 20px;
  background: rgba(255,255,255,.92);
  color: var(--emerald);
  font-weight: 900;
  box-shadow: 0 14px 34px rgba(0,0,0,.16);
}
.featured-ribbon {
  position: absolute;
  top: 1.1rem;
  right: -2.5rem;
  transform: rotate(38deg);
  background: var(--gold);
  color: #2b210d;
  padding: .35rem 3rem;
  font-size: .72rem;
  font-weight: 900;
}
.event-pill {
  display: inline-flex;
  gap: .4rem;
  align-items: center;
  padding: .46rem .72rem;
  border-radius: 999px;
  background: #f2faf5;
  color: #3b5f53;
  font-size: .78rem;
  font-weight: 800;
}
.timeline {
  position: relative;
  border-left: 2px solid rgba(10,118,85,.18);
  margin-left: 1rem;
}
.timeline-block {
  position: relative;
  padding: 0 0 1.2rem 1.5rem;
}
.timeline-block::before {
  content: "";
  position: absolute;
  left: -9px;
  top: .25rem;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: var(--emerald-2);
  box-shadow: 0 0 0 6px #e6f8ee;
}
.timeline-card {
  border-radius: 20px;
  background: #fff;
  border: 1px solid rgba(6,63,46,.08);
  box-shadow: 0 16px 44px rgba(6,63,46,.08);
  padding: 1rem;
}
@media (max-width: 767px) {
  .events-hero { min-height: 760px; }
  .event-search { flex-direction: column; align-items: stretch; }
  .event-search input { width: 100%; padding: .5rem; }
}
</style>

<div class="events-premium-page">
  <section class="events-hero">
    <div class="container position-relative py-5">
      <div class="row align-items-center g-5">
        <div class="col-lg-7">
          <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2 mb-3 animate__animated animate__fadeInDown"><i class="fas fa-calendar-star me-2"></i>Community Programs</span>
          <h1 class="event-title animate__animated animate__fadeInUp">Community Events</h1>
          <p class="event-subtitle my-4 animate__animated animate__fadeInUp animate__delay-1s">Discover lectures, family programs, workshops, live gatherings, and spiritual events hosted by local masajid.</p>
          <div class="event-glass p-3 p-md-4" data-aos="fade-up">
            <div class="event-search mb-3">
              <i class="fas fa-search text-success ms-2"></i>
              <input id="eventSearch" type="search" placeholder="Search event, masjid, scholar, location, or category">
              <button class="btn btn-success rounded-pill px-4" type="button">Search</button>
            </div>
            <div class="d-flex flex-wrap gap-2">
              <button type="button" class="category-filter active" data-category-filter="All">All</button>
              @foreach($categories as $category)
                <button type="button" class="category-filter" data-category-filter="{{ $category }}">{{ $category }}</button>
              @endforeach
            </div>
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
          <div class="event-glass event-illustration" data-aos="zoom-in">
            <div class="text-center">
              <i class="fas fa-people-roof mb-4"></i>
              <h2 class="h4 fw-black text-white">Gather with purpose</h2>
              <p class="text-white-50 mb-0 px-4">Premium event discovery with fast actions and polished context.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <svg class="hero-wave" viewBox="0 0 1440 110" preserveAspectRatio="none"><path fill="currentColor" d="M0,80 C280,20 410,120 720,72 C990,30 1130,18 1440,70 L1440,110 L0,110 Z"/></svg>
  </section>

  <section class="container py-5">
    <div class="d-flex justify-content-between flex-column flex-lg-row gap-3 mb-4">
      <div>
        <div class="section-kicker">Featured slider</div>
        <h2 class="fw-black mb-1">Featured Events</h2>
        <p class="text-secondary mb-0">Swipe horizontally to explore highlighted community programs.</p>
      </div>
    </div>
    <div class="featured-strip">
      @forelse($featuredEvents as $event)
        <article class="featured-slide p-4 p-lg-5">
          <span class="badge bg-warning text-dark rounded-pill mb-3"><i class="fas fa-star me-1"></i>Featured</span>
          <h3 class="h3 fw-black mb-2">{{ $event->title }}</h3>
          <p class="text-white-50 mb-4">{{ Str::limit($event->description, 130) }}</p>
          <div class="d-flex flex-wrap gap-2 mb-4">
            <span class="event-pill"><i class="fas fa-calendar"></i>{{ $event->event_date->format('d M Y') }}</span>
            <span class="event-pill"><i class="fas fa-clock"></i>{{ $event->event_time ?: 'Time TBA' }}</span>
            <span class="event-pill"><i class="fas fa-mosque"></i>{{ $event->masjid?->name ?? 'Community Venue' }}</span>
          </div>
          <a href="{{ route('events.show', $event) }}" class="btn btn-light rounded-pill px-4"><i class="fas fa-arrow-right me-2"></i>View Details</a>
        </article>
      @empty
        <div class="timeline-card w-100 text-center">No featured events scheduled yet.</div>
      @endforelse
    </div>
  </section>

  <section class="container pb-5">
    <div class="row g-4">
      @forelse($events as $event)
        @php
          $masjidName = $event->masjid?->name ?? 'Community Masjid';
          $location = $event->masjid?->address ?? $event->masjid?->city ?? 'Location to be announced';
          $searchText = strtolower(trim($event->title . ' ' . $event->description . ' ' . $event->category . ' ' . $event->sect . ' ' . $masjidName . ' ' . $location));
        @endphp
        <div class="col-lg-6 event-item" data-aos="fade-up" data-category="{{ $event->category ?? 'Event' }}" data-search="{{ $searchText }}">
          <article class="event-card h-100">
            <div class="event-card-inner">
              <div class="event-cover">
                <div class="date-badge text-center">
                  <div>
                    <div class="fs-3 lh-1">{{ $event->event_date->format('d') }}</div>
                    <div class="small text-uppercase">{{ $event->event_date->format('M') }}</div>
                  </div>
                </div>
                @if($event->is_featured)
                  <div class="featured-ribbon">Featured</div>
                @endif
              </div>
              <div class="p-4">
                <div class="d-flex justify-content-between gap-3 mb-2">
                  <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">{{ $event->category ?? 'Event' }}</span>
                  <button type="button" class="btn btn-light rounded-circle bookmark-event" data-bookmark="event-{{ $event->id }}" title="Bookmark"><i class="far fa-bookmark"></i></button>
                </div>
                <h3 class="h4 fw-black mb-2">{{ $event->title }}</h3>
                <p class="text-secondary mb-3">{{ Str::limit($event->description, 135) }}</p>
                <div class="d-flex flex-wrap gap-2 mb-4">
                  <span class="event-pill"><i class="fas fa-calendar-days"></i>{{ $event->event_date->format('d M Y') }}</span>
                  <span class="event-pill"><i class="fas fa-moon"></i>Islamic Date</span>
                  <span class="event-pill"><i class="fas fa-mosque"></i>{{ Str::limit($masjidName, 22) }}</span>
                  <span class="event-pill"><i class="fas fa-user-graduate"></i>Scholar TBA</span>
                  <span class="event-pill"><i class="fas fa-location-dot"></i>{{ Str::limit($location, 26) }}</span>
                  <span class="event-pill"><i class="fas fa-chair"></i>Seats open</span>
                  <span class="event-pill"><i class="fas fa-users"></i>Registration open</span>
                  <span class="event-pill"><i class="fas fa-video"></i>Live</span>
                  <span class="event-pill"><i class="fas fa-ticket"></i>Free</span>
                  <span class="event-pill"><i class="fas fa-hourglass-half"></i><span class="event-countdown" data-event-date="{{ $event->event_date->format('Y-m-d') }}" data-event-time="{{ $event->event_time ?: '09:00' }}">Soon</span></span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('events.show', $event) }}" class="btn btn-success rounded-pill px-4"><i class="fas fa-user-plus me-2"></i>Register</a>
                  <a href="{{ route('events.show', $event) }}" class="btn btn-outline-success rounded-pill px-4"><i class="fas fa-eye me-2"></i>View Details</a>
                  <button type="button" class="btn btn-outline-dark rounded-pill px-3 share-event" data-title="{{ $event->title }}"><i class="fas fa-share-nodes"></i></button>
                </div>
              </div>
            </div>
          </article>
        </div>
      @empty
        <div class="col-12">
          <div class="timeline-card text-center py-5">
            <i class="fas fa-calendar-xmark fa-3x text-success mb-3"></i>
            <h3 class="fw-black">No events scheduled yet.</h3>
          </div>
        </div>
      @endforelse
    </div>
    <div class="mt-5 d-flex justify-content-center">{{ $events->links() }}</div>
  </section>

  <section class="container pb-5">
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="section-kicker">Upcoming timeline</div>
        <h2 class="fw-black">Plan your week</h2>
        <p class="text-secondary">A simple time-based view for what is happening today, tomorrow, this week, and next month.</p>
      </div>
      <div class="col-lg-7">
        <div class="timeline">
          @foreach(['Today' => $todayEvents, 'Tomorrow' => $tomorrowEvents, 'This Week' => $weekEvents, 'Next Month' => $monthEvents] as $label => $items)
            <div class="timeline-block" data-aos="fade-left">
              <h3 class="h6 fw-black text-success">{{ $label }}</h3>
              <div class="timeline-card">
                @forelse($items->take(3) as $event)
                  <a href="{{ route('events.show', $event) }}" class="d-flex justify-content-between gap-3 text-decoration-none text-dark border-bottom py-2">
                    <span class="fw-bold">{{ $event->title }}</span>
                    <span class="text-secondary small">{{ $event->event_date->format('d M') }}</span>
                  </a>
                @empty
                  <span class="text-secondary small">No events in this window.</span>
                @endforelse
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  AOS.init({ duration: 700, once: true, offset: 80 });
  const search = document.getElementById('eventSearch');
  const filters = document.querySelectorAll('[data-category-filter]');
  const cards = document.querySelectorAll('.event-item');
  let activeCategory = 'All';

  function applyFilters() {
    const term = (search?.value || '').toLowerCase().trim();
    cards.forEach(card => {
      const categoryOk = activeCategory === 'All' || card.dataset.category === activeCategory;
      const searchOk = !term || card.dataset.search.includes(term);
      card.classList.toggle('d-none', !(categoryOk && searchOk));
    });
  }

  search?.addEventListener('input', applyFilters);
  filters.forEach(button => {
    button.addEventListener('click', () => {
      filters.forEach(item => item.classList.remove('active'));
      button.classList.add('active');
      activeCategory = button.dataset.categoryFilter;
      applyFilters();
    });
  });

  document.querySelectorAll('.bookmark-event').forEach(button => {
    const key = button.dataset.bookmark;
    const icon = button.querySelector('i');
    if (localStorage.getItem(key)) icon.className = 'fas fa-bookmark text-warning';
    button.addEventListener('click', () => {
      if (localStorage.getItem(key)) {
        localStorage.removeItem(key);
        icon.className = 'far fa-bookmark';
      } else {
        localStorage.setItem(key, '1');
        icon.className = 'fas fa-bookmark text-warning';
      }
    });
  });

  document.querySelectorAll('.share-event').forEach(button => {
    button.addEventListener('click', () => {
      const payload = { title: button.dataset.title, url: window.location.href };
      if (navigator.share) navigator.share(payload).catch(() => {});
      else navigator.clipboard.writeText(window.location.href);
    });
  });

  function renderCountdowns() {
    document.querySelectorAll('.event-countdown').forEach(node => {
      const target = new Date(`${node.dataset.eventDate}T${node.dataset.eventTime || '09:00'}`);
      const diff = target - new Date();
      if (diff <= 0) {
        node.textContent = 'Live now';
        return;
      }
      const days = Math.floor(diff / 86400000);
      const hours = Math.floor(diff % 86400000 / 3600000);
      node.textContent = days ? `${days}d ${hours}h` : `${hours}h`;
    });
  }
  renderCountdowns();
  setInterval(renderCountdowns, 60000);
});
</script>
@endsection
