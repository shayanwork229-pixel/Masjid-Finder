@extends('layouts.public')

@section('title', 'Juma & Eid')

@php
  $totalMasjids = $masjids->count();
  $sunniCount = $masjids->where('sect', 'Sunni')->count();
  $shiaCount = $masjids->where('sect', 'Shia')->count();
  $todaysJuma = now()->isFriday() ? $totalMasjids : 0;
  $upcomingEid = $masjids->whereNotNull('eid_time')->count();
  $averageJuma = $masjids->pluck('juma_time')->filter()->map(fn($time) => strtotime($time))->filter();
  $averageJumaTime = $averageJuma->count() ? date('h:i A', (int) round($averageJuma->avg())) : 'Not set';
  $nearestMosque = $masjids->first();
  $hijriMonths = ['Muharram','Safar','Rabi al-Awwal','Rabi al-Thani','Jumada al-Awwal','Jumada al-Thani','Rajab','Shaaban','Ramadan','Shawwal','Dhu al-Qadah','Dhu al-Hijjah'];
  $jd = gregoriantojd((int) now()->format('m'), (int) now()->format('d'), (int) now()->format('Y'));
  $l = $jd - 1948440 + 10632;
  $n = intdiv($l - 1, 10631);
  $l = $l - 10631 * $n + 354;
  $j = intdiv(10985 - $l, 5316) * intdiv(50 * $l, 17719) + intdiv($l, 5670) * intdiv(43 * $l, 15238);
  $l = $l - intdiv(30 - $j, 15) * intdiv(17719 * $j, 50) - intdiv($j, 16) * intdiv(15238 * $j, 43) + 29;
  $m = intdiv(24 * $l, 709);
  $d = $l - intdiv(709 * $m, 24);
  $y = 30 * $n + $j - 30;
  $hijriDate = $d . ' ' . ($hijriMonths[$m - 1] ?? 'Hijri') . ' ' . $y . ' AH';
@endphp

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

