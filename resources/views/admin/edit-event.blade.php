@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
<div class="card shadow-sm border-0 rounded-4 p-4">
  <form method="POST" action="{{ route('admin.update-event', $event) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ $event->title }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="{{ $event->category }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Event Date</label>
        <input type="date" name="event_date" class="form-control" value="{{ $event->event_date->format('Y-m-d') }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Event Time</label>
        <input type="text" name="event_time" class="form-control" value="{{ $event->event_time }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Sect</label>
        <select name="sect" class="form-select">
          <option value="">Select</option>
          <option value="Sunni" {{ $event->sect === 'Sunni' ? 'selected' : '' }}>Sunni</option>
          <option value="Shia" {{ $event->sect === 'Shia' ? 'selected' : '' }}>Shia</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Assigned Masjid</label>
        <select name="masjid_id" class="form-select">
          <option value="">None</option>
          @foreach($masjids as $masjid)
            <option value="{{ $masjid->id }}" {{ $event->masjid_id == $masjid->id ? 'selected' : '' }}>{{ $masjid->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ $event->description }}</textarea>
      </div>
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ $event->is_featured ? 'checked' : '' }}>
          <label class="form-check-label">Featured Event</label>
        </div>
      </div>
    </div>
    <button class="btn btn-success mt-3">Update Event</button>
  </form>
</div>
@endsection
