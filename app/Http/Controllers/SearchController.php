<?php

namespace App\Http\Controllers;

use App\Models\Masjid;
use App\Models\Event;
use App\Models\Announcement;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return view('public.search', ['masjids' => collect(), 'events' => collect(), 'announcements' => collect(), 'query' => $query]);
        }

        $masjids = Masjid::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('area', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->get();

        $events = Event::query()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->get();

        $announcements = Announcement::query()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->get();

        return view('public.search', compact('masjids', 'events', 'announcements', 'query'));
    }
}
