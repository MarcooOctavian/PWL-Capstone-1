<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch organizers (role '2')
        $organizers  = User::where('role', '2')->get();
        $categories  = Category::all()->keyBy('name');
        $locations   = Location::all()->keyBy('venue_name');

        $events = [
            [
                'title'       => 'Jakarta Music Festival 2026',
                'description' => 'Festival musik terbesar di Jakarta yang menghadirkan artis-artis ternama dari dalam dan luar negeri. Nikmati penampilan spektakuler selama dua hari penuh.',
                'category'    => 'Musik & Konser',
                'location'    => 'Jakarta Convention Center',
                'date'        => '2026-05-20',
                'status'      => 'upcoming',
                'organizer'   => 0, // index into $organizers
            ],
            [
                'title'       => 'Tech Summit Indonesia 2026',
                'description' => 'Konferensi teknologi terkemuka yang mempertemukan para inovator, startup, dan pemimpin industri untuk berbagi wawasan dan tren terbaru di dunia teknologi.',
                'category'    => 'Teknologi',
                'location'    => 'Balai Kartini',
                'date'        => '2026-06-05',
                'status'      => 'upcoming',
                'organizer'   => 1,
            ],
            [
                'title'       => 'Workshop Fotografi Profesional',
                'description' => 'Workshop intensif selama sehari penuh yang dipandu oleh fotografer profesional berpengalaman. Cocok untuk pemula maupun fotografer menengah.',
                'category'    => 'Seminar & Workshop',
                'location'    => 'Gedung Sate',
                'date'        => '2026-05-10',
                'status'      => 'upcoming',
                'organizer'   => 0,
            ],
            [
                'title'       => 'Bandung Food & Culture Fest',
                'description' => 'Festival kuliner dan budaya yang merayakan kekayaan cita rasa dan tradisi Jawa Barat. Tersedia ratusan booth makanan dan pertunjukan seni budaya.',
                'category'    => 'Kuliner & Lifestyle',
                'location'    => 'Gedung Sate',
                'date'        => '2026-04-28',
                'status'      => 'ongoing',
                'organizer'   => 1,
            ],
            [
                'title'       => 'Jogja Half Marathon 2026',
                'description' => 'Lomba lari setengah maraton melewati jalur ikonik kota Yogyakarta. Terbuka untuk pelari dari seluruh Indonesia dan mancanegara.',
                'category'    => 'Olahraga',
                'location'    => 'Jogja Expo Center',
                'date'        => '2026-07-12',
                'status'      => 'upcoming',
                'organizer'   => 0,
            ],
            [
                'title'       => 'Pameran Seni Kontemporer Surabaya',
                'description' => 'Pameran seni kontemporer yang menampilkan karya-karya seniman muda berbakat Indonesia. Tersedia galeri lukisan, instalasi, dan seni digital.',
                'category'    => 'Seni & Budaya',
                'location'    => 'Grand City Convention & Exhibition',
                'date'        => '2026-03-15',
                'status'      => 'completed',
                'organizer'   => 1,
            ],
            [
                'title'       => 'Bali Digital Nomad Summit',
                'description' => 'Pertemuan eksklusif para digital nomad dan remote worker dari seluruh dunia di Bali. Networking, talk show, dan workshop produktivitas.',
                'category'    => 'Teknologi',
                'location'    => 'Bali Nusa Dua Convention Center',
                'date'        => '2026-08-20',
                'status'      => 'upcoming',
                'organizer'   => 0,
            ],
            [
                'title'       => 'Seminar Kewirausahaan Muda',
                'description' => 'Seminar inspiratif bagi anak muda yang ingin memulai atau mengembangkan bisnis mereka. Hadirkan pembicara top dari berbagai industri.',
                'category'    => 'Seminar & Workshop',
                'location'    => 'Jakarta Convention Center',
                'date'        => '2026-06-25',
                'status'      => 'upcoming',
                'organizer'   => 1,
            ],
        ];

        foreach ($events as $eventData) {
            $organizer = $organizers[$eventData['organizer'] % $organizers->count()];
            $category  = $categories[$eventData['category']];
            $location  = $locations[$eventData['location']];

            Event::create([
                'organizer_id' => $organizer->id,
                'category_id'  => $category->id,
                'location_id'  => $location->id,
                'title'        => $eventData['title'],
                'description'  => $eventData['description'],
                'date'         => $eventData['date'],
                'status'       => $eventData['status'],
            ]);
        }
    }
}
