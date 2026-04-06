@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="section-title text-center">
                        <h2>E-Ticket Anda</h2>
                        <p>Tunjukkan QR Code ini kepada petugas di lokasi event.</p>
                    </div>
                    <div style="border: 2px dashed #f1592a; padding: 30px; border-radius: 15px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <div class="text-center mb-4">
                            <h4 style="color: #111; font-weight: 700;">{{ strtoupper($ticket->typeTicket->event->title ?? 'Maranatha Tech Conference 2026') }}</h4>
                            <p style="color: #f1592a; font-weight: 600;">{{ strtoupper($ticket->typeTicket->name) }} PASS</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <small style="color: #999;">NAMA PEMESAN</small>
                                <p style="font-weight: 600;">{{ $ticket->transaction->user->name ?? 'Guest' }}</p>
                            </div>
                            <div class="col-6 text-right">
                                <small style="color: #999;">TANGGAL EVENT</small>
                                <p style="font-weight: 600;">{{ \Carbon\Carbon::parse($ticket->typeTicket->event->date ?? now())->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="text-center p-4" style="background: #f8f8f8; border-radius: 10px;">
                            {!! QrCode::size(200)->generate(url('/scan/' . $ticket->qr_code)) !!}
                            <p class="mt-2" style="font-size: 12px; color: #666;">(Kode Tiket: {{ $ticket->qr_code }})</p>
                        </div>

                        <div class="text-center mt-4">
                            <button onclick="window.print()" class="site-btn">Cetak / Simpan PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
