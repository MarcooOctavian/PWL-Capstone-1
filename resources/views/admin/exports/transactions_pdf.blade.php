<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        .header { margin-bottom: 25px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #333; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; }
        th { background-color: #f4f6f9; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        tr:nth-child(even) { background-color: #fdfdfd; }
        
        .badge { padding: 4px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; color: #fff;}
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-danger { background-color: #dc3545; }
    </style>
</head>
<body>

    <div class="header text-center">
        <h2>Laporan Transaksi E-Ticketing</h2>
        <p>Di-generate pada: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">ID Transaksi</th>
                <th width="30%">Nama Pembeli</th>
                <th width="20%">Total (Rp)</th>
                <th width="15%">Status</th>
                <th width="15%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $trx)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>#{{ $trx->id }}</td>
                <td>{{ $trx->user->name ?? 'Guest' }}</td>
                <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                <td>
                    @if(in_array(strtolower($trx->payment_status), ['paid', 'success']))
                        <span class="badge badge-success">{{ ucfirst($trx->payment_status) }}</span>
                    @elseif(in_array(strtolower($trx->payment_status), ['pending', 'unpaid']))
                        <span class="badge badge-warning">{{ ucfirst($trx->payment_status) }}</span>
                    @else
                        <span class="badge badge-danger">{{ ucfirst($trx->payment_status) }}</span>
                    @endif
                </td>
                <td>{{ $trx->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
