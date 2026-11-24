@extends('layouts.admin')

@section('title', 'Islamic Articles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="fas fa-newspaper me-2 text-success"></i>Islamic Articles</h4>
        <small class="text-muted">Manage published articles, drafts, categories, and featured content</small>
    </div>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Add New Article
    </a>
</div>

    <div class="row g-3 mb-4">
        <div class="col-sm-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <i class="fas fa-newspaper fa-2x text-success mb-2"></i>
                    <h5 class="mb-0">{{ $stats->total }}</h5>
                    <small class="text-muted">Total Articles</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <i class="fas fa-check-circle fa-2x text-primary mb-2"></i>
                    <h5 class="mb-0">{{ $stats->published }}</h5>
                    <small class="text-muted">Published</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                    <h5 class="mb-0">{{ $stats->featured }}</h5>
                    <small class="text-muted">Featured</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <i class="fas fa-eye fa-2x text-info mb-2"></i>
                    <h5 class="mb-0">{{ number_format($stats->views) }}</h5>
                    <small class="text-muted">Total Views</small>
                </div>
            </div>
        </div>
    </div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="row g-2 align-items-end">
            <div class="col-lg-4">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Title, author, or content">
            </div>
            <div class="col-lg-3">
                <label class="form-label small fw-semibold">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Featured</option>
                    <option value="trending" {{ request('status') === 'trending' ? 'selected' : '' }}>Trending</option>
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary" title="Clear filters">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        @if($articles->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="72">Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Published</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>
                                    <img src="{{ $article->image_url }}"
                                         alt="{{ $article->title }}"
                                         class="rounded"
                                         width="56" height="42"
                                         decoding="async"
                                         loading="lazy"
                                         style="object-fit:cover;"
                                         onerror="this.src='{{ asset('assets/img/article-placeholder.jpg') }}'">
                                </td>
                                <td>
                                    <strong>{{ Str::limit($article->title, 48) }}</strong>
                                    <div class="small text-muted">
                                        {{ $article->reading_time }} min read
                                        @if($article->slug)
                                            <span class="mx-1">/</span>{{ $article->slug }}
                                        @endif
                                    </div>
                                </td>
                                <td class="text-muted">{{ $article->author ?: 'None' }}</td>
                                <td>
                                    @if($article->category)
                                        <span class="badge bg-success-subtle text-success border border-success">{{ $article->category }}</span>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->is_published)
                                        <span class="badge bg-primary"><i class="fas fa-check me-1"></i>Published</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-pencil-alt me-1"></i>Draft</span>
                                    @endif
                                    @if($article->is_featured)
                                        <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Featured</span>
                                    @endif
                                    @if($article->is_trending)
                                        <span class="badge bg-danger"><i class="fas fa-fire me-1"></i>Trending</span>
                                    @endif
                                </td>
                                <td>{{ number_format($article->views) }}</td>
                                <td class="text-muted small">{{ $article->published_at?->format('d M Y') ?? 'Not set' }}</td>
                                <td>
                                    @if($article->slug && $article->is_published)
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-info" target="_blank" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.articles.destroy') }}" class="d-inline"
                                          onsubmit="return confirm('Delete this article?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $article->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                <p class="text-muted">No articles yet. <a href="{{ route('admin.articles.create') }}">Add the first article</a>.</p>
            </div>
        @endif
    </div>
</div>
@endsection
