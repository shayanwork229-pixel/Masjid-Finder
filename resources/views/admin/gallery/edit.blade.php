@extends('layouts.admin')

@section('title', 'Edit Image — Islamic Gallery')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="fas fa-edit me-2 text-success"></i>Edit Image</h4>
        <small class="text-muted">Update image details and settings</small>
    </div>
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Gallery
    </a>
</div>

<form method="POST" action="{{ route('admin.gallery.update', $image) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-info-circle me-2"></i>Image Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $image->title) }}" placeholder="Image title…" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Location / City</label>
                            <input type="text" name="city" class="form-control"
                                   value="{{ old('city', $image->city) }}" placeholder="e.g. Mecca, Saudi Arabia">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Related Masjid (Optional)</label>
                            <select name="masjid_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($masjids as $m)
                                    <option value="{{ $m->id }}" {{ old('masjid_id', $image->masjid_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" class="form-select">
                                <option value="">— Select Category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $image->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Uploaded By</label>
                            <input type="text" name="uploaded_by" class="form-control"
                                   value="{{ old('uploaded_by', $image->uploaded_by) }}" placeholder="Photographer name or agency">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                                  placeholder="Brief details about the image…">{{ old('description', $image->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-file-image me-2"></i>Image File
                </div>
                <div class="card-body text-center">
                    @if($image->image_url)
                        <div class="mb-3">
                            <a href="{{ $image->image_url }}" target="_blank">
                                <img src="{{ $image->image_url }}" alt="Current Image"
                                     class="img-fluid rounded shadow-sm" style="max-height:160px;object-fit:cover;">
                            </a>
                            <div class="small text-muted mt-1">Current Image</div>
                        </div>
                    @endif
                    <div class="mb-3 text-start">
                        <label class="form-label fw-semibold">Update Photo</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                               accept="image/*" id="imageInput" onchange="previewImage(this)">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Leave blank to keep current image. Max size: 4MB</div>
                    </div>
                    <div id="imagePreview" class="d-none mt-2 text-center">
                        <img id="imagePreviewImg" src="" alt="Preview"
                             class="img-fluid rounded shadow-sm" style="max-height:160px;object-fit:cover;">
                        <div class="small text-muted mt-1">New Image Preview</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-toggle-on me-2"></i>Status & Flags
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $image->status) === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                            <option value="inactive" {{ old('status', $image->status) === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                               value="1" {{ old('is_featured', $image->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <span class="badge bg-warning text-dark me-1"><i class="fas fa-star"></i></span> Featured
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_trending" id="is_trending"
                               value="1" {{ old('is_trending', $image->is_trending) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_trending">
                            <span class="badge bg-danger me-1"><i class="fas fa-fire"></i></span> Trending
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save me-1"></i> Update Image
                </button>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('imagePreviewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
