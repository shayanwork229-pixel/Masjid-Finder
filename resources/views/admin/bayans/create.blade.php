@extends('layouts.admin')

@section('title', 'Add New Bayan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Add New Bayan</h4>
        <small class="text-muted">Add an Islamic lecture or khutbah to the library</small>
    </div>
    <a href="{{ route('admin.bayans.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Library
    </a>
</div>

<form method="POST" action="{{ route('admin.bayans.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-info-circle me-2"></i>Bayan Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="Bayan title…" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Scholar Name</label>
                            <input type="text" name="scholar_name" class="form-control"
                                   value="{{ old('scholar_name') }}" placeholder="e.g. Sheikh Tariq Jameel">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Masjid</label>
                            <select name="masjid_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($masjids as $m)
                                    <option value="{{ $m->id }}" {{ old('masjid_id') == $m->id ? 'selected' : '' }}>
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
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Duration</label>
                            <input type="text" name="duration" class="form-control"
                                   value="{{ old('duration') }}" placeholder="e.g. 45:00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date') }}">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                                  placeholder="Brief description of the bayan topic…">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-link me-2"></i>Media Links
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fab fa-youtube text-danger me-1"></i> YouTube URL
                        </label>
                        <input type="url" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror"
                               value="{{ old('youtube_url') }}"
                               placeholder="https://www.youtube.com/watch?v=...">
                        @error('youtube_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Paste the full YouTube watch URL or short youtu.be link</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-headphones text-info me-1"></i> Audio URL
                        </label>
                        <input type="url" name="audio_url" class="form-control @error('audio_url') is-invalid @enderror"
                               value="{{ old('audio_url') }}" placeholder="https://…/bayan.mp3">
                        @error('audio_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-image me-2"></i>Thumbnail
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control"
                               accept="image/*" id="thumbInput" onchange="previewThumb(this)">
                        <div class="form-text">Leave blank to auto-fetch from YouTube</div>
                    </div>
                    <div id="thumbPreview" class="d-none mt-2">
                        <img id="thumbPreviewImg" src="" alt="Preview"
                             class="img-fluid rounded" style="max-height:160px;object-fit:cover;">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-toggle-on me-2"></i>Status & Flags
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_live" id="is_live"
                               value="1" {{ old('is_live') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_live">
                            <span class="badge bg-danger me-1">LIVE</span> Mark as Live Stream
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                               value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <span class="badge bg-warning text-dark me-1"><i class="fas fa-star"></i></span> Featured
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_trending" id="is_trending"
                               value="1" {{ old('is_trending') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_trending">
                            <span class="badge bg-danger me-1"><i class="fas fa-fire"></i></span> Trending
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save me-1"></i> Save Bayan
                </button>
                <a href="{{ route('admin.bayans.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
function previewThumb(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('thumbPreviewImg').src = e.target.result;
            document.getElementById('thumbPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
