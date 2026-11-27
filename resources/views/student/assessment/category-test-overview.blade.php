@extends('layouts.app')

@section('title', 'Assessments - Aspire Learners')

@push('styles')
    <style>
        /* ==========================================================================
           STUDENT ASSESSMENTS OVERVIEW - MODERN UI/UX (LIGHT & DARK MODE)
           ========================================================================== */
        .as-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.4rem;
        }

        .as-page-title {
            color: var(--text-primary, #0b1f51);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .as-page-subtitle {
            color: var(--text-muted, #64748b);
            font-size: 0.96rem;
            font-weight: 500;
            margin: 0;
        }

        .as-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .as-header-pill-btn {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            color: var(--text-primary, #1e293b) !important;
            border-radius: 12px;
            padding: 9px 16px;
            font-size: 0.86rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .as-header-pill-btn:hover {
            border-color: #2477F9;
            color: #2477F9 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(36, 119, 249, 0.14);
        }

        /* Top 4 KPI Summary Cards */
        .as-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 1.35rem;
        }

        .as-kpi-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 3px 14px rgba(15, 23, 42, 0.035);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            text-align: left;
            width: 100%;
            outline: none !important;
        }

        .as-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            border-color: #cbd5e1;
        }

        .as-kpi-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .as-kpi-icon.kpi-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2477F9 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.25);
        }

        .as-kpi-icon.kpi-soft-blue {
            background-color: #dcebff;
            color: #2563eb;
        }

        .as-kpi-icon.kpi-green {
            background: linear-gradient(135deg, #2ecc71 0%, #16a34a 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
        }

        .as-kpi-icon.kpi-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .as-kpi-meta {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .as-kpi-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-body, #334155);
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .as-kpi-count {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.15;
            margin: 2px 0;
            font-variant-numeric: tabular-nums;
        }

        .as-kpi-sub {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Toolbar: Filter Pills, Sort & Search */
        .as-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 1.35rem;
        }

        .as-filter-pills {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .as-filter-pill {
            border: 1px solid transparent;
            background-color: #e8eef7;
            color: #334155;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.18s ease;
            outline: none !important;
            line-height: 1.2;
        }

        .as-filter-pill:hover {
            background-color: #dce5f3;
            color: #0b1f51;
        }

        .as-filter-pill.active {
            background: linear-gradient(135deg, #2477F9 0%, #1757C1 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.28);
        }

        .as-toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .as-sort-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .as-sort-label {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin: 0;
            white-space: nowrap;
        }

        .as-select {
            height: 40px;
            padding: 6px 34px 6px 14px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            border-radius: 10px;
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--text-primary, #1e293b);
            cursor: pointer;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 15px 15px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .as-select:hover,
        .as-select:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .as-search-box {
            position: relative;
            width: 245px;
            max-width: 100%;
        }

        .as-search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: inline-flex;
        }

        .as-search-input {
            width: 100%;
            height: 40px;
            padding: 8px 32px 8px 36px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            border-radius: 10px;
            font-size: 0.88rem;
            color: var(--text-primary, #0b1f51);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .as-search-input::placeholder {
            color: #94a3b8;
        }

        .as-search-input:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .as-search-clear {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .as-search-clear:hover {
            color: #ef4444;
        }

        /* Subject Cards Grid */
        .as-subjects-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 1.5rem;
        }

        .as-subject-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        }

        .as-subject-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--subj-gradient, linear-gradient(135deg, #2477F9 0%, #1757C1 100%));
        }

        .as-subject-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.075);
            border-color: var(--subj-soft-border, #bfdbfe);
        }

        .as-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .as-subj-identity {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .as-subj-avatar {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            border: 1px solid var(--subj-soft-border, #bfdbfe);
            color: var(--subj-color, #2477F9);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .as-subj-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 7px;
        }

        .as-subj-title {
            font-size: 1.18rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
            line-height: 1.25;
        }

        .as-subj-topics-meta {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
        }

        /* Status Pills */
        .as-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .as-status-completed {
            background-color: #dcfce7;
            color: #15803d;
        }

        .as-status-progress {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .as-status-pending {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .as-pulse-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #2477F9;
            display: inline-block;
        }

        .as-subj-desc {
            font-size: 0.86rem;
            color: var(--text-muted, #64748b);
            line-height: 1.5;
            margin-bottom: 16px;
            min-height: 40px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* 3-Item Mini Stats Strip */
        .as-mini-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            padding: 10px;
            border-radius: 12px;
            background-color: #f8fafc;
            border: 1px solid #eef2f7;
            margin-bottom: 16px;
        }

        .as-mini-stat {
            text-align: center;
            padding: 4px;
        }

        .as-mini-stat + .as-mini-stat {
            border-left: 1px solid #e2e8f0;
        }

        .as-mini-val {
            display: block;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .as-mini-lbl {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin-top: 2px;
        }

        /* Progress Section */
        .as-progress-wrap {
            margin-bottom: 18px;
        }

        .as-progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 7px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-body, #334155);
        }

        .as-progress-fraction {
            color: var(--text-muted, #64748b);
            font-variant-numeric: tabular-nums;
        }

        .as-progress-pct {
            font-weight: 800;
            color: var(--subj-color, #2477F9);
            font-variant-numeric: tabular-nums;
        }

        .as-progress-track {
            width: 100%;
            height: 9px;
            border-radius: 999px;
            background-color: #edf2f7;
            overflow: hidden;
        }

        .as-progress-fill {
            height: 100%;
            border-radius: 999px;
            background: var(--subj-gradient, linear-gradient(90deg, #2477F9, #1757C1));
            transition: width 0.55s ease;
        }

        .as-last-completed {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 9px;
            font-size: 0.78rem;
            color: var(--text-muted, #64748b);
        }

        .as-avg-chip {
            padding: 2px 8px;
            border-radius: 999px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            color: var(--subj-soft-text, #1757C1);
            font-weight: 700;
            font-size: 0.74rem;
        }

        /* CTA Button */
        .as-card-cta {
            width: 100%;
            padding: 10px 18px;
            border-radius: 12px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            color: var(--subj-soft-text, #1757C1) !important;
            border: 1px solid var(--subj-soft-border, #bfdbfe);
            font-size: 0.9rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .as-card-cta:hover {
            background: var(--subj-gradient, linear-gradient(135deg, #2477F9 0%, #1757C1 100%));
            color: #ffffff !important;
            border-color: transparent;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.12);
        }

        .as-card-cta i {
            transition: transform 0.2s ease;
        }

        .as-card-cta:hover i {
            transform: translateX(3px);
        }

        /* Bottom Guidance Strip (consistent with Focus Areas Keep Going banner) */
        .as-tip-banner {
            background: linear-gradient(135deg, #eef4ff 0%, #e5efff 100%);
            border: 1px solid #d5e5ff;
            border-radius: 16px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .as-tip-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .as-tip-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background-color: #ffffff;
            color: #2477F9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(36, 119, 249, 0.12);
        }

        .as-tip-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
        }

        .as-tip-desc {
            font-size: 0.85rem;
            color: #475569;
            margin: 0;
        }

        .as-tip-link {
            font-size: 0.86rem;
            font-weight: 700;
            color: #2477F9 !important;
            text-decoration: none !important;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .as-tip-link:hover {
            color: #1757C1 !important;
        }

        /* Empty State */
        .as-empty-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            padding: 3rem 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .as-empty-icon {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2477F9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            margin-bottom: 14px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1199.98px) {
            .as-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .as-subjects-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .as-subjects-grid {
                grid-template-columns: 1fr;
            }
            .as-search-box {
                width: 100%;
            }
        }

        @media (max-width: 575.98px) {
            .as-kpi-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dark Mode Support */
        body.dark-layout .as-kpi-card,
        body.dark-layout .as-subject-card,
        body.dark-layout .as-header-pill-btn,
        body.dark-layout .as-select,
        body.dark-layout .as-search-input,
        body.dark-layout .as-empty-card {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-layout .as-page-title,
        body.dark-layout .as-kpi-count,
        body.dark-layout .as-subj-title,
        body.dark-layout .as-mini-val,
        body.dark-layout .as-tip-title,
        body.dark-layout .as-header-pill-btn,
        body.dark-layout .as-select,
        body.dark-layout .as-search-input {
            color: #f8fafc !important;
        }

        body.dark-layout .as-page-subtitle,
        body.dark-layout .as-kpi-sub,
        body.dark-layout .as-subj-topics-meta,
        body.dark-layout .as-subj-desc,
        body.dark-layout .as-mini-lbl,
        body.dark-layout .as-progress-fraction,
        body.dark-layout .as-last-completed,
        body.dark-layout .as-sort-label,
        body.dark-layout .as-tip-desc {
            color: #94a3b8 !important;
        }

        body.dark-layout .as-kpi-label,
        body.dark-layout .as-progress-header {
            color: #cbd5e1 !important;
        }

        body.dark-layout .as-mini-stats,
        body.dark-layout .as-progress-track {
            background-color: #0f172a;
            border-color: #334155;
        }

        body.dark-layout .as-mini-stat + .as-mini-stat {
            border-left-color: #334155;
        }

        body.dark-layout .as-filter-pill {
            background-color: #1e293b;
            color: #cbd5e1;
            border-color: #334155;
        }

        body.dark-layout .as-filter-pill:hover {
            background-color: #334155;
            color: #ffffff;
        }

        body.dark-layout .as-filter-pill.active {
            border-color: transparent;
        }

        body.dark-layout .as-status-completed {
            background-color: rgba(34, 197, 94, 0.2);
            color: #86efac;
        }

        body.dark-layout .as-status-progress {
            background-color: rgba(36, 119, 249, 0.22);
            color: #93c5fd;
        }

        body.dark-layout .as-status-pending {
            background-color: rgba(148, 163, 184, 0.16);
            color: #cbd5e1;
        }

        body.dark-layout .as-tip-banner {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16) 0%, rgba(30, 41, 59, 0.95) 100%);
            border-color: #334155;
        }

        body.dark-layout .as-tip-icon {
            background-color: #0f172a;
            color: #60a5fa;
        }

        body.dark-layout .as-kpi-icon.kpi-soft-blue {
            background-color: rgba(37, 99, 235, 0.22);
            color: #93c5fd;
        }

        body.dark-layout .as-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        }
    </style>
@endpush

@section('content')
    @php
        $subjectsCount = $subjects->count();
        $allTotalPapers = $totalPapers ?? $subjects->sum('total_papers');
        $allTotalCompleted = $totalCompleted ?? $subjects->sum('completed_papers_count');
        $allTotalPaused = $totalPaused ?? $subjects->sum('paused_papers_count');
        $prepPct = $overallProgress ?? 0;
    @endphp

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <!-- Page Header -->
                <div class="as-page-header">
                    <div>
                        <h1 class="as-page-title">Assessments</h1>
                        <p class="as-page-subtitle">Choose a subject to explore topics, practice tests, and track your progress.</p>
                    </div>

                    <div class="as-header-actions">
                        <a href="{{ route('student.weeklytests') }}" class="as-header-pill-btn">
                            <i class="feather icon-calendar text-primary"></i>
                            <span>Weekly Tests</span>
                        </a>
                        <a href="{{ route('student.focusareas') }}" class="as-header-pill-btn">
                            <i class="feather icon-crosshair text-danger"></i>
                            <span>Focus Areas</span>
                        </a>
                    </div>
                </div>

                <!-- Top Row: 4 KPI Summary Cards -->
                <div class="as-kpi-grid">
                    <button type="button" class="as-kpi-card" data-kpi-filter="all" title="Show all subjects">
                        <div class="as-kpi-icon kpi-blue">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                <polyline points="2 17 12 22 22 17"/>
                                <polyline points="2 12 12 17 22 12"/>
                            </svg>
                        </div>
                        <div class="as-kpi-meta">
                            <span class="as-kpi-label">Subjects</span>
                            <span class="as-kpi-count">{{ $subjectsCount }}</span>
                            <span class="as-kpi-sub">Active curriculum</span>
                        </div>
                    </button>

                    <button type="button" class="as-kpi-card" data-kpi-filter="all" title="Show all subjects">
                        <div class="as-kpi-icon kpi-soft-blue">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="9" y1="13" x2="15" y2="13"/>
                                <line x1="9" y1="17" x2="13" y2="17"/>
                            </svg>
                        </div>
                        <div class="as-kpi-meta">
                            <span class="as-kpi-label">Total Tests</span>
                            <span class="as-kpi-count">{{ $allTotalPapers }}</span>
                            <span class="as-kpi-sub">Available to practice</span>
                        </div>
                    </button>

                    <button type="button" class="as-kpi-card" data-kpi-filter="completed" title="Filter completed subjects">
                        <div class="as-kpi-icon kpi-green">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <div class="as-kpi-meta">
                            <span class="as-kpi-label">Finished</span>
                            <span class="as-kpi-count">{{ $allTotalCompleted }}</span>
                            <span class="as-kpi-sub">Completed tests</span>
                        </div>
                    </button>

                    <button type="button" class="as-kpi-card" data-kpi-filter="in-progress" title="Filter subjects in progress">
                        <div class="as-kpi-icon kpi-amber">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                                <polyline points="17 6 23 6 23 12"/>
                            </svg>
                        </div>
                        <div class="as-kpi-meta">
                            <span class="as-kpi-label">Overall Prep</span>
                            <span class="as-kpi-count">{{ $prepPct }}%</span>
                            <span class="as-kpi-sub">
                                {{ $allTotalPaused > 0 ? $allTotalPaused . ' paused in progress' : 'Across all subjects' }}
                            </span>
                        </div>
                    </button>
                </div>

                <!-- Filter Pills, Sort & Search Toolbar -->
                <div class="as-toolbar">
                    <div class="as-filter-pills" role="tablist" aria-label="Filter subjects by status">
                        <button type="button" class="as-filter-pill active" data-filter="all">
                            All (<span id="count-all">{{ $subjectsCount }}</span>)
                        </button>
                        <button type="button" class="as-filter-pill" data-filter="in-progress">
                            In Progress (<span id="count-progress">0</span>)
                        </button>
                        <button type="button" class="as-filter-pill" data-filter="completed">
                            Completed (<span id="count-completed">0</span>)
                        </button>
                        <button type="button" class="as-filter-pill" data-filter="not-started">
                            Not Started (<span id="count-notstarted">0</span>)
                        </button>
                    </div>

                    <div class="as-toolbar-right">
                        <div class="as-sort-group">
                            <label for="subjectSortSelect" class="as-sort-label">Sort by:</label>
                            <select id="subjectSortSelect" class="as-select" aria-label="Sort subjects">
                                <option value="default">Default Order</option>
                                <option value="progress_desc">Progress (High to Low)</option>
                                <option value="tests_desc">Most Tests</option>
                                <option value="name_asc">Name (A to Z)</option>
                            </select>
                        </div>

                        <div class="as-search-box">
                            <span class="as-search-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                            </span>
                            <input type="text"
                                   id="subjectSearchInput"
                                   class="as-search-input"
                                   placeholder="Search subjects..."
                                   autocomplete="off"
                                   aria-label="Search subjects">
                            <button type="button" id="clearSearchBtn" class="as-search-clear d-none" aria-label="Clear search">
                                <i class="feather icon-x"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Subject Cards Grid -->
                <div class="as-subjects-grid" id="subjectsGrid">
                    @forelse ($subjects as $subject)
                        @php
                            $theme = $subject->theme ?? [
                                'key'         => 'default',
                                'color'       => '#2477F9',
                                'gradient'    => 'linear-gradient(135deg, #2477F9 0%, #1757C1 100%)',
                                'soft_bg'     => '#EBF3FF',
                                'soft_text'   => '#1757C1',
                                'soft_border' => '#BFDBFE',
                                'icon'        => 'layers',
                            ];

                            $completedCount = $subject->completed_papers_count ?? 0;
                            $pausedCount = $subject->paused_papers_count ?? 0;
                            $totalSubjectPapers = $subject->total_papers ?? 0;
                            $progressPercentage = $subject->progress_percentage ?? 0;
                            $topicsCount = $subject->topics_count ?? 0;

                            if ($totalSubjectPapers > 0 && $completedCount >= $totalSubjectPapers) {
                                $filterStatus = 'completed';
                                $statusBadgeClass = 'as-status-completed';
                                $statusText = 'Completed';
                            } elseif ($completedCount > 0 || $pausedCount > 0) {
                                $filterStatus = 'in-progress';
                                $statusBadgeClass = 'as-status-progress';
                                $statusText = 'In Progress';
                            } else {
                                $filterStatus = 'not-started';
                                $statusBadgeClass = 'as-status-pending';
                                $statusText = 'Not Started';
                            }
                        @endphp

                        <div class="as-subject-card subject-col"
                             data-index="{{ $loop->index }}"
                             data-title="{{ strtolower($subject->title) }}"
                             data-desc="{{ strtolower($subject->description ?? '') }}"
                             data-status="{{ $filterStatus }}"
                             data-progress="{{ $progressPercentage }}"
                             data-tests="{{ $totalSubjectPapers }}"
                             style="--subj-color: {{ $theme['color'] }};
                                    --subj-gradient: {{ $theme['gradient'] }};
                                    --subj-soft-bg: {{ $theme['soft_bg'] }};
                                    --subj-soft-text: {{ $theme['soft_text'] }};
                                    --subj-soft-border: {{ $theme['soft_border'] }};">

                            <div>
                                <!-- Top Identity + Status -->
                                <div class="as-card-top">
                                    <div class="as-subj-identity">
                                        <div class="as-subj-avatar">
                                            @if(!empty($subject->image))
                                                <img src="{{ asset('storage/' . $subject->image) }}"
                                                     alt="{{ $subject->title }}"
                                                     loading="lazy"
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                                <span class="d-none">
                                                    @if($theme['icon'] === 'book-open')
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M11.2 5.2C9.5 4.2 7.1 3.7 4 4a1 1 0 0 0-.9 1v13.2a1 1 0 0 0 1.1 1c2.8-.3 5.2.3 6.9 1.3V5.2zm1.6 0v15.3c1.7-1 4.1-1.6 6.9-1.3a1 1 0 0 0 1.1-1V5a1 1 0 0 0-.9-1c-3.1-.3-5.5.2-7.1 1.2z"/>
                                                        </svg>
                                                    @elseif($theme['icon'] === 'calculator')
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm1.5 3.5v3h9v-3h-9zm1 5.5a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm-7 4a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4z"/>
                                                        </svg>
                                                    @elseif($theme['icon'] === 'brain')
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04z"/>
                                                            <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04z"/>
                                                        </svg>
                                                    @else
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                                            <polyline points="2 17 12 22 22 17"/>
                                                            <polyline points="2 12 12 17 22 12"/>
                                                        </svg>
                                                    @endif
                                                </span>
                                            @else
                                                @if($theme['icon'] === 'book-open')
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M11.2 5.2C9.5 4.2 7.1 3.7 4 4a1 1 0 0 0-.9 1v13.2a1 1 0 0 0 1.1 1c2.8-.3 5.2.3 6.9 1.3V5.2zm1.6 0v15.3c1.7-1 4.1-1.6 6.9-1.3a1 1 0 0 0 1.1-1V5a1 1 0 0 0-.9-1c-3.1-.3-5.5.2-7.1 1.2z"/>
                                                    </svg>
                                                @elseif($theme['icon'] === 'calculator')
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm1.5 3.5v3h9v-3h-9zm1 5.5a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm-7 4a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4z"/>
                                                    </svg>
                                                @elseif($theme['icon'] === 'brain')
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04z"/>
                                                        <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04z"/>
                                                    </svg>
                                                @else
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                                        <polyline points="2 17 12 22 22 17"/>
                                                        <polyline points="2 12 12 17 22 12"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="min-width-0">
                                            <h3 class="as-subj-title text-truncate">{{ $subject->title }}</h3>
                                            <span class="as-subj-topics-meta">
                                                {{ $topicsCount }} {{ \Illuminate\Support\Str::plural('Topic', $topicsCount) }}
                                            </span>
                                        </div>
                                    </div>

                                    <span class="as-status-badge {{ $statusBadgeClass }}">
                                        @if($filterStatus === 'in-progress')
                                            <span class="as-pulse-dot"></span>
                                        @elseif($filterStatus === 'completed')
                                            <i class="feather icon-check-circle"></i>
                                        @endif
                                        {{ $statusText }}
                                    </span>
                                </div>

                                <p class="as-subj-desc" title="{{ $subject->description }}">
                                    {{ $subject->description ?: 'Build confidence with topic practice papers and timed mock assessments.' }}
                                </p>
                            </div>

                            <div>
                                <!-- 3-Cell Metrics Strip -->
                                <div class="as-mini-stats">
                                    <div class="as-mini-stat">
                                        <span class="as-mini-val">{{ $totalSubjectPapers }}</span>
                                        <span class="as-mini-lbl">Total Tests</span>
                                    </div>
                                    <div class="as-mini-stat">
                                        <span class="as-mini-val text-success">{{ $completedCount }}</span>
                                        <span class="as-mini-lbl">Finished</span>
                                    </div>
                                    <div class="as-mini-stat">
                                        <span class="as-mini-val text-warning">{{ $pausedCount }}</span>
                                        <span class="as-mini-lbl">Paused</span>
                                    </div>
                                </div>

                                <!-- Progress Bar & Last Completed -->
                                <div class="as-progress-wrap">
                                    <div class="as-progress-header">
                                        <span>
                                            Completion <span class="as-progress-fraction ml-25">{{ $completedCount }}/{{ $totalSubjectPapers }}</span>
                                        </span>
                                        <span class="as-progress-pct">{{ $progressPercentage }}%</span>
                                    </div>
                                    <div class="as-progress-track">
                                        <div class="as-progress-fill" style="width: {{ $progressPercentage }}%;"></div>
                                    </div>
                                    <div class="as-last-completed">
                                        <span>
                                            <i class="feather icon-calendar mr-25"></i>
                                            Last Completed:
                                            <strong>{{ $subject->last_completed_at ? $subject->last_completed_at->format('d/m/Y') : 'Not taken yet' }}</strong>
                                        </span>
                                        @if($subject->average_score !== null)
                                            <span class="as-avg-chip">Avg {{ $subject->average_score }}%</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action CTA -->
                                <a href="{{ route('student.assessments.topics', ['subject' => $subject->id]) }}" class="as-card-cta">
                                    <span>Check Assessments</span>
                                    <i class="feather icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="as-empty-card" style="grid-column: 1 / -1;">
                            <div class="as-empty-icon">
                                <i class="feather icon-folder"></i>
                            </div>
                            <h4 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">No Subjects Available</h4>
                            <p class="text-muted mb-0">There are no active subjects configured in the student portal yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Client-side No Results Box -->
                <div id="noSearchResultsBox" class="as-empty-card d-none">
                    <div class="as-empty-icon">
                        <i class="feather icon-search"></i>
                    </div>
                    <h4 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">No matching subjects found</h4>
                    <p class="text-muted mb-2">We couldn't find any subjects matching your search or selected status filter.</p>
                    <button type="button" id="resetFiltersBtn" class="btn btn-primary btn-sm px-2" style="border-radius: 10px;">
                        <i class="feather icon-refresh-cw mr-25"></i> Reset Filters
                    </button>
                </div>

                <!-- Guidance Callout Strip -->
                <div class="as-tip-banner">
                    <div class="as-tip-left">
                        <span class="as-tip-icon">
                            <i class="feather icon-target font-medium-3"></i>
                        </span>
                        <div>
                            <h4 class="as-tip-title">Want to see which topics need more practice?</h4>
                            <p class="as-tip-desc">Review your topic-by-topic accuracy and focus areas to raise your scores faster.</p>
                        </div>
                    </div>
                    <a href="{{ route('student.focusareas') }}" class="as-tip-link">
                        <span>View Focus Areas</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var grid = document.getElementById('subjectsGrid');
            var searchInput = document.getElementById('subjectSearchInput');
            var clearBtn = document.getElementById('clearSearchBtn');
            var sortSelect = document.getElementById('subjectSortSelect');
            var filterPills = document.querySelectorAll('.as-filter-pill');
            var kpiCards = document.querySelectorAll('.as-kpi-card[data-kpi-filter]');
            var subjectCols = Array.prototype.slice.call(document.querySelectorAll('.subject-col'));
            var noResultsBox = document.getElementById('noSearchResultsBox');
            var resetBtn = document.getElementById('resetFiltersBtn');

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

            function syncActivePill(filterKey) {
                filterPills.forEach(function (p) {
                    p.classList.toggle('active', p.getAttribute('data-filter') === filterKey);
                });
            }

            function applyFiltersAndSort() {
                var query = (searchInput ? searchInput.value : '').trim().toLowerCase();
                var sortBy = sortSelect ? sortSelect.value : 'default';

                if (clearBtn) {
                    clearBtn.classList.toggle('d-none', query.length === 0);
                }

                subjectCols.sort(function (a, b) {
                    if (sortBy === 'progress_desc') {
                        return parseInt(b.getAttribute('data-progress') || '0', 10) - parseInt(a.getAttribute('data-progress') || '0', 10);
                    }
                    if (sortBy === 'tests_desc') {
                        return parseInt(b.getAttribute('data-tests') || '0', 10) - parseInt(a.getAttribute('data-tests') || '0', 10);
                    }
                    if (sortBy === 'name_asc') {
                        return (a.getAttribute('data-title') || '').localeCompare(b.getAttribute('data-title') || '');
                    }
                    return parseInt(a.getAttribute('data-index') || '0', 10) - parseInt(b.getAttribute('data-index') || '0', 10);
                });

                var visibleCount = 0;
                subjectCols.forEach(function (col) {
                    if (grid) grid.appendChild(col);

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
                    noResultsBox.classList.toggle('d-none', !(visibleCount === 0 && subjectCols.length > 0));
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFiltersAndSort);
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    searchInput.value = '';
                    applyFiltersAndSort();
                    searchInput.focus();
                });
            }

            if (sortSelect) {
                sortSelect.addEventListener('change', applyFiltersAndSort);
            }

            filterPills.forEach(function (pill) {
                pill.addEventListener('click', function () {
                    currentFilter = this.getAttribute('data-filter') || 'all';
                    syncActivePill(currentFilter);
                    applyFiltersAndSort();
                });
            });

            kpiCards.forEach(function (card) {
                card.addEventListener('click', function () {
                    var target = this.getAttribute('data-kpi-filter') || 'all';
                    currentFilter = (target === currentFilter && target !== 'all') ? 'all' : target;
                    syncActivePill(currentFilter);
                    applyFiltersAndSort();
                });
            });

            if (resetBtn) {
                resetBtn.addEventListener('click', function () {
                    if (searchInput) searchInput.value = '';
                    if (sortSelect) sortSelect.value = 'default';
                    currentFilter = 'all';
                    syncActivePill('all');
                    applyFiltersAndSort();
                });
            }
        });
    </script>
@endpush
