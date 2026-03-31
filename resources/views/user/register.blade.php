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
                    <form action="#" class="comment-form">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <input type="text" placeholder="Full Name" style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
                            </div>
                            <div class="col-lg-12 text-center">
                                <input type="email" placeholder="Email Address" style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
                            </div>
                            <div class="col-lg-12 text-center">
                                <input type="password" placeholder="Password" style="width: 100%; margin-bottom: 20px; padding: 15px; border: 1px solid #e1e1e1;">
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
