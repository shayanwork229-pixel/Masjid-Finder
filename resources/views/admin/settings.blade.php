@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="row">
  <div class="col-lg-8 mx-auto">
    <div class="admin-form-card">
      <h5><i class="fas fa-sliders-h"></i> System Settings Configuration</h5>

      <form method="POST" action="{{ route('admin.update-settings') }}">
        @csrf
        <div class="row g-3 mb-3">
          <div class="col-sm-6"><label class="form-label">Site Name</label><input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] }}" required></div>
          <div class="col-sm-6"><label class="form-label">Site Tagline</label><input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] }}"></div>
        </div>

        <h6 class="mt-4 pb-2 border-bottom text-success fw-bold"><i class="fas fa-map-marker-alt"></i> Default City & Coordinates</h6>
        <div class="row g-3 mb-3">
          <div class="col-sm-4"><label class="form-label">Default City Name</label><input type="text" name="default_city" class="form-control" value="{{ $settings['default_city'] }}" required></div>
          <div class="col-sm-4"><label class="form-label">Default Latitude</label><input type="number" step="any" name="default_lat" class="form-control" value="{{ $settings['default_lat'] }}" required></div>
          <div class="col-sm-4"><label class="form-label">Default Longitude</label><input type="number" step="any" name="default_lng" class="form-control" value="{{ $settings['default_lng'] }}" required></div>
        </div>

        <h6 class="mt-4 pb-2 border-bottom text-success fw-bold"><i class="fas fa-address-book"></i> Contact Details</h6>
        <div class="row g-3 mb-3">
          <div class="col-sm-6"><label class="form-label">Support Email Address</label><input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] }}" required></div>
          <div class="col-sm-6"><label class="form-label">Contact Phone Number</label><input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] }}" required></div>
        </div>

        <div class="mb-4">
          <label class="form-label">Postal / Office Address</label>
          <textarea name="contact_address" class="form-control" rows="2" required>{{ $settings['contact_address'] }}</textarea>
        </div>

        <button type="submit" class="btn btn-success fw-bold w-100 py-2">
          <i class="fas fa-save me-1"></i> Save Configuration Settings
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
