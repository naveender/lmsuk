@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    /* ==========================================================================
       ADMIN DASHBOARD REDESIGN - HIGH FIDELITY TO REFERENCE IMAGE
       ========================================================================== */

    :root {
        --dash-bg: #f4f6fa;
        --dash-card-bg: #ffffff;
        --dash-text-dark: #0f172a;
        --dash-text-body: #334155;
        --dash-text-muted: #64748b;
        --dash-border: rgba(226, 232, 240, 0.85);
        --dash-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        --dash-shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    body.dark-layout {
        --dash-bg: #0e1726;
        --dash-card-bg: #1e2440;
        --dash-text-dark: #f8fafc;
        --dash-text-body: #cbd5e1;
        --dash-text-muted: #828690;
        --dash-border: rgba(255, 255, 255, 0.08);
        --dash-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        --dash-shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.4);
    }

    .admin-dashboard-container {
        padding-top: 0.25rem;
    }

    /* --------------------------------------------------------------------------
       1. Top 4 KPI Stat Cards
       -------------------------------------------------------------------------- */
    .stat-kpi-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        box-shadow: var(--dash-shadow);
        position: relative;
        overflow: hidden;
        padding: 22px 20px;
        margin-bottom: 24px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-decoration: none !important;
        display: block;
    }

    .stat-kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--dash-shadow-hover);
    }

    .stat-card-inner {
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .stat-icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 18px;
        transition: transform 0.3s ease;
    }

    .stat-kpi-card:hover .stat-icon-wrapper {
        transform: scale(1.08);
    }

    .stat-icon-wrapper svg {
        width: 26px;
        height: 26px;
        stroke-width: 2.2;
    }

    .stat-kpi-card.card-red .stat-icon-wrapper {
        background: #fee2e2;
        color: #ef4444;
    }
    .stat-kpi-card.card-blue .stat-icon-wrapper {
        background: #e0f2fe;
        color: #0284c7;
    }
    .stat-kpi-card.card-amber .stat-icon-wrapper {
        background: #fef3c7;
        color: #f59e0b;
    }
    .stat-kpi-card.card-green .stat-icon-wrapper {
        background: #dcfce7;
        color: #10b981;
    }

    .stat-meta {
        display: flex;
        flex-direction: column;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }

    .stat-kpi-card.card-red .stat-number { color: #ef4444; }
    .stat-kpi-card.card-blue .stat-number { color: #0284c7; }
    .stat-kpi-card.card-amber .stat-number { color: #f59e0b; }
    .stat-kpi-card.card-green .stat-number { color: #10b981; }

    .stat-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dash-text-dark);
        margin-bottom: 2px;
        line-height: 1.2;
    }

    .stat-subtitle {
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--dash-text-muted);
        line-height: 1.2;
    }

    /* Soft wave decorative shape at the bottom right corner */
    .stat-card-wave {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 140px;
        height: 70px;
        pointer-events: none;
        z-index: 1;
    }

    .stat-kpi-card.card-red .stat-card-wave path { fill: #ef4444; opacity: 0.08; }
    .stat-kpi-card.card-blue .stat-card-wave path { fill: #0284c7; opacity: 0.08; }
    .stat-kpi-card.card-amber .stat-card-wave path { fill: #f59e0b; opacity: 0.08; }
    .stat-kpi-card.card-green .stat-card-wave path { fill: #10b981; opacity: 0.08; }

    /* Watermark icon on the right for Card 1 */
    .stat-card-watermark {
        position: absolute;
        right: 18px;
        top: 20px;
        color: #f87171;
        opacity: 0.45;
        pointer-events: none;
        z-index: 1;
    }
    .stat-card-watermark svg {
        width: 28px;
        height: 28px;
        stroke-width: 1.8;
    }

    /* --------------------------------------------------------------------------
       2. Middle Row: Interactive Charts Section
       -------------------------------------------------------------------------- */
    .chart-box-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        box-shadow: var(--dash-shadow);
        padding: 22px 24px;
        margin-bottom: 28px;
        height: calc(100% - 28px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .chart-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .chart-box-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .chart-header-icon svg {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
    }

    .chart-box-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--dash-text-dark);
        margin: 0;
        letter-spacing: -0.01em;
    }

    .chart-view-details-link {
        font-size: 0.82rem;
        font-weight: 600;
        color: #0284c7;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .chart-view-details-link:hover {
        color: #0369a1;
        transform: translateX(2px);
    }

    /* Custom Subject Donut Chart & Legend Layout */
    .donut-chart-container {
        display: flex;
        align-items: center;
        min-height: 250px;
    }

    .subject-legend-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
        width: 100%;
        padding-left: 0;
    }

    .subject-legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.84rem;
    }

    .legend-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-grow: 1;
        min-width: 0;
    }

    .legend-color-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-name {
        font-weight: 600;
        color: var(--dash-text-body);
        font-size: 0.82rem;
        line-height: 1.3;
        white-space: nowrap;
    }

    .legend-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
        margin-left: 8px;
    }

    .legend-count {
        font-weight: 800;
        color: var(--dash-text-dark);
        min-width: 14px;
        text-align: right;
    }

    .legend-percent {
        font-weight: 500;
        color: var(--dash-text-muted);
        min-width: 30px;
        text-align: right;
    }

    /* --------------------------------------------------------------------------
       3. Bottom Section: System Quick Actions
       -------------------------------------------------------------------------- */
    .quick-actions-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .quick-actions-title-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quick-actions-lightning {
        font-size: 1.35rem;
        color: #f59e0b;
        display: inline-block;
        line-height: 1;
    }

    .quick-actions-main-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--dash-text-dark);
        margin: 0;
        letter-spacing: -0.01em;
    }

    .quick-actions-subtext {
        font-size: 0.84rem;
        color: var(--dash-text-muted);
        font-weight: 500;
    }

    .quick-action-card-col {
        margin-bottom: 24px;
    }

    .quick-action-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        box-shadow: var(--dash-shadow);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .quick-action-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--dash-shadow-hover);
    }

    .quick-action-top {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 18px;
    }

    .quick-action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.25s ease;
    }

    .quick-action-card:hover .quick-action-icon {
        transform: scale(1.08);
    }

    .quick-action-icon svg {
        width: 24px;
        height: 24px;
        stroke-width: 2.2;
    }

    .quick-action-info {
        flex-grow: 1;
        min-width: 0;
    }

    .quick-action-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dash-text-dark);
        margin-bottom: 4px;
        line-height: 1.25;
    }

    .quick-action-desc {
        font-size: 0.77rem;
        color: var(--dash-text-muted);
        line-height: 1.45;
        margin: 0;
        min-height: 34px;
    }

    /* Vibrant Full-Width Action Buttons */
    .quick-action-btn {
        width: 100%;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 9px 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: none !important;
        color: #ffffff !important;
        text-decoration: none !important;
        transition: all 0.25s ease;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(1.08);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .quick-action-btn svg {
        width: 15px;
        height: 15px;
        stroke-width: 2.4;
        transition: transform 0.2s ease;
    }

    .quick-action-btn:hover svg {
        transform: translateX(3px);
    }

    /* Card Specific Color Themes */
    /* 1. Manage Students */
    .qa-students .quick-action-icon { background: #fee2e2; color: #ef4444; }
    .qa-students .quick-action-btn {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        box-shadow: 0 4px 14px rgba(255, 65, 108, 0.3);
    }

    /* 2. Manage Classes */
    .qa-classes .quick-action-icon { background: #e0f2fe; color: #0284c7; }
    .qa-classes .quick-action-btn {
        background: linear-gradient(135deg, #0066ff 0%, #0099ff 100%);
        box-shadow: 0 4px 14px rgba(0, 102, 255, 0.3);
    }

    /* 3. Questions Bank */
    .qa-questions .quick-action-icon { background: #fef3c7; color: #f59e0b; }
    .qa-questions .quick-action-btn {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
    }

    /* 4. Announcements */
    .qa-announcements .quick-action-icon { background: #dcfce7; color: #10b981; }
    .qa-announcements .quick-action-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
    }

    /* 5. Users Directory */
    .qa-users .quick-action-icon { background: #ede9fe; color: #8b5cf6; }
    .qa-users .quick-action-btn {
        background: linear-gradient(135deg, #7c3aed 0%, #9333ea 100%);
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);
    }

    /* 6. Create Exam Paper */
    .qa-papers-create .quick-action-icon { background: #fee2e2; color: #ef4444; }
    .qa-papers-create .quick-action-btn {
        background: linear-gradient(135deg, #f97316 0%, #ef4444 100%);
        box-shadow: 0 4px 14px rgba(249, 115, 22, 0.3);
    }

    /* 7. Papers Directory */
    .qa-papers-dir .quick-action-icon { background: #e0f2fe; color: #0284c7; }
    .qa-papers-dir .quick-action-btn {
        background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
    }

    /* 8. System Settings */
    .qa-settings .quick-action-icon { background: #f3e8ff; color: #a855f7; }
    .qa-settings .quick-action-btn {
        background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
        box-shadow: 0 4px 14px rgba(139, 92, 246, 0.3);
    }

    /* ApexCharts custom tooltip and styling */
    .apexcharts-tooltip {
        border-radius: 8px !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    }
</style>
@endpush

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body admin-dashboard-container">

            <!-- =========================================================================
                 SECTION 1: 4 TOP STAT CARDS (Matches Reference Image)
                 ========================================================================= -->
            <section id="kpi-dashboard-stats">
                <div class="row">
                    <!-- 1. Total Students -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <a href="{{ route('admin.students.index') }}" class="stat-kpi-card card-red">
                            <div class="stat-card-inner">
                                <div class="stat-icon-wrapper">
                                    <i data-lucide="users"></i>
                                </div>
                                <div class="stat-meta">
                                    <span class="stat-number">{{ $stats['students'] }}</span>
                                    <span class="stat-title">Total Students</span>
                                    <span class="stat-subtitle">Across all classes</span>
                                </div>
                            </div>
                            <!-- Soft Pink Watermark Outline Icon -->
                            <div class="stat-card-watermark">
                                <i data-lucide="users"></i>
                            </div>
                            <!-- Corner Wave Accent -->
                            <svg class="stat-card-wave" viewBox="0 0 140 70" preserveAspectRatio="none">
                                <path d="M0,70 C40,35 85,65 140,15 L140,70 Z"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- 2. Active Classes -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <a href="{{ route('admin.classes.index') }}" class="stat-kpi-card card-blue">
                            <div class="stat-card-inner">
                                <div class="stat-icon-wrapper">
                                    <i data-lucide="graduation-cap"></i>
                                </div>
                                <div class="stat-meta">
                                    <span class="stat-number">{{ $stats['classes'] }}</span>
                                    <span class="stat-title">Active Classes</span>
                                    <span class="stat-subtitle">Currently running</span>
                                </div>
                            </div>
                            <svg class="stat-card-wave" viewBox="0 0 140 70" preserveAspectRatio="none">
                                <path d="M0,70 C40,35 85,65 140,15 L140,70 Z"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- 3. Question Bank -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <a href="{{ route('admin.questions.index') }}" class="stat-kpi-card card-amber">
                            <div class="stat-card-inner">
                                <div class="stat-icon-wrapper">
                                    <i data-lucide="help-circle"></i>
                                </div>
                                <div class="stat-meta">
                                    <span class="stat-number">{{ $stats['questions'] }}</span>
                                    <span class="stat-title">Question Bank</span>
                                    <span class="stat-subtitle">Total questions</span>
                                </div>
                            </div>
                            <svg class="stat-card-wave" viewBox="0 0 140 70" preserveAspectRatio="none">
                                <path d="M0,70 C40,35 85,65 140,15 L140,70 Z"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- 4. Exam Papers -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <a href="{{ route('admin.papers.index') }}" class="stat-kpi-card card-green">
                            <div class="stat-card-inner">
                                <div class="stat-icon-wrapper">
                                    <i data-lucide="file-text"></i>
                                </div>
                                <div class="stat-meta">
                                    <span class="stat-number">{{ $stats['papers'] }}</span>
                                    <span class="stat-title">Exam Papers</span>
                                    <span class="stat-subtitle">Published papers</span>
                                </div>
                            </div>
                            <svg class="stat-card-wave" viewBox="0 0 140 70" preserveAspectRatio="none">
                                <path d="M0,70 C40,35 85,65 140,15 L140,70 Z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>

            <!-- =========================================================================
                 SECTION 2: INTERACTIVE CHARTS (Class Enrollment & Subject Spread)
                 ========================================================================= -->
            <section id="dashboard-charts">
                <div class="row align-items-stretch">
                    <!-- Left: Class Enrollment Metrics (Bar Chart) -->
                    <div class="col-xl-6 col-lg-6 col-12 mb-2">
                        <div class="chart-box-card">
                            <div class="chart-box-header">
                                <div class="chart-box-title-group">
                                    <div class="chart-header-icon">
                                        <i data-lucide="calendar"></i>
                                    </div>
                                    <h4 class="chart-box-title">Class Enrollment Metrics</h4>
                                </div>
                                <a href="{{ route('admin.classes.index') }}" class="chart-view-details-link">
                                    View Details <i data-lucide="arrow-right"></i>
                                </a>
                            </div>
                            <div id="classStatsChart" style="min-height: 270px;"></div>
                        </div>
                    </div>

                    <!-- Right: Subject Question Bank Spread (Donut Chart & Custom Legend) -->
                    @php
                        $palette = ['#0066ff', '#f59e0b', '#10b981', '#06b6d4', '#8b5cf6', '#ef4444'];
                        $totalSubjectQuestions = $subjectStats->sum('questions_count');
                    @endphp
                    <div class="col-xl-6 col-lg-6 col-12 mb-2">
                        <div class="chart-box-card">
                            <div class="chart-box-header">
                                <div class="chart-box-title-group">
                                    <div class="chart-header-icon">
                                        <i data-lucide="pie-chart"></i>
                                    </div>
                                    <h4 class="chart-box-title">Subject Question Bank Spread</h4>
                                </div>
                                <a href="{{ route('admin.questions.index') }}" class="chart-view-details-link">
                                    View Details <i data-lucide="arrow-right"></i>
                                </a>
                            </div>

                            <div class="donut-chart-container row align-items-center">
                                <!-- Donut Chart -->
                                <div class="col-sm-5 col-12 text-center pr-0">
                                    <div id="subjectStatsChart"></div>
                                </div>

                                <!-- Custom Side Legend List (Matches Reference Image) -->
                                <div class="col-sm-7 col-12 pl-sm-2">
                                    <div class="subject-legend-list">
                                        @forelse($subjectStats as $index => $subject)
                                            @php
                                                $color = $palette[$index % count($palette)];
                                                $cnt = $subject->questions_count;
                                                $pct = $totalSubjectQuestions > 0 ? round(($cnt / $totalSubjectQuestions) * 100) : 0;
                                            @endphp
                                            <div class="subject-legend-item">
                                                <div class="legend-left">
                                                    <span class="legend-color-dot" style="background-color: {{ $color }};"></span>
                                                    <span class="legend-name" title="{{ $subject->title }}">{{ $subject->title }}</span>
                                                </div>
                                                <div class="legend-right">
                                                    <span class="legend-count">{{ $cnt }}</span>
                                                    <span class="legend-percent">{{ $pct }}%</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="font-small-2 py-1 text-center">No subject metrics available</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- =========================================================================
                 SECTION 3: SYSTEM QUICK ACTIONS (4x2 Grid Matching Reference Image)
                 ========================================================================= -->
            <section id="admin-actions-grid">
                <div class="quick-actions-header">
                    <div class="quick-actions-title-wrap">
                        <span class="quick-actions-lightning">⚡</span>
                        <h3 class="quick-actions-main-title">System Quick Actions</h3>
                    </div>
                    <span class="quick-actions-subtext">Quick access to common tasks and features</span>
                </div>

                <div class="row align-items-stretch">
                    <!-- 1. Manage Students -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 quick-action-card-col">
                        <div class="quick-action-card qa-students">
                            <div class="quick-action-top">
                                <div class="quick-action-icon">
                                    <i data-lucide="users"></i>
                                </div>
                                <div class="quick-action-info">
                                    <h5 class="quick-action-title">Manage Students</h5>
                                    <p class="quick-action-desc">View records, edit details, assign cohorts, and track learning progress.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.students.index') }}" class="quick-action-btn">
                                <span>Manage Students</span>
                                <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 2. Manage Classes -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 quick-action-card-col">
                        <div class="quick-action-card qa-classes">
                            <div class="quick-action-top">
                                <div class="quick-action-icon">
                                    <i data-lucide="presentation"></i>
                                </div>
                                <div class="quick-action-info">
                                    <h5 class="quick-action-title">Manage Classes</h5>
                                    <p class="quick-action-desc">Configure class rooms, enroll students, and link academic groups.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.classes.index') }}" class="quick-action-btn">
                                <span>Manage Classes</span>
                                <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 3. Questions Bank -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 quick-action-card-col">
                        <div class="quick-action-card qa-questions">
                            <div class="quick-action-top">
                                <div class="quick-action-icon">
                                    <i data-lucide="database"></i>
                                </div>
                                <div class="quick-action-info">
                                    <h5 class="quick-action-title">Questions Bank</h5>
                                    <p class="quick-action-desc">Create, import, organize, and categorize assessment questions.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.questions.index') }}" class="quick-action-btn">
                                <span>Questions Bank</span>
                                <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 4. Manage Announcements -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 quick-action-card-col">
                        <div class="quick-action-card qa-announcements">
                            <div class="quick-action-top">
                                <div class="quick-action-icon">
                                    <i data-lucide="megaphone"></i>
                                </div>
                                <div class="quick-action-info">
                                    <h5 class="quick-action-title">Manage Announcements</h5>
                                    <p class="quick-action-desc">Broadcast messages, notifications, and center news to members.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.announcements.index') }}" class="quick-action-btn">
                                <span>Announcements</span>
                                <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 5. Users Directory -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 quick-action-card-col">
                        <div class="quick-action-card qa-users">
                            <div class="quick-action-top">
                                <div class="quick-action-icon">
                                    <i data-lucide="user-plus"></i>
                                </div>
                                <div class="quick-action-info">
                                    <h5 class="quick-action-title">Users Directory</h5>
                                    <p class="quick-action-desc">Oversee users across all roles: admins, tutors, students, parents.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="quick-action-btn">
                                <span>Users Directory</span>
                                <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 6. Create Exam Paper -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 quick-action-card-col">
                        <div class="quick-action-card qa-papers-create">
                            <div class="quick-action-top">
                                <div class="quick-action-icon">
                                    <i data-lucide="file-text"></i>
                                </div>
                                <div class="quick-action-info">
                                    <h5 class="quick-action-title">Create Exam Paper</h5>
                                    <p class="quick-action-desc">Design and customize mock test sheets, duration, styles, and instructions.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.papers.create') }}" class="quick-action-btn">
                                <span>Create Paper</span>
                                <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 7. Papers Directory -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 quick-action-card-col">
                        <div class="quick-action-card qa-papers-dir">
                            <div class="quick-action-top">
                                <div class="quick-action-icon">
                                    <i data-lucide="library"></i>
                                </div>
                                <div class="quick-action-info">
                                    <h5 class="quick-action-title">Papers Directory</h5>
                                    <p class="quick-action-desc">List, assign, duplicate, edit, or delete existing evaluation papers.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.papers.index') }}" class="quick-action-btn">
                                <span>Papers Directory</span>
                                <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 8. System Settings -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 quick-action-card-col">
                        <div class="quick-action-card qa-settings">
                            <div class="quick-action-top">
                                <div class="quick-action-icon">
                                    <i data-lucide="settings"></i>
                                </div>
                                <div class="quick-action-info">
                                    <h5 class="quick-action-title">System Settings</h5>
                                    <p class="quick-action-desc">Configure default paths, notification preferences, and storage details.</p>
                                </div>
                            </div>
                            <a href="{{ route('settings.index') }}" class="quick-action-btn">
                                <span>Configure System</span>
                                <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Re-initialize Lucide Icons if available
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const isDark = document.body.classList.contains('dark-layout');
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.06)' : '#e2e8f0';

    // -------------------------------------------------------------------------
    // 1. Class Enrollment Metrics (Column Bar Chart)
    // -------------------------------------------------------------------------
    const classLabels = {!! json_encode($classStats->pluck('name')) !!};
    const classCounts = {!! json_encode($classStats->pluck('students_count')) !!};

    const classChartOptions = {
        chart: {
            type: 'bar',
            height: 270,
            toolbar: { show: false },
            parentHeightOffset: 0
        },
        series: [{
            name: 'Enrolled Students',
            data: classCounts.length ? classCounts : [1, 0, 0, 0, 0]
        }],
        xaxis: {
            categories: classLabels.length ? classLabels : ['Class 5A', 'Class 3A', 'Class 4A', 'Class 6A', 'Class 7A'],
            labels: {
                style: {
                    colors: labelColor,
                    fontSize: '11px',
                    fontWeight: 600,
                    fontFamily: 'Montserrat, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                }
            },
            axisBorder: { show: true, color: gridColor },
            axisTicks: { show: true, color: gridColor }
        },
        yaxis: {
            min: 0,
            max: function(maxVal) {
                return maxVal > 1 ? Math.ceil(maxVal * 1.2) : 1.0;
            },
            tickAmount: 5,
            labels: {
                formatter: function (val) {
                    return val.toFixed(1);
                },
                style: {
                    colors: labelColor,
                    fontSize: '11px',
                    fontFamily: 'Montserrat, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                }
            }
        },
        grid: {
            borderColor: gridColor,
            strokeDashArray: 0,
            yaxis: { lines: { show: true } },
            xaxis: { lines: { show: false } },
            padding: { top: 0, right: 10, bottom: 0, left: 10 }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '24%',
                distributed: false
            }
        },
        colors: ['#3b82f6'],
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: {
                formatter: function(val) {
                    return val + ' Student' + (val === 1 ? '' : 's');
                }
            }
        }
    };

    const classChartEl = document.querySelector("#classStatsChart");
    if (classChartEl) {
        const classChart = new ApexCharts(classChartEl, classChartOptions);
        classChart.render();
    }

    // -------------------------------------------------------------------------
    // 2. Subject Question Bank Spread (Donut Chart)
    // -------------------------------------------------------------------------
    const subjectLabels = {!! json_encode($subjectStats->pluck('title')) !!};
    const subjectCounts = {!! json_encode($subjectStats->pluck('questions_count')) !!};
    const totalSubjectQuestions = {{ $totalSubjectQuestions }};

    const donutColors = ['#0066ff', '#f59e0b', '#10b981', '#06b6d4', '#8b5cf6', '#ef4444'];

    const subjectChartOptions = {
        chart: {
            type: 'donut',
            height: 240,
            parentHeightOffset: 0
        },
        series: subjectCounts.length && totalSubjectQuestions > 0 ? subjectCounts : [3, 1, 1],
        labels: subjectLabels.length ? subjectLabels : ['Maths & Number Reasoning', 'English', 'Verbal Reasoning'],
        colors: donutColors,
        stroke: {
            width: 2,
            colors: [isDark ? '#1e2440' : '#ffffff']
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '12px',
                            fontWeight: 600,
                            color: labelColor,
                            offsetY: -6,
                            fontFamily: 'Montserrat, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                        },
                        value: {
                            show: true,
                            fontSize: '24px',
                            fontWeight: 800,
                            color: isDark ? '#f8fafc' : '#0f172a',
                            offsetY: 6,
                            fontFamily: 'Montserrat, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                            formatter: function (val) {
                                return val;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total Questions',
                            color: labelColor,
                            fontSize: '11px',
                            fontFamily: 'Montserrat, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                            formatter: function (w) {
                                return {{ $stats['questions'] }};
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: {
                formatter: function (val) {
                    return val + ' Question' + (val === 1 ? '' : 's');
                }
            }
        }
    };

    const subjectChartEl = document.querySelector("#subjectStatsChart");
    if (subjectChartEl) {
        const subjectChart = new ApexCharts(subjectChartEl, subjectChartOptions);
        subjectChart.render();
    }
});
</script>
@endpush
