@extends('layouts.public')

@section('title', 'Islamic Gallery | Masjid Locator')

@section('content')

{{-- ══════════════════════ HERO ══════════════════════ --}}
<section class="gallery-hero">
    <div class="gallery-hero-bg"></div>
    <div class="container position-relative">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="gallery-hero-icon mb-3">
                    <i class="fas fa-images"></i>
                </div>
                <h1 class="gallery-hero-title">Islamic Gallery</h1>
                <p class="gallery-hero-sub">Beautiful captures of masajid, Islamic architecture, events and celebrations</p>

                <form action="{{ route('gallery.index') }}" method="GET" class="bayan-search-form mt-4">
                    <div class="bayan-search-wrap">
                        <i class="fas fa-search bayan-search-icon"></i>
                        <input type="text" name="q" class="bayan-search-input"
                               placeholder="Search images by title, city or description…"
                               value="{{ request('q') }}">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        <button type="submit" class="bayan-search-btn">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════ FILTERS ══════════════════════ --}}
<div class="bayan-filters-bar sticky-top">
    <div class="container">
        <div class="d-flex align-items-center gap-2 flex-wrap py-2">
            <div class="bayan-tabs me-3">
                <a href="{{ route('gallery.index', array_merge(request()->except('tab','page'), ['tab'=>''])) }}"
                   class="bayan-tab {{ !request('tab') ? 'active' : '' }}">
                    <i class="fas fa-th me-1"></i> All
                </a>
                <a href="{{ route('gallery.index', array_merge(request()->except('tab','page'), ['tab'=>'featured'])) }}"
                   class="bayan-tab {{ request('tab')==='featured' ? 'active' : '' }}">
                    <i class="fas fa-star me-1"></i> Featured
                </a>
                <a href="{{ route('gallery.index', array_merge(request()->except('tab','page'), ['tab'=>'trending'])) }}"
                   class="bayan-tab {{ request('tab')==='trending' ? 'active' : '' }}">
                    <i class="fas fa-fire me-1"></i> Trending
                </a>
            </div>
            <div class="bayan-category-pills">
                <a href="{{ route('gallery.index', array_merge(request()->except('category','page'))) }}"
                   class="bayan-pill {{ !request('category') ? 'active' : '' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('gallery.index', array_merge(request()->except('category','page'), ['category'=>$cat])) }}"
                       class="bayan-pill {{ request('category')===$cat ? 'active' : '' }}">{{ $cat }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════ GALLERY GRID ══════════════════════ --}}
<div class="container py-5">

    @if(request('q') || request('category'))
        <div class="bayan-filter-info mb-4">
            <i class="fas fa-filter me-2"></i>
            @if(request('q')) Results for "<strong>{{ request('q') }}</strong>" @endif
            @if(request('category')) in <strong>{{ request('category') }}</strong>@endif
            &nbsp;·&nbsp; <strong>{{ $images->total() }}</strong> image(s) found
            <a href="{{ route('gallery.index') }}" class="ms-2 text-muted"><i class="fas fa-times-circle"></i> Clear</a>
        </div>
    @endif

    @if($images->count())

        {{-- Gallery Masonry Grid --}}
        <div class="gallery-grid" id="galleryGrid">
            @foreach($images as $image)
                <div class="gallery-item">
                    {{-- Lightbox trigger --}}
                    <a href="{{ $image->image_url }}"
                       data-lightbox="gallery"
                       data-title="{{ $image->title }}"
                       data-description="{{ $image->description }}"
                       class="gallery-img-link"
                       data-id="{{ $image->id }}">
                        <img src="{{ $image->image_url }}"
                             alt="{{ $image->title }}"
                             class="gallery-img"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('assets/img/gallery-placeholder.jpg') }}';this.closest('a').href=this.src">
                        <div class="gallery-img-overlay">
                            <i class="fas fa-expand-alt gallery-expand-icon"></i>
                        </div>
                        {{-- Badges --}}
                        @if($image->is_featured)
                            <span class="gallery-badge featured"><i class="fas fa-star me-1"></i>Featured</span>
                        @elseif($image->is_trending)
                            <span class="gallery-badge trending"><i class="fas fa-fire me-1"></i>Trending</span>
                        @endif
                    </a>
                    <div class="gallery-item-info">
                        <div class="gallery-item-title">
                            <a href="{{ route('gallery.show', $image) }}">{{ $image->title }}</a>
                        </div>
                        <div class="gallery-item-meta">
                            @if($image->masjid)
                                <span><i class="fas fa-mosque me-1"></i>{{ $image->masjid->name }}</span>
                            @elseif($image->city)
                                <span><i class="fas fa-map-marker-alt me-1"></i>{{ $image->city }}</span>
                            @endif
                            <span><i class="fas fa-eye me-1"></i>{{ number_format($image->views) }}</span>
                            <span class="gallery-like-btn" onclick="likeImage({{ $image->id }}, this)" title="Like">
                                <i class="fas fa-heart me-1"></i><span id="likes-{{ $image->id }}">{{ number_format($image->likes) }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $images->links() }}
        </div>

    @else
        <div class="bayan-empty-state">
            <div class="bayan-empty-icon">
                <i class="fas fa-image"></i>
            </div>
            <h3>No Images Found</h3>
            @if(request('q') || request('category'))
                <p>No images match your filters. Try a different category or search term.</p>
                <a href="{{ route('gallery.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-redo me-1"></i> View All Images
                </a>
            @else
                <p>The gallery is being curated. Beautiful Islamic photography coming soon.</p>
            @endif
        </div>
    @endif

