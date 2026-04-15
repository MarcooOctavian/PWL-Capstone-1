@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    <div class="card" style="border: 1px solid #e1e1e1; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                        <div class="card-header text-center" style="background-color: #f8f9fa; padding: 20px; border-bottom: 1px solid #e1e1e1; border-radius: 8px 8px 0 0;">
                            <h3 style="margin-bottom: 0; color: #111;">Selesaikan Pembayaran</h3>
                            <p style="margin-top: 5px; color: #666; font-size: 14px;">Waktu Anda: <b id="countdown-timer" style="color: #dc3545;">05:00</b></p>
                        </div>

                        <div class="card-body" style="padding: 30px;">
                            <div style="background-color: #f4f6f9; padding: 15px; border-radius: 6px; margin-bottom: 25px;">
                                <h5 style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">Detail Pesanan:</h5>
                                <p style="margin-bottom: 5px;"><strong>Tiket:</strong> {{ $checkoutData['ticket_name'] }} ({{ $checkoutData['qty'] }}x)</p>
                                <p style="margin-bottom: 5px;"><strong>Nama:</strong> {{ $checkoutData['name'] }}</p>
                                <p style="margin-bottom: 5px;"><strong>Total Tagihan:</strong> <span style="font-size: 18px; font-weight: bold; color: #28a745;">Rp {{ number_format($checkoutData['total_amount'], 0, ',', '.') }}</span></p>
                            </div>

                            <div class="text-center" style="margin-bottom: 30px;">
                                @if($checkoutData['payment_method'] == 'qris')
                                    <h4 style="color: #111; margin-bottom: 15px;">Scan QRIS di Bawah Ini</h4>
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS Dummy" style="width: 200px; height: 200px; border: 2px solid #eee; padding: 10px; border-radius: 8px;">
                                    <p style="margin-top: 15px; font-size: 14px; color: #666;">Buka aplikasi e-wallet Anda (Gopay/OVO/Dana) lalu scan barcode di atas.</p>

                                @elseif($checkoutData['payment_method'] == 'ovo' || $checkoutData['payment_method'] == 'dana')
                                    <h4 style="color: #111; margin-bottom: 15px;">Pembayaran via {{ strtoupper($checkoutData['payment_method']) }}</h4>
                                    <div style="font-size: 50px; color: #007bff; margin-bottom: 15px;">
                                        <i class="fa fa-mobile"></i>
                                    </div>
                                    <p style="font-size: 15px; color: #333;">Silakan cek aplikasi {{ strtoupper($checkoutData['payment_method']) }} di nomor <b>{{ $checkoutData['payment_credential'] }}</b> untuk menyetujui pembayaran.</p>

                                @elseif($checkoutData['payment_method'] == 'credit_card')
                                    <h4 style="color: #111; margin-bottom: 15px;">Verifikasi Kartu Kredit</h4>
                                    <div style="font-size: 50px; color: #6c757d; margin-bottom: 15px;">
                                        <i class="fa fa-credit-card"></i>
                                    </div>
                                    <p style="font-size: 15px; color: #333;">Memproses kartu dengan nomor berakhiran: <b>...{{ substr($checkoutData['payment_credential'], -4) }}</b></p>
                                @endif
                            </div>

                            <hr style="border-top: 1px dashed #ccc;">

                            <div class="text-center mt-4">
                                <p style="font-size: 12px; color: #999; margin-bottom: 15px;">*Pastikan Anda sudah melakukan transfer sebelum menekan tombol di bawah.</p>

                                <form id="payment-form" action="{{ route('checkout.payment.process', $transaction->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="site-btn" style="width: 100%; border-radius: 4px; font-size: 16px; padding: 15px 0; background-color: #28a745; border: none; color: white; cursor: pointer; transition: 0.3s;">
                                        Simulasikan Pembayaran Berhasil
                                    </button>
                                </form>
                                <form id="payment-fail-form" action="{{ route('checkout.payment.fail', $transaction->id) }}" method="POST" style="margin-top: 15px;">
                                    @csrf
                                    <button type="submit" class="site-btn" style="width: 100%; border-radius: 4px; font-size: 16px; padding: 15px 0; background-color: #dc3545; border: none; color: white; cursor: pointer; transition: 0.3s;">
                                        Simulasikan Timeout/Gagal
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        // Set waktu 5 menit
        let timeInSeconds = 5 * 60;
        let display = document.getElementById('countdown-timer');

        let timer = setInterval(function () {
            let minutes = parseInt(timeInSeconds / 60, 10);
            let seconds = parseInt(timeInSeconds % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timeInSeconds < 0) {
                clearInterval(timer);
                alert("Waktu pembayaran Anda telah habis. Transaksi dibatalkan secara otomatis.");
                document.getElementById('payment-fail-form').submit();
            }
        }, 1000);
    </script>
@endsection
