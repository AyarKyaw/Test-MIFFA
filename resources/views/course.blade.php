@extends('layouts.master')

@section('title', 'Courses - MIFFA')

@section('content')
    <!-- Start Breadcrumb 
    ============================================= -->
    <div class="breadcrumb-area text-center bg-gray-gradient-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>Course</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="/"><i class="fas fa-home"></i> Home</a></li>
                            <li class="active">Course</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->
     
    <!-- Start Course 
    ============================================= -->
    <div class="course-tabs-area default-padding">
        <div class="container">
            <div class="course-tab-style-one">
                <div class="row">
                    <div class="col-xl-4 col-lg-5">
                        <ul class="nav nav-tabs category-tabs wow fadeInUp" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tabs_1" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="tab_1" aria-selected="true">
                                    <img src="assets/img/icon/29.png" alt="Image Not Fond"> <strong>Programming</strong>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tabs_2" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="tab_2" aria-selected="false">
                                    <img src="assets/img/icon/47.png" alt="Image Not Fond"> <strong>Business Finance</strong>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tabs_3" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="tab_3" aria-selected="false">
                                    <img src="assets/img/icon/48.png" alt="Image Not Fond"> <strong>Teaching Online</strong>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tabs_4" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="tab_4" aria-selected="false">
                                    <img src="assets/img/icon/26.png" alt="Image Not Fond"> <strong>Arts & Design</strong>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tabs_5" data-bs-toggle="tab" data-bs-target="#tab_5" type="button" role="tab" aria-controls="tab_5" aria-selected="false">
                                    <img src="assets/img/icon/28.png" alt="Image Not Fond"> <strong>Language Learning</strong>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xl-7 offset-xl-1 col-lg-7">
                        <div class="tab-content category-tab-content wow fadeInUp" data-wow-delay="400ms" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tabs_1">
                                
                                <!-- Single Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/1.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m2.jpg" alt="Image Not Found">
                                            <a href="#">Aleesha Brown</a>
                                        </div>
                                        <h4><a href="/course-single">WordPress website complete development.</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(2.8k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$46.00</del> $35.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
    
                                 <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/3.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                            <a href="#">Amaul Joey</a>
                                        </div>
                                        <h4><a href="/course-single">Complete React Front-end developer course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(3.4k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$42.00</del> $29.00</h2>
                                        </div>
                                    </div>
                                </div>
                                 <!-- End Item -->
    
                                <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/6.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                            <a href="#">Sarah Albert</a>
                                        </div>
                                        <h4><a href="/course-single">Dynamic website development to make money from online</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star-half-alt"></i>  
                                                        <span>(1k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 244 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$35.00</del> $27.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
    
                            </div>
                            <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tabs_2">
                                <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/6.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                            <a href="#">Sarah Albert</a>
                                        </div>
                                        <h4><a href="/course-single">Dynamic website development to make money from online</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star-half-alt"></i>  
                                                        <span>(1k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 244 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$36.00</del> $27.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
                                <!-- Single Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/8.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m2.jpg" alt="Image Not Found">
                                            By <a href="#">Aleesha Brown</a>
                                        </div>
                                        <h4><a href="/course-single">Complete React Front-end developer course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(2.8k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$47.00</del> $35.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
    
                                 <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/9.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                            <a href="#">Amaul Joey</a>
                                        </div>
                                        <h4><a href="/course-single">How to create a dyanmic app by using Flutter development</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(3.4k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$28.00</del> $15.00</h2>
                                        </div>
                                    </div>
                                </div>
                                 <!-- End Item -->
    
                            </div>
                            <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tabs_3">
                                <!-- Single Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/11.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m2.jpg" alt="Image Not Found">
                                            By <a href="#">Aleesha Brown</a>
                                        </div>
                                        <h4><a href="/course-single">WordPress website complete development.</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(2.8k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$36.00</del> $22.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
    
                                 <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/4.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                            <a href="#">Amaul Joey</a>
                                        </div>
                                        <h4><a href="/course-single">Complete React Front-end developer course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(3.4k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$53.00</del> $45.00</h2>
                                        </div>
                                    </div>
                                </div>
                                 <!-- End Item -->
    
                                <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/6.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                            <a href="#">Sarah Albert</a>
                                        </div>
                                        <h4><a href="/course-single">Dynamic website development to make money from online</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star-half-alt"></i>  
                                                        <span>(1k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 244 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$26.00</del> $18.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
                            </div>
                            <div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tabs_4">
                                <!-- Single Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/7.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m2.jpg" alt="Image Not Found">
                                            By <a href="#">Aleesha Brown</a>
                                        </div>
                                        <h4><a href="/course-single">Best live Figma Courses online with expertise certificates</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(2.8k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$68.00</del> $52.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
    
                                 <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/4.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                            <a href="#">Amaul Joey</a>
                                        </div>
                                        <h4><a href="/course-single">Basic to Advance UX & UI Design and live Training Course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(3.4k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$34.00</del> $25.00</h2>
                                        </div>
                                    </div>
                                </div>
                                 <!-- End Item -->
    
                                <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/8.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                            <a href="#">Sarah Albert</a>
                                        </div>
                                        <h4><a href="/course-single">Dynamic website development to make money from online</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star-half-alt"></i>  
                                                        <span>(1k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 244 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$26.00</del> $14.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
                            </div>
                            <div class="tab-pane fade" id="tab_5" role="tabpanel" aria-labelledby="tabs_5">
                                <!-- Single Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/2.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m2.jpg" alt="Image Not Found">
                                            By <a href="#">Aleesha Brown</a>
                                        </div>
                                        <h4><a href="/course-single">English grammar courses online with real certificates</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(2.8k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$49.00</del> $36.00</h2>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Item -->
    
                                 <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/6.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m4.jpg" alt="Image Not Found">
                                            <a href="#">Amaul Joey</a>
                                        </div>
                                        <h4><a href="/course-single">Dynamic website development to make money from online</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i>  
                                                        <span>(3.4k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 12 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$35.00</del> $27.00</h2>
                                        </div>
                                    </div>
                                </div>
                                 <!-- End Item -->
    
                                <!-- Start Item -->
                                <div class="course-style-one-item hover-less list-layout">
                                    <div class="thumb">
                                        <img src="assets/img/courses/5.jpg" alt="Image Not Found">
                                    </div>
                                    <div class="info">
                                        <div class="author">
                                            <img src="assets/img/team/m3.jpg" alt="Image Not Found">
                                            <a href="#">Sarah Albert</a>
                                        </div>
                                        <h4><a href="/course-single">Online learning management system & learn dash course</a></h4>
                                        <div class="course-meta">
                                            <ul>
                                                <li>
                                                    <div class="course-rating">
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star"></i> 
                                                        <i class="fas fa-star-half-alt"></i>  
                                                        <span>(1k)</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user"></i> 244 Students
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom-meta">
                                            <a href="/course-single">Enroll Now <i class="fas fa-long-arrow-right"></i></a>
                                            <h2 class="price"><del>$25.00</del> $14.00</h2>
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
    </div>
    <!-- End Course -->
@endsection