@extends('layouts.public')

@section('title', 'Live Bayan Library | Masjid Locator')

@section('content')

{{-- ══════════════════════ HERO ══════════════════════ --}}
<section class="bayan-hero">
    <div class="bayan-hero-bg"></div>
    <div class="container position-relative">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="bayan-hero-icon mb-3">
                    <i class="fas fa-microphone-alt"></i>
                </div>
                <h1 class="bayan-hero-title">Live Bayan Library</h1>
                <p class="bayan-hero-sub">Listen to inspiring Islamic lectures, Jumu'ah khutbahs and authentic bayans from esteemed scholars</p>

                {{-- Search bar --}}
                <form action="{{ route('live-bayan.index') }}" method="GET" class="bayan-search-form mt-4">
                    <div class="bayan-search-wrap">
                        <i class="fas fa-search bayan-search-icon"></i>
                        <input type="text" name="q" class="bayan-search-input"
                               placeholder="Search by title, scholar or topic…"
                               value="{{ request('q') }}">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('tab')) <input type="hidden" name="tab" value="{{ request('tab') }}"> @endif
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
            {{-- Tabs --}}
            <div class="bayan-tabs me-3">
                <a href="{{ route('live-bayan.index', array_merge(request()->except('tab','page'), ['tab'=>''])) }}"
                   class="bayan-tab {{ !request('tab') ? 'active' : '' }}">
                    <i class="fas fa-th me-1"></i> All
                </a>
                <a href="{{ route('live-bayan.index', array_merge(request()->except('tab','page'), ['tab'=>'featured'])) }}"
                   class="bayan-tab {{ request('tab')==='featured' ? 'active' : '' }}">
                    <i class="fas fa-star me-1"></i> Featured
                </a>
                <a href="{{ route('live-bayan.index', array_merge(request()->except('tab','page'), ['tab'=>'trending'])) }}"
                   class="bayan-tab {{ request('tab')==='trending' ? 'active' : '' }}">
                    <i class="fas fa-fire me-1"></i> Trending
                </a>
            </div>

            {{-- Category pills --}}
            <div class="bayan-category-pills">
                <a href="{{ route('live-bayan.index', array_merge(request()->except('category','page'))) }}"
                   class="bayan-pill {{ !request('category') ? 'active' : '' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('live-bayan.index', array_merge(request()->except('category','page'), ['category'=>$cat])) }}"
                       class="bayan-pill {{ request('category')===$cat ? 'active' : '' }}">{{ $cat }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════ MAIN CONTENT ══════════════════════ --}}
<div class="container py-5">

    {{-- Active filters info --}}
    @if(request('q') || request('category') || request('scholar'))
        <div class="bayan-filter-info mb-4">
            <i class="fas fa-filter me-2"></i>
            Showing results
            @if(request('q')) for "<strong>{{ request('q') }}</strong>"@endif
            @if(request('category')) in <strong>{{ request('category') }}</strong>@endif
            &nbsp;·&nbsp; <strong>{{ $bayans->total() }}</strong> bayan(s) found
            <a href="{{ route('live-bayan.index') }}" class="ms-2 text-muted"><i class="fas fa-times-circle"></i> Clear</a>
        </div>
    @endif

    @if($bayans->count())

        <div class="row g-4" id="bayanGrid">
            @foreach($bayans as $bayan)
                <div class="col-md-6 col-lg-4">
                    <div class="bayan-card">
                        {{-- Thumbnail --}}
                        <a href="{{ route('live-bayan.show', $bayan) }}" class="bayan-thumb-wrap">
                            <img src="{{ $bayan->thumbnail_url }}"
                                 alt="{{ $bayan->title }}"
                                 class="bayan-thumb"
                                 onerror="this.src='{{ asset('assets/img/bayan-placeholder.jpg') }}'">
                            <div class="bayan-thumb-overlay">
                                <i class="fas fa-play-circle bayan-play-icon"></i>
                            </div>
                            @if($bayan->is_live)
                                <span class="bayan-badge live"><i class="fas fa-circle blink me-1"></i>LIVE</span>
                            @elseif($bayan->is_featured)
                                <span class="bayan-badge featured"><i class="fas fa-star me-1"></i>Featured</span>
                            @elseif($bayan->is_trending)
                                <span class="bayan-badge trending"><i class="fas fa-fire me-1"></i>Trending</span>
                            @else
                                <span class="bayan-badge latest"><i class="fas fa-clock me-1"></i>Latest</span>
                            @endif
                            @if($bayan->duration)
                                <span class="bayan-duration"><i class="fas fa-clock me-1"></i>{{ $bayan->duration }}</span>
                            @endif
                        </a>

                        {{-- Card Body --}}
                        <div class="bayan-card-body">
                            @if($bayan->category)
                                <span class="bayan-cat-tag">{{ $bayan->category }}</span>
                            @endif

                            <h5 class="bayan-title">
                                <a href="{{ route('live-bayan.show', $bayan) }}">{{ $bayan->title }}</a>
                            </h5>

                            <div class="bayan-meta">
                                @if($bayan->scholar_name)
                                    <span class="bayan-meta-item">
                                        <i class="fas fa-user-graduate"></i>
                                        {{ $bayan->scholar_name }}
                                    </span>
                                @endif
                                @if($bayan->masjid)
                                    <span class="bayan-meta-item">
                                        <i class="fas fa-mosque"></i>
                                        {{ $bayan->masjid->name }}
                                    </span>
                                @endif
                                <span class="bayan-meta-item">
                                    <i class="fas fa-eye"></i> {{ number_format($bayan->views) }}
                                </span>
                                @if($bayan->date)
                                    <span class="bayan-meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $bayan->date->format('d M Y') }}
                                    </span>
                                @endif
                            </div>

                            <div class="bayan-actions mt-3">
                                @if($bayan->youtube_url)
                                    <a href="{{ route('live-bayan.show', $bayan) }}"
                                       class="btn btn-bayan-watch">
                                        <i class="fab fa-youtube me-1"></i> Watch
                                    </a>
                                @endif
                                @if($bayan->audio_url)
                                    <a href="{{ $bayan->audio_url }}" target="_blank"
                                       class="btn btn-bayan-listen">
                                        <i class="fas fa-headphones me-1"></i> Listen
                                    </a>
                                @endif
                                <a href="{{ route('live-bayan.show', $bayan) }}"
                                   class="btn btn-bayan-details">
                                    <i class="fas fa-info-circle me-1"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $bayans->links() }}
        </div>

    @else
        {{-- Empty State --}}
        <div class="bayan-empty-state">
            <div class="bayan-empty-icon">
                <i class="fas fa-microphone-slash"></i>
            </div>
            <h3>No Bayans Found</h3>
            @if(request('q') || request('category'))
                <p>No results match your search criteria. Try adjusting your filters.</p>
                <a href="{{ route('live-bayan.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-redo me-1"></i> View All Bayans
                </a>
            @else
                <p>The Bayan Library is being set up. Check back soon for Islamic lectures and khutbahs.</p>
                <a href="{{ route('admin.bayans.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-1"></i> Add First Bayan
                </a>
            @endif
        </div>
    @endif

</div>
@endsection
