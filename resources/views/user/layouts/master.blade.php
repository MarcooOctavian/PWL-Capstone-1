<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Tickify Template">
    <meta name="keywords" content="Tickify, event, ticket, booking">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tickify</title>

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600,700,800,900&display=swap"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}" type="text/css">
</head>
@vite(['resources/js/app.js'])
<body>
<header class="header-section">
    <div class="container">
        <div class="logo">
            <a href="/" style="font-size: 28px; font-weight: 800; color: #111; letter-spacing: 1px; text-transform: uppercase;">
                Tickify<span style="color: #f1592a;">.</span>
            </a>
        </div>
        <div class="nav-menu">
            <nav class="mainmenu mobile-menu">
                <ul>
                    <li class="active"><a href="/">Home</a></li>
                    <li><a href="{{ route('events.public') }}">Events</a></li>
                </ul>
            </nav>
            @guest
                <a href="{{ route('login') }}" class="primary-btn top-btn">Login / Register</a>
            @endguest

            @auth
                <form method="POST" action="{{ route('logout') }}" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="primary-btn top-btn" style="border: none; cursor: pointer;">Logout</button>
                </form>
            @endauth

            @auth
                <a href="/user-profile" class="primary-btn top-btn ml-2">My Tickets</a>
            @endauth
        </div>
        <div id="mobile-menu-wrap"></div>
    </div>
</header>
@yield('content')

<footer class="footer-section">
    <div class="container">
        <div class="partner-logo owl-carousel">
            <a href="#" class="pl-table">
                <div class="pl-tablecell">
                    <img src="{{ asset('user/img/partner-logo/logo-1.png') }}" alt="">
                </div>
            </a>
            <a href="#" class="pl-table">
                <div class="pl-tablecell">
                    <img src="{{ asset('user/img/partner-logo/logo-2.png') }}" alt="">
                </div>
            </a>
            <a href="#" class="pl-table">
                <div class="pl-tablecell">
                    <img src="{{ asset('user/img/partner-logo/logo-3.png') }}" alt="">
                </div>
            </a>
            <a href="#" class="pl-table">
                <div class="pl-tablecell">
                    <img src="{{ asset('user/img/partner-logo/logo-4.png') }}" alt="">
                </div>
            </a>
            <a href="#" class="pl-table">
                <div class="pl-tablecell">
                    <img src="{{ asset('user/img/partner-logo/logo-5.png') }}" alt="">
                </div>
            </a>
            <a href="#" class="pl-table">
                <div class="pl-tablecell">
                    <img src="{{ asset('user/img/partner-logo/logo-6.png') }}" alt="">
                </div>
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="footer-text">
                    <div class="ft-logo">
                        <a href="/" style="font-size: 32px; font-weight: 800; color: #fff; letter-spacing: 1px; text-transform: uppercase;">
                            Tickify<span style="color: #f1592a;">.</span>
                        </a>
                    </div>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="{{ route('events.public') }}">Events</a></li>
                    </ul>
                    <div class="copyright-text">
                        <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Created by Richard & Team</p>
                    </div>
                    <div class="ft-social">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<script src="{{ asset('user/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('user/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('user/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('user/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('user/js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('user/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('user/js/main.js') }}"></script>
</body>

</html>
