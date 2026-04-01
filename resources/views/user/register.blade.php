@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="section-title text-center">
                        <h2>Register</h2>
                        <p>Create an account to buy your event tickets.</p>
                    </div>
                    <form action="{{ route('register') }}" method="POST" class="comment-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <input type="text" name="name" placeholder="Full Name" required style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
                            </div>
                            <div class="col-lg-12 text-center">
                                <input type="email" name="email" placeholder="Email Address" required style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
                            </div>
                            <div class="col-lg-12 text-center">
                                <input type="password" name="password" placeholder="Password" required style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
                            </div>
                            <div class="col-lg-12 text-center">
                                <input type="password" name="password_confirmation" placeholder="Confirm Password" required style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
                            </div>
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="site-btn" style="width: 100%;">Register</button>
                                <p class="mt-4">Sudah punya akun? <a href="/user-login" style="color: #f1592a; font-weight: bold;">Login di sini</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
