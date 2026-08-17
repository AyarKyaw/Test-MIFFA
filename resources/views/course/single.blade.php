@extends('layouts.master')

@section('title', ($course->title ?? 'Course Details') . ' - MIFFA')

@section('content')
    <!-- Start Breadcrumb 
    ============================================= -->
    <div class="breadcrumb-area text-center bg-gray-gradient-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>{{ $course->title }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="/"><i class="fas fa-home"></i> Home</a></li>
                            @if(isset($course->category))
                                <li><a href="#">{{ $course->category->name }}</a></li>
                            @endif
                            <li class="active">{{ $course->code ?? 'Course Single' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Start Course Details 
    ============================================= -->
    <div class="course-detils-area course-details-two default-padding-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="course-details-thumb">
                        <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('assets/img/courses/13.jpg') }}" alt="{{ $course->title }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="course-single-meta-box">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="course-single-meta">
                            <!-- Instructor / Author -->
                            <div class="item author">
                                <div class="thumb">
                                    <a href="#">
                                        <img alt="{{ $course->instructor->name ?? 'Instructor' }}" 
                                            src="{{ isset($course->instructor->avatar) ? asset($course->instructor->avatar) : asset('assets/img/team/m3.jpg') }}">
                                    </a>
                                </div>
                                <div class="desc">
                                    <h4>Author</h4>
                                    <a href="#">{{ $course->instructor->name ?? 'MIFFA Instructor' }}</a>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="item category">
                                <h4>Category</h4>
                                <a href="#">{{ $course->category->name ?? 'General' }}</a>
                            </div>

                            <!-- Rating / Code -->
                            <div class="item rating">
                                <h4>Course Code</h4>
                                <span class="fw-bold text-dark">{{ $course->code ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="course-details-items">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 pr-40 pr-md-15 pr-xs-15">
                        <div class="course-details-left-info">
                            <div class="course-details-info">
                                <ul class="nav nav-tabs course-details-navs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="tabs_1" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="tab_1" aria-selected="true">
                                            Course Info
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tabs_2" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="tab_2" aria-selected="false">
                                            Curriculum
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tabs_3" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="tab_3" aria-selected="false">
                                            Advisor
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tabs_4" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="tab_4" aria-selected="false">
                                            Reviews
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content course-details-tab-content" id="myTabContent">
                                    <!-- Tab Single -->
                                    <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tabs_1">
                                        <h2>About this course</h2>
                                        <p>
                                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. 
                                        </p>
                                        <p>
                                            All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first   true generator on the Internet. It uses a dictionary of over 200 Latin words.
                                        </p>
                                        <h2>What you’ll learn</h2>
                                        <ul class="list-style-one">
                                            <li>Artificial Intelligence and Machine learning</li>
                                            <li>AI-based tutoring platforms can connect students</li>
                                            <li>Detecting suspicious behavior</li>
                                            <li>AI algorithms can analyze students' academic</li>
                                            <li>Algorithms can identify students</li>
                                            <li>Automatically grade assignments</li>
                                        </ul>
                                        <h2>What is the target audience?</h2>
                                        <p>
                                            Placing assured be if removed it besides on. Far shed each high read are men over day. Afraid we praise lively he suffer family estate is. Ample order up in of in ready. Timed blind had now those ought set often which
                                        </p>
                                    </div>
                                    <!-- End Tab Single -->
                                    <!-- Tab Single -->
                                    <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tabs_2">
                                        <div class="faq-style-one-items curriculum-accordion">
                                            <div class="accordion" id="faqAccordion">
                                                <div class="accordion-item faq-style-one">
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                            Understanding UI and UX design
                                                        </button>
                                                    </h2>
                                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            <ul class="curriculum-list">
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>01</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i>Introduction to UI/UX Design
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <a href="#">Preview</a>
                                                                        <span>2 hrs 45 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>02</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i> Persona Development
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>7 hrs 48 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>03</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-file"></i>
                                                                                User Research Sssignments
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>3 hrs 25 min</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item faq-style-one">
                                                    <h2 class="accordion-header" id="headingTwo">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            Roles in UI/UX design
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            <ul class="curriculum-list">
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>01</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i> Understanding user needs
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <a href="#">Preview</a>
                                                                        <span>2 hrs 45 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>02</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i> Visual design
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>7 hrs 48 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>03</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i>
                                                                                Design and prototyping
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>3 hrs 25 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>04</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-file"></i>
                                                                                Accessibility and inclusivity
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>5 hrs 24 min</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item faq-style-one">
                                                    <h2 class="accordion-header" id="headingThree">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                            User research techniques
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            <ul class="curriculum-list">
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>01</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i>Introduction to UI/UX Design
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <a href="#">Preview</a>
                                                                        <span>2 hrs 45 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>02</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i> Persona Development
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>7 hrs 48 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>03</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-file"></i>
                                                                                User Research Sssignments
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>3 hrs 25 min</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item faq-style-one">
                                                    <h2 class="accordion-header" id="headingFour">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                            Analytics – Reports and dashboards
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">
                                                            <ul class="curriculum-list">
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>01</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i> Understanding user needs
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <a href="#">Preview</a>
                                                                        <span>2 hrs 45 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>02</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i> Visual design
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>7 hrs 48 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>03</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-play-circle"></i>
                                                                                Design and prototyping
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>3 hrs 25 min</span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="left-content">
                                                                        <span>04</span>
                                                                        <h5>
                                                                            <a href="#">
                                                                                <i class="fas fa-file"></i>
                                                                                Accessibility and inclusivity
                                                                            </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="right-content">
                                                                        <i class="fas fa-lock"></i>
                                                                        <span>5 hrs 24 min</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Tab Single -->
                                    <!-- Tab Single -->
                                    <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tabs_3">
                                        <div class="curriculum-advisor">
                                            <div class="thumb">
                                                <img src="https://validthemes.net/site-template/learna/assets/img/advisor/2.jpg" alt="Image Not Found">
                                            </div>
                                            <div class="info">
                                                <div class="top">
                                                    <h4>James William</h4>
                                                    <span>Graphics Designer</span>
                                                    <div class="reviews">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star-half-alt"></i>
                                                        <span>(4.7/ 3 Reviews)</span>
                                                    </div>
                                                </div>
                                                <ul>
                                                    <li><i class="fas fa-play"></i> 12 Courses</li>
                                                    <li><i class="fas fa-users"></i> 24k Students</li>
                                                </ul>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in.
                                                </p>
                                            </div>
                                            <ul class="social">
                                                <li class="facebook">
                                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                                </li>
                                                <li class="twitter">
                                                    <a href="#"><img src="assets/img/icon/x.png" alt="Image Not Found"></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="curriculum-advisor">
                                            <div class="thumb">
                                                <img src="https://validthemes.net/site-template/learna/assets/img/advisor/3.jpg" alt="Image Not Found">
                                            </div>
                                            <div class="info">
                                                <div class="top">
                                                    <h4>Jones Mark</h4>
                                                    <span>UX Designer</span>
                                                    <div class="reviews">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star-half-alt"></i>
                                                        <span>(4.7/ 55 Reviews)</span>
                                                    </div>
                                                </div>
                                                <ul>
                                                    <li><i class="fas fa-play"></i> 35 Courses</li>
                                                    <li><i class="fas fa-users"></i> 56k Students</li>
                                                </ul>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in.
                                                </p>
                                            </div>
                                            <ul class="social">
                                                <li class="facebook">
                                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                                </li>
                                                <li class="twitter">
                                                    <a href="#"><img src="assets/img/icon/x.png" alt="Image Not Found"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- End Tab Single -->
                                    <!-- Tab Single -->
                                    <div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tabs_4">
                                        <div class="curriculum-review">
                                            <div class="curriculum-review-item">
                                                <div class="total-review">
                                                    <h2>4.9</h2>
                                                    <h4>Wonderful</h4>
                                                    <div class="ratings">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star-half-alt"></i>
                                                    </div>
                                                    <span>(2.5k Reviews)</span>
                                                </div>
                                                <div class="review-count">
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-five">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-90"></div>
                                                        <span>90%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-four">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-80"></div>
                                                        <span>80%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-three">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-70"></div>
                                                        <span>70%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-two">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-60"></div>
                                                        <span>60%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                    <!-- Single Item -->
                                                    <div class="review-count-item">
                                                        <div class="ratings five-out-of-one">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="progress-bar width-10"></div>
                                                        <span>10%</span>
                                                    </div>
                                                    <!-- End Single Item -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="course-review-section mt-80 mt-xs-50">
                                            <!-- Single Item -->
                                            <div class="course-review-item-one">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <div class="top">
                                                        <h4>Nigar Sultrua</h4>
                                                        <span>15 December, 2025</span>
                                                    </div>
                                                    <div class="ratings">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star-half-alt"></i>
                                                    </div>
                                                    <p>
                                                        Agreeable law unwilling sir deficient curiosity instantly. Easy mind life fact with see has bore ten. Parish any chatty can elinor direct for former. Up as meant widow equal an share least.
                                                    </p>
                                                    <div class="bottom">
                                                        <ul>
                                                            <li>
                                                                <a href="#"><i class="fas fa-thumbs-up"></i> 202</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fas fa-reply"></i> Reply</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Item -->
                                            <!-- Single Item -->
                                            <div class="course-review-item-one">
                                                <div class="thumb">
                                                    <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                                </div>
                                                <div class="info">
                                                    <div class="top">
                                                        <h4>Sarahub Albert</h4>
                                                        <span>18 October, 2025</span>
                                                    </div>
                                                    <div class="ratings">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                    <p>
                                                        Agreeable law unwilling sir deficient curiosity instantly. Easy mind life fact with see has bore ten. Parish any chatty can elinor direct for former. Up as meant widow equal an share least.
                                                    </p>
                                                    <div class="bottom">
                                                        <ul>
                                                            <li>
                                                                <a href="#"><i class="fas fa-thumbs-up"></i> 658</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fas fa-reply"></i> Reply</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Item -->
                                        </div>
                                    </div>
                                    <!-- End Tab Single -->
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="course-details-category">
                            <!-- Single Item -->
                            <div class="course-cat-single">
                                <div class="course-preview-info style-two">
                                    <div class="thumb">
                                        <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('assets/img/courses/13.jpg') }}" alt="{{ $course->title }}" alt="{{ $course->title }}">
                                        @if(!empty($course->video_url))
                                            <a href="{{ $course->video_url }}" class="popup-youtube light video-play-button item-center">
                                                <i class="fa fa-play"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="content">
                                        <div class="top">
                                            <div class="pricce">
                                                <h2>
                                                    @if(isset($course->price) && $course->price > 0)
                                                        ${{ number_format($course->price, 2) }}
                                                    @else
                                                        Free
                                                    @endif
                                                </h2>
                                            </div>
                                            <a class="btn btn-gradient animation btn-sm" href="{{ route('enroll.index', $course->id) }}">Buy Now</a>
                                        </div>
                                        <div class="course-includes">
                                            <div class="info">
                                                <ul>
                                                    <li>
                                                        <i class="fas fa-code"></i> Course Code <span>{{ $course->code ?? 'N/A' }}</span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-clock"></i> Duration <span>{{ $course->hour ?? '0' }} Hours</span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-tag"></i> Category <span>{{ $course->category?->name ?? 'General' }}</span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-sliders-h"></i> Skill level <span>All Levels</span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-language"></i> Language <span>English</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Course Details -->

    <!-- Start Related Course 
    ============================================= -->
    <div class="related-course-area overflow-hidden default-padding-bottom">
        <div class="container">
            <div class="heading-left">
                <div class="row">
                    <div class="col-lg-7">
                        <h4 class="sub-title">Related Course</h4>
                        <h2 class="title">You might be interested in</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="related-course-carousel swiper">
                        <div class="swiper-wrapper">
                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="course-style-one-item hover-less">
                                    <div class="thumb">
                                        <img src="assets/img/courses/1.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="top-meta">
                                        <div class="author">
                                            <img src="assets/img/team/m1.jpg" alt="Image Not Found">
                                            <a href="#">Alsha Brown</a>
                                        </div>
                                        <div class="bookmark">
                                            <a href="#"><i class="fas fa-bookmark"></i></a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="course-tags">
                                            <a href="#">Development</a>
                                            <div class="course-rating">
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star-half-alt"></i>  
                                                <span>(2.7K)</span>
                                            </div>
                                        </div>
                                        <h4><a href="course-single.html">WordPress and WooCommerce complete development.</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-file-alt"></i> 78 Lessons
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="course-single.html">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price">$42</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->
                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="course-style-one-item hover-less">
                                    <div class="thumb">
                                        <img src="assets/img/courses/2.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="top-meta">
                                        <div class="author">
                                            <img src="assets/img/team/m2.jpg" alt="Image Not Found">
                                            <a href="#">Kevin Martin</a>
                                        </div>
                                        <div class="bookmark">
                                            <a href="#"><i class="fas fa-bookmark"></i></a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="course-tags">
                                            <a href="#">Programming</a>
                                            <div class="course-rating">
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i>  
                                                <span>(2.4K)</span>
                                            </div>
                                        </div>
                                        <h4><a href="course-single.html">English grammar courses online with real certificates</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-file-alt"></i> 128 Lessons
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 2K Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="course-single.html">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price">$27</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->
                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="course-style-one-item hover-less">
                                    <div class="thumb">
                                        <img src="assets/img/courses/3.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="top-meta">
                                        <div class="author">
                                            <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                            <a href="#">Sarah Albert</a>
                                        </div>
                                        <div class="bookmark">
                                            <a href="#"><i class="fas fa-bookmark"></i></a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="course-tags">
                                            <a href="#">Accounting</a>
                                            <div class="course-rating">
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star-half-alt"></i>  
                                                <span>(4.1K)</span>
                                            </div>
                                        </div>
                                        <h4><a href="course-single.html">Complete React Front-end developer course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-file-alt"></i> 69 Lessons
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 246 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="course-single.html">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price">$38</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->
                            <!-- Single Item -->
                            <div class="swiper-slide">
                                <div class="course-style-one-item hover-less">
                                    <div class="thumb">
                                        <img src="assets/img/courses/4.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="top-meta">
                                        <div class="author">
                                            <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                            <a href="#">Amaul Joey</a>
                                        </div>
                                        <div class="bookmark">
                                            <a href="#"><i class="fas fa-bookmark"></i></a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="course-tags">
                                            <a href="#">WordPress</a>
                                            <div class="course-rating">
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i> 
                                                <i class="fas fa-star"></i>  
                                                <span>(1.7K)</span>
                                            </div>
                                        </div>
                                        <h4><a href="course-single.html">Basic to Advance UX &amp; UI Design and live Training Course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <i class="fas fa-file-alt"></i> 38 Lessons
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 154 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="course-single.html">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price">$26</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Related Course  -->
@endsection