<?php

namespace App\Http\Controllers;

use App\Models\LiveBayan;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LiveBayanController extends Controller
{
    /* ─────────────────────────── PUBLIC ─────────────────────────── */

    public function index(Request $request)
    {
        $query = LiveBayan::with('masjid:id,name')->latest('date');

        // Search
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('scholar_name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Scholar filter
        if ($request->filled('scholar')) {
            $query->where('scholar_name', 'like', '%' . $request->input('scholar') . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Tab filter: featured / trending / latest
        if ($request->input('tab') === 'featured') {
            $query->where('is_featured', true);
        } elseif ($request->input('tab') === 'trending') {
            $query->where('is_trending', true)->orderByDesc('views');
        }

        $bayans   = $query->paginate(12)->withQueryString();
        $categories = LiveBayan::categories();
        $masjids  = Masjid::orderBy('name')->get(['id', 'name']);

        return view('public.live-bayan', compact('bayans', 'categories', 'masjids'));
    }

    public function show(LiveBayan $bayan)
    {
        $bayan->incrementViews();
        $bayan->load('masjid');

        $related = LiveBayan::where('id', '!=', $bayan->id)
            ->where(function ($q) use ($bayan) {
                $q->where('category', $bayan->category)
                  ->orWhere('scholar_name', $bayan->scholar_name);
            })
            ->latest('date')
            ->limit(4)
            ->get();

        return view('public.live-bayan-details', compact('bayan', 'related'));
    }

    /* ─────────────────────────── ADMIN ──────────────────────────── */

    public function adminIndex()
    {
        $bayans = LiveBayan::with('masjid:id,name')->latest()->paginate(20);
        return view('admin.bayans.index', compact('bayans'));
    }

    public function adminCreate()
    {
        $categories = LiveBayan::categories();
        $masjids    = Masjid::orderBy('name')->get(['id', 'name']);
        return view('admin.bayans.create', compact('categories', 'masjids'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'scholar_name'  => 'nullable|string|max:255',
            'masjid_id'     => 'nullable|exists:masjids,id',
            'description'   => 'nullable|string',
            'youtube_url'   => 'nullable|url|max:500',
            'audio_url'     => 'nullable|url|max:500',
            'category'      => 'nullable|string|max:100',
            'duration'      => 'nullable|string|max:20',
            'date'          => 'nullable|date',
            'is_live'       => 'nullable|boolean',
            'is_featured'   => 'nullable|boolean',
            'is_trending'   => 'nullable|boolean',
            'thumbnail'     => 'nullable|image|max:2048',
        ]);

        $validated['is_live']     = $request->boolean('is_live');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['slug']        = LiveBayan::generateUniqueSlug($validated['title']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('bayans/thumbnails', 'public');
        }
        unset($validated['thumbnail']);

        LiveBayan::create($validated);

        return redirect()->route('admin.bayans.index')->with('status', 'Bayan added successfully.');
    }

    public function adminEdit(LiveBayan $bayan)
    {
        $categories = LiveBayan::categories();
        $masjids    = Masjid::orderBy('name')->get(['id', 'name']);
        return view('admin.bayans.edit', compact('bayan', 'categories', 'masjids'));
    }

    public function adminUpdate(Request $request, LiveBayan $bayan)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'scholar_name' => 'nullable|string|max:255',
            'masjid_id'    => 'nullable|exists:masjids,id',
            'description'  => 'nullable|string',
            'youtube_url'  => 'nullable|url|max:500',
            'audio_url'    => 'nullable|url|max:500',
            'category'     => 'nullable|string|max:100',
            'duration'     => 'nullable|string|max:20',
            'date'         => 'nullable|date',
            'is_live'      => 'nullable|boolean',
            'is_featured'  => 'nullable|boolean',
            'is_trending'  => 'nullable|boolean',
            'thumbnail'    => 'nullable|image|max:2048',
        ]);

        $validated['is_live']     = $request->boolean('is_live');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($bayan->thumbnail_path) {
                Storage::disk('public')->delete($bayan->thumbnail_path);
            }
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('bayans/thumbnails', 'public');
        }
        unset($validated['thumbnail']);

        $bayan->update($validated);

        return redirect()->route('admin.bayans.index')->with('status', 'Bayan updated successfully.');
    }

    public function adminDestroy(Request $request)
    {
        $bayan = LiveBayan::findOrFail($request->integer('id'));

        if ($bayan->thumbnail_path) {
            Storage::disk('public')->delete($bayan->thumbnail_path);
        }

        $bayan->delete();

        return back()->with('status', 'Bayan deleted successfully.');
    }
}
