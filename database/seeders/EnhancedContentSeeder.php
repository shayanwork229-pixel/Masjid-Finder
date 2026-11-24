<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Facility;
use App\Models\Masjid;
use App\Models\RamadanTiming;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnhancedContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // STEP 1 — Truncate (disable FK checks first)
        // ─────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('facility_masjid')->truncate();
        DB::table('masjid_images')->truncate();
        DB::table('ramadan_timings')->truncate();
        DB::table('events')->truncate();
        DB::table('announcements')->truncate();
        DB::table('facilities')->truncate();
        DB::table('masjids')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('All tables truncated. Starting seeding...');

        // ─────────────────────────────────────────────
        // STEP 2 — Facilities
        // ─────────────────────────────────────────────
        $facilityDefs = [
            ['name' => 'Parking',        'icon' => 'fa-parking'],
            ['name' => 'Library',         'icon' => 'fa-book'],
            ['name' => 'Air Conditioning','icon' => 'fa-wind'],
            ['name' => 'Women Section',   'icon' => 'fa-female'],
            ['name' => 'Wuzu Area',       'icon' => 'fa-tint'],
            ['name' => 'Wheelchair Access','icon' => 'fa-wheelchair'],
            ['name' => 'Madarsa',         'icon' => 'fa-graduation-cap'],
        ];

        $facilityIds = [];
        foreach ($facilityDefs as $def) {
            $f = Facility::create($def);
            $facilityIds[$def['name']] = $f->id;
        }

        $this->command->info('Facilities seeded: ' . count($facilityIds));

        // ─────────────────────────────────────────────
        // STEP 3 — Masajid (10 records, 4 featured)
        // ─────────────────────────────────────────────
        $masjidsData = [
            [
                'name'        => 'Masjid-e-Tooba (Gol Masjid)',
                'address'     => 'Defence Housing Authority, Phase 2, Karachi',
                'area'        => 'DHA Phase 2',
                'city'        => 'Karachi',
                'sect'        => 'Sunni',
                'latitude'    => 24.80270000,
                'longitude'   => 67.03440000,
                'fajr'        => '05:15',
                'zuhr'        => '13:00',
                'asr'         => '16:30',
                'maghrib'     => '18:55',
                'isha'        => '20:15',
                'juma_time'   => '13:15',
                'eid_time'    => '08:00',
                'phone'       => '+92-21-35388901',
                'description' => 'One of the largest single-domed mosques in the world, located in the heart of DHA Karachi. A stunning piece of Islamic architecture.',
                'is_featured' => true,
                'facilities'  => ['Parking', 'Air Conditioning', 'Women Section', 'Wuzu Area', 'Wheelchair Access'],
            ],
            [
                'name'        => 'Masjid-e-Aqsa',
                'address'     => 'Gulshan-e-Iqbal Block 13, Karachi',
                'area'        => 'Gulshan-e-Iqbal',
                'city'        => 'Karachi',
                'sect'        => 'Sunni',
                'latitude'    => 24.92060000,
                'longitude'   => 67.11310000,
                'fajr'        => '05:10',
                'zuhr'        => '12:55',
                'asr'         => '16:25',
                'maghrib'     => '18:52',
                'isha'        => '20:10',
                'juma_time'   => '13:10',
                'eid_time'    => '08:15',
                'phone'       => '+92-21-34822110',
                'description' => 'A prominent mosque in the Gulshan area, well-known for its Hifz classes and Islamic education programs.',
                'is_featured' => true,
                'facilities'  => ['Library', 'Wuzu Area', 'Air Conditioning', 'Madarsa'],
            ],
            [
                'name'        => 'Jamia Masjid Baitul Mukarram',
                'address'     => 'North Nazimabad Block L, Karachi',
                'area'        => 'North Nazimabad',
                'city'        => 'Karachi',
                'sect'        => 'Sunni',
                'latitude'    => 24.94720000,
                'longitude'   => 67.06490000,
                'fajr'        => '05:20',
                'zuhr'        => '13:05',
                'asr'         => '16:35',
                'maghrib'     => '18:58',
                'isha'        => '20:20',
                'juma_time'   => '13:20',
                'eid_time'    => '08:30',
                'phone'       => '+92-21-36601234',
                'description' => 'A large community mosque serving the North Nazimabad area, with extensive Tarawih arrangements during Ramadan.',
                'is_featured' => true,
                'facilities'  => ['Parking', 'Women Section', 'Wuzu Area'],
            ],
            [
                'name'        => 'Masjid Ali',
                'address'     => 'Orangi Town Sector 11-C, Karachi',
                'area'        => 'Orangi Town',
                'city'        => 'Karachi',
                'sect'        => 'Shia',
                'latitude'    => 24.97510000,
                'longitude'   => 66.97820000,
                'fajr'        => '05:25',
                'zuhr'        => '13:10',
                'asr'         => '16:40',
                'maghrib'     => '19:00',
                'isha'        => '20:25',
                'juma_time'   => '13:30',
                'eid_time'    => null,
                'phone'       => '+92-21-32561890',
                'description' => 'A well-established Shia mosque in Orangi Town, serving its community with daily prayers and religious gatherings.',
                'is_featured' => true,
                'facilities'  => ['Parking', 'Wuzu Area', 'Air Conditioning'],
            ],
            [
                'name'        => 'Masjid Quba',
                'address'     => 'Clifton Block 5, Near Sea View, Karachi',
                'area'        => 'Clifton',
                'city'        => 'Karachi',
                'sect'        => 'Sunni',
                'latitude'    => 24.81980000,
                'longitude'   => 67.01550000,
                'fajr'        => '05:12',
                'zuhr'        => '12:58',
                'asr'         => '16:28',
                'maghrib'     => '18:53',
                'isha'        => '20:12',
                'juma_time'   => '13:05',
                'eid_time'    => '08:00',
                'phone'       => '+92-21-35871234',
                'description' => 'Located near the scenic Clifton beachfront, Masjid Quba is a modern facility with full amenities for all worshippers.',
                'is_featured' => false,
                'facilities'  => ['Parking', 'Air Conditioning', 'Women Section', 'Wheelchair Access'],
            ],
            [
                'name'        => 'Jamia Masjid Darul Uloom',
                'address'     => 'Korangi Industrial Area, Karachi',
                'area'        => 'Korangi',
                'city'        => 'Karachi',
                'sect'        => 'Sunni',
                'latitude'    => 24.84420000,
                'longitude'   => 67.11880000,
                'fajr'        => '05:18',
                'zuhr'        => '13:02',
                'asr'         => '16:32',
                'maghrib'     => '18:56',
                'isha'        => '20:16',
                'juma_time'   => '13:15',
                'eid_time'    => '08:15',
                'phone'       => '+92-21-35120000',
                'description' => 'An important madrasa-linked mosque providing religious education and daily Namaz facilities in the Korangi area.',
                'is_featured' => false,
                'facilities'  => ['Library', 'Madarsa', 'Wuzu Area'],
            ],
            [
                'name'        => 'Masjid Ibrahim',
                'address'     => 'PECHS Block 6, Karachi',
                'area'        => 'PECHS',
                'city'        => 'Karachi',
                'sect'        => 'Sunni',
                'latitude'    => 24.87540000,
                'longitude'   => 67.07220000,
                'fajr'        => '05:16',
                'zuhr'        => '13:00',
                'asr'         => '16:30',
                'maghrib'     => '18:54',
                'isha'        => '20:14',
                'juma_time'   => '13:10',
                'eid_time'    => '08:30',
                'phone'       => '+92-21-34553421',
                'description' => 'A neighbourhood mosque in PECHS, known for its active youth programs and online live-streaming of Friday sermons.',
                'is_featured' => false,
                'facilities'  => ['Air Conditioning', 'Women Section', 'Wuzu Area'],
            ],
            [
                'name'        => 'Imambargah Mehfil Shah-e-Khorasan',
                'address'     => 'Liaquatabad Town, Karachi',
                'area'        => 'Liaquatabad',
                'city'        => 'Karachi',
                'sect'        => 'Shia',
                'latitude'    => 24.93320000,
                'longitude'   => 67.07610000,
                'fajr'        => '05:22',
                'zuhr'        => '13:08',
                'asr'         => '16:38',
                'maghrib'     => '18:59',
                'isha'        => '20:22',
                'juma_time'   => null,
                'eid_time'    => null,
                'phone'       => '+92-21-34901122',
                'description' => 'A central Shia place of worship in Liaquatabad, hosting major Muharram gatherings and Majalis throughout the year.',
                'is_featured' => false,
                'facilities'  => ['Parking', 'Women Section'],
            ],
            [
                'name'        => 'Masjid-e-Bilal',
                'address'     => 'Landhi Industrial Zone, Karachi',
                'area'        => 'Landhi',
                'city'        => 'Karachi',
                'sect'        => 'Sunni',
                'latitude'    => 24.85910000,
                'longitude'   => 67.17340000,
                'fajr'        => '05:19',
                'zuhr'        => '13:03',
                'asr'         => '16:33',
                'maghrib'     => '18:57',
                'isha'        => '20:17',
                'juma_time'   => '13:15',
                'eid_time'    => '08:00',
                'phone'       => '+92-21-35092233',
                'description' => 'Serving the workers and residents of the Landhi industrial zone, this mosque has an active welfare committee.',
                'is_featured' => false,
                'facilities'  => ['Wuzu Area', 'Parking'],
            ],
            [
                'name'        => 'Masjid Al-Noor',
                'address'     => 'Saddar Town, M.A. Jinnah Road, Karachi',
                'area'        => 'Saddar',
                'city'        => 'Karachi',
                'sect'        => 'Sunni',
                'latitude'    => 24.86080000,
                'longitude'   => 67.01050000,
                'fajr'        => '05:14',
                'zuhr'        => '12:59',
                'asr'         => '16:29',
                'maghrib'     => '18:53',
                'isha'        => '20:13',
                'juma_time'   => '13:00',
                'eid_time'    => '08:00',
                'phone'       => '+92-21-32213344',
                'description' => 'Located in the historic heart of Karachi near M.A. Jinnah Road, Masjid Al-Noor is a landmark congregation site.',
                'is_featured' => false,
                'facilities'  => ['Air Conditioning', 'Wuzu Area', 'Wheelchair Access'],
            ],
        ];

        foreach ($masjidsData as $data) {
            $facilitiesForMasjid = $data['facilities'];
            unset($data['facilities']);

            $masjid = Masjid::create($data);

            // Sync facilities via pivot
            $ids = collect($facilitiesForMasjid)
                ->map(fn($name) => $facilityIds[$name] ?? null)
                ->filter()
                ->values()
                ->toArray();

            $masjid->facilities()->sync($ids);
        }

        $this->command->info('10 Masajid seeded with facility pivot sync.');

        // ─────────────────────────────────────────────
        // STEP 4 — 30-Day Ramadan Timings (2026)
        // ─────────────────────────────────────────────
        // Base times: Sehri at 04:45, Iftar at 18:48
        // Shift: Sehri +1 min every 3 days (earlier in season), Iftar +1 min every 3 days
        $baseSehriH  = 4;
        $baseSehriM  = 45;
        $baseIftarH  = 18;
        $baseIftarM  = 48;

        for ($day = 1; $day <= 30; $day++) {
            // Every 3 days the sehri gets 1 minute earlier, iftar gets 1 minute later
            $sehriOffset = (int) floor(($day - 1) / 3);
            $iftarOffset = (int) floor(($day - 1) / 3);

            $sehriTotal = ($baseSehriH * 60 + $baseSehriM) - $sehriOffset;
            $iftarTotal = ($baseIftarH * 60 + $baseIftarM) + $iftarOffset;

            $sehriTime = sprintf('%02d:%02d', intdiv($sehriTotal, 60), $sehriTotal % 60);
            $iftarTime = sprintf('%02d:%02d', intdiv($iftarTotal, 60), $iftarTotal % 60);

            RamadanTiming::create([
                'city'        => 'Karachi',
                'year'        => 2026,
                'day'         => $day,
                'sehri_time'  => $sehriTime,
                'iftar_time'  => $iftarTime,
                'notes'       => $day === 1 ? 'First day of Ramadan 2026' : ($day === 15 ? 'Mid-Ramadan Mubarak!' : ($day === 29 ? 'Chand Raat soon!' : ($day === 30 ? 'Last day of Ramadan 2026' : null))),
                'is_active'   => true,
            ]);
        }

        $this->command->info('30 Ramadan timing records seeded for 2026.');

        // ─────────────────────────────────────────────
        // STEP 5 — Community Events (4 future)
        // ─────────────────────────────────────────────
        $masjidIds  = Masjid::pluck('id')->toArray();
        $eventsData = [
            [
                'title'       => 'Annual Quran Recitation Competition',
                'description' => 'A city-wide Quran recitation competition open to all age groups. Categories include Hifz, Tajweed, and Translation. Prizes for top 3 in each category.',
                'event_date'  => now()->addDays(10)->format('Y-m-d'),
                'event_time'  => '10:00',
                'category'    => 'Education',
                'sect'        => 'Sunni',
                'masjid_id'   => $masjidIds[0] ?? null,
                'is_featured' => true,
            ],
            [
                'title'       => 'Islamic Finance & Halal Investment Seminar',
                'description' => 'Learn the principles of Islamic finance, interest-free banking, and halal investment strategies from leading scholars and financial experts.',
                'event_date'  => now()->addDays(18)->format('Y-m-d'),
                'event_time'  => '14:00',
                'category'    => 'Seminar',
                'sect'        => 'Sunni',
                'masjid_id'   => $masjidIds[1] ?? null,
                'is_featured' => false,
            ],
            [
                'title'       => 'Muharram Majlis & Procession',
                'description' => 'Annual Muharram gathering with Majalis, Noha recitations, and a peaceful procession. All are welcome to pay their respects.',
                'event_date'  => now()->addDays(25)->format('Y-m-d'),
                'event_time'  => '18:00',
                'category'    => 'Religious',
                'sect'        => 'Shia',
                'masjid_id'   => $masjidIds[3] ?? null,
                'is_featured' => true,
            ],
            [
                'title'       => 'Eid ul-Adha Community Iftar & Charity Drive',
                'description' => 'Join us for a community-wide charity drive and open Iftar dinner. Donate for Qurbani meat distribution to underprivileged families across Karachi.',
                'event_date'  => now()->addDays(38)->format('Y-m-d'),
                'event_time'  => '18:30',
                'category'    => 'Community',
                'sect'        => 'Sunni',
                'masjid_id'   => $masjidIds[2] ?? null,
                'is_featured' => true,
            ],
        ];

        foreach ($eventsData as $event) {
            Event::create($event);
        }

        $this->command->info('4 community events seeded.');

        // ─────────────────────────────────────────────
        // STEP 6 — Announcements
        // ─────────────────────────────────────────────
        Announcement::create([
            'title'       => 'Ramadan 2026 Prayer Timetable Available',
            'content'     => 'The complete Sehri and Iftar timetable for Ramadan 2026 is now available. Download or view the full 30-day schedule from the Ramadan section.',
            'priority'    => 'high',
            'expiry_date' => now()->addDays(60)->format('Y-m-d'),
            'is_active'   => true,
        ]);

        Announcement::create([
            'title'       => 'New Masajid Added to Directory',
            'content'     => 'We have added 10 new masajid to the Masjid Locator directory. Find your nearest mosque and get accurate prayer timings instantly.',
            'priority'    => 'normal',
            'expiry_date' => now()->addDays(90)->format('Y-m-d'),
            'is_active'   => true,
        ]);

        $this->command->info('✅ EnhancedContentSeeder completed successfully!');
    }
}
