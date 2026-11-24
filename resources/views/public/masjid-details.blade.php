@extends('layouts.public')

@section('title', $masjid->name)

@section('content')
<section class="breadcrumb-section">
  <div class="container">
    <h2 class="breadcrumb-title">{{ $masjid->name }}</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('masjids.index') }}">Masajid</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $masjid->name }} Details</li>
      </ol>
    </nav>
  </div>
</section>

<section class="container my-5 animate-fadeInUp">
  <div class="profile-header mb-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <span class="badge bg-white text-success mb-2">{{ $masjid->sect ?? 'Sunni' }} Sect</span>
        @if($masjid->construction_status && $masjid->construction_status !== 'Completed')
          <span class="badge bg-warning text-dark mb-2 ms-2"><i class="fas fa-tools"></i> {{ $masjid->construction_status }}</span>
        @endif
        <h2 class="profile-name text-white fw-bold mb-2">{{ $masjid->name }}</h2>
        <p class="profile-address text-white-50 mb-0"><i class="fas fa-map-marker-alt me-1"></i> {{ $masjid->address }}</p>
      </div>
      <div>
        <a href="{{ route('map') }}" class="btn btn-outline-custom bg-white border-0 px-4 py-2 fw-bold rounded-pill text-success">View Other Masajid</a>
      </div>
    </div>
  </div>
  

  <div class="row g-4 mb-5">
  @if($recommendedMasjid)

<div class="alert alert-success shadow-sm border-0 mb-4">

    <h5 class="fw-bold mb-3">
        <i class="fas fa-lightbulb text-warning"></i>
        Smart Prayer Recommendation
    </h5>

    <p class="mb-2">
        You may reach
        <strong>{{ $recommendedMasjid->name }}</strong>
        before Jama'at.
    </p>

    <ul class="mb-3">

        <li>
            Distance:
            <strong>{{ number_format($recommendedMasjid->distance,2) }} km</strong>
        </li>

        <li>
            Address:
            {{ $recommendedMasjid->address }}
        </li>

        <li>
            Sect:
            {{ $recommendedMasjid->sect }}
        </li>

    </ul>

    <div class="d-flex gap-2">

        <a href="{{ route('masjids.show',$recommendedMasjid) }}"
           class="btn btn-success">

            View Masjid

        </a>

        <a href="https://www.google.com/maps/search/?api=1&query={{ $recommendedMasjid->latitude }},{{ $recommendedMasjid->longitude }}"
           target="_blank"
           class="btn btn-outline-success">

            Directions

        </a>

    </div>

</div>


