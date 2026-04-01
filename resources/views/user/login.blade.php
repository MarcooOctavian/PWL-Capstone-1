@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="section-title text-center">
                        <h2>Login</h2>
                        <p>Welcome back! Please enter your details.</p>
                    </div>

                    {{-- Tampilkan pesan error jika login gagal --}}
                    @if ($errors->any())
                        <div class="alert alert-danger" style="color: red; text-align: center; margin-bottom: 20px;">
                            Email atau Password salah.
                        </div>
                    @endif

                    {{-- Ubah action dan tambahkan method POST --}}
                    <form action="{{ route('login') }}" method="POST" class="comment-form">
                        {{-- WAJIB tambahkan @csrf --}}
                        @csrf

                        <div class="row">
                            <div class="col-lg-12 text-center">
                                {{-- Tambahkan name="email" --}}
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
                            </div>
                            <div class="col-lg-12 text-center">
                                {{-- Tambahkan name="password" --}}
                                <input type="password" name="password" placeholder="Password" required style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
                            </div>
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="site-btn" style="width: 100%;">Login</button>
                                <p class="mt-4">Belum punya akun? <a href="/user-register" style="color: #f1592a; font-weight: bold;">Register di sini</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
