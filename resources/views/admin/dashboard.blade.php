@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
    <div class="row">
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

    <div class="row mt-3">
        <div class="col-lg-8 col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-chart-line"></i> Grafik Transaksi (Pendapatan)</h3>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="salesChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card card-success card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Statistik Penjualan</h3>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="ticketTypeChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-info card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Event Performance Analytics</h3>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="eventPerformanceChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
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
                        <!-- Latest Transactions Loop -->
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
                        <!-- Recently Sold Tickets Loop -->
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // 1. Transaction Line Chart Initialization
            const ctxSales = document.getElementById('salesChart').getContext('2d');
            new Chart(ctxSales, {
                type: 'line',
                data: {
                    labels: {!! isset($dates) ? json_encode($dates) : '[]' !!},
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: {!! isset($revenues) ? json_encode($revenues) : '[]' !!},
                        borderColor: '#f1592a',
                        backgroundColor: 'rgba(241, 89, 42, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#f1592a'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });

            // 2. Sales Statistics by Ticket Type Doughnut Chart
            const ctxType = document.getElementById('ticketTypeChart').getContext('2d');
            new Chart(ctxType, {
                type: 'doughnut',
                data: {
                    labels: {!! isset($typeLabels) ? json_encode($typeLabels) : '[]' !!},
                    datasets: [{
                        data: {!! isset($typeData) ? json_encode($typeData) : '[]' !!},
                        backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6c757d'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // 3. Event Performance Analytics Bar Chart
            const ctxEvent = document.getElementById('eventPerformanceChart').getContext('2d');
            new Chart(ctxEvent, {
                type: 'bar',
                data: {
                    labels: {!! isset($eventLabels) ? json_encode($eventLabels) : '[]' !!},
                    datasets: [{
                        label: 'Tiket Terjual',
                        data: {!! isset($eventData) ? json_encode($eventData) : '[]' !!},
                        backgroundColor: '#007bff',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

        });
    </script>
@endsection
