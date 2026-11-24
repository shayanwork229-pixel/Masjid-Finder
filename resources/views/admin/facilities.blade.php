@extends('layouts.admin')

@section('title', 'Facilities Management')

@section('content')
<div class="row g-4">
  <div class="col-lg-5">
    <div class="card shadow-sm border-0 rounded-4 p-4">
      <h5 class="mb-3">Add Facility</h5>
      <form method="POST" action="{{ route('admin.store-facility') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Facility Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Icon Class</label>
          <input type="text" name="icon" class="form-control" placeholder="fa-parking">
        </div>
        <button class="btn btn-success">Save Facility</button>
      </form>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="card shadow-sm border-0 rounded-4 p-4">
      <h5 class="mb-3">Assign Facilities to Masjid</h5>
      <form method="POST" action="{{ route('admin.attach-facility') }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Masjid</label>
            <select name="masjid_id" class="form-select">
              @foreach($masjids as $masjid)
                <option value="{{ $masjid->id }}">{{ $masjid->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Facility</label>
            <select name="facility_id" class="form-select">
              @foreach($facilities as $facility)
                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <button class="btn btn-success mt-3">Attach Facility</button>
      </form>
      <div class="mt-4">
        <h6>Available Facilities</h6>
        <div class="d-flex flex-wrap gap-2">
          @foreach($facilities as $facility)
            <span class="badge bg-light text-dark border">{{ $facility->name }}</span>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
