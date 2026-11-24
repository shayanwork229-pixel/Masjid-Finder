@extends('layouts.public')

@section('title', $image->title . ' | Islamic Gallery | Masjid Locator')

@section('content')

{{-- Breadcrumb --}}
<div class="bayan-breadcrumb">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('gallery.index') }}">Gallery</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($image->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">

        {{-- ═══ LEFT: MAIN IMAGE ═══ --}}
        <div class="col-lg-8">
            <div class="gallery-detail-img-wrap mb-4">
                <img src="{{ $image->image_url }}"
                     alt="{{ $image->title }}"
                     class="gallery-detail-img img-fluid w-100 rounded-3"
                     id="mainDetailImg"
                     onerror="this.onerror=null;this.src='{{ asset('assets/img/gallery-placeholder.jpg') }}'">
                <div class="gallery-detail-controls">
                    <button onclick="openFullscreen()" class="btn btn-sm btn-dark" title="Fullscreen">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button onclick="shareImage()" class="btn btn-sm btn-dark" title="Share">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
            </div>

            {{-- Image Details --}}
            <div class="gallery-detail-info mb-4">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @if($image->category)
                        <span class="bayan-cat-tag">{{ $image->category }}</span>
                    @endif
                    @if($image->is_featured)
                        <span class="bayan-badge featured"><i class="fas fa-star me-1"></i>Featured</span>
                    @endif
                    @if($image->is_trending)
                        <span class="bayan-badge trending"><i class="fas fa-fire me-1"></i>Trending</span>
                    @endif
                </div>
                <h1 class="bayan-detail-title">{{ $image->title }}</h1>

                <div class="bayan-detail-meta mt-3">
                    @if($image->masjid)
                        <div class="bayan-detail-meta-item">
                            <i class="fas fa-mosque"></i>
                            <span><strong>Masjid:</strong>
                                <a href="{{ route('masjids.show', $image->masjid) }}">{{ $image->masjid->name }}</a>
                            </span>
                        </div>
                    @endif
                    @if($image->city)
                        <div class="bayan-detail-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><strong>City:</strong> {{ $image->city }}</span>
                        </div>
                    @endif
                    <div class="bayan-detail-meta-item">
                        <i class="fas fa-eye"></i>
                        <span><strong>Views:</strong> {{ number_format($image->views) }}</span>
                    </div>
                    <div class="bayan-detail-meta-item">
                        <i class="fas fa-heart text-danger"></i>
                        <span><strong>Likes:</strong> <span id="likeCount">{{ number_format($image->likes) }}</span></span>
                    </div>
                    <div class="bayan-detail-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span><strong>Uploaded:</strong> {{ $image->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                @if($image->description)
                    <p class="mt-3 text-muted">{{ $image->description }}</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="bayan-action-bar mb-5">
                <button onclick="likeThisImage()" class="btn btn-share" id="likeBtn">
                    <i class="fas fa-heart me-1"></i> Like
                </button>
                <button onclick="shareImage()" class="btn btn-bookmark">
                    <i class="fas fa-share-alt me-1"></i> Share
                </button>
                <a href="{{ route('gallery.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Gallery
                </a>
            </div>
        </div>

        {{-- ═══ RIGHT: RELATED ═══ --}}
        <div class="col-lg-4">
            <div class="bayan-related-panel">
                <h5 class="bayan-related-title">
                    <i class="fas fa-images me-2"></i> Related Images
                </h5>

                @forelse($related as $rel)
                    <a href="{{ route('gallery.show', $rel) }}" class="bayan-related-item">
                        <img src="{{ $rel->image_url }}"
                             alt="{{ $rel->title }}"
                             class="bayan-related-thumb"
                             style="border-radius:8px;object-fit:cover;">
                        <div class="bayan-related-info">
                            <p class="bayan-related-name">{{ Str::limit($rel->title, 45) }}</p>
                            <small class="text-muted">
                                @if($rel->city) {{ $rel->city }} · @endif
                                <i class="fas fa-heart"></i> {{ number_format($rel->likes) }}
                            </small>
                        </div>
                    </a>
                @empty
                    <p class="text-muted small p-3">No related images.</p>
                @endforelse

                <div class="mt-3">
                    <a href="{{ route('gallery.index', ['category' => $image->category]) }}"
                       class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-th me-1"></i> More {{ $image->category }} Images
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openFullscreen() {
    const img = document.getElementById('mainDetailImg');
    if (img.requestFullscreen) img.requestFullscreen();
    else if (img.webkitRequestFullscreen) img.webkitRequestFullscreen();
}

function shareImage() {
    if (navigator.share) {
        navigator.share({ title: '{{ addslashes($image->title) }}', url: window.location.href }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => alert('Link copied!'));
    }
}

function likeThisImage() {
    const btn = document.getElementById('likeBtn');
    const key = 'liked_gallery_{{ $image->id }}';
    if (localStorage.getItem(key)) { alert('You already liked this!'); return; }

    fetch('{{ route("gallery.like", $image) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('likeCount').textContent = data.likes.toLocaleString();
        localStorage.setItem(key, '1');
        btn.innerHTML = '<i class="fas fa-heart me-1"></i> Liked!';
        btn.disabled = true;
    });
}
</script>
@endsection
