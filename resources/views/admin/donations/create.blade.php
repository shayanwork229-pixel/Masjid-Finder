@extends('layouts.admin')

@section('title', 'Add Donation Campaign')

@section('content')
<div class="admin-form-card">
  <h5>Create Donation Campaign</h5>
  <form action="{{ route('admin.donations.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Campaign Title</label>
        <input name="title" type="text" class="form-control" value="{{ old('title') }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Assign Masjid</label>
        <select name="masjid_id" class="form-select" required>
          <option value="">Select Masjid</option>
          @foreach($masjids as $masjid)
            <option value="{{ $masjid->id }}" {{ old('masjid_id') == $masjid->id ? 'selected' : '' }}>{{ $masjid->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Target Amount</label>
        <input name="target_amount" type="number" min="0" step="0.01" class="form-control" value="{{ old('target_amount') }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Collected Amount</label>
        <input name="collected_amount" type="number" min="0" step="0.01" class="form-control" value="{{ old('collected_amount', 0) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Campaign Image</label>
        <input name="image" type="file" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Start Date</label>
        <input name="start_date" type="date" class="form-control" value="{{ old('start_date') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">End Date</label>
        <input name="end_date" type="date" class="form-control" value="{{ old('end_date') }}">
      </div>
      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
      </div>
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
          <label class="form-check-label" for="is_featured">Featured Campaign</label>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="is_emergency" id="is_emergency" value="1" {{ old('is_emergency') ? 'checked' : '' }}>
          <label class="form-check-label" for="is_emergency">Emergency Campaign</label>
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="paused" {{ old('status') === 'paused' ? 'selected' : '' }}>Paused</option>
          <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
      </div>
      <div class="col-12 text-end">
        <button class="btn btn-success">Save Campaign</button>
      </div>
    </div>
  </form>
</div>
@endsection
