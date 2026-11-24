<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IslamicArticle extends Model
{
    protected $fillable = [
        'title', 'slug', 'author', 'excerpt', 'content', 'category', 'image_path',
        'is_published', 'is_featured', 'is_trending', 'reading_time',
        'views', 'published_at', 'tags', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
        'is_trending'  => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->title);
            }
            if (empty($article->reading_time)) {
                $article->reading_time = static::estimateReadingTime($article->content);
            }
            if (empty($article->published_at) && $article->is_published) {
                $article->published_at = now();
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') || empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->title, $article->id);
            }
            if ($article->isDirty('content')) {
                $article->reading_time = static::estimateReadingTime($article->content);
            }
            if ($article->isDirty('is_published') && $article->is_published && empty($article->published_at)) {
                $article->published_at = now();
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'article';
        $slug = $baseSlug;
        $suffix = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        return $slug;
    }

    public static function estimateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, (int) ceil($wordCount / 200));
    }

    public function getExcerptAttribute(): string
    {
        $excerpt = $this->attributes['excerpt'] ?? null;

        return $excerpt ?: Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($this->content))), 200);
    }

    public function getTagListAttribute(): array
    {
        if (!$this->tags) {
            return [];
        }

        return collect(explode(',', $this->tags))
            ->map(fn($tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && is_file(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }

        return asset('assets/img/article-placeholder.jpg');
    }

    public static function categories(): array
    {
        return [
            'Prayer', 'Quran', 'Hadith', 'Fiqh', 'Aqeedah',
            'Ramadan', 'Muharram', 'Hajj', 'Umrah', 'History', 'Family', 'Youth',
        ];
    }

    public function scopeSearch($query, ?string $search)
    {
        return $query->when($search, fn($q) => $q->where(function ($q2) use ($search) {
            $q2->where('title', 'like', "%{$search}%")
               ->orWhere('author', 'like', "%{$search}%")
               ->orWhere('excerpt', 'like', "%{$search}%")
               ->orWhere('content', 'like', "%{$search}%")
               ->orWhere('tags', 'like', "%{$search}%");
        }));
    }

    public function scopeCategory($query, ?string $category)
    {
        return $query->when($category, fn($q) => $q->where('category', $category));
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
