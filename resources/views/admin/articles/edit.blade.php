@extends('layouts.admin')

@section('title', 'Edit Article')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="fas fa-edit me-2 text-success"></i>Edit Article</h4>
        <small class="text-muted">Update article content and publishing settings</small>
    </div>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Articles
    </a>
</div>

<form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-info-circle me-2"></i>Article Content
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $article->title) }}" placeholder="Article title" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Author</label>
                            <input type="text" name="author" class="form-control"
                                   value="{{ old('author', $article->author) }}" placeholder="Scholar or author name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category', $article->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Excerpt</label>
                        <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror"
                                  rows="3" maxlength="500" placeholder="Short summary shown on article cards">{{ old('excerpt', $article->excerpt) }}</textarea>
                        @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                        @include('admin.articles._rich-editor', ['content' => $article->content])
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-tags me-2"></i>Tags
                </div>
                <div class="card-body">
                    <label class="form-label fw-semibold">Tags</label>
                    <input type="text" name="tags" class="form-control"
                           value="{{ old('tags', $article->tags) }}" placeholder="Quran, Prayer, Family">
                    <div class="form-text">Separate tags with commas.</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-magnifying-glass-chart me-2"></i>SEO
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control"
                               value="{{ old('meta_title', $article->meta_title) }}" maxlength="255" placeholder="Optional search title">
                    </div>
                    <label class="form-label fw-semibold">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3" maxlength="500"
                              placeholder="Optional search description">{{ old('meta_description', $article->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-image me-2"></i>Cover Image
                </div>
                <div class="card-body text-center">
                    @if($article->image_path)
                        <div class="mb-3">
                            <img src="{{ $article->image_url }}" alt="Current cover"
                                 class="img-fluid rounded" style="max-height:180px;object-fit:cover;">
                            <div class="small text-muted mt-1">Current Cover</div>
                        </div>
                    @endif
                    <div class="text-start">
                        <label class="form-label fw-semibold">Update Cover</label>
                        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror"
                               accept="image/*" id="coverInput" onchange="previewCover(this)">
                        @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Leave blank to keep the current cover.</div>
                    </div>
                    <div id="coverPreview" class="d-none mt-3">
                        <img id="coverPreviewImg" src="" alt="Cover preview"
                             class="img-fluid rounded" style="max-height:180px;object-fit:cover;">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-toggle-on me-2"></i>Publishing
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_published" id="is_published"
                               value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">Published</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                               value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Featured</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_trending" id="is_trending"
                               value="1" {{ old('is_trending', $article->is_trending) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_trending">Trending</label>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Publish Date</label>
                        <input type="datetime-local" name="published_at" class="form-control"
                               value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}">
                        <div class="form-text">Leave empty to use the current date when published.</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save me-1"></i> Update Article
                </button>
                @if($article->slug && $article->is_published)
                    <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-info" target="_blank">
                        <i class="fas fa-eye me-1"></i> View Public Article
                    </a>
                @endif
                <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
function previewCover(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (event) => {
            document.getElementById('coverPreviewImg').src = event.target.result;
            document.getElementById('coverPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
