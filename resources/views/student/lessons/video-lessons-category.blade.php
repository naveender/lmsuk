@extends('layouts.app')

@section('title', 'Video Lessons - Aspire Learners')

@push('styles')
<style>
    /* Video Lessons Hero Card */
    .lessons-hero-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 45%, #4338ca 100%);
        border-radius: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(49, 46, 129, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .lessons-hero-card::before {
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

    .lessons-hero-card::after {
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

    .lessons-badge {
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

    .lessons-hero-title {
        font-size: 2.15rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.02em;
        line-height: 1.25;
        margin-bottom: 0.4rem;
    }

    .lessons-hero-subtitle {
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

    /* Controls & Filter Card */
    .lessons-controls-card {
        background: #ffffff;
        border-radius: 1.15rem;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
        border: 1px solid #edf2f7;
    }

    .dark-layout .lessons-controls-card {
        background: #1e2440;
        border-color: #2d3748;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.25);
    }

    .search-input-group {
        position: relative;
    }

    .search-input-group .search-icon {
        position: absolute;
        left: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.05rem;
        pointer-events: none;
        z-index: 4;
    }

    .lesson-search-control {
        padding-left: 2.8rem;
        padding-right: 2.5rem;
        height: 44px;
        border-radius: 0.85rem;
        border: 1.5px solid #e2e8f0;
        font-size: 0.92rem;
        transition: all 0.25s ease;
        background-color: #f8fafc;
    }

    .lesson-search-control:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .dark-layout .lesson-search-control {
        background-color: #181d36;
        border-color: #2d3748;
        color: #e2e8f0;
    }

    .dark-layout .lesson-search-control:focus {
        background-color: #1e2440;
        border-color: #7367f0;
    }

    .clear-search-btn {
        position: absolute;
        right: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.25rem;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
        z-index: 5;
    }

    .clear-search-btn:hover {
        color: #e11d48;
    }

    .filter-pills-wrapper {
        gap: 0.45rem;
    }

    .filter-pill {
        border: 1.5px solid transparent;
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.82rem;
        padding: 0.45rem 0.95rem;
        border-radius: 9999px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        outline: none !important;
    }

    .filter-pill:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .filter-pill.active {
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        border-color: #4f46e5;
    }

    .dark-layout .filter-pill {
        background: #181d36;
        color: #94a3b8;
        border-color: #2d3748;
    }

    .dark-layout .filter-pill:hover {
        background: #252b48;
        color: #f1f5f9;
    }

    .dark-layout .filter-pill.active {
        background: #7367f0;
        color: #ffffff;
        border-color: #7367f0;
        box-shadow: 0 4px 14px rgba(115, 103, 240, 0.4);
    }

    /* Subject Cards Grid */
    #student-lessons-section .row > [class*="col-"] {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        margin-bottom: 2rem;
    }

    .subject-card {
        border: 1px solid var(--subject-border-color, #edf2f7);
        border-radius: 1.4rem;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        margin-bottom: 0;
    }

    .subject-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--subject-gradient);
        z-index: 2;
    }

    .dark-layout .subject-card {
        background: #1e2440;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        border-color: rgba(255, 255, 255, 0.08);
    }

    .subject-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 40px var(--subject-shadow-color);
        border-color: var(--subject-color);
    }

    .dark-layout .subject-card:hover {
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.5);
    }

    /* Top bar badge */
    .subject-card-top-bar {
        display: flex;
        justify-content: flex-end;
        padding: 1rem 1.25rem 0.25rem;
        z-index: 3;
    }

    .subject-status-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.28rem 0.75rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .badge-status-completed {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .badge-status-progress {
        background: rgba(79, 70, 229, 0.12);
        color: #4f46e5;
        border: 1px solid rgba(79, 70, 229, 0.25);
    }

    .badge-status-pending {
        background: rgba(148, 163, 184, 0.14);
        color: #64748b;
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    .dark-layout .badge-status-completed {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    .dark-layout .badge-status-progress {
        background: rgba(115, 103, 240, 0.2);
        color: #9b8cfc;
    }

    .dark-layout .badge-status-pending {
        background: rgba(148, 163, 184, 0.15);
        color: #94a3b8;
    }

    .pulse-indicator {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #4f46e5;
        margin-right: 6px;
        display: inline-block;
        animation: pulseAnimation 1.8s infinite;
    }

    .dark-layout .pulse-indicator {
        background: #9b8cfc;
    }

    @keyframes pulseAnimation {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
        70% { transform: scale(1.15); box-shadow: 0 0 0 7px rgba(79, 70, 229, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }

    .subject-card-body {
        padding: 1rem 1.75rem 1.75rem;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Dynamic Subject Image Showcase */
    .subject-image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .subject-image-wrapper {
        width: 88px;
        height: 88px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--subject-gradient-light);
        border: 2px solid var(--subject-border-color, rgba(115, 103, 240, 0.18));
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.04);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .subject-dynamic-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 12px;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .subject-fallback-icon {
        font-size: 2.3rem;
        color: var(--subject-color);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .subject-card:hover .subject-image-wrapper {
        transform: translateY(-4px) scale(1.08);
        box-shadow: 0 12px 28px var(--subject-shadow-color);
        border-color: var(--subject-color);
    }

    .subject-card:hover .subject-dynamic-img {
        transform: scale(1.15) rotate(4deg);
    }

    .subject-card:hover .subject-fallback-icon {
        transform: rotate(10deg) scale(1.15);
    }

    .subject-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #1e293b;
        text-align: center;
        margin-bottom: 0.5rem;
        transition: color 0.3s ease;
        line-height: 1.35;
    }

    .dark-layout .subject-title {
        color: #f1f5f9;
    }

    .subject-card:hover .subject-title {
        color: var(--subject-color);
    }

    .subject-desc {
        font-size: 0.88rem;
        color: #64748b;
        text-align: center;
        height: 42px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 1.25rem;
        line-height: 1.5;
    }

    .dark-layout .subject-desc {
        color: #94a3b8;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.65rem;
        margin-bottom: 1.25rem;
        padding-top: 1rem;
        border-top: 1px dashed #e2e8f0;
    }

    .dark-layout .stats-grid {
        border-color: #2d3748;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.65rem 0.4rem;
        background: #f8fafc;
        border-radius: 0.85rem;
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }

    .dark-layout .stat-item {
        background: #181d36;
        border-color: #252b48;
    }

    .stat-item:hover {
        background: var(--subject-gradient-light);
        border-color: var(--subject-border-color);
    }

    .stat-value {
        font-size: 1.2rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.68rem;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
        margin-top: 0.2rem;
    }

    .subject-progress-container {
        margin-bottom: 1.5rem;
    }

    .progress-label-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .dark-layout .progress-label-wrapper {
        color: #cbd5e1;
    }

    .progress-percent-badge {
        background: var(--subject-gradient-light);
        color: var(--subject-color);
        padding: 0.15rem 0.55rem;
        border-radius: 9999px;
        font-size: 0.76rem;
        font-weight: 800;
    }

    .subject-progress-bar {
        height: 8px;
        border-radius: 9999px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .dark-layout .subject-progress-bar {
        background: #2d3748;
    }

    .subject-progress-fill {
        height: 100%;
        background: var(--subject-gradient);
        border-radius: 9999px;
        box-shadow: 0 2px 6px var(--subject-shadow-color);
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .action-btn {
        width: 100%;
        border-radius: 0.85rem;
        padding: 0.85rem 1.2rem;
        font-weight: 700;
        font-size: 0.95rem;
        background: var(--subject-gradient);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 4px 15px var(--subject-shadow-color);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .action-btn i {
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px var(--subject-shadow-color);
        filter: brightness(1.08);
    }

    .action-btn:hover i {
        transform: translateX(4px);
    }

    /* Empty State */
    .empty-state-box {
        padding: 3.5rem 1.5rem;
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1.5px dashed #cbd5e1;
        box-shadow: 0 6px 20px rgba(0,0,0,0.03);
    }

    .dark-layout .empty-state-box {
        background: #1e2440;
        border-color: #2d3748;
    }

    .empty-state-icon {
        font-size: 3.25rem;
        color: #94a3b8;
        margin-bottom: 0.75rem;
    }

    .empty-state-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .dark-layout .empty-state-title {
        color: #f1f5f9;
    }

    .empty-state-desc {
        color: #64748b;
        max-width: 500px;
        margin: 0 auto;
        font-size: 0.92rem;
        line-height: 1.5;
    }

    .dark-layout .empty-state-desc {
        color: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body">
            
            <!-- Hero Header Banner -->
            <div class="lessons-hero-card mb-2 mb-md-3">
                <div class="p-2 p-md-4">
                    <div class="row align-items-center">
                        <div class="col-lg-7 col-md-12 mb-2 mb-lg-0">
                            <div class="lessons-badge mb-1">
                                <i class="feather icon-video mr-50"></i>
                                <span>VIDEO LEARNING LIBRARY</span>
                            </div>
                            <h1 class="lessons-hero-title">Video Lessons &amp; Tutorials</h1>
                            <p class="lessons-hero-subtitle mb-0">
                                High-definition lessons tailored specifically to your learning track. Master complex topics, watch tutor walkthroughs, and track your progress in real-time.
                            </p>
                        </div>

                        <div class="col-lg-5 col-md-12">
                            <div class="hero-stats-matrix">
                                <div class="hero-stat-box">
                                    <span class="hero-stat-num text-primary">{{ $subjects->count() }}</span>
                                    <span class="hero-stat-lbl">Video Subjects</span>
                                </div>
                                <div class="hero-stat-box">
                                    <span class="hero-stat-num text-info">{{ $totalVideos ?? collect($progressBySubject)->sum('total') }}</span>
                                    <span class="hero-stat-lbl">Total Lessons</span>
                                </div>
                                <div class="hero-stat-box">
                                    <span class="hero-stat-num text-success">{{ $totalCompletedVideos ?? collect($progressBySubject)->sum('completed') }}</span>
                                    <span class="hero-stat-lbl">Completed</span>
                                </div>
                                <div class="hero-stat-box">
                                    <span class="hero-stat-num text-warning">{{ $overallVideoProgress ?? 0 }}%</span>
                                    <span class="hero-stat-lbl">Watch Progress</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Search and Quick Filters -->
            <div class="lessons-controls-card mb-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between p-1 p-md-2">
                    <div class="search-input-group flex-grow-1 mr-md-2 mb-1 mb-md-0 position-relative">
                        <i class="feather icon-search search-icon"></i>
                        <input type="text" id="subjectSearchInput" class="form-control lesson-search-control" placeholder="Search video subjects by name or keywords..." autocomplete="off">
                        <button type="button" id="clearSearchBtn" class="clear-search-btn d-none" title="Clear search">
                            <i class="feather icon-x"></i>
                        </button>
                    </div>
                    
                    <div class="filter-pills-wrapper d-flex align-items-center flex-wrap">
                        <button type="button" class="filter-pill active" data-filter="all">All (<span id="count-all">{{ $subjects->count() }}</span>)</button>
                        <button type="button" class="filter-pill" data-filter="in-progress">In Progress (<span id="count-progress">0</span>)</button>
                        <button type="button" class="filter-pill" data-filter="completed">Completed (<span id="count-completed">0</span>)</button>
                        <button type="button" class="filter-pill" data-filter="not-started">Not Started (<span id="count-notstarted">0</span>)</button>
                    </div>
                </div>
            </div>

            <!-- Subject Grid Area -->
            <section id="student-lessons-section">
                <div class="row" id="subjectsGrid">
                    @php
                        $defaultPalettes = [
                            [ // Indigo / Blue (Maths)
                                'gradient' => 'linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%)',
                                'light' => 'rgba(79, 70, 229, 0.08)',
                                'color' => '#4f46e5',
                                'shadow' => 'rgba(79, 70, 229, 0.28)',
                                'border' => 'rgba(79, 70, 229, 0.22)',
                                'icon' => 'feather icon-award'
                            ],
                            [ // Sunset Pink / Rose (English)
                                'gradient' => 'linear-gradient(135deg, #ec4899 0%, #f43f5e 100%)',
                                'light' => 'rgba(236, 72, 153, 0.08)',
                                'color' => '#ec4899',
                                'shadow' => 'rgba(236, 72, 153, 0.28)',
                                'border' => 'rgba(236, 72, 153, 0.22)',
                                'icon' => 'feather icon-book-open'
                            ],
                            [ // Emerald / Cyan (Verbal Reasoning)
                                'gradient' => 'linear-gradient(135deg, #10b981 0%, #06b6d4 100%)',
                                'light' => 'rgba(16, 185, 129, 0.08)',
                                'color' => '#10b981',
                                'shadow' => 'rgba(16, 185, 129, 0.28)',
                                'border' => 'rgba(16, 185, 129, 0.22)',
                                'icon' => 'feather icon-cpu'
                            ],
                            [ // Violet / Indigo (Non-Verbal Reasoning)
                                'gradient' => 'linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%)',
                                'light' => 'rgba(139, 92, 246, 0.08)',
                                'color' => '#8b5cf6',
                                'shadow' => 'rgba(139, 92, 246, 0.28)',
                                'border' => 'rgba(139, 92, 246, 0.22)',
                                'icon' => 'feather icon-help-circle'
                            ],
                            [ // Amber / Orange (Science)
                                'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #ea580c 100%)',
                                'light' => 'rgba(245, 158, 11, 0.08)',
                                'color' => '#f59e0b',
                                'shadow' => 'rgba(245, 158, 11, 0.28)',
                                'border' => 'rgba(245, 158, 11, 0.22)',
                                'icon' => 'feather icon-zap'
                            ],
                            [ // Teal / Cyan (General)
                                'gradient' => 'linear-gradient(135deg, #0284c7 0%, #0d9488 100%)',
                                'light' => 'rgba(2, 132, 199, 0.08)',
                                'color' => '#0284c7',
                                'shadow' => 'rgba(2, 132, 199, 0.28)',
                                'border' => 'rgba(2, 132, 199, 0.22)',
                                'icon' => 'feather icon-layers'
                            ]
                        ];
                    @endphp

                    @forelse($subjects as $subject)
                        @php
                            $titleLower = strtolower($subject->title ?? '');
                            if (strpos($titleLower, 'math') !== false) {
                                $style = $defaultPalettes[0];
                            } elseif (strpos($titleLower, 'eng') !== false) {
                                $style = $defaultPalettes[1];
                            } elseif (strpos($titleLower, 'verbal') !== false && strpos($titleLower, 'non') === false) {
                                $style = $defaultPalettes[2];
                            } elseif (strpos($titleLower, 'non') !== false || strpos($titleLower, 'nvr') !== false) {
                                $style = $defaultPalettes[3];
                            } elseif (strpos($titleLower, 'sci') !== false) {
                                $style = $defaultPalettes[4];
                            } else {
                                $style = $defaultPalettes[$loop->index % count($defaultPalettes)];
                            }

                            $stats = $progressBySubject[$subject->id] ?? ['total' => 0, 'completed' => 0, 'percent' => 0];
                            $totalLessons = $stats['total'];
                            $completedLessons = $stats['completed'];
                            $percent = $stats['percent'];

                            // Determine filter status
                            if ($totalLessons > 0 && $completedLessons == $totalLessons) {
                                $filterStatus = 'completed';
                                $statusBadgeClass = 'badge-status-completed';
                                $statusText = 'Completed';
                                $statusIcon = 'feather icon-check-circle';
                            } elseif ($completedLessons > 0) {
                                $filterStatus = 'in-progress';
                                $statusBadgeClass = 'badge-status-progress';
                                $statusText = 'In Progress';
                                $statusIcon = 'feather icon-play-circle';
                            } else {
                                $filterStatus = 'not-started';
                                $statusBadgeClass = 'badge-status-pending';
                                $statusText = 'Not Started';
                                $statusIcon = 'feather icon-circle';
                            }
                        @endphp

                        <div class="col-lg-4 col-md-6 col-sm-12 subject-col" 
                             data-title="{{ strtolower($subject->title) }}" 
                             data-desc="{{ strtolower($subject->description ?? '') }}" 
                             data-status="{{ $filterStatus }}">
                            <div class="subject-card" 
                                 style="--subject-gradient: {{ $style['gradient'] }}; 
                                        --subject-gradient-light: {{ $style['light'] }}; 
                                        --subject-color: {{ $style['color'] }}; 
                                        --subject-shadow-color: {{ $style['shadow'] }};
                                        --subject-border-color: {{ $style['border'] }};">
                                
                                <!-- Card Top Status Ribbon -->
                                <div class="subject-card-top-bar">
                                    <span class="subject-status-badge {{ $statusBadgeClass }}">
                                        @if($filterStatus === 'in-progress')
                                            <span class="pulse-indicator"></span>
                                        @else
                                            <i class="{{ $statusIcon }} mr-25"></i>
                                        @endif
                                        {{ $statusText }}
                                    </span>
                                </div>

                                <div class="subject-card-body">
                                    <div class="text-center">
                                        <!-- Dynamic Subject Image / Illustration Container -->
                                        <div class="subject-image-container">
                                            <div class="subject-image-wrapper">
                                                @if(!empty($subject->image))
                                                    <img src="{{ asset('storage/' . $subject->image) }}" 
                                                         alt="{{ $subject->title }}" 
                                                         class="subject-dynamic-img" 
                                                         loading="lazy"
                                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                                    <div class="subject-fallback-icon d-none">
                                                        <i class="{{ $style['icon'] }}"></i>
                                                    </div>
                                                @else
                                                    <div class="subject-fallback-icon">
                                                        <i class="{{ $style['icon'] }}"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <h3 class="subject-title">{{ $subject->title }}</h3>
                                        <p class="subject-desc" title="{{ $subject->description }}">
                                            {{ $subject->description ?: 'Browse interactive video explanations and step-by-step walkthroughs.' }}
                                        </p>
                                    </div>

                                    <div>
                                        <!-- 3-Item Stats Matrix -->
                                        <div class="stats-grid">
                                            <div class="stat-item">
                                                <span class="stat-value text-primary">{{ $totalLessons }}</span>
                                                <span class="stat-label">Total Videos</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-success">{{ $completedLessons }}</span>
                                                <span class="stat-label">Completed</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-warning">{{ max(0, $totalLessons - $completedLessons) }}</span>
                                                <span class="stat-label">Remaining</span>
                                            </div>
                                        </div>

                                        <!-- Progress Section -->
                                        <div class="subject-progress-container">
                                            <div class="progress-label-wrapper">
                                                <span>
                                                    <i class="feather icon-trending-up mr-25"></i> Watch Progress
                                                </span>
                                                <span class="progress-percent-badge">{{ $percent }}%</span>
                                            </div>
                                            <div class="subject-progress-bar">
                                                <div class="subject-progress-fill" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-50 font-small-2 text-muted">
                                                <span>{{ $completedLessons }} of {{ $totalLessons }} Watched</span>
                                                <span>
                                                    <i class="feather icon-shield text-primary mr-25"></i> Seek-Proof
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Action CTA Button -->
                                        <a href="{{ route('student.videolessonslist', ['subject_id' => $subject->id]) }}" class="action-btn">
                                            <span>Watch Lessons</span>
                                            <i class="feather icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="empty-state-box">
                                <div class="empty-state-icon">
                                    <i class="feather icon-video-off"></i>
                                </div>
                                <h4 class="empty-state-title">No Video Lessons Available</h4>
                                <p class="empty-state-desc">There are no video lessons currently published matching your class, year group, or academic session.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- No Results Box for Search/Filter -->
                <div id="noSearchResultsBox" class="text-center py-5 d-none">
                    <div class="empty-state-box">
                        <div class="empty-state-icon text-muted">
                            <i class="feather icon-search"></i>
                        </div>
                        <h4 class="empty-state-title">No matching video subjects found</h4>
                        <p class="empty-state-desc">We couldn't find any subjects matching your current search query or active filter.</p>
                        <button type="button" id="resetFiltersBtn" class="btn btn-primary mt-1">
                            <i class="feather icon-refresh-cw mr-50"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('subjectSearchInput');
        var clearBtn = document.getElementById('clearSearchBtn');
        var filterPills = document.querySelectorAll('.filter-pill');
        var subjectCols = document.querySelectorAll('.subject-col');
        var noResultsBox = document.getElementById('noSearchResultsBox');
        var resetBtn = document.getElementById('resetFiltersBtn');

        // Dynamic Counters
        var countProgress = 0;
        var countCompleted = 0;
        var countNotStarted = 0;

        subjectCols.forEach(function (col) {
            var status = col.getAttribute('data-status');
            if (status === 'in-progress') countProgress++;
            else if (status === 'completed') countCompleted++;
            else if (status === 'not-started') countNotStarted++;
        });

        var elProgress = document.getElementById('count-progress');
        var elCompleted = document.getElementById('count-completed');
        var elNotStarted = document.getElementById('count-notstarted');
        if (elProgress) elProgress.textContent = countProgress;
        if (elCompleted) elCompleted.textContent = countCompleted;
        if (elNotStarted) elNotStarted.textContent = countNotStarted;

        var currentFilter = 'all';

        function applyFilters() {
            var query = (searchInput ? searchInput.value : '').trim().toLowerCase();
            var visibleCount = 0;

            if (clearBtn) {
                if (query.length > 0) {
                    clearBtn.classList.remove('d-none');
                } else {
                    clearBtn.classList.add('d-none');
                }
            }

            subjectCols.forEach(function (col) {
                var title = col.getAttribute('data-title') || '';
                var desc = col.getAttribute('data-desc') || '';
                var status = col.getAttribute('data-status') || '';

                var matchesQuery = !query || title.indexOf(query) !== -1 || desc.indexOf(query) !== -1;
                var matchesFilter = currentFilter === 'all' || status === currentFilter;

                if (matchesQuery && matchesFilter) {
                    col.style.display = 'flex';
                    visibleCount++;
                } else {
                    col.style.display = 'none';
                }
            });

            if (noResultsBox) {
                if (visibleCount === 0 && subjectCols.length > 0) {
                    noResultsBox.classList.remove('d-none');
                } else {
                    noResultsBox.classList.add('d-none');
                }
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                applyFilters();
                searchInput.focus();
            });
        }

        filterPills.forEach(function (pill) {
            pill.addEventListener('click', function () {
                filterPills.forEach(function (p) { p.classList.remove('active'); });
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                applyFilters();
            });
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                if (searchInput) searchInput.value = '';
                currentFilter = 'all';
                filterPills.forEach(function (p) {
                    if (p.getAttribute('data-filter') === 'all') {
                        p.classList.add('active');
                    } else {
                        p.classList.remove('active');
                    }
                });
                applyFilters();
            });
        }
    });
</script>
@endpush