<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Teacher Dashboard - MIFFA</title>

    <link rel="shortcut icon"
          href="{{ asset('assets/img/new/logo-light.png') }}"
          type="image/x-icon">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('style.css') }}" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .teacher-sidebar {
            width: 260px;
            min-height: 100vh;
            background: #111827;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding: 25px 15px;
        }

        .teacher-logo {
            padding: 10px 15px 30px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            margin-bottom: 20px;
        }

        .teacher-logo img {
            max-width: 130px;
        }

        .teacher-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            padding: 13px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            text-decoration: none;
            transition: .2s;
        }

        .teacher-menu a:hover,
        .teacher-menu a.active {
            background: #2563eb;
            color: #fff;
        }

        .teacher-menu i {
            width: 20px;
            text-align: center;
        }

        .teacher-main {
            margin-left: 260px;
            min-height: 100vh;
        }

        .teacher-topbar {
            height: 75px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
        }

        .teacher-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .teacher-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
        }

        .teacher-content {
            padding: 35px;
        }

        .welcome-card {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            border-radius: 14px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .welcome-card h2 {
            margin-bottom: 8px;
            color: #fff;
        }

        .welcome-card p {
            margin: 0;
            color: rgba(255,255,255,.85);
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 22px;
            border: 1px solid #e5e7eb;
            height: 100%;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #2563eb;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 28px;
            color: #111827;
        }

        .stat-card p {
            margin: 5px 0 0;
            color: #6b7280;
        }

        .dashboard-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .dashboard-card-header {
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-card-header h5 {
            margin: 0;
        }

        .course-item {
            padding: 18px 22px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .course-item:last-child {
            border-bottom: 0;
        }

        .course-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .course-image {
            width: 65px;
            height: 50px;
            object-fit: cover;
            border-radius: 7px;
            background: #e5e7eb;
        }

        .course-info h6 {
            margin: 0 0 4px;
        }

        .course-info small {
            color: #6b7280;
        }

        .logout-btn {
            border: 0;
            background: transparent;
            color: #cbd5e1;
            width: 100%;
            text-align: left;
            padding: 13px 15px;
            border-radius: 8px;
        }

        .logout-btn:hover {
            background: #dc2626;
            color: #fff;
        }

        @media (max-width: 991px) {
            .teacher-sidebar {
                width: 220px;
            }

            .teacher-main {
                margin-left: 220px;
            }
        }

        @media (max-width: 767px) {
            .teacher-sidebar {
                display: none;
            }

            .teacher-main {
                margin-left: 0;
            }

            .teacher-content {
                padding: 20px 15px;
            }

            .teacher-topbar {
                padding: 0 15px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="teacher-sidebar">

        <div class="teacher-logo">
            <a href="{{ route('teacher.dashboard') }}">
                <img src="{{ asset('assets/img/new/logo-light.png') }}"
                     alt="MIFFA">
            </a>
        </div>

        <nav class="teacher-menu">

            <a href="{{ route('teacher.dashboard') }}" class="active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>

            <a href="#">
                <i class="fas fa-book"></i>
                <span>My Courses</span>
            </a>

            <a href="#">
                <i class="fas fa-video"></i>
                <span>Lessons</span>
            </a>

            <a href="#">
                <i class="fas fa-question-circle"></i>
                <span>Quizzes</span>
            </a>

            <a href="#">
                <i class="fas fa-users"></i>
                <span>Students</span>
            </a>

            <a href="#">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>

            <hr style="border-color: rgba(255,255,255,.1);">

            <form action="{{ route('teacher.logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </button>
            </form>

        </nav>

    </aside>


    <!-- Main -->
    <main class="teacher-main">

        <!-- Topbar -->
        <header class="teacher-topbar">

            <div>
                <strong>Teacher Dashboard</strong>
            </div>

            <div class="teacher-profile">

                @if(auth('teacher')->user()->image)
                    <img
                        src="{{ asset('storage/' . auth('teacher')->user()->image) }}"
                        class="teacher-avatar"
                        alt="{{ auth('teacher')->user()->name }}"
                    >
                @else
                    <div class="teacher-avatar d-flex align-items-center justify-content-center">
                        <i class="fas fa-user text-secondary"></i>
                    </div>
                @endif

                <div>
                    <strong>{{ auth('teacher')->user()->name }}</strong>
                    <small class="d-block text-muted">Teacher</small>
                </div>

            </div>

        </header>


        <!-- Content -->
        <div class="teacher-content">

            <!-- Welcome -->
            <div class="welcome-card">

                <h2>
                    Welcome back, {{ auth('teacher')->user()->name }}!
                </h2>

                <p>
                    Manage your courses, lessons, quizzes and students from here.
                </p>

            </div>


            <!-- Statistics -->
            <div class="row g-4 mb-4">

                <div class="col-xl-3 col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="fas fa-book"></i>
                        </div>

                        <h3>{{ $courseCount ?? 0 }}</h3>

                        <p>My Courses</p>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>

                        <h3>{{ $studentCount ?? 0 }}</h3>

                        <p>Total Students</p>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>

                        <h3>{{ $lessonCount ?? 0 }}</h3>

                        <p>Total Lessons</p>

                    </div>

                </div>


                <div class="col-xl-3 col-md-6">

                    <div class="stat-card">

                        <div class="stat-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>

                        <h3>{{ $quizCount ?? 0 }}</h3>

                        <p>Total Quizzes</p>

                    </div>

                </div>

            </div>


            <!-- Courses -->
            <div class="row g-4">

                <div class="col-lg-8">

                    <div class="dashboard-card">

                        <div class="dashboard-card-header">

                            <h5>My Courses</h5>

                            <a href="#" class="btn btn-sm btn-primary">
                                View All
                            </a>

                        </div>


                        @forelse($courses ?? [] as $course)

                            <div class="course-item">

                                <div class="course-info">

                                    @if($course->image)
                                        <img
                                            src="{{ asset('storage/' . $course->image) }}"
                                            class="course-image"
                                            alt="{{ $course->title }}"
                                        >
                                    @else
                                        <div class="course-image d-flex align-items-center justify-content-center">
                                            <i class="fas fa-book text-muted"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h6>{{ $course->title }}</h6>

                                        <small>
                                            {{ $course->students_count ?? 0 }} students
                                        </small>
                                    </div>

                                </div>

                                <a href="#"
                                   class="btn btn-sm btn-outline-primary">
                                    Manage
                                </a>

                            </div>

                        @empty

                            <div class="text-center py-5">

                                <i class="fas fa-book-open fa-2x text-muted mb-3"></i>

                                <p class="text-muted mb-0">
                                    You don't have any courses yet.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>


                <!-- Quick Actions -->
                <div class="col-lg-4">

                    <div class="dashboard-card">

                        <div class="dashboard-card-header">
                            <h5>Quick Actions</h5>
                        </div>

                        <div class="p-3">

                            <a href="#"
                               class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-book me-2"></i>
                                My Courses
                            </a>

                            <a href="#"
                               class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-video me-2"></i>
                                Manage Lessons
                            </a>

                            <a href="#"
                               class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-question-circle me-2"></i>
                                Manage Quizzes
                            </a>

                            <a href="#"
                               class="btn btn-outline-secondary w-100">
                                <i class="fas fa-user me-2"></i>
                                My Profile
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>


    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>