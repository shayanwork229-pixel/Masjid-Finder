<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            ['name' => 'Parking', 'icon' => 'fa-parking'],
            ['name' => 'Library', 'icon' => 'fa-book'],
            ['name' => 'Wuzu Area', 'icon' => 'fa-tint'],
            ['name' => 'Air Conditioning', 'icon' => 'fa-wind'],
            ['name' => 'Wheelchair Access', 'icon' => 'fa-wheelchair'],
            ['name' => 'Madarsa', 'icon' => 'fa-graduation-cap'],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(['name' => $facility['name']], $facility);
        }
    }
}
