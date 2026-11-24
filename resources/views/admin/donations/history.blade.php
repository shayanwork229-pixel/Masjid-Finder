@extends('layouts.admin')

@section('title', 'Donation History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="mb-0">Donation History — {{ $campaign->title }}</h5>
    <p class="text-muted small mb-0">Search, filter, and review donations for this campaign.</p>
  </div>
  <a href="{{ route('admin.donations') }}" class="btn btn-outline-custom btn-sm">Back to Campaigns</a>
</div>

<div class="admin-table-card mb-4">
  <div class="card-body">
    <form class="row g-3" method="GET">
      <div class="col-md-4">
        <label class="form-label">Category</label>
        <select name="category" class="form-select">
          <option value="">All</option>
          @foreach($categories as $key => $label)
            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">From</label>
        <input name="date_from" type="date" class="form-control" value="{{ request('date_from') }}">
      </div>
      <div class="col-md-2">
        <label class="form-label">To</label>
        <input name="date_to" type="date" class="form-control" value="{{ request('date_to') }}">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary-custom w-100">Search</button>
      </div>
    </form>
  </div>
</div>

<div class="admin-table-card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead>
          <tr>
            <th>Donor</th>
            <th>Amount</th>
            <th>Category</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($donations as $donation)
            <tr>
              <td>{{ $donation->display_name }}</td>
              <td>₹{{ number_format($donation->amount, 2) }}</td>
              <td>{{ $categories[$donation->category] ?? $donation->category }}</td>
              <td>{{ $donation->created_at->format('d M Y') }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center py-4 text-muted">No donations found for this campaign.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
