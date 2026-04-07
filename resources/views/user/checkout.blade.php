@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 m-auto">
                    <div class="section-title text-center">
                        <h2>Checkout Tiket</h2>
                        <p>Silakan lengkapi detail pesanan dan data diri Anda di bawah ini.</p>
                    </div>
                    <form action="{{ route('checkout.store') }}" method="POST" class="comment-form">
                        @csrf
                        <div class="row" style="background: #fdfdfd; padding: 30px; border-radius: 8px; border: 1px solid #eee;">

                            <div class="col-lg-12">
                                <h4 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #111; border-bottom: 2px solid #f1592a; padding-bottom: 8px;">1. Pilihan Tiket</h4>
                            </div>

                            <div class="col-lg-12">
                                <select name="schedule_id" required style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1; color: #666666; font-size: 16px; border-radius: 4px;">
                                    <option value="">-- Pilih Jadwal Event --</option>
                                    @if(isset($schedules))
                                        @foreach($schedules as $schedule)
                                            <option value="{{ $schedule->id }}">
                                                {{ \Carbon\Carbon::parse($schedule->date)->format('d F Y') }} - {{ $schedule->location_name ?? 'Lokasi Event' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <select name="type_ticket_id" required style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1; color: #666666; font-size: 16px; border-radius: 4px;">
                                    <option value="">-- Pilih Jenis Tiket --</option>
                                    @if(isset($typeTickets))
                                        @foreach($typeTickets as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->name }} - Rp {{ number_format($type->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <input type="number" name="qty" placeholder="Jumlah (Kuota) Tiket" min="1" value="1" required style="width: 100%; height: 50px; margin-bottom: 30px; padding-left: 20px; border: 1px solid #e1e1e1; border-radius: 4px;">
                            </div>


                            <div class="col-lg-12">
                                <h4 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #111; border-bottom: 2px solid #f1592a; padding-bottom: 8px;">2. Identitas Kontak Utama</h4>
                            </div>

                            <div class="col-lg-12">
                                <input type="text" name="name" placeholder="Nama Lengkap (sesuai KTP)" required style="border-radius: 4px; margin-bottom: 20px;">
                            </div>
                            <div class="col-lg-6">
                                <input type="email" name="email" placeholder="Alamat Email (untuk E-Ticket)" required style="border-radius: 4px; margin-bottom: 20px;">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" name="phone" placeholder="Nomor WhatsApp (untuk notifikasi)" required style="border-radius: 4px; margin-bottom: 30px;">
                            </div>

                            <div class="col-lg-12 text-center mt-4">
                                <button type="submit" class="site-btn" style="width: 100%; border-radius: 4px; font-size: 18px; padding: 15px 0;">Lanjutkan Pembayaran</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
