@extends('layouts.public')

@section('title', 'Ramadan Calendar')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 p-4">
          <h3 class="section-title">Karachi Ramadan Timings</h3>
          <p class="text-muted">Monthly Ramadan schedule for the city of Karachi.</p>
          <form method="GET" class="row g-2">
            <div class="col-8">
              <select name="year" class="form-select">
                @for($year = now()->year; $year <= now()->year + 1; $year++)
                  <option value="{{ $year }}" {{ $year == $year ?? '' ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
              </select>
            </div>
            <div class="col-4">
              <button class="btn btn-success w-100">Go</button>
            </div>
          </form>
          @if($latest)
            <div class="mt-4 p-3 rounded-3 bg-light">
              <div class="small text-uppercase text-muted">Current Entry</div>
              <div class="fw-bold">Day {{ $latest->day }}</div>
              <div class="small">Sehri: {{ $latest->sehri_time }}</div>
              <div class="small">Iftar: {{ $latest->iftar_time }}</div>
            </div>
          @endif
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Daily Ramadan Schedule</h4>
            <button class="btn btn-outline-success btn-sm" onclick="window.print()"><i class="fas fa-print me-1"></i> Print</button>
          </div>
          <div class="table-responsive">
            <table class="table table-striped align-middle">
              <thead>
                <tr>
                  <th>Day</th>
                  <th>Sehri</th>
                  <th>Iftar</th>
                  <th>Notes</th>
                </tr>
              </thead>
              <tbody>
                @forelse($timings as $entry)
                  <tr>
                    <td>{{ $entry->day }}</td>
                    <td>{{ $entry->sehri_time ?? '—' }}</td>
                    <td>{{ $entry->iftar_time ?? '—' }}</td>
                    <td>{{ $entry->notes ?? '—' }}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted py-4">No Ramadan timings available yet.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
