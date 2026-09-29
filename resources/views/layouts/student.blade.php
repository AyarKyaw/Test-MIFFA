<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Student Dashboard | MIFFA ACADEMY')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="shortcut icon"
          href="{{ asset('assets/img/icon/miffas.png') }}"
          type="image/x-icon">

    <style>
        :root {
            --miffa-primary: #0b3281;
            --miffa-secondary: #ff7a00;
            --miffa-bg: #f4f7f9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--miffa-bg);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            margin: 0;
        }

        /* =====================================================
           DESKTOP SIDEBAR
        ===================================================== */

        .sidebar {
            min-height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
        }

        .sidebar-brand {
            color: var(--miffa-primary);
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        .nav-link-custom {
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }

        .nav-link-custom:hover {
            color: var(--miffa-primary);
            background-color: #eef3ff;
        }

        .nav-link-custom.active {
            color: var(--miffa-primary);
            background-color: #eef3ff;
            font-weight: 700;
        }

        /* =====================================================
           USER AVATAR
        ===================================================== */

        .user-avatar {
            width: 38px;
            height: 38px;
            min-width: 38px;
            background-color: var(--miffa-primary);
            color: white;
            font-weight: 600;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* =====================================================
           MAIN CONTENT
        ===================================================== */

        .main-content {
            min-height: 100vh;
        }

        /* =====================================================
           MOBILE BOTTOM NAV
        ===================================================== */

        .mobile-bottom-nav {
            display: none;
        }

        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 767.98px) {

            body {
                padding-bottom: 72px;
                overflow-x: hidden;
            }

            /* Hide desktop sidebar */
            .desktop-sidebar {
                display: none !important;
            }

            /* Full width content */
            .main-content {
                width: 100%;
                min-height: 100vh;
            }

            /* Header */
            .top-header {
                padding: 0.75rem 1rem !important;
            }

            .dashboard-title {
                font-size: 1rem;
            }

            /* Main page */
            .page-main {
                padding: 1rem !important;
            }

            /* Hide desktop user information */
            .desktop-user-info {
                display: none !important;
            }

            /* Bottom navigation */
            .mobile-bottom-nav {
                position: fixed;
                display: flex;
                left: 0;
                right: 0;
                bottom: 0;
                height: 68px;
                background: #ffffff;
                border-top: 1px solid #e9ecef;
                z-index: 1050;
                box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.06);
                padding: 6px 8px;
            }

            .mobile-bottom-nav a,
            .mobile-bottom-nav button {
                flex: 1;
                min-width: 0;
                border: 0;
                background: transparent;
                text-decoration: none;
                color: #8a8f98;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 3px;
                font-size: 0.68rem;
                font-weight: 500;
                border-radius: 10px;
                transition: all 0.2s ease;
            }

            .mobile-bottom-nav i {
                font-size: 1.15rem;
                line-height: 1;
            }

            .mobile-bottom-nav .bottom-nav-active {
                color: var(--miffa-primary);
                font-weight: 700;
            }

            .mobile-bottom-nav .bottom-nav-active i {
                transform: translateY(-1px);
            }

            .mobile-bottom-nav a:hover,
            .mobile-bottom-nav button:hover {
                color: var(--miffa-primary);
            }

            /* Profile item */
            .mobile-profile-icon {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: var(--miffa-primary);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.7rem;
                font-weight: 700;
            }
        }

        @media (max-width: 575.98px) {

            .top-header {
                padding: 0.65rem 0.75rem !important;
            }

            .page-main {
                padding: 0.75rem !important;
            }

            .dashboard-title {
                font-size: 0.95rem;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
                min-width: 35px;
            }

            .mobile-bottom-nav {
                height: 64px;
            }

            .mobile-bottom-nav a,
            .mobile-bottom-nav button {
                font-size: 0.63rem;
            }

            .mobile-bottom-nav i {
                font-size: 1.05rem;
            }
        }

        .mobile-main-site-link {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #eef3ff;
    color: var(--miffa-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 0.8rem;
    transition: all 0.2s ease;
}

.mobile-main-site-link:hover {
    background: var(--miffa-primary);
    color: #ffffff;
}
    </style>

    @stack('styles')
</head>

<body>

<div class="container-fluid p-0">

    <div class="row g-0">

        <!-- =====================================================
             DESKTOP SIDEBAR
        ====================================================== -->

        <aside class="col-md-3 col-xl-2 sidebar p-3 d-flex flex-column justify-content-between desktop-sidebar">

            <div>

                <!-- Brand -->
                <div class="d-flex align-items-center justify-content-between mb-4 px-2">

                    <a href="{{ url('/') }}"
                       class="sidebar-brand text-decoration-none">

                        🎓 MIFFA ACADEMY

                    </a>

                </div>

                <!-- Navigation -->
                <nav class="nav flex-column gap-1">

                    {{-- Dashboard --}}
                    <a href="{{ route('student.dashboard') }}"
                       class="nav-link-custom {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">

                        <i class="fas fa-th-large"></i>

                        <span>Dashboard</span>

                    </a>

                    {{-- My Courses --}}
                    <a href="{{ route('student.dashboard.courses') }}"
                       class="nav-link-custom {{ request()->routeIs('student.dashboard.courses') || request()->routeIs('courses.learn') || request()->routeIs('courses.show') ? 'active' : '' }}">

                        <i class="fas fa-book"></i>

                        <span>My Courses</span>

                    </a>

                    {{-- My Homeworks --}}
                    <a href="{{ route('student.dashboard.homework') }}"
                       class="nav-link-custom {{ request()->routeIs('student.dashboard.homework') ? 'active' : '' }}">

                        <i class="fas fa-folder-open"></i>

                        <span>My Homeworks</span>

                    </a>

                </nav>

            </div>


            <!-- Bottom Desktop Navigation -->
            <div class="pt-3 border-top d-flex flex-column gap-1">

                <a href="{{ url('/') }}"
                   class="nav-link-custom text-secondary">

                    <i class="fas fa-globe"></i>

                    <span>Back to Main Site</span>

                </a>

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button type="submit"
                            class="nav-link-custom w-100 border-0 bg-transparent text-danger">

                        <i class="fas fa-sign-out-alt"></i>

                        <span>Logout</span>

                    </button>

                </form>

            </div>

        </aside>


        <!-- =====================================================
             MAIN CONTENT
        ====================================================== -->

        <div class="col-12 col-md-9 col-xl-10 main-content">

            <!-- =================================================
                 TOP HEADER
            ================================================== -->

            <header class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center top-header">

                <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-bold mb-0 text-dark dashboard-title">
                        Student Dashboard
                    </h5>

                    {{-- Mobile: Back to Main Site --}}
                    <a href="{{ url('/') }}"
                    class="mobile-main-site-link d-md-none"
                    title="Back to Main Site">

                        <i class="fas fa-arrow-up-right-from-square"></i>

                    </a>

                </div>


                <div class="d-flex align-items-center gap-3">

                    <!-- Exit Dashboard -->
                    <a href="{{ url('/') }}"
                       class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-2">

                        <i class="fas fa-arrow-left"></i>

                        <span>Exit Dashboard</span>

                    </a>

                    <div class="vr d-none d-md-block my-1"></div>


                    <!-- User Info -->
                    <div class="text-end desktop-user-info d-none d-sm-block">

                        <div class="fw-bold text-dark fs-6">

                            {{ auth()->user()->name ?? 'Student Account' }}

                        </div>

                        <small class="text-muted">

                            {{ auth()->user()->email ?? 'student@miffa.com' }}

                        </small>

                    </div>


                    <!-- Avatar -->
                    <div class="user-avatar">

                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}

                    </div>

                </div>

            </header>


            <!-- =================================================
                 PAGE CONTENT
            ================================================== -->

            <main class="p-4 page-main">

                @yield('content')

            </main>

        </div>

    </div>

