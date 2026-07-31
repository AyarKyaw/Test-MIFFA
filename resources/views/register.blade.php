<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Learna - Education HTML Template">

    <!-- ========== Page Title ========== -->
    <title>Register - MIFFA</title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">

    <!-- ========== Google Identity Services ========== -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <!-- ========== Start Stylesheet ========== -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/validnavs.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/unit-test.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('style.css') }}" rel="stylesheet">
    <!-- ========== End Stylesheet ========== -->
</head>

<body>

    <!-- Start Register Area -->
    <div class="login-register-area register bg-gray-gradient-secondary">
        <div class="login-style-one-items">
            <div class="shape">
                <img src="https://validthemes.net/site-template/learna/assets/img/shape/banner-5.jpg" alt="Image Not Found">
            </div>
            <div class="thumb">
                <img src="https://validthemes.net/site-template/learna/assets/img/illustration/14.png" alt="Image Not Found">
            </div>
            <div class="container">
                <div class="row align-center">
                    <div class="col-xl-5 col-lg-6">
                        <div class="login-register-items text-light py-3">
                            <h2 class="mb-1" style="font-size: 24px;">Create an Account</h2>
                            <p class="mb-3" style="font-size: 14px;">
                                Already have an account? <a href="{{ route('login') }}">Sign in</a>
                            </p>

                            <!-- Registration Form -->
                            <form action="{{ route('register.perform') }}" method="POST" id="registrationForm">
                                @csrf

                                <!-- Hidden input to hold google_id across reloads -->
                                <input type="hidden" name="google_id" id="google_id" value="{{ old('google_id') }}">

                                @if ($errors->any())
                                    <div class="alert alert-danger py-1 px-2 mb-2" style="font-size: 12px;">
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Instructions Step Alert -->
                                <div id="stepNotice" class="alert alert-info py-2 px-3 mb-3" style="font-size: 12px; background-color: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.2); color: #fff;">
                                    Please click <strong>"Sign in with Google"</strong> below to start your registration.
                                </div>

                                <!-- Full Name -->
                                <div class="row">
                                    <div class="col-xl-12 mb-2">
                                        <div class="form-group mb-0">
                                            <input id="name" name="name" value="{{ old('name') }}" class="form-control py-2" placeholder="Full Name*" type="text" required disabled style="height: 42px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="row">
                                    <div class="col-xl-12 mb-2">
                                        <div class="form-group mb-0">
                                            <input id="email" name="email" value="{{ old('email') }}" class="form-control py-2" placeholder="Email*" type="email" required disabled readonly style="height: 42px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <!-- <div class="row">
                                    <div class="col-xl-12 mb-2">
                                        <div class="form-group mb-0">
                                            <input id="phone" name="phone" value="{{ old('phone') }}" class="form-control py-2" placeholder="Phone number" type="text" disabled style="height: 42px;">
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Password & Confirm Password -->
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <div class="form-group mb-0">
                                            <input id="password" name="password" class="form-control py-2" placeholder="Password*" type="password" required disabled style="height: 42px; font-size: 13px;">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-0">
                                            <input id="password-confirm" name="password_confirmation" class="form-control py-2" placeholder="Confirm Password*" type="password" required disabled style="height: 42px; font-size: 13px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Google Sign-In Button (Visible initially) -->
                                <div class="row" id="googleBtnRow">
                                    <div class="col-lg-12">
                                        <button type="button" onclick="triggerGoogleSignIn()" class="btn btn-sm circle btn-theme animation d-flex align-items-center justify-content-center gap-2 w-100" style="background-color: #ffffff; color: #333333 !important; text-transform: none; border: none; height: 42px; font-size: 13px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 48 48">
                                                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                                                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                                                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                                                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                                            </svg>
                                            Sign in with Google
                                        </button>
                                    </div>
                                </div>

                                <!-- Register Submit Button (Hidden initially) -->
                                <div class="row d-none" id="registerBtnRow">
                                    <div class="col-xl-12">
                                        <button class="btn btn-sm circle btn-theme animation w-100 py-2" type="submit" style="height: 42px; line-height: 1;">Complete Registration</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Register -->

    <!-- ========== Google Sign-In Client Script ========== -->
    <script>
        let tokenClient;

window.onload = function () {
    tokenClient = google.accounts.oauth2.initTokenClient({
        client_id: '681316627623-lb6qo0j0qd42esdp26v492rsen7rth02.apps.googleusercontent.com',
        scope: 'email profile openid',
        callback: handleGoogleUserResponse,
    });

    // Restore form state if returning from validation errors
    restoreStateOnValidationError();
};

function enableFormFields() {
    document.getElementById('name').disabled = false;
    document.getElementById('email').disabled = false;
    document.getElementById('phone').disabled = false;
    document.getElementById('password').disabled = false;
    document.getElementById('password-confirm').disabled = false;

    // Show register button, hide Google sign-in button
    document.getElementById('googleBtnRow').classList.add('d-none');
    document.getElementById('registerBtnRow').classList.remove('d-none');

    // Update banner
    const notice = document.getElementById('stepNotice');
    notice.className = 'alert alert-success py-2 px-3 mb-3';
    notice.style.backgroundColor = 'rgba(40, 167, 69, 0.2)';
    notice.innerHTML = '✔ Google account verified! Please fix any errors and complete registration.';
}

function restoreStateOnValidationError() {
    // Check if Laravel has returned validation errors or old input
    const hasErrors = @json($errors->any());
    const hasOldEmail = @json(old('email') !== null);

    if (hasErrors || hasOldEmail) {
        enableFormFields();
    }
}

function triggerGoogleSignIn() {
    if (tokenClient) {
        tokenClient.requestAccessToken({ prompt: 'select_account' });
    } else {
        console.error("Google Client SDK not loaded yet.");
    }
}

function handleGoogleUserResponse(tokenResponse) {
    if (tokenResponse.error) {
        console.error("Google Auth Error:", tokenResponse.error);
        return;
    }

    fetch('https://www.googleapis.com/oauth2/v3/userinfo', {
        headers: {
            'Authorization': `Bearer ${tokenResponse.access_token}`
        }
    })
    .then(res => res.json())
    .then(googleUser => {
        document.getElementById('email').value = googleUser.email;
        document.getElementById('name').value = googleUser.name || '';
        document.getElementById('google_id').value = googleUser.sub;

        enableFormFields();
    })
    .catch(error => {
        console.error("Error during Google Authentication:", error);
        alert("Authentication failed. Please try again.");
    });
}
    </script>

    <!-- jQuery Frameworks -->
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

</body>
</html>