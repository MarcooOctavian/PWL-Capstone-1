<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\TypeTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed transactions for regular users (role '3')
        $users       = User::where('role', '3')->get();
        $typeTickets = TypeTicket::all();

        if ($users->isEmpty() || $typeTickets->isEmpty()) {
            return;
        }

        $statuses = ['paid', 'paid', 'paid', 'pending', 'failed']; // ~60% paid

        foreach ($users as $user) {
            // Each user gets 2-5 transactions
            $numTransactions = rand(2, 5);

            for ($i = 0; $i < $numTransactions; $i++) {
                $typeTicket  = $typeTickets->random();
                $numTickets  = rand(1, min(3, $typeTicket->max_purchase));
                $status      = $statuses[array_rand($statuses)];
                $daysAgo     = rand(0, 30);

                $transaction = Transaction::create([
                    'user_id'          => $user->id,
                    'total_amount'     => $typeTicket->price * $numTickets,
                    'payment_status'   => $status,
                    'transaction_date' => Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23)),
                ]);

                // Only create ticket records for paid transactions
                if ($status === 'paid') {
                    for ($k = 0; $k < $numTickets; $k++) {
                        Ticket::create([
                            'transaction_id' => $transaction->id,
                            'type_ticket_id' => $typeTicket->id,
                            'qr_code'        => 'TKT-' . Str::upper(Str::random(10)),
                            'status'         => 'valid',
                        ]);
                    }
                }
            }
        }
    }
}
