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
                    <form action="{{ route('checkout.store') }}" method="POST" class="comment-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <select name="type_ticket_id" style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1; color: #666666; font-size: 16px;">
                                    <option value="">-- Pilih Jenis Tiket --</option>
                                    @foreach($typeTickets as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} - Rp {{ number_format($type->price, 0, ',', '.') }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <input type="number" name="qty" placeholder="Jumlah Tiket" min="1" value="1" style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1;">
                            </div>

                            <div class="col-lg-12">
                                <input type="text" name="name" placeholder="Nama Lengkap Pemesan">
                            </div>
                            <div class="col-lg-6">
                                <input type="email" name="email" placeholder="Alamat Email">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" name="whatsapp" placeholder="Nomor WhatsApp">
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
