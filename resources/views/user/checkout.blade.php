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

                    @if ($errors->any())
                        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <strong>Transaksi Gagal!</strong>
                            <ul style="margin-top: 10px; margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <strong>Perhatian!</strong> {{ session('error') }}
                        </div>
                    @endif

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

                            <div class="col-lg-6">
                                <input type="text" name="name" placeholder="Nama Lengkap (sesuai KTP)" required style="border-radius: 4px; margin-bottom: 20px;">
                            </div>
                            <div class="col-lg-6">
                                <input type="email" name="email" placeholder="Alamat Email (untuk E-Ticket)" required style="border-radius: 4px; margin-bottom: 30px;">
                            </div>

                            <div class="col-lg-12">
                                <h4 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #111; border-bottom: 2px solid #f1592a; padding-bottom: 8px;">3. Metode Pembayaran</h4>
                            </div>

                            <div class="col-lg-12">
                                <select name="payment_method" id="payment_method" required onchange="togglePaymentInput()" style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1; color: #666666; font-size: 16px; border-radius: 4px;">
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="qris">QRIS (Scan Barcode Umum)</option>
                                    <option value="ovo">OVO</option>
                                    <option value="dana">DANA</option>
                                    <option value="credit_card">Kartu Kredit / Debit</option>
                                </select>
                            </div>

                            <div class="col-lg-12" id="dynamic_payment_input" style="display: none;">
                                <input type="text" name="payment_credential" id="payment_credential" placeholder="" style="border-radius: 4px; margin-bottom: 20px; width: 100%;">
                            </div>

                            <div class="col-lg-12 text-center mt-4">
                                <button type="submit" class="site-btn" style="width: 100%; border-radius: 4px; font-size: 18px; padding: 15px 0;">Lanjutkan ke Verifikasi</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function togglePaymentInput() {
            var method = document.getElementById('payment_method').value;
            var inputContainer = document.getElementById('dynamic_payment_input');
            var inputField = document.getElementById('payment_credential');

            if (method === 'ovo' || method === 'dana') {
                inputContainer.style.display = 'block';
                inputField.placeholder = "Masukkan Nomor " + method.toUpperCase() + " Anda (Contoh: 0812...)";
                inputField.required = true;
            }
            else if (method === 'qris') {
                inputContainer.style.display = 'block';
                inputField.placeholder = "Masukkan Nomor WhatsApp (Untuk Bukti QRIS)";
                inputField.required = true;
            }
            else if (method === 'credit_card') {
                inputContainer.style.display = 'block';
                inputField.placeholder = "Masukkan Nomor Kartu Kredit (Simulasi: 4111-2222-...)";
                inputField.required = true;
            }
            else {
                inputContainer.style.display = 'none';
                inputField.required = false;
            }
        }
    </script>
@endsection
