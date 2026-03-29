@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 m-auto">
                    <div class="section-title text-center">
                        <h2>Checkout Tiket</h2>
                        <p>Silakan pilih jenis tiket dan lengkapi data diri Anda.</p>
                    </div>
                    <form action="#" class="comment-form">
                        <div class="row">
                            <div class="col-lg-6">
                                <select style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1; color: #666666; font-size: 16px;">
                                    <option value="">-- Pilih Jenis Tiket --</option>
                                    <option value="regular">Regular - Rp 100.000</option>
                                    <option value="vip">VIP - Rp 250.000</option>
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <input type="number" placeholder="Jumlah Tiket" min="1" value="1" style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1;">
                            </div>

                            <div class="col-lg-12">
                                <input type="text" placeholder="Nama Lengkap Pemesan">
                            </div>
                            <div class="col-lg-6">
                                <input type="email" placeholder="Alamat Email">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Nomor WhatsApp">
                            </div>

                            <div class="col-lg-12 text-center mt-3">
                                <button type="submit" class="site-btn" style="width: 100%;">Lanjutkan Pembayaran</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
