@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad">
        <div class="container">
            <div class="col-lg-6 m-auto">
                <div class="section-title text-center">
                    <h2>Pengajuan Organizer</h2>
                    <p>Ajukan diri Anda untuk menjadi organizer event</p>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if($pending)
                    <div class="alert alert-warning text-center">
                        Permintaan Anda sedang diproses.
                    </div>
                @else
                    <form action="{{ route('organizer.request.store') }}" method="POST">
                        @csrf
                        <input type="text" name="organization_name"
                               placeholder="Nama organisasi (opsional)"
                               style="width:100%; height:50px; margin-bottom:15px;">
                        <textarea name="reason"
                                  placeholder="Alasan menjadi organizer..."
                                  style="width:100%; height:120px; margin-bottom:20px;"
                                  required></textarea>
                        <button type="submit" class="site-btn" style="width:100%;">Kirim Permintaan</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
@endsection
