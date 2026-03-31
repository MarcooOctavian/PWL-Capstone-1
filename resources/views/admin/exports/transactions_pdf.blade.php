<!DOCTYPE html>
<html>
<head>
    <title>Transactions Report</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #d9534f; }
        .header p { margin: 5px 0 0 0; color: #777; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        tr:nth-child(even) { background-color: #fdfdfd; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-success { background-color: #dff0d8; color: #3c763d; }
        .badge-warning { background-color: #fcf8e3; color: #8a6d3b; }
        .total-row { font-weight: bold; background-color: #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Event Management System</h1>
        <p><strong>Official Transactions Report</strong></p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('M d, Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th width="30%">Buyer Name</th>
                <th width="20%">Amount</th>
                <th width="20%">Date</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRevenue = 0; @endphp
            @foreach($transactions as $trx)
            @php if($trx->payment_status == 'paid') $totalRevenue += $trx->total_amount; @endphp
            <tr>
                <td>#{{ $trx->id }}</td>
                <td>{{ $trx->user->name ?? 'Guest' }}</td>
                <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('M d, Y') }}</td>
                <td>
                    @if($trx->payment_status == 'paid')
                        <span class="badge badge-success">{{ ucfirst($trx->payment_status) }}</span>
                    @else
                        <span class="badge badge-warning">{{ ucfirst($trx->payment_status) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" style="text-align: right;">Total Paid Revenue:</td>
                <td colspan="3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
