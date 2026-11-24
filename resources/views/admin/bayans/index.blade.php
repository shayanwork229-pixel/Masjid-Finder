@extends('layouts.admin')

@section('title', 'Bayan Library — Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="fas fa-microphone-alt me-2 text-success"></i>Bayan Library</h4>
        <small class="text-muted">Manage Islamic lectures and bayans</small>
    </div>
    <a href="{{ route('admin.bayans.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Add New Bayan
    </a>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <i class="fas fa-microphone fa-2x text-success mb-2"></i>
                <h5 class="mb-0">{{ \App\Models\LiveBayan::count() }}</h5>
                <small class="text-muted">Total Bayans</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <i class="fas fa-star fa-2x text-warning mb-2"></i>
                <h5 class="mb-0">{{ \App\Models\LiveBayan::where('is_featured', true)->count() }}</h5>
                <small class="text-muted">Featured</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <i class="fas fa-fire fa-2x text-danger mb-2"></i>
                <h5 class="mb-0">{{ \App\Models\LiveBayan::where('is_trending', true)->count() }}</h5>
                <small class="text-muted">Trending</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <i class="fas fa-eye fa-2x text-info mb-2"></i>
                <h5 class="mb-0">{{ number_format(\App\Models\LiveBayan::sum('views')) }}</h5>
                <small class="text-muted">Total Views</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($bayans->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">Thumb</th>
                            <th>Title</th>
                            <th>Scholar</th>
                            <th>Category</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bayans as $bayan)
                            <tr>
                                <td>
                                    <img src="{{ $bayan->thumbnail_url }}"
                                         alt="{{ $bayan->title }}"
                                         class="rounded"
                                         style="width:50px;height:38px;object-fit:cover;"
                                         onerror="this.src='{{ asset('assets/img/bayan-placeholder.jpg') }}'">
                                </td>
                                <td>
                                    <strong>{{ Str::limit($bayan->title, 45) }}</strong>
                                    @if($bayan->is_live)
                                        <span class="badge bg-danger ms-1">LIVE</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $bayan->scholar_name ?: '—' }}</td>
                                <td>
                                    @if($bayan->category)
                                        <span class="badge bg-success-subtle text-success border border-success">{{ $bayan->category }}</span>
                                    @else — @endif
                                </td>
                                <td>{{ number_format($bayan->views) }}</td>
                                <td>
                                    @if($bayan->is_featured)
                                        <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Featured</span>
                                    @endif
                                    @if($bayan->is_trending)
                                        <span class="badge bg-danger"><i class="fas fa-fire me-1"></i>Trending</span>
                                    @endif
                                    @if(!$bayan->is_featured && !$bayan->is_trending)
                                        <span class="badge bg-secondary">Normal</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $bayan->date?->format('d M Y') ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('live-bayan.show', $bayan) }}" class="btn btn-sm btn-outline-info" target="_blank" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.bayans.edit', $bayan) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.bayans.destroy') }}" class="d-inline"
                                          onsubmit="return confirm('Delete this bayan?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $bayan->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $bayans->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-microphone-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted">No bayans yet. <a href="{{ route('admin.bayans.create') }}">Add the first bayan</a>.</p>
            </div>
        @endif
    </div>
</div>
@endsection
