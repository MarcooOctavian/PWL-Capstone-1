<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TypeTicket;
use App\Models\User;
use App\Models\WaitingList;
use Illuminate\Database\Seeder;

class WaitingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', '3')->get();

        // Only add waiting list entries for events that have sold-out or nearly sold-out ticket types
        // We'll use a few popular events for this
        $popularEventTitles = [
            'Jakarta Music Festival 2026',
            'Tech Summit Indonesia 2026',
            'Workshop Fotografi Profesional',
        ];

        foreach ($popularEventTitles as $eventTitle) {
            $event = Event::where('title', $eventTitle)->first();
            if (!$event) {
                continue;
            }

            $typeTicket = TypeTicket::where('event_id', $event->id)->first();
            if (!$typeTicket) {
                continue;
            }

            // Add 2-4 users to the waiting list per event
            $eligible = $users->random(min(3, $users->count()));
            $statuses = ['waiting', 'notified', 'purchased', 'canceled'];

            foreach ($eligible as $user) {
                WaitingList::firstOrCreate(
                    [
                        'user_id'        => $user->id,
                        'event_id'       => $event->id,
                        'ticket_type_id' => $typeTicket->id,
                    ],
                    [
                        'name'   => $user->name,
                        'email'  => $user->email,
                        'status' => $statuses[array_rand($statuses)],
                    ]
                );
            }
        }
    }
}
