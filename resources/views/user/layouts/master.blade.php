<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Manup Template">
    <meta name="keywords" content="Manup, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manup | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600,700,800,900&display=swap"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">


    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}" type="text/css">
</head>

<body>
<!-- Header Section Begin -->
<header class="header-section">
    <div class="container">
        <div class="logo">
            <a href="./index.html">
                <img src="{{ asset('user/img/logo.png') }}" alt="">
            </a>
        </div>
        <div class="nav-menu">
            <nav class="mainmenu mobile-menu">
                <ul>
                    <li class="active"><a href="/home">Home</a></li>
                    <li><a href="./about-us.html">About</a></li>
                    <li><a href="/speaker">Speakers</a>
                        <ul class="dropdown">
                            <li><a href="#">Jayden</a></li>
                            <li><a href="#">Sara</a></li>
                            <li><a href="#">Emma</a></li>
                            <li><a href="#">Harriet</a></li>
                        </ul>
                    </li>
                    <li><a href="/schedule">Schedule</a></li>
                    <li><a href="./blog.html">Blog</a></li>
                    <li><a href="./contact.html">Contacts</a></li>
                </ul>
            </nav>
            @guest
                <a href="/user-login" class="primary-btn top-btn">Login / Register</a>
            @endguest

            @auth
                <form method="POST" action="{{ route('logout') }}" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="primary-btn top-btn" style="border: none; cursor: pointer;">Logout</button>
                </form>
            @endauth

            <a href="/user-profile" class="primary-btn top-btn ml-2">My Tickets</a>
        </div>
        <div id="mobile-menu-wrap"></div>
    </div>
</header>
<!-- Header End -->

<!-- Hero Section Begin -->
@yield('content')

<!-- Footer Section Begin -->
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
                        <a href="#" class="footer-logo"><img src="{{ asset('user/img/footer-logo.png') }}" alt=""></a>
                    </div>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Speakers</a></li>
                        <li><a href="#">Schedule</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                    <div class="copyright-text"><p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p></div>
                    <div class="ft-social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-youtube-play"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->

<!-- Js Plugins -->
<script src="{{ asset('user/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('user/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('user/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('user/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('user/js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('user/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('user/js/main.js') }}"></script>
</body>

</html>
