@extends('layouts.admin')

@section('title', 'Manage Events')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Events</h5>
  <a href="{{ route('admin.create-event') }}" class="btn btn-success btn-sm">Add Event</a>
</div>
<div class="card shadow-sm border-0 rounded-4 p-4">
  <div class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>Title</th><th>Date</th><th>Time</th><th>Category</th><th>Sect</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($events as $event)
          <tr>
            <td>{{ $event->title }}</td>
            <td>{{ $event->event_date->format('d M Y') }}</td>
            <td>{{ $event->event_time }}</td>
            <td>{{ $event->category }}</td>
            <td>{{ $event->sect }}</td>
            <td>
              <a href="{{ route('admin.edit-event', $event) }}" class="btn btn-sm btn-outline-primary me-2">Edit</a>
              <form action="{{ route('admin.delete-event') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="id" value="{{ $event->id }}">
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-3">No events yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
