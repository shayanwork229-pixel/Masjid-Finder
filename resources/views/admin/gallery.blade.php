@extends('layouts.admin')

@section('title', 'Masjid Gallery')

@section('content')
<div class="card shadow-sm border-0 rounded-4 p-4">
  <h5 class="mb-3">Gallery for {{ $masjid->name }}</h5>
  <form method="POST" action="{{ route('admin.upload-gallery', $masjid) }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label class="form-label">Upload Images</label>
      <input type="file" name="images[]" class="form-control" multiple>
    </div>
    <button class="btn btn-success">Upload</button>
  </form>
  <div class="row g-3 mt-3">
    @forelse($masjid->images as $image)
      <div class="col-md-3">
        <div class="border rounded p-2">
          <img src="{{ asset('storage/'.$image->image_path) }}" class="img-fluid rounded" alt="">
          <div class="mt-2 d-flex justify-content-between">
            <small>{{ $image->caption }}</small>
            <form method="POST" action="{{ route('admin.delete-gallery-image', $image) }}">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-muted">No gallery images yet.</div>
    @endforelse
  </div>
</div>
@endsection