</div>

{{-- ══════════════════════ LIGHTBOX MODAL ══════════════════════ --}}
<div class="lightbox-overlay" id="lightboxOverlay" onclick="closeLightbox()">
    <div class="lightbox-container" onclick="event.stopPropagation()">
        <button class="lightbox-close" onclick="closeLightbox()" title="Close">
            <i class="fas fa-times"></i>
        </button>
        <button class="lightbox-prev" onclick="lightboxNav(-1)" title="Previous">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-next" onclick="lightboxNav(1)" title="Next">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="lightbox-img-wrap">
            <img src="" alt="" id="lightboxImg" class="lightbox-img">
        </div>
        <div class="lightbox-caption">
            <div id="lightboxTitle" class="lightbox-title"></div>
            <div id="lightboxDesc" class="lightbox-desc"></div>
            <div class="lightbox-controls mt-2">
                <button onclick="lightboxFullscreen()" class="btn btn-sm btn-outline-light" title="Fullscreen">
                    <i class="fas fa-expand"></i> Fullscreen
                </button>
                <button onclick="lightboxShare()" class="btn btn-sm btn-outline-light" title="Share">
                    <i class="fas fa-share-alt"></i> Share
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ── Lightbox ──────────────────────────────────────────────────────
const galleryLinks = Array.from(document.querySelectorAll('.gallery-img-link'));
let currentIdx = 0;

galleryLinks.forEach((link, idx) => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        currentIdx = idx;
        openLightbox(link);
        trackView(link.dataset.id);
    });
});

function openLightbox(link) {
    const overlay = document.getElementById('lightboxOverlay');
    document.getElementById('lightboxImg').src = link.href;
    document.getElementById('lightboxImg').alt = link.dataset.title || '';
    document.getElementById('lightboxTitle').textContent = link.dataset.title || '';
    document.getElementById('lightboxDesc').textContent = link.dataset.description || '';
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightboxOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

function lightboxNav(dir) {
    currentIdx = (currentIdx + dir + galleryLinks.length) % galleryLinks.length;
    openLightbox(galleryLinks[currentIdx]);
}

function lightboxFullscreen() {
    const img = document.getElementById('lightboxImg');
    if (img.requestFullscreen) img.requestFullscreen();
    else if (img.webkitRequestFullscreen) img.webkitRequestFullscreen();
}

function lightboxShare() {
    const img = document.getElementById('lightboxImg');
    if (navigator.share) {
        navigator.share({ title: document.getElementById('lightboxTitle').textContent, url: img.src }).catch(() => {});
    } else {
        navigator.clipboard.writeText(img.src).then(() => alert('Image URL copied!'));
    }
}

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    const overlay = document.getElementById('lightboxOverlay');
    if (!overlay.classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') lightboxNav(-1);
    if (e.key === 'ArrowRight') lightboxNav(1);
});

// Like AJAX
function likeImage(id, el) {
    fetch('{{ url("/gallery") }}/' + id + '/like', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const span = document.getElementById('likes-' + id);
        if (span) span.textContent = data.likes.toLocaleString();
        el.classList.add('liked');
    })
    .catch(() => {});
}

// Track view (fire-and-forget)
function trackView(id) { /* views tracked on show() */ }
</script>
@endsection
