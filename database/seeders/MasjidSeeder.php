<?php

namespace Database\Seeders;

use App\Models\Masjid;
use Illuminate\Database\Seeder;

class MasjidSeeder extends Seeder
{
    public function run(): void
    {
        $sunni = [
            ['name' => 'Masjid-e-Tooba', 'address' => 'Old Korangi Road, Phase-II, DHA, Karachi, Pakistan', 'area' => 'DHA Phase 2', 'sect' => 'Sunni', 'latitude' => 24.843017, 'longitude' => 67.054450, 'fajr' => '04:44', 'zuhr' => '12:39', 'asr' => '17:00', 'maghrib' => '19:23', 'isha' => '20:48', 'juma_time' => '13:28', 'eid_time' => '07:02', 'phone' => '+92 21 35881234', 'description' => 'Largest single-dome mosque in the world without pillars. A landmark in DHA Phase 2.', 'parking' => true, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => true],
            ['name' => 'Baitul Mukarram Masjid', 'address' => 'Block 8, Main University Road, Gulshan-e-Iqbal, Karachi, Pakistan', 'area' => 'Gulshan-e-Iqbal', 'sect' => 'Sunni', 'latitude' => 24.907400, 'longitude' => 67.082700, 'fajr' => '04:42', 'zuhr' => '12:37', 'asr' => '16:58', 'maghrib' => '19:21', 'isha' => '20:46', 'juma_time' => '13:25', 'eid_time' => '07:00', 'phone' => '+92 21 34987654', 'description' => 'Prominent mosque near Urdu University and Expo Centre. Accommodates thousands of worshippers.', 'parking' => true, 'wheelchair' => true, 'ladies_prayer' => true, 'construction_status' => 'Completed', 'is_featured' => true],
            ['name' => 'Farooq-e-Azam Masjid', 'address' => 'Block 10, Gulshan-e-Iqbal, Karachi, Pakistan', 'area' => 'Gulshan-e-Iqbal', 'sect' => 'Sunni', 'latitude' => 24.896500, 'longitude' => 67.088000, 'fajr' => '04:43', 'zuhr' => '12:38', 'asr' => '16:59', 'maghrib' => '19:22', 'isha' => '20:47', 'juma_time' => '13:26', 'eid_time' => '07:01', 'phone' => '+92 21 34981234', 'description' => 'One of the largest mosques in Gulshan-e-Iqbal with a huge congregation.', 'parking' => true, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => true],
            ['name' => 'Masjid-e-Ayesha', 'address' => 'Khayaban-e-Ittehad, DHA, Karachi, Pakistan', 'area' => 'DHA Phase 2', 'sect' => 'Sunni', 'latitude' => 24.842000, 'longitude' => 67.051000, 'fajr' => '04:44', 'zuhr' => '12:39', 'asr' => '17:00', 'maghrib' => '19:24', 'isha' => '20:49', 'juma_time' => '13:27', 'eid_time' => '07:03', 'phone' => '+92 21 35891234', 'description' => 'Well-maintained community mosque in DHA serving local residents.', 'parking' => true, 'wheelchair' => true, 'ladies_prayer' => true, 'construction_status' => 'Completed', 'is_featured' => false],
            ['name' => 'Masjid-e-Umar Bin Khattab', 'address' => 'Saddar, Karachi, Pakistan', 'area' => 'Saddar', 'sect' => 'Sunni', 'latitude' => 24.852500, 'longitude' => 67.032000, 'fajr' => '04:45', 'zuhr' => '12:40', 'asr' => '17:01', 'maghrib' => '19:25', 'isha' => '20:50', 'juma_time' => '13:30', 'eid_time' => '07:05', 'phone' => '+92 21 32451234', 'description' => 'Historic mosque in the heart of Saddar serving the downtown community.', 'parking' => false, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => false],
            ['name' => 'New Memon Masjid', 'address' => 'Bolton Market, Saddar, Karachi, Pakistan', 'area' => 'Saddar', 'sect' => 'Sunni', 'latitude' => 24.851000, 'longitude' => 67.029000, 'fajr' => '04:45', 'zuhr' => '12:40', 'asr' => '17:01', 'maghrib' => '19:25', 'isha' => '20:50', 'juma_time' => '13:30', 'eid_time' => '07:05', 'phone' => '+92 21 32467890', 'description' => 'Historic mosque near Bolton Market serving the Memon community for decades.', 'parking' => false, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => false],
            ['name' => 'Jama Masjid Eidgah', 'address' => 'M.A. Jinnah Road, Karachi, Pakistan', 'area' => 'Saddar', 'sect' => 'Sunni', 'latitude' => 24.853000, 'longitude' => 67.031000, 'fajr' => '04:46', 'zuhr' => '12:41', 'asr' => '17:02', 'maghrib' => '19:26', 'isha' => '20:51', 'juma_time' => '13:31', 'eid_time' => '07:06', 'phone' => '+92 21 32455667', 'description' => 'Eidgah mosque on M.A. Jinnah Road. Used for Eid prayers and large congregations.', 'parking' => false, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => false],
            ['name' => 'Jamia Masjid Bab-ul-Islam', 'address' => 'Ram Talao Road, Aram Bagh, Karachi, Pakistan', 'area' => 'Garden', 'sect' => 'Sunni', 'latitude' => 24.854000, 'longitude' => 67.028000, 'fajr' => '04:46', 'zuhr' => '12:41', 'asr' => '17:02', 'maghrib' => '19:26', 'isha' => '20:51', 'juma_time' => '13:31', 'eid_time' => '07:06', 'phone' => '', 'description' => 'Community mosque in Aram Bagh area.', 'parking' => false, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => false],
            ['name' => 'Liaquat Masjid', 'address' => 'Opposite Khaliqdina Hall, M.A. Jinnah Road, Aram Bagh, Karachi, Pakistan', 'area' => 'Saddar', 'sect' => 'Sunni', 'latitude' => 24.852800, 'longitude' => 67.030500, 'fajr' => '04:45', 'zuhr' => '12:40', 'asr' => '17:01', 'maghrib' => '19:25', 'isha' => '20:50', 'juma_time' => '13:30', 'eid_time' => '07:05', 'phone' => '', 'description' => 'Located near Khaliqdina Hall on M.A. Jinnah Road.', 'parking' => false, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => false],
            ['name' => 'Sunehri Masjid', 'address' => 'Tamchand Hashmat Rai Street, Saddar, Karachi, Pakistan', 'area' => 'Saddar', 'sect' => 'Sunni', 'latitude' => 24.851500, 'longitude' => 67.027500, 'fajr' => '04:46', 'zuhr' => '12:41', 'asr' => '17:02', 'maghrib' => '19:26', 'isha' => '20:51', 'juma_time' => '13:31', 'eid_time' => '07:06', 'phone' => '', 'description' => 'Historic mosque in the old Saddar area.', 'parking' => false, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => false],
            ['name' => 'Madina Masjid', 'address' => 'Shahrah-e-Liaquat, Burns Road, Karachi, Pakistan', 'area' => 'Saddar', 'sect' => 'Sunni', 'latitude' => 24.853200, 'longitude' => 67.029800, 'fajr' => '04:45', 'zuhr' => '12:40', 'asr' => '17:01', 'maghrib' => '19:25', 'isha' => '20:50', 'juma_time' => '13:30', 'eid_time' => '07:05', 'phone' => '', 'description' => 'Active mosque on bustling Burns Road.', 'parking' => false, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => false],
            ['name' => 'Jinnah Masjid', 'address' => 'Burns Road, Karachi, Pakistan', 'area' => 'Saddar', 'sect' => 'Sunni', 'latitude' => 24.852900, 'longitude' => 67.030100, 'fajr' => '04:45', 'zuhr' => '12:40', 'asr' => '17:01', 'maghrib' => '19:25', 'isha' => '20:50', 'juma_time' => '13:30', 'eid_time' => '07:05', 'phone' => '', 'description' => 'Burns Road landmark mosque.', 'parking' => false, 'wheelchair' => false, 'ladies_prayer' => false, 'construction_status' => 'Completed', 'is_featured' => false],
        ];

        $shia = [
            ['name' => 'Masjid-e-Yasrab', 'address' => 'Phase 4, DHA, Karachi, Pakistan', 'area' => 'DHA Phase 4', 'sect' => 'Shia', 'latitude' => 24.822200, 'longitude' => 67.074400, 'fajr' => '04:45', 'zuhr' => '12:40', 'asr' => '17:01', 'maghrib' => '19:24', 'isha' => '20:49', 'juma_time' => '13:35', 'eid_time' => '07:06', 'phone' => '+92 21 35891122', 'description' => 'Prominent Shia mosque and community center in DHA Phase 4.', 'parking' => true, 'wheelchair' => true, 'ladies_prayer' => true, 'construction_status' => 'Completed', 'is_featured' => true],
            ['name' => 'Imambargah Shah-e-Karbala', 'address' => 'Rizvia Society, Liaquatabad, Karachi, Pakistan', 'area' => 'Liaquatabad', 'sect' => 'Shia', 'latitude' => 24.859000, 'longitude' => 67.038000, 'fajr' => '04:44', 'zuhr' => '12:39', 'asr' => '17:00', 'maghrib' => '19:23', 'isha' => '20:48', 'juma_time' => '13:33', 'eid_time' => '07:04', 'phone' => '+92 21 32456789', 'description' => 'Trust mosque in Rizvia Society. Historic center for Azadari in Karachi since 1960s.', 'parking' => true, 'wheelchair' => false, 'ladies_prayer' => true, 'construction_status' => 'Completed', 'is_featured' => true],
            ['name' => 'Imambargah Jaffar-e-Tayyar', 'address' => 'Jaffar-e-Tayyar Society, Malir, Karachi, Pakistan', 'area' => 'Malir', 'sect' => 'Shia', 'latitude' => 24.892000, 'longitude' => 67.145000, 'fajr' => '04:41', 'zuhr' => '12:36', 'asr' => '16:57', 'maghrib' => '19:20', 'isha' => '20:45', 'juma_time' => '13:22', 'eid_time' => '06:58', 'phone' => '+92 21 34567890', 'description' => 'Central Shia religious institution in Malir serving a large community.', 'parking' => true, 'wheelchair' => true, 'ladies_prayer' => true, 'construction_status' => 'Completed', 'is_featured' => true],
            ['name' => 'Imambargah Ancholi', 'address' => 'Federal B Area, Karachi, Pakistan', 'area' => 'Federal B Area', 'sect' => 'Shia', 'latitude' => 24.931000, 'longitude' => 67.062000, 'fajr' => '04:40', 'zuhr' => '12:35', 'asr' => '16:56', 'maghrib' => '19:19', 'isha' => '20:44', 'juma_time' => '13:20', 'eid_time' => '06:56', 'phone' => '+92 21 33768901', 'description' => 'Major Imambargah in Federal B Area with large majlis hall.', 'parking' => true, 'wheelchair' => false, 'ladies_prayer' => true, 'construction_status' => 'Completed', 'is_featured' => true],
            ['name' => 'Masjid Wali-ul-Asr', 'address' => 'Gulshan-e-Iqbal, Karachi, Pakistan', 'area' => 'Gulshan-e-Iqbal', 'sect' => 'Shia', 'latitude' => 24.894000, 'longitude' => 67.084000, 'fajr' => '04:43', 'zuhr' => '12:38', 'asr' => '16:59', 'maghrib' => '19:22', 'isha' => '20:47', 'juma_time' => '13:27', 'eid_time' => '07:01', 'phone' => '+92 21 34993456', 'description' => 'Well-known Shia mosque in Gulshan-e-Iqbal.', 'parking' => true, 'wheelchair' => true, 'ladies_prayer' => true, 'construction_status' => 'Completed', 'is_featured' => true],
            ['name' => 'Markazi ImamBargah Ghazi Abbas Trust', 'address' => 'Jaffar-e-Tayyar Society, Malir, Karachi, Pakistan', 'area' => 'Malir', 'sect' => 'Shia', 'latitude' => 24.893000, 'longitude' => 67.146000, 'fajr' => '04:41', 'zuhr' => '12:36', 'asr' => '16:57', 'maghrib' => '19:20', 'isha' => '20:45', 'juma_time' => '13:22', 'eid_time' => '06:58', 'phone' => '+92 21 34562345', 'description' => 'Major Shia community and religious centre in Malir.', 'parking' => true, 'wheelchair' => true, 'ladies_prayer' => true, 'construction_status' => 'Completed', 'is_featured' => true],
        ];

        $all = array_merge($sunni, $shia);

        $counts = ['Sunni' => 0, 'Shia' => 0, 'skipped' => 0];

        foreach ($all as $masjid) {
            $existing = Masjid::where('name', $masjid['name'])->first();

            if ($existing) {
                $updated = false;
                foreach ($masjid as $key => $value) {
                    if ($existing->$key !== $value) {
                        $existing->$key = $value;
                        $updated = true;
                    }
                }
                if ($updated) {
                    $existing->save();
                    $counts['skipped']++;
                }
                continue;
            }

            Masjid::create($masjid);
            $counts[$masjid['sect']]++;
        }

        dump('Total Sunni Masajids inserted/updated: ' . $counts['Sunni']);
        dump('Total Shia Masajids inserted/updated: ' . $counts['Shia']);
        dump('Duplicate records skipped: ' . $counts['skipped']);
        dump('Areas covered: ' . Masjid::distinct('area')->count('area'));
        dump('Total records in DB: ' . Masjid::count());
    }
}
