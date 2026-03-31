<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\TypeTicket;

class TypeTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        // Get or Create a dummy event just to satisfy the constraint
        $event = Event::firstOrCreate(
            ['id' => 1],
            [
                'organizer_id' => 1,
                'category_id' => 1,
                'location_id' => 1,
                'title' => 'Maranatha Tech Conference 2026',
                'description' => 'A tech event for students and devs',
                'date' => '2026-05-29',
                'quota' => 500,
                'status' => 'upcoming'
            ]
        );
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        TypeTicket::create([
            'event_id' => $event->id,
            'name' => 'Regular Ticket',
            'price' => 100000,
            'stock' => 100,
            'max_purchase' => 5,
        ]);

        TypeTicket::create([
            'event_id' => $event->id,
            'name' => 'VIP Ticket',
            'price' => 250000,
            'stock' => 50,
            'max_purchase' => 2,
        ]);
    }
}
