@extends('user.layouts.master')

@section('content')
    <section class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>My Profile & Tickets</h2>
                        <div class="bt-option">
                            <a href="/home">Home</a>
                            <span>Profile</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="schedule-table-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Riwayat Transaksi</h2>
                        <p>Daftar tiket event yang sudah Anda pesan.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="schedule-table-tab">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="schedule-table-content">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>Nama Event</th>
                                            <th>Tanggal Pesan</th>
                                            <th>Jenis Tiket</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($transactions as $transaction)
                                            @php
                                                // Mengambil 1 tiket perwakilan untuk menampilkan nama event
                                                $firstTicket = $transaction->tickets->first();
                                            @endphp
                                            <tr>
                                                <td class="hover-bg">
                                                    <h5 style="color: #111;">{{ $firstTicket ? $firstTicket->typeTicket->event->title : 'Event Tidak Diketahui' }}</h5>
                                                </td>

                                                <td class="hover-bg">
                                                    <p>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d F Y') }}</p>
                                                </td>

                                                <td class="hover-bg">
                                                    <p>
                                                        {{ $firstTicket ? $firstTicket->typeTicket->name : '-' }}
                                                        <br><span style="font-size: 13px; color: #888;">({{ $transaction->tickets->count() }} Tiket)</span>
                                                    </p>
                                                </td>

                                                <td class="hover-bg">
                                                    @if($transaction->payment_status == 'paid')
                                                        <span style="color: #28a745; font-weight: bold;">Paid</span>
                                                    @else
                                                        <span style="color: #ffc107; font-weight: bold;">Pending</span>
                                                    @endif
                                                </td>

                                                <td class="hover-bg">
                                                    @if($transaction->payment_status == 'paid' && $firstTicket)
                                                        <a href="{{ route('ticket.show', $firstTicket->id) }}" class="primary-btn" style="padding: 10px 20px;">Lihat E-Ticket</a>
                                                    @else
                                                        <a href="#" class="primary-btn" style="background: #333; padding: 10px 20px;">Bayar Sekarang</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center hover-bg" style="padding: 40px;">
                                                    <h5 style="color: #666; margin-bottom: 10px;">Belum ada riwayat transaksi.</h5>
                                                    <a href="/checkout" style="color: #f1592a; font-weight: 600; text-decoration: underline;">Beli tiket sekarang</a>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
