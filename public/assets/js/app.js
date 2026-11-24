/**
 * Masjid Locator — Core JavaScript
 * GPS, Map, Countdown
 */

// ═══════════════════════════════════════════════════════════════
// GPS LOCATION MODULE
// ═══════════════════════════════════════════════════════════════
const GPS = {
    lat: null,
    lng: null,
    ready: false,

    /**
     * Get user's current position
     * @returns {Promise<{lat: number, lng: number}>}
     */
    getLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation not supported'));
                return;
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.lat = pos.coords.latitude;
                    this.lng = pos.coords.longitude;
                    this.ready = true;
                    window.currentUserLat = this.lat;
                    window.currentUserLng = this.lng;
                    resolve({ lat: this.lat, lng: this.lng });
                },
                (err) => {
                    console.warn('GPS Error:', err.message);
                    reject(err);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 300000 }
            );
        });
    },

    buildDirectionsUrl(destinationLat, destinationLng, originLat = this.lat, originLng = this.lng) {
        const userLat = parseFloat(originLat);
        const userLng = parseFloat(originLng);
        const masjidLat = parseFloat(destinationLat);
        const masjidLng = parseFloat(destinationLng);

        if (!Number.isFinite(userLat) || !Number.isFinite(userLng) || !Number.isFinite(masjidLat) || !Number.isFinite(masjidLng)) {
            return `https://www.google.com/maps/search/?api=1&query=${masjidLat},${masjidLng}`;
        }

        return `https://www.google.com/maps/dir/${userLat.toFixed(8)},${userLng.toFixed(8)}/${masjidLat.toFixed(8)},${masjidLng.toFixed(8)}`;
    },

    refreshDirectionsLinks() {
        document.querySelectorAll('[data-directions-link="true"]').forEach(link => {
            const lat = link.dataset.destLat;
            const lng = link.dataset.destLng;
            link.href = this.buildDirectionsUrl(lat, lng);
        });
    }
};

// ═══════════════════════════════════════════════════════════════
// HAVERSINE DISTANCE (CLIENT-SIDE)
// ═══════════════════════════════════════════════════════════════
function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) ** 2 +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return (R * c).toFixed(2);
}

// ═══════════════════════════════════════════════════════════════
// PRAYER COUNTDOWN TIMER
// ═══════════════════════════════════════════════════════════════
const PrayerCountdown = {
    timerInterval: null,
    activeMasjid: null,
    prayerOrder: ['fajr', 'zuhr', 'asr', 'maghrib', 'isha'],
    prayerLabels: {
        fajr: 'Fajr',
        zuhr: 'Zuhr',
        asr: 'Asr',
        maghrib: 'Maghrib',
        isha: 'Isha'
    },

    /**
     * Start countdown for the selected masjid.
     */
    start(masjid, displayElementId) {
        const el = document.getElementById(displayElementId);
        if (!el || !masjid) return;

        if (this.timerInterval) clearInterval(this.timerInterval);
        this.activeMasjid = masjid;

        const tick = () => {
            const next = this.getNextPrayer(this.activeMasjid, new Date());
            if (!next) {
                const hoursEl = el.querySelector('.countdown-hours');
                const minsEl = el.querySelector('.countdown-minutes');
                const secsEl = el.querySelector('.countdown-seconds');
                if (hoursEl) hoursEl.textContent = '--';
                if (minsEl) minsEl.textContent = '--';
                if (secsEl) secsEl.textContent = '--';
                return;
            }
            const diff = Math.max(0, next.targetDate.getTime() - Date.now());
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            const hoursEl = el.querySelector('.countdown-hours');
            const minsEl = el.querySelector('.countdown-minutes');
            const secsEl = el.querySelector('.countdown-seconds');
            const nameEl = document.getElementById('countdownPrayerName') || el.querySelector('.countdown-prayer-name');

            if (hoursEl) hoursEl.textContent = String(h).padStart(2, '0');
            if (minsEl) minsEl.textContent = String(m).padStart(2, '0');
            if (secsEl) secsEl.textContent = String(s).padStart(2, '0');
            if (nameEl) nameEl.textContent = next.name;
        };

        tick();
        this.timerInterval = setInterval(tick, 1000);
    },

    getNextPrayer(masjid, now = new Date()) {
        if (!masjid) return null;

        for (const key of this.prayerOrder) {
            const time = masjid[key];
            const targetDate = this.timeToDate(time, now);
            if (targetDate && targetDate > now) {
                return {
                    key,
                    name: this.prayerLabels[key],
                    time,
                    targetDate,
                    current: this.getCurrentPrayer(masjid, now)
                };
            }
        }

        const fajrTomorrow = this.timeToDate(masjid.fajr, now);
        if (!fajrTomorrow) return null;
        fajrTomorrow.setDate(fajrTomorrow.getDate() + 1);
        return {
            key: 'fajr',
            name: 'Fajr',
            time: masjid.fajr,
            targetDate: fajrTomorrow,
            current: this.getCurrentPrayer(masjid, now)
        };
    },

    getCurrentPrayer(masjid, now = new Date()) {
        let current = null;
        for (const key of this.prayerOrder) {
            const targetDate = this.timeToDate(masjid[key], now);
            if (targetDate && targetDate <= now) {
                current = { key, name: this.prayerLabels[key], time: masjid[key] };
            }
        }
        return current;
    },

    timeToDate(time, baseDate = new Date()) {
        if (!time || typeof time !== 'string') return null;
        const parts = time.split(':').map(Number);
        if (parts.length < 2 || !Number.isFinite(parts[0]) || !Number.isFinite(parts[1])) return null;
        const target = new Date(baseDate);
        target.setHours(parts[0], parts[1], 0, 0);
        return target;
    },

    stop() {
        if (this.timerInterval) clearInterval(this.timerInterval);
    }
};

