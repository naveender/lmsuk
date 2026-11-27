@extends('layouts.app')

@section('title', 'Student Dashboard - Aspire Learners')

@section('content')
@php
    $user = auth()->user();
    $subjectCards = $subjectCards ?? collect();
    $focusTopics = $focusTopics ?? collect();
    $upcomingItems = $upcomingItems ?? collect();
    $weeklyActivity = $weeklyActivity ?? [];
    $overallCompleted = $overallCompletedCount ?? 0;
    $overallTotal = $overallTotalCount ?? 0;
    $overallPct = $overallProgressPct ?? 0;
    $dashLength = $overallDashLength ?? 0;
    $streakCount = $learningStreak ?? 0;
@endphp

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body">

            @if(isset($unreadHighPriority) && $unreadHighPriority->count() > 0)
                <div class="alert alert-danger alert-dismissible fade show shadow-sm urgent-notice-banner mb-3" role="alert" style="border-radius: 14px; border-left: 6px solid #FA2833;">
                    <div class="d-flex align-items-center flex-wrap flex-md-nowrap">
                        <div class="d-flex align-items-center mr-auto mb-1 mb-md-0">
                            <div class="urgent-icon-wrap mr-1" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(250, 40, 51, 0.12); display: flex; align-items: center; justify-content: center; color: #FA2833;">
                                <i class="feather icon-alert-circle font-medium-3"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-danger text-uppercase font-weight-bold mr-1" style="background-color: #FA2833;">Urgent Notice</span>
                                    <h6 class="alert-heading font-weight-bold mb-0 text-danger">Action Required</h6>
                                </div>
                                <p class="mb-0 font-small-2 text-muted mt-25">
                                    You have <strong>{{ $unreadHighPriority->count() }}</strong> new urgent announcement(s).
                                </p>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('student.announcements') }}"
                                class="btn btn-danger btn-sm text-uppercase font-weight-bold px-2 py-75 shadow-sm" style="background-color: #FA2833; border: none; border-radius: 50px;">
                                View Notices <i class="feather icon-arrow-right ml-50"></i>
                            </a>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">

                <!-- ===================================================================
                     LEFT / CENTER SECTION (Hero + Subject Cards + 2 Lower Cards)
                     =================================================================== -->
                <div class="col-xl-8 col-lg-8 col-12">

                    <!-- 1. Hero Journey Banner -->
                    <div class="student-journey-hero">
                        <div class="hero-bg-circle-1"></div>
                        <div class="hero-bg-circle-2"></div>

                        <div class="row align-items-center position-relative" style="z-index: 2;">
                            <!-- Left: Welcome Texts & Continue Learning CTA -->
                            <div class="col-md-7 col-12">
                                <span class="hero-journey-subtitle">Your Learning Journey</span>
                                <h1 class="hero-journey-title">
                                    Welcome Back,<br>
                                    <span class="title-highlight-yellow">{{ $user->name ?? 'Student' }}!</span>
                                </h1>
                                <p class="hero-journey-desc">
                                    Build your skills across lessons and practice tests.<br class="d-none d-sm-inline">
                                    Small steps today, big goals tomorrow!
                                </p>
                                <a href="{{ route('student.videolessonscategories') }}" class="btn-hero-continue">
                                    <span>Continue Learning</span>
                                    <i class="feather icon-arrow-right"></i>
                                </a>
                            </div>

                            <!-- Right: Student illustration with laptop & 3 stacked books -->
                            <div class="col-md-5 col-12 mt-2 mt-md-0">
                                <div class="hero-illustration-wrap">
                                    <div class="hero-quote-badge">
                                        <span class="hero-quote-text">Small Steps</span>
                                        <span class="hero-quote-sub">Big Goals</span>
                                    </div>

                                    <svg viewBox="0 0 280 190" width="100%" height="180" fill="none" xmlns="http://www.w3.org/2000/svg" style="max-width: 290px;">
                                        <g transform="translate(180, 15)">
                                            <line x1="16" y1="2" x2="16" y2="7" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                            <line x1="5" y1="6" x2="9" y2="10" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                            <line x1="27" y1="6" x2="23" y2="10" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                            <line x1="1" y1="16" x2="6" y2="16" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                            <line x1="31" y1="16" x2="26" y2="16" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                            <path d="M16 6C11 6 7 10 7 15C7 18 9 20 10 22C11 23 12 25 12 27H20C20 25 21 23 22 22C23 20 25 18 25 15C25 10 21 6 16 6Z" fill="#FBDA33"/>
                                            <rect x="12" y="28" width="8" height="2" rx="1" fill="#FFFFFF"/>
                                            <rect x="13" y="31" width="6" height="2" rx="1" fill="#FFFFFF"/>
                                        </g>

                                        <circle cx="50" cy="80" r="2.5" fill="#FBDA33"/>
                                        <circle cx="210" cy="95" r="2" fill="#FFFFFF" fill-opacity="0.8"/>
                                        <circle cx="160" cy="40" r="1.5" fill="#FBDA33"/>

                                        <g transform="translate(60, 20)">
                                            <path d="M78 30C78 18 70 10 58 10C46 10 38 18 38 30C38 32 39 34 40 36C40 36 36 40 37 45C38 48 40 49 42 49C42 56 46 62 58 62C70 62 74 56 74 49C76 49 78 48 79 45C80 40 76 36 76 36C77 34 78 32 78 30Z" fill="#1E293B"/>
                                            <ellipse cx="58" cy="40" rx="13" ry="14" fill="#FCD34D"/>
                                            <path d="M46 32C50 26 62 25 70 32C66 28 54 28 46 32Z" fill="#0F172A"/>
                                            <circle cx="53" cy="39" r="1.8" fill="#1E293B"/>
                                            <circle cx="63" cy="39" r="1.8" fill="#1E293B"/>
                                            <path d="M55 45C56 47 60 47 61 45" stroke="#1E293B" stroke-width="1.2" stroke-linecap="round"/>
                                            <ellipse cx="50" cy="43" rx="2" ry="1.2" fill="#F87171" fill-opacity="0.6"/>
                                            <ellipse cx="66" cy="43" rx="2" ry="1.2" fill="#F87171" fill-opacity="0.6"/>

                                            <path d="M30 82C30 70 42 62 58 62C74 62 86 70 86 82L96 130H20L30 82Z" fill="#FA2833"/>
                                            <path d="M52 62C52 67 58 71 58 71C58 71 64 67 64 62" stroke="#D91E27" stroke-width="2.5" fill="#A41D21"/>
                                            <line x1="55" y1="70" x2="55" y2="84" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"/>
                                            <line x1="61" y1="70" x2="61" y2="82" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"/>

                                            <path d="M22 96C22 96 36 104 50 106C52 106 50 114 44 114C32 114 20 106 20 106" fill="#D91E27"/>
                                            <path d="M94 96C94 96 80 104 66 106C64 106 66 114 72 114C84 114 96 106 96 106" fill="#D91E27"/>
                                        </g>

                                        <g transform="translate(68, 115)">
                                            <path d="M10 38L78 38L70 44L18 44Z" fill="#94A3B8"/>
                                            <path d="M18 6L70 6L76 38L12 38Z" fill="#0F172A"/>
                                            <path d="M20 9L68 9L73 36L15 36Z" fill="#1E293B"/>
                                            <ellipse cx="44" cy="22" rx="4.5" ry="5.5" fill="#FFFFFF"/>
                                            <path d="M46 15C46 13 44 12 43 12C43 14 45 15 46 15Z" fill="#FFFFFF"/>
                                        </g>

                                        <g transform="translate(162, 108)">
                                            <g transform="translate(0, 36)">
                                                <rect x="0" y="0" width="94" height="18" rx="3" fill="#FBDA33"/>
                                                <rect x="3" y="3" width="88" height="12" rx="2" fill="#F3A521"/>
                                                <rect x="86" y="2" width="7" height="14" fill="#FFFFFF" fill-opacity="0.9"/>
                                                <text x="44" y="11" fill="#063080" font-family="'Inter', sans-serif" font-weight="800" font-size="6.5" text-anchor="middle" letter-spacing="0.4">VERBAL REASONING</text>
                                            </g>

                                            <g transform="translate(4, 18)">
                                                <rect x="0" y="0" width="88" height="18" rx="3" fill="#2477F9"/>
                                                <rect x="3" y="3" width="82" height="12" rx="2" fill="#1757C1"/>
                                                <rect x="80" y="2" width="7" height="14" fill="#FFFFFF" fill-opacity="0.9"/>
                                                <text x="41" y="11" fill="#FFFFFF" font-family="'Inter', sans-serif" font-weight="800" font-size="7.5" text-anchor="middle" letter-spacing="0.5">MATHS</text>
                                            </g>

                                            <g transform="translate(8, 0)">
                                                <rect x="0" y="0" width="82" height="18" rx="3" fill="#FA2833"/>
                                                <rect x="3" y="3" width="76" height="12" rx="2" fill="#D91E27"/>
                                                <rect x="74" y="2" width="7" height="14" fill="#FFFFFF" fill-opacity="0.9"/>
                                                <text x="38" y="11" fill="#FFFFFF" font-family="'Inter', sans-serif" font-weight="800" font-size="7.5" text-anchor="middle" letter-spacing="0.5">ENGLISH</text>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Dynamic Active Subject Cards -->
                    <div class="row">
                        @forelse($subjectCards as $card)
                            @php
                                $themeClass = match ($card['theme']) {
                                    'english' => 'card-theme-english',
                                    'vr'      => 'card-theme-vr',
                                    default   => 'card-theme-maths',
                                };
                                $customStyle = $card['theme'] === 'nvr'
                                    ? 'background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 60%, #4C1D95 100%);'
                                    : '';
                            @endphp
                            <div class="col-md-4 col-12">
                                <div class="subject-banner-card {{ $themeClass }}" @if($customStyle) style="{{ $customStyle }}" @endif>
                                    <div class="subject-top-row">
                                        <div class="subject-icon-box">
                                            @if($card['theme'] === 'english')
                                                <i class="feather icon-book-open icon-english-color"></i>
                                            @elseif($card['theme'] === 'vr' || $card['theme'] === 'nvr')
                                                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="{{ $card['theme'] === 'nvr' ? '#7C3AED' : '#F3A521' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04z"></path>
                                                    <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04z"></path>
                                                </svg>
                                            @else
                                                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2477F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="4" y="2" width="16" height="20" rx="3"></rect>
                                                    <line x1="8" y1="6" x2="16" y2="6"></line>
                                                    <circle cx="8" cy="11" r="1"></circle>
                                                    <circle cx="12" cy="11" r="1"></circle>
                                                    <circle cx="16" cy="11" r="1"></circle>
                                                    <circle cx="8" cy="15" r="1"></circle>
                                                    <circle cx="12" cy="15" r="1"></circle>
                                                    <circle cx="16" cy="15" r="1"></circle>
                                                </svg>
                                            @endif
                                        </div>
                                        <a href="{{ $card['url'] }}" class="subject-arrow-link" title="Open {{ $card['title'] }}">
                                            <i class="feather icon-chevron-right"></i>
                                        </a>
                                    </div>

                                    <h3 class="subject-card-title">{{ $card['title'] }}</h3>
                                    <p class="subject-card-subtitle" title="{{ $card['subtitle'] }}">{{ $card['subtitle'] }}</p>

                                    <a href="{{ $card['url'] }}" class="btn-subject-start" @if($card['theme'] === 'nvr') style="color: #6D28D9 !important;" @endif>
                                        <span>Start Learning</span>
                                        <i class="feather icon-arrow-right"></i>
                                    </a>

                                    <div class="subject-progress-section">
                                        <div class="subject-progress-labels">
                                            <span>Progress</span>
                                            <span>{{ $card['progress'] }}%</span>
                                        </div>
                                        <div class="subject-progress-track">
                                            <div class="subject-progress-fill" style="width: {{ $card['progress'] }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="student-card-white text-center py-3 mb-2">
                                    <p class="text-muted mb-0">No active subjects have been assigned yet.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- 3. Lower Row: Focus Areas & Upcoming & Important -->
                    <div class="row match-height">

                        <!-- FOCUS AREAS CARD -->
                        <div class="col-md-6 col-12 d-flex">
                            <div class="student-card-white w-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="student-card-header">
                                        <h4 class="student-card-title">
                                            <i class="feather icon-target student-card-icon"></i>
                                            <span>Focus Areas</span>
                                        </h4>
                                        <a href="{{ route('student.focusareas') }}" class="student-view-all-link">
                                            <span>View All</span>
                                            <i class="feather icon-arrow-right"></i>
                                        </a>
                                    </div>

                                    <div class="focus-list-wrap">
                                        @forelse($focusTopics as $ft)
                                            @php
                                                $badgeClass = 'badge-' . $ft['style_key'] . '-bg';
                                                $percentClass = 'focus-percent-' . $ft['style_key'];
                                            @endphp
                                            <a href="{{ $ft['url'] }}" class="focus-item-row">
                                                <div class="focus-icon-badge {{ $badgeClass }}">
                                                    @if($ft['style_key'] === 'english')
                                                        <i class="feather icon-book"></i>
                                                    @elseif($ft['style_key'] === 'vr')
                                                        <i class="feather icon-cpu"></i>
                                                    @else
                                                        <i class="feather icon-grid"></i>
                                                    @endif
                                                </div>
                                                <div class="focus-item-info">
                                                    <h6 class="focus-item-name">{{ $ft['subject_name'] }}</h6>
                                                    <span class="focus-item-sub">{{ $ft['topic_name'] }}</span>
                                                </div>
                                                <div class="focus-progress-wrap">
                                                    <div class="focus-percent-text {{ $percentClass }}">{{ $ft['accuracy'] }}%</div>
                                                    <div class="focus-progress-track">
                                                        <div style="height: 100%; width: {{ $ft['accuracy'] }}%; background-color: {{ $ft['color'] }}; border-radius: 10px;"></div>
                                                    </div>
                                                </div>
                                                <i class="feather icon-chevron-right focus-chevron"></i>
                                            </a>
                                        @empty
                                            <div class="text-center py-3">
                                                <p class="text-muted font-small-3 mb-1">Complete a practice test to identify your focus areas.</p>
                                                <a href="{{ route('student.assessments') }}" class="student-view-all-link">
                                                    <span>Start an Assessment</span>
                                                    <i class="feather icon-arrow-right"></i>
                                                </a>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPCOMING & IMPORTANT CARD -->
                        <div class="col-md-6 col-12 d-flex">
                            <div class="student-card-white w-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="student-card-header">
                                        <h4 class="student-card-title">
                                            <i class="feather icon-calendar student-card-icon"></i>
                                            <span>Upcoming &amp; Important</span>
                                        </h4>
                                        <a href="{{ route('student.weeklytests') }}" class="student-view-all-link">
                                            <span>View All</span>
                                            <i class="feather icon-arrow-right"></i>
                                        </a>
                                    </div>

                                    <div class="upcoming-list-wrap">
                                        @forelse($upcomingItems as $item)
                                            <a href="{{ $item['url'] }}" class="upcoming-item-row">
                                                <div class="upcoming-date-box {{ $item['box_class'] }}">
                                                    <span class="upcoming-date-num">{{ $item['day'] }}</span>
                                                    <span class="upcoming-date-month">{{ $item['month'] }}</span>
                                                </div>
                                                <div class="upcoming-item-info">
                                                    <h6 class="upcoming-item-title">{{ $item['title'] }}</h6>
                                                    <span class="upcoming-item-sub">{{ $item['subtitle'] }}</span>
                                                </div>
                                                <span class="upcoming-pill {{ $item['pill_cls'] }}">{{ $item['pill'] }}</span>
                                                <i class="feather icon-chevron-right focus-chevron"></i>
                                            </a>
                                        @empty
                                            <div class="text-center py-3">
                                                <p class="text-muted font-small-3 mb-1">You are all caught up! No pending tests or notices right now.</p>
                                                <a href="{{ route('student.assessments') }}" class="student-view-all-link">
                                                    <span>Browse Assessments</span>
                                                    <i class="feather icon-arrow-right"></i>
                                                </a>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- ===================================================================
                     RIGHT COLUMN SECTION (Overall Progress, Weekly Progress, Streak, Motivation)
                     =================================================================== -->
                <div class="col-xl-4 col-lg-4 col-12">

                    <!-- CARD 1: Overall Progress -->
                    <div class="student-card-white student-side-card">
                        <div class="student-side-header">
                            <h4 class="student-side-title">Overall Progress</h4>
                            <a href="{{ route('student.analytics') }}" class="student-side-link">
                                <span>View Details</span>
                                <i class="feather icon-arrow-right"></i>
                            </a>
                        </div>
                        <div class="overall-progress-row">
                            <div class="donut-chart-wrap">
                                <svg class="donut-svg" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="37" fill="none" stroke="#E8EFFC" stroke-width="12" />
                                    <circle cx="50" cy="50" r="37" fill="none" stroke="#2477F9" stroke-width="12"
                                            stroke-dasharray="{{ $dashLength }} 232.5" stroke-dashoffset="0"
                                            transform="rotate(-90 50 50)" />
                                </svg>
                                <div class="donut-center-val">{{ $overallPct }}%</div>
                            </div>
                            <div class="overall-progress-info">
                                <div class="overall-val-big">{{ $overallCompleted }} of {{ $overallTotal }}</div>
                                <div class="overall-label-sub">tasks completed</div>
                                <div class="overall-track-bar">
                                    <div class="overall-fill-bar" style="width: {{ $overallPct }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 2: Weekly Progress (Mon-Sun) -->
                    <div class="student-card-white student-side-card">
                        <div class="student-side-header">
                            <h4 class="student-side-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#063080" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="3" ry="3"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                    <circle cx="8" cy="15" r="1" fill="#063080"></circle>
                                    <circle cx="12" cy="15" r="1" fill="#063080"></circle>
                                    <circle cx="16" cy="15" r="1" fill="#063080"></circle>
                                </svg>
                                <span>Weekly Progress</span>
                            </h4>
                            <a href="{{ route('student.analytics') }}" class="student-side-link">
                                <span>View Weekly</span>
                                <i class="feather icon-arrow-right"></i>
                            </a>
                        </div>

                        <div class="weekly-barchart-container">
                            <div class="weekly-grid-line"></div>
                            <div class="weekly-bars-flex">
                                @foreach($weeklyActivity as $bar)
                                    <div class="weekly-col-item">
                                        <span class="bar-num-label" @if($bar['count'] === 0) style="opacity: 0.45;" @endif>{{ $bar['count'] }}</span>
                                        <div class="bar-solid-pillar {{ $bar['count'] > 0 ? $bar['color_class'] : '' }}"
                                             style="height: {{ $bar['height'] }}px; @if($bar['count'] === 0) background-color: #cbd5e1; opacity: 0.45; @endif"></div>
                                        <span class="bar-day-label">{{ $bar['day'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3: Learning Streak -->
                    <div class="student-card-white student-side-card">
                        <div class="student-side-header">
                            <h4 class="student-side-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="flex-shrink: 0;">
                                    <path d="M12 2C9.5 5.5 10 8 8 10C6.5 11.5 5 13.5 5 16C5 19.866 8.13401 23 12 23C15.866 23 19 19.866 19 16C19 11.5 15.5 7.5 12 2Z" fill="#FA2833"/>
                                    <path d="M12 11C10.5 12.8 11 14.5 9.5 15.5C9 16 8 16.8 8 18C8 20.2 9.8 22 12 22C14.2 22 16 20.2 16 18C16 15.5 14 13.5 12 11Z" fill="#FBDA33"/>
                                </svg>
                                <span>Learning Streak</span>
                            </h4>
                        </div>
                        <div class="streak-flex-row">
                            <div class="streak-left-block">
                                <div class="streak-big-stat">
                                    <span class="streak-number">{{ $streakCount }}</span>
                                    <span class="streak-unit">{{ \Illuminate\Support\Str::plural('day', $streakCount) }}</span>
                                </div>
                                <div class="streak-sub-phrase">
                                    {{ $streakCount > 0 ? 'Keep it going!' : 'Complete a task today!' }}
                                </div>
                            </div>
                            <div class="streak-illustration-box">
                                <div class="streak-bg-circle"></div>
                                <svg width="60" height="60" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg" style="position: relative; z-index: 2;">
                                    <line x1="16" y1="36" x2="8" y2="38" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                    <line x1="19" y1="28" x2="10" y2="25" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                    <line x1="25" y1="20" x2="18" y2="13" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                    <line x1="34" y1="17" x2="33" y2="8" stroke="#FBDA33" stroke-width="2.5" stroke-linecap="round"/>
                                    <rect x="24" y="22" width="36" height="36" rx="8" fill="#FFFFFF" stroke="#2477F9" stroke-width="3"/>
                                    <path d="M24 28C24 24.5 26.5 22 30 22H54C57.5 22 60 24.5 60 28V31H24V28Z" fill="#2477F9"/>
                                    <rect x="32" y="18" width="3" height="6" rx="1.5" fill="#063080"/>
                                    <rect x="49" y="18" width="3" height="6" rx="1.5" fill="#063080"/>
                                    <circle cx="33" cy="38" r="1.8" fill="#BACCE6"/>
                                    <circle cx="42" cy="38" r="1.8" fill="#BACCE6"/>
                                    <circle cx="51" cy="38" r="1.8" fill="#BACCE6"/>
                                    <circle cx="33" cy="46" r="1.8" fill="#BACCE6"/>
                                    <circle cx="42" cy="46" r="1.8" fill="#BACCE6"/>
                                    <circle cx="51" cy="46" r="1.8" fill="#BACCE6"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 4: Motivation Banner -->
                    <div class="student-card-white student-side-card motivation-fluid-card">
                        <svg class="motivation-waves-bg" viewBox="0 0 380 96" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M-10 96C15 82 40 78 70 88C95 96 110 96 120 96H-10Z" fill="#7EC1FE" fill-opacity="0.6"/>
                            <path d="M330 0C330 18 350 36 380 40V0H330Z" fill="#7EC1FE" fill-opacity="0.7"/>
                            <path d="M250 96C275 92 300 75 325 70C350 65 365 72 380 82V96H250Z" fill="#8C52FF" fill-opacity="0.85"/>
                        </svg>

                        <div class="motivation-content-wrap">
                            <div class="motivation-trophy-box">
                                <svg width="38" height="38" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12C7 12 5 16 5 21C5 26 9 28 14 28" stroke="#F3A521" stroke-width="3" stroke-linecap="round"/>
                                    <path d="M36 12C41 12 43 16 43 21C43 26 39 28 34 28" stroke="#F3A521" stroke-width="3" stroke-linecap="round"/>
                                    <path d="M12 8H36V22C36 28.5 30.5 34 24 34C17.5 34 12 28.5 12 22V8Z" fill="#FBDA33"/>
                                    <path d="M15 11H33V20C33 25 29 29 24 29C19 29 15 25 15 20V11Z" fill="#F3A521" fill-opacity="0.25"/>
                                    <path d="M21 34H27V39H21V34Z" fill="#F3A521"/>
                                    <rect x="15" y="39" width="18" height="4" rx="2" fill="#E09214"/>
                                </svg>
                            </div>
                            <div>
                                <div class="motivation-heading">
                                    {{ $overallPct >= 80 ? 'Amazing progress!' : ($overallCompleted > 0 ? 'Keep going!' : 'Ready to start!') }}
                                </div>
                                <div class="motivation-subhead">
                                    {{ $overallPct >= 80 ? "You're crushing your goals!" : ($overallCompleted > 0 ? "You're doing great!" : 'Pick a subject to begin!') }}
                                </div>
                            </div>
                        </div>

                        <div class="motivation-sparkles-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="#F3A521" xmlns="http://www.w3.org/2000/svg" style="transform: translateY(-6px);">
                                <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                            </svg>
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="#FBDA33" xmlns="http://www.w3.org/2000/svg" style="transform: translate(2px, 8px);">
                                <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                            </svg>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
<!-- END: Content-->
@endsection
