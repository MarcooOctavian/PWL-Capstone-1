<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\TypeTicket;
use App\Models\User;
use Carbon\Carbon;

class DummyTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = User::first();
        $typeTicket = TypeTicket::first();

        if (!$user || !$typeTicket) {
            return;
        }

        // Generate 15 fake past transactions to populate the graphs and reports
        for ($i = 0; $i < 15; $i++) {
            $status = rand(0, 3) > 0 ? 'paid' : 'pending'; // 75% chance of paid
            $numTickets = rand(1, 3);
            
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'total_amount' => $typeTicket->price * $numTickets,
                'payment_status' => $status,
                'transaction_date' => Carbon::now()->subDays(rand(0, 14))->subHours(rand(1, 23)),
            ]);

            for ($k = 0; $k < $numTickets; $k++) {
                Ticket::create([
                    'transaction_id' => $transaction->id,
                    'type_ticket_id' => $typeTicket->id,
                    'qr_code' => 'TKT-' . Str::upper(Str::random(8)),
                    'status' => 'valid',
                ]);
            }
        }
    }
}
