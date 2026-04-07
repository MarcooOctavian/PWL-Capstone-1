<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    // Get all data
    public function collection()
    {
        return Transaction::with('user')->latest()->get();
    }

    // Header for excel
    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Pembeli',
            'Total Harga (Rp)',
            'Status',
            'Tanggal Transaksi'
        ];
    }

    // Mapping
    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->user->name ?? 'Guest/Unknown', 
            $transaction->total_amount,
            ucfirst($transaction->payment_status),
            $transaction->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
