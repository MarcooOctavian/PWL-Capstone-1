@extends('user.layouts.master')

@section('content')
    <section class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Event</h2>
                        <div class="bt-option">
                            <a href="/">Home</a>
                            <span>Our Event</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="speaker-section spad">
        <div class="container">

            <div class="row mb-5">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <h3 style="color: #f1592a; font-weight: 700; margin-bottom: 15px;">Temukan Event Terbaik untuk Anda!</h3>
                    <p style="color: #666; font-size: 16px; line-height: 1.8;">
                        Selamat datang di platform E-Ticketing kami! Jelajahi berbagai event seru — mulai dari
                        konferensi teknologi, workshop kreatif, seminar bisnis, hingga festival musik.
                        Pilih event yang sesuai dengan minat Anda, pesan tiket secara online, dan dapatkan
                        e-ticket langsung di email Anda. Proses cepat, aman, dan tanpa ribet!
                    </p>
                </div>
            </div>

            <div class="row">
                @foreach($events as $event)
                    <div class="col-lg-12 mb-4">
                        <div class="speaker-item">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="si-pic">
                                        @if($event->banner_url)
                                            <img src="{{ asset($event->banner_url) }}" alt="">
                                        @else
                                            <div style="width:100%; height:200px; background:#eee; display:flex; align-items:center; justify-content:center;">
                                                <span>No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="si-text">
                                        <div class="si-title">
                                            <h4>{{ $event->title }}</h4>
                                            <span>{{ $event->date }}</span>
                                        </div>

                                        <!-- database description -->
                                        <p style="color: #555; line-height: 1.7; margin-top: 10px;">
                                            {{ $event->description ?? 'Event ini merupakan kesempatan luar biasa untuk belajar, berjejaring, dan mendapatkan pengalaman baru. Bergabunglah bersama ratusan peserta lainnya dalam acara yang dikurasi secara profesional. Jangan lewatkan — tiket terbatas!' }}
                                        </p>

                                        <div style="margin-top: 12px; padding: 12px 16px; background: #f8f9fa; border-left: 4px solid #f1592a; border-radius: 4px;">
                                            <small style="color: #888;">
                                                <i class="fa fa-info-circle"></i>
                                                Pembelian tiket bersifat final. Harap periksa kembali tanggal dan jenis tiket sebelum melakukan pembayaran.
                                                E-Ticket akan dikirim secara otomatis setelah pembayaran berhasil dikonfirmasi.
                                            </small>
                                        </div>

                                        <div class="mt-3">
                                            <a href="{{ route('checkout.create', ['event_id' => $event->id]) }}" class="primary-btn">
                                                Pesan Tiket
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
