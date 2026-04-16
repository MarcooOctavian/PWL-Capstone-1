<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'venue_name' => 'Jakarta Convention Center',
                'address'    => 'Jl. Gatot Subroto, Senayan',
                'city'       => 'Jakarta',
                'maps_url'   => 'https://maps.google.com/?q=Jakarta+Convention+Center',
            ],
            [
                'venue_name' => 'Balai Kartini',
                'address'    => 'Jl. Gatot Subroto No.37',
                'city'       => 'Jakarta',
                'maps_url'   => 'https://maps.google.com/?q=Balai+Kartini+Jakarta',
            ],
            [
                'venue_name' => 'Gedung Sate',
                'address'    => 'Jl. Diponegoro No.22',
                'city'       => 'Bandung',
                'maps_url'   => 'https://maps.google.com/?q=Gedung+Sate+Bandung',
            ],
            [
                'venue_name' => 'Jogja Expo Center',
                'address'    => 'Jl. Raya Janti, Banguntapan',
                'city'       => 'Yogyakarta',
                'maps_url'   => 'https://maps.google.com/?q=Jogja+Expo+Center',
            ],
            [
                'venue_name' => 'Grand City Convention & Exhibition',
                'address'    => 'Jl. Gubeng Pojok No.1',
                'city'       => 'Surabaya',
                'maps_url'   => 'https://maps.google.com/?q=Grand+City+Surabaya',
            ],
            [
                'venue_name' => 'Bali Nusa Dua Convention Center',
                'address'    => 'Kawasan ITDC, Nusa Dua',
                'city'       => 'Bali',
                'maps_url'   => 'https://maps.google.com/?q=BNDCC+Bali',
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
