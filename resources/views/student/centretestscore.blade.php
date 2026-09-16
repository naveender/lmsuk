@extends('layouts.app')

@section('title', 'Centre Test Scores - Coming Soon')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Centre Test Scores</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Centre Test Scores</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                {{-- Hero Coming Soon Container --}}
                <div class="coming-soon-hero position-relative overflow-hidden mb-4">
                    {{-- Ambient Background Glow Elements --}}
                    <div class="ambient-glow glow-1"></div>
                    <div class="ambient-glow glow-2"></div>
                    <div class="ambient-glow glow-3"></div>

                    <div class="row align-items-center position-relative" style="z-index: 2;">
                        <div class="col-lg-7 col-12 mb-3 mb-lg-0 text-white">
                            <div class="d-inline-flex align-items-center mb-2 px-2 py-50 rounded-pill launch-badge">
                                <span class="pulse-dot mr-1"></span>
                                <span class="font-small-3 font-weight-bold text-uppercase tracking-wider">In Final Testing</span>
                            </div>

                            <h1 class="hero-heading font-weight-bolder text-white mb-2">
                                In-Centre Mock Exam Scores <br>
                                <span class="gradient-text">&amp; Digital Scorecards</span>
                            </h1>

                            <p class="hero-subtext mb-3">
                                We are finalizing the official In-Centre Test Score portal. Soon, you will be able to access all marked physical mock papers, age-standardised scores, invigilator comments, and centre-wide percentile rankings in one secure dashboard.
                            </p>

                            {{-- Development Progress Meter --}}
                            <div class="progress-box p-2 rounded mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="font-weight-bold font-small-3 text-white">
                                        <i class="feather icon-award mr-50 text-warning"></i> Release Readiness
                                    </span>
                                    <span class="badge badge-pill badge-warning font-weight-bold text-dark">90% Complete</span>
                                </div>
                                <div class="progress progress-bar-warning progress-xl mb-1" style="height: 10px; background-color: rgba(255,255,255,0.15); border-radius: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                         style="width: 90%; border-radius: 8px; background: linear-gradient(90deg, #ff9f43, #28c76f);" 
                                         aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between text-muted font-small-2">
                                    <span class="text-light-50"><i class="feather icon-check text-success mr-25"></i> Paper Digitisation</span>
                                    <span class="text-light-50"><i class="feather icon-check text-success mr-25"></i> Standardised Scaling</span>
                                    <span class="text-warning"><i class="feather icon-loader mr-25"></i> Tutor Feedback Sync</span>
                                </div>
                            </div>

                            {{-- Quick Action Buttons --}}
                            <div class="d-flex flex-wrap align-items-center" style="gap: 12px;">
                                <a href="{{ route('student.weeklytests') }}" class="btn btn-warning btn-glow text-dark px-2 py-1 font-weight-bold d-inline-flex align-items-center">
                                    <i class="feather icon-file-text mr-1 font-medium-1"></i> Practice Weekly Tests
                                </a>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-light px-2 py-1 font-weight-bold d-inline-flex align-items-center">
                                    <i class="feather icon-home mr-1 font-medium-1"></i> Return to Dashboard
                                </a>
                                <button type="button" id="notifyBtnScores" class="btn btn-dark px-2 py-1 font-weight-bold d-inline-flex align-items-center" onclick="handleNotifyMeScores()">
                                    <i class="feather icon-bell mr-1 font-medium-1 text-warning"></i> Notify When Ready
                                </button>
                            </div>
                        </div>

                        {{-- Floating Scorecard Visual Illustration --}}
                        <div class="col-lg-5 col-12 text-center">
                            <div class="floating-visual-wrap mx-auto">
                                <svg class="centretest-svg-art img-fluid" viewBox="0 0 500 420" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="cardGrad" x1="0" y1="0" x2="1" y2="1">
                                            <stop offset="0%" stop-color="#1e293b" stop-opacity="0.9"/>
                                            <stop offset="100%" stop-color="#0f172a" stop-opacity="0.95"/>
                                        </linearGradient>
                                        <linearGradient id="goldGrad" x1="0" y1="0" x2="1" y2="1">
                                            <stop offset="0%" stop-color="#f6d365"/>
                                            <stop offset="100%" stop-color="#fda085"/>
                                        </linearGradient>
                                        <linearGradient id="greenGrad" x1="0" y1="0" x2="1" y2="1">
                                            <stop offset="0%" stop-color="#28c76f"/>
                                            <stop offset="100%" stop-color="#48bb78"/>
                                        </linearGradient>
                                        <filter id="cardGlow" x="-20%" y="-20%" width="140%" height="140%">
                                            <feGaussianBlur stdDeviation="10" result="blur" />
                                            <feComposite in="SourceGraphic" in2="blur" operator="over" />
                                        </filter>
                                    </defs>

                                    <!-- Main Certificate / Scorecard Frame -->
                                    <rect x="35" y="25" width="430" height="370" rx="20" fill="url(#cardGrad)" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>

                                    <!-- Top Header Bar -->
                                    <rect x="35" y="25" width="430" height="60" rx="20" fill="rgba(255,255,255,0.04)"/>
                                    <!-- Window Control Dots -->
                                    <circle cx="65" cy="55" r="5" fill="#ea5455" opacity="0.8"/>
                                    <circle cx="83" cy="55" r="5" fill="#ff9f43" opacity="0.8"/>
                                    <circle cx="101" cy="55" r="5" fill="#28c76f" opacity="0.8"/>
                                    <text x="130" y="60" fill="rgba(255,255,255,0.75)" font-size="13" font-weight="600" font-family="sans-serif">OFFICIAL IN-CENTRE MOCK SCORECARD</text>

                                    <!-- Gold Trophy / Ribbon Seal -->
                                    <g transform="translate(385, 40)">
                                        <circle cx="20" cy="20" r="22" fill="url(#goldGrad)" filter="url(#cardGlow)" opacity="0.85"/>
                                        <circle cx="20" cy="20" r="18" fill="#1e293b"/>
                                        <text x="20" y="26" fill="#ffd700" font-size="16" font-weight="bold" font-family="sans-serif" text-anchor="middle">★</text>
                                    </g>

                                    <!-- Student & Exam Header Info -->
                                    <g transform="translate(65, 105)">
                                        <rect width="370" height="65" rx="12" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.08)"/>
                                        <text x="18" y="28" fill="#fff" font-size="15" font-weight="bold" font-family="sans-serif">Year 5 Autumn Comprehensive Mock</text>
                                        <text x="18" y="48" fill="rgba(255,255,255,0.6)" font-size="12" font-family="sans-serif">Sitting Date: Saturday Session • Physical Assessment Hall</text>
                                        <rect x="290" y="15" width="65" height="34" rx="8" fill="#28c76f" opacity="0.2"/>
                                        <text x="322" y="37" fill="#28c76f" font-size="14" font-weight="bold" font-family="sans-serif" text-anchor="middle">PASS</text>
                                    </g>

                                    <!-- Score Subject Rows -->
                                    <!-- Mathematics -->
                                    <g transform="translate(65, 185)">
                                        <rect width="370" height="42" rx="10" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.06)"/>
                                        <circle cx="24" cy="21" r="6" fill="#7367f0"/>
                                        <text x="40" y="26" fill="#fff" font-size="13" font-weight="600" font-family="sans-serif">Mathematics &amp; Numerical</text>
                                        <rect x="235" y="16" width="60" height="8" rx="4" fill="rgba(255,255,255,0.1)"/>
                                        <rect x="235" y="16" width="54" height="8" rx="4" fill="#7367f0"/>
                                        <text x="335" y="26" fill="#7367f0" font-size="13" font-weight="bold" font-family="sans-serif">47 / 50</text>
                                    </g>

                                    <!-- English / Comprehension -->
                                    <g transform="translate(65, 235)">
                                        <rect width="370" height="42" rx="10" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.06)"/>
                                        <circle cx="24" cy="21" r="6" fill="#00cfe8"/>
                                        <text x="40" y="26" fill="#fff" font-size="13" font-weight="600" font-family="sans-serif">English &amp; Verbal Reasoning</text>
                                        <rect x="235" y="16" width="60" height="8" rx="4" fill="rgba(255,255,255,0.1)"/>
                                        <rect x="235" y="16" width="50" height="8" rx="4" fill="#00cfe8"/>
                                        <text x="335" y="26" fill="#00cfe8" font-size="13" font-weight="bold" font-family="sans-serif">45 / 50</text>
                                    </g>

                                    <!-- Overall Result & Standardised Score Callout -->
                                    <g transform="translate(65, 290)">
                                        <rect width="175" height="75" rx="12" fill="rgba(40, 199, 111, 0.12)" stroke="rgba(40, 199, 111, 0.3)"/>
                                        <text x="16" y="26" fill="#28c76f" font-size="11" font-weight="bold" font-family="sans-serif">STANDARDISED SCORE</text>
                                        <text x="16" y="56" fill="#fff" font-size="24" font-weight="bolder" font-family="sans-serif">134 <span font-size="12" fill="#28c76f">/ 141</span></text>
                                    </g>

                                    <g transform="translate(255, 290)">
                                        <rect width="180" height="75" rx="12" fill="rgba(255, 159, 67, 0.12)" stroke="rgba(255, 159, 67, 0.3)"/>
                                        <text x="16" y="26" fill="#ff9f43" font-size="11" font-weight="bold" font-family="sans-serif">CENTRE PERCENTILE</text>
                                        <text x="16" y="56" fill="#fff" font-size="24" font-weight="bolder" font-family="sans-serif">Top 4% <span font-size="12" fill="#ff9f43">🏆</span></text>
                                    </g>

                                    <!-- Tutor Stamp -->
                                    <g transform="translate(325, 335) rotate(-8)">
                                        <rect width="105" height="26" rx="4" fill="none" stroke="#ea5455" stroke-width="2" stroke-dasharray="3"/>
                                        <text x="52" y="18" fill="#ea5455" font-size="11" font-weight="bold" font-family="sans-serif" text-anchor="middle">VERIFIED MARK</text>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Feature Sneak Peek Grid --}}
                <div class="mb-2">
                    <h4 class="font-weight-bold text-dark mb-50">
                        <i class="feather icon-clipboard text-warning mr-50"></i> What's Coming in Centre Test Scores
                    </h4>
                    <p class="text-muted mb-2">Detailed in-centre exam transparency, teacher marks, and progress insights.</p>
                </div>

                <div class="row">
                    <!-- Feature 1 -->
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div class="card h-100 preview-feature-card shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="feature-icon-box bg-light-warning text-warning mb-1">
                                    <i class="feather icon-file-text font-medium-4"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">Official Mock Scorecards</h5>
                                <p class="text-muted font-small-3 mb-0">
                                    Access certified breakdown of marks from your in-person tuition centre mock exams and baseline test sittings.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div class="card h-100 preview-feature-card shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="feature-icon-box bg-light-success text-success mb-1">
                                    <i class="feather icon-bar-chart-2 font-medium-4"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">Age-Standardised Scores</h5>
                                <p class="text-muted font-small-3 mb-0">
                                    View nationally calibrated Standardised Age Scores (SAS) reflecting actual 11+ and grammar school examination formats.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div class="card h-100 preview-feature-card shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="feature-icon-box bg-light-primary text-primary mb-1">
                                    <i class="feather icon-users font-medium-4"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">Centre Batch Rankings</h5>
                                <p class="text-muted font-small-3 mb-0">
                                    Discover how your marks compare across class cohorts and centres with anonymized percentile rankings.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div class="card h-100 preview-feature-card shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="feature-icon-box bg-light-info text-info mb-1">
                                    <i class="feather icon-message-circle font-medium-4"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">Tutor Feedback &amp; Notes</h5>
                                <p class="text-muted font-small-3 mb-0">
                                    Read personalised invigilator comments, areas of commendation, and recommended target topics for your next session.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Modern Coming Soon Hero Styles */
        .coming-soon-hero {
            background: linear-gradient(135deg, #091e3a 0%, #173b5c 50%, #064e3b 100%);
            border-radius: 20px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 20px 40px -15px rgba(9, 30, 58, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.45;
            pointer-events: none;
        }
        .glow-1 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, #ff9f43 0%, rgba(255, 159, 67, 0) 70%);
            top: -80px;
            right: 15%;
        }
        .glow-2 {
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, #28c76f 0%, rgba(40, 199, 111, 0) 70%);
            bottom: -60px;
            left: 10%;
        }
        .glow-3 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, #00cfe8 0%, rgba(0, 207, 232, 0) 70%);
            top: 40%;
            right: 40%;
        }

        .launch-badge {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            letter-spacing: 0.8px;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #ff9f43;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(255, 159, 67, 0.7);
            animation: pulseDotWarning 1.8s infinite;
        }
        @keyframes pulseDotWarning {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 159, 67, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(255, 159, 67, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 159, 67, 0); }
        }

        .hero-heading {
            font-size: 2.3rem;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        .gradient-text {
            background: linear-gradient(90deg, #ff9f43, #28c76f, #00cfe8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtext {
            font-size: 1.05rem;
            line-height: 1.6;
            color: rgba(241, 245, 249, 0.88);
            max-width: 620px;
        }

        .progress-box {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            max-width: 540px;
        }

        .btn-glow {
            box-shadow: 0 4px 20px 0 rgba(255, 159, 67, 0.45);
            transition: all 0.25s ease;
        }
        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px 0 rgba(255, 159, 67, 0.65);
        }

        .floating-visual-wrap {
            animation: floatArtScores 4.5s ease-in-out infinite;
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.55));
            max-width: 440px;
        }
        @keyframes floatArtScores {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }

        .preview-feature-card {
            border-radius: 14px;
            transition: all 0.25s ease;
            background: #ffffff;
        }
        .preview-feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
            border-color: #ff9f43 !important;
        }

        .feature-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .coming-soon-hero {
                padding: 2rem 1.25rem;
            }
            .hero-heading {
                font-size: 1.75rem;
            }
        }
    </style>

    <script>
        function handleNotifyMeScores() {
            const btn = document.getElementById('notifyBtnScores');
            btn.classList.remove('btn-dark');
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="feather icon-check mr-1 font-medium-1"></i> Notification Enabled!';
            btn.disabled = true;

            // Trigger notification alert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Notification Confirmed!',
                    text: 'We will notify you as soon as In-Centre Test Scores and Mock Reports go live.',
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-warning'
                    },
                    buttonsStyling: false
                });
            } else {
                alert('We will notify you as soon as Centre Test Scores are live!');
            }
        }
    </script>
@endsection
