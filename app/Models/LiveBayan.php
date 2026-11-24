<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LiveBayan extends Model
{
    protected $fillable = [
        'title', 'slug', 'scholar_name', 'masjid_id', 'description',
        'youtube_url', 'audio_url', 'thumbnail_path', 'category',
        'duration', 'views', 'date', 'is_live', 'is_featured', 'is_trending',
    ];

    protected $casts = [
        'date'        => 'date',
        'is_live'     => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($bayan) {
            if (empty($bayan->slug)) {
                $bayan->slug = static::generateUniqueSlug($bayan->title);
            }
        });

        static::updating(function ($bayan) {
            if ($bayan->isDirty('title') && empty($bayan->slug)) {
                $bayan->slug = static::generateUniqueSlug($bayan->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count ? $slug . '-' . ($count + 1) : $slug;
    }

    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->youtube_url) return null;

        // Handle youtu.be short links
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $this->youtube_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Handle youtube.com/watch?v=
        if (preg_match('/[?&]v=([a-zA-Z0-9_-]+)/', $this->youtube_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Handle youtube.com/embed/ already
        if (str_contains($this->youtube_url, '/embed/')) {
            return $this->youtube_url;
        }

        return $this->youtube_url;
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path && is_file(public_path('storage/' . $this->thumbnail_path))) {
            return asset('storage/' . $this->thumbnail_path);
        }

        // Extract YouTube thumbnail from URL
        if ($this->youtube_url) {
            if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $this->youtube_url, $m)) {
                return 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
            }
            if (preg_match('/[?&]v=([a-zA-Z0-9_-]+)/', $this->youtube_url, $m)) {
                return 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
            }
        }

        return asset('assets/img/bayan-placeholder.jpg');
    }

    public static function categories(): array
    {
        return ['Quran', 'Hadith', 'Seerat', 'Ramadan', 'Muharram', 'Prayer', 'Family', 'Youth'];
    }

    public function scopeSearch($query, ?string $search)
    {
        return $query->when($search, fn($q) => $q->where(function ($q2) use ($search) {
            $q2->where('title', 'like', "%{$search}%")
               ->orWhere('scholar_name', 'like', "%{$search}%")
               ->orWhere('description', 'like', "%{$search}%");
        }));
    }

    public function scopeCategory($query, ?string $category)
    {
        return $query->when($category, fn($q) => $q->where('category', $category));
    }

    public function scopeScholar($query, ?string $scholar)
    {
        return $query->when($scholar, fn($q) => $q->where('scholar_name', 'like', "%{$scholar}%"));
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
