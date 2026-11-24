@extends('layouts.admin')

@section('title', 'Timings')

@section('content')
<div class="row g-4">
  <div class="col-lg-5">
    <div class="admin-form-card">
      <h5><i class="fas fa-clock"></i> Select & Update Timings</h5>

      <form action="{{ route('admin.timings') }}" method="GET" class="mb-4">
        <label class="form-label fw-bold text-success">Choose Mosque</label>
        <select name="masjid_id" class="form-select form-select-sm" onchange="this.form.submit()">
          @foreach($masjids as $masjid)
            <option value="{{ $masjid->id }}" @selected($selectedMasjid && $selectedMasjid->id === $masjid->id)>[{{ $masjid->sect }}] {{ $masjid->name }}</option>
          @endforeach
        </select>
      </form>

      @if($selectedMasjid)
        <form action="{{ route('admin.update-timings') }}" method="POST">
          @csrf
          <input type="hidden" name="masjid_id" value="{{ $selectedMasjid->id }}">
          <div class="mb-3">
            <label class="form-label">Fajr *</label>
            <input type="time" name="fajr" class="form-control" value="{{ $selectedMasjid->fajr }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Zuhr *</label>
            <input type="time" name="zuhr" class="form-control" value="{{ $selectedMasjid->zuhr }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Asr *</label>
            <input type="time" name="asr" class="form-control" value="{{ $selectedMasjid->asr }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Maghrib *</label>
            <input type="time" name="maghrib" class="form-control" value="{{ $selectedMasjid->maghrib }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Isha *</label>
            <input type="time" name="isha" class="form-control" value="{{ $selectedMasjid->isha }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Juma (Friday) *</label>
            <input type="time" name="juma_time" class="form-control" value="{{ $selectedMasjid->juma_time }}" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Eid Prayer</label>
            <input type="time" name="eid_time" class="form-control" value="{{ $selectedMasjid->eid_time }}">
          </div>
          <button type="submit" class="btn btn-success fw-bold w-100 py-2">
            <i class="fas fa-save me-1"></i> Save Timings
          </button>
        </form>
      @else
        <div class="alert alert-warning">Please register mosques first.</div>
      @endif
    </div>
  </div>

  <div class="col-lg-7">
    <div class="admin-table-card h-100">
      <div class="card-header"><i class="fas fa-table"></i> Current Timings Directory</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table admin-table mb-0 text-center align-middle">
            <thead class="table-success align-middle">
              <tr>
                <th class="text-start ps-3">Name</th>
                <th>Fajr</th>
                <th>Zuhr</th>
                <th>Asr</th>
                <th>Maghrib</th>
                <th>Isha</th>
                <th>Juma</th>
              </tr>
            </thead>
            <tbody>
              @forelse($masjids as $masjid)
                <tr class="{{ $selectedMasjid && $selectedMasjid->id === $masjid->id ? 'table-success' : '' }}">
                  <td class="text-start ps-3">
                    <a href="{{ route('admin.timings', ['masjid_id' => $masjid->id]) }}" class="fw-bold text-success text-decoration-none">{{ $masjid->name }}</a>
                  </td>
                  <td>{{ $masjid->fajr ?: '-' }}</td>
                  <td>{{ $masjid->zuhr ?: '-' }}</td>
                  <td>{{ $masjid->asr ?: '-' }}</td>
                  <td>{{ $masjid->maghrib ?: '-' }}</td>
                  <td>{{ $masjid->isha ?: '-' }}</td>
                  <td class="fw-bold">{{ $masjid->juma_time ?: '-' }}</td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No timings found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
