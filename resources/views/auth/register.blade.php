@extends('auth.layout.auth')

@section('title', 'Aspire Learners - Register')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* ==========================================================
       ASPIRE LEARNERS - REGISTER PAGE STYLES
       Complete Pure HTML/CSS UI matching reference design exactly
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
    .register-page-wrapper {
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
    @media (min-width: 901px) and (min-height: 620px) {
        .register-page-wrapper {
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
            padding: 12px 24px;
        }
    }

    /* ---- Main Register Card ---- */
    .register-card {
        width: 100%;
        max-width: 1080px;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 16px 45px rgba(30, 58, 138, 0.08), 0 2px 14px rgba(0, 0, 0, 0.04);
        display: flex;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;
    }

    @media (min-width: 901px) and (min-height: 620px) {
        .register-card {
            max-height: min(710px, calc(100vh - 24px));
        }
    }

    /* ==========================================================
       LEFT PANEL - PURE HTML/CSS DESIGN
    ========================================================== */
    .register-left-panel {
        width: 52%;
        min-width: 52%;
        position: relative;
        background: linear-gradient(180deg, #e9f2fd 0%, #f3f8fe 45%, #e0eefc 100%);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 24px 26px 18px;
        overflow: hidden;
        box-sizing: border-box;
    }

    /* Decorative soft background wave accent */
    .left-decor-wave {
        position: absolute;
        bottom: -20px;
        left: -30px;
        width: 240px;
        height: 160px;
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
        margin-bottom: 12px;
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

    /* Center Middle Layout: Text Features (Left) + Illustration (Right) */
    .left-middle-row {
        position: relative;
        display: flex;
        flex: 1;
        align-items: flex-start;
        z-index: 2;
        min-height: 0;
    }

    /* Left Info Column */
    .left-info-column {
        width: 58%;
        max-width: 280px;
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
    }

    .left-headline {
        font-family: 'Poppins', sans-serif;
        font-size: 1.62rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
        margin: 0 0 6px 0;
        letter-spacing: -0.4px;
    }

    .left-description {
        font-family: 'Inter', sans-serif;
        font-size: 0.77rem;
        color: #475569;
        line-height: 1.45;
        margin: 0 0 14px 0;
    }

    /* Feature List */
    .feature-items-list {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .feature-item-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .feature-badge-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .feature-badge-icon svg {
        width: 16px;
        height: 16px;
    }

    /* Badge Color Schemes Matching Reference */
    .badge-pink {
        background: #fee2e2;
        color: #ef4444;
    }

    .badge-cyan {
        background: #e0f2fe;
        color: #0284c7;
    }

    .badge-emerald {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-violet {
        background: #f3e8ff;
        color: #9333ea;
    }

    .feature-content {
        display: flex;
        flex-direction: column;
    }

    .feature-item-title {
        font-family: 'Inter', sans-serif;
        font-size: 0.77rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.25;
    }

    .feature-item-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 0.68rem;
        color: #64748b;
        font-weight: 400;
        line-height: 1.25;
    }

    /* Right Illustration Column in Left Panel */
    .left-illustration-column {
        position: absolute;
        right: -10px;
        bottom: 0px;
        width: 58%;
        height: 104%;
        pointer-events: none;
        z-index: 2;
        display: flex;
        align-items: flex-end;
        justify-content: flex-end;
        -webkit-mask-image: linear-gradient(to right, transparent 0%, rgba(0,0,0,0.5) 8%, rgba(0,0,0,1) 18%, rgba(0,0,0,1) 100%);
        mask-image: linear-gradient(to right, transparent 0%, rgba(0,0,0,0.5) 8%, rgba(0,0,0,1) 18%, rgba(0,0,0,1) 100%);
    }

    .student-desk-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: right bottom;
        display: block;
    }

    /* Bottom Bar: Script Text & Testimonial Box */
    .left-bottom-row {
        position: relative;
        z-index: 4;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
        margin-top: 10px;
        padding-top: 4px;
    }

    /* "Small Steps Big Success" stylish handwritten lettering */
    .script-lettering-wrap {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding-left: 4px;
        flex-shrink: 0;
    }

    .script-lettering {
        font-family: 'Caveat', cursive;
        font-size: 1.6rem;
        font-weight: 700;
        color: #1d4ed8;
        line-height: 1.05;
        letter-spacing: 0.3px;
        transform: rotate(-4deg);
        transform-origin: left bottom;
        text-shadow: 0 1px 3px rgba(37, 99, 235, 0.12);
    }

    .script-swoosh-svg {
        width: 95px;
        height: 10px;
        margin-top: -2px;
        transform: rotate(-3deg);
    }

    /* Testimonial Card */
    .left-testimonial-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.95);
        border-radius: 14px;
        padding: 9px 15px;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.08);
        max-width: 310px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .testimonial-quote-symbol {
        font-family: Georgia, serif;
        font-size: 1.6rem;
        color: #60a5fa;
        line-height: 0.9;
        flex-shrink: 0;
        user-select: none;
    }

    .testimonial-content-wrap {
        display: flex;
        flex-direction: column;
    }

    .testimonial-quote-text {
        font-family: 'Inter', sans-serif;
        font-size: 0.74rem;
        color: #1e293b;
        font-style: italic;
        line-height: 1.35;
        margin: 0 0 2px 0;
    }

    .testimonial-author-text {
        font-family: 'Inter', sans-serif;
        font-size: 0.68rem;
        color: #64748b;
        font-weight: 600;
        text-align: right;
    }

    /* ==========================================================
       RIGHT PANEL - REGISTRATION FORM
    ========================================================== */
    .register-right-panel {
        width: 48%;
        min-width: 48%;
        padding: 24px 34px 18px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow-y: auto;
        box-sizing: border-box;
        background: #ffffff;
    }

    /* Custom subtle scrollbar if right panel needs it on very small heights */
    .register-right-panel::-webkit-scrollbar {
        width: 5px;
    }
    .register-right-panel::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .register-right-panel::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    /* Back to Login link */
    .register-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        align-self: flex-end;
        font-family: 'Inter', sans-serif;
        font-size: 0.84rem;
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 6px;
        transition: color 0.2s, transform 0.2s;
    }

    .register-back-link:hover {
        color: #1d4ed8;
        text-decoration: none;
        transform: translateX(-2px);
    }

    .register-back-link svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2.2;
    }

    .register-right-panel .form-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.55rem;
        font-weight: 800;
        color: #111827;
        margin: 0 0 2px 0;
        letter-spacing: -0.4px;
        line-height: 1.25;
    }

    .register-right-panel .form-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        color: #6b7280;
        margin: 0 0 14px 0;
        font-weight: 400;
        line-height: 1.4;
    }

    /* Form Input Groups */
    .reg-input-group {
        position: relative;
        margin-bottom: 9px;
        background: #ffffff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .reg-input-group:hover {
        border-color: #cbd5e1;
    }

    .reg-input-group:focus-within {
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .reg-input-group .input-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        min-width: 38px;
        color: #94a3b8;
    }

    .reg-input-group .input-icon svg {
        width: 17px;
        height: 17px;
        stroke: #94a3b8;
        fill: none;
        stroke-width: 1.9;
        transition: stroke 0.2s;
    }

    .reg-input-group:focus-within .input-icon svg {
        stroke: #2563eb;
    }

    .reg-input-group input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 9px 12px 9px 0;
        font-family: 'Inter', sans-serif;
        font-size: 0.86rem;
        color: #1e293b;
        outline: none;
    }

    .reg-input-group input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .reg-input-group .toggle-password {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        min-width: 38px;
        cursor: pointer;
        color: #94a3b8;
        background: none;
        border: none;
        padding: 0;
        transition: color 0.2s;
    }

    .reg-input-group .toggle-password:hover {
        color: #2563eb;
    }

    .reg-input-group .toggle-password svg {
        width: 17px;
        height: 17px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.9;
    }

    /* Username feedback */
    .username-feedback-wrap {
        margin-top: -6px;
        margin-bottom: 8px;
        padding-left: 4px;
    }

    .username-feedback-wrap .text-success {
        color: #059669 !important;
        font-size: 0.78rem;
        font-weight: 500;
    }

    .username-feedback-wrap .text-danger {
        color: #dc2626 !important;
        font-size: 0.78rem;
        font-weight: 500;
    }

    .suggestion-pill {
        cursor: pointer;
        background-color: rgba(37, 99, 235, 0.08);
        color: #2563eb;
        border: 1px solid rgba(37, 99, 235, 0.25);
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 0.72rem;
        margin-right: 5px;
        margin-bottom: 4px;
        display: inline-block;
        transition: all 0.2s ease-in-out;
        font-family: 'Inter', sans-serif;
    }

    .suggestion-pill:hover {
        background-color: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    /* Username Spinner */
    .username-spinner-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
        z-index: 10;
        color: #2563eb;
    }

    .username-spinner-icon i {
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    /* Role Selector */
    .reg-role-section {
        margin-bottom: 9px;
    }

    .reg-role-label {
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .reg-role-options {
        display: flex;
        gap: 20px;
    }

    .reg-role-option {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        font-size: 0.84rem;
        color: #374151;
        user-select: none;
    }

    .reg-role-option input[type="radio"] {
        width: 16px;
        height: 16px;
        accent-color: #2563eb;
        cursor: pointer;
        margin: 0;
    }

    /* Terms Checkbox */
    .reg-terms-row {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin-bottom: 12px;
    }

    .reg-terms-row input[type="checkbox"] {
        width: 15px;
        height: 15px;
        accent-color: #2563eb;
        margin-top: 2px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .reg-terms-row label {
        font-family: 'Inter', sans-serif;
        font-size: 0.79rem;
        color: #64748b;
        cursor: pointer;
        line-height: 1.35;
        margin: 0;
    }

    .reg-terms-row label a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
    }

    .reg-terms-row label a:hover {
        text-decoration: underline;
    }

    /* Create Account Button */
    .reg-submit-btn {
        width: 100%;
        padding: 10.5px 20px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 0.94rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        margin-bottom: 9px;
    }

    .reg-submit-btn:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        transform: translateY(-1px);
    }

    .reg-submit-btn:active {
        transform: translateY(0);
    }

    /* OR Divider */
    .reg-or-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 9px;
    }

    .reg-or-divider .line {
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .reg-or-divider span {
        font-family: 'Inter', sans-serif;
        font-size: 0.76rem;
        color: #94a3b8;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Google Button */
    .reg-google-btn {
        width: 100%;
        padding: 9px 20px;
        background: #ffffff;
        color: #334155;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 0.86rem;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: all 0.2s ease;
        margin-bottom: 11px;
        box-sizing: border-box;
    }

    .reg-google-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        text-decoration: none;
        color: #1e293b;
    }

    .reg-google-btn .google-icon {
        width: 18px;
        height: 18px;
    }

    /* Bottom Login Link */
    .reg-login-link {
        text-align: center;
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        color: #64748b;
    }

    .reg-login-link a {
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
        margin-left: 4px;
    }

    .reg-login-link a:hover {
        text-decoration: underline;
    }

    /* Error Alert */
    .reg-error-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 10px;
    }

    .reg-error-alert ul {
        margin: 0;
        padding-left: 16px;
    }

    .reg-error-alert li {
        font-family: 'Inter', sans-serif;
        font-size: 0.78rem;
        color: #dc2626;
        line-height: 1.4;
    }

    /* Mobile Social Proof - Hidden on desktop */
    .mobile-social-proof {
        display: none;
    }

    /* ==========================================================
       RESPONSIVE BEHAVIOR (MOBILE UI/UX OPTIMIZED)
    ========================================================== */
    @media (max-width: 900px) {
        .register-page-wrapper {
            height: auto !important;
            min-height: 100vh;
            overflow-y: auto !important;
            padding: 14px 12px 36px !important;
            align-items: flex-start;
            background: #eef2f7;
        }

        .register-card {
            flex-direction: column;
            max-width: 500px;
            margin: 6px auto 20px;
            border-radius: 20px;
            max-height: none !important;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Compact, modern mobile brand banner */
        .register-left-panel {
            width: 100%;
            min-width: 100%;
            padding: 18px 18px 14px;
            background: linear-gradient(175deg, #e9f2fd 0%, #f4f8fe 50%, #e1effc 100%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
        }

        .brand-header-row {
            margin-bottom: 10px;
            gap: 8px;
        }

        .brand-logo-img {
            width: 36px;
        }

        .brand-main-title {
            font-size: 1.05rem;
        }

        .brand-tagline-text {
            font-size: 0.58rem;
            letter-spacing: 1.2px;
        }

        .left-info-column {
            width: 100%;
            max-width: none;
        }

        .left-headline {
            font-size: 1.25rem;
            margin-bottom: 4px;
            line-height: 1.25;
        }

        .left-description {
            font-size: 0.76rem;
            margin-bottom: 10px;
            line-height: 1.35;
            color: #475569;
        }

        /* Horizontal scrolling feature chips on mobile! */
        .feature-items-list {
            display: flex !important;
            flex-direction: row !important;
            overflow-x: auto !important;
            padding: 2px 2px 8px !important;
            gap: 8px !important;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .feature-items-list::-webkit-scrollbar {
            display: none !important;
        }

        .feature-item-row {
            display: inline-flex !important;
            align-items: center !important;
            background: #ffffff !important;
            border-radius: 20px !important;
            padding: 5px 12px 5px 6px !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03) !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            gap: 6px !important;
        }

        .feature-badge-icon {
            width: 24px !important;
            height: 24px !important;
            min-width: 24px !important;
        }

        .feature-badge-icon svg {
            width: 13px !important;
            height: 13px !important;
        }

        .feature-item-title {
            font-size: 0.72rem !important;
            font-weight: 600 !important;
        }

        .feature-item-subtitle {
            display: none !important; /* Subtitle hidden in mobile chips to keep them sleek & compact */
        }

        .left-illustration-column {
            display: none !important;
        }

        .left-bottom-row {
            display: none !important; /* Shown in mobile-social-proof at the bottom of the card */
        }

        /* Form styling on mobile */
        .register-right-panel {
            width: 100%;
            min-width: 100%;
            padding: 22px 20px 24px;
            overflow-y: visible;
        }

        .register-back-link {
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        .register-right-panel .form-title {
            font-size: 1.45rem;
            letter-spacing: -0.3px;
        }

        .register-right-panel .form-subtitle {
            font-size: 0.8rem;
            margin-bottom: 16px;
        }

        .reg-input-group {
            margin-bottom: 12px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
        }

        .reg-input-group:focus-within {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .reg-input-group input {
            font-size: 16px !important; /* CRITICAL: Prevents iOS auto-zoom on focus */
            padding: 12px 12px 12px 0;
        }

        .reg-input-group .input-icon {
            width: 42px;
            min-width: 42px;
        }

        .reg-input-group .toggle-password {
            width: 42px;
            min-width: 42px;
        }

        /* Mobile Segmented Role Picker */
        .reg-role-section {
            margin-bottom: 14px;
        }

        .reg-role-label {
            font-size: 0.82rem;
            margin-bottom: 6px;
        }

        .reg-role-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 12px;
        }

        .reg-role-option {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 8px;
            border-radius: 9px;
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            background: transparent;
            color: #475569;
            text-align: center;
            gap: 6px;
        }

        .reg-role-option:has(input:checked) {
            background: #ffffff;
            color: #1e3a8a;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .reg-terms-row {
            margin-bottom: 16px;
            align-items: center;
            gap: 8px;
        }

        .reg-terms-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 0;
        }

        .reg-terms-row label {
            font-size: 0.82rem;
            line-height: 1.35;
        }

        .reg-submit-btn {
            padding: 13px 20px;
            font-size: 1rem;
            border-radius: 12px;
            margin-bottom: 12px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
        }

        .reg-google-btn {
            padding: 11.5px 20px;
            font-size: 0.9rem;
            border-radius: 12px;
            margin-bottom: 14px;
        }

        .reg-login-link {
            font-size: 0.86rem;
        }

        /* Mobile Social Proof Footer */
        .mobile-social-proof {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px dashed #e2e8f0;
            gap: 12px;
            width: 100%;
        }

        .mobile-testimonial-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        .mobile-quote-symbol {
            font-family: Georgia, serif;
            font-size: 1.6rem;
            color: #60a5fa;
            line-height: 0.9;
            flex-shrink: 0;
        }

        .mobile-quote-body {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .mobile-quote-text {
            font-family: 'Inter', sans-serif;
            font-size: 0.74rem;
            color: #334155;
            font-style: italic;
            line-height: 1.35;
            margin: 0 0 2px 0;
        }

        .mobile-quote-author {
            font-family: 'Inter', sans-serif;
            font-size: 0.68rem;
            color: #64748b;
            font-weight: 600;
            text-align: right;
            display: block;
        }

        .mobile-script-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .mobile-script-text {
            font-family: 'Caveat', cursive;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1d4ed8;
            line-height: 1;
            transform: rotate(-3deg);
        }

        .mobile-script-swoosh {
            width: 90px;
            height: 10px;
            margin-top: -2px;
            transform: rotate(-2deg);
        }
    }

    @media (max-width: 480px) {
        .register-page-wrapper {
            padding: 6px 6px 30px !important;
        }

        .register-card {
            border-radius: 16px;
            margin: 2px auto 16px;
        }

        .register-left-panel {
            padding: 16px 14px 12px;
        }

        .register-right-panel {
            padding: 18px 14px 20px;
        }

        .register-right-panel .form-title {
            font-size: 1.35rem;
        }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')

    <div class="register-page-wrapper">

        <!-- ===== Main Register Card ===== -->
        <div class="register-card">

            <!-- ====================================================
                 LEFT PANEL (PURE HTML / CSS DESIGN)
                 Matches Reference Design UI/UX Exactly
            ==================================================== -->
            <div class="register-left-panel">
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

                <!-- 2. Middle Row: Features Content (Left) + Student Illustration (Right) -->
                <div class="left-middle-row">
                    <!-- Left Column: Title, Subtitle, 4 Features -->
                    <div class="left-info-column">
                        <h2 class="left-headline">
                            A Brighter<br>Future Starts<br>Here!
                        </h2>
                        <p class="left-description">
                            Join Aspire Learners and get access to personalised practice, mock tests, detailed progress reports and more.
                        </p>

                        <!-- Feature List Items -->
                        <div class="feature-items-list">
                            <!-- Feature 1: Wide range of practice tests -->
                            <div class="feature-item-row">
                                <div class="feature-badge-icon badge-pink">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                </div>
                                <div class="feature-content">
                                    <div class="feature-item-title">Wide range of practice tests</div>
                                    <div class="feature-item-subtitle">Build confidence with exam-style questions</div>
                                </div>
                            </div>

                            <!-- Feature 2: Track your progress -->
                            <div class="feature-item-row">
                                <div class="feature-badge-icon badge-cyan">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="20" x2="18" y2="10"></line>
                                        <line x1="12" y1="20" x2="12" y2="4"></line>
                                        <line x1="6" y1="20" x2="6" y2="14"></line>
                                    </svg>
                                </div>
                                <div class="feature-content">
                                    <div class="feature-item-title">Track your progress</div>
                                    <div class="feature-item-subtitle">See your improvement over time</div>
                                </div>
                            </div>

                            <!-- Feature 3: Learn at your own pace -->
                            <div class="feature-item-row">
                                <div class="feature-badge-icon badge-emerald">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </div>
                                <div class="feature-content">
                                    <div class="feature-item-title">Learn at your own pace</div>
                                    <div class="feature-item-subtitle">Anytime, anywhere</div>
                                </div>
                            </div>

                            <!-- Feature 4: Support from expert tutors -->
                            <div class="feature-item-row">
                                <div class="feature-badge-icon badge-violet">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                                <div class="feature-content">
                                    <div class="feature-item-title">Support from expert tutors</div>
                                    <div class="feature-item-subtitle">Guidance when you need it</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Student Desk Illustration -->
                    <div class="left-illustration-column">
                        <img src="{{ asset('theme/app-assets/images/auth/student-illustration.png') }}"
                             alt="Student celebrating learning success" class="student-desk-img" />
                    </div>
                </div>

                <!-- 3. Bottom Row: "Small Steps Big Success" + Testimonial Card -->
                <div class="left-bottom-row">
                    <!-- Script Lettering with yellow curved swoosh underline -->
                    <div class="script-lettering-wrap">
                        <span class="script-lettering">Small Steps<br>Big Success</span>
                        <svg class="script-swoosh-svg" viewBox="0 0 100 12" fill="none">
                            <path d="M3 9 C 30 2, 70 2, 97 9" stroke="#facc15" stroke-width="3.5" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <!-- Testimonial Quote Card -->
                    <div class="left-testimonial-card">
                        <span class="testimonial-quote-symbol">&ldquo;</span>
                        <div class="testimonial-content-wrap">
                            <p class="testimonial-quote-text">&ldquo;Excellent resources and support! My child&rsquo;s confidence has really grown.&rdquo;</p>
                            <span class="testimonial-author-text">&mdash; A Parent</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====================================================
                 RIGHT PANEL (REGISTRATION FORM)
            ==================================================== -->
            <div class="register-right-panel">

                <!-- Back to Login -->
                <a href="{{ url('login') }}" class="register-back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Back to Login
                </a>

                <h1 class="form-title">Create Your Account</h1>
                <p class="form-subtitle">Join Aspire Learners and start your learning journey today.</p>

                @if ($errors->any())
                    <div class="reg-error-alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="register-form">
                    @csrf

                    <!-- Name -->
                    <div class="reg-input-group">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <input type="text" name="name" id="name"
                               placeholder="Student's Full Name" required
                               value="{{ old('name') }}" autocomplete="name" autofocus />
                    </div>

                    <!-- Username -->
                    <div class="reg-input-group" style="position: relative;">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="4"/>
                                <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/>
                            </svg>
                        </div>
                        <input type="text" name="username" id="username"
                               placeholder="Username" required
                               value="{{ old('username') }}" autocomplete="username" />
                        <span class="username-spinner-icon" id="username-spinner">
                            <i class="feather icon-loader"></i>
                        </span>
                    </div>

                    <!-- Username Feedback -->
                    <div class="username-feedback-wrap">
                        <div id="username-feedback" style="display: none;">
                            <span id="username-feedback-text"></span>
                        </div>
                        <div id="username-suggestions" style="display: none;">
                            <div class="text-muted small mb-25" style="font-size: 0.72rem;">Suggested Usernames:</div>
                            <div id="suggestions-list" class="d-flex flex-wrap align-items-center"></div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="reg-input-group">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="M22 4l-10 8L2 4"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email"
                               placeholder="Parent/Guardian Email Address" required
                               value="{{ old('email') }}" autocomplete="email" />
                    </div>

                    <!-- Password -->
                    <div class="reg-input-group">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password"
                               placeholder="Create Password" required autocomplete="new-password" />
                        <button type="button" class="toggle-password" data-target="password" tabindex="-1">
                            <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Confirm Password -->
                    <div class="reg-input-group">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <input type="password" name="password_confirmation" id="password-confirm"
                               placeholder="Confirm Password" required autocomplete="new-password" />
                        <button type="button" class="toggle-password" data-target="password-confirm" tabindex="-1">
                            <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Role Selection -->
                    <div class="reg-role-section">
                        <div class="reg-role-label">I am a:</div>
                        <div class="reg-role-options">
                            <label class="reg-role-option">
                                <input type="radio" name="role" value="parent" {{ old('role', 'parent') == 'parent' ? 'checked' : '' }} />
                                Parent/Guardian
                            </label>
                            <label class="reg-role-option">
                                <input type="radio" name="role" value="student" {{ old('role') == 'student' ? 'checked' : '' }} />
                                Student
                            </label>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="reg-terms-row">
                        <input type="checkbox" id="terms-checkbox" name="terms" required />
                        <label for="terms-checkbox">
                            I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="reg-submit-btn">Create Account</button>
                </form>

                <!-- OR Divider -->
                <div class="reg-or-divider">
                    <div class="line"></div>
                    <span>OR</span>
                    <div class="line"></div>
                </div>

                <!-- Google Sign Up -->
                <a href="#" class="reg-google-btn">
                    <svg class="google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Sign up with Google
                </a>

                <!-- Login Link -->
                <div class="reg-login-link">
                    Already have an account? <a href="{{ url('login') }}">Login</a>
                </div>

                <!-- Mobile Social Proof & Trust Footer (Visible on mobile only) -->
                <div class="mobile-social-proof">
                    <div class="mobile-testimonial-card">
                        <span class="mobile-quote-symbol">&ldquo;</span>
                        <div class="mobile-quote-body">
                            <p class="mobile-quote-text">&ldquo;Excellent resources and support! My child&rsquo;s confidence has really grown.&rdquo;</p>
                            <span class="mobile-quote-author">&mdash; A Parent</span>
                        </div>
                    </div>

                    <div class="mobile-script-wrap">
                        <span class="mobile-script-text">Small Steps Big Success</span>
                        <svg class="mobile-script-swoosh" viewBox="0 0 100 12" fill="none">
                            <path d="M3 9 C 30 2, 70 2, 97 9" stroke="#facc15" stroke-width="3.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle password visibility for all toggle buttons
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const eyeOpen = this.querySelector('.eye-open');
            const eyeClosed = this.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        });
    });

    // Username check logic (preserved from original)
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const spinner = document.getElementById('username-spinner');
    const feedback = document.getElementById('username-feedback');
    const feedbackText = document.getElementById('username-feedback-text');
    const suggestionsDiv = document.getElementById('username-suggestions');
    const suggestionsList = document.getElementById('suggestions-list');
    const csrfToken = document.querySelector('input[name="_token"]').value;

    let debounceTimeout = null;
    let usernameManuallyEdited = false;

    // Helper to generate suggestions from Name
    function fetchSuggestionsFromName() {
        const nameVal = nameInput.value.trim();
        if (nameVal.length >= 2 && !usernameManuallyEdited && usernameInput.value.trim() === '') {
            spinner.style.display = 'block';
            axios.post('{{ route("register.check-username") }}', {
                username: nameVal.replace(/\s+/g, '_').toLowerCase(),
                name: nameVal
            }, {
                headers: { 'X-CSRF-TOKEN': csrfToken }
            }).then(response => {
                spinner.style.display = 'none';
                if (response.data && response.data.suggestions && usernameInput.value.trim() === '') {
                    renderSuggestions(response.data.suggestions);
                }
            }).catch(err => {
                spinner.style.display = 'none';
            });
        }
    }

    // Keyup on Name field to auto-suggest
    nameInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(fetchSuggestionsFromName, 400);
    });

    // Keyup on Username field
    usernameInput.addEventListener('input', function () {
        usernameManuallyEdited = true;
        const usernameVal = usernameInput.value.trim();

        clearTimeout(debounceTimeout);

        if (usernameVal === '') {
            feedback.style.display = 'none';
            suggestionsDiv.style.display = 'none';
            return;
        }

        if (usernameVal.length < 3) {
            feedback.style.display = 'block';
            feedbackText.className = 'text-danger';
            feedbackText.innerHTML = '<i class="feather icon-alert-triangle mr-25"></i>Username must be at least 3 characters.';
            suggestionsDiv.style.display = 'none';
            return;
        }

        // Regex check
        const regex = /^[a-zA-Z0-9_.-]+$/;
        if (!regex.test(usernameVal)) {
            feedback.style.display = 'block';
            feedbackText.className = 'text-danger';
            feedbackText.innerHTML = '<i class="feather icon-alert-triangle mr-25"></i>Only letters, numbers, dot, dash, and underscore are allowed.';
            suggestionsDiv.style.display = 'none';
            return;
        }

        spinner.style.display = 'block';

        debounceTimeout = setTimeout(function () {
            axios.post('{{ route("register.check-username") }}', {
                username: usernameVal,
                name: nameInput.value.trim()
            }, {
                headers: { 'X-CSRF-TOKEN': csrfToken }
            })
            .then(response => {
                spinner.style.display = 'none';
                feedback.style.display = 'block';

                if (response.data.available) {
                    feedbackText.className = 'text-success';
                    feedbackText.innerHTML = '<i class="feather icon-check-circle mr-25"></i>Username is available!';
                    suggestionsDiv.style.display = 'none';
                } else {
                    feedbackText.className = 'text-danger';
                    feedbackText.innerHTML = '<i class="feather icon-alert-circle mr-25"></i>' + response.data.message;
                    if (response.data.suggestions && response.data.suggestions.length > 0) {
                        renderSuggestions(response.data.suggestions);
                    } else {
                        suggestionsDiv.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                spinner.style.display = 'none';
                feedback.style.display = 'none';
            });
        }, 300);
    });

    function renderSuggestions(suggestions) {
        suggestionsList.innerHTML = '';
        suggestions.forEach(suggestion => {
            const span = document.createElement('span');
            span.className = 'suggestion-pill';
            span.textContent = suggestion;
            span.addEventListener('click', function () {
                usernameInput.value = suggestion;
                usernameManuallyEdited = true;
                const event = new Event('input', { bubbles: true });
                usernameInput.dispatchEvent(event);
            });
            suggestionsList.appendChild(span);
        });
        suggestionsDiv.style.display = 'block';
    }
});
</script>
@endpush
