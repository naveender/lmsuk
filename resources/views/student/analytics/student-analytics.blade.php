@extends('layouts.app')

@section('title', 'Student Analytics - Aspire Learners')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body">

            <!-- Page Header with Title, Subtitle and Period Dropdown -->
            <div class="analytics-header d-flex justify-content-between align-items-center flex-wrap mb-2">
                <div>
                    <h1 class="analytics-title font-weight-bolder mb-25">Analytics</h1>
                    <p class="analytics-subtitle text-muted mb-0 font-medium-1">Track your progress and see how you're improving.</p>
                </div>
                <div class="mt-1 mt-sm-0">
                    <div class="dropdown">
                        <button class="btn analytics-dropdown-btn dropdown-toggle font-weight-bold d-inline-flex align-items-center px-2 py-75 rounded-pill shadow-sm"
                                type="button"
                                id="analyticsPeriodDropdown"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <i class="feather icon-calendar mr-75 text-primary"></i>
                            <span class="mr-50">{{ $selectedPeriodLabel }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right analytics-dropdown-menu shadow-lg border-0" aria-labelledby="analyticsPeriodDropdown">
                            <a class="dropdown-item py-75 {{ $period === '4_weeks' ? 'active font-weight-bold' : '' }}" href="{{ route('student.analytics', ['period' => '4_weeks']) }}">
                                <i class="feather icon-clock mr-50"></i> Last 4 Weeks
                            </a>
                            <a class="dropdown-item py-75 {{ $period === '8_weeks' ? 'active font-weight-bold' : '' }}" href="{{ route('student.analytics', ['period' => '8_weeks']) }}">
                                <i class="feather icon-clock mr-50"></i> Last 8 Weeks
                            </a>
                            <a class="dropdown-item py-75 {{ $period === '12_weeks' ? 'active font-weight-bold' : '' }}" href="{{ route('student.analytics', ['period' => '12_weeks']) }}">
                                <i class="feather icon-calendar mr-50"></i> Last 12 Weeks
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item py-75 {{ $period === 'all' ? 'active font-weight-bold' : '' }}" href="{{ route('student.analytics', ['period' => 'all']) }}">
                                <i class="feather icon-layers mr-50"></i> All Time
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Row: 4 Metric KPI Cards -->
            <div class="row match-height mb-2">
                <!-- Card 1: Total Assessments -->
                <div class="col-xl-3 col-md-6 col-12 mb-1 mb-xl-0">
                    <div class="card analytics-stat-card h-100 shadow-sm border-0">
                        <div class="card-body d-flex align-items-center p-2">
                            <div class="analytics-icon-circle bg-blue text-white mr-1 mr-sm-2 flex-shrink-0">
                                <i data-lucide="bar-chart-2" style="width: 26px; height: 26px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="analytics-card-label d-block text-muted font-small-3 font-weight-bold mb-25">Total Assessments</span>
                                <h2 class="analytics-card-val font-weight-bolder mb-0">{{ $totalAssessments }}</h2>
                                <span class="analytics-card-subtext font-small-2 text-muted">&nbsp;</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Average Score -->
                <div class="col-xl-3 col-md-6 col-12 mb-1 mb-xl-0">
                    <div class="card analytics-stat-card h-100 shadow-sm border-0">
                        <div class="card-body d-flex align-items-center p-2">
                            <div class="analytics-icon-circle bg-red text-white mr-1 mr-sm-2 flex-shrink-0">
                                <i data-lucide="target" style="width: 26px; height: 26px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="analytics-card-label d-block text-muted font-small-3 font-weight-bold mb-25">Average Score</span>
                                <h2 class="analytics-card-val font-weight-bolder mb-0">{{ $totalAssessments > 0 ? $averageScore . '%' : '—' }}</h2>
                                @if($scoreGrowth === null)
                                    <span class="analytics-card-subtext font-small-2 text-muted">
                                        {{ $totalAssessments > 0 ? 'Need 2+ tests for trend' : 'No completed tests' }}
                                    </span>
                                @elseif($scoreGrowth > 0)
                                    <span class="analytics-card-subtext font-small-2 font-weight-bold text-success d-inline-flex align-items-center">
                                        <i class="feather icon-arrow-up mr-25 font-small-1"></i> +{{ $scoreGrowth }}%
                                    </span>
                                @elseif($scoreGrowth < 0)
                                    <span class="analytics-card-subtext font-small-2 font-weight-bold text-danger d-inline-flex align-items-center">
                                        <i class="feather icon-arrow-down mr-25 font-small-1"></i> {{ $scoreGrowth }}%
                                    </span>
                                @else
                                    <span class="analytics-card-subtext font-small-2 font-weight-bold text-muted d-inline-flex align-items-center">
                                        0% change
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Overall Progress -->
                <div class="col-xl-3 col-md-6 col-12 mb-1 mb-xl-0">
                    <div class="card analytics-stat-card h-100 shadow-sm border-0">
                        <div class="card-body d-flex align-items-center p-2">
                            <div class="analytics-icon-circle bg-amber text-white mr-1 mr-sm-2 flex-shrink-0">
                                <i data-lucide="trending-up" style="width: 26px; height: 26px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="analytics-card-label d-block text-muted font-small-3 font-weight-bold mb-25">Overall Progress</span>
                                @if($overallProgress !== null)
                                    <h2 class="analytics-card-val font-weight-bolder mb-0 {{ $overallProgress < 0 ? 'text-danger' : '' }}">
                                        {{ ($overallProgress >= 0 ? '+' : '') . $overallProgress }}%
                                    </h2>
                                    <span class="analytics-card-subtext font-small-2 text-muted">Vs. previous period</span>
                                @else
                                    <h2 class="analytics-card-val font-weight-bolder mb-0">—</h2>
                                    <span class="analytics-card-subtext font-small-2 text-muted">No prior period data</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Learning Streak -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card analytics-stat-card h-100 shadow-sm border-0">
                        <div class="card-body d-flex align-items-center p-2">
                            <div class="analytics-icon-circle bg-purple text-white mr-1 mr-sm-2 flex-shrink-0">
                                <i data-lucide="star" style="width: 26px; height: 26px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="analytics-card-label d-block text-muted font-small-3 font-weight-bold mb-25">Learning Streak</span>
                                <h2 class="analytics-card-val font-weight-bolder mb-0">{{ $streak }} {{ Str::plural('day', $streak) }}</h2>
                                <span class="analytics-card-subtext font-small-2 text-muted">{{ $streak > 0 ? 'Keep it going!' : 'Practice today!' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Row: Performance Trend & Subject Performance -->
            <div class="row match-height mb-2">
                <!-- Performance Trend (Area Chart) -->
                <div class="col-xl-7 col-lg-7 col-12 mb-2 mb-lg-0">
                    <div class="card analytics-content-card h-100 shadow-sm border-0">
                        <div class="card-header border-0 pb-0 d-flex flex-column align-items-start">
                            <h4 class="card-title font-weight-bold mb-25">Performance Trend</h4>
                            <p class="text-muted font-small-3 mb-0">Your average score over time.</p>
                        </div>
                        <div class="card-body pt-1 pb-2 d-flex flex-column justify-content-center">
                            @if(!empty($trendData['series']))
                                <div id="performanceTrendChart" class="apex-charts-trend" style="min-height: 280px;"></div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="feather icon-trending-up font-large-2 d-block mb-1 opacity-75"></i>
                                    <p class="mb-1">No completed assessments found in {{ strtolower($selectedPeriodLabel) }}.</p>
                                    <a href="{{ route('student.assessments') }}" class="btn btn-sm btn-outline-primary">Start a Practice Test</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subject Performance (Column Chart) -->
                <div class="col-xl-5 col-lg-5 col-12">
                    <div class="card analytics-content-card h-100 shadow-sm border-0">
                        <div class="card-header border-0 pb-0 d-flex flex-column align-items-start">
                            <h4 class="card-title font-weight-bold mb-25">Subject Performance</h4>
                            <p class="text-muted font-small-3 mb-0">Your average score by subject.</p>
                        </div>
                        <div class="card-body pt-1 pb-2 d-flex flex-column justify-content-between">
                            @if(!empty($hasSubjectData) && !empty($subjectPerformance))
                                <div id="subjectPerformanceChart" class="apex-charts-subject" style="min-height: 220px;"></div>

                                <div class="subject-icons-row d-flex justify-content-around text-center pt-1 mt-1 border-top">
                                    @foreach($subjectPerformance as $subj)
                                        @php
                                            $iconColorClass = match ($subj['key']) {
                                                'english' => 'text-danger',
                                                'maths'   => 'text-primary',
                                                'vr'      => 'text-warning',
                                                'science' => 'text-success',
                                                default   => 'text-info',
                                            };
                                            $lucideName = match ($subj['icon']) {
                                                'book-open'  => 'book-open',
                                                'calculator' => 'calculator',
                                                'brain'      => 'brain',
                                                'zap'        => 'zap',
                                                default      => 'layers',
                                            };
                                        @endphp
                                        <div class="subject-icon-item text-center">
                                            <div class="subject-icon-box {{ $iconColorClass }} mx-auto mb-50" title="{{ $subj['title'] }}">
                                                <i data-lucide="{{ $lucideName }}" style="width: 26px; height: 26px;"></i>
                                            </div>
                                            <span class="subject-icon-caption font-small-2 font-weight-bold text-muted">{{ $subj['title'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 my-auto text-muted">
                                    <i class="feather icon-bar-chart-2 font-large-2 d-block mb-1 opacity-75"></i>
                                    <p class="mb-0">Complete an assessment to view your subject breakdown.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Recent Assessments & Accuracy by Topic -->
            <div class="row match-height">
                <!-- Recent Assessments Table -->
                <div class="col-xl-6 col-lg-6 col-12 mb-2 mb-lg-0">
                    <div class="card analytics-content-card h-100 shadow-sm border-0">
                        <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title font-weight-bold mb-25">Recent Assessments</h4>
                            </div>
                            <a href="{{ route('student.weeklytests') }}" class="analytics-view-all font-weight-bold">
                                View All &rarr;
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover analytics-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0 text-muted font-small-2 font-weight-bold pl-2">Date</th>
                                            <th class="border-top-0 text-muted font-small-2 font-weight-bold">Assessment</th>
                                            <th class="border-top-0 text-muted font-small-2 font-weight-bold text-center">Subject</th>
                                            <th class="border-top-0 text-muted font-small-2 font-weight-bold text-right pr-2">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentAssessments as $recent)
                                            <tr>
                                                <td class="font-small-3 text-muted pl-2 text-nowrap align-middle">
                                                    {{ $recent->display_date ? $recent->display_date->format('d M Y') : '' }}
                                                </td>
                                                <td class="font-small-3 font-weight-bold text-dark align-middle">
                                                    <a href="{{ route('student.attempts.result', $recent->id) }}" class="text-truncate d-inline-block text-reset" style="max-width: 220px;" title="{{ $recent->paper?->title ?? 'Assessment' }}">
                                                        {{ $recent->paper?->title ?? 'Assessment' }}
                                                    </a>
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if($recent->subject_badge_type === 'english')
                                                        <span class="badge-subject badge-subject-english">{{ $recent->subject_badge_name }}</span>
                                                    @elseif($recent->subject_badge_type === 'maths')
                                                        <span class="badge-subject badge-subject-maths">{{ $recent->subject_badge_name }}</span>
                                                    @elseif($recent->subject_badge_type === 'vr')
                                                        <span class="badge-subject badge-subject-vr">{{ $recent->subject_badge_name }}</span>
                                                    @else
                                                        <span class="badge-subject badge-subject-maths">{{ $recent->subject_badge_name }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-right pr-2 align-middle">
                                                    @php
                                                        $scoreColorClass = 'text-success';
                                                        if ($recent->score_pct < 61) {
                                                            $scoreColorClass = 'text-danger';
                                                        } elseif ($recent->score_pct < 80) {
                                                            $scoreColorClass = 'text-warning';
                                                        }
                                                    @endphp
                                                    <span class="font-weight-bolder font-small-3 {{ $scoreColorClass }}">
                                                        {{ $recent->score_pct }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="feather icon-inbox font-large-1 d-block mb-1 text-muted"></i>
                                                    No completed assessments found in this period yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accuracy by Topic Progress Bars -->
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="card analytics-content-card h-100 shadow-sm border-0">
                        <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title font-weight-bold mb-25">Accuracy by Topic</h4>
                                <p class="text-muted font-small-3 mb-0">Your performance in key topics.</p>
                            </div>
                            <a href="{{ route('student.focusareas') }}" class="analytics-view-all font-weight-bold">
                                View All &rarr;
                            </a>
                        </div>
                        <div class="card-body pt-2 pb-2">
                            <div class="topic-progress-list">
                                @forelse($accuracyByTopic as $topic)
                                    <div class="topic-progress-item d-flex align-items-center mb-1 pb-50">
                                        <!-- Topic Name -->
                                        <div class="topic-name-col font-weight-bold text-dark font-small-3">
                                            {{ $topic['name'] }}
                                        </div>
                                        <!-- Progress Bar -->
                                        <div class="topic-bar-col flex-grow-1 mx-2">
                                            <div class="progress topic-progress-track">
                                                <div class="progress-bar topic-progress-fill"
                                                     role="progressbar"
                                                     style="width: {{ $topic['accuracy'] }}%; background-color: {{ $topic['color'] }};"
                                                     aria-valuenow="{{ $topic['accuracy'] }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Percentage Label -->
                                        <div class="topic-percent-col text-right font-weight-bolder font-small-3 text-dark">
                                            {{ $topic['accuracy'] }}%
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <i class="feather icon-pie-chart font-large-1 d-block mb-1"></i>
                                        No topic performance data yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    /* ==========================================================================
       STUDENT ANALYTICS - DESIGN SYSTEM (LIGHT & DARK MODE COMPATIBLE)
       ========================================================================== */
    .analytics-title {
        color: var(--text-primary, #0f172a);
        font-size: 2rem;
        letter-spacing: -0.5px;
    }

    .analytics-subtitle {
        color: var(--text-muted, #64748b);
    }

    /* Dropdown Button */
    .analytics-dropdown-btn {
        background-color: var(--card-bg, #ffffff) !important;
        border: 1px solid var(--border-color, #e2e8f0) !important;
        color: var(--text-primary, #1e293b) !important;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .analytics-dropdown-btn:hover,
    .analytics-dropdown-btn:focus {
        border-color: #2563eb !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12) !important;
    }

    .analytics-dropdown-menu {
        border-radius: 14px;
        background-color: var(--card-bg, #ffffff);
        border: 1px solid var(--border-subtle, rgba(0, 0, 0, 0.05)) !important;
    }

    .analytics-dropdown-menu .dropdown-item {
        color: var(--text-body, #334155);
        font-size: 0.875rem;
        border-radius: 8px;
        margin: 2px 6px;
        width: auto;
    }

    .analytics-dropdown-menu .dropdown-item:hover {
        background-color: var(--theme-primary-light, #ebf3ff);
        color: #2563eb;
    }

    .analytics-dropdown-menu .dropdown-item.active {
        background-color: #2563eb;
        color: #ffffff;
    }

    /* Cards Base */
    .analytics-stat-card,
    .analytics-content-card {
        background-color: var(--card-bg, #ffffff) !important;
        border-radius: 16px !important;
        border: 1px solid var(--border-subtle, #f1f5f9) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .analytics-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06) !important;
    }

    .analytics-content-card {
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important;
    }

    /* KPI Icon Circles */
    .analytics-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    }

    .bg-blue {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .bg-red {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    }

    .bg-amber {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    }

    .bg-purple {
        background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
    }

    .analytics-card-label {
        color: var(--text-muted, #64748b) !important;
        font-size: 0.85rem;
    }

    .analytics-card-val {
        color: var(--text-primary, #0f172a) !important;
        font-size: 1.85rem;
        line-height: 1.15;
    }

    /* View All Link */
    .analytics-view-all {
        color: #2563eb;
        font-size: 0.875rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .analytics-view-all:hover {
        color: #1d4ed8;
        transform: translateX(3px);
        display: inline-block;
    }

    /* Table Styles */
    .analytics-table th {
        background-color: var(--body-bg, #f8fafc);
        color: var(--text-muted, #64748b);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        padding: 0.85rem 1rem;
    }

    .analytics-table td {
        padding: 0.85rem 1rem;
        border-top: 1px solid var(--border-subtle, #f1f5f9);
    }

    .analytics-table tbody tr:hover {
        background-color: var(--theme-primary-light, rgba(37, 99, 235, 0.03));
    }

    /* Subject Badges */
    .badge-subject {
        display: inline-block;
        padding: 0.35rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-align: center;
        min-width: 65px;
    }

    .badge-subject-english {
        background-color: #fee2e2;
        color: #ef4444;
    }

    .badge-subject-maths {
        background-color: #dbeafe;
        color: #2563eb;
    }

    .badge-subject-vr {
        background-color: #fef3c7;
        color: #d97706;
    }

    /* Accuracy by Topic Progress Bars */
    .topic-name-col {
        width: 170px;
        flex-shrink: 0;
        color: var(--text-primary, #1e293b);
        font-size: 0.85rem;
    }

    .topic-progress-track {
        height: 10px;
        border-radius: 9999px;
        background-color: var(--body-bg, #f1f5f9);
        overflow: hidden;
    }

    .topic-progress-fill {
        border-radius: 9999px;
        transition: width 0.8s ease-in-out;
    }

    .topic-percent-col {
        width: 50px;
        flex-shrink: 0;
        color: var(--text-primary, #1e293b);
        font-size: 0.85rem;
    }

    /* Subject Icons Box */
    .subject-icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .subject-icon-item:hover .subject-icon-box {
        transform: scale(1.15);
    }

    /* Dark Mode Overrides */
    body.dark-layout .analytics-title {
        color: #f8fafc !important;
    }

    body.dark-layout .analytics-card-val,
    body.dark-layout .card-title,
    body.dark-layout .topic-name-col,
    body.dark-layout .topic-percent-col,
    body.dark-layout .analytics-table td {
        color: #f8fafc !important;
    }

    body.dark-layout .analytics-table th {
        background-color: rgba(255, 255, 255, 0.04);
        color: #94a3b8 !important;
    }

    body.dark-layout .topic-progress-track {
        background-color: rgba(255, 255, 255, 0.08);
    }

    body.dark-layout .badge-subject-english {
        background-color: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
    }

    body.dark-layout .badge-subject-maths {
        background-color: rgba(37, 99, 235, 0.2);
        color: #93c5fd;
    }

    body.dark-layout .badge-subject-vr {
        background-color: rgba(245, 158, 11, 0.2);
        color: #fde68a;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize Lucide vector icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark' || document.body.classList.contains('dark-layout');
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? '#334155' : '#f1f5f9';
    const textColor = isDark ? '#f8fafc' : '#0f172a';

    // -------------------------------------------------------------------------
    // 1. Performance Trend Area Spline Chart
    // -------------------------------------------------------------------------
    const trendCategories = @json($trendData['categories'] ?? []);
    const trendSeriesData = @json($trendData['series'] ?? []);
    const trendPointLabels = @json($trendData['labels'] ?? []);

    const trendChartEl = document.querySelector("#performanceTrendChart");
    if (trendChartEl && trendSeriesData.length > 0 && typeof ApexCharts !== 'undefined') {
        const trendChartOptions = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                parentHeightOffset: 0,
                zoom: { enabled: false },
                fontFamily: 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
            },
            series: [{
                name: 'Score',
                data: trendSeriesData
            }],
            colors: ['#2563eb'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.25,
                    opacityTo: 0.02,
                    stops: [0, 90, 100],
                    colorStops: [
                        { offset: 0, color: '#2563eb', opacity: 0.28 },
                        { offset: 100, color: '#2563eb', opacity: 0.02 }
                    ]
                }
            },
            markers: {
                size: 4,
                colors: ['#2563eb'],
                strokeColors: '#ffffff',
                strokeWidth: 2,
                hover: {
                    size: 6,
                    sizeOffset: 2
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: trendCategories,
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '11px',
                        fontWeight: 500
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickAmount: 4,
                labels: {
                    formatter: function (val) {
                        return Math.round(val) + '%';
                    },
                    style: {
                        colors: labelColor,
                        fontSize: '11px'
                    }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                yaxis: { lines: { show: true } },
                xaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (val, opts) {
                        const ctxLabel = trendPointLabels[opts.dataPointIndex] || '';
                        return ctxLabel ? (val + '% (' + ctxLabel + ')') : (val + '%');
                    }
                }
            }
        };

        const trendChart = new ApexCharts(trendChartEl, trendChartOptions);
        trendChart.render();
    }

    // -------------------------------------------------------------------------
    // 2. Subject Performance Distributed Bar Chart
    // -------------------------------------------------------------------------
    const subjectItems = @json($subjectPerformance ?? []);
    const subjectChartEl = document.querySelector("#subjectPerformanceChart");

    if (subjectChartEl && subjectItems.length > 0 && typeof ApexCharts !== 'undefined') {
        const subjectChartOptions = {
            chart: {
                type: 'bar',
                height: 220,
                toolbar: { show: false },
                parentHeightOffset: 0,
                fontFamily: 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
            },
            series: [{
                name: 'Score',
                data: subjectItems.map(s => s.score)
            }],
            colors: subjectItems.map(s => s.color),
            plotOptions: {
                bar: {
                    distributed: true,
                    borderRadius: 8,
                    columnWidth: subjectItems.length <= 3 ? '42%' : '55%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val + '%';
                },
                offsetY: -22,
                style: {
                    fontSize: '13px',
                    fontWeight: '700',
                    colors: [textColor]
                }
            },
            legend: { show: false },
            xaxis: {
                categories: subjectItems.map(s => s.title),
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '12px',
                        fontWeight: 600
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickAmount: 4,
                labels: {
                    formatter: function (val) {
                        return Math.round(val) + '%';
                    },
                    style: {
                        colors: labelColor,
                        fontSize: '11px'
                    }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                yaxis: { lines: { show: true } },
                xaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (val, opts) {
                        const tests = subjectItems[opts.dataPointIndex]?.tests ?? 0;
                        return val + '% Average (' + tests + ' ' + (tests === 1 ? 'test' : 'tests') + ')';
                    }
                }
            }
        };

        const subjectChart = new ApexCharts(subjectChartEl, subjectChartOptions);
        subjectChart.render();
    }
});
</script>
@endpush
@endsection
