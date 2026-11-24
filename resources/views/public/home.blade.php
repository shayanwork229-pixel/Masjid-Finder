@extends('layouts.public')

@section('title', 'Find Nearest Masjid')

@section('content')
@include('components.announcement-banner')

{{-- ═══════════════════════════════════════════════════════════
      HERO SECTION
═══════════════════════════════════════════════════════════ --}}
<section class="hero-section text-white py-5">
  <div class="container hero-content">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 animate-fadeInLeft">
        <span class="badge bg-white text-success px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">SMART GPS LOCATOR</span>
        <h1 class="hero-title mb-3">Find Your <span class="highlight">Nearest</span> Masjid &amp; Prayer Timings</h1>
        <p class="hero-subtitle mb-4">Instantly locate mosques around you, view accurate daily prayer times, get directions, and keep track of Friday (Juma) and Eid salah schedules.</p>
        <div class="hero-search mb-3">
          <form action="{{ route('masjids.index') }}" method="GET">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search by masjid name or area..." aria-label="Search masjid">
              <button class="btn btn-gold" type="submit"><i class="fas fa-search"></i> Search</button>
            </div>
          </form>
        </div>
        <button id="btnGPSLocate" class="hero-gps-btn">
          <i class="fas fa-location-crosshairs fa-lg me-1"></i>
          <span>Locate Nearest Masjid</span>
        </button>
        <div id="gpsStatus" class="mt-2 small text-warning d-none"></div>
      </div>
      <div class="col-lg-6 animate-fadeInUp">
        <div class="hero-map-container">
          <div id="heroMap" style="height:100%; width:100%;"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container my-5">

  {{-- ── GPS Recommended Nearest ──────────────────────────────────── --}}
  <div id="recommendedSection" class="row mb-5 d-none">
    <div class="col-12">
      <h3 class="section-title mb-4">Nearest Recommended Masjid</h3>
      <div id="recommendedContainer"></div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════════════════
       STATS + COUNTDOWN ROW
  ═══════════════════════════════════════════════════════════ --}}
  <div class="row g-4 mb-5">
    <div class="col-lg-6">
      <div class="countdown-card" id="countdownCard">
        <div class="countdown-label">Next Prayer Countdown</div>
        <div class="countdown-prayer-name" id="countdownPrayerName">{{ $nextPrayer['name'] }}</div>
        <div class="countdown-digits" id="prayerCountdown">
          <div class="digit-box"><div class="digit-value countdown-hours">00</div><div class="digit-label">Hrs</div></div>
          <div class="digit-box"><div class="digit-value countdown-minutes">00</div><div class="digit-label">Min</div></div>
          <div class="digit-box"><div class="digit-value countdown-seconds">00</div><div class="digit-label">Sec</div></div>
        </div>
        @if($defaultMasjid)
          <p class="mt-3 mb-0 small text-white-50">Based on default: <strong>{{ $defaultMasjid->name }}</strong></p>
        @endif
      </div>
    </div>
    <div class="col-lg-6">
      <div class="row g-4 h-100">
        <div class="col-sm-6">
          <div class="stat-card h-100 d-flex flex-column justify-content-center">
            <div class="stat-icon"><i class="fas fa-mosque"></i></div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Masajid Covered</div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="stat-card h-100 d-flex flex-column justify-content-center">
            <div class="stat-icon bg-info"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $sunniCount }} / {{ $shiaCount }}</div>
            <div class="stat-label">Sunni / Shia Masajid</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════════════════
       QUICK LINKS
  ═══════════════════════════════════════════════════════════ --}}
  <div class="row g-4 mb-5">
    <div class="col-12">
      <div class="row g-4">
        <div class="col-lg-3 col-md-6">
          <div class="stat-card h-100 d-flex flex-column justify-content-center">
            <div class="stat-icon bg-info"><i class="fas fa-calendar-alt"></i></div>
            <a href="{{ route('ramadan') }}" class="stat-label text-decoration-none text-white">Ramadan Calendar</a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-card h-100 d-flex flex-column justify-content-center">
            <div class="stat-icon bg-warning"><i class="fas fa-compass"></i></div>
            <a href="{{ route('qibla') }}" class="stat-label text-decoration-none text-white">Qibla Finder</a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-card h-100 d-flex flex-column justify-content-center">
            <div class="stat-icon bg-success"><i class="fas fa-calendar-day"></i></div>
            <a href="{{ route('events.index') }}" class="stat-label text-decoration-none text-white">Upcoming Events</a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          @include('components.hijri-widget')
        </div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════════════════
       FEATURED MASAJID GRID (3 Cards)
  ═══════════════════════════════════════════════════════════ --}}
  <div class="row mb-4">
    <div class="col-md-6">
      <h3 class="section-title">Featured Masajid</h3>
      <p class="section-subtitle">Discover prominent mosques handpicked for you</p>
    </div>
    <div class="col-md-6 text-md-end d-flex align-items-center justify-content-md-end gap-2 mb-4">
      <button class="sect-filter-btn active" data-filter="all" onclick="filterBySect('all')">All</button>
      <button class="sect-filter-btn" data-filter="Sunni" onclick="filterBySect('Sunni')">Sunni</button>
      <button class="sect-filter-btn" data-filter="Shia" onclick="filterBySect('Shia')">Shia</button>
      <a href="{{ route('masjids.index') }}" class="btn btn-outline-success btn-sm ms-3">View All <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
  </div>

  <div class="row g-4 mb-5" id="featuredGrid">
    @forelse($featured_masjids as $m)
      <div class="col-lg-4 col-md-6 masjid-item-col" data-sect="{{ $m->sect }}">
        <div class="masjid-card h-100 d-flex flex-column">
          <div class="card-img-top">
            <i class="fas fa-mosque"></i>
          </div>
          <div class="card-body flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge badge-sect badge-{{ strtolower($m->sect) }}">{{ $m->sect }}</span>
              <span class="distance-badge d-none" data-lat="{{ $m->latitude }}" data-lng="{{ $m->longitude }}">
                <i class="fas fa-route"></i> Calculating...
              </span>
            </div>

            <h5 class="card-title mb-1">{{ $m->name }}</h5>
            <p class="card-text text-truncate-2 mb-3 small text-muted">
              <i class="fas fa-map-marker-alt text-success me-1"></i> {{ $m->address }}
            </p>

            {{-- ── Facility Badges ─────────────── --}}
            @if($m->facilities && $m->facilities->count() > 0)
              <div class="d-flex flex-wrap gap-1 mb-3">
                @foreach($m->facilities as $facility)
                  <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25"
                        style="font-size: 0.68rem; font-weight: 500;"
                        title="{{ $facility->name }}">
                    <i class="fas {{ $facility->icon ?? 'fa-check-circle' }} me-1"></i>{{ $facility->name }}
                  </span>
                @endforeach
              </div>
            @else
              <p class="text-muted small mb-3"><em>No facilities listed yet.</em></p>
            @endif

            {{-- Prayer Timings Mini Grid --}}
            <div class="row g-1 text-center py-2 px-1 bg-light rounded mb-3 small">
              <div class="col"><strong>Fajr</strong><br>{{ $m->fajr ?? 'N/A' }}</div>
              <div class="col"><strong>Zuhr</strong><br>{{ $m->zuhr ?? 'N/A' }}</div>
              <div class="col"><strong>Asr</strong><br>{{ $m->asr ?? 'N/A' }}</div>
              <div class="col"><strong>Maghrib</strong><br>{{ $m->maghrib ?? 'N/A' }}</div>
              <div class="col"><strong>Isha</strong><br>{{ $m->isha ?? 'N/A' }}</div>
            </div>

            @if($m->juma_time)
              <p class="mb-0 small">
                <i class="fas fa-star-and-crescent text-warning me-1"></i>
                <strong>Juma:</strong> {{ date('h:i A', strtotime($m->juma_time)) }}
              </p>
            @endif
          </div>
          <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
            <a href="{{ route('masjids.show', $m) }}" class="btn btn-outline-custom w-100 btn-sm">
              View Details &amp; Map <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <i class="fas fa-mosque fa-3x text-muted mb-3 d-block"></i>
        <h5 class="text-secondary">No Featured Masajid Found</h5>
        <p class="text-muted">Please run the seeder or mark masajid as featured via the admin panel.</p>
        <a href="{{ route('masjids.index') }}" class="btn btn-success mt-2">Browse All Masajid</a>
      </div>
    @endforelse
  </div>

  {{-- ═══════════════════════════════════════════════════════════
       FEATURED FUNDRAISERS
  ═══════════════════════════════════════════════════════════ --}}
  <div class="row mb-4 mt-5">
    <div class="col-md-8">
      <h3 class="section-title">Featured Fundraisers</h3>
      <p class="section-subtitle">Support urgent masjid campaigns and ongoing community projects.</p>
    </div>
    <div class="col-md-4 text-md-end">
      <a href="{{ route('donations.index') }}" class="btn btn-outline-success btn-sm">View All Campaigns</a>
    </div>
  </div>

  <div class="row g-4 mb-5">
    @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
      @foreach($featuredCampaigns as $campaign)
        <div class="col-lg-4 col-md-6">
          <div class="masjid-card h-100">
            @if($campaign->image_path)
              <img src="{{ asset('storage/'.$campaign->image_path) }}" class="card-img-top" alt="{{ $campaign->title }}" onerror="this.onerror=null;this.src='{{ asset('assets/img/donation-placeholder.jpg') }}'">
            @else
              <div class="card-img-top"><i class="fas fa-hand-holding-heart"></i></div>
            @endif
            <div class="card-body d-flex flex-column">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge badge-sect badge-{{ strtolower($campaign->masjid->sect ?? 'Sunni') }}">{{ $campaign->masjid->sect ?? 'Sunni' }}</span>
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
              <p class="card-text text-truncate-2 mb-3 small text-muted">{{ $campaign->description ?: 'No additional details provided for this campaign.' }}</p>
              
              <div class="row g-2 mb-3 small text-secondary">
                <div class="col-4">
                  <strong>Target</strong><br>₹{{ number_format($campaign->target_amount, 2) }}
                </div>
                <div class="col-4">
                  <strong>Raised</strong><br>₹{{ number_format($campaign->collected_amount, 2) }}
                </div>
                <div class="col-4">
                  <strong>Remaining</strong><br>₹{{ number_format($campaign->remaining_amount, 2) }}
                </div>
              </div>
              
              <div class="progress mb-3" style="height: 12px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $campaign->percentage_completed }}%;" aria-valuenow="{{ $campaign->percentage_completed }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              
              <div class="d-flex justify-content-between small text-secondary mb-3">
                <div><strong>{{ $campaign->progress_label }}</strong> complete</div>
                <div><strong>Left:</strong> ₹{{ number_format($campaign->remaining_amount, 2) }}</div>
              </div>
              
              @php
                $daysRemaining = $campaign->end_date ? now()->diffInDays($campaign->end_date, false) : null;
              @endphp
              @if($daysRemaining !== null)
                <div class="mb-3 small text-secondary">Days Remaining: {{ $daysRemaining >= 0 ? $daysRemaining : 0 }}</div>
              @endif
              
              <div class="mt-auto d-grid gap-2">
                <a href="{{ route('donations.show', $campaign) }}" class="btn btn-outline-custom w-100">View Campaign</a>
                <a href="https://wa.me/92338956547?text={{ urlencode("Assalamu Alaikum,\n\nI would like to donate.\n\nMasjid: {$campaign->masjid->name}\n\nCampaign: {$campaign->title}\n\nDonation Amount: PKR \n\nPlease provide the payment details.\n\nJazakAllah Khair.") }}" class="btn btn-success w-100" target="_blank" rel="noopener noreferrer">
                  Donate via WhatsApp
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @else
      <div class="col-12 text-center py-5">
        <i class="fas fa-heart fa-3x text-muted mb-3 d-block"></i>
        <h5 class="text-secondary">No Featured Fundraisers Yet</h5>
        <p class="text-muted">Check back later for upcoming donation campaigns.</p>
      </div>
    @endif
  </div>

  {{-- ═══════════════════════════════════════════════════════════
       RAMADAN 2026 TIMETABLE (30 Days)
  ═══════════════════════════════════════════════════════════ --}}
  <div class="row mb-4 mt-5">
    <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
      <div>
        <h3 class="section-title mb-1"><i class="fas fa-moon text-warning me-2"></i>Ramadan 2026 Timetable</h3>
        <p class="section-subtitle mb-0">Karachi — Complete Sehri &amp; Iftar schedule</p>
      </div>
      <a href="{{ route('ramadan') }}" class="btn btn-outline-warning btn-sm">
        Full Ramadan Page <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0 rounded-4 mb-5">
    <div class="card-header bg-warning bg-opacity-10 border-0 rounded-top-4 py-3 px-4 d-flex align-items-center gap-2">
      <i class="fas fa-calendar-alt text-warning"></i>
      <strong class="text-dark">30-Day Schedule — 2026</strong>
      <span class="badge bg-warning text-dark ms-auto">Karachi</span>
    </div>
    @if($ramadan_days && $ramadan_days->count() > 0)
      <div style="max-height: 420px; overflow-y: auto;">
        <table class="table table-hover table-bordered mb-0" id="ramadanTable">
          <thead class="table-warning sticky-top">
            <tr class="text-center">
              <th class="py-3" style="width: 70px;">Day</th>
              <th class="py-3"><i class="fas fa-moon me-1 text-muted"></i> Sehri (End)</th>
              <th class="py-3"><i class="fas fa-sun me-1 text-warning"></i> Iftar (Start)</th>
              <th class="py-3">Notes</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ramadan_days as $day)
              <tr class="text-center {{ $day->day === 1 || $day->day === 15 || $day->day === 30 ? 'table-success fw-bold' : '' }}">
                <td class="py-2">
                  <span class="badge {{ $day->day === 1 || $day->day === 30 ? 'bg-warning text-dark' : 'bg-light text-dark border' }} rounded-pill px-2">
                    {{ $day->day }}
                  </span>
                </td>
                <td class="py-2">
                  <span class="text-primary fw-semibold">
                    <i class="fas fa-moon me-1 small"></i>
                    @if($day->sehri_time)
                      {{ date('h:i A', strtotime($day->sehri_time)) }}
                    @else
                      N/A
                    @endif
                  </span>
                </td>
                <td class="py-2">
                  <span class="text-warning fw-semibold">
                    <i class="fas fa-sun me-1 small"></i>
                    @if($day->iftar_time)
                      {{ date('h:i A', strtotime($day->iftar_time)) }}
                    @else
                      N/A
                    @endif
                  </span>
                </td>
                <td class="py-2 text-muted small">
                  {{ $day->notes ?? '—' }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="card-body text-center py-5">
        <i class="fas fa-moon fa-3x text-muted mb-3 d-block"></i>
        <h5 class="text-secondary">No Ramadan Timings Available</h5>
        <p class="text-muted">Please run the <code>EnhancedContentSeeder</code> to populate the Ramadan timetable.</p>
      </div>
    @endif
  </div>

  {{-- ═══════════════════════════════════════════════════════════
       EVENTS CALENDAR (FullCalendar v6)
  ═══════════════════════════════════════════════════════════ --}}
  <div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
      <div>
        <h3 class="section-title mb-1"><i class="fas fa-calendar-day text-success me-2"></i>Community Events Calendar</h3>
        <p class="section-subtitle mb-0">Upcoming masjid events and community programs</p>
      </div>
      <a href="{{ route('events.index') }}" class="btn btn-outline-success btn-sm">
        All Events <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0 rounded-4 mb-5">
    <div class="card-body p-3 p-md-4">
      <div id="masjid-event-calendar"></div>
    </div>
  </div>

</div>{{-- /container --}}

{{-- FullCalendar v6 CDN --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

  // ─── FullCalendar Init ────────────────────────────────────────────────────
  const calendarEl = document.getElementById('masjid-event-calendar');
  if (calendarEl) {
    const calendarEvents = {!! json_encode($calendar_events) !!};

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView:       'dayGridMonth',
      headerToolbar: {
        left:   'prev,next today',
        center: 'title',
        right:  'dayGridMonth,listWeek'
      },
      events:       calendarEvents,
      eventClick:   function(info) {
        if (info.event.url) {
          info.jsEvent.preventDefault();
          window.location.href = info.event.url;
        }
      },
      height:        'auto',
      aspectRatio:   1.8,
      buttonText: {
        today:    'Today',
        month:    'Month',
        listWeek: 'List'
      },
      eventDisplay: 'block',
      dayMaxEvents: 3,
      moreLinkText: function(n) { return '+ ' + n + ' more'; },
    });

    calendar.render();
  }

  // ─── Masjid Map ───────────────────────────────────────────────────────────
  const masjids = @json($featured->values());
  MapHelper.init('heroMap', 24.8607, 67.0011, 12);
  MapHelper.addMasjidMarkers(masjids);

  @if($defaultMasjid)
    PrayerCountdown.start(@json($defaultMasjid), 'prayerCountdown');
  @endif

  // ─── GPS Locate Button ────────────────────────────────────────────────────
  const btnGPS       = document.getElementById('btnGPSLocate');
  const gpsStatus           = document.getElementById('gpsStatus');
  const recommendedSection  = document.getElementById('recommendedSection');
  const recommendedContainer = document.getElementById('recommendedContainer');

  btnGPS?.addEventListener('click', () => {
    btnGPS.disabled = true;
    btnGPS.querySelector('span').textContent = 'Locating...';
    gpsStatus.classList.remove('d-none');
    gpsStatus.textContent = 'Acquiring GPS location...';

    GPS.getLocation().then(pos => {
      btnGPS.querySelector('span').textContent = 'Location Updated';
      gpsStatus.innerHTML = '<i class="fas fa-check-circle"></i> Location acquired.';
      gpsStatus.className = 'mt-2 small text-white';
      MapHelper.addUserMarker(pos.lat, pos.lng);
      MapHelper.map.setView([pos.lat, pos.lng], 13);
      MapHelper.addMasjidMarkers(masjids, pos.lat, pos.lng);

      document.querySelectorAll('.distance-badge').forEach(badge => {
        const dist = haversineDistance(pos.lat, pos.lng, parseFloat(badge.dataset.lat), parseFloat(badge.dataset.lng));
        badge.innerHTML = `<i class="fas fa-route"></i> ${dist} km`;
        badge.classList.remove('d-none');
      });

      // Find nearest masjid
      let nearest  = null;
      let minDist  = Infinity;
      masjids.forEach(m => {
        const dist = parseFloat(haversineDistance(pos.lat, pos.lng, m.latitude, m.longitude));
        if (dist < minDist) { minDist = dist; nearest = m; }
      });

      if (nearest) {
        recommendedSection.classList.remove('d-none');
        recommendedContainer.innerHTML = `
          <div class="recommended-card p-4 animate-fadeInUp">
            <div class="row g-4 align-items-center">
              <div class="col-md-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge badge-sect badge-${nearest.sect.toLowerCase()}">${nearest.sect}</span>
                  <span class="badge bg-primary rounded-pill"><i class="fas fa-route"></i> ${minDist.toFixed(2)} km away</span>
                </div>
                <h4 class="text-success fw-bold">${nearest.name}</h4>
                <p class="text-secondary mb-3"><i class="fas fa-map-marker-alt"></i> ${nearest.address}</p>
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ url('/masjids') }}/${nearest.id}" class="btn btn-primary-custom btn-sm">View Full Profile <i class="fas fa-chevron-right ms-1"></i></a>
                  <a href="${GPS.buildDirectionsUrl(nearest.latitude, nearest.longitude, pos.lat, pos.lng)}" target="_blank" class="btn btn-gold btn-sm" data-directions-link="true" data-dest-lat="${nearest.latitude}" data-dest-lng="${nearest.longitude}"><i class="fas fa-location-arrow me-1"></i> Get Directions</a>
                </div>
              </div>
              <div class="col-md-5">
                <div class="p-3 bg-light rounded-4">
                  <h6 class="text-success fw-bold mb-3 border-bottom pb-2"><i class="fas fa-clock"></i> Today's Prayer Timings</h6>
                  <table class="table table-sm table-borderless mb-0">
                    <tr><td><strong>Fajr</strong></td><td class="text-end">${formatTime12h(nearest.fajr)}</td></tr>
                    <tr><td><strong>Zuhr</strong></td><td class="text-end">${formatTime12h(nearest.zuhr)}</td></tr>
                    <tr><td><strong>Asr</strong></td><td class="text-end">${formatTime12h(nearest.asr)}</td></tr>
                    <tr><td><strong>Maghrib</strong></td><td class="text-end">${formatTime12h(nearest.maghrib)}</td></tr>
                    <tr><td><strong>Isha</strong></td><td class="text-end">${formatTime12h(nearest.isha)}</td></tr>
                    <tr class="table-success border-top"><td><strong>Juma</strong></td><td class="text-end"><strong>${formatTime12h(nearest.juma_time)}</strong></td></tr>
                  </table>
                </div>
              </div>
            </div>
          </div>`;
        PrayerCountdown.start(nearest, 'prayerCountdown');
      }
    }).catch(() => {
      btnGPS.disabled = false;
      btnGPS.querySelector('span').textContent = 'Locate Nearest Masjid';
      gpsStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> GPS access denied or timed out.';
      gpsStatus.className = 'mt-2 small text-danger';
    });
  });

});
</script>
@endsection