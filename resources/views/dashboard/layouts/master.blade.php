<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="generator" content="MIFFA"/>
    <meta name="description" content="MIFFA"/>
    <title>@yield('title', 'MIFFA')</title>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Dashboard | MIFFA"/>
    <meta property="og:description" content="MIFFA"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="Dashboard | MIFFA"/>
    <meta name="twitter:description" content="MIFFA"/>
    <meta name="theme-color" content="#4272d7"/>
    <link href="css/font-face.css" rel="stylesheet" media="all"/>
    <link rel="preconnect" href="https://rsms.me/"/>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css"/>

    <link href="{{ asset('assets/vendor/fontawesome-7.2.0/css/all.min.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('assets/vendor/bootstrap-5.3.8.min.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('assets/vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ asset('assets/css/theme-2026.css') }}" rel="stylesheet" media="all"/>
<style>
  /* Hide theme switcher float menu / toggle panel */
  .theme-switcher, 
  .theme-config, 
  #theme-switcher,
  .switcher-wrapper {
      display: none !important;
  }
</style>
  </head>
  <body class="theme-2026"><a class="visually-hidden-focusable skip-link" href="#main-content">Skip to main content</a>
    <div class="page-wrapper">
      <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
          <div class="container-fluid">
            <div class="header-mobile-inner"><a class="logo" href="index.html"><img src="images/icon/logo.png" alt="CoolAdmin"></a>
            <button class="hamburger hamburger--slider" type="button" aria-label="Toggle navigation"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
          </div>
        </div>
      </div>
      <nav class="navbar-mobile">
        <div class="container-fluid">
          <ul class="navbar-mobile__list list-unstyled">
            <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-tachometer-alt"></i>Dashboard</a>
          </li>
          <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-user-gear"></i>New Features</a>
            <ul class="list-unstyled navbar__sub-list js-sub-list">
              <li><a href="/dashboard/students">Students</a></li>
              <li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
              <li><a href="{{ route('admin.course-categories.index') }}">Course Categories</a></li>
            </ul>
          </li>
</li>
</ul>
</div>
</nav>
</header>
<aside class="menu-sidebar" id="main-sidebar">
  <div class="logo"><a class="logo-link" href="index.html" aria-label="CoolAdmin home"><span class="logo-mark" aria-hidden="true">C</span><span class="logo-text">CoolAdmin</span></a>
  <button class="sidebar-close js-sidebar-toggle" type="button" aria-label="Close navigation"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
</div>
<div class="menu-sidebar__content js-scrollbar1">
  <nav class="navbar-sidebar">
    <ul class="list-unstyled navbar__list">
      <li><a href="/dashboard"><i class="fa-solid fa-tachometer-alt"></i>Dashboard</a></li>
      <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-user-gear"></i>Students</a>
      <ul class="list-unstyled navbar__sub-list js-sub-list">
        <li><a href="/dashboard/students">View</a></li>
      </ul>
    </li>
      <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-user-gear"></i>Categories</a>
      <ul class="list-unstyled navbar__sub-list js-sub-list">
        <li><a href="{{ route('admin.categories.index') }}">View</a></li>
        <li><a href="{{ route('admin.categories.create') }}">Create</a></li>
      </ul>
    </li>
    <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-user-gear"></i>Course Categories</a>
    <ul class="list-unstyled navbar__sub-list js-sub-list">
                <li><a href="{{ route('admin.course-categories.index') }}">View</a></li>
                <li><a href="{{ route('admin.course-categories.create') }}">Create</a></li>
              </ul>
            </li>
            <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-user-gear"></i>Course</a>
              <ul class="list-unstyled navbar__sub-list js-sub-list">
                <li><a href="{{ route('admin.courses.index') }}">View</a></li>
                <li><a href="{{ route('admin.courses.create') }}">Create</a></li>
              </ul>
            </li>
            <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-user-gear"></i>Instructor</a>
              <ul class="list-unstyled navbar__sub-list js-sub-list">
                <li><a href="{{ route('admin.instructors.index') }}">View</a></li>
                <li><a href="{{ route('admin.instructors.create') }}">Create</a></li>
              </ul>
            </li>
            </ul>
          </nav>
        </div>
      </aside>
      <div class="page-container">
        <header class="header-desktop">
          <div class="section__content section__content--p30">
            <div class="container-fluid">
              <div class="header-wrap">
                <button class="sidebar-toggle js-sidebar-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="main-sidebar"><i class="fa-solid fa-bars" aria-hidden="true"></i></button>

                <div class="header-button">
                  <div class="noti-wrap">
                    <!-- Notifications/Messages menu items -->
                  </div>

                  <!-- Authenticated User Menu -->
                  <div class="account-wrap">
                    <div class="account-item clearfix js-item-menu" role="button" tabindex="0" aria-haspopup="true" aria-label="Account menu">
                      <div class="image">
                        <img src="{{  asset('images/icon/avatar-01.jpg') }}" alt="">
                      </div>
                      <div class="content">
                        <a class="js-acc-btn" href="#"></a>
                      </div>
                      <div class="account-dropdown js-dropdown">
                        <div class="info clearfix">
                          <div class="image">
                            <a href="#">
                              <img src="{{ asset('images/icon/avatar-01.jpg') }}" alt="">
                            </a>
                          </div>
                          <div class="content">
                            <h5 class="name"><a href="#"></a></h5>
                            <span class="email"></span>
                          </div>
                        </div>
                        <div class="account-dropdown__body">
                          <div class="account-dropdown__item"><a href="#"><i class="fa-solid fa-user"></i>Account</a></div>
                          <div class="account-dropdown__item"><a href="#"><i class="fa-solid fa-gear"></i>Setting</a></div>
                        </div>
                        <div class="account-dropdown__footer">
                          <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn text-danger ps-3"><i class="fa-solid fa-power-off me-2"></i>Logout</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </header>
      <main>

        @yield('content')
    </main>
    </div>
     </div>
    <script src="{{ asset('assets/js/vanilla-utils.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-5.3.8.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chartjs/chart.umd.js-4.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap5-init.js') }}"></script>
    <script src="{{ asset('assets/js/main-vanilla.js') }}"></script>
    <script src="{{ asset('assets/js/modern-plugins.js') }}"></script>
  </body>
</html>