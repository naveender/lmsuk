@extends('layouts.app')

@section('title', 'Student Dashboard')


@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <!-- Welcome Header -->
                <div class="welcome-card">
                    <div class="row align-items-center">
                        <div class="col-md-8 text-center text-md-left">
                            <h1 class="welcome-title text-white">Welcome Back, {{ auth()->user()->name }}! 🌟</h1>
                            <p class="welcome-text">Explore your studies, evaluate your knowledge, and monitor your scores.
                                Let's make today a highly productive learning day!</p>
                        </div>
                    </div>
                </div>

                @if(isset($unreadHighPriority) && $unreadHighPriority->count() > 0)
                    <div class="alert alert-danger alert-dismissible fade show mt-2 shadow" role="alert" style="border-left: 6px solid #ea5455; background-color: #fff;">
                        <div class="d-flex align-items-center">
                            <i class="feather icon-alert-circle font-large-1 mr-2 text-danger pulsing-icon"></i>
                            <div>
                                <h5 class="alert-heading font-weight-bold mb-0 text-danger">Urgent Notices Attention Needed!</h5>
                                <p class="mb-0 font-small-3 text-secondary">You have <strong>{{ $unreadHighPriority->count() }}</strong> new urgent announcement(s). Please read them immediately.</p>
                            </div>
                            <div class="ml-auto">
                                <a href="{{ route('student.announcements') }}" class="btn btn-danger btn-sm text-uppercase font-weight-bold">View Notices</a>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <style>
                        @keyframes pulse {
                            0% { transform: scale(1); }
                            50% { transform: scale(1.15); }
                            100% { transform: scale(1); }
                        }
                        .pulsing-icon {
                            animation: pulse 1.5s infinite;
                        }
                    </style>
                @endif

                <!-- Dashboard Grid Section -->
                <section id="student-dashboard-grid">
                    <div class="row">

                        <!-- Lessons -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); --card-light: rgba(17, 153, 142, 0.08); --card-color: #11998e; --card-shadow: rgba(17, 153, 142, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Lessons.png') }}"
                                            alt="Lessons illustration">
                                    </div>
                                    <h4 class="dashboard-card-title">Lessons</h4>
                                    <p class="dashboard-card-text">Checkout video lessons to strengthen and enhance your
                                        learning experience.</p>
                                    <a href="{{ route('student.videolessonscategories') }}" class="card-action-btn">
                                        Watch Now <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Analytics -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #2f80ed 0%, #56ccf2 100%); --card-light: rgba(47, 128, 237, 0.08); --card-color: #2f80ed; --card-shadow: rgba(47, 128, 237, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Analytics.png') }}"
                                            alt="Analytics illustration">
                                    </div>
                                    <h4 class="dashboard-card-title">Analytics</h4>
                                    <p class="dashboard-card-text">View your comprehensive performance metrics, strengths,
                                        and historical analytics.</p>
                                    <a href="{{ route('student.analytics') }}" class="card-action-btn">
                                        View Details <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Assessment -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #7367f0 0%, #ce9ffc 100%); --card-light: rgba(115, 103, 240, 0.08); --card-color: #7367f0; --card-shadow: rgba(115, 103, 240, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Assessment.png') }}"
                                            alt="Assessment illustration">
                                    </div>
                                    <h4 class="dashboard-card-title">Assessment</h4>
                                    <p class="dashboard-card-text">Take tests, quizzes, and assigned mock exams to evaluate
                                        your curriculum progress.</p>
                                    <a href="{{ route('student.assessments') }}" class="card-action-btn">
                                        Start Assessment <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Focus Areas -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); --card-light: rgba(255, 65, 108, 0.08); --card-color: #ff416c; --card-shadow: rgba(255, 65, 108, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/FocusAreas.png') }}"
                                            alt="Focus Areas illustration">
                                    </div>
                                    <h4 class="dashboard-card-title">Focus Areas</h4>
                                    <p class="dashboard-card-text">Review and focus on your weaker topics and core skills
                                        needing extra practice.</p>
                                    <a href="{{ route('student.focusareas') }}" class="card-action-btn">
                                        View Focus Areas <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Announcements -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #f12711 0%, #f5af19 100%); --card-light: rgba(241, 39, 17, 0.08); --card-color: #f12711; --card-shadow: rgba(241, 39, 17, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Anouncement.png') }}"
                                            alt="Announcements illustration">
                                    </div>
                                    <h4 class="dashboard-card-title">Announcements</h4>
                                    <p class="dashboard-card-text">Stay updated with the latest news, notices, and portal
                                        notifications from center admin.</p>
                                    <a href="{{ route('student.announcements') }}" class="card-action-btn">
                                        View Notifications <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Center Test Scores -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #e52d27 0%, #b31217 100%); --card-light: rgba(229, 45, 39, 0.08); --card-color: #e52d27; --card-shadow: rgba(229, 45, 39, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Centertestscore.png') }}"
                                            alt="Center Test Scores illustration">
                                    </div>
                                    <h4 class="dashboard-card-title">Center Test Scores</h4>
                                    <p class="dashboard-card-text">Monitor your offline performance and track reports from
                                        center test mock sessions.</p>
                                    <a href="{{ route('student.centretestscores') }}" class="card-action-btn">
                                        View Scores <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection