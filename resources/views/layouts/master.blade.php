<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MIFFA">

    <!-- ========== Page Title ========== -->
    <title>@yield('title', 'MIFFA')</title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="{{ asset('assets/img/new/logo-light.png') }}" type="image/x-icon">

    <!-- ========== Font Awesome CDN ========== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- ========== Stylesheets ========== -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/validnavs.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/unit-test.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    <!-- Start Preloader -->
    <div id="preloader">
        <div id="edufix-preloader" class="edufix-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="M" class="letters-loading"> M </span>
                    <span data-text-preloader="I" class="letters-loading"> I </span>
                    <span data-text-preloader="F" class="letters-loading"> F </span>
                    <span data-text-preloader="F" class="letters-loading"> F </span>
                    <span data-text-preloader="A" class="letters-loading"> A </span>
                </div>
            </div>
            <div class="loader">
                <div class="row">
                    <div class="col-3 loader-section section-left"><div class="bg"></div></div>
                    <div class="col-3 loader-section section-left"><div class="bg"></div></div>
                    <div class="col-3 loader-section section-right"><div class="bg"></div></div>
                    <div class="col-3 loader-section section-right"><div class="bg"></div></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Preloader -->

    <!-- Start Header Top -->
    <div class="top-bar-area top-bar-style-one bg-dark text-light">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-7">
                    <ul class="item-flex">
                        <li>
                            <a href="tel:+959400013099"> 
                                <img src="{{ asset('assets/img/icon/2.png') }}" alt="Icon"> Phone: +959400013099
                            </a>
                        </li>
                        <li>
                            <a href="mailto:miffa@org.com">
                                <img src="{{ asset('assets/img/icon/3.png') }}" alt="Icon"> Email: miffa@org.com
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-5 text-end">
                    <div class="item-flex">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('assets/img/icon/flag.png') }}" alt="Image Not Found">
                                English <i class="fas fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                              <li><a class="dropdown-item" href="#">Myanmar</a></li>
                            </ul>
                        </div>
                        <div>
                            @auth
                                <div class="d-inline-flex align-items-center gap-2">
                                    <a href="{{ route('student.dashboard') }}" class="text-light text-decoration-none d-inline-flex align-items-center gap-1">
                                        <img src="{{ asset('assets/img/icon/1.png') }}" alt="Icon"> {{ auth()->user()->name }}
                                    </a>
                                    <span class="text-light opacity-50">|</span>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline m-0 p-0">
                                        @csrf
                                        <button type="submit" class="bg-transparent border-0 text-light p-0 m-0 align-baseline" style="box-shadow: none; font-size: inherit;">
                                            <i class="fas fa-sign-out-alt ms-1"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ url('/login') }}" class="text-light text-decoration-none">
                                    <img src="{{ asset('assets/img/icon/1.png') }}" alt="Icon"> Login
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Header Top -->

    <!-- Header -->
    <header>
        <!-- Start Navigation -->
        <nav class="navbar mobile-sidenav navbar-sticky navbar-default validnavs dark navbar-fixed no-background inc-topbar">
            <div class="container d-flex justify-content-between align-items-center">            
                <!-- Start Header Navigation -->
                <div class="item-flex">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fas fa-bars" aria-hidden="true"></i>
                        </button>
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img src="{{ asset('assets/img/new/logo-light.png') }}" class="logo" alt="Logo">
                        </a>
                    </div>
                </div>
                <!-- End Header Navigation -->

                <div class="nav-item-box d-flex justify-content-between align-items-center">
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbar-menu">

                        <img src="{{ asset('assets/img/new/logo-light.png') }}" alt="Logo">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-times"></i>
                        </button>
                        
                        <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                            <li>
                                <a href="{{ url('/') }}">Home</a>
                            </li>

                            <!-- <li>
                                <a href="{{ url('/about') }}">About Us</a>
                            </li> -->

                            <!-- <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Academic Programs</a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/course/categories') }}">Diploma Programs</a></li>
                                    <li><a href="{{ url('/course/categories') }}">Short Courses</a></li>
                                    <li><a href="{{ url('/course/categories') }}">Online Modules</a></li>
                                </ul>
                            </li> -->

                            <li class="dropdown megamenu-fw megamenu-style-four" style="right: 0 !important;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Courses</a>
                                <ul class="dropdown-menu megamenu-content" role="menu">
                                    <li>
                                        <div class="col-menu-wrap">
                                            <div class="col-menu">
                                                <h6 class="title">Course Layout</h6>
                                                <div class="content">
                                                    <ul class="menu-col">
                                                        <li><a href="{{ url('/course/categories') }}">All Courses</a></li>
                                                        @auth
                                                            <li><a href="{{ url('/my-courses') }}">My Enrolled Courses</a></li>
                                                        @endauth
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <!-- <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Media & Events</a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Latest News</a></li>
                                    <li><a href="#">Events & Workshops</a></li>
                                    <li><a href="#">Photo Gallery</a></li>
                                </ul>
                            </li> -->

                            <!-- <li>
                                <a href="#">Contact Us</a>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>    
            <!-- Overlay screen for menu -->
            <div class="overlay-screen"></div>
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Header -->

    {{-- Main View Content --}}
    <main>
        @yield('content')
    </main>

    <!-- Start Footer -->
    <footer class="bg-dark footer-style-one text-light">
        <div class="footer-shape-style-one">
            <img src="{{ asset('assets/img/shape/2-light.png') }}" alt="Image Not Found">
        </div>
        <div class="container">
            <div class="f-items default-padding">
                <div class="row">
                    <div class="col-lg-4 col-md-6 footer-item pr-30 pr-md-15 pr-xs-15">
                        <div class="f-item about">
                            <div class="footer-logo">
                                <img src="{{ asset('assets/img/new/logo-light.png') }}" alt="Image Not Found">
                            </div>
                            <p>
                                Myanmar International Freight Forwarders' Association Education & Training Institute.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 footer-item">
                        <div class="f-item link">
                            <h4 class="widget-title">About</h4>
                            <ul>
                                <li><a href="{{ url('/about') }}">About Us</a></li>
                                <li><a href="{{ url('/course/categories') }}">Courses</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 footer-item">
                        <div class="f-item newsletter">
                            <h4 class="widget-title">Contact Info</h4>
                            <ul class="contact-list-two">
                                <li>
                                    <div class="icon"><i class="fas fa-phone-alt"></i></div>
                                    <div class="info"><h5><a href="tel:+959400013099">+959400013099</a></h5></div>
                                </li>
                                <li>
                                    <div class="icon"><i class="fas fa-envelope"></i></div>
                                    <div class="info"><h5><a href="mailto:miffa@org.com">miffa@org.com</a></h5></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Footer Bottom -->
        <div class="footer-bottom style-one">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <p>&copy; {{ date('Y') }} MIFFA. All Rights Reserved.</p>
                    </div>
                    <div class="col-lg-6 text-end">
                        <ul>
                            <li><a href="#">Terms of Use</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->
    </footer>
    <!-- End Footer -->
    
    <!-- ========== Scripts ========== -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/progress-bar.min.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/count-to.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/YTPlayer.min.js') }}"></script>
    <script src="{{ asset('assets/js/loopcounter.js') }}"></script>
    <script src="{{ asset('assets/js/validnavs.js') }}"></script>
    <script src="{{ asset('assets/js/gsap.js') }}"></script>
    <script src="{{ asset('assets/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('assets/js/SplitText.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>