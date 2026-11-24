<?php

namespace App\Http\Controllers;

use App\Models\Masjid;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminPageController extends Controller
{
    public function dashboard()
    {
        $totalMasjids = Masjid::count();
        $totalMessages = Message::count();
        $sunniCount = Masjid::query()->where('sect', 'Sunni')->count();
        $shiaCount = Masjid::query()->where('sect', 'Shia')->count();
        $unreadMessages = Message::query()->where('is_read', false)->count();
        $recentMasjids = Masjid::query()->orderByDesc('created_at')->take(5)->get();
        $recentMessages = Message::query()->orderByDesc('created_at')->take(5)->get();

        $latestEvents = \App\Models\Event::query()->orderByDesc('created_at')->take(5)->get();
        $latestAnnouncements = \App\Models\Announcement::query()->orderByDesc('created_at')->take(5)->get();
        $latestDonations = \App\Models\Donation::query()->orderByDesc('created_at')->take(5)->get();

        return view('admin.dashboard', compact('totalMasjids', 'totalMessages', 'sunniCount', 'shiaCount', 'unreadMessages', 'recentMasjids', 'recentMessages', 'latestEvents', 'latestAnnouncements', 'latestDonations'));
    }

    public function masjids()
    {
        $masjids = Masjid::query()->orderBy('name')->get();

        return view('admin.masjids', compact('masjids'));
    }

    public function addMasjid()
    {
        return view('admin.add-masjid');
    }

    public function storeMasjid(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'area' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'sect' => 'required|in:Sunni,Shia',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phone' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'fajr' => 'nullable|string|max:10',
            'zuhr' => 'nullable|string|max:10',
            'asr' => 'nullable|string|max:10',
            'maghrib' => 'nullable|string|max:10',
            'isha' => 'nullable|string|max:10',
            'juma_time' => 'nullable|string|max:10',
            'eid_time' => 'nullable|string|max:10',
            'is_featured' => 'nullable|boolean',
            'parking' => 'nullable|boolean',
            'wheelchair' => 'nullable|boolean',
            'ladies_prayer' => 'nullable|boolean',
            'construction_status' => 'nullable|string|max:100',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['parking'] = $request->boolean('parking');
        $validated['wheelchair'] = $request->boolean('wheelchair');
        $validated['ladies_prayer'] = $request->boolean('ladies_prayer');
        if (!$request->filled('construction_status')) {
            $validated['construction_status'] = 'Completed';
        }
        Masjid::create($validated);
        
        $this->clearMasjidCaches();

        return redirect()->route('admin.masjids')->with('status', 'Masjid added successfully.');
    }

    public function editMasjid(Masjid $masjid)
    {
        return view('admin.edit-masjid', compact('masjid'));
    }

    public function updateMasjid(Request $request, Masjid $masjid)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'area' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'sect' => 'required|in:Sunni,Shia',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phone' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'fajr' => 'nullable|string|max:10',
            'zuhr' => 'nullable|string|max:10',
            'asr' => 'nullable|string|max:10',
            'maghrib' => 'nullable|string|max:10',
            'isha' => 'nullable|string|max:10',
            'juma_time' => 'nullable|string|max:10',
            'eid_time' => 'nullable|string|max:10',
            'is_featured' => 'nullable|boolean',
            'parking' => 'nullable|boolean',
            'wheelchair' => 'nullable|boolean',
            'ladies_prayer' => 'nullable|boolean',
            'construction_status' => 'nullable|string|max:100',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['parking'] = $request->boolean('parking');
        $validated['wheelchair'] = $request->boolean('wheelchair');
        $validated['ladies_prayer'] = $request->boolean('ladies_prayer');
        if (!$request->filled('construction_status')) {
            $validated['construction_status'] = 'Completed';
        }
        $masjid->update($validated);
        
        $this->clearMasjidCaches($masjid->id);

        return redirect()->route('admin.masjids')->with('status', 'Masjid updated successfully.');
    }

    public function deleteMasjid(Request $request)
    {
        $id = $request->input('id');
        Masjid::findOrFail($id)->delete();
        
        $this->clearMasjidCaches($id);

        return redirect()->route('admin.masjids')->with('status', 'Masjid deleted.');
    }
    
    private function clearMasjidCaches($id = null)
    {
        \Illuminate\Support\Facades\Cache::forget('all_masjids_map');
        \Illuminate\Support\Facades\Cache::forget('all_masjids_timings');
        \Illuminate\Support\Facades\Cache::forget('all_masjids_juma');
        if ($id) {
            \Illuminate\Support\Facades\Cache::forget('masjid_nearby_'.$id);
        }
    }

    public function timings()
    {
        $masjids = Masjid::query()->orderBy('name')->get();
        $selectedMasjid = null;

        if (request()->filled('masjid_id')) {
            $selectedMasjid = Masjid::find(request('masjid_id'));
        }

        if (! $selectedMasjid) {
            $selectedMasjid = $masjids->first();
        }

        return view('admin.timings', compact('masjids', 'selectedMasjid'));
    }

    public function updateTimings(Request $request)
    {
        $validated = $request->validate([
            'masjid_id' => 'required|exists:masjids,id',
            'fajr' => 'required|string|max:10',
            'zuhr' => 'required|string|max:10',
            'asr' => 'required|string|max:10',
            'maghrib' => 'required|string|max:10',
            'isha' => 'required|string|max:10',
            'juma_time' => 'required|string|max:10',
            'eid_time' => 'nullable|string|max:10',
        ]);

        $masjid = Masjid::findOrFail($validated['masjid_id']);
        $masjid->update($request->only(['fajr', 'zuhr', 'asr', 'maghrib', 'isha', 'juma_time', 'eid_time']));

        $this->clearMasjidCaches($masjid->id);

        return redirect()
            ->route('admin.timings', ['masjid_id' => $masjid->id])
            ->with('status', "Prayer timings for {$masjid->name} updated successfully.");
    }

    public function settings()
    {
        $defaults = [
            'site_name' => 'Masjid Locator',
            'site_tagline' => 'Find Nearest Masjid',
            'default_city' => 'Lahore',
            'contact_email' => 'info@masjidlocator.com',
            'contact_phone' => '+92 300 1234567',
            'contact_address' => 'Lahore, Pakistan',
            'default_lat' => '31.5204',
            'default_lng' => '74.3587',
        ];
        $settings = collect($defaults)->merge(Setting::pluck('setting_value', 'setting_key'));

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'default_city' => 'required|string|max:100',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_address' => 'required|string',
            'default_lat' => 'required|numeric',
            'default_lng' => 'required|numeric',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['setting_key' => $key], ['setting_value' => $value]);
        }

        return back()->with('status', 'System settings saved successfully.');
    }
}
