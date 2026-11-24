@extends('layouts.admin')

@section('title', 'Donation Campaigns')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="mb-0">Donation Campaigns</h5>
    <p class="text-muted small mb-0">Manage fundraising campaigns for every masjid.</p>
  </div>
  <a href="{{ route('admin.donations.create') }}" class="btn btn-success btn-sm">Add Campaign</a>
</div>

<div class="admin-table-card mb-4">
  <div class="card-header">Filters</div>
  <div class="card-body">
    <form class="row g-3" method="GET" action="{{ route('admin.donations') }}">
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Paused</option>
          <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Masjid</label>
        <select name="masjid_id" class="form-select">
          <option value="">All Masajid</option>
          @foreach($masjids as $masjid)
            <option value="{{ $masjid->id }}" {{ request('masjid_id') == $masjid->id ? 'selected' : '' }}>{{ $masjid->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary-custom w-100">Apply Filters</button>
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
            <th>Title</th>
            <th>Masjid</th>
            <th>Target</th>
            <th>Raised</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($campaigns as $campaign)
            <tr>
              <td>{{ $campaign->title }}</td>
              <td>{{ $campaign->masjid->name }}</td>
              <td>₹{{ number_format($campaign->target_amount, 2) }}</td>
              <td>₹{{ number_format($campaign->collected_amount, 2) }}</td>
              <td class="text-capitalize">{{ $campaign->status }}</td>
              <td>
                <div class="progress" style="height: 10px;">
                  <div class="progress-bar bg-success" role="progressbar" style="width: {{ $campaign->percentage_completed }}%;" aria-valuenow="{{ $campaign->percentage_completed }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small>{{ $campaign->progress_label }}</small>
              </td>
              <td>
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('admin.donations.edit', $campaign) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                  <a href="{{ route('admin.donations.history', $campaign) }}" class="btn btn-sm btn-outline-success">History</a>
                  <form action="{{ route('admin.donations.destroy', $campaign) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this campaign?');">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No donation campaigns found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
