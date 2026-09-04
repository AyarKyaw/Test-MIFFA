<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Student Dashboard | MIFFA ACADEMY')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --miffa-primary: #0b3281;
            --miffa-secondary: #ff7a00;
            --miffa-bg: #f4f7f9;
        }

        body {
            background-color: var(--miffa-bg);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

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

        .user-avatar {
            width: 38px;
            height: 38px;
            background-color: var(--miffa-primary);
            color: white;
            font-weight: 600;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <!-- Sidebar Navigation -->
        <aside class="col-12 col-md-3 col-xl-2 sidebar p-3 d-flex flex-column justify-content-between">
            <div>
                <!-- Brand Logo -->
                <div class="d-flex align-items-center justify-content-between mb-4 px-2">
                    <a href="{{ url('/') }}" class="sidebar-brand text-decoration-none">
                        🎓 MIFFA ACADEMY
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="nav flex-column gap-1">
                    <a href="{{ route('student.dashboard') }}" class="nav-link-custom {{ request()->routeIs('student.dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="nav-link-custom {{ request()->routeIs('courses*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>My Courses</span>
                    </a>
                    <a href="#" class="nav-link-custom {{ request()->routeIs('schedule*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Schedule</span>
                    </a>
                    <a href="#" class="nav-link-custom {{ request()->routeIs('resources*') ? 'active' : '' }}">
                        <i class="fas fa-folder-open"></i>
                        <span>Resources</span>
                    </a>
                </nav>
            </div>

            <!-- Profile, Exit Site & Logout Links at Sidebar Bottom -->
            <div class="pt-3 border-top d-flex flex-column gap-1">
                <!-- Return to Public Web Page -->
                <a href="{{ url('/') }}" class="nav-link-custom text-secondary">
                    <i class="fas fa-globe"></i>
                    <span>Back to Main Site</span>
                </a>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link-custom w-100 border-0 bg-transparent text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="col-12 col-md-9 col-xl-10 p-0">
            
            <!-- Top Navbar -->
            <header class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="fw-bold mb-0 text-dark">Student Dashboard</h5>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Quick Exit Button -->
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Exit Dashboard</span>
                    </a>

                    <div class="vr d-none d-md-block my-1"></div>

                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark fs-6">{{ auth()->user()->name ?? 'Student Account' }}</div>
                        <small class="text-muted">{{ auth()->user()->email ?? 'student@miffa.com' }}</small>
                    </div>
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                    </div>
                </div>
            </header>

            <!-- Page View Body -->
            <main class="p-4">
                @yield('content')
            </main>

        </div>

    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>