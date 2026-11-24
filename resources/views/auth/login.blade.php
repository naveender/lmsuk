@extends('auth.layout.auth')
@section('title', 'Aspire Learners - Login')

@push('styles')
    <style>
        /* ============================================
                                                                                                                                               ASPIRE LEARNERS - LOGIN PAGE STYLES
                                                                                                                                               Matches reference design exactly
                                                                                                                                            ============================================ */

        /* Reset & Base */
        body.blank-page {
            background: #e8edf5 !important;
            min-height: 100vh;
            font-family: 'Inter', 'Poppins', sans-serif !important;
            overflow-x: hidden;
        }

        .app-content.content {
            padding: 0 !important;
            margin: 0 !important;
        }

        .content-wrapper {
            padding: 0 !important;
        }

        /* ---- Background Decorative Elements ---- */
        .login-page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 20px;
        }

        /* Decorative background blobs */
        .login-page-wrapper::before {
            content: '';
            position: absolute;
            top: -80px;
            left: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(37, 99, 235, 0.03));
            z-index: 0;
        }

        .login-page-wrapper::after {
            content: '';
            position: absolute;
            bottom: -120px;
            right: -60px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.06), rgba(37, 99, 235, 0.02));
            z-index: 0;
        }

        .bg-blob-1 {
            position: absolute;
            top: 40px;
            left: 30px;
            width: 60px;
            height: 60px;
            display: grid;
            grid-template-columns: repeat(5, 8px);
            grid-template-rows: repeat(5, 8px);
            gap: 4px;
            z-index: 0;
            opacity: 0.3;
        }

        .bg-blob-1 span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #2563eb;
        }

        .bg-blob-2 {
            position: absolute;
            bottom: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 500px;
            height: 200px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(59, 130, 246, 0.03));
            z-index: 0;
        }

        /* Corner text decorations */
        .corner-text-tr {
            position: absolute;
            top: 40px;
            right: 40px;
            font-family: 'Poppins', cursive;
            font-style: italic;
            font-weight: 500;
            font-size: 1.15rem;
            line-height: 1.5;
            color: #2563eb;
            text-align: right;
            z-index: 1;
            opacity: 0.7;
        }

        .corner-text-bl {
            position: absolute;
            bottom: 40px;
            left: 40px;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            line-height: 1.7;
            letter-spacing: 3px;
            color: #2563eb;
            text-transform: uppercase;
            z-index: 1;
            opacity: 0.5;
        }

        /* ---- Main Login Card ---- */
        .login-card {
            width: 100%;
            max-width: 920px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(37, 99, 235, 0.10), 0 4px 20px rgba(0, 0, 0, 0.06);
            display: flex;
            overflow: hidden;
            z-index: 2;
            position: relative;
            min-height: 520px;
        }

        /* ---- Left Panel (Branding) ---- */
        .login-left-panel {
            width: 45%;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            overflow: hidden;
            background: linear-gradient(180deg, #3478e0 0%, #1a3fa0 50%, #152d7a 100%);
        }

        .login-left-panel .panel-bg-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.35;
            z-index: 0;
        }

        .login-left-panel .panel-content {
            position: relative;
            z-index: 2;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .login-left-panel .panel-logo {
            width: 160px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.2));
        }

        .login-left-panel .panel-tagline {
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 40px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .login-left-panel .panel-bottom-text {
            color: rgba(255, 255, 255, 0.85);
            font-family: 'Poppins', sans-serif;
            font-style: italic;
            font-size: 0.95rem;
            font-weight: 400;
            text-align: center;
            margin-top: auto;
            padding-top: 30px;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        }

        /* ---- Right Panel (Form) ---- */
        .login-right-panel {
            width: 55%;
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right-panel .accent-line {
            width: 45px;
            height: 4px;
            background: #2563eb;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        .login-right-panel .welcome-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .login-right-panel .welcome-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 30px;
            font-weight: 400;
        }

        /* Form Input Groups */
        .login-input-group {
            position: relative;
            margin-bottom: 18px;
            background: #f1f5fd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            border: 1.5px solid transparent;
            transition: all 0.3s ease;
        }

        .login-input-group:focus-within {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .login-input-group .input-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            min-width: 50px;
            color: #9ca3af;
            font-size: 1.1rem;
        }

        .login-input-group .input-icon svg {
            width: 20px;
            height: 20px;
            stroke: #9ca3af;
            fill: none;
            stroke-width: 1.8;
        }

        .login-input-group:focus-within .input-icon svg {
            stroke: #2563eb;
        }

        .login-input-group input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 15px 15px 15px 0;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: #1a1a2e;
            outline: none;
        }

        .login-input-group input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .login-input-group .toggle-password {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            min-width: 50px;
            cursor: pointer;
            color: #9ca3af;
            background: none;
            border: none;
            padding: 0;
            transition: color 0.2s;
        }

        .login-input-group .toggle-password:hover {
            color: #2563eb;
        }

        .login-input-group .toggle-password svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }

        /* Remember / Forgot Row */
        .login-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .login-remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            color: #374151;
            user-select: none;
        }

        .forgot-pass-link {
            font-size: 0.86rem;
            font-weight: 500;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .forgot-pass-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .login-remember-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #2563eb;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-forgot-link {
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .login-forgot-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* Login Button */
        .login-submit-btn {
            width: 100%;
            padding: 12.5px 20px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            margin-bottom: 24px;
        }

        .login-submit-btn:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }


        .login-submit-btn:active {
            transform: translateY(0);
        }

        .login-submit-btn .btn-arrow {
            font-size: 1.2rem;
            transition: transform 0.2s;
        }

        .login-submit-btn:hover .btn-arrow {
            transform: translateX(4px);
        }

        /* Divider Text */
        .login-divider-text {
            text-align: center;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            color: #9ca3af;
            margin-bottom: 14px;
        }

        /* Create Account Button */
        .login-create-btn {
            width: 100%;
            padding: 12.5px 20px;
            background: transparent;
            color: #2563eb;
            border: 2px solid #2563eb;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
        }

        .login-create-btn:hover {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.25);
            text-decoration: none;
        }

        /* Error Alert */
        .login-error-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 18px;
        }

        .login-error-alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .login-error-alert li {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            color: #dc2626;
            line-height: 1.5;
        }

        /* ---- Responsive ---- */
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 440px;
            }

            .login-left-panel {
                width: 100%;
                min-height: 200px;
                padding: 30px 20px;
            }

            .login-left-panel .panel-logo {
                width: 100px;
                margin-bottom: 12px;
            }

            .login-left-panel .panel-tagline {
                font-size: 1rem;
                margin-bottom: 10px;
            }

            .login-left-panel .panel-bottom-text {
                display: none;
            }

            .login-right-panel {
                width: 100%;
                padding: 30px 25px;
            }

            .login-right-panel .welcome-title {
                font-size: 1.5rem;
            }

            .corner-text-tr,
            .corner-text-bl {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .login-page-wrapper {
                padding: 10px;
            }

            .login-right-panel {
                padding: 24px 18px;
            }
        }

        /* ---- Modal Override Styles (Forgot Password) ---- */
        #forgotPasswordModal .modal-content {
            border-radius: 16px !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
            border: none !important;
        }

        #forgotPasswordModal .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 20px 24px;
        }

        #forgotPasswordModal .modal-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #1a1a2e;
        }

        #forgotPasswordModal .modal-body {
            padding: 20px 24px;
        }

        #forgotPasswordModal .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 16px 24px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

