@extends('layouts.admin')

@section('title', 'Manage Masjids')

@section('content')
<div class="row mb-4">
  <div class="col-md-6 col-sm-12">
    <p class="text-secondary small mb-0">Total of {{ $masjids->count() }} masjids registered in database.</p>
  </div>
  <div class="col-md-6 col-sm-12 text-md-end mt-2 mt-md-0">
    <a href="{{ route('admin.add-masjid') }}" class="btn btn-success btn-sm px-3 rounded-pill fw-bold">
      <i class="fas fa-plus-circle me-1"></i> Add New Masjid
    </a>
  </div>
</div>

<div class="admin-table-card">
  <div class="card-header"><i class="fas fa-mosque me-1"></i> Masjid Directory</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Sect</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Fajr</th>
            <th>Zuhr</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($masjids as $masjid)
            <tr>
              <td>{{ $masjid->id }}</td>
              <td class="fw-bold text-success">{{ $masjid->name }}</td>
              <td><span class="badge badge-sect badge-{{ strtolower($masjid->sect) }} py-1" style="font-size:0.65rem;">{{ $masjid->sect }}</span></td>
              <td class="text-truncate" style="max-width: 200px;" title="{{ $masjid->address }}">{{ $masjid->address }}</td>
              <td>{{ $masjid->phone ?: 'N/A' }}</td>
              <td>{{ $masjid->fajr ?: '-' }}</td>
              <td>{{ $masjid->zuhr ?: '-' }}</td>
              <td>
                <div class="d-flex gap-1 align-items-center">
                  <a href="{{ route('admin.edit-masjid', $masjid) }}" class="btn-admin-action btn-edit" title="Edit Profile"><i class="fas fa-edit"></i> Edit</a>
                  <a href="{{ route('admin.gallery', $masjid) }}" class="btn-admin-action btn-edit text-info bg-info-subtle border-info-subtle" title="Gallery"><i class="fas fa-images"></i> Gallery</a>
                  <a href="{{ route('admin.timings', ['masjid_id' => $masjid->id]) }}" class="btn-admin-action btn-edit text-success bg-success-subtle border-success-subtle" title="Edit Timings"><i class="fas fa-clock"></i> Timings</a>
                  <form method="POST" action="{{ route('admin.delete-masjid') }}" onsubmit="return confirm('Are you sure you want to delete this masjid? This action cannot be undone.');" class="d-inline">
                    @csrf
                    <input type="hidden" name="id" value="{{ $masjid->id }}">
                    <button class="btn-admin-action btn-delete border-0" type="submit" title="Delete"><i class="fas fa-trash-alt"></i> Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-5 text-muted">
                <i class="fas fa-mosque fa-2x mb-2"></i>
                <p class="mb-0">No masjids found. Click "Add New Masjid" to add one.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