// ═══════════════════════════════════════════════════════════════
// MAP HELPERS
// ═══════════════════════════════════════════════════════════════
const MapHelper = {
    map: null,
    markers: null,
    userMarker: null,
    baseUrl: (document.querySelector('meta[name="app-base-url"]')?.content || '').replace(/\/$/, ''),

    /**
     * Custom mosque icon
     */
    getMosqueIcon() {
        return L.divIcon({
            className: 'custom-mosque-marker',
            html: '<div class="mosque-marker-inner"><i class="fas fa-mosque"></i></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });
    },

    /**
     * User location icon
     */
    getUserIcon() {
        return L.divIcon({
            className: 'custom-user-marker',
            html: '<div class="user-marker-inner"><div class="user-marker-pulse"></div></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    },

    /**
     * Initialize a Leaflet map
     */
    init(containerId, lat = 31.5204, lng = 74.3587, zoom = 12) {
        this.map = L.map(containerId, {
            zoomControl: true,
            scrollWheelZoom: true
        }).setView([lat, lng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(this.map);

        this.markers = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false
        });
        this.map.addLayer(this.markers);

        return this.map;
    },

    /**
     * Add masjid markers from JSON data
     */
    addMasjidMarkers(masjids, userLat = null, userLng = null) {
        // Remove old markers
        this.markers.clearLayers();

        // Sort by nearest distance
        if (userLat !== null && userLng !== null) {
            masjids = [...masjids].sort((a, b) => {
                const d1 = parseFloat(haversineDistance(userLat, userLng, parseFloat(a.latitude), parseFloat(a.longitude)));
                const d2 = parseFloat(haversineDistance(userLat, userLng, parseFloat(b.latitude), parseFloat(b.longitude)));
                return d1 - d2;
            });
        }

        masjids.forEach(m => {
            let distanceHtml = "";

            if (userLat !== null && userLng !== null) {
                const distance = haversineDistance(userLat, userLng, parseFloat(m.latitude), parseFloat(m.longitude));
                distanceHtml = `<div class="mt-1 text-primary fw-bold">📍 ${distance} km away</div>`;
            }

            const popup = `
                <div class="map-popup">
                    <h6 class="fw-bold mb-2">${m.name}</h6>
                    <p class="mb-1"><strong>Sect:</strong> ${m.sect}</p>
                    <p class="mb-1">${m.address}</p>
                    ${distanceHtml}
                    <hr>
                    <a href="${this.baseUrl}/masjids/${m.id}" class="btn btn-success btn-sm w-100 mb-2">View Details</a>
                    <a href="${GPS.buildDirectionsUrl(m.latitude, m.longitude, userLat, userLng)}"
                       target="_blank"
                       class="btn btn-primary btn-sm w-100"
                       data-directions-link="true"
                       data-dest-lat="${m.latitude}"
                       data-dest-lng="${m.longitude}">
                        Get Directions
                    </a>
                </div>
            `;

            const marker = L.marker(
                [parseFloat(m.latitude), parseFloat(m.longitude)],
                { icon: this.getMosqueIcon() }
            ).bindPopup(popup);

            this.markers.addLayer(marker);
        });
    },

    /**
     * Add user location marker
     */
    addUserMarker(lat, lng) {
        if (this.userMarker) this.map.removeLayer(this.userMarker);
        this.userMarker = L.marker([lat, lng], { icon: this.getUserIcon() })
            .bindPopup('<strong>📍 Your Location</strong>')
            .addTo(this.map);
    }
};

// ═══════════════════════════════════════════════════════════════
// SECT FILTER (for card grids)
// ═══════════════════════════════════════════════════════════════
function filterBySect(sect) {
    const cards = document.querySelectorAll('[data-sect]');
    cards.forEach(card => {
        if (sect === 'all' || card.dataset.sect === sect) {
            card.style.display = '';
            card.style.animation = 'fadeInUp 0.4s ease';
        } else {
            card.style.display = 'none';
        }
    });

    // Update active filter button
    document.querySelectorAll('.sect-filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === sect) btn.classList.add('active');
    });
}

// ═══════════════════════════════════════════════════════════════
// UTILITIES
// ═══════════════════════════════════════════════════════════════
function formatTime12h(time) {
    if (!time) return 'N/A';
    const [h, m] = time.split(':');
    const hour = parseInt(h);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const h12 = hour % 12 || 12;
    return `${h12}:${m} ${ampm}`;
}

// Navbar scroll effect
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        navbar.classList.toggle('navbar-scrolled', window.scrollY > 50);
    }
});

// Smooth scroll and click bindings
document.addEventListener('DOMContentLoaded', () => {
    GPS.refreshDirectionsLinks();

    document.addEventListener('click', (event) => {
        const link = event.target.closest('[data-directions-link="true"]');
        if (!link) return;

        const openDirections = (lat, lng) => {
            const url = GPS.buildDirectionsUrl(link.dataset.destLat, link.dataset.destLng, lat, lng);
            link.href = url;
            window.open(url, '_blank', 'noopener');
        };

        if (GPS.ready) {
            event.preventDefault();
            openDirections(GPS.lat, GPS.lng);
            return;
        }

        event.preventDefault();
        GPS.getLocation()
            .then(pos => {
                GPS.refreshDirectionsLinks();
                openDirections(pos.lat, pos.lng);
            })
            .catch(() => {
                alert('Please allow location access so Google Maps can start directions from your current GPS position.');
            });
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});