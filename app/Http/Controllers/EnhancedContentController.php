<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Facility;
use App\Models\LiveBayan;
use App\Models\Masjid;
use App\Models\MasjidImage;
use App\Models\QuranClass;
use App\Models\RamadanTiming;
use Illuminate\Http\Request;

class EnhancedContentController extends Controller
{
    public function qibla()
    {
        return view('public.qibla');
    }

    public function announcements()
    {
        $announcements = Announcement::query()->where('is_active', true)->orderByDesc('created_at')->paginate(10);

        return view('public.announcements', compact('announcements'));
    }

    public function quranClasses()
    {
        $classes = QuranClass::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('public.quran-classes', compact('classes'));
    }

    public function liveBayan()
    {
        $bayans = LiveBayan::query()
            ->orderByDesc('is_live')
            ->orderByDesc('date')
            ->get();

        return view('public.live-bayan', compact('bayans'));
    }

    public function gallery()
    {
        $images = MasjidImage::query()
            ->with('masjid:id,name')
            ->orderBy('sort_order')
            ->get();

        return view('public.gallery', compact('images'));
    }

    public function ramadan(Request $request)
    {
        $year = $request->input('year', now()->year);
        $timings = RamadanTiming::query()->where('year', $year)->where('city', 'Karachi')->orderBy('day')->get();
        $latest = $timings->first();

        return view('public.ramadan', compact('timings', 'year', 'latest'));
    }

    public function hijriCalendar()
    {
        $today = now();
        $events = Event::query()->whereDate('event_date', '>=', $today->toDateString())->orderBy('event_date')->take(6)->get();

        return view('public.hijri-calendar', compact('today', 'events'));
    }

    public function eventsIndex()
    {
        $events = Event::query()->whereDate('event_date', '>=', now()->toDateString())->orderBy('event_date')->paginate(8);

        return view('public.events', compact('events'));
    }

    public function eventDetails(Event $event)
    {
        return view('public.event-details', compact('event'));
    }

    public function adminRamadan()
    {
        $timings = RamadanTiming::query()->orderBy('year')->orderBy('day')->get();

        return view('admin.ramadan', compact('timings'));
    }

    public function storeRamadan(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'year' => 'required|integer',
            'day' => 'required|integer',
            'sehri_time' => 'nullable|string|max:10',
            'iftar_time' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        RamadanTiming::create($validated);

        return back()->with('status', 'Ramadan timetable entry added.');
    }

    public function adminEvents()
    {
        $events = Event::query()->orderByDesc('event_date')->get();

        return view('admin.events', compact('events'));
    }

    public function createEvent()
    {
        $masjids = Masjid::query()->orderBy('name')->get();

        return view('admin.add-event', compact('masjids'));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|string|max:10',
            'category' => 'nullable|string|max:100',
            'sect' => 'nullable|in:Sunni,Shia',
            'masjid_id' => 'nullable|exists:masjids,id',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        Event::create($validated);

        return redirect()->route('admin.events')->with('status', 'Event added successfully.');
    }

    public function editEvent(Event $event)
    {
        $masjids = Masjid::query()->orderBy('name')->get();

        return view('admin.edit-event', compact('event', 'masjids'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|string|max:10',
            'category' => 'nullable|string|max:100',
            'sect' => 'nullable|in:Sunni,Shia',
            'masjid_id' => 'nullable|exists:masjids,id',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $event->update($validated);

        return redirect()->route('admin.events')->with('status', 'Event updated successfully.');
    }

    public function deleteEvent(Request $request)
    {
        Event::findOrFail($request->integer('id'))->delete();

        return back()->with('status', 'Event deleted successfully.');
    }

    public function adminGallery(Masjid $masjid)
    {
        $masjid->load('images');

        return view('admin.gallery', compact('masjid'));
    }

    public function uploadGallery(Request $request, Masjid $masjid)
    {
        $request->validate(['images.*' => 'required|image|max:2048']);

        foreach ($request->file('images', []) as $file) {
            $path = $file->store('gallery', 'public');
            $masjid->images()->create([
                'image_path' => $path,
                'caption' => $request->input('caption', ''),
                'sort_order' => $masjid->images()->count(),
            ]);
        }

        return back()->with('status', 'Images uploaded successfully.');
    }

    public function deleteGalleryImage(MasjidImage $image)
    {
        $image->delete();

        return back()->with('status', 'Image removed successfully.');
    }

    public function reorderGallery(Request $request)
    {
        foreach ($request->input('order', []) as $index => $imageId) {
            MasjidImage::whereKey($imageId)->update(['sort_order' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function adminFacilities()
    {
        $facilities = Facility::query()->orderBy('name')->get();
        $masjids = Masjid::query()->orderBy('name')->get();

        return view('admin.facilities', compact('facilities', 'masjids'));
    }

    public function storeFacility(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
        ]);

        Facility::create($validated);

        return back()->with('status', 'Facility added successfully.');
    }

    public function attachFacility(Request $request)
    {
        $validated = $request->validate([
            'masjid_id' => 'required|exists:masjids,id',
            'facility_id' => 'required|exists:facilities,id',
        ]);

        $masjid = Masjid::findOrFail($validated['masjid_id']);
        $masjid->facilities()->syncWithoutDetaching([$validated['facility_id']]);

        return back()->with('status', 'Facility attached to masjid.');
    }

    public function adminAnnouncements()
    {
        $announcements = Announcement::query()->orderByDesc('created_at')->get();

        return view('admin.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'nullable|in:low,normal,high',
            'expiry_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        Announcement::create($validated);

        return redirect()->route('admin.announcements')->with('status', 'Announcement created successfully.');
    }
}
