<?php

namespace Database\Seeders;

use App\Models\LiveBayan;
use App\Models\Masjid;
use Illuminate\Database\Seeder;

class LiveBayanSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing live bayans
        LiveBayan::truncate();

        $masjids = Masjid::all();
        $masjid1 = $masjids->first();
        $masjid2 = $masjids->skip(1)->first();
        $masjid3 = $masjids->skip(2)->first();

        $bayans = [
            [
                'title' => 'Tafseer of Surah Al-Fatiha',
                'scholar_name' => 'Dr. Yasir Qadhi',
                'masjid_id' => $masjid1?->id,
                'description' => 'A comprehensive explanation of Surah Al-Fatiha, covering its virtues, naming, and deep theological lessons.',
                'youtube_url' => 'https://www.youtube.com/watch?v=kQD26aW5uE8',
                'audio_url' => 'https://www.soundofislam.org/audio/fatiha.mp3',
                'category' => 'Quran',
                'duration' => '45:12',
                'views' => 1250,
                'date' => '2026-06-01',
                'is_live' => false,
                'is_featured' => true,
                'is_trending' => false,
            ],
            [
                'title' => 'Understanding the Prophetic Hadith on Intentions',
                'scholar_name' => 'Mufti Menk',
                'masjid_id' => $masjid2?->id,
                'description' => 'Mufti Menk explains the first Hadith of Sahih Al-Bukhari: "Actions are but by intentions". How to purify your intentions in daily actions.',
                'youtube_url' => 'https://www.youtube.com/watch?v=ap_a5vUo0O8',
                'category' => 'Hadith',
                'duration' => '32:45',
                'views' => 3420,
                'date' => '2026-06-15',
                'is_live' => false,
                'is_featured' => false,
                'is_trending' => true,
            ],
            [
                'title' => 'Lessons from the Seerah: The Early Years',
                'scholar_name' => 'Shaykh Hamza Yusuf',
                'masjid_id' => $masjid3?->id,
                'description' => 'An inspiring look at the early life of Prophet Muhammad (peace be upon him) before revelation, focusing on his character and integrity.',
                'youtube_url' => 'https://www.youtube.com/watch?v=wX-yD80d2YI',
                'category' => 'Seerat',
                'duration' => '58:20',
                'views' => 2100,
                'date' => '2026-07-01',
                'is_live' => false,
                'is_featured' => true,
                'is_trending' => true,
            ],
            [
                'title' => 'How to Focus in Salah (Khushu)',
                'scholar_name' => 'Ustadh Nouman Ali Khan',
                'masjid_id' => $masjid1?->id,
                'description' => 'Practical tips and psychological insights on achieving mindfulness and focus (Khushu) in our daily prayers.',
                'youtube_url' => 'https://www.youtube.com/watch?v=Vl0cWJ2e4x0',
                'category' => 'Prayer',
                'duration' => '28:10',
                'views' => 5400,
                'date' => '2026-07-10',
                'is_live' => false,
                'is_featured' => false,
                'is_trending' => true,
            ],
            [
                'title' => 'Nurturing an Islamic Family Environment',
                'scholar_name' => 'Shaykh Bilal Philips',
                'masjid_id' => $masjid2?->id,
                'description' => 'Guidelines on building a harmonious home on Islamic principles, addressing parent-child relationships and mutual respect.',
                'youtube_url' => 'https://www.youtube.com/watch?v=P21iNn3sN4U',
                'category' => 'Family',
                'duration' => '51:15',
                'views' => 980,
                'date' => '2026-07-15',
                'is_live' => false,
                'is_featured' => false,
                'is_trending' => false,
            ],
            [
                'title' => 'Challenges Facing Muslim Youth Today',
                'scholar_name' => 'Dr. Yasir Qadhi',
                'masjid_id' => $masjid3?->id,
                'description' => 'An engaging address to the younger generation of Muslims, tackling issues of identity, faith, and peer pressure in a modern world.',
                'youtube_url' => 'https://www.youtube.com/watch?v=d_kS3m6S1qI',
                'category' => 'Youth',
                'duration' => '42:30',
                'views' => 1890,
                'date' => '2026-07-18',
                'is_live' => false,
                'is_featured' => true,
                'is_trending' => false,
            ],
            [
                'title' => 'Live Jumu\'ah Khutbah: Preparing for Ramadan',
                'scholar_name' => 'Mufti Menk',
                'masjid_id' => $masjid1?->id,
                'description' => 'Live broadcast of the weekly Friday sermon detailing the spiritual preparations needed to make the most of the upcoming holy month.',
                'youtube_url' => 'https://www.youtube.com/watch?v=x7K4fD1Gq8E',
                'category' => 'Ramadan',
                'duration' => '35:00',
                'views' => 6100,
                'date' => '2026-07-21',
                'is_live' => true,
                'is_featured' => false,
                'is_trending' => false,
            ],
            [
                'title' => 'The Significance of Muharram & Ashura',
                'scholar_name' => 'Shaykh Hamza Yusuf',
                'masjid_id' => $masjid2?->id,
                'description' => 'A historical and spiritual perspective on the sacred month of Muharram and the lessons of sacrifice from the day of Ashura.',
                'youtube_url' => 'https://www.youtube.com/watch?v=7hR92pT8h8I',
                'category' => 'Muharram',
                'duration' => '49:40',
                'views' => 1340,
                'date' => '2026-07-19',
                'is_live' => false,
                'is_featured' => false,
                'is_trending' => false,
            ]
        ];

        foreach ($bayans as $bayan) {
            LiveBayan::create($bayan);
        }
    }
}
