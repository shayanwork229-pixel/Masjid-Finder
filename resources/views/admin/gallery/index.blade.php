@extends('layouts.admin')

@section('title', 'Islamic Gallery — Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="fas fa-images me-2 text-success"></i>Islamic Gallery</h4>
        <small class="text-muted">Manage beautiful captures of masajid and events</small>
    </div>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Upload Image
    </a>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <i class="fas fa-image fa-2x text-success mb-2"></i>
                <h5 class="mb-0">{{ \App\Models\GalleryImage::count() }}</h5>
                <small class="text-muted">Total Images</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <i class="fas fa-star fa-2x text-warning mb-2"></i>
                <h5 class="mb-0">{{ \App\Models\GalleryImage::where('is_featured', true)->count() }}</h5>
                <small class="text-muted">Featured</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <i class="fas fa-eye fa-2x text-info mb-2"></i>
                <h5 class="mb-0">{{ number_format(\App\Models\GalleryImage::sum('views')) }}</h5>
                <small class="text-muted">Total Views</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                <h5 class="mb-0">{{ number_format(\App\Models\GalleryImage::sum('likes')) }}</h5>
                <small class="text-muted">Total Likes</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($images->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="80">Image</th>
                            <th>Title & Details</th>
                            <th>Category</th>
                            <th>Views / Likes</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($images as $image)
                            <tr>
                                <td>
                                    <a href="{{ $image->image_url }}" target="_blank">
                                        <img src="{{ $image->image_url }}"
                                             alt="{{ $image->title }}"
                                             class="rounded"
                                             style="width:60px;height:45px;object-fit:cover;">
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ Str::limit($image->title, 40) }}</strong>
                                    <div class="text-muted small">
                                        @if($image->masjid)
                                            <i class="fas fa-mosque me-1"></i>{{ $image->masjid->name }}
                                        @elseif($image->city)
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $image->city }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($image->category)
                                        <span class="badge bg-success-subtle text-success border border-success">{{ $image->category }}</span>
                                    @else — @endif
                                </td>
                                <td>
                                    <span class="text-info me-2" title="Views"><i class="fas fa-eye me-1"></i>{{ number_format($image->views) }}</span>
                                    <span class="text-danger" title="Likes"><i class="fas fa-heart me-1"></i>{{ number_format($image->likes) }}</span>
                                </td>
                                <td>
                                    @if($image->status !== 'active')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @else
                                        @if($image->is_featured)
                                            <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Featured</span>
                                        @endif
                                        @if($image->is_trending)
                                            <span class="badge bg-danger"><i class="fas fa-fire me-1"></i>Trending</span>
                                        @endif
                                        @if(!$image->is_featured && !$image->is_trending)
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $image->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('gallery.show', $image) }}" class="btn btn-sm btn-outline-info" target="_blank" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.gallery.edit', $image) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.gallery.destroy') }}" class="d-inline"
                                          onsubmit="return confirm('Delete this image?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $image->id }}">
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
                {{ $images->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <p class="text-muted">No images yet. <a href="{{ route('admin.gallery.create') }}">Upload the first image</a>.</p>
            </div>
        @endif
    </div>
</div>
@endsection
