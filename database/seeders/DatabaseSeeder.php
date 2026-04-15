<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Run order respects FK dependencies:
     *   Users → Categories → Locations → Events
     *   → Schedules → TypeTickets → Transactions → WaitingLists
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
            EventSeeder::class,
            ScheduleSeeder::class,
            TypeTicketSeeder::class,
            TransactionSeeder::class,
            WaitingListSeeder::class,
        ]);
    }
}
