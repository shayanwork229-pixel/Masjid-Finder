@extends('layouts.admin')

@section('title', 'Edit Donation Campaign')

@section('content')
<div class="admin-form-card">
  <h5>Edit Donation Campaign</h5>
  <form action="{{ route('admin.donations.update', $campaign) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Campaign Title</label>
        <input name="title" type="text" class="form-control" value="{{ old('title', $campaign->title) }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Assign Masjid</label>
        <select name="masjid_id" class="form-select" required>
          <option value="">Select Masjid</option>
          @foreach($masjids as $masjid)
            <option value="{{ $masjid->id }}" {{ old('masjid_id', $campaign->masjid_id) == $masjid->id ? 'selected' : '' }}>{{ $masjid->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Target Amount</label>
        <input name="target_amount" type="number" min="0" step="0.01" class="form-control" value="{{ old('target_amount', $campaign->target_amount) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Collected Amount</label>
        <input name="collected_amount" type="number" min="0" step="0.01" class="form-control" value="{{ old('collected_amount', $campaign->collected_amount) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Campaign Image</label>
        <input name="image" type="file" class="form-control">
        @if($campaign->image_path)
          <div class="mt-2 small text-muted">Current image: {{ basename($campaign->image_path) }}</div>
        @endif
      </div>
      <div class="col-md-6">
        <label class="form-label">Start Date</label>
        <input name="start_date" type="date" class="form-control" value="{{ old('start_date', $campaign->start_date?->format('Y-m-d')) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">End Date</label>
        <input name="end_date" type="date" class="form-control" value="{{ old('end_date', $campaign->end_date?->format('Y-m-d')) }}">
      </div>
      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $campaign->description) }}</textarea>
      </div>
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $campaign->is_featured) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_featured">Featured Campaign</label>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="is_emergency" id="is_emergency" value="1" {{ old('is_emergency', $campaign->is_emergency) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_emergency">Emergency Campaign</label>
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="active" {{ old('status', $campaign->status) === 'active' ? 'selected' : '' }}>Active</option>
          <option value="paused" {{ old('status', $campaign->status) === 'paused' ? 'selected' : '' }}>Paused</option>
          <option value="closed" {{ old('status', $campaign->status) === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
      </div>
      <div class="col-12 text-end">
        <button class="btn btn-success">Update Campaign</button>
      </div>
    </div>
  </form>
</div>
@endsection