@endif
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <div class="bg-success bg-gradient text-white p-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold"><i class="fas fa-clock me-2"></i> Namaz Timings</h5>
          <span class="badge bg-white text-success fw-bold">Daily Schedule</span>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover prayer-table mb-0">
            <thead>
              <tr>
                <th>Prayer</th>
                <th>Azaan / Jama'at Time</th>
              </tr>
            </thead>
            <tbody>
              @php
                $prayerMap = [
                  ['key' => 'fajr', 'label' => 'Fajr', 'icon' => 'feather-alt', 'class' => 'prayer-icon-fajr'],
                  ['key' => 'zuhr', 'label' => 'Zuhr', 'icon' => 'sun', 'class' => 'prayer-icon-zuhr'],
                  ['key' => 'asr', 'label' => 'Asr', 'icon' => 'cloud-sun', 'class' => 'prayer-icon-asr'],
                  ['key' => 'maghrib', 'label' => 'Maghrib', 'icon' => 'cloud-moon', 'class' => 'prayer-icon-maghrib'],
                  ['key' => 'isha', 'label' => 'Isha', 'icon' => 'moon', 'class' => 'prayer-icon-isha'],
                ];
              @endphp
              @foreach($prayerMap as $item)
                <tr class="{{ ($nextPrayer['name'] ?? '') === $item['label'] ? 'current-prayer' : '' }}">
                  <td>
                    <span class="prayer-icon {{ $item['class'] }}"><i class="fas fa-{{ $item['icon'] }}"></i></span>
                    <strong>{{ $item['label'] }}</strong>
                  </td>
                  <td>{{ date('h:i A', strtotime($masjid->{$item['key']})) }}</td>
                </tr>
              @endforeach
              <tr class="table-success">
                <td><span class="prayer-icon bg-success text-white"><i class="fas fa-users"></i></span> <strong>Juma prayer</strong></td>
                <td><strong>{{ $masjid->juma_time ? date('h:i A', strtotime($masjid->juma_time)) : 'N/A' }}</strong></td>
              </tr>
              @if($masjid->eid_time)
                <tr class="table-warning">
                  <td><span class="prayer-icon bg-warning text-dark"><i class="fas fa-star-and-crescent"></i></span> <strong>Eid prayer</strong></td>
                  <td><strong>{{ date('h:i A', strtotime($masjid->eid_time)) }}</strong></td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>

      <div class="card shadow-sm border-0 rounded-4 p-4">
        <h5 class="text-success fw-bold mb-3"><i class="fas fa-info-circle me-1"></i> About This Masjid</h5>
        <p class="text-secondary mb-4">{{ $masjid->description ?: 'No description or announcements available for this mosque.' }}</p>

        <h5 class="text-success fw-bold mb-3"><i class="fas fa-thumbs-up me-1"></i> Facilities</h5>
        <div class="d-flex flex-wrap gap-2 mb-4">
          @forelse($masjid->facilities as $facility)
            <span class="badge rounded-pill bg-light text-success border"><i class="fas {{ $facility->icon ?? 'fa-check' }} me-1"></i> {{ $facility->name }}</span>
          @empty
            <span class="text-muted">No specific amenities added.</span>
          @endforelse
          
          @if($masjid->parking)
            <span class="badge rounded-pill bg-light text-success border"><i class="fas fa-parking me-1"></i> Parking Available</span>
          @endif
          @if($masjid->wheelchair)
            <span class="badge rounded-pill bg-light text-success border"><i class="fas fa-wheelchair me-1"></i> Wheelchair Accessible</span>
          @endif
          @if($masjid->ladies_prayer)
            <span class="badge rounded-pill bg-light text-success border"><i class="fas fa-female me-1"></i> Ladies Prayer Area</span>
          @endif
        </div>

        @if($masjid->images->count())
          <h5 class="text-success fw-bold mb-3"><i class="fas fa-images me-1"></i> Gallery</h5>
          <div class="row g-3 mb-4">
            @foreach($masjid->images as $image)
              <div class="col-md-6">
                <img src="{{ asset('storage/'.$image->image_path) }}" class="img-fluid rounded-3" alt="{{ $masjid->name }}" onerror="this.onerror=null;this.src='{{ asset('assets/img/gallery-placeholder.jpg') }}'">
              </div>
            @endforeach
          </div>
        @endif

        <h5 class="text-success fw-bold mb-3 border-top pt-3"><i class="fas fa-address-book me-1"></i> Contact Information</h5>
        <div class="detail-info-item"><span class="info-label">Address:</span><span class="info-value">{{ $masjid->address }}</span></div>
        <div class="detail-info-item"><span class="info-label">Phone:</span><span class="info-value">{{ $masjid->phone ?: 'N/A' }}</span></div>
        <div class="detail-info-item"><span class="info-label">Sect/School:</span><span class="info-value">{{ $masjid->sect ?? 'N/A' }}</span></div>
      </div>

      @php
        $campaignsForMasjid = $masjid->campaigns->sortByDesc('is_emergency')->sortByDesc('is_featured');
      @endphp
      @if($campaignsForMasjid->count())
        <div class="card shadow-sm border-0 rounded-4 p-4 mt-4">
          <h5 class="text-success fw-bold mb-3"><i class="fas fa-hand-holding-heart me-1"></i> Donation Campaigns</h5>
          <div class="row g-3">
            @foreach($campaignsForMasjid as $campaign)
              <div class="col-12">
                <div class="card border rounded-3">
                  <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                      <div>
                        <h6 class="mb-1">{{ $campaign->title }}</h6>
                        <p class="small text-secondary mb-2 text-truncate">{{ $campaign->description ?: 'No details available.' }}</p>
                      </div>
                      <div class="text-end">
                        <span class="badge bg-success-subtle text-success text-capitalize">{{ $campaign->status }}</span>
                        @if($campaign->is_emergency)
                          <span class="badge bg-danger text-white">URGENT</span>
                        @endif
                      </div>
                    </div>

                    <div class="row g-2 mb-3 small text-secondary">
                      <div class="col-4"><strong>Target</strong><br>₹{{ number_format($campaign->target_amount, 2) }}</div>
                      <div class="col-4"><strong>Raised</strong><br>₹{{ number_format($campaign->collected_amount, 2) }}</div>
                      <div class="col-4"><strong>Remaining</strong><br>₹{{ number_format($campaign->remaining_amount, 2) }}</div>
                    </div>

                    <div class="progress mb-3" style="height: 10px;">
                      <div class="progress-bar bg-success" role="progressbar" style="width: {{ $campaign->percentage_completed }}%;" aria-valuenow="{{ $campaign->percentage_completed }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary mb-3">
                      <span>{{ $campaign->progress_label }} complete</span>
                      <span>{{ $campaign->status === 'active' ? 'Accepting donations' : ucfirst($campaign->status) }}</span>
                    </div>

                    <div class="d-grid gap-2">
                      <a href="https://wa.me/92338956547?text={{ urlencode("Assalamu Alaikum,\n\nI would like to donate.\n\nMasjid: {$masjid->name}\n\nCampaign: {$campaign->title}\n\nDonation Amount: PKR \n\nPlease provide the payment details.\n\nJazakAllah Khair.") }}" class="btn btn-success w-100" target="_blank" rel="noopener noreferrer">
                        Donate via WhatsApp
                      </a>
                      <a href="{{ route('donations.show', $campaign) }}" class="btn btn-outline-custom w-100">View Campaign</a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>

    <div class="col-lg-5">
      <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <div class="map-embed" id="detailsMap" style="height: 350px;"></div>
        <div class="card-body">
          <div id="distanceStatus" class="alert alert-info py-2 px-3 mb-3 d-none">
          <div id="recommendationBox" class="alert alert-warning d-none mt-3">
    <h6 class="fw-bold mb-2">
        <i class="fas fa-lightbulb text-warning"></i>
        Smart Recommendation
    </h6>

    <div id="recommendationText"></div>

    <a id="recommendationBtn"
       href="#"
       class="btn btn-warning btn-sm mt-2"
       style="display:none;">
        View Recommended Masjid
    </a>
