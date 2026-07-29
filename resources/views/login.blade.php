<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from validthemes.net/site-template/learna/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 23 Jul 2026 08:21:49 GMT -->
<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Learna - Education HTML Template">

    <!-- ========== Page Title ========== -->
    <title>MIFFA - Login</title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

    <!-- ========== Start Stylesheet ========== -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/magnific-popup.css" rel="stylesheet">
    <link href="assets/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">
    <link href="assets/css/validnavs.css" rel="stylesheet">
    <link href="assets/css/helper.css" rel="stylesheet">
    <link href="assets/css/unit-test.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <!-- ========== End Stylesheet ========== -->

    <script src="https://accounts.google.com/gsi/client" async defer></script>

</head>

<body>

    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
     
    <!-- Start Login 
    ============================================= -->
    <div class="login-register-area bg-gray-gradient-secondary">
        <div class="login-style-one-items">
            <div class="shape">
                <img src="assets/img/shape/banner-5.jpg" alt="Imge Not Found">
            </div>
            <div class="thumb">
                <img src="assets/img/illustration/14.png" alt="Imge Not Found">
            </div>
            <div class="container">
                <div class="row align-center">
                    <div class="col-xl-5 col-lg-6">
                        <div class="login-register-items text-light">
                            <h2>Sign in</h2>
                            <p>
                                Dont have an account? <a href="/register">Create New</a>
                            </p>
                            <form action="{{ route('login.perform') }}" method="POST">

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-3" style="color: red;">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input id="email" class="form-control" placeholder="Email or Phone*" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input id="password" class="form-control" placeholder="Password*" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="remember-pass">
                                            <div class="check-box">
                                                <input type="checkbox" id="remember" name="remember" value="Remember Me">
                                                <label for="remember"> Remember Me</label>
                                            </div>
                                            <a href="#">Forgot Password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button class="btn btn-sm circle btn-theme animation w-100 py-2" type="submit" style="height: 42px; line-height: 1;">Log in</button>
                                    </div>
                                </div>
                                <!-- <div class="text-center my-3 text-muted">
                                    <span style="opacity: 0.7;">OR</span>
                                </div> -->

                                <!-- Google Sign-In Button -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="button" onclick="triggerGoogleSignIn()" class="btn btn-sm circle btn-theme animation d-flex align-items-center justify-content-center gap-2 w-100" style="background-color: #ffffff; color: #333333 !important; text-transform: none; border: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                                                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                                                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                                                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                                                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                                            </svg>
                                            Sign in with Google
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Login -->

    <script>
        window.onload = function () {
            google.accounts.id.initialize({
                client_id: "681316627623-lb6qo0j0qd42esdp26v492rsen7rth02.apps.googleusercontent.com",
                callback: handleCredentialResponse
            });
        };

        function triggerGoogleSignIn() {
            google.accounts.id.prompt(); // Shows One Tap prompt or account selector
        }

        function handleCredentialResponse(response) {
            // Here is your JWT ID token from Google
            console.log("Encoded JWT ID token: " + response.credential);

            // TODO: Send response.credential to your backend endpoint via fetch/AJAX
        }
    </script>
    <!-- jQuery Frameworks
    ============================================= -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.appear.js"></script>
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/progress-bar.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/magnific-popup.min.js"></script>
    <script src="assets/js/count-to.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/YTPlayer.min.js"></script>
    <script src="assets/js/loopcounter.js"></script>
    <script src="assets/js/validnavs.js"></script>
    <script src="assets/js/gsap.js"></script>
    <script src="assets/js/ScrollTrigger.min.js"></script>
    <script src="assets/js/SplitText.min.js"></script>
    <script src="assets/js/main.js"></script>

</body>

<!-- Mirrored from validthemes.net/site-template/learna/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 23 Jul 2026 08:21:49 GMT -->
</html>