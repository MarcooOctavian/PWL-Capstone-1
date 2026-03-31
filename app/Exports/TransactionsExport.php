<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Transaction::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Buyer Name',
            'Total Amount (Rp)',
            'Status',
            'Transaction Date'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->user->name ?? 'Guest',
            $transaction->total_amount,
            ucfirst($transaction->payment_status),
            Carbon::parse($transaction->transaction_date)->format('Y-m-d')
        ];
    }
}
