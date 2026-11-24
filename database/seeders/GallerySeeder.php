<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use App\Models\Masjid;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        GalleryImage::truncate();

        $masjids = Masjid::all();
        $masjid1 = $masjids->first();
        $masjid2 = $masjids->skip(1)->first();
        $masjid3 = $masjids->skip(2)->first();

        $images = [
            [
                'title' => 'Beautiful Sunrise at the Grand Mosque',
                'masjid_id' => $masjid1?->id,
                'city' => 'Mecca',
                'category' => 'Architecture',
                'description' => 'A breathtaking view of the Grand Mosque at sunrise, capturing the peaceful ambiance and stunning architecture.',
                'image_path' => 'assets/img/gallery/sample1.jpg',
                'is_featured' => true,
                'is_trending' => false,
                'status' => 'active',
                'views' => 15420,
                'likes' => 3400,
                'uploaded_by' => 'Ahmed Photography',
            ],
            [
                'title' => 'Jumu\'ah Prayers Congregation',
                'masjid_id' => $masjid2?->id,
                'city' => 'Medina',
                'category' => 'Events',
                'description' => 'Thousands of worshippers gathered for the Friday congregational prayer, showcasing the unity of the Ummah.',
                'image_path' => 'assets/img/gallery/sample2.jpg',
                'is_featured' => false,
                'is_trending' => true,
                'status' => 'active',
                'views' => 8950,
                'likes' => 1250,
                'uploaded_by' => 'Islamic Media',
            ],
            [
                'title' => 'Intricate Islamic Calligraphy',
                'masjid_id' => $masjid3?->id,
                'city' => 'Istanbul',
                'category' => 'Calligraphy',
                'description' => 'Close-up detail of the magnificent Arabic calligraphy adorning the dome interior.',
                'image_path' => 'assets/img/gallery/sample3.jpg',
                'is_featured' => true,
                'is_trending' => true,
                'status' => 'active',
                'views' => 12050,
                'likes' => 4500,
                'uploaded_by' => 'Art Heritage',
            ],
            [
                'title' => 'Ramadan Iftar Community Gathering',
                'masjid_id' => $masjid1?->id,
                'city' => 'Mecca',
                'category' => 'Community',
                'description' => 'Locals and pilgrims breaking their fast together during the holy month of Ramadan.',
                'image_path' => 'assets/img/gallery/sample4.jpg',
                'is_featured' => false,
                'is_trending' => false,
                'status' => 'active',
                'views' => 3200,
                'likes' => 540,
                'uploaded_by' => 'Masjid Admin',
            ],
            [
                'title' => 'Historic Minaret at Dusk',
                'masjid_id' => null,
                'city' => 'Cairo',
                'category' => 'Architecture',
                'description' => 'A stunning silhouette of a historic minaret against the vibrant colors of the evening sky.',
                'image_path' => 'assets/img/gallery/sample5.jpg',
                'is_featured' => false,
                'is_trending' => true,
                'status' => 'active',
                'views' => 7600,
                'likes' => 890,
                'uploaded_by' => 'Traveler',
            ],
            [
                'title' => 'Eid Celebration Lights',
                'masjid_id' => $masjid2?->id,
                'city' => 'Medina',
                'category' => 'Events',
                'description' => 'The mosque beautifully illuminated with festive lights to celebrate Eid al-Fitr.',
                'image_path' => 'assets/img/gallery/sample6.jpg',
                'is_featured' => true,
                'is_trending' => false,
                'status' => 'active',
                'views' => 9800,
                'likes' => 2100,
                'uploaded_by' => 'Local Media',
            ]
        ];

        foreach ($images as $img) {
            GalleryImage::create($img);
        }
    }
}
