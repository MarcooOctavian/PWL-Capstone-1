@extends('user.layouts.master')

@section('content')
    <section class="hero-section set-bg" data-setbg="{{ asset('user/img/hero.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="hero-text">
                        <span>Live Events & Concerts | Hari ini, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</span>
                        <h2>Dapatkan Tiket<br /> Konser Impianmu</h2>
                        <a href="{{ route('events.public') }}" class="primary-btn">Beli Tiket Sekarang</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img src="{{ asset('user/img/hero-right.png') }}" alt="Concert Event">
                </div>
            </div>
        </div>
    </section>

    <section class="counter-section bg-gradient">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="counter-text">
                        <span>
                            Jadwal Terdekat
                            @if(!empty($nextEvent))
                                : {{ $nextEvent->title }}
                            @endif
                        </span>
                        <h3>Hitung Mundur <br />Menuju Acara</h3>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="cd-timer" id="countdown" data-countdown-date="{{ $countdownTarget }}">
                        <div class="cd-item">
                            <span>00</span>
                            <p>Hari</p>
                        </div>
                        <div class="cd-item">
                            <span>00</span>
                            <p>Jam</p>
                        </div>
                        <div class="cd-item">
                            <span>00</span>
                            <p>Menit</p>
                        </div>
                        <div class="cd-item">
                            <span>00</span>
                            <p>Detik</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-about-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ha-pic">
                        <img src="{{ asset('user/img/h-about.jpg') }}" alt="Tentang Tickify">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ha-text">
                        <h2>Tentang Tickify</h2>
                        <p>Tickify adalah platform penyedia layanan tiket event dan konser terpercaya. Kami memastikan pengalaman pembelian tiket Anda menjadi lebih cepat, aman, dan tanpa hambatan. Jangan sampai kehabisan tiket musisi favorit Anda!</p>
                        <ul>
                            <li><span class="icon_check"></span> Sistem Pembayaran Aman & Praktis</li>
                            <li><span class="icon_check"></span> E-Ticket Otomatis Terkirim via Email</li>
                            <li><span class="icon_check"></span> Fitur Waiting List Anti-Kehabisan</li>
                        </ul>
                        <a href="{{ route('events.public') }}" class="ha-btn">Lihat Semua Event</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
