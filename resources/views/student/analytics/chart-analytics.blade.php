@extends('layouts.app')

@section('title', 'Analytics - Coming Soon')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Student Analytics</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Analytics</li>
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
                                <span class="font-small-3 font-weight-bold text-uppercase tracking-wider">Feature in Development</span>
                            </div>

                            <h1 class="hero-heading font-weight-bolder text-white mb-2">
                                Deep Learning Analytics <br>
                                <span class="gradient-text">&amp; Progress Insights</span>
                            </h1>

                            <p class="hero-subtext mb-3">
                                We are engineering a revolutionary learning analytics engine to give you unprecedented visibility into your strengths, timing accuracy, topic masteries, and cohort benchmark rankings.
                            </p>

                            {{-- Development Progress Meter --}}
                            <div class="progress-box p-2 rounded mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="font-weight-bold font-small-3 text-white">
                                        <i class="feather icon-cpu mr-50 text-info"></i> Development Status
                                    </span>
                                    <span class="badge badge-pill badge-primary font-weight-bold">85% Complete</span>
                                </div>
                                <div class="progress progress-bar-primary progress-xl mb-1" style="height: 10px; background-color: rgba(255,255,255,0.15); border-radius: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                         style="width: 85%; border-radius: 8px; background: linear-gradient(90deg, #7367f0, #00cfe8);" 
                                         aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between text-muted font-small-2">
                                    <span class="text-light-50"><i class="feather icon-check text-success mr-25"></i> Core Telemetry</span>
                                    <span class="text-light-50"><i class="feather icon-check text-success mr-25"></i> Scoring Models</span>
                                    <span class="text-warning"><i class="feather icon-loader mr-25"></i> Final Calibration</span>
                                </div>
                            </div>

                            {{-- Quick Action Buttons --}}
                            <div class="d-flex flex-wrap align-items-center" style="gap: 12px;">
                                <a href="{{ route('student.weeklytests') }}" class="btn btn-primary btn-glow px-2 py-1 font-weight-bold d-inline-flex align-items-center">
                                    <i class="feather icon-play-circle mr-1 font-medium-1"></i> Practice Weekly Tests
                                </a>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-light px-2 py-1 font-weight-bold d-inline-flex align-items-center">
                                    <i class="feather icon-home mr-1 font-medium-1"></i> Return to Dashboard
                                </a>
                                <button type="button" id="notifyBtn" class="btn btn-dark px-2 py-1 font-weight-bold d-inline-flex align-items-center" onclick="handleNotifyMe()">
                                    <i class="feather icon-bell mr-1 font-medium-1 text-warning"></i> Notify When Ready
                                </button>
                            </div>
                        </div>

                        {{-- Floating Visual Illustration --}}
                        <div class="col-lg-5 col-12 text-center">
                            <div class="floating-visual-wrap mx-auto">
                                <svg class="analytics-svg-art img-fluid" viewBox="0 0 500 420" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="chartGrad1" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#7367f0" stop-opacity="0.7"/>
                                            <stop offset="100%" stop-color="#7367f0" stop-opacity="0.0"/>
                                        </linearGradient>
                                        <linearGradient id="chartGrad2" x1="0" y1="0" x2="1" y2="1">
                                            <stop offset="0%" stop-color="#00cfe8"/>
                                            <stop offset="100%" stop-color="#7367f0"/>
                                        </linearGradient>
                                        <filter id="glowFilter" x="-20%" y="-20%" width="140%" height="140%">
                                            <feGaussianBlur stdDeviation="8" result="blur" />
                                            <feComposite in="SourceGraphic" in2="blur" operator="over" />
                                        </filter>
                                    </defs>

                                    <!-- Outer Dashboard Frame Card -->
                                    <rect x="30" y="30" width="440" height="360" rx="20" fill="rgba(15, 23, 42, 0.75)" stroke="rgba(255,255,255,0.15)" stroke-width="2"/>

                                    <!-- Top Window Dots -->
                                    <circle cx="65" cy="60" r="5" fill="#ea5455" opacity="0.8"/>
                                    <circle cx="85" cy="60" r="5" fill="#ff9f43" opacity="0.8"/>
                                    <circle cx="105" cy="60" r="5" fill="#28c76f" opacity="0.8"/>

                                    <rect x="140" y="55" width="120" height="10" rx="5" fill="rgba(255,255,255,0.15)"/>
                                    <rect x="370" y="52" width="70" height="16" rx="8" fill="#7367f0" opacity="0.3"/>

                                    <!-- Mini Stat Widgets -->
                                    <g transform="translate(60, 90)">
                                        <rect width="105" height="60" rx="10" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.08)"/>
                                        <rect x="15" y="15" width="40" height="8" rx="4" fill="rgba(255,255,255,0.3)"/>
                                        <text x="15" y="44" fill="#00cfe8" font-size="18" font-weight="bold" font-family="sans-serif">96.4%</text>
                                    </g>
                                    <g transform="translate(180, 90)">
                                        <rect width="105" height="60" rx="10" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.08)"/>
                                        <rect x="15" y="15" width="45" height="8" rx="4" fill="rgba(255,255,255,0.3)"/>
                                        <text x="15" y="44" fill="#28c76f" font-size="18" font-weight="bold" font-family="sans-serif">+14.2%</text>
                                    </g>
                                    <g transform="translate(300, 90)">
                                        <rect width="120" height="60" rx="10" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.08)"/>
                                        <rect x="15" y="15" width="50" height="8" rx="4" fill="rgba(255,255,255,0.3)"/>
                                        <text x="15" y="44" fill="#ff9f43" font-size="18" font-weight="bold" font-family="sans-serif">Top 5%</text>
                                    </g>

                                    <!-- Wave Chart Area -->
                                    <g transform="translate(60, 180)">
                                        <!-- Gridlines -->
                                        <line x1="0" y1="30" x2="360" y2="30" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>
                                        <line x1="0" y1="80" x2="360" y2="80" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>
                                        <line x1="0" y1="130" x2="360" y2="130" stroke="rgba(255,255,255,0.06)" stroke-dasharray="4"/>

                                        <!-- Area Fill -->
                                        <path d="M 0 130 Q 60 70, 120 90 T 240 40 T 360 20 L 360 150 L 0 150 Z" fill="url(#chartGrad1)"/>

                                        <!-- Main Trend Line with Glowing Points -->
                                        <path d="M 0 130 Q 60 70, 120 90 T 240 40 T 360 20" stroke="url(#chartGrad2)" stroke-width="4" fill="none" filter="url(#glowFilter)"/>
                                        <path d="M 0 130 Q 60 70, 120 90 T 240 40 T 360 20" stroke="#fff" stroke-width="2.5" fill="none"/>

                                        <!-- Interactive Point Circles -->
                                        <circle cx="120" cy="90" r="5" fill="#00cfe8" stroke="#fff" stroke-width="2"/>
                                        <circle cx="240" cy="40" r="6" fill="#7367f0" stroke="#fff" stroke-width="2"/>
                                        <circle cx="360" cy="20" r="7" fill="#28c76f" stroke="#fff" stroke-width="2.5" filter="url(#glowFilter)"/>

                                        <!-- Target Callout Tag -->
                                        <g transform="translate(290, -15)">
                                            <rect width="80" height="24" rx="6" fill="#7367f0"/>
                                            <text x="40" y="16" fill="#fff" font-size="11" font-weight="bold" font-family="sans-serif" text-anchor="middle">Target Met 🎯</text>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Feature Sneak Peek Grid --}}
                <div class="mb-2">
                    <h4 class="font-weight-bold text-dark mb-50">
                        <i class="feather icon-layers text-primary mr-50"></i> What You'll Be Able to Do
                    </h4>
                    <p class="text-muted mb-2">Here is a sneak peek of the upcoming analytics suite designed for your success.</p>
                </div>

                <div class="row">
                    <!-- Feature 1 -->
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div class="card h-100 preview-feature-card shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="feature-icon-box bg-light-primary text-primary mb-1">
                                    <i class="feather icon-trending-up font-medium-4"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">Mastery Heatmap</h5>
                                <p class="text-muted font-small-3 mb-0">
                                    Visualize topic-by-topic comprehension across Maths, English, and Reasoning to spot strengths and focus targets instantly.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div class="card h-100 preview-feature-card shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="feature-icon-box bg-light-info text-info mb-1">
                                    <i class="feather icon-clock font-medium-4"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">Pacing &amp; Speed</h5>
                                <p class="text-muted font-small-3 mb-0">
                                    Measure exact time spent per question and identify pacing bottlenecks to maximize efficiency during timed exams.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div class="card h-100 preview-feature-card shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="feature-icon-box bg-light-success text-success mb-1">
                                    <i class="feather icon-award font-medium-4"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">Cohort Benchmarks</h5>
                                <p class="text-muted font-small-3 mb-0">
                                    Compare test percentile standings anonymously against your class and year group averages to gauge exam readiness.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <div class="card h-100 preview-feature-card shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="feature-icon-box bg-light-warning text-warning mb-1">
                                    <i class="feather icon-zap font-medium-4"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">AI Exam Forecaster</h5>
                                <p class="text-muted font-small-3 mb-0">
                                    Smart projection models predicting your likelihood of scoring top grades based on recent test consistency and growth.
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
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            border-radius: 20px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            pointer-events: none;
        }
        .glow-1 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, #7367f0 0%, rgba(115, 103, 240, 0) 70%);
            top: -80px;
            right: 15%;
        }
        .glow-2 {
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, #00cfe8 0%, rgba(0, 207, 232, 0) 70%);
            bottom: -60px;
            left: 10%;
        }
        .glow-3 {
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, #ff9f43 0%, rgba(255, 159, 67, 0) 70%);
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
            background-color: #28c76f;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(40, 199, 111, 0.7);
            animation: pulseDot 1.8s infinite;
        }
        @keyframes pulseDot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 199, 111, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(40, 199, 111, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 199, 111, 0); }
        }

        .hero-heading {
            font-size: 2.3rem;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        .gradient-text {
            background: linear-gradient(90deg, #00cfe8, #7367f0, #ff9f43);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtext {
            font-size: 1.05rem;
            line-height: 1.6;
            color: rgba(241, 245, 249, 0.85);
            max-width: 620px;
        }

        .progress-box {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            max-width: 540px;
        }

        .btn-glow {
            box-shadow: 0 4px 20px 0 rgba(115, 103, 240, 0.5);
            transition: all 0.25s ease;
        }
        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px 0 rgba(115, 103, 240, 0.7);
        }

        .floating-visual-wrap {
            animation: floatArt 4.5s ease-in-out infinite;
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.5));
            max-width: 420px;
        }
        @keyframes floatArt {
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
            border-color: #7367f0 !important;
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
        function handleNotifyMe() {
            const btn = document.getElementById('notifyBtn');
            btn.classList.remove('btn-dark');
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="feather icon-check mr-1 font-medium-1"></i> Notification Enabled!';
            btn.disabled = true;

            // Trigger notification alert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Notification Confirmed!',
                    text: 'We will notify you as soon as the Student Analytics Suite goes live.',
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            } else {
                alert('We will notify you as soon as Student Analytics goes live!');
            }
        }
    </script>
@endsection
