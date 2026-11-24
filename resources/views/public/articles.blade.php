@extends('layouts.public')

@section('title', 'Islamic Articles | Masjid Locator')

@section('content')

{{-- ══════════════════════ HERO ══════════════════════ --}}
<section class="articles-hero">
    <div class="articles-hero-bg"></div>
    <div class="container position-relative">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="bayan-hero-icon mb-3">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h1 class="bayan-hero-title">Islamic Articles</h1>
                <p class="bayan-hero-sub">Authentic Islamic knowledge — Fiqh, Seerah, Quran, Hadith, and guidance for everyday life</p>

                <form action="{{ route('articles.index') }}" method="GET" class="bayan-search-form mt-4">
                    <div class="bayan-search-wrap">
                        <i class="fas fa-search bayan-search-icon"></i>
                        <input type="text" name="q" class="bayan-search-input"
                               placeholder="Search articles by title, author or topic…"
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
                <a href="{{ route('articles.index', array_merge(request()->except('tab','page'), ['tab'=>''])) }}"
                   class="bayan-tab {{ !request('tab') ? 'active' : '' }}">
                    <i class="fas fa-th me-1"></i> All
                </a>
                <a href="{{ route('articles.index', array_merge(request()->except('tab','page'), ['tab'=>'featured'])) }}"
                   class="bayan-tab {{ request('tab')==='featured' ? 'active' : '' }}">
                    <i class="fas fa-star me-1"></i> Featured
                </a>
                <a href="{{ route('articles.index', array_merge(request()->except('tab','page'), ['tab'=>'trending'])) }}"
                   class="bayan-tab {{ request('tab')==='trending' ? 'active' : '' }}">
                    <i class="fas fa-fire me-1"></i> Trending
                </a>
            </div>
            <div class="bayan-category-pills">
                <a href="{{ route('articles.index', array_merge(request()->except('category','page'))) }}"
                   class="bayan-pill {{ !request('category') ? 'active' : '' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('articles.index', array_merge(request()->except('category','page'), ['category'=>$cat])) }}"
                       class="bayan-pill {{ request('category')===$cat ? 'active' : '' }}">{{ $cat }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════ MAIN ══════════════════════ --}}
<div class="container py-4">

    @if(request('q') || request('category'))
        <div class="bayan-filter-info mb-4">
            <i class="fas fa-filter me-2"></i>
            @if(request('q')) Results for "<strong>{{ request('q') }}</strong>" @endif
            @if(request('category')) in <strong>{{ request('category') }}</strong>@endif
            &nbsp;·&nbsp; <strong>{{ $articles->total() }}</strong> article(s) found
            <a href="{{ route('articles.index') }}" class="ms-2 text-muted"><i class="fas fa-times-circle"></i> Clear</a>
        </div>
    @endif

    @if($articles->count())

        <div class="row g-4">
                @foreach($articles as $article)
                <div class="col-md-6 col-lg-4">
                    <div class="article-card h-100">
                        {{-- Cover Image --}}
                        <a href="{{ route('articles.show', $article) }}" class="article-img-link">
                            <img src="{{ $article->image_url }}"
                                 alt="{{ $article->title }}"
                                 class="article-cover-img article-cover-img--loading"
                                 width="600" height="300"
                                 decoding="async"
                                 loading="lazy"
                                 onerror="this.src='{{ asset('assets/img/article-placeholder.jpg') }}';this.classList.remove('article-cover-img--loading')">
                            <div class="article-cover-overlay"></div>
                            {{-- Badges --}}
                            @if($article->is_featured)
                                <span class="bayan-badge featured"><i class="fas fa-star me-1"></i>Featured</span>
                            @elseif($article->is_trending)
                                <span class="bayan-badge trending"><i class="fas fa-fire me-1"></i>Trending</span>
                            @else
                                <span class="bayan-badge latest"><i class="fas fa-clock me-1"></i>Latest</span>
                            @endif
                        </a>

                        <div class="article-card-body">
                            @if($article->category)
                                <span class="article-cat-tag">{{ $article->category }}</span>
                            @endif

                            <h5 class="article-title mt-2">
                                <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                            </h5>

                            <p class="article-excerpt">{{ $article->excerpt }}</p>

                            <div class="article-meta mt-3">
                                @if($article->author)
                                    <span class="article-meta-item">
                                        <i class="fas fa-pen-nib"></i> {{ $article->author }}
                                    </span>
                                @endif
                                <span class="article-meta-item">
                                    <i class="fas fa-clock"></i> {{ $article->reading_time }} min read
                                </span>
                                <span class="article-meta-item">
                                    <i class="fas fa-eye"></i> {{ number_format($article->views) }}
                                </span>
                                @if($article->published_at)
                                    <span class="article-meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $article->published_at->format('d M Y') }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('articles.show', $article) }}" class="article-read-btn mt-3">
                                <i class="fas fa-book-open me-1"></i> Read More
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $articles->links() }}
        </div>

    @else
        <div class="bayan-empty-state py-5">
            <div class="bayan-empty-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h3 class="mb-3">No Articles Found</h3>
            @if(request('q') || request('category'))
                <p class="mb-3">No articles match your search. Try different keywords or a different category.</p>
                <a href="{{ route('articles.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-redo me-1"></i> View All Articles
                </a>
            @else
                <p>Our scholars and writers are preparing authentic Islamic content. Check back soon!</p>
            @endif
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var imgs = document.querySelectorAll('.article-cover-img--loading');
    imgs.forEach(function (img) {
        if (img.complete && img.naturalWidth > 0) {
            img.classList.remove('article-cover-img--loading');
        }
        img.addEventListener('load', function () {
            img.classList.remove('article-cover-img--loading');
        });
        img.addEventListener('error', function () {
            img.classList.remove('article-cover-img--loading');
        });
    });
});
</script>

@endsection

<noscript><style>.article-cover-img--loading { opacity:1 !important; }</style></noscript>
