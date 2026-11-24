<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::query()->whereDate('event_date', '>=', now()->toDateString())->orderBy('event_date')->paginate(8);

        return view('public.events', compact('events'));
    }

    public function show(Event $event)
    {
        return view('public.event-details', compact('event'));
    }

    public function adminEvents()
    {
        $events = Event::query()->orderByDesc('event_date')->get();

        return view('admin.events', compact('events'));
    }

    public function createEvent()
    {
        $masjids = \App\Models\Masjid::query()->orderBy('name')->get();

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

        if (!empty($validated['masjid_id']) && !empty($validated['sect'])) {
            $masjid = \App\Models\Masjid::find($validated['masjid_id']);
            if ($masjid && $masjid->sect !== $validated['sect']) {
                return back()->withErrors(['sect' => 'Event sect must match the assigned Masjid sect ('.$masjid->sect.').'])->withInput();
            }
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        Event::create($validated);

        return redirect()->route('admin.events')->with('status', 'Event added successfully.');
    }

    public function editEvent(Event $event)
    {
        $masjids = \App\Models\Masjid::query()->orderBy('name')->get();

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

        if (!empty($validated['masjid_id']) && !empty($validated['sect'])) {
            $masjid = \App\Models\Masjid::find($validated['masjid_id']);
            if ($masjid && $masjid->sect !== $validated['sect']) {
                return back()->withErrors(['sect' => 'Event sect must match the assigned Masjid sect ('.$masjid->sect.').'])->withInput();
            }
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $event->update($validated);

        return redirect()->route('admin.events')->with('status', 'Event updated successfully.');
    }

    public function deleteEvent(Request $request)
    {
        Event::findOrFail($request->integer('id'))->delete();

        return back()->with('status', 'Event deleted successfully.');
    }
}
