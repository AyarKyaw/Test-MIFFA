@extends('layouts.master')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card p-4 shadow-sm rounded-4">
                <h3 class="fw-bold mb-3">Verify Your Email Address</h3>
                <p class="text-muted mb-4">
                    Thanks for joining! Before getting started, please verify your email address by clicking on the link we just emailed to you.
                </p>

                @if (session('message'))
                    <div class="alert alert-success rounded-3 mb-3">
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                <form method="POST" action="{{ route('alumni.verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Resend Verification Email
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection