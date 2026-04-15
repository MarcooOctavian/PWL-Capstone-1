<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Get all data
     */
    public function collection()
    {
        return Ticket::with(['transaction.user', 'typeTicket.event'])->latest()->get();
    }

    /**
     * Header for excel
     */
    public function headings(): array
    {
        return [
            'ID Tiket',
            'QR Code',
            'ID Transaksi',
            'Tanggal Beli',
            'Nama Pembeli',
            'Event',
            'Jenis Tiket',
            'Status Tiket'
        ];
    }

    /**
     * Mapping
     */
    public function map($ticket): array
    {
        $buyerName = $ticket->transaction->user->name ?? 'Guest/Unknown';
        $trxDate = $ticket->transaction ? Carbon::parse($ticket->transaction->created_at)->format('Y-m-d H:i:s') : '-';
        $eventName = $ticket->typeTicket->event->title ?? '-';
        $ticketType = $ticket->typeTicket->name ?? '-';

        return [
            'TKT-' . $ticket->id,
            $ticket->qr_code,
            'TRX-' . $ticket->transaction_id,
            $trxDate,
            $buyerName,
            $eventName,
            $ticketType,
            ucfirst($ticket->status)
        ];
    }
}
