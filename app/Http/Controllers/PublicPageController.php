<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\DonationCampaign;
use App\Models\Event;
use App\Models\Facility;
use App\Models\Masjid;
use App\Models\Message;
use App\Models\RamadanTiming;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PublicPageController extends Controller
{
    public function home()
    {
        // ── Featured Masajid (exactly 3, eager-load facilities) ──────────────
        $featured_masjids = Masjid::with('facilities')
            ->where('is_featured', true)
            ->orderBy('name')
            ->take(3)
            ->get();

        // ── All Masajid for map / home stats (eager-load facilities) ──────────
        $featured = Masjid::with('facilities')->orderBy('name')->get();

        $defaultMasjid = $featured->first();
        $nextPrayer    = $defaultMasjid
            ? $this->nextPrayer($defaultMasjid)
            : ['name' => 'Fajr', 'time' => '04:30'];

        $total      = Masjid::count();
        $sunniCount = Masjid::where('sect', 'Sunni')->count();
        $shiaCount  = Masjid::where('sect', 'Shia')->count();

        // ── Active Announcements ──────────────────────────────────────────────
        $announcements = Announcement::query()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now()->toDateString());
            })
            ->orderByDesc('priority')
            ->take(3)
            ->get();

        // ── Ramadan Timings — 30 days ordered ascending ───────────────────────
        $ramadan_days = RamadanTiming::query()
            ->where('year', 2026)
            ->where('city', 'Karachi')
            ->where('is_active', true)
            ->orderBy('day')
            ->get();

        // ── Calendar Events — FullCalendar JSON format ────────────────────────
        $calendar_events = Event::query()
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->get()
            ->map(fn(Event $e) => [
                'id'    => $e->id,
                'title' => $e->title,
                'start' => $e->event_date->format('Y-m-d'),
                'color' => $e->sect === 'Shia' ? '#0d6efd' : '#198754',
                'url'   => route('events.show', $e->id),
            ])
            ->values()
            ->toArray();

        $featuredCampaigns = DonationCampaign::active()
            ->with('masjid')
            ->orderByDesc('is_emergency')
            ->orderByDesc('is_featured')
            ->take(3)
            ->get();

        return view('public.home', compact(
            'featured_masjids',
            'featured',
            'defaultMasjid',
            'nextPrayer',
            'total',
            'sunniCount',
            'shiaCount',
            'announcements',
            'ramadan_days',
            'calendar_events',
            'featuredCampaigns'
        ));
    }

    public function about()
    {
        return view('public.about');
    }

    public function aboutDetails()
    {
        return view('public.about-details');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create($validated);

        return back()->with('status', 'Your message has been received.');
    }

    public function masjids(Request $request)
    {
        $query = Masjid::with(['facilities', 'images'])
            ->search($request->input('search'))
            ->sect($request->input('sect'))
            ->area($request->input('area'))
            ->withFacility($request->input('facility'))
            ->jumaAvailable($request->boolean('juma_available'))
            ->prayerAvailable($request->boolean('prayer_available'));

        $masjids   = $query->orderBy('name')->get();
        $facilities = \Illuminate\Support\Facades\Cache::remember('all_facilities', 86400, fn() => Facility::orderBy('name')->get());

        if ($request->filled('latitude') && $request->filled('longitude') && $request->filled('distance')) {
            $userLat = (float) $request->latitude;
            $userLng = (float) $request->longitude;
            $radius  = (float) $request->distance;
            $masjids = $masjids->filter(function (Masjid $item) use ($userLat, $userLng, $radius) {
                return $this->distanceInKm($userLat, $userLng, (float) $item->latitude, (float) $item->longitude) <= $radius;
            })->values();
        }

        $page     = $request->input('page', 1);
        $perPage  = 12;
        $paginated = new LengthAwarePaginator(
            $masjids->forPage($page, $perPage),
            $masjids->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('public.masjids', compact('masjids', 'facilities', 'paginated'));
    }

    public function showMasjid(Masjid $masjid)
    {
        $masjid->load('facilities', 'images', 'campaigns');
    
        $nearby = Masjid::where('id', '!=', $masjid->id)
            ->get()
            ->map(function ($item) use ($masjid) {
    
                $item->distance = $this->distanceInKm(
                    $masjid->latitude,
                    $masjid->longitude,
                    $item->latitude,
                    $item->longitude
                );
    
                return $item;
    
            })
            ->sortBy('distance')
            ->take(5)
            ->values();
    
        // Nearest Masjid
        $recommendedMasjid = $nearby->first();
    
        $nextPrayer = $this->nextPrayer($masjid);
        // Smart recommendation
$recommendedMasjid = null;

foreach ($nearby as $near) {

    $currentPrayer = strtolower($nextPrayer['name'] ?? '');

    if ($currentPrayer && isset($near->$currentPrayer)) {

        $currentMasjidTime = strtotime($masjid->$currentPrayer);
        $nearMasjidTime    = strtotime($near->$currentPrayer);

        // Agar nearby masjid ki prayer current masjid se 5 minute ya zyada baad hai
        if (($nearMasjidTime - $currentMasjidTime) >= 300) {

            $recommendedMasjid = $near;
            break;

        }

    }

}
    
        return view('public.masjid-details', compact(
            'masjid',
            'nearby',
            'nextPrayer',
            'recommendedMasjid'
        ));
    }

    public function map()
    {
        $masjids = \Illuminate\Support\Facades\Cache::remember('all_masjids_map', 3600, function() {
            return Masjid::select('id', 'name', 'sect', 'address', 'latitude', 'longitude', 'fajr', 'zuhr', 'asr', 'maghrib', 'isha', 'juma_time')->orderBy('name')->get();
        });

        return view('public.map', compact('masjids'));
    }

    public function timings(Request $request)
    {
        $masjids       = \Illuminate\Support\Facades\Cache::remember('all_masjids_timings', 3600, function() {
            return Masjid::select('id', 'name', 'fajr', 'zuhr', 'asr', 'maghrib', 'isha', 'juma_time')->orderBy('name')->get();
        });
        
        $selectedMasjid = $request->filled('masjid_id')
            ? Masjid::find($request->integer('masjid_id'))
            : null;
        $selectedMasjid ??= $masjids->first();
        $nextPrayer     = $selectedMasjid ? $this->nextPrayer($selectedMasjid) : null;

        return view('public.timings', compact('masjids', 'selectedMasjid', 'nextPrayer'));
    }

    public function jumaEid()
    {
        $masjids = \Illuminate\Support\Facades\Cache::remember('all_masjids_juma', 3600, function() {
            return Masjid::with('facilities')->whereNotNull('juma_time')->orderBy('name')->get();
        });

        return view('public.juma-eid', compact('masjids'));
    }

    public function jumaEidDetails(Masjid $masjid)
    {
        return view('public.juma-eid-details', compact('masjid'));
    }

    public function timingsDetails()
    {
        $masjids = Masjid::orderBy('name')->get();

        return view('public.timings-details', compact('masjids'));
    }

    public function pdfTimetable(Request $request)
    {
        $masjid = null;
        $masjids = Masjid::orderBy('name')->get();

        if ($request->filled('masjid_id')) {
            $masjid = Masjid::find($request->integer('masjid_id'));
        }

        return view('public.pdf-timetable', compact('masjid', 'masjids'));
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    private function distanceInKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat        = deg2rad($lat2 - $lat1);
        $dLng        = deg2rad($lng2 - $lng1);
        $a           = sin($dLat / 2) ** 2
                     + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c           = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    private function nextPrayer(Masjid $masjid): array
    {
        $labels = [
            'fajr'    => 'Fajr',
            'zuhr'    => 'Zuhr',
            'asr'     => 'Asr',
            'maghrib' => 'Maghrib',
            'isha'    => 'Isha',
        ];

        $now = now();
        foreach ($labels as $key => $label) {
            if (!$masjid->{$key}) {
                continue;
            }
            [$hour, $minute] = array_pad(explode(':', $masjid->{$key}), 2, 0);
            $time            = now()->setTime((int) $hour, (int) $minute);
            if ($time->greaterThan($now)) {
                return ['key' => $key, 'name' => $label, 'time' => $masjid->{$key}];
            }
        }

        return ['key' => 'fajr', 'name' => 'Fajr', 'time' => $masjid->fajr];
    }
}
