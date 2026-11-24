@extends('layouts.admin')

@section('title', 'Ramadan Timings')

@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card shadow-sm border-0 rounded-4 p-4">
      <h5 class="mb-3">Add Ramadan Entry</h5>
      <form method="POST" action="{{ route('admin.store-ramadan') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">City</label>
          <input type="text" name="city" class="form-control" value="Karachi" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Year</label>
          <input type="number" name="year" class="form-control" value="{{ now()->year }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Day</label>
          <input type="number" name="day" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Sehri Time</label>
          <input type="text" name="sehri_time" class="form-control" placeholder="04:30">
        </div>
        <div class="mb-3">
          <label class="form-label">Iftar Time</label>
          <input type="text" name="iftar_time" class="form-control" placeholder="18:45">
        </div>
        <div class="mb-3">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control"></textarea>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
          <label class="form-check-label">Active</label>
        </div>
        <button class="btn btn-success">Save Entry</button>
      </form>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card shadow-sm border-0 rounded-4 p-4">
      <h5 class="mb-3">Ramadan Timings</h5>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>City</th><th>Year</th><th>Day</th><th>Sehri</th><th>Iftar</th><th>Notes</th></tr></thead>
          <tbody>
            @forelse($timings as $entry)
              <tr><td>{{ $entry->city }}</td><td>{{ $entry->year }}</td><td>{{ $entry->day }}</td><td>{{ $entry->sehri_time }}</td><td>{{ $entry->iftar_time }}</td><td>{{ $entry->notes }}</td></tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-3">No Ramadan entries yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
