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
                                            <div class="text-right"><a href="#" data-toggle="modal" data-target="#forgotPasswordModal"
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

    <!-- Forgot Password Modal -->
    <div class="modal fade text-left" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 8px; box-shadow: 0 4px 25px 0 rgba(0,0,0,0.15);">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Your Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="forgot-password-form">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">Enter your email address and we'll send you a link to reset your password.</p>
                        
                        <div id="modal-alert-container"></div>

                        <div class="form-label-group position-relative has-icon-left mb-0">
                            <input type="email" id="forgot-email" name="email" class="form-control" placeholder="Email Address" required>
                            <div class="form-control-position">
                                <i class="feather icon-mail"></i>
                            </div>
                            <label for="forgot-email">Email Address</label>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="btn-forgot-submit" class="btn btn-primary d-flex align-items-center">
                            <span id="btn-spinner" class="mr-50" style="display: none; z-index: 10;">
                                <i class="feather icon-loader" style="animation: spin 1s linear infinite; display: inline-block;"></i>
                            </span>
                            Send Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- END: Content-->
@endsection

@push('scripts')
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('forgot-password-form');
    const emailInput = document.getElementById('forgot-email');
    const submitBtn = document.getElementById('btn-forgot-submit');
    const spinner = document.getElementById('btn-spinner');
    const alertContainer = document.getElementById('modal-alert-container');
    const csrfToken = document.querySelector('input[name="_token"]').value;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Clear previous alerts
        alertContainer.innerHTML = '';
        
        // Show spinner & disable button
        spinner.style.display = 'inline-block';
        submitBtn.disabled = true;

        axios.post('/forgot-password', {
            email: emailInput.value
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            spinner.style.display = 'none';
            submitBtn.disabled = false;
            
            // Show success alert
            alertContainer.innerHTML = `
                <div class="alert alert-success d-flex align-items-center mb-2" role="alert" style="font-size: 0.9rem;">
                    <i class="feather icon-check-circle mr-50"></i>
                    <span>${response.data.message || response.data.status}</span>
                </div>
            `;
            
            // Reset form
            form.reset();
        })
        .catch(error => {
            spinner.style.display = 'none';
            submitBtn.disabled = false;
            
            let message = 'An error occurred. Please try again.';
            if (error.response && error.response.data) {
                if (error.response.data.errors && error.response.data.errors.email) {
                    message = error.response.data.errors.email[0];
                } else if (error.response.data.message) {
                    message = error.response.data.message;
                }
            }

            // Show error alert
            alertContainer.innerHTML = `
                <div class="alert alert-danger d-flex align-items-center mb-2" role="alert" style="font-size: 0.9rem;">
                    <i class="feather icon-alert-circle mr-50"></i>
                    <span>${message}</span>
                </div>
            `;
        });
    });

    // Reset modal state when it's closed using jQuery (since jQuery is loaded in auth.layout)
    if (typeof $ !== 'undefined') {
        $('#forgotPasswordModal').on('hidden.bs.modal', function () {
            form.reset();
            alertContainer.innerHTML = '';
            spinner.style.display = 'none';
            submitBtn.disabled = false;
        });
    }
});
</script>
@endpush

