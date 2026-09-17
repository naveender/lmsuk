@extends('layouts.app')

@section('title', 'Topic Focus Areas - Aspire Learners')

@push('styles')
<style>
    /* Focus Areas Hero Banner */
    .focus-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 45%, #312e81 100%);
        border-radius: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .focus-hero-card::before {
        content: '';
        position: absolute;
        top: -60%;
        right: -15%;
        width: 380px;
        height: 380px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .focus-hero-card::after {
        content: '';
        position: absolute;
        bottom: -50%;
        left: 10%;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(236, 72, 153, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .focus-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
    }

    .focus-hero-title {
        font-size: 2.15rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.02em;
        line-height: 1.25;
        margin-bottom: 0.4rem;
    }

    .focus-hero-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.95rem;
        line-height: 1.55;
        max-width: 580px;
    }

    .hero-stats-matrix {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.85rem;
    }

    .hero-stat-box {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        padding: 0.85rem 0.9rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .hero-stat-box:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .hero-stat-num {
        font-size: 1.45rem;
        font-weight: 800;
        color: #ffffff !important;
        display: block;
        line-height: 1.2;
    }

    .hero-stat-lbl {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.82);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
        margin-top: 0.2rem;
        display: block;
    }

    /* Controls & Threshold Card */
    .focus-controls-card {
        background: #ffffff;
        border-radius: 1.25rem;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
        border: 1px solid #edf2f7;
    }

    .dark-layout .focus-controls-card {
        background: #1e2440;
        border-color: #2d3748;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.25);
    }

    .custom-pill-radio {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 9999px;
        padding: 4px;
        border: 1px solid #e2e8f0;
    }

    .dark-layout .custom-pill-radio {
        background: #181d36;
        border-color: #2d3748;
    }

    .custom-pill-radio label {
        margin: 0;
        padding: 0.4rem 0.9rem;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .dark-layout .custom-pill-radio label {
        color: #94a3b8;
    }

    .custom-pill-radio input[type="radio"] {
        display: none;
    }

    .custom-pill-radio input[type="radio"]:checked + label {
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
    }

    .dark-layout .custom-pill-radio input[type="radio"]:checked + label {
        background: #7367f0;
        box-shadow: 0 2px 8px rgba(115, 103, 240, 0.4);
    }

    .threshold-number-input {
        width: 70px;
        text-align: center;
        border-radius: 0.75rem;
        border: 1.5px solid #cbd5e1;
        padding: 0.45rem 0.5rem;
        font-weight: 800;
        font-size: 1rem;
        color: #1e293b;
        background: #ffffff;
        transition: border-color 0.2s;
    }

    .dark-layout .threshold-number-input {
        background: #181d36;
        border-color: #2d3748;
        color: #f1f5f9;
    }

    .threshold-number-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .btn-recalculate-glow {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: #ffffff !important;
        border-radius: 0.85rem;
        padding: 0.55rem 1.35rem;
        font-weight: 700;
        font-size: 0.9rem;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        transition: all 0.3s ease;
    }

    .btn-recalculate-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
        filter: brightness(1.08);
    }

    .search-topic-input {
        padding-left: 2.75rem;
        padding-right: 2.25rem;
        height: 42px;
        border-radius: 0.85rem;
        border: 1.5px solid #e2e8f0;
        font-size: 0.9rem;
        background-color: #f8fafc;
        transition: all 0.25s ease;
    }

    .search-topic-input:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .dark-layout .search-topic-input {
        background-color: #181d36;
        border-color: #2d3748;
        color: #e2e8f0;
    }

    /* Subject Accordion Card */
    .fa-subject-card {
        border-radius: 1.25rem;
        border: 1px solid #edf2f7;
        background: #ffffff;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .dark-layout .fa-subject-card {
        background: #1e2440;
        border-color: #2d3748;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    }

    .fa-subject-header {
        padding: 1.1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        background: #ffffff;
        border-bottom: 1px solid #edf2f7;
        transition: background 0.2s ease;
    }

    .dark-layout .fa-subject-header {
        background: #1e2440;
        border-bottom-color: #2d3748;
    }

    .fa-subject-header:hover {
        background: #f8fafc;
    }

    .dark-layout .fa-subject-header:hover {
        background: #232a48;
    }

    /* Subject Image Avatar */
    .subject-mini-avatar {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--subj-light, rgba(79, 70, 229, 0.1));
        border: 1.5px solid var(--subj-border, rgba(79, 70, 229, 0.2));
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        flex-shrink: 0;
        overflow: hidden;
    }

    .subject-mini-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 6px;
    }

    .subject-mini-icon {
        font-size: 1.35rem;
        color: var(--subj-color, #4f46e5);
    }

    .fa-chevron-icon {
        font-size: 1.25rem;
        color: #94a3b8;
        transition: transform 0.3s ease;
    }

    .fa-subject-header.collapsed .fa-chevron-icon {
        transform: rotate(-90deg);
    }

    /* Table Styling */
    .fa-modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.92rem;
        margin-bottom: 0;
    }

    .fa-modern-table thead tr {
        background: #f8fafc;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .dark-layout .fa-modern-table thead tr {
        background: #181d36;
        border-bottom-color: #2d3748;
    }

    .fa-modern-table thead th {
        padding: 0.9rem 1.1rem;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        text-align: center;
        border: none;
        white-space: nowrap;
    }

    .dark-layout .fa-modern-table thead th {
        color: #94a3b8;
    }

    .fa-modern-table thead th:first-child {
        text-align: left;
    }

    .fa-modern-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .dark-layout .fa-modern-table tbody tr {
        border-bottom-color: #2d3748;
    }

    .fa-modern-table tbody tr:hover {
        background: #f8fafc;
    }

    .dark-layout .fa-modern-table tbody tr:hover {
        background: #232a48;
    }

    .fa-modern-table tbody td {
        padding: 1rem 1.1rem;
        text-align: center;
        vertical-align: middle;
        color: #334155;
    }

    .dark-layout .fa-modern-table tbody td {
        color: #e2e8f0;
    }

    .fa-modern-table tbody td:first-child {
        text-align: left;
    }

    /* Score Badges */
    .score-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.28rem 0.65rem;
        border-radius: 9999px;
        font-weight: 800;
        font-size: 0.82rem;
        min-width: 58px;
        justify-content: center;
        gap: 4px;
    }

    .score-badge-danger {
        background: rgba(244, 63, 94, 0.12);
        color: #e11d48;
        border: 1px solid rgba(244, 63, 94, 0.25);
    }

    .score-badge-warning {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.25);
    }

    .score-badge-success {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .dark-layout .score-badge-danger {
        background: rgba(244, 63, 94, 0.2);
        color: #fb7185;
    }

    .dark-layout .score-badge-warning {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
    }

    .dark-layout .score-badge-success {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    .btn-practice-topic {
        background: #f1f5f9;
        color: #4f46e5 !important;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.4rem 0.85rem;
        border-radius: 0.65rem;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .dark-layout .btn-practice-topic {
        background: #181d36;
        border-color: #2d3748;
        color: #9b8cfc !important;
    }

    .btn-practice-topic:hover {
        background: #4f46e5;
        color: #ffffff !important;
        border-color: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
    }

    /* Methodology Guide Card */
    .methodology-card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid #edf2f7;
        box-shadow: 0 6px 22px rgba(0, 0, 0, 0.04);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .dark-layout .methodology-card {
        background: #1e2440;
        border-color: #2d3748;
        box-shadow: 0 6px 22px rgba(0, 0, 0, 0.25);
    }

    .guide-point-box {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #f8fafc;
        border-radius: 1rem;
        padding: 1rem;
        height: 100%;
        border: 1px solid #f1f5f9;
    }

    .dark-layout .guide-point-box {
        background: #181d36;
        border-color: #2d3748;
    }

    .guide-icon-wrap {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    /* Celebration Card */
    .celebration-card {
        background: #ffffff;
        border-radius: 1.5rem;
        border: 1.5px dashed #10b981;
        padding: 3.5rem 1.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.08);
    }

    .dark-layout .celebration-card {
        background: #1e2440;
        border-color: #059669;
    }

    .celebration-icon {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin: 0 auto 1.25rem;
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0.06);
    }

    /* Threshold Quick Presets */
    .threshold-preset-btn {
        padding: 0.28rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        line-height: 1.2;
    }

    .threshold-preset-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
        border-color: #94a3b8;
    }

    .threshold-preset-btn.active {
        background: #4f46e5;
        border-color: #4f46e5;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
    }

    .dark-layout .threshold-preset-btn {
        background: #181d36;
        border-color: #334155;
        color: #94a3b8;
    }

    .dark-layout .threshold-preset-btn:hover {
        background: #232a48;
        color: #f1f5f9;
        border-color: #475569;
    }

    .dark-layout .threshold-preset-btn.active {
        background: #7367f0;
        border-color: #7367f0;
        color: #ffffff !important;
    }

    /* Enhanced Dark Layout Contrast */
    .dark-layout .focus-controls-card label,
    .dark-layout .focus-controls-card span,
    .dark-layout .fa-subject-card h4,
    .dark-layout .fa-subject-card .text-dark,
    .dark-layout .methodology-card h5,
    .dark-layout .methodology-card strong,
    .dark-layout .celebration-card h3,
    .dark-layout .celebration-card h4 {
        color: #f1f5f9 !important;
    }
</style>
@endpush

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body">
            
            <!-- Hero Header Banner -->
            <div class="focus-hero-card mb-2 mb-md-3">
                <div class="p-2 p-md-4">
                    <div class="row align-items-center">
                        <div class="col-lg-7 col-md-12 mb-2 mb-lg-0">
                            <div class="focus-badge mb-1">
                                <i class="feather icon-target mr-50"></i>
                                <span>DIAGNOSTIC PERFORMANCE REPORT</span>
                            </div>
                            <h1 class="focus-hero-title">Target Focus Areas</h1>
                            <p class="focus-hero-subtitle mb-0">
                                Pinpoint topics scoring below your target threshold so you can focus revision where it matters most, eliminate weak spots, and boost your 11+ score.
                            </p>
                        </div>

                        <div class="col-lg-5 col-md-12">
                            <div class="hero-stats-matrix">
                                <div class="hero-stat-box">
                                    <span class="hero-stat-num text-primary">{{ $totalFocusTopics ?? collect($subjectData)->sum(fn($s) => count($s['topics'])) }}</span>
                                    <span class="hero-stat-lbl">Focus Topics</span>
                                </div>
                                <div class="hero-stat-box">
                                    <span class="hero-stat-num text-danger">{{ $criticalCount ?? 0 }}</span>
                                    <span class="hero-stat-lbl">Critical (&lt;50%)</span>
                                </div>
                                <div class="hero-stat-box">
                                    <span class="hero-stat-num text-warning">{{ $moderateCount ?? 0 }}</span>
                                    <span class="hero-stat-lbl">Moderate</span>
                                </div>
                                <div class="hero-stat-box">
                                    <span class="hero-stat-num text-info">{{ $threshold }}%</span>
                                    <span class="hero-stat-lbl">Current Target</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Threshold Customizer & Search -->
            <div class="focus-controls-card p-2 p-md-3 mb-3">
                <form id="focusFilterForm" method="GET" action="{{ route('student.focusareas') }}">
                    <div class="row align-items-center">
                        <!-- Metric Basis Radios -->
                        <div class="col-xl-6 col-lg-6 col-12 mb-1 mb-xl-0">
                            <label class="font-weight-bold font-small-3 text-dark d-block mb-50">
                                <i class="feather icon-sliders mr-25"></i> Benchmark Metric:
                            </label>
                            <div class="custom-pill-radio flex-wrap">
                                <input type="radio" name="average_type" id="type_avg" value="average" 
                                       {{ ($averageType ?? 'average') === 'average' ? 'checked' : '' }} onchange="this.form.submit()">
                                <label for="type_avg"><i class="feather icon-bar-chart mr-25"></i> Overall Average</label>

                                <input type="radio" name="average_type" id="type_first" value="first" 
                                       {{ ($averageType ?? '') === 'first' ? 'checked' : '' }} onchange="this.form.submit()">
                                <label for="type_first"><i class="feather icon-play mr-25"></i> First Attempt</label>

                                <input type="radio" name="average_type" id="type_last" value="last" 
                                       {{ ($averageType ?? '') === 'last' ? 'checked' : '' }} onchange="this.form.submit()">
                                <label for="type_last"><i class="feather icon-clock mr-25"></i> Latest Attempt</label>
                            </div>
                        </div>

                        <!-- Threshold Presets & Recalculate -->
                        <div class="col-xl-6 col-lg-6 col-12 d-flex align-items-center justify-content-xl-end flex-wrap gap-2">
                            <div class="d-flex align-items-center flex-wrap mr-1 my-50">
                                <span class="font-weight-bold font-small-3 mr-50 text-dark">Below Target:</span>
                                <div class="d-inline-flex gap-1 mr-75 my-25">
                                    @foreach([70, 75, 80, 85, 90] as $preset)
                                        <button type="button" 
                                                class="threshold-preset-btn {{ ($threshold ?? 80) == $preset ? 'active' : '' }}" 
                                                onclick="applyPreset({{ $preset }})">
                                            {{ $preset }}%
                                        </button>
                                    @endforeach
                                </div>
                                <div class="d-inline-flex align-items-center">
                                    <input type="number" id="thresholdInput" name="threshold" class="threshold-number-input mr-25" min="1" max="100" value="{{ $threshold ?? 80 }}">
                                    <span class="font-weight-bold font-small-3 mr-75">%</span>
                                </div>
                            </div>
                            <button type="submit" class="btn-recalculate-glow">
                                <i class="feather icon-refresh-cw mr-25"></i> Apply
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Real-time Filter Input & View Controls -->
                @if(!empty($subjectData))
                    <div class="mt-2 pt-2 border-top">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="position-relative flex-grow-1 mr-1" style="min-width: 250px;">
                                <i class="feather icon-search position-absolute" style="left: 1.1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.05rem;"></i>
                                <input type="text" id="topicSearchInput" class="form-control search-topic-input" placeholder="Search focus topics by name across all subjects..." autocomplete="off">
                                <button type="button" id="clearSearchBtn" class="position-absolute d-none" style="right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer;">
                                    <i class="feather icon-x"></i>
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" id="toggleAllAccordionBtn">
                                    <i class="feather icon-minimize-2 mr-25"></i> Collapse All
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Subject Accordion Sections -->
            @if(empty($subjectData))
                <div class="celebration-card my-3">
                    <div class="celebration-icon">
                        <i class="feather icon-award"></i>
                    </div>
                    <h3 class="font-weight-bold text-dark mb-1">Exceptional Mastery! No Focus Areas Found</h3>
                    <p class="text-muted max-w-500 mx-auto mb-2 font-medium-1">
                        All your attempted topics currently have scores at or above your <strong>{{ $threshold }}%</strong> benchmark. Keep maintaining this excellent momentum!
                    </p>
                    <a href="{{ route('student.focusareas', ['threshold' => 85, 'average_type' => $averageType]) }}" class="btn btn-outline-primary px-2 font-weight-bold">
                        <i class="feather icon-trending-up mr-50"></i> Challenge Yourself at 85% Benchmark
                    </a>
                </div>
            @else
                <div id="focusSubjectsContainer">
                    @foreach($subjectData as $index => $data)
                        @php
                            $subject = $data['subject'];
                            $collapseId = 'subject-collapse-' . $loop->index;
                            
                            // Map subject dynamic styles
                            $titleLower = strtolower($subject->title ?? '');
                            if (strpos($titleLower, 'math') !== false) {
                                $subjColor = '#4f46e5';
                                $subjLight = 'rgba(79, 70, 229, 0.08)';
                                $subjBorder = 'rgba(79, 70, 229, 0.2)';
                                $subjIcon = 'feather icon-award';
                            } elseif (strpos($titleLower, 'eng') !== false) {
                                $subjColor = '#ec4899';
                                $subjLight = 'rgba(236, 72, 153, 0.08)';
                                $subjBorder = 'rgba(236, 72, 153, 0.2)';
                                $subjIcon = 'feather icon-book-open';
                            } elseif (strpos($titleLower, 'verbal') !== false && strpos($titleLower, 'non') === false) {
                                $subjColor = '#10b981';
                                $subjLight = 'rgba(16, 185, 129, 0.08)';
                                $subjBorder = 'rgba(16, 185, 129, 0.2)';
                                $subjIcon = 'feather icon-cpu';
                            } elseif (strpos($titleLower, 'non') !== false || strpos($titleLower, 'nvr') !== false) {
                                $subjColor = '#8b5cf6';
                                $subjLight = 'rgba(139, 92, 246, 0.08)';
                                $subjBorder = 'rgba(139, 92, 246, 0.2)';
                                $subjIcon = 'feather icon-help-circle';
                            } elseif (strpos($titleLower, 'sci') !== false) {
                                $subjColor = '#f59e0b';
                                $subjLight = 'rgba(245, 158, 11, 0.08)';
                                $subjBorder = 'rgba(245, 158, 11, 0.2)';
                                $subjIcon = 'feather icon-zap';
                            } else {
                                $subjColor = '#0284c7';
                                $subjLight = 'rgba(2, 132, 199, 0.08)';
                                $subjBorder = 'rgba(2, 132, 199, 0.2)';
                                $subjIcon = 'feather icon-layers';
                            }
                        @endphp

                        <div class="fa-subject-card" 
                             style="--subj-color: {{ $subjColor }}; --subj-light: {{ $subjLight }}; --subj-border: {{ $subjBorder }};">
                            
                            <!-- Card Accordion Header -->
                            <div class="fa-subject-header"
                                 data-toggle="collapse"
                                 data-target="#{{ $collapseId }}"
                                 aria-expanded="true"
                                 aria-controls="{{ $collapseId }}"
                                 id="header-{{ $collapseId }}">
                                
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Dynamic Subject Image / Icon Frame -->
                                    <div class="subject-mini-avatar mr-1">
                                        @if(!empty($subject->image))
                                            <img src="{{ asset('storage/' . $subject->image) }}" 
                                                 alt="{{ $subject->title }}" 
                                                 class="subject-mini-img"
                                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                            <div class="subject-mini-icon d-none">
                                                <i class="{{ $subjIcon }}"></i>
                                            </div>
                                        @else
                                            <div class="subject-mini-icon">
                                                <i class="{{ $subjIcon }}"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="font-weight-bold text-dark mb-25">{{ $subject->title }}</h4>
                                        <span class="badge badge-pill badge-light-danger font-small-1 font-weight-bold text-uppercase">
                                            {{ count($data['topics']) }} {{ \Illuminate\Support\Str::plural('Focus Topic', count($data['topics'])) }} Identified
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <i class="feather icon-chevron-down fa-chevron-icon ml-1"></i>
                                </div>
                            </div>

                            <!-- Collapsible Table Panel -->
                            <div id="{{ $collapseId }}" class="collapse show">
                                <div class="table-responsive">
                                    <table class="fa-modern-table">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 240px;">Topic / Focus Area</th>
                                                <th>Tests<br>Available</th>
                                                <th>Tests<br>Attempted</th>
                                                <th>Total<br>Attempts</th>
                                                <th>Overall<br>Average</th>
                                                <th>First Attempt<br>Average</th>
                                                <th>Latest Attempt<br>Average</th>
                                                <th style="min-width: 140px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['topics'] as $row)
                                                <tr class="topic-data-row" data-topic-name="{{ strtolower($row['name']) }}">
                                                    <td>
                                                        <div class="font-weight-bold text-dark d-flex align-items-center">
                                                            <i class="feather icon-corner-down-right text-muted mr-50"></i>
                                                            <span>{{ $row['name'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td><span class="font-weight-bold text-primary">{{ $row['available'] }}</span></td>
                                                    <td><span class="font-weight-bold text-info">{{ $row['attempted'] }}</span></td>
                                                    <td><span class="font-weight-bold text-secondary">{{ $row['total'] }}</span></td>
                                                    <td>
                                                        @if($row['average'] !== null)
                                                            <span class="score-badge {{ $row['average'] < 50 ? 'score-badge-danger' : ($row['average'] < 75 ? 'score-badge-warning' : 'score-badge-success') }}">
                                                                {{ $row['average'] }}%
                                                            </span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($row['first_average'] !== null)
                                                            <span class="score-badge {{ $row['first_average'] < 50 ? 'score-badge-danger' : ($row['first_average'] < 75 ? 'score-badge-warning' : 'score-badge-success') }}">
                                                                {{ $row['first_average'] }}%
                                                            </span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($row['last_average'] !== null)
                                                            <span class="score-badge {{ $row['last_average'] < 50 ? 'score-badge-danger' : ($row['last_average'] < 75 ? 'score-badge-warning' : 'score-badge-success') }}">
                                                                {{ $row['last_average'] }}%
                                                            </span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('student.topics.subtopics', $row['id']) }}" 
                                                           class="btn-practice-topic" 
                                                           title="Practice tests under {{ $row['name'] }}">
                                                            <span>Practice Tests</span>
                                                            <i class="feather icon-arrow-right"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- No Results Search Placeholder -->
                <div class="celebration-card my-3 d-none" id="noTopicsSearchBox">
                    <div class="celebration-icon" style="background: rgba(148, 163, 184, 0.15); color: #64748b;">
                        <i class="feather icon-search"></i>
                    </div>
                    <h4 class="font-weight-bold text-dark mb-50">No focus topics match your search</h4>
                    <p class="text-muted mb-1 font-small-3">Try checking spelling or reset your keyword filter.</p>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="resetSearchBtn">
                        Clear Search Filter
                    </button>
                </div>
            @endif

            <!-- Diagnostic Methodology Guide -->
            <div class="methodology-card mt-3">
                <h5 class="font-weight-bold text-dark mb-1 d-flex align-items-center">
                    <i class="feather icon-info text-primary mr-50"></i> Understanding Your Focus Areas
                </h5>
                <p class="text-muted font-small-3 mb-2">
                    Focus areas are dynamically calculated using full completed test papers. Sweeps and abandoned attempts are excluded to ensure statistical accuracy.
                </p>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-1 mb-md-0">
                        <div class="guide-point-box">
                            <div class="guide-icon-wrap" style="background: #e0e7ff; color: #4338ca;">
                                <i class="feather icon-percent"></i>
                            </div>
                            <div>
                                <strong class="text-dark d-block font-small-3 mb-25">80% Target Benchmark</strong>
                                <small class="text-muted">Recommended standard for 11+ grammar and selective school entry.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1 mb-md-0">
                        <div class="guide-point-box">
                            <div class="guide-icon-wrap" style="background: #fef3c7; color: #b45309;">
                                <i class="feather icon-git-commit"></i>
                            </div>
                            <div>
                                <strong class="text-dark d-block font-small-3 mb-25">Baseline vs. Current</strong>
                                <small class="text-muted">Compare first attempt to latest attempt to see retention and recovery.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1 mb-md-0">
                        <div class="guide-point-box">
                            <div class="guide-icon-wrap" style="background: #dcfce7; color: #15803d;">
                                <i class="feather icon-shield"></i>
                            </div>
                            <div>
                                <strong class="text-dark d-block font-small-3 mb-25">Verified Full Attempts</strong>
                                <small class="text-muted">Only complete exam sessions are included for realistic mastery stats.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="guide-point-box">
                            <div class="guide-icon-wrap" style="background: #fee2e2; color: #b91c1c;">
                                <i class="feather icon-crosshair"></i>
                            </div>
                            <div>
                                <strong class="text-dark d-block font-small-3 mb-25">Targeted Practice</strong>
                                <small class="text-muted">Direct practice on your lowest scoring topics gives the fastest score gains.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Accordion Chevron Toggle Animations
    document.querySelectorAll('.fa-subject-header').forEach(function (header) {
        var targetId = header.getAttribute('data-target');
        var panel = document.querySelector(targetId);

        if (!panel) return;

        $(panel).on('show.bs.collapse', function () {
            header.classList.remove('collapsed');
        });
        $(panel).on('hide.bs.collapse', function () {
            header.classList.add('collapsed');
        });
    });

    // Real-time Topic Search Filter
    var searchInput = document.getElementById('topicSearchInput');
    var clearBtn = document.getElementById('clearSearchBtn');
    var resetSearchBtn = document.getElementById('resetSearchBtn');
    var noSearchBox = document.getElementById('noTopicsSearchBox');

    function filterTopics() {
        var query = (searchInput ? searchInput.value : '').trim().toLowerCase();
        var rows = document.querySelectorAll('.topic-data-row');
        var visibleCount = 0;

        if (clearBtn) {
            if (query.length > 0) clearBtn.classList.remove('d-none');
            else clearBtn.classList.add('d-none');
        }

        rows.forEach(function (row) {
            var topicName = row.getAttribute('data-topic-name') || '';
            if (!query || topicName.indexOf(query) !== -1) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Toggle visibility of empty subject cards if all rows inside are hidden
        document.querySelectorAll('.fa-subject-card').forEach(function (card) {
            var visibleRowsInCard = card.querySelectorAll('.topic-data-row:not([style*="display: none"])').length;
            if (visibleRowsInCard === 0 && query.length > 0) {
                card.style.display = 'none';
            } else {
                card.style.display = '';
            }
        });

        if (noSearchBox) {
            if (visibleCount === 0 && rows.length > 0) {
                noSearchBox.classList.remove('d-none');
            } else {
                noSearchBox.classList.add('d-none');
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterTopics);
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            filterTopics();
            searchInput.focus();
        });
    }

    if (resetSearchBtn) {
        resetSearchBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            filterTopics();
        });
    }

    // Toggle All Accordion Cards
    var toggleAllBtn = document.getElementById('toggleAllAccordionBtn');
    var allExpanded = true;
    if (toggleAllBtn) {
        toggleAllBtn.addEventListener('click', function() {
            allExpanded = !allExpanded;
            var panels = $('.fa-subject-card .collapse');
            var headers = document.querySelectorAll('.fa-subject-header');
            if (allExpanded) {
                panels.collapse('show');
                headers.forEach(function(h) { h.classList.remove('collapsed'); });
                toggleAllBtn.innerHTML = '<i class="feather icon-minimize-2 mr-25"></i> Collapse All';
            } else {
                panels.collapse('hide');
                headers.forEach(function(h) { h.classList.add('collapsed'); });
                toggleAllBtn.innerHTML = '<i class="feather icon-maximize-2 mr-25"></i> Expand All';
            }
        });
    }
});

function applyPreset(val) {
    var input = document.getElementById('thresholdInput');
    var form = document.getElementById('focusFilterForm');
    if (input && form) {
        input.value = val;
        document.querySelectorAll('.threshold-preset-btn').forEach(function(btn) {
            btn.classList.remove('active');
            if (btn.innerText.trim() === val + '%') {
                btn.classList.add('active');
            }
        });
        form.submit();
    }
}
window.applyPreset = applyPreset;
</script>
@endpush
