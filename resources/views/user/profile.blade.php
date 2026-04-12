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

            @php
                // Mencari tiket yang statusnya 'notified'
                $myWaitingList = \App\Models\WaitingList::where('user_id', auth()->id() ?? 1)
                    ->where('status', 'notified')
                    ->with('ticketType.event')
                    ->get();
            @endphp

            @if($myWaitingList->count() > 0)
                <div class="row">
                    <div class="col-lg-12">
                        <div style="background-color: #fff3cd; color: #856404; padding: 20px; border-radius: 8px; margin-bottom: 40px; border-left: 5px solid #ffeeba;">
                            <h4 style="margin-top: 0; font-size: 18px;"><i class="fas fa-bell"></i> Pemberitahuan Kuota Tiket!</h4>
                            <p style="margin-bottom: 10px;">Tiket incaran Anda dari Waiting List sekarang <strong>TERSEDIA!</strong> Segera ambil sebelum hangus.</p>

                            @foreach($myWaitingList as $wl)
                                <div style="background: white; padding: 15px; border-radius: 5px; margin-top: 10px; border: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                                    <div>
                                        <strong style="font-size: 16px;">{{ $wl->ticketType->event->title ?? 'Event' }}</strong><br>
                                        <span style="color: #666;">Jenis: {{ $wl->ticketType->name ?? 'Tiket' }}</span>
                                    </div>

                                    <div>
                                        <form action="{{ route('waiting-list.respond', $wl->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="primary-btn" style="padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                                                Ambil & Bayar
                                            </button>
                                        </form>

                                        <form action="{{ route('waiting-list.respond', $wl->id) }}" method="POST" style="display: inline; margin-left: 5px;">
                                            @csrf
                                            <input type="hidden" name="action" value="decline">
                                            <button type="submit" class="primary-btn" style="padding: 10px 20px; border: 1px solid #dc3545; background: transparent; color: #dc3545; border-radius: 4px; cursor: pointer;">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
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
