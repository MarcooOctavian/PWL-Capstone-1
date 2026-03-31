@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="row">
    <!-- Small Box 1 -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $metrics['events_count'] }}</h3>
                <p>Total Events</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="{{ url('/events') }}" class="small-box-footer">
                Manage Events <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Small Box 2 -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $metrics['types_count'] }}</h3>
                <p>Ticket Types</p>
            </div>
            <div class="icon">
                <i class="fas fa-tags"></i>
            </div>
            <a href="{{ url('/ticket-types') }}" class="small-box-footer">
                Manage Types <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Small Box 3 -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $metrics['tickets_count'] }}</h3>
                <p>Tickets Generated</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <a href="#" class="small-box-footer">
                Dashboard Live <i class="fas fa-check-circle"></i>
            </a>
        </div>
    </div>

    <!-- Small Box 4 -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><sup style="font-size: 20px">Rp</sup>{{ number_format($metrics['revenue'], 0, ',', '.') }}</h3>
                <p>Total Gross Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <a href="#" class="small-box-footer">
                Dashboard Live <i class="fas fa-check-circle"></i>
            </a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Latest Transactions Panel -->
    <div class="col-md-12 col-lg-5">
        <div class="card card-warning card-outline">
            <div class="card-header border-0">
                <h3 class="card-title"><i class="fas fa-shopping-cart"></i> Latest Transactions</h3>
                <div class="card-tools">
                    <a href="{{ route('export.excel') }}" class="btn btn-xs btn-success"><i class="fas fa-file-excel"></i> Excel</a>
                    <a href="{{ route('export.pdf') }}" class="btn btn-xs btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestTransactions as $trx)
                        <tr>
                            <td>{{ $trx->user->name ?? 'Guest' }}</td>
                            <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('M d, Y') }}</td>
                            <td><span class="badge badge-success">{{ ucfirst($trx->payment_status) }}</span></td>
                        </tr>
                        @endforeach
                        @if($latestTransactions->isEmpty())
                        <tr><td colspan="4" class="text-center">No transactions yet</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Sold Tickets Panel -->
    <div class="col-md-12 col-lg-7">
        <div class="card card-primary card-outline">
            <div class="card-header border-0">
                <h3 class="card-title"><i class="fas fa-ticket-alt"></i> Recently Generated Tickets</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>QR Code</th>
                            <th>Buyer</th>
                            <th>Event</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($soldTickets as $ticket)
                        <tr>
                            <td>
                                <strong>{{ $ticket->qr_code }}</strong><br>
                                <small class="text-success">{{ ucfirst($ticket->status) }}</small>
                            </td>
                            <td>{{ $ticket->transaction->user->name ?? 'Guest' }}</td>
                            <td>{{ $ticket->typeTicket->event->title ?? 'N/A' }}</td>
                            <td>{{ $ticket->typeTicket->name ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                        @if($soldTickets->isEmpty())
                        <tr><td colspan="4" class="text-center">No tickets sold yet</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
