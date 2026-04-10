<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; }
        .text-center { text-align: center; }
        .header { margin-bottom: 25px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #333; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 11px; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: middle; }
        th { background-color: #f4f6f9; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        tr:nth-child(even) { background-color: #fdfdfd; }

        .badge { padding: 4px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; color: #fff;}
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-danger { background-color: #dc3545; }
    </style>
</head>
<body>

<div class="header text-center">
    <h2>Laporan Transaksi E-Ticketing</h2>
    <p>Di-generate pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i:s') }} WIB</p>
</div>

<table>
    <thead>
    <tr>
        <th width="3%">No</th>
        <th width="8%">ID TRX</th>
        <th width="17%">Nama Pembeli</th>
        <th width="22%">Event & Jenis Tiket</th>
        <th width="7%">Jumlah</th>
        <th width="13%">Total (Rp)</th>
        <th width="10%">Status</th>
        <th width="20%">Tanggal & Waktu</th> </tr>
    </thead>
    <tbody>
    @forelse($transactions as $index => $trx)
        @php
            $firstTicket = $trx->tickets->first();
            $eventName = $firstTicket ? ($firstTicket->typeTicket->event->title ?? '-') : '-';
            $ticketName = $firstTicket ? ($firstTicket->typeTicket->name ?? '-') : '-';
            $totalTickets = $trx->tickets->count();
        @endphp
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>#{{ $trx->id }}</td>
            <td>{{ $trx->user->name ?? 'Guest' }}</td>
            <td>
                <b>{{ $eventName }}</b><br>
                <span style="color: #666; font-size: 9px;">{{ $ticketName }}</span>
            </td>
            <td class="text-center">{{ $totalTickets }}x</td>
            <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
            <td class="text-center">
                @if(in_array(strtolower($trx->payment_status), ['paid', 'success']))
                    <span class="badge badge-success">{{ ucfirst($trx->payment_status) }}</span>
                @elseif(in_array(strtolower($trx->payment_status), ['pending', 'unpaid']))
                    <span class="badge badge-warning">{{ ucfirst($trx->payment_status) }}</span>
                @else
                    <span class="badge badge-danger">{{ ucfirst($trx->payment_status) }}</span>
                @endif
            </td>
            <td>{{ $trx->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Belum ada data transaksi.</td>
        </tr>
    @endforelse
    </tbody>
</table>

</body>
</html>
