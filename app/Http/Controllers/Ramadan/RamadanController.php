<?php

namespace App\Http\Controllers\Ramadan;

use App\Http\Controllers\Controller;
use App\Models\RamadanTiming;
use Illuminate\Http\Request;

class RamadanController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $timings = RamadanTiming::query()->where('year', $year)->where('city', 'Karachi')->orderBy('day')->get();
        $latest = $timings->first();

        return view('public.ramadan', compact('timings', 'year', 'latest'));
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
}