<!-- BEGIN: Body-->
@section('content')

    <div class="login-page-wrapper">
        <!-- Background Decorative Dots -->
        <div class="bg-blob-1">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
        </div>

        <!-- Bottom Blob -->
        <div class="bg-blob-2"></div>

        <!-- Corner Decorative Text -->
        <div class="corner-text-tr">
            Better<br>Learners<br>Brighter<br>Futures
        </div>
        <div class="corner-text-bl">
            Education<br>Anytime<br>Anywhere
        </div>

        <!-- ===== Main Login Card ===== -->
        <div class="login-card">
            <!-- Left Panel -->
            <div class="login-left-panel">
                <img src="{{ asset('theme/app-assets/images/auth/login-left-panel.jpg') }}" alt="" class="panel-bg-image" />
                <div class="panel-content">
                    <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Full-Logo.png') }}"
                        alt="Aspire Learners" class="panel-logo" />
                    <div class="panel-tagline">
                        Learn Today<br>Build a Brighter<br>Tomorrow
                    </div>
                    <div class="panel-bottom-text">
                        <em>Empowering<br>Learners for a Brighter Future</em>
                    </div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="login-right-panel">
                <div class="accent-line"></div>
                <h1 class="welcome-title">Welcome Back</h1>
                <p class="welcome-subtitle">Sign in to continue to your Aspire Learners portal.</p>

                @if ($errors->any())
                    <div class="login-error-alert">
                        <ul>
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf

                    <!-- Email Input -->
                    <div class="login-input-group">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="M22 4l-10 8L2 4" />
                            </svg>
                        </div>
                        <input type="email" name="email" id="user-email" placeholder="Enter your email" required
                            value="{{ old('email') }}" autocomplete="email" />
                    </div>

                    <!-- Password Input -->
                    <div class="login-input-group">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </div>
                        <input type="password" name="password" id="user-password" placeholder="Enter your password" required
                            autocomplete="current-password" />
                        <button type="button" class="toggle-password" id="toggle-password-btn" tabindex="-1">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path
                                    d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                <line x1="1" y1="1" x2="23" y2="23" />
                            </svg>
                        </button>
                    </div>

                    <!-- Remember Me / Forgot Password -->
                    <div class="login-options-row">
                        <label class="login-remember-label">
                            <input type="checkbox" name="remember" checked />
                            Remember me
                        </label>
                        <a href="{{ url('/forgot-password') }}" class="forgot-pass-link">Forgot Password?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-submit-btn" id="login-submit-btn">
                        Login <span class="btn-arrow">&rarr;</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="login-divider-text">New to Aspire Learners?</div>

                <!-- Create Account -->
                <a href="{{ url('register') }}" class="login-create-btn">Create Account</a>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade text-left" id="forgotPasswordModal" tabindex="-1" role="dialog"
        aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Your Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="forgot-password-form">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-2" style="font-size: 0.9rem;">Enter your email address and we'll send you a
                            link to reset your password.</p>

                        <div id="modal-alert-container"></div>

                        <div class="form-label-group position-relative has-icon-left mb-0">
                            <input type="email" id="forgot-email" name="email" class="form-control"
                                placeholder="Email Address" required>
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
                                <i class="feather icon-loader"
                                    style="animation: spin 1s linear infinite; display: inline-block;"></i>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle password visibility
            const toggleBtn = document.getElementById('toggle-password-btn');
            const passwordInput = document.getElementById('user-password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeIcon.style.display = 'none';
                        eyeOffIcon.style.display = 'block';
                    } else {
                        passwordInput.type = 'password';
                        eyeIcon.style.display = 'block';
                        eyeOffIcon.style.display = 'none';
                    }
                });
            }

            // Forgot Password Modal AJAX
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

            // Reset modal state when it's closed
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