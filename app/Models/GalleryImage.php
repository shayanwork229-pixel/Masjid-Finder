<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    protected $fillable = [
        'title', 'image_path', 'masjid_id', 'city', 'category',
        'description', 'views', 'likes', 'is_featured', 'is_trending',
        'status', 'uploaded_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
    ];

    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    public function getImageUrlAttribute(): string
    {
        $fallback = asset('assets/img/gallery-placeholder.jpg');

        if (! $this->image_path) {
            return $fallback;
        }

        if (str_starts_with($this->image_path, 'assets/')) {
            return is_file(public_path($this->image_path))
                ? asset($this->image_path)
                : $fallback;
        }

        return is_file(public_path('storage/' . $this->image_path))
            ? asset('storage/' . $this->image_path)
            : $fallback;
    }

    public static function categories(): array
    {
        return ['Masajid', 'Ramadan', 'Eid', 'Muharram', 'Architecture', 'Events', 'Night View'];
    }

    public function scopeSearch($query, ?string $search)
    {
        return $query->when($search, fn($q) => $q->where(function ($q2) use ($search) {
            $q2->where('title', 'like', "%{$search}%")
               ->orWhere('city', 'like', "%{$search}%")
               ->orWhere('description', 'like', "%{$search}%");
        }));
    }

    public function scopeCategory($query, ?string $category)
    {
        return $query->when($category, fn($q) => $q->where('category', $category));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function incrementLikes(): void
    {
        $this->increment('likes');
    }
}
