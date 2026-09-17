@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                @php
                    $hour = (int) date('H');
                    if ($hour < 12) {
                        $greeting = 'Good Morning';
                        $greetingIcon = '🌅';
                    } elseif ($hour < 17) {
                        $greeting = 'Good Afternoon';
                        $greetingIcon = '☀️';
                    } else {
                        $greeting = 'Good Evening';
                        $greetingIcon = '🌙';
                    }
                    $studentDetail = auth()->user()->studentDetail;
                @endphp

                <!-- Welcome Header -->
                <div class="welcome-card shadow-lg">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-7 text-center text-md-left">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start mb-1 hero-badges-wrap">
                                <span class="hero-badge mr-1 mb-50">
                                    <i class="feather icon-calendar mr-50"></i> {{ now()->format('l, j F Y') }}
                                </span>
                                @if($studentDetail && $studentDetail->group_year)
                                    <span class="hero-badge mr-1 mb-50">
                                        <i class="feather icon-award mr-50"></i> Year {{ $studentDetail->group_year }}
                                    </span>
                                @endif
                                <span class="hero-badge mb-50">
                                    <i class="feather icon-user-check mr-50 text-success"></i> Student Portal
                                </span>
                            </div>

                            <h1 class="welcome-title text-white">
                                {{ $greeting }}, {{ auth()->user()->name }}! {{ $greetingIcon }}
                            </h1>
                            <p class="welcome-text">
                                Explore your studies, evaluate your knowledge, and monitor your test scores.
                                Let's make today a highly productive learning day!
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-5 text-center text-md-right mt-2 mt-md-0">
                            <a href="{{ route('student.weeklytests') }}" class="btn btn-hero-shortcut">
                                <span class="d-flex align-items-center justify-content-center">
                                    <i class="feather icon-file-text font-medium-2 mr-1"></i>
                                    <span>Weekly Tests &amp; Mocks</span>
                                    <i class="feather icon-arrow-right ml-1"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                @if(isset($unreadHighPriority) && $unreadHighPriority->count() > 0)
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm urgent-notice-banner" role="alert">
                        <div class="d-flex align-items-center flex-wrap flex-md-nowrap">
                            <div class="d-flex align-items-center mr-auto mb-1 mb-md-0">
                                <div class="urgent-icon-wrap mr-1">
                                    <i class="feather icon-alert-circle text-danger pulsing-icon"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-danger text-uppercase font-weight-bold mr-1">Urgent Notice</span>
                                        <h5 class="alert-heading font-weight-bold mb-0 text-danger">Action Required</h5>
                                    </div>
                                    <p class="mb-0 font-small-3 text-secondary mt-25">
                                        You have <strong>{{ $unreadHighPriority->count() }}</strong> new urgent announcement(s). Please read them immediately.
                                    </p>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <a href="{{ route('student.announcements') }}" class="btn btn-danger btn-sm text-uppercase font-weight-bold px-2 py-75 shadow-sm">
                                    View Notices <i class="feather icon-arrow-right ml-50"></i>
                                </a>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Section Header -->
                <div class="d-flex align-items-center justify-content-between mb-2 mt-1">
                    <div>
                        <h3 class="font-weight-bold mb-0 text-dark learning-hub-title">Learning Hub</h3>
                        <p class="text-muted font-small-3 mb-0">Select a module below to access your learning materials and tools</p>
                    </div>
                </div>

                <!-- Dashboard Grid Section -->
                <section id="student-dashboard-grid">
                    <div class="row">

                        <!-- Lessons -->
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); --card-light: rgba(17, 153, 142, 0.08); --card-color: #11998e; --card-shadow: rgba(17, 153, 142, 0.35);">
                                <div class="dashboard-card-body">
                                    <span class="card-tag-pill">Video Library</span>
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Lessons.svg') }}"
                                            alt="Lessons illustration" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Lessons</h4>
                                    <p class="dashboard-card-text">Checkout video lessons to strengthen and enhance your learning experience.</p>
                                    <a href="{{ route('student.videolessonscategories') }}" class="card-action-btn">
                                        <span>Watch Now</span> <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Analytics -->
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #2f80ed 0%, #56ccf2 100%); --card-light: rgba(47, 128, 237, 0.08); --card-color: #2f80ed; --card-shadow: rgba(47, 128, 237, 0.35);">
                                <div class="dashboard-card-body">
                                    <span class="card-tag-pill">Performance Metrics</span>
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Analytics.svg') }}"
                                            alt="Analytics illustration" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Analytics</h4>
                                    <p class="dashboard-card-text">View your comprehensive performance metrics, strengths, and historical analytics.</p>
                                    <a href="{{ route('student.analytics') }}" class="card-action-btn">
                                        <span>View Details</span> <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Assessment -->
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #7367f0 0%, #ce9ffc 100%); --card-light: rgba(115, 103, 240, 0.08); --card-color: #7367f0; --card-shadow: rgba(115, 103, 240, 0.35);">
                                <div class="dashboard-card-body">
                                    <span class="card-tag-pill">Quizzes &amp; Mocks</span>
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Assessment.svg') }}"
                                            alt="Assessment illustration" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Assessment</h4>
                                    <p class="dashboard-card-text">Take tests, quizzes, and assigned mock exams to evaluate your curriculum progress.</p>
                                    <a href="{{ route('student.assessments') }}" class="card-action-btn">
                                        <span>Start Assessment</span> <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Focus Areas -->
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); --card-light: rgba(255, 65, 108, 0.08); --card-color: #ff416c; --card-shadow: rgba(255, 65, 108, 0.35);">
                                <div class="dashboard-card-body">
                                    <span class="card-tag-pill">Targeted Practice</span>
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Focus%20Area.svg') }}"
                                            alt="Focus Areas illustration" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Focus Areas</h4>
                                    <p class="dashboard-card-text">Review and focus on your weaker topics and core skills needing extra practice.</p>
                                    <a href="{{ route('student.focusareas') }}" class="card-action-btn">
                                        <span>View Focus Areas</span> <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Announcements -->
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #f12711 0%, #f5af19 100%); --card-light: rgba(241, 39, 17, 0.08); --card-color: #f12711; --card-shadow: rgba(241, 39, 17, 0.35);">
                                <div class="dashboard-card-body">
                                    <span class="card-tag-pill">Notices &amp; News</span>
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Announcements.svg') }}"
                                            alt="Announcements illustration" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Announcements</h4>
                                    <p class="dashboard-card-text">Stay updated with the latest news, notices, and portal notifications from center admin.</p>
                                    <a href="{{ route('student.announcements') }}" class="card-action-btn">
                                        <span>View Notifications</span> <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Center Test Scores -->
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #e52d27 0%, #b31217 100%); --card-light: rgba(229, 45, 39, 0.08); --card-color: #e52d27; --card-shadow: rgba(229, 45, 39, 0.35);">
                                <div class="dashboard-card-body">
                                    <span class="card-tag-pill">Exam Reports</span>
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Centre%20Scores.svg') }}"
                                            alt="Center Test Scores illustration" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Center Test Scores</h4>
                                    <p class="dashboard-card-text">Monitor your offline performance and track reports from center test mock sessions.</p>
                                    <a href="{{ route('student.centretestscores') }}" class="card-action-btn">
                                        <span>View Scores</span> <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- Spotlight / Quick Access Banner -->
                <div class="card spotlight-banner mt-1 mb-3 shadow-sm border-0">
                    <div class="card-body p-2 p-md-3">
                        <div class="row align-items-center">
                            <div class="col-lg-8 col-md-7 mb-2 mb-md-0">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge badge-light-primary text-uppercase font-weight-bold px-1 py-50 mr-1">
                                        <i class="feather icon-clock mr-50"></i> Weekly Homework &amp; Mocks
                                    </span>
                                </div>
                                <h4 class="font-weight-bold text-dark mb-50 spotlight-title">Need to practice this week's scheduled papers?</h4>
                                <p class="text-muted font-medium-1 mb-0 spotlight-desc">
                                    Access all assigned weekly tests, download offline homework sheets, and check your test deadlines in one place.
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-5 text-center text-md-right">
                                <a href="{{ route('student.weeklytests') }}" class="btn btn-primary px-3 py-1 font-weight-bold shadow spotlight-btn">
                                    <span>Go to Weekly Tests</span>
                                    <i class="feather icon-arrow-right ml-50"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <style>
        /* Scoped Enhancements for Student Dashboard */
        .hero-badge {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.4rem 0.95rem;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-hero-shortcut {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 50rem;
            padding: 0.75rem 1.6rem;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-hero-shortcut:hover {
            background: #ffffff;
            color: #7367f0 !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        }

        .urgent-notice-banner {
            border: none;
            border-left: 6px solid #ea5455;
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .dark-layout .urgent-notice-banner {
            background-color: #1e2440;
        }

        .urgent-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(234, 84, 85, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .learning-hub-title {
            letter-spacing: -0.01em;
        }

        .dark-layout .learning-hub-title {
            color: #e3e8f0 !important;
        }

        .card-tag-pill {
            background: var(--card-light);
            color: var(--card-color);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 0.35rem 0.9rem;
            border-radius: 50rem;
            margin-bottom: 1.2rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .dashboard-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark-layout .dashboard-card {
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .dashboard-card:hover .card-tag-pill {
            background: var(--card-color);
            color: #ffffff;
            box-shadow: 0 3px 10px var(--card-shadow);
        }

        .spotlight-banner {
            border-radius: 1.25rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fe 100%);
            border: 1px solid rgba(115, 103, 240, 0.15) !important;
            position: relative;
            overflow: hidden;
        }

        .spotlight-banner::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, #7367f0 0%, #ce9ffc 100%);
        }

        .dark-layout .spotlight-banner {
            background: linear-gradient(135deg, #1e2440 0%, #171b30 100%);
            border-color: rgba(115, 103, 240, 0.3) !important;
        }

        .dark-layout .spotlight-title {
            color: #f1f5f9 !important;
        }

        .dark-layout .spotlight-desc {
            color: #94a3b8 !important;
        }

        .spotlight-btn {
            border-radius: 50rem;
            transition: all 0.3s ease;
        }

        .spotlight-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(115, 103, 240, 0.4) !important;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.18); }
            100% { transform: scale(1); }
        }

        .pulsing-icon {
            animation: pulse 1.5s infinite;
        }
    </style>
@endsection