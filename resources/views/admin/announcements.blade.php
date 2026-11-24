@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card shadow-sm border-0 rounded-4 p-4">
      <h5 class="mb-3">Create Announcement</h5>
      <form method="POST" action="{{ route('admin.store-announcement') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Content</label>
          <textarea name="content" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Priority</label>
          <select name="priority" class="form-select">
            <option value="low">Low</option>
            <option value="normal" selected>Normal</option>
            <option value="high">High</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Expiry Date</label>
          <input type="date" name="expiry_date" class="form-control">
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
          <label class="form-check-label">Active</label>
        </div>
        <button class="btn btn-success">Publish</button>
      </form>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card shadow-sm border-0 rounded-4 p-4">
      <h5 class="mb-3">Announcements</h5>
      <div class="list-group">
        @forelse($announcements as $announcement)
          <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h6 class="mb-1">{{ $announcement->title }}</h6>
                <p class="small text-muted mb-0">{{ $announcement->content }}</p>
              </div>
              <span class="badge bg-success">{{ ucfirst($announcement->priority) }}</span>
            </div>
          </div>
        @empty
          <div class="text-muted py-3">No announcements yet.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
