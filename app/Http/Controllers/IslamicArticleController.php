<?php

namespace App\Http\Controllers;

use App\Models\IslamicArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class IslamicArticleController extends Controller
{
    /* ─────────────────────────── PUBLIC ─────────────────────────── */

    public function index(Request $request)
    {
        $query = IslamicArticle::published()->latest('published_at');

        $query = $query->search($request->input('q'))->category($request->input('category'));

        if ($request->input('tab') === 'featured') {
            $query->where('is_featured', true);
        } elseif ($request->input('tab') === 'trending') {
            $query->where('is_trending', true)->orderByDesc('views');
        }

        $articles = $query->paginate(9)->withQueryString();
        $categories = Cache::remember('articles.categories', now()->addHours(6), fn () => IslamicArticle::categories());

        return view('public.articles', compact('articles', 'categories'));
    }

    public function show(IslamicArticle $article)
    {
        if (! $article->is_published) {
            abort(404);
        }
        $article->incrementViews();

        $related = IslamicArticle::published()
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($article) {
                $q->where('category', $article->category)
                    ->orWhere('author', $article->author);
            })
            ->limit(3)
            ->get();

        return view('public.article-details', compact('article', 'related'));
    }

    /* ─────────────────────────── ADMIN ──────────────────────────── */

    public function adminIndex()
    {
        $query = IslamicArticle::query()->latest();

        $query = $query->search(request('q'))->category(request('category'));

        if (request('status') === 'published') {
            $query->where('is_published', true);
        } elseif (request('status') === 'draft') {
            $query->where('is_published', false);
        } elseif (request('status') === 'featured') {
            $query->where('is_featured', true);
        } elseif (request('status') === 'trending') {
            $query->where('is_trending', true);
        }

        $articles = $query->paginate(20)->withQueryString();
        $categories = IslamicArticle::categories();

        $stats = Cache::remember('articles.admin_stats', now()->addMinutes(2), function () {
            return IslamicArticle::query()
                ->selectRaw('count(*) as total, COALESCE(sum(is_published = 1), 0) as published, COALESCE(sum(is_featured = 1), 0) as featured, COALESCE(sum(views), 0) as views')
                ->first();
        });

        return view('admin.articles.index', compact('articles', 'categories', 'stats'));
    }

    public function adminCreate()
    {
        $categories = IslamicArticle::categories();

        return view('admin.articles.create', compact('categories'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'cover_image' => 'nullable|image|max:3072',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['content'] = $this->sanitizeArticleContent($validated['content']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['reading_time'] = IslamicArticle::estimateReadingTime($validated['content']);
        $validated['slug'] = IslamicArticle::generateUniqueSlug($validated['title']);

        if ($request->hasFile('cover_image')) {
            $validated['image_path'] = $request->file('cover_image')->store('articles/covers', 'public');
        }
        unset($validated['cover_image']);

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        IslamicArticle::create($validated);

        return redirect()->route('admin.articles.index')->with('status', 'Article created successfully.');
    }

    public function adminEdit(IslamicArticle $article)
    {
        $categories = IslamicArticle::categories();

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function adminUpdate(Request $request, IslamicArticle $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'cover_image' => 'nullable|image|max:3072',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['content'] = $this->sanitizeArticleContent($validated['content']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['reading_time'] = IslamicArticle::estimateReadingTime($validated['content']);

        if ($request->hasFile('cover_image')) {
            if ($article->image_path) {
                Storage::disk('public')->delete($article->image_path);
            }
            $validated['image_path'] = $request->file('cover_image')->store('articles/covers', 'public');
        }
        unset($validated['cover_image']);

        if ($validated['is_published'] && empty($article->published_at) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('status', 'Article updated successfully.');
    }

    public function adminDestroy(Request $request)
    {
        $article = IslamicArticle::findOrFail($request->integer('id'));

        if ($article->image_path) {
            Storage::disk('public')->delete($article->image_path);
        }

        $article->delete();

        return back()->with('status', 'Article deleted successfully.');
    }

    private function sanitizeArticleContent(string $content): string
    {
        $allowedTags = '<p><br><strong><b><em><i><u><s><h2><h3><h4><blockquote><ul><ol><li><a><img><figure><figcaption><hr>';
        $content = strip_tags($content, $allowedTags);

        $content = preg_replace('/\s(on\w+|style|class|id)=("|\').*?\2/i', '', $content);
        $content = preg_replace('/href=("|\')\s*javascript:.*?\1/i', 'href="#"', $content);
        $content = preg_replace('/src=("|\')\s*javascript:.*?\1/i', '', $content);

        return trim($content);
    }
}
