@extends('layouts.public')

@section('title', ($article->meta_title ?: $article->title) . ' | Islamic Articles | Masjid Locator')

@section('content')

{{-- Breadcrumb --}}
<div class="bayan-breadcrumb">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($article->title, 50) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">

        {{-- ═══ LEFT: ARTICLE CONTENT ═══ --}}
        <div class="col-lg-8">

            {{-- Cover Image --}}
            <div class="article-detail-cover mb-4">
                <img src="{{ $article->image_url }}"
                     alt="{{ $article->title }}"
                     class="img-fluid w-100 rounded-3"
                     style="max-height:420px;object-fit:cover;"
                     onerror="this.onerror=null;this.src='{{ asset('assets/img/article-placeholder.jpg') }}'">
            </div>

            {{-- Badges & Category --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @if($article->category)
                    <span class="article-cat-tag">{{ $article->category }}</span>
                @endif
                @if($article->is_featured)
                    <span class="bayan-badge featured"><i class="fas fa-star me-1"></i>Featured</span>
                @endif
                @if($article->is_trending)
                    <span class="bayan-badge trending"><i class="fas fa-fire me-1"></i>Trending</span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="article-detail-title mb-3">{{ $article->title }}</h1>

            {{-- Meta --}}
            <div class="article-detail-meta mb-4">
                @if($article->author)
                    <span class="article-meta-item">
                        <i class="fas fa-pen-nib"></i> By <strong>{{ $article->author }}</strong>
                    </span>
                @endif
                @if($article->published_at)
                    <span class="article-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $article->published_at->format('d M Y') }}
                    </span>
                @endif
                <span class="article-meta-item">
                    <i class="fas fa-clock"></i> {{ $article->reading_time }} min read
                </span>
                <span class="article-meta-item">
                    <i class="fas fa-eye"></i> {{ number_format($article->views) }} views
                </span>
            </div>

            <hr class="article-divider mb-4">

            {{-- Rich Text Content --}}
            <div class="article-rich-content" id="articleContent">
                {!! $article->content !!}
            </div>

            @if($article->tag_list)
                <div class="d-flex flex-wrap gap-2 mt-4">
                    @foreach($article->tag_list as $tag)
                        <a href="{{ route('articles.index', ['q' => $tag]) }}" class="badge bg-light text-success border text-decoration-none">
                            <i class="fas fa-tag me-1"></i>{{ $tag }}
                        </a>
                    @endforeach
                </div>
            @endif

            <hr class="article-divider mt-5 mb-4">

            {{-- Action Bar --}}
            <div class="bayan-action-bar mb-5">
                <button onclick="shareArticle()" class="btn btn-share">
                    <i class="fas fa-share-alt me-1"></i> Share
                </button>
                <button onclick="printArticle()" class="btn btn-bookmark">
                    <i class="fas fa-print me-1"></i> Print
                </button>
                <button onclick="bookmarkArticle()" class="btn btn-outline-secondary" id="bookmarkArticleBtn">
                    <i class="fas fa-bookmark me-1"></i> Bookmark
                </button>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Articles
                </a>
            </div>
        </div>

        {{-- ═══ RIGHT: RELATED ARTICLES ═══ --}}
        <div class="col-lg-4">
            <div class="bayan-related-panel">
                <h5 class="bayan-related-title">
                    <i class="fas fa-newspaper me-2"></i> Related Articles
                </h5>

                @forelse($related as $rel)
                    <a href="{{ route('articles.show', $rel) }}" class="bayan-related-item">
                        <img src="{{ $rel->image_url }}"
                             alt="{{ $rel->title }}"
                             class="bayan-related-thumb"
                             width="120" height="90"
                             decoding="async"
                             loading="lazy"
                             onerror="this.src='{{ asset('assets/img/article-placeholder.jpg') }}'"
                             style="border-radius:6px;object-fit:cover;">
                        <div class="bayan-related-info">
                            <p class="bayan-related-name">{{ Str::limit($rel->title, 55) }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $rel->reading_time }} min
                                · <i class="fas fa-eye"></i> {{ number_format($rel->views) }}
                            </small>
                        </div>
                    </a>
                @empty
                    <p class="text-muted small p-3">No related articles.</p>
                @endforelse

                @if($article->category)
                    <div class="mt-3">
                        <a href="{{ route('articles.index', ['category' => $article->category]) }}"
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-th me-1"></i> More {{ $article->category }} Articles
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: '{{ addslashes($article->title) }}',
            url: window.location.href
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => alert('Link copied!'));
    }
}

function printArticle() {
    window.print();
}

function bookmarkArticle() {
    const btn = document.getElementById('bookmarkArticleBtn');
    const key = 'bookmarked_article_{{ $article->id }}';
    if (localStorage.getItem(key)) {
        localStorage.removeItem(key);
        btn.innerHTML = '<i class="fas fa-bookmark me-1"></i> Bookmark';
        btn.classList.remove('bookmarked');
    } else {
        localStorage.setItem(key, '1');
        btn.innerHTML = '<i class="fas fa-bookmark me-1"></i> Bookmarked!';
        btn.classList.add('bookmarked');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('bookmarkArticleBtn');
    if (btn && localStorage.getItem('bookmarked_article_{{ $article->id }}')) {
        btn.innerHTML = '<i class="fas fa-bookmark me-1"></i> Bookmarked!';
        btn.classList.add('bookmarked');
    }
});
</script>
@endsection
