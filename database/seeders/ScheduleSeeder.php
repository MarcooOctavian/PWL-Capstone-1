<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Each event gets 1-2 schedules; location reused from the event's own location
        $schedules = [
            // Jakarta Music Festival — 2 days
            'Jakarta Music Festival 2026' => [
                ['start_time' => '14:00:00', 'end_time' => '22:00:00'],
                ['start_time' => '14:00:00', 'end_time' => '23:00:00'],
            ],
            // Tech Summit — 2 sessions
            'Tech Summit Indonesia 2026' => [
                ['start_time' => '08:00:00', 'end_time' => '12:00:00'],
                ['start_time' => '13:00:00', 'end_time' => '17:00:00'],
            ],
            // Workshop Fotografi — 1 session
            'Workshop Fotografi Profesional' => [
                ['start_time' => '09:00:00', 'end_time' => '17:00:00'],
            ],
            // Bandung Food Fest — 2 sessions
            'Bandung Food & Culture Fest' => [
                ['start_time' => '10:00:00', 'end_time' => '17:00:00'],
                ['start_time' => '17:00:00', 'end_time' => '22:00:00'],
            ],
            // Jogja Half Marathon — 1 session
            'Jogja Half Marathon 2026' => [
                ['start_time' => '06:00:00', 'end_time' => '12:00:00'],
            ],
            // Pameran Seni — 1 session
            'Pameran Seni Kontemporer Surabaya' => [
                ['start_time' => '10:00:00', 'end_time' => '20:00:00'],
            ],
            // Bali Digital Nomad — 2 sessions
            'Bali Digital Nomad Summit' => [
                ['start_time' => '09:00:00', 'end_time' => '13:00:00'],
                ['start_time' => '14:00:00', 'end_time' => '18:00:00'],
            ],
            // Seminar Kewirausahaan — 1 session
            'Seminar Kewirausahaan Muda' => [
                ['start_time' => '08:30:00', 'end_time' => '16:30:00'],
            ],
        ];

        foreach ($schedules as $eventTitle => $sessions) {
            $event = Event::where('title', $eventTitle)->first();
            if (!$event) {
                continue;
            }

            foreach ($sessions as $session) {
                Schedule::create([
                    'event_id'    => $event->id,
                    'location_id' => $event->location_id,
                    'start_time'  => $session['start_time'],
                    'end_time'    => $session['end_time'],
                ]);
            }
        }
    }
}