</div>


<!-- =========================================================
     MOBILE BOTTOM NAVIGATION
========================================================== -->

<nav class="mobile-bottom-nav">

    {{-- Dashboard --}}
    <a href="{{ route('student.dashboard') }}"
       class="{{ request()->routeIs('student.dashboard') ? 'bottom-nav-active' : '' }}">

        <i class="fas fa-home"></i>

        <span>Home</span>

    </a>


    {{-- Courses --}}
    <a href="{{ route('student.dashboard.courses') }}"
       class="{{ request()->routeIs('student.dashboard.courses') || request()->routeIs('courses.learn') || request()->routeIs('courses.show') ? 'bottom-nav-active' : '' }}">

        <i class="fas fa-book"></i>

        <span>Courses</span>

    </a>


    {{-- Homework --}}
    <a href="{{ route('student.dashboard.homework') }}"
       class="{{ request()->routeIs('student.dashboard.homework') ? 'bottom-nav-active' : '' }}">

        <i class="fas fa-clipboard-list"></i>

        <span>Homework</span>

    </a>


    {{-- Logout --}}
    <form method="POST"
          action="{{ route('logout') }}"
          class="d-flex flex-fill">

        @csrf

        <button type="submit">

            <i class="fas fa-sign-out-alt"></i>

            <span>Logout</span>

        </button>

    </form>

</nav>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>