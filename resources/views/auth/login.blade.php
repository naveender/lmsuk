@extends('auth.layout.auth')
@section('title', 'Aspire Learners - Login')
<!-- BEGIN: Body-->
@section('content')

    <section class="row flexbox-container">
        <div class="col-xl-6 col-lg-6  col-11 d-flex justify-content-center">
            <div class="card bg-authentication rounded-0 mb-0">
                <div class="row m-0">
                    <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                        <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Full-Logo.png') }}"
                            alt="branding logo" width="50%">
                    </div>
                    <div class="col-lg-6 col-12 p-0">
                        <div class="card rounded-0 mb-0 py-5">
                            <div class="card-header pb-1">
                                <div class="card-title">
                                    <h4 class="mb-0">Login</h4>
                                </div>
                            </div>
                            <p class="px-2">Welcome back, please login to your account.</p>
                            <div class="card-content">
                                @if ($errors->any())
                                    <div>
                                        <div class="alert alert-danger" role="alert">
                                            <h4 class="alert-heading">Error Detected</h4>
                                            <p class="mb-0">
                                            <ul>
                                                @foreach ($errors->all() as $e)
                                                    <li>{{ $e }}</li>
                                                @endforeach
                                            </ul>
                                            </p>
                                        </div>

                                    </div>
                                @endif
                                <div class="card-body pt-1">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <fieldset class="form-label-group form-group position-relative has-icon-left">
                                            <input type="email" name="email" class="form-control" id="user-email"
                                                placeholder="Enter your Email" required>
                                            <div class="form-control-position">
                                                <i class="feather icon-user"></i>
                                            </div>
                                            <label for="user-name">Email Address</label>
                                            @error('email')
                                                <span class="error-text">{{ $message }}</span>
                                            @enderror
                                        </fieldset>

                                        <fieldset class="form-label-group position-relative has-icon-left">
                                            <input type="password" name="password" class="form-control" id="user-password"
                                                placeholder="Enter Your Password" required>
                                            <div class="form-control-position">
                                                <i class="feather icon-lock"></i>
                                            </div>
                                            <label for="user-password">Password</label>
                                        </fieldset>
                                        <div class="form-group d-flex justify-content-between align-items-center">
                                            <div class="text-left">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox">
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class="">Remember me</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="text-right"><a href="auth-forgot-password.html"
                                                    class="card-link">Forgot Password?</a></div>
                                        </div>
                                        <a href="register"
                                            class="btn btn-outline-primary float-left btn-inline">Register</a>
                                        <button type="submit" class="btn btn-primary float-right btn-inline">Login</button>
                                    </form>
                                </div>
                            </div>
                            {{-- <div class="login-footer">
                                            <div class="divider">
                                                <div class="divider-text">Aspire Learner</div>
                                            </div>
                                            <div class="footer-btn d-inline">
                                                <center>
                                                    <p>Best Learning Experience</p>
                                                    <p> With Aspire Learner</p>
                                                </center>
                                            </div>
                                        </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- END: Content-->
@endsection
