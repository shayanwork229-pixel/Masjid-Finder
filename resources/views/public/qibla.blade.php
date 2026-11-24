@extends('layouts.public')

@section('title', 'Qibla Finder')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <h2 class="section-title">Qibla Direction Finder</h2>
        <p class="section-subtitle">Use your device location to calculate the direction of the Kaaba and align your prayer with confidence.</p>
        <div class="card shadow-sm border-0 rounded-4 p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-compass text-success me-2"></i> Live Compass</h5>
            <button class="btn btn-success btn-sm" id="qiblaLocateBtn"><i class="fas fa-location-crosshairs me-1"></i> Detect Location</button>
          </div>
          <div class="text-center py-4">
            <div id="qiblaCompass" class="mx-auto rounded-circle border border-success border-4 d-flex align-items-center justify-content-center" style="width:220px; height:220px; background: radial-gradient(circle, #f7fff9 0%, #eaf7ec 100%);">
              <div class="position-relative" style="width:100%; height:100%;">
                <div class="position-absolute top-50 start-50 translate-middle" style="width: 12px; height: 120px; background: linear-gradient(180deg, #1d6f42 0%, #2d9f59 100%); transform-origin: center bottom;" id="qiblaNeedle"></div>
                <div class="position-absolute top-50 start-50 translate-middle rounded-circle bg-white border border-success" style="width:62px; height:62px;"></div>
              </div>
            </div>
            <div class="mt-4">
              <div class="h2 fw-bold text-success" id="qiblaDegrees">0°</div>
              <div class="text-muted">Direction toward the Kaaba</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4 p-4">
          <h5 class="mb-3"><i class="fas fa-info-circle text-info me-2"></i> How it works</h5>
          <ul class="list-unstyled small text-muted">
            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Uses your current GPS location.</li>
            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Calculates the bearing toward Makkah.</li>
            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Updates as your device orientation changes.</li>
          </ul>
          <div class="alert alert-light border mt-3">
            <strong>Tip:</strong> Hold your phone flat and rotate it until the needle points to the Kaaba direction.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const needle = document.getElementById('qiblaNeedle');
    const degreesEl = document.getElementById('qiblaDegrees');
    const locateBtn = document.getElementById('qiblaLocateBtn');

    const makkah = { lat: 21.4225, lng: 39.8262 };
    const normalize = (value) => ((value % 360) + 360) % 360;
    const bearingToMakkah = (lat, lng) => {
      const lat1 = lat * Math.PI / 180;
      const lat2 = makkah.lat * Math.PI / 180;
      const dLon = (makkah.lng - lng) * Math.PI / 180;
      const y = Math.sin(dLon) * Math.cos(lat2);
      const x = Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(dLon);
      const brng = Math.atan2(y, x) * 180 / Math.PI;
      return normalize(360 - ((brng + 360) % 360));
    };

    const updateQibla = (degree) => {
      if (needle) needle.style.transform = `translate(-50%, -100%) rotate(${degree}deg)`;
      if (degreesEl) degreesEl.textContent = `${Math.round(degree)}°`;
    };

    const useLocation = () => {
      if (!navigator.geolocation) {
        alert('Geolocation is not supported on this device.');
        return;
      }
      locateBtn.disabled = true;
      locateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Detecting';
      navigator.geolocation.getCurrentPosition((position) => {
        const degree = bearingToMakkah(position.coords.latitude, position.coords.longitude);
        updateQibla(degree);
        locateBtn.disabled = false;
        locateBtn.innerHTML = '<i class="fas fa-location-crosshairs me-1"></i> Detect Location';
      }, () => {
        updateQibla(0);
        locateBtn.disabled = false;
        locateBtn.innerHTML = '<i class="fas fa-location-crosshairs me-1"></i> Detect Location';
        alert('Location access was denied. Please allow location access to use the qibla finder.');
      }, { enableHighAccuracy: true, timeout: 10000 });
    };

    window.addEventListener('deviceorientation', (event) => {
      if (typeof event.webkitCompassHeading !== 'undefined') {
        updateQibla(event.webkitCompassHeading);
      }
    }, true);

    locateBtn.addEventListener('click', useLocation);
    useLocation();
  });
</script>
@endsection