<style>
.premium-prayer-page {
  --emerald: #063f2e;
  --emerald-2: #0b6b4f;
  --mint: #dff7ea;
  --gold: #d7b56d;
  --ink: #10231d;
  --muted: #66746f;
  background: linear-gradient(180deg, #f7fbf8 0%, #eef7f1 42%, #ffffff 100%);
  color: var(--ink);
}
.prayer-hero {
  position: relative;
  min-height: 620px;
  display: flex;
  align-items: center;
  overflow: hidden;
  background:
    linear-gradient(120deg, rgba(3, 45, 32, .92), rgba(8, 91, 66, .8)),
    url('https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=1800&q=80') center/cover;
}
.prayer-hero::before,
.eid-top-banner::before {
  content: "";
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle at 1px 1px, rgba(255,255,255,.18) 1px, transparent 0);
  background-size: 24px 24px;
  mask-image: linear-gradient(180deg, #000, transparent);
  opacity: .5;
}
.hero-wave {
  position: absolute;
  left: 0;
  right: 0;
  bottom: -1px;
  color: #f7fbf8;
}
.hero-kicker {
  display: inline-flex;
  gap: .6rem;
  align-items: center;
  padding: .7rem 1rem;
  border: 1px solid rgba(255,255,255,.24);
  border-radius: 999px;
  color: #f8f3dc;
  background: rgba(255,255,255,.1);
  backdrop-filter: blur(14px);
  font-weight: 700;
}
.prayer-title {
  color: #fff;
  font-weight: 900;
  letter-spacing: 0;
  font-size: clamp(2.5rem, 5vw, 5.8rem);
  line-height: 1;
}
.prayer-subtitle {
  color: rgba(255,255,255,.82);
  max-width: 760px;
  font-size: 1.16rem;
}
.hero-glass {
  border: 1px solid rgba(255,255,255,.18);
  background: rgba(255,255,255,.12);
  box-shadow: 0 24px 80px rgba(0,0,0,.25);
  backdrop-filter: blur(18px);
  border-radius: 28px;
}
.hero-stat {
  padding: 1.1rem;
  border-radius: 22px;
  background: rgba(255,255,255,.12);
  border: 1px solid rgba(255,255,255,.14);
}
.hero-stat strong {
  display: block;
  color: #fff;
  font-size: 1.65rem;
  line-height: 1;
}
.hero-stat span { color: rgba(255,255,255,.72); font-size: .86rem; }
.prayer-search {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: .65rem;
  border-radius: 22px;
  background: #fff;
  box-shadow: 0 18px 50px rgba(0,0,0,.16);
}
.prayer-search input {
  flex: 1;
  min-width: 0;
  border: 0;
  outline: 0;
  font-size: 1rem;
}
.prayer-filter {
  border: 1px solid rgba(255,255,255,.22);
  background: rgba(255,255,255,.11);
  color: #fff;
  border-radius: 999px;
  padding: .75rem 1.1rem;
  font-weight: 800;
  transition: .25s ease;
}
.prayer-filter.active,
.prayer-filter:hover {
  background: #fff;
  color: var(--emerald);
  transform: translateY(-2px);
}
.eid-top-banner {
  position: relative;
  overflow: hidden;
  margin-top: -70px;
  z-index: 2;
  border-radius: 30px;
  background: linear-gradient(135deg, #073d2e, #0c7a58);
  color: #fff;
  box-shadow: 0 26px 80px rgba(6,63,46,.24);
}
.eid-chip {
  border-radius: 22px;
  background: rgba(255,255,255,.12);
  border: 1px solid rgba(255,255,255,.15);
  padding: 1rem;
  height: 100%;
}
.section-eyebrow {
  color: var(--emerald-2);
  font-weight: 900;
  letter-spacing: .08em;
  text-transform: uppercase;
  font-size: .78rem;
}
.premium-stat-card {
  border: 1px solid rgba(6,63,46,.08);
  border-radius: 24px;
  background: rgba(255,255,255,.78);
  box-shadow: 0 18px 52px rgba(16,35,29,.08);
  padding: 1.35rem;
  height: 100%;
}
.premium-stat-card i {
  width: 46px;
  height: 46px;
  display: inline-grid;
  place-items: center;
  border-radius: 16px;
  color: var(--emerald);
  background: var(--mint);
}
.masjid-card {
  position: relative;
  border-radius: 30px;
  padding: 1px;
  background: linear-gradient(145deg, rgba(215,181,109,.85), rgba(13,121,88,.48), rgba(255,255,255,.8));
  box-shadow: 0 24px 70px rgba(6,63,46,.12);
  transition: transform .25s ease, box-shadow .25s ease;
}
.masjid-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 34px 90px rgba(6,63,46,.22);
}
.masjid-card-inner {
  overflow: hidden;
  border-radius: 29px;
  background: rgba(255,255,255,.86);
  backdrop-filter: blur(16px);
  height: 100%;
}
.masjid-image {
  position: relative;
  min-height: 230px;
  background:
    linear-gradient(180deg, rgba(0,0,0,.05), rgba(3,45,32,.78)),
    url('https://images.unsplash.com/photo-1542816417-0983c9c9ad53?auto=format&fit=crop&w=1200&q=80') center/cover;
}
.floating-badges {
  position: absolute;
  inset: 1rem 1rem auto 1rem;
  display: flex;
  justify-content: space-between;
  gap: .5rem;
}
.lux-badge {
  border-radius: 999px;
  padding: .45rem .75rem;
  font-size: .76rem;
  font-weight: 900;
  background: rgba(255,255,255,.88);
  color: var(--emerald);
  box-shadow: 0 10px 28px rgba(0,0,0,.12);
}
.time-panel {
  border-radius: 22px;
  background: linear-gradient(135deg, #f4fff8, #ffffff);
  border: 1px solid rgba(6,63,46,.08);
  padding: 1rem;
}
.facility-pill {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  padding: .45rem .7rem;
  border-radius: 999px;
  background: #f1f8f4;
  color: #426157;
  font-size: .78rem;
  font-weight: 800;
}
.action-icon {
  width: 42px;
  height: 42px;
  display: inline-grid;
  place-items: center;
  border-radius: 14px;
}
.countdown {
  color: var(--gold);
  font-weight: 900;
}
@media (max-width: 767px) {
  .prayer-hero { min-height: 720px; }
  .prayer-search { flex-direction: column; align-items: stretch; }
  .prayer-search input { width: 100%; padding: .5rem; }
}
</style>

<div class="premium-prayer-page">
  <section class="prayer-hero">
    <div class="container position-relative py-5">
      <div class="row align-items-center g-5">
        <div class="col-lg-8">
          <div class="hero-kicker animate__animated animate__fadeInDown">
            <i class="fas fa-star-and-crescent"></i> Premium prayer directory
          </div>
          <h1 class="prayer-title mt-4 mb-3 animate__animated animate__fadeInUp">Juma & Eid Timings</h1>
          <p class="prayer-subtitle mb-4 animate__animated animate__fadeInUp animate__delay-1s">A refined Friday and Eid prayer experience for discovering verified masajid, timing details, facilities, and directions.</p>
          <div class="hero-glass p-3 p-md-4" data-aos="fade-up">
            <div class="row g-3 mb-3">
              <div class="col-6 col-md-4"><div class="hero-stat"><strong>{{ $totalMasjids }}</strong><span>Masajid</span></div></div>
              <div class="col-6 col-md-4"><div class="hero-stat"><strong>{{ $todaysJuma }}</strong><span>Today's Juma</span></div></div>
              <div class="col-12 col-md-4"><div class="hero-stat"><strong>{{ $upcomingEid }}</strong><span>Upcoming Eid</span></div></div>
            </div>
            <div class="prayer-search mb-3">
              <i class="fas fa-search text-success ms-2"></i>
              <input id="jumaSearch" type="search" placeholder="Search mosque, city, sect, or address">
              <button class="btn btn-success rounded-pill px-4" type="button">Search</button>
            </div>
            <div class="d-flex flex-wrap gap-2">
              <button class="prayer-filter active" type="button" data-sect-filter="All">All</button>
              <button class="prayer-filter" type="button" data-sect-filter="Sunni">Sunni</button>
              <button class="prayer-filter" type="button" data-sect-filter="Shia">Shia</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 d-none d-lg-block">
          <div class="hero-glass p-4 text-white" data-aos="zoom-in">
            <i class="fas fa-mosque fa-4x text-warning mb-4"></i>
            <h2 class="h4 fw-black mb-3">Elegant prayer planning</h2>
            <p class="text-white-50 mb-0">Browse high-signal cards with schedule, amenities, call, directions, and detail actions.</p>
          </div>
        </div>
      </div>
    </div>
    <svg class="hero-wave" viewBox="0 0 1440 110" preserveAspectRatio="none"><path fill="currentColor" d="M0,80 C280,20 410,120 720,72 C990,30 1130,18 1440,70 L1440,110 L0,110 Z"/></svg>
  </section>

  <div class="container">
    <section class="eid-top-banner p-4 p-lg-5" data-aos="fade-up">
      <div class="row g-3 align-items-center position-relative">
        <div class="col-lg-4">
          <div class="d-flex align-items-center gap-3">
            <i class="fas fa-kaaba fa-3x text-warning"></i>
            <div>
              <div class="text-white-50 small fw-bold text-uppercase">Upcoming Eid Countdown</div>
              <div id="eidCountdown" class="h3 fw-black mb-0">Preparing...</div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4">
          <div class="eid-chip">
            <div class="text-white-50 small">Current Hijri Date</div>
            <strong><i class="fas fa-moon me-2 text-warning"></i>{{ $hijriDate }}</strong>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4">
          <div class="eid-chip">
            <div class="text-white-50 small">Islamic Date</div>
            <strong><i class="fas fa-calendar-days me-2 text-warning"></i>{{ now()->format('l, d M Y') }}</strong>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5">
      <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
        <div>
          <div class="section-eyebrow">Prayer intelligence</div>
          <h2 class="fw-black mb-1">Live Directory Statistics</h2>
          <p class="text-secondary mb-0">Fast context for planning Friday and Eid prayers.</p>
        </div>
      </div>
      <div class="row g-3">
        <div class="col-6 col-lg"><div class="premium-stat-card"><i class="fas fa-mosque mb-3"></i><h3 class="h4 fw-black mb-0">{{ $totalMasjids }}</h3><span class="text-secondary small">Total Mosques</span></div></div>
        <div class="col-6 col-lg"><div class="premium-stat-card"><i class="fas fa-users mb-3"></i><h3 class="h4 fw-black mb-0">{{ $sunniCount }}</h3><span class="text-secondary small">Sunni</span></div></div>
        <div class="col-6 col-lg"><div class="premium-stat-card"><i class="fas fa-hand-holding-heart mb-3"></i><h3 class="h4 fw-black mb-0">{{ $shiaCount }}</h3><span class="text-secondary small">Shia</span></div></div>
        <div class="col-6 col-lg"><div class="premium-stat-card"><i class="fas fa-clock mb-3"></i><h3 class="h4 fw-black mb-0">{{ $averageJumaTime }}</h3><span class="text-secondary small">Average Juma Time</span></div></div>
        <div class="col-12 col-lg"><div class="premium-stat-card"><i class="fas fa-location-crosshairs mb-3"></i><h3 class="h6 fw-black mb-0">{{ $nearestMosque?->name ?? 'Not available' }}</h3><span class="text-secondary small">Nearest Mosque</span></div></div>
      </div>
    </section>

    <section class="pb-5">
      <div class="row g-4" id="masjidGrid">
        @forelse($masjids as $masjid)
          @php
            $facilityNames = $masjid->facilities?->pluck('name')->map(fn($name) => strtolower($name))->all() ?? [];
            $hasParking = $masjid->parking || collect($facilityNames)->contains(fn($name) => str_contains($name, 'parking'));
            $hasWheelchair = $masjid->wheelchair || collect($facilityNames)->contains(fn($name) => str_contains($name, 'wheel'));
            $hasWudu = collect($facilityNames)->contains(fn($name) => str_contains($name, 'wudu') || str_contains($name, 'ablution'));
            $directions = $masjid->latitude && $masjid->longitude
              ? 'https://www.google.com/maps/dir/?api=1&destination=' . $masjid->latitude . ',' . $masjid->longitude
              : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($masjid->address ?: $masjid->name);
            $searchText = strtolower(trim($masjid->name . ' ' . $masjid->city . ' ' . $masjid->area . ' ' . $masjid->address . ' ' . $masjid->sect));
          @endphp
          <div class="col-md-6 col-xl-4 masjid-item" data-aos="fade-up" data-sect="{{ $masjid->sect ?? 'Community' }}" data-search="{{ $searchText }}">
            <article class="masjid-card h-100">
              <div class="masjid-card-inner">
                <div class="masjid-image">
                  <div class="floating-badges">
                    <span class="lux-badge"><i class="fas fa-shield-halved me-1"></i>Verified</span>
                    @if($masjid->is_featured)
                      <span class="lux-badge"><i class="fas fa-star me-1 text-warning"></i>Featured</span>
                    @endif
                  </div>
                </div>
                <div class="p-4">
                  <div class="d-flex justify-content-between gap-3 mb-2">
                    <div>
                      <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">{{ $masjid->sect ?? 'Community' }}</span>
                      <h3 class="h4 fw-black mt-2 mb-1">{{ $masjid->name }}</h3>
                      <p class="text-secondary small mb-0"><i class="fas fa-location-dot me-1"></i>{{ $masjid->city ?: $masjid->area ?: $masjid->address }}</p>
                    </div>
                    <button type="button" class="btn btn-light action-icon bookmark-btn" data-bookmark="juma-{{ $masjid->id }}" title="Bookmark">
                      <i class="far fa-bookmark"></i>
                    </button>
                  </div>
                  <div class="time-panel my-3">
                    <div class="d-flex justify-content-between align-items-center mb-2"><span class="text-secondary small">Juma Time</span><strong class="text-success">{{ $masjid->juma_time ? date('h:i A', strtotime($masjid->juma_time)) : 'Not set' }}</strong></div>
                    <div class="d-flex justify-content-between align-items-center"><span class="text-secondary small">Eid Time</span><strong class="text-success">{{ $masjid->eid_time ? date('h:i A', strtotime($masjid->eid_time)) : 'Not set' }}</strong></div>
                  </div>
                  <div class="row g-2 mb-3">
                    <div class="col-6"><span class="facility-pill"><i class="fas fa-user-tie"></i>{{ $masjid->description ? Str::limit($masjid->description, 18) : 'Prayer Leader' }}</span></div>
                    <div class="col-6"><span class="facility-pill"><i class="fas fa-users"></i>Capacity open</span></div>
                    <div class="col-6"><span class="facility-pill"><i class="fas fa-square-parking"></i>{{ $hasParking ? 'Parking' : 'Parking info' }}</span></div>
                    <div class="col-6"><span class="facility-pill"><i class="fas fa-wheelchair"></i>{{ $hasWheelchair ? 'Wheelchair' : 'Access info' }}</span></div>
                    <div class="col-6"><span class="facility-pill"><i class="fas fa-droplet"></i>{{ $hasWudu ? 'Wudu Area' : 'Wudu info' }}</span></div>
                    <div class="col-6"><span class="facility-pill"><i class="fas fa-hourglass-half"></i><span class="juma-countdown countdown" data-juma-time="{{ $masjid->juma_time ?: '13:00' }}">Soon</span></span></div>
                  </div>
                  <div class="d-grid gap-2">
                    <div class="d-flex gap-2">
                      <a href="{{ $directions }}" target="_blank" class="btn btn-success flex-fill"><i class="fas fa-route me-1"></i>Direction</a>
                      <a href="{{ $masjid->phone ? 'tel:' . $masjid->phone : '#' }}" class="btn btn-outline-success flex-fill {{ $masjid->phone ? '' : 'disabled' }}"><i class="fas fa-phone me-1"></i>Call</a>
                    </div>
                    <a href="{{ route('timings.juma.details', $masjid) }}" class="btn btn-dark rounded-pill"><i class="fas fa-arrow-right me-2"></i>View Details</a>
                  </div>
                </div>
              </div>
            </article>
          </div>
        @empty
          <div class="col-12">
            <div class="premium-stat-card text-center py-5">
              <i class="fas fa-mosque fa-2x mb-3"></i>
              <h3 class="fw-black">No Juma and Eid timings are available right now.</h3>
            </div>
          </div>
        @endforelse
      </div>
    </section>
  </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  AOS.init({ duration: 700, once: true, offset: 80 });

  const search = document.getElementById('jumaSearch');
  const filters = document.querySelectorAll('[data-sect-filter]');
  const cards = document.querySelectorAll('.masjid-item');
  let activeSect = 'All';

  function applyFilters() {
    const term = (search?.value || '').toLowerCase().trim();
    cards.forEach(card => {
      const matchesSect = activeSect === 'All' || card.dataset.sect === activeSect;
      const matchesSearch = !term || card.dataset.search.includes(term);
      card.classList.toggle('d-none', !(matchesSect && matchesSearch));
    });
  }

  search?.addEventListener('input', applyFilters);
  filters.forEach(button => {
    button.addEventListener('click', () => {
      filters.forEach(item => item.classList.remove('active'));
      button.classList.add('active');
      activeSect = button.dataset.sectFilter;
      applyFilters();
    });
  });

  document.querySelectorAll('.bookmark-btn').forEach(button => {
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

  function nextFridayAt(timeValue) {
    const [hour, minute] = String(timeValue || '13:00').split(':').map(Number);
    const now = new Date();
    const target = new Date(now);
    const daysUntilFriday = (5 - now.getDay() + 7) % 7;
    target.setDate(now.getDate() + daysUntilFriday);
    target.setHours(hour || 13, minute || 0, 0, 0);
    if (target <= now) target.setDate(target.getDate() + 7);
    return target;
  }

  function renderCountdowns() {
    document.querySelectorAll('.juma-countdown').forEach(node => {
      const diff = nextFridayAt(node.dataset.jumaTime) - new Date();
      const days = Math.floor(diff / 86400000);
      const hours = Math.floor(diff % 86400000 / 3600000);
      node.textContent = days ? `${days}d ${hours}h` : `${hours}h`;
    });
    const eidTarget = new Date(new Date().getFullYear(), 11, 31, 8, 0, 0);
    if (eidTarget <= new Date()) eidTarget.setFullYear(eidTarget.getFullYear() + 1);
    const eidDiff = eidTarget - new Date();
    document.getElementById('eidCountdown').textContent = `${Math.floor(eidDiff / 86400000)} days`;
  }
  renderCountdowns();
  setInterval(renderCountdowns, 60000);
});
</script>
@endsection
