@extends('user.layouts.master')

@section('content')
    <section class="hero-section set-bg" data-setbg="{{ asset('user/img/hero.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="hero-text">
                        <span>5 to 9 may 2019, mardavall hotel, New York</span>
                        <h2>Change Your Mind<br /> To Become Sucess</h2>
                        <a href="#" class="primary-btn">Buy Ticket</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img src="{{ asset('user/img/hero-right.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>
    <section class="counter-section bg-gradient">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="counter-text">
                        <span>Conference Date</span>
                        <h3>Count Every Second <br />Until the Event</h3>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="cd-timer" id="countdown">
                        <div class="cd-item">
                            <span>40</span>
                            <p>Days</p>
                        </div>
                        <div class="cd-item">
                            <span>18</span>
                            <p>Hours</p>
                        </div>
                        <div class="cd-item">
                            <span>46</span>
                            <p>Minutes</p>
                        </div>
                        <div class="cd-item">
                            <span>32</span>
                            <p>Seconds</p>
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
                        <img src="{{ asset('user/img/h-about.jpg') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ha-text">
                        <h2>About Conference</h2>
                        <p>When I first got into the online advertising business, I was looking for the magical
                            combination that would put my website into the top search engine rankings, catapult me to
                            the forefront of the minds or individuals looking to buy my product, and generally make me
                            rich beyond my wildest dreams!</p>
                        <ul>
                            <li><span class="icon_check"></span> Write On Your Business Card</li>
                            <li><span class="icon_check"></span> Advertising Outdoors</li>
                            <li><span class="icon_check"></span> Effective Advertising Pointers</li>
                        </ul>
                        <a href="#" class="ha-btn">Discover Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="team-member-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Who’s speaking</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="member-item set-bg" data-setbg="{{ asset('user/img/team-member/member-1.jpg') }}">
            <div class="mi-text">
                <h5>Emma Sandoval</h5>
                <span>Speaker</span>
            </div>
        </div>
    </section>
@endsection
