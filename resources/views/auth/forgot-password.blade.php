@extends('auth.layout.auth')

@section('title', 'Aspire Learners - Forgot Password')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* ==========================================================
       ASPIRE LEARNERS - FORGOT PASSWORD PAGE STYLES
       Matches reference design exactly & responsive
    ========================================================== */

    /* Reset & Base */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body.blank-page {
        background: #eef2f7 !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        overflow-x: hidden;
    }

    .app-content.content,
    .content-wrapper,
    .content-body {
        padding: 0 !important;
        margin: 0 !important;
        height: 100%;
    }

    /* ---- Page Wrapper ---- */
    .forgot-page-wrapper {
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px 20px;
        box-sizing: border-box;
        background: #eef2f7;
    }

    /* Desktop Viewport optimization to eliminate outer page scroll */
    @media (min-width: 901px) and (min-height: 600px) {
        .forgot-page-wrapper {
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
            padding: 12px 24px;
        }
    }

    /* ---- Main Card ---- */
    .forgot-card {
        width: 100%;
        max-width: 1040px;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 16px 45px rgba(30, 58, 138, 0.08), 0 2px 14px rgba(0, 0, 0, 0.04);
        display: flex;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;
    }

    @media (min-width: 901px) and (min-height: 600px) {
        .forgot-card {
            max-height: min(680px, calc(100vh - 24px));
        }
    }

    /* ==========================================================
       LEFT PANEL - BRANDING & MOTIVATIONAL ARTWORK
    ========================================================== */
    .forgot-left-panel {
        width: 48%;
        min-width: 48%;
        position: relative;
        background: linear-gradient(180deg, #e9f2fd 0%, #f4f8fe 45%, #e1effc 100%);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 28px 28px 0;
        overflow: hidden;
        box-sizing: border-box;
    }

    /* Decorative soft background wave accent */
    .left-decor-wave {
        position: absolute;
        top: -40px;
        right: -40px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(191, 219, 254, 0.45) 0%, rgba(219, 234, 254, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 1;
    }

    /* Top Brand Logo Header */
    .brand-header-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        position: relative;
        z-index: 3;
    }

    .brand-logo-img {
        width: 44px;
        height: auto;
        object-fit: contain;
    }

    .brand-text-block {
        display: flex;
        flex-direction: column;
    }

    .brand-main-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.3px;
    }

    .brand-main-title .brand-red {
        color: #e11d48;
    }

    .brand-main-title .brand-navy {
        color: #1e3a8a;
    }

    .brand-tagline-text {
        font-family: 'Inter', sans-serif;
        font-size: 0.64rem;
        color: #2563eb;
        font-weight: 600;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        margin-top: 2px;
    }

    /* Top Info Section */
    .left-info-block {
        position: relative;
        z-index: 3;
        margin-bottom: 12px;
    }

    .left-headline {
        font-family: 'Poppins', sans-serif;
        font-size: 1.95rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
        margin: 0 0 10px 0;
        letter-spacing: -0.5px;
    }

    .left-description {
        font-family: 'Inter', sans-serif;
        font-size: 0.86rem;
        color: #475569;
        line-height: 1.5;
        margin: 0;
        max-width: 330px;
    }

    /* Still Life Desk Artwork */
    .left-desk-artwork-wrap {
        position: relative;
        z-index: 2;
        width: 100%;
        margin-top: auto;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        -webkit-mask-image: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.5) 4%, rgba(0,0,0,1) 14%, rgba(0,0,0,1) 100%);
        mask-image: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.5) 4%, rgba(0,0,0,1) 14%, rgba(0,0,0,1) 100%);
    }

    .desk-artwork-img {
        width: 100%;
        height: auto;
        object-fit: cover;
        object-position: bottom center;
        display: block;
    }

    /* ==========================================================
       RIGHT PANEL - FORGOT PASSWORD FORM
    ========================================================== */
    .forgot-right-panel {
        width: 52%;
        min-width: 52%;
        padding: 36px 42px 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow-y: auto;
        box-sizing: border-box;
        background: #ffffff;
    }

    /* Custom subtle scrollbar if right panel needs it on very small heights */
    .forgot-right-panel::-webkit-scrollbar {
        width: 5px;
    }
    .forgot-right-panel::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .forgot-right-panel::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    /* Back to Login link */
    .forgot-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        align-self: flex-end;
        font-family: 'Inter', sans-serif;
        font-size: 0.86rem;
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 14px;
        transition: color 0.2s, transform 0.2s;
    }

    .forgot-back-link:hover {
        color: #1d4ed8;
        text-decoration: none;
        transform: translateX(-2px);
    }

    .forgot-back-link svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2.2;
    }

    /* Circular Lock Icon Badge */
    .lock-icon-badge {
        width: 54px;
        height: 54px;
        min-width: 54px;
        background: #ede9fe;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.10);
    }

    .lock-icon-badge svg {
        width: 24px;
        height: 24px;
        color: #7c3aed;
        stroke: #7c3aed;
        fill: #7c3aed;
    }

    /* Titles */
    .forgot-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.85rem;
        font-weight: 800;
        color: #111827;
        margin: 0 0 6px 0;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    .forgot-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        color: #475569;
        margin: 0 0 22px 0;
        font-weight: 400;
        line-height: 1.5;
        max-width: 440px;
    }

    /* Form Field */
    .form-field-wrap {
        margin-bottom: 16px;
    }

    .form-field-label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 0.86rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .forgot-input-group {
        position: relative;
        background: #ffffff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .forgot-input-group:hover {
        border-color: #cbd5e1;
    }

    .forgot-input-group:focus-within {
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .forgot-input-group .input-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        min-width: 42px;
        color: #94a3b8;
    }

    .forgot-input-group .input-icon svg {
        width: 18px;
        height: 18px;
        stroke: #94a3b8;
        fill: none;
        stroke-width: 1.9;
        transition: stroke 0.2s;
    }

    .forgot-input-group:focus-within .input-icon svg {
        stroke: #2563eb;
    }

    .forgot-input-group input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 12px 14px 12px 0;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        color: #1e293b;
        outline: none;
    }

    .forgot-input-group input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    /* Send Reset Link Button */
    .forgot-submit-btn {
        width: 100%;
        padding: 12.5px 20px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-size: 0.98rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
        margin-bottom: 20px;
    }

    .forgot-submit-btn:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.38);
        transform: translateY(-1px);
    }

    .forgot-submit-btn:active {
        transform: translateY(0);
    }

    /* Info Box ("Check your inbox") */
    .forgot-info-box {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 14px;
        padding: 13px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 22px;
    }

    .info-box-icon {
        width: 24px;
        height: 24px;
        min-width: 24px;
        margin-top: 1px;
        flex-shrink: 0;
    }

    .info-box-icon svg {
        width: 24px;
        height: 24px;
    }

    .info-box-body {
        display: flex;
        flex-direction: column;
    }

    .info-box-title {
        font-family: 'Inter', sans-serif;
        font-size: 0.86rem;
        font-weight: 700;
        color: #1e3a8a;
        margin: 0 0 2px 0;
        line-height: 1.3;
    }

    .info-box-desc {
        font-family: 'Inter', sans-serif;
        font-size: 0.78rem;
        color: #475569;
        margin: 0;
        line-height: 1.45;
    }

    /* "Need more help?" Divider */
    .help-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .help-divider .line {
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .help-divider span {
        font-family: 'Inter', sans-serif;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Support Row */
    .support-footer-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        text-align: center;
    }

    .support-action-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-family: 'Inter', sans-serif;
        font-size: 0.86rem;
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .support-action-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    .support-action-link svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .support-email-text {
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        color: #64748b;
    }

    /* Status & Error Alerts */
    .forgot-alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 16px;
        color: #065f46;
        font-size: 0.84rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .forgot-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 16px;
        color: #dc2626;
        font-size: 0.84rem;
    }

    .forgot-alert-error ul {
        margin: 0;
        padding-left: 16px;
    }

    /* ==========================================================
       RESPONSIVE BEHAVIOR (MOBILE UI/UX)
    ========================================================== */
    @media (max-width: 900px) {
        .forgot-page-wrapper {
            height: auto !important;
            min-height: 100vh;
            overflow-y: auto !important;
            padding: 14px 12px 36px !important;
            align-items: flex-start;
            background: #eef2f7;
        }

        .forgot-card {
            flex-direction: column;
            max-width: 490px;
            margin: 6px auto 20px;
            border-radius: 20px;
            max-height: none !important;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Mobile top banner */
        .forgot-left-panel {
            width: 100%;
            min-width: 100%;
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
        }

        .brand-header-row {
            margin-bottom: 12px;
            gap: 8px;
        }

        .brand-logo-img {
            width: 36px;
        }

        .brand-main-title {
            font-size: 1.05rem;
        }

        .left-headline {
            font-size: 1.4rem;
            margin-bottom: 6px;
        }

        .left-description {
            font-size: 0.8rem;
            max-width: none;
        }

        /* Hide the heavy desk illustration on mobile for super fast loading */
        .left-desk-artwork-wrap {
            display: none !important;
        }

        /* Form panel on mobile */
        .forgot-right-panel {
            width: 100%;
            min-width: 100%;
            padding: 24px 20px 28px;
            overflow-y: visible;
        }

        .forgot-back-link {
            font-size: 0.82rem;
            margin-bottom: 12px;
        }

        .lock-icon-badge {
            width: 48px;
            height: 48px;
            min-width: 48px;
            margin-bottom: 14px;
        }

        .lock-icon-badge svg {
            width: 22px;
            height: 22px;
        }

        .forgot-title {
            font-size: 1.6rem;
        }

        .forgot-subtitle {
            font-size: 0.84rem;
            margin-bottom: 20px;
        }

        .forgot-input-group input {
            font-size: 16px !important; /* CRITICAL: Prevents iOS auto-zoom */
            padding: 12px 14px 12px 0;
        }

        .forgot-submit-btn {
            padding: 13px 20px;
            font-size: 1rem;
            border-radius: 12px;
        }
    }

    @media (max-width: 480px) {
        .forgot-page-wrapper {
            padding: 6px 6px 30px !important;
        }

        .forgot-card {
            border-radius: 16px;
            margin: 2px auto 16px;
        }

        .forgot-left-panel {
            padding: 16px 16px 14px;
        }

        .forgot-right-panel {
            padding: 20px 16px 24px;
        }

        .forgot-title {
            font-size: 1.45rem;
        }
    }
</style>
@endpush

@section('content')

    <div class="forgot-page-wrapper">

        <!-- ===== Main Forgot Password Card ===== -->
        <div class="forgot-card">

            <!-- ====================================================
                 LEFT PANEL (PURE HTML / CSS DESIGN)
                 Matches Reference Design UI/UX Exactly
            ==================================================== -->
            <div class="forgot-left-panel">
                <!-- Soft background decorative accent -->
                <div class="left-decor-wave"></div>

                <!-- 1. Top Brand Logo Header -->
                <div class="brand-header-row">
                    <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Full-Logo.png') }}"
                         alt="Aspire Learners" class="brand-logo-img" />
                    <div class="brand-text-block">
                        <div class="brand-main-title">
                            <span class="brand-red">A</span>SPIRE <span class="brand-navy">LEARNERS</span>
                        </div>
                        <div class="brand-tagline-text">Learn | Practise | Progress</div>
                    </div>
                </div>

                <!-- 2. Headline & Description Text -->
                <div class="left-info-block">
                    <h2 class="left-headline">
                        Brighter Learners<br>Brighter Futures
                    </h2>
                    <p class="left-description">
                        A supportive learning platform to help every child achieve their potential.
                    </p>
                </div>

                <!-- 3. Desk Books Still Life Artwork with "Small Steps Big Success" -->
                <div class="left-desk-artwork-wrap">
                    <img src="{{ asset('theme/app-assets/images/auth/forgot-password-desk.png') }}"
                         alt="Books stacked with Confidence, Practice, Progress, Success and Believe Practise Achieve mug"
                         class="desk-artwork-img" />
                </div>
            </div>

            <!-- ====================================================
                 RIGHT PANEL (FORGOT PASSWORD FORM)
            ==================================================== -->
            <div class="forgot-right-panel">

                <!-- Back to Login -->
                <a href="{{ url('login') }}" class="forgot-back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Back to Login
                </a>

                <!-- Purple Lock Icon Badge -->
                <div class="lock-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <rect x="4" y="11" width="16" height="10" rx="3" fill="#7c3aed" stroke="none"/>
                        <path d="M8 11V7a4 4 0 0 1 8 0v4" fill="none" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="12" cy="16" r="1.5" fill="#ffffff"/>
                    </svg>
                </div>

                <!-- Titles -->
                <h1 class="forgot-title">Forgot Password?</h1>
                <p class="forgot-subtitle">
                    No worries! Enter your registered email address and we’ll send you a link to reset your password.
                </p>

                <!-- Status Feedback (when link sent) -->
                @if (session('status'))
                    <div class="forgot-alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="forgot-alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Password Reset Request Form -->
                <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                    @csrf

                    <div class="form-field-wrap">
                        <label for="email" class="form-field-label">Email address</label>
                        <div class="forgot-input-group">
                            <div class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <path d="M22 4l-10 8L2 4"/>
                                </svg>
                            </div>
                            <input type="email" name="email" id="email"
                                   placeholder="Enter your email address" required
                                   value="{{ old('email') }}" autocomplete="email" autofocus />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="forgot-submit-btn">Send Reset Link</button>
                </form>

                <!-- Info Box: Check your inbox -->
                <div class="forgot-info-box">
                    <div class="info-box-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="#3b82f6"/>
                            <path d="M12 16V11" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="12" cy="8" r="1" fill="#ffffff"/>
                        </svg>
                    </div>
                    <div class="info-box-body">
                        <h4 class="info-box-title">Check your inbox</h4>
                        <p class="info-box-desc">
                            We’ll send a password reset link to your email address. If you don’t see it, please check your spam folder.
                        </p>
                    </div>
                </div>

                <!-- Divider: Need more help? -->
                <div class="help-divider">
                    <div class="line"></div>
                    <span>Need more help?</span>
                    <div class="line"></div>
                </div>

                <!-- Contact Support -->
                <div class="support-footer-wrap">
                    <a href="mailto:support@aspirelearners.co.uk" class="support-action-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="M22 4l-10 8L2 4"/>
                        </svg>
                        Contact support
                    </a>
                    <span class="support-email-text">support@aspirelearners.co.uk</span>
                </div>

            </div>
        </div>
    </div>

@endsection
