<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationCampaignRequest;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = DonationCampaign::with('masjid')
            ->orderByDesc('is_emergency')
            ->orderByDesc('is_featured')
            ->orderBy('status')
            ->orderByDesc('created_at')
            ->get();

        return view('public.donations.index', compact('campaigns'));
    }

    public function show(DonationCampaign $campaign)
    {
        $campaign->load('masjid');

        return view('public.donations.show', compact('campaign'));
    }

    public function donate(Request $request, DonationCampaign $campaign)
    {
        return redirect()->route('donations.show', $campaign)->with('status', 'Please use the WhatsApp donation button on this page to contact us.');
    }

    public function adminIndex(Request $request)
    {
        $query = DonationCampaign::with('masjid')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->input('status'));
            })
            ->when($request->filled('masjid_id'), function ($q) use ($request) {
                $q->where('masjid_id', $request->input('masjid_id'));
            });

        $campaigns = $query->orderByDesc('is_emergency')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->get();

        $masjids = Masjid::orderBy('name')->get();

        $totalDonations = Donation::count();
        $totalRaised = Donation::sum('amount');
        $totalCampaigns = DonationCampaign::count();
        $activeCampaigns = DonationCampaign::where('status', 'active')->count();
        $closedCampaigns = DonationCampaign::where('status', 'closed')->count();
        $remainingGoal = DonationCampaign::sum('target_amount') - DonationCampaign::sum('collected_amount');

        return view('admin.donations.index', compact(
            'campaigns',
            'masjids',
            'totalDonations',
            'totalRaised',
            'totalCampaigns',
            'activeCampaigns',
            'closedCampaigns',
            'remainingGoal'
        ));
    }

    public function create()
    {
        $masjids = Masjid::orderBy('name')->get();
        return view('admin.donations.create', compact('masjids'));
    }

    public function store(DonationCampaignRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('donations', 'public');
        }

        DonationCampaign::create($data);

        return redirect()->route('admin.donations')->with('status', 'Donation campaign created successfully.');
    }

    public function edit(DonationCampaign $campaign)
    {
        $masjids = Masjid::orderBy('name')->get();
        return view('admin.donations.edit', compact('campaign', 'masjids'));
    }

    public function update(DonationCampaignRequest $request, DonationCampaign $campaign)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if (!empty($campaign->image_path)) {
                Storage::disk('public')->delete($campaign->image_path);
            }
            $data['image_path'] = $request->file('image')->store('donations', 'public');
        }

        $campaign->update($data);

        return redirect()->route('admin.donations')->with('status', 'Donation campaign updated successfully.');
    }

    public function destroy(Request $request, DonationCampaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('admin.donations')->with('status', 'Donation campaign deleted successfully.');
    }

    public function status(Request $request, DonationCampaign $campaign)
    {
        $request->validate([ 'status' => 'required|in:active,paused,closed' ]);

        $campaign->update(['status' => $request->input('status')]);

        return redirect()->route('admin.donations')->with('status', 'Campaign status updated.');
    }

    public function history(Request $request, DonationCampaign $campaign)
    {
        $query = $campaign->donations()->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $donations = $query->get();
        $categories = config('payments.categories');

        return view('admin.donations.history', compact('campaign', 'donations', 'categories'));
    }
}
