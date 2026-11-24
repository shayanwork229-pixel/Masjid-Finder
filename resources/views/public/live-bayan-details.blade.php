@extends('layouts.public')

@section('title', $bayan->title . ' | Bayan Library | Masjid Locator')

@section('content')

{{-- Breadcrumb --}}
<div class="bayan-breadcrumb">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('live-bayan.index') }}">Bayan Library</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($bayan->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">

        {{-- ═══ LEFT: VIDEO + DETAILS ═══ --}}
        <div class="col-lg-8">

            {{-- Video Player --}}
            @if($bayan->youtube_embed_url)
                <div class="bayan-video-wrap mb-4">
                    <iframe
                        src="{{ $bayan->youtube_embed_url }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        title="{{ $bayan->title }}"
                        loading="lazy">
                    </iframe>
                </div>
            @else
                <div class="bayan-thumb-full mb-4">
                    <img src="{{ $bayan->thumbnail_url }}"
                         alt="{{ $bayan->title }}"
                         class="img-fluid rounded-3 w-100"
                         style="max-height:420px;object-fit:cover;"
                         onerror="this.src='{{ asset('assets/img/bayan-placeholder.jpg') }}'">
                </div>
            @endif

            {{-- Audio Player --}}
            @if($bayan->audio_url)
                <div class="bayan-audio-player mb-4">
                    <div class="bayan-audio-label">
                        <i class="fas fa-headphones me-2"></i> Audio Bayan
                    </div>
                    <audio controls class="w-100" preload="metadata">
                        <source src="{{ $bayan->audio_url }}">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            @endif

            {{-- Title & Badges --}}
            <div class="bayan-detail-header mb-3">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @if($bayan->category)
                        <span class="bayan-cat-tag">{{ $bayan->category }}</span>
                    @endif
                    @if($bayan->is_live)
                        <span class="bayan-badge live"><i class="fas fa-circle blink me-1"></i>LIVE</span>
                    @endif
                    @if($bayan->is_featured)
                        <span class="bayan-badge featured"><i class="fas fa-star me-1"></i>Featured</span>
                    @endif
                    @if($bayan->is_trending)
                        <span class="bayan-badge trending"><i class="fas fa-fire me-1"></i>Trending</span>
                    @endif
                </div>
                <h1 class="bayan-detail-title">{{ $bayan->title }}</h1>
            </div>

            {{-- Meta row --}}
            <div class="bayan-detail-meta mb-4">
                @if($bayan->scholar_name)
                    <div class="bayan-detail-meta-item">
                        <i class="fas fa-user-graduate"></i>
                        <span><strong>Scholar:</strong> {{ $bayan->scholar_name }}</span>
                    </div>
                @endif
                @if($bayan->masjid)
                    <div class="bayan-detail-meta-item">
                        <i class="fas fa-mosque"></i>
                        <span><strong>Masjid:</strong>
                            <a href="{{ route('masjids.show', $bayan->masjid) }}">{{ $bayan->masjid->name }}</a>
                        </span>
                    </div>
                @endif
                @if($bayan->duration)
                    <div class="bayan-detail-meta-item">
                        <i class="fas fa-clock"></i>
                        <span><strong>Duration:</strong> {{ $bayan->duration }}</span>
                    </div>
                @endif
                @if($bayan->date)
                    <div class="bayan-detail-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span><strong>Date:</strong> {{ $bayan->date->format('d M Y') }}</span>
                    </div>
                @endif
                <div class="bayan-detail-meta-item">
                    <i class="fas fa-eye"></i>
                    <span><strong>Views:</strong> {{ number_format($bayan->views) }}</span>
                </div>
            </div>

            {{-- Description --}}
            @if($bayan->description)
                <div class="bayan-detail-desc mb-4">
                    <h5 class="mb-2"><i class="fas fa-align-left me-2 text-primary"></i>Description</h5>
                    <p>{{ $bayan->description }}</p>
                </div>
            @endif

            {{-- Share & Bookmark --}}
            <div class="bayan-action-bar mb-5">
                <button onclick="shareBayan()" class="btn btn-share">
                    <i class="fas fa-share-alt me-1"></i> Share
                </button>
                <button onclick="bookmarkBayan()" class="btn btn-bookmark" id="bookmarkBtn">
                    <i class="fas fa-bookmark me-1"></i> Bookmark
                </button>
                <a href="{{ route('live-bayan.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Library
                </a>
            </div>

        </div>

        {{-- ═══ RIGHT: RELATED BAYANS ═══ --}}
        <div class="col-lg-4">
            <div class="bayan-related-panel">
                <h5 class="bayan-related-title">
                    <i class="fas fa-list me-2"></i> Related Bayans
                </h5>

                @forelse($related as $rel)
                    <a href="{{ route('live-bayan.show', $rel) }}" class="bayan-related-item">
                        <img src="{{ $rel->thumbnail_url }}"
                             alt="{{ $rel->title }}"
                             class="bayan-related-thumb"
                             onerror="this.src='{{ asset('assets/img/bayan-placeholder.jpg') }}'">
                        <div class="bayan-related-info">
                            <p class="bayan-related-name">{{ Str::limit($rel->title, 50) }}</p>
                            <small class="text-muted">
                                @if($rel->scholar_name) {{ $rel->scholar_name }} · @endif
                                <i class="fas fa-eye"></i> {{ number_format($rel->views) }}
                            </small>
                        </div>
                    </a>
                @empty
                    <p class="text-muted small p-3">No related bayans found.</p>
                @endforelse

                <div class="mt-3">
                    <a href="{{ route('live-bayan.index', ['category' => $bayan->category]) }}"
                       class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-th me-1"></i> More {{ $bayan->category }} Bayans
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function shareBayan() {
    if (navigator.share) {
        navigator.share({
            title: '{{ addslashes($bayan->title) }}',
            text: '{{ addslashes(Str::limit($bayan->description ?? "", 100)) }}',
            url: window.location.href
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}

function bookmarkBayan() {
    const btn = document.getElementById('bookmarkBtn');
    const bookmarks = JSON.parse(localStorage.getItem('bayanBookmarks') || '[]');
    const id = {{ $bayan->id }};
    const idx = bookmarks.indexOf(id);
    if (idx === -1) {
        bookmarks.push(id);
        btn.innerHTML = '<i class="fas fa-bookmark me-1"></i> Bookmarked';
        btn.classList.add('bookmarked');
    } else {
        bookmarks.splice(idx, 1);
        btn.innerHTML = '<i class="fas fa-bookmark me-1"></i> Bookmark';
        btn.classList.remove('bookmarked');
    }
    localStorage.setItem('bayanBookmarks', JSON.stringify(bookmarks));
}

// Check if already bookmarked on load
document.addEventListener('DOMContentLoaded', () => {
    const bookmarks = JSON.parse(localStorage.getItem('bayanBookmarks') || '[]');
    if (bookmarks.includes({{ $bayan->id }})) {
        const btn = document.getElementById('bookmarkBtn');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-bookmark me-1"></i> Bookmarked';
            btn.classList.add('bookmarked');
        }
    }
});
</script>
@endsection
