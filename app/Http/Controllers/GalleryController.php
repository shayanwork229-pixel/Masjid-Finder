<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /* ─────────────────────────── PUBLIC ─────────────────────────── */

    public function index(Request $request)
    {
        $query = GalleryImage::with('masjid:id,name')->active();

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->input('tab') === 'featured') {
            $query->where('is_featured', true);
        } elseif ($request->input('tab') === 'trending') {
            $query->where('is_trending', true)->orderByDesc('views');
        } else {
            $query->latest();
        }

        $images     = $query->paginate(16)->withQueryString();
        $categories = GalleryImage::categories();
        $featured   = GalleryImage::active()->where('is_featured', true)->limit(6)->get();

        return view('public.gallery', compact('images', 'categories', 'featured'));
    }

    public function show(GalleryImage $image)
    {
        $image->incrementViews();
        $image->load('masjid');

        $related = GalleryImage::active()
            ->where('id', '!=', $image->id)
            ->where(function ($q) use ($image) {
                $q->where('category', $image->category)
                  ->orWhere('city', $image->city);
            })
            ->limit(8)
            ->get();

        return view('public.gallery-details', compact('image', 'related'));
    }

    public function like(GalleryImage $image)
    {
        $image->incrementLikes();
        return response()->json(['likes' => $image->fresh()->likes]);
    }

    /* ─────────────────────────── ADMIN ──────────────────────────── */

    public function adminIndex()
    {
        $images = GalleryImage::with('masjid:id,name')->latest()->paginate(24);
        return view('admin.gallery.index', compact('images'));
    }

    public function adminCreate()
    {
        $categories = GalleryImage::categories();
        $masjids    = Masjid::orderBy('name')->get(['id', 'name']);
        return view('admin.gallery.create', compact('categories', 'masjids'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'image'       => 'required|image|max:4096',
            'masjid_id'   => 'nullable|exists:masjids,id',
            'city'        => 'nullable|string|max:100',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'status'      => 'nullable|in:active,inactive',
            'uploaded_by' => 'nullable|string|max:100',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['status']      = $request->input('status', 'active');
        $validated['image_path']  = $request->file('image')->store('gallery', 'public');
        unset($validated['image']);

        GalleryImage::create($validated);

        return redirect()->route('admin.gallery.index')->with('status', 'Image uploaded successfully.');
    }

    public function adminEdit(GalleryImage $image)
    {
        $categories = GalleryImage::categories();
        $masjids    = Masjid::orderBy('name')->get(['id', 'name']);
        return view('admin.gallery.edit', compact('image', 'categories', 'masjids'));
    }

    public function adminUpdate(Request $request, GalleryImage $image)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'image'       => 'nullable|image|max:4096',
            'masjid_id'   => 'nullable|exists:masjids,id',
            'city'        => 'nullable|string|max:100',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'status'      => 'nullable|in:active,inactive',
            'uploaded_by' => 'nullable|string|max:100',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['status']      = $request->input('status', 'active');

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($image->image_path);
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        }
        unset($validated['image']);

        $image->update($validated);

        return redirect()->route('admin.gallery.index')->with('status', 'Image updated successfully.');
    }

    public function adminDestroy(Request $request)
    {
        $image = GalleryImage::findOrFail($request->integer('id'));
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('status', 'Image deleted successfully.');
    }
}