</div>
            <i class="fas fa-location-crosshairs me-1 animate-pulse"></i>
            <span id="distanceText">Calculating distance...</span>
          </div>
          <div class="row g-2">
            <div class="col-sm-6">
              <a id="btnGetDirections" href="https://www.google.com/maps/search/?api=1&query={{ $masjid->latitude }},{{ $masjid->longitude }}" target="_blank" class="btn btn-gold w-100" data-directions-link="true" data-dest-lat="{{ $masjid->latitude }}" data-dest-lng="{{ $masjid->longitude }}">
                <i class="fas fa-location-arrow me-1"></i> Get Directions
              </a>
            </div>
            <div class="col-sm-6">
              <a href="{{ route('map') }}" class="btn btn-outline-custom w-100">
                <i class="fas fa-map-marked-alt me-1"></i> View Full Map
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-12">
      <h4 class="section-title">Nearby Masajid</h4>
      <p class="section-subtitle">Other mosques located in close proximity</p>
    </div>
  </div>

  <div class="nearby-scroll pb-3">
    @foreach($nearby as $item)
      <div class="masjid-card" style="min-width: 300px; flex-shrink: 0;">
        <div class="card-img-top" style="height: 120px; font-size: 2rem;"><i class="fas fa-mosque"></i></div>
        <div class="card-body p-3">
          <span class="badge badge-sect badge-{{ strtolower($item->sect ?? 'sunni') }} mb-2">{{ $item->sect ?? 'Sunni' }}</span>
          <h6 class="fw-bold text-success mb-1 text-truncate">{{ $item->name }}</h6>
          <p class="text-muted small mb-2 text-truncate"><i class="fas fa-map-marker-alt"></i> {{ $item->address }}</p>
          <span class="badge bg-secondary"><i class="fas fa-route"></i> {{ number_format($item->distance, 2) }} km away</span>
        </div>
        <div class="card-footer p-2 text-center bg-transparent">
          <a href="{{ route('masjids.show', $item) }}" class="btn btn-sm btn-outline-custom w-100 py-1">View Details</a>
        </div>
      </div>
    @endforeach
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const masjid = {
      id: {{ $masjid->id }},
      name: @json($masjid->name),
      address: @json($masjid->address),
      sect: @json($masjid->sect ?? 'Sunni'),
      latitude: {{ (float) $masjid->latitude }},
      longitude: {{ (float) $masjid->longitude }}
    };

    MapHelper.init('detailsMap', masjid.latitude, masjid.longitude, 15);

    const markerPopup = `
      <div class="map-popup text-center">
        <h6><i class="fas fa-mosque text-success"></i> ${masjid.name}</h6>
        <span class="badge bg-${masjid.sect === 'Sunni' ? 'success' : 'info'}">${masjid.sect}</span>
        <p class="small text-muted mb-0 mt-1">${masjid.address}</p>
      </div>`;

    L.marker([masjid.latitude, masjid.longitude], { icon: MapHelper.getMosqueIcon() })
      .bindPopup(markerPopup)
      .addTo(MapHelper.map)
      .openPopup();

    GPS.getLocation().then(pos => {
      MapHelper.addUserMarker(pos.lat, pos.lng);
      const bounds = L.latLngBounds([pos.lat, pos.lng], [masjid.latitude, masjid.longitude]);
      MapHelper.map.fitBounds(bounds, { padding: [50, 50] });

      const dist = haversineDistance(pos.lat, pos.lng, masjid.latitude, masjid.longitude);
      const recommendationBox = document.getElementById("recommendationBox");
const recommendationText = document.getElementById("recommendationText");
const recommendationBtn = document.getElementById("recommendationBtn");

const distanceKm = parseFloat(dist);

if(distanceKm > 3){

    recommendationBox.classList.remove("d-none");

    recommendationText.innerHTML =
    `
    <strong>This masjid is ${distanceKm.toFixed(1)} km away.</strong><br>
    You may miss Jamaat if you leave now.
    <br><br>
    Please check a nearer masjid.
    `;

    recommendationBtn.href="{{ route('map') }}";
    recommendationBtn.style.display="inline-block";
}
      const directionsBtn = document.getElementById('btnGetDirections');
      if (directionsBtn) {
        directionsBtn.href = GPS.buildDirectionsUrl(masjid.latitude, masjid.longitude, pos.lat, pos.lng);
      }

      const distanceStatus = document.getElementById('distanceStatus');
      const distanceText = document.getElementById('distanceText');
      if (distanceStatus && distanceText) {
        distanceText.innerHTML = `This masjid is <strong>${dist} km</strong> away from your current location.`;
        distanceStatus.classList.remove('d-none');
      }
    }).catch(err => {
      console.warn('GPS location access denied or failed on details page:', err.message);
    });
  });
</script>
@endsection
