<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Schedule;
use App\Models\TypeTicket;
use Illuminate\Database\Seeder;

class TypeTicketSeeder extends Seeder
{
    public function run(): void
    {
        // Ticket types keyed by event title.
        // schedule_index: which schedule index (0-based) to attach to; null = event-level
        $data = [
            'Jakarta Music Festival 2026' => [
                ['name' => 'Early Bird',  'price' => 150000,  'stock' => 500,  'max_purchase' => 4, 'schedule_index' => null],
                ['name' => 'Regular',     'price' => 250000,  'stock' => 2000, 'max_purchase' => 4, 'schedule_index' => null],
                ['name' => 'VIP',         'price' => 750000,  'stock' => 200,  'max_purchase' => 2, 'schedule_index' => null],
                ['name' => 'VVIP',        'price' => 1500000, 'stock' => 50,   'max_purchase' => 2, 'schedule_index' => null],
            ],
            'Tech Summit Indonesia 2026' => [
                ['name' => 'Morning Session', 'price' => 200000, 'stock' => 400, 'max_purchase' => 2, 'schedule_index' => 0],
                ['name' => 'Afternoon Session', 'price' => 200000, 'stock' => 400, 'max_purchase' => 2, 'schedule_index' => 1],
                ['name' => 'Full Day',        'price' => 350000, 'stock' => 200, 'max_purchase' => 2, 'schedule_index' => null],
            ],
            'Workshop Fotografi Profesional' => [
                ['name' => 'Peserta Umum',   'price' => 300000, 'stock' => 80, 'max_purchase' => 1, 'schedule_index' => 0],
                ['name' => 'Peserta Pelajar', 'price' => 150000, 'stock' => 20, 'max_purchase' => 1, 'schedule_index' => 0],
            ],
            'Bandung Food & Culture Fest' => [
                ['name' => 'Tiket Siang',  'price' => 50000,  'stock' => 1500, 'max_purchase' => 6, 'schedule_index' => 0],
                ['name' => 'Tiket Malam',  'price' => 75000,  'stock' => 1000, 'max_purchase' => 6, 'schedule_index' => 1],
                ['name' => 'All Day Pass', 'price' => 100000, 'stock' => 500,  'max_purchase' => 4, 'schedule_index' => null],
            ],
            'Jogja Half Marathon 2026' => [
                ['name' => 'Kategori Umum',    'price' => 200000, 'stock' => 1500, 'max_purchase' => 1, 'schedule_index' => 0],
                ['name' => 'Kategori Pelajar', 'price' => 100000, 'stock' => 500,  'max_purchase' => 1, 'schedule_index' => 0],
            ],
            'Pameran Seni Kontemporer Surabaya' => [
                ['name' => 'Tiket Masuk',  'price' => 50000, 'stock' => 400, 'max_purchase' => 5, 'schedule_index' => 0],
                ['name' => 'Guided Tour', 'price' => 150000, 'stock' => 100, 'max_purchase' => 2, 'schedule_index' => 0],
            ],
            'Bali Digital Nomad Summit' => [
                ['name' => 'Morning Talk',     'price' => 500000, 'stock' => 300, 'max_purchase' => 2, 'schedule_index' => 0],
                ['name' => 'Afternoon Workshop', 'price' => 500000, 'stock' => 300, 'max_purchase' => 2, 'schedule_index' => 1],
                ['name' => 'Full Day Pass',    'price' => 800000, 'stock' => 200, 'max_purchase' => 2, 'schedule_index' => null],
            ],
            'Seminar Kewirausahaan Muda' => [
                ['name' => 'Tiket Umum',   'price' => 100000, 'stock' => 400, 'max_purchase' => 3, 'schedule_index' => 0],
                ['name' => 'Tiket Mahasiswa', 'price' => 50000, 'stock' => 200, 'max_purchase' => 2, 'schedule_index' => 0],
                ['name' => 'VIP Seat',     'price' => 300000, 'stock' => 50,  'max_purchase' => 2, 'schedule_index' => 0],
            ],
        ];

        foreach ($data as $eventTitle => $types) {
            $event     = Event::where('title', $eventTitle)->first();
            if (!$event) {
                continue;
            }

            $schedules = Schedule::where('event_id', $event->id)->get();

            foreach ($types as $type) {
                $scheduleId = null;
                if (!is_null($type['schedule_index'])) {
                    $schedule   = $schedules->get($type['schedule_index']);
                    $scheduleId = $schedule?->id;
                }

                TypeTicket::create([
                    'event_id'     => $event->id,
                    'schedule_id'  => $scheduleId,
                    'name'         => $type['name'],
                    'price'        => $type['price'],
                    'stock'        => $type['stock'],
                    'max_purchase' => $type['max_purchase'],
                ]);
            }
        }
    }
}
