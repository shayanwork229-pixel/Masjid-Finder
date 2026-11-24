<?php

namespace Database\Seeders;

use App\Models\DonationCampaign;
use App\Models\Masjid;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        if (DonationCampaign::count() > 0) {
            return;
        }

        $masjid = Masjid::first();
        if (! $masjid) {
            return;
        }

        DonationCampaign::create([
            'masjid_id' => $masjid->id,
            'title' => 'Ramadan Support Fund',
            'description' => 'Support essential masjid services and provide meals during Ramadan.',
            'target_amount' => 200000,
            'collected_amount' => 85000,
            'start_date' => now()->subDays(15)->toDateString(),
            'end_date' => now()->addDays(15)->toDateString(),
            'status' => 'active',
            'is_featured' => true,
            'is_emergency' => false,
        ]);

        DonationCampaign::create([
            'masjid_id' => $masjid->id,
            'title' => 'Water Project Initiative',
            'description' => 'Help complete safe water access for the masjid and community ablution areas.',
            'target_amount' => 90000,
            'collected_amount' => 32000,
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->addDays(20)->toDateString(),
            'status' => 'active',
            'is_featured' => false,
            'is_emergency' => true,
        ]);
    }
}
