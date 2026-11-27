@extends('layouts.app')

@section('title', $subject->title . ' Topics - Aspire Learners')

@push('styles')
    <style>
        /* ==========================================================================
           STUDENT SUBJECT TOPICS - MODERN UI/UX (LIGHT & DARK MODE COMPATIBLE)
           ========================================================================== */
        .tp-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin-bottom: 0.85rem;
        }

        .tp-breadcrumb a {
            color: #2477F9;
            text-decoration: none;
        }

        .tp-breadcrumb a:hover {
            text-decoration: underline;
        }

        .tp-breadcrumb-sep {
            opacity: 0.55;
        }

        .tp-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.4rem;
        }

        .tp-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .tp-subject-avatar {
            width: 54px;
            height: 54px;
            border-radius: 15px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            border: 1px solid var(--subj-soft-border, #bfdbfe);
            color: var(--subj-color, #2477F9);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .tp-subject-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
        }

        .tp-page-title {
            color: var(--text-primary, #0b1f51);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .tp-page-subtitle {
            color: var(--text-muted, #64748b);
            font-size: 0.95rem;
            font-weight: 500;
            margin: 0;
        }

        .tp-back-btn {
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

        .tp-back-btn:hover {
            border-color: #2477F9;
            color: #2477F9 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(36, 119, 249, 0.14);
        }

        /* Top 4 KPI Summary Cards */
        .tp-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 1.35rem;
        }

        .tp-kpi-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 3px 14px rgba(15, 23, 42, 0.035);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .tp-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        }

        .tp-kpi-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .tp-kpi-icon.kpi-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2477F9 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.25);
        }

        .tp-kpi-icon.kpi-soft-blue {
            background-color: #dcebff;
            color: #2563eb;
        }

        .tp-kpi-icon.kpi-green {
            background: linear-gradient(135deg, #2ecc71 0%, #16a34a 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
        }

        .tp-kpi-icon.kpi-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .tp-kpi-meta {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .tp-kpi-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-body, #334155);
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tp-kpi-count {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.15;
            margin: 2px 0;
            font-variant-numeric: tabular-nums;
        }

        .tp-kpi-sub {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Toolbar: Status Pills, Sort & Search */
        .tp-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .tp-filter-pills {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tp-filter-pill {
            border: 1px solid transparent;
            background-color: #e8eef7;
            color: #334155;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 999px;
            cursor: pointer;
            text-decoration: none !important;
            transition: all 0.18s ease;
            line-height: 1.2;
        }

        .tp-filter-pill:hover {
            background-color: #dce5f3;
            color: #0b1f51;
        }

        .tp-filter-pill.active {
            background: linear-gradient(135deg, #2477F9 0%, #1757C1 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.28);
        }

        .tp-toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .tp-sort-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .tp-sort-label {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin: 0;
            white-space: nowrap;
        }

        .tp-select {
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

        .tp-select:hover,
        .tp-select:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .tp-search-box {
            position: relative;
            width: 260px;
            max-width: 100%;
        }

        .tp-search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: inline-flex;
        }

        .tp-search-input {
            width: 100%;
            height: 40px;
            padding: 8px 34px 8px 36px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            border-radius: 10px;
            font-size: 0.88rem;
            color: var(--text-primary, #0b1f51);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .tp-search-input::placeholder {
            color: #94a3b8;
        }

        .tp-search-input:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .tp-search-clear {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            padding: 2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none !important;
        }

        .tp-search-clear:hover {
            color: #ef4444;
        }

        /* Topics Directory Card & Table */
        .tp-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
            margin-bottom: 1.5rem;
        }

        .tp-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .tp-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
            line-height: 1.3;
        }

        .tp-card-subtitle {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            margin: 0;
        }

        .tp-count-chip {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            background-color: #f1f5f9;
            padding: 5px 12px;
            border-radius: 999px;
        }

        .tp-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .tp-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tp-table thead th {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            padding: 10px 12px 12px;
            border-bottom: 1px solid var(--border-subtle, #eef2f7);
            white-space: nowrap;
            text-align: left;
        }

        .tp-table thead th.text-right {
            text-align: right;
        }

        .tp-topic-row {
            border-bottom: 1px solid var(--border-subtle, #f1f5f9);
            transition: background-color 0.16s ease;
            cursor: pointer;
        }

        .tp-topic-row:last-child {
            border-bottom: none;
        }

        .tp-topic-row:hover {
            background-color: #f8fbff;
        }

        .tp-table tbody td {
            padding: 14px 12px;
            vertical-align: middle;
        }

        .tp-topic-title-link {
            color: var(--text-primary, #0b1f51);
            font-size: 0.96rem;
            font-weight: 700;
            text-decoration: none !important;
            display: inline-block;
            margin-bottom: 3px;
            transition: color 0.16s ease;
        }

        .tp-topic-row:hover .tp-topic-title-link {
            color: #2477F9;
        }

        .tp-topic-submeta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.78rem;
            color: var(--text-muted, #64748b);
        }

        .tp-acc-pill {
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .tp-acc-strong {
            background-color: #dcfce7;
            color: #15803d;
        }

        .tp-acc-developing {
            background-color: #fef3c7;
            color: #b45309;
        }

        .tp-acc-focus {
            background-color: #fee2e2;
            color: #dc2626;
        }

        /* Progress Column */
        .tp-prog-cell {
            min-width: 175px;
        }

        .tp-prog-labels {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-body, #334155);
            margin-bottom: 5px;
            font-variant-numeric: tabular-nums;
        }

        .tp-prog-track {
            width: 100%;
            height: 9px;
            border-radius: 999px;
            background-color: #edf2f7;
            overflow: hidden;
        }

        .tp-prog-fill {
            height: 100%;
            border-radius: 999px;
            background: var(--subj-gradient, linear-gradient(90deg, #2477F9, #1757C1));
            transition: width 0.5s ease;
        }

        .tp-prog-fill.is-complete {
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        /* Difficulty Breakdown Micro-Pills */
        .tp-diff-group {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tp-diff-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .tp-diff-easy {
            background-color: #dcfce7;
            color: #15803d;
        }

        .tp-diff-medium {
            background-color: #fef3c7;
            color: #b45309;
        }

        .tp-diff-hard {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .tp-diff-muted {
            background-color: #f1f5f9;
            color: #94a3b8;
            font-weight: 600;
        }

        /* Status Labels */
        .tp-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .tp-status-completed {
            background-color: #dcfce7;
            color: #15803d;
        }

        .tp-status-in-progress {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .tp-status-not-started {
            background-color: #f1f5f9;
            color: #64748b;
        }

        /* Action Button */
        .tp-go-btn {
            padding: 7px 15px;
            border-radius: 10px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            color: var(--subj-soft-text, #1757C1) !important;
            border: 1px solid var(--subj-soft-border, #bfdbfe);
            font-size: 0.82rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none !important;
            white-space: nowrap;
            transition: all 0.18s ease;
        }

        .tp-go-btn:hover {
            background: var(--subj-gradient, linear-gradient(135deg, #2477F9 0%, #1757C1 100%));
            color: #ffffff !important;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.22);
        }

        /* Pagination Styling */
        .tp-pagination-wrap {
            display: flex;
            justify-content: center;
            margin-top: 1.25rem;
        }

        .tp-pagination-wrap .pagination {
            margin-bottom: 0;
            gap: 4px;
        }

        .tp-pagination-wrap .page-link {
            border-radius: 8px !important;
            border: 1px solid var(--border-color, #dfe7f2);
            color: var(--text-primary, #1e293b);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .tp-pagination-wrap .page-item.active .page-link {
            background-color: #2477F9;
            border-color: #2477F9;
            color: #ffffff;
        }

        /* Empty State */
        .tp-empty-box {
            text-align: center;
            padding: 3rem 1.25rem;
        }

        .tp-empty-icon {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2477F9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 12px;
        }

        /* Guidance Banner */
        .tp-tip-banner {
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

        .tp-tip-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .tp-tip-icon {
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

        .tp-tip-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
        }

        .tp-tip-desc {
            font-size: 0.85rem;
            color: #475569;
            margin: 0;
        }

        .tp-tip-link {
            font-size: 0.86rem;
            font-weight: 700;
            color: #2477F9 !important;
            text-decoration: none !important;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* Responsive */
        @media (max-width: 1199.98px) {
            .tp-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .tp-search-box {
                width: 100%;
            }
            .tp-card {
                padding: 18px 16px;
            }
        }

        @media (max-width: 575.98px) {
            .tp-kpi-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dark Mode Support */
        body.dark-layout .tp-kpi-card,
        body.dark-layout .tp-card,
        body.dark-layout .tp-back-btn,
        body.dark-layout .tp-select,
        body.dark-layout .tp-search-input {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-layout .tp-page-title,
        body.dark-layout .tp-kpi-count,
        body.dark-layout .tp-card-title,
        body.dark-layout .tp-topic-title-link,
        body.dark-layout .tp-tip-title,
        body.dark-layout .tp-back-btn,
        body.dark-layout .tp-select,
        body.dark-layout .tp-search-input {
            color: #f8fafc !important;
        }

        body.dark-layout .tp-page-subtitle,
        body.dark-layout .tp-kpi-sub,
        body.dark-layout .tp-card-subtitle,
        body.dark-layout .tp-sort-label,
        body.dark-layout .tp-table thead th,
        body.dark-layout .tp-topic-submeta,
        body.dark-layout .tp-tip-desc {
            color: #94a3b8 !important;
        }

        body.dark-layout .tp-kpi-label,
        body.dark-layout .tp-prog-labels {
            color: #cbd5e1 !important;
        }

        body.dark-layout .tp-table thead th,
        body.dark-layout .tp-topic-row {
            border-color: #334155 !important;
        }

        body.dark-layout .tp-topic-row:hover {
            background-color: rgba(36, 119, 249, 0.08);
        }

        body.dark-layout .tp-prog-track,
        body.dark-layout .tp-count-chip {
            background-color: #0f172a;
            color: #94a3b8;
        }

        body.dark-layout .tp-filter-pill {
            background-color: #1e293b;
            color: #cbd5e1;
            border-color: #334155;
        }

        body.dark-layout .tp-filter-pill:hover {
            background-color: #334155;
            color: #ffffff;
        }

        body.dark-layout .tp-filter-pill.active {
            border-color: transparent;
        }

        body.dark-layout .tp-diff-easy,
        body.dark-layout .tp-acc-strong,
        body.dark-layout .tp-status-completed {
            background-color: rgba(34, 197, 94, 0.2);
            color: #86efac;
        }

        body.dark-layout .tp-diff-medium,
        body.dark-layout .tp-acc-developing {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fde68a;
        }

        body.dark-layout .tp-diff-hard,
        body.dark-layout .tp-acc-focus {
            background-color: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        body.dark-layout .tp-status-in-progress {
            background-color: rgba(36, 119, 249, 0.22);
            color: #93c5fd;
        }

        body.dark-layout .tp-diff-muted,
        body.dark-layout .tp-status-not-started {
            background-color: rgba(148, 163, 184, 0.14);
            color: #94a3b8;
        }

        body.dark-layout .tp-kpi-icon.kpi-soft-blue {
            background-color: rgba(37, 99, 235, 0.22);
            color: #93c5fd;
        }

        body.dark-layout .tp-tip-banner {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16) 0%, rgba(30, 41, 59, 0.95) 100%);
            border-color: #334155;
        }

        body.dark-layout .tp-tip-icon {
            background-color: #0f172a;
            color: #60a5fa;
        }

        body.dark-layout .tp-pagination-wrap .page-link {
            background-color: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }

        body.dark-layout .tp-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        }
    </style>
@endpush

@section('content')
    @php
        $theme = $subjectTheme ?? [
            'color'       => '#2477F9',
            'gradient'    => 'linear-gradient(135deg, #2477F9 0%, #1757C1 100%)',
            'soft_bg'     => '#EBF3FF',
            'soft_text'   => '#1757C1',
            'soft_border' => '#BFDBFE',
            'icon'        => 'layers',
        ];

        $hasActiveFilter = request()->filled('topic_name') || ($statusFilter ?? 'all') !== 'all' || ($sortBy ?? 'name_asc') !== 'name_asc';
        $baseQuery = array_filter([
            'topic_name' => request('topic_name'),
            'sort'       => ($sortBy ?? 'name_asc') !== 'name_asc' ? $sortBy : null,
        ]);
    @endphp

    <div class="app-content content"
         style="--subj-color: {{ $theme['color'] }};
                --subj-gradient: {{ $theme['gradient'] }};
                --subj-soft-bg: {{ $theme['soft_bg'] }};
                --subj-soft-text: {{ $theme['soft_text'] }};
                --subj-soft-border: {{ $theme['soft_border'] }};">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <!-- Breadcrumb Trail -->
                <nav class="tp-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('student.dashboard') }}">Dashboard</a>
                    <span class="tp-breadcrumb-sep">/</span>
                    <a href="{{ route('student.assessments') }}">Assessments</a>
                    <span class="tp-breadcrumb-sep">/</span>
                    <span>{{ $subject->title }} Topics</span>
                </nav>

                <!-- Page Header -->
                <div class="tp-page-header">
                    <div class="tp-header-left">
                        <div class="tp-subject-avatar">
                            @if(!empty($subject->image))
                                <img src="{{ asset('storage/' . $subject->image) }}"
                                     alt="{{ $subject->title }}"
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

                        <div>
                            <h1 class="tp-page-title">{{ $subject->title }} Topics</h1>
                            <p class="tp-page-subtitle">
                                {{ $subject->description ?: 'Select a topic to browse subtopics, difficulty levels, and practice tests.' }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('student.assessments') }}" class="tp-back-btn">
                        <i class="feather icon-arrow-left"></i>
                        <span>Back to Assessments</span>
                    </a>
                </div>

                <!-- Top Row: 4 KPI Summary Cards -->
                <div class="tp-kpi-grid">
                    <div class="tp-kpi-card">
                        <div class="tp-kpi-icon kpi-blue">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                <polyline points="2 17 12 22 22 17"/>
                                <polyline points="2 12 12 17 22 12"/>
                            </svg>
                        </div>
                        <div class="tp-kpi-meta">
                            <span class="tp-kpi-label">Total Topics</span>
                            <span class="tp-kpi-count">{{ $totalTopicsCount }}</span>
                            <span class="tp-kpi-sub">In {{ $subject->title }}</span>
                        </div>
                    </div>

                    <div class="tp-kpi-card">
                        <div class="tp-kpi-icon kpi-soft-blue">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="9" y1="13" x2="15" y2="13"/>
                                <line x1="9" y1="17" x2="13" y2="17"/>
                            </svg>
                        </div>
                        <div class="tp-kpi-meta">
                            <span class="tp-kpi-label">Available Tests</span>
                            <span class="tp-kpi-count">{{ $totalTestsCount }}</span>
                            <span class="tp-kpi-sub">Across all topics</span>
                        </div>
                    </div>

                    <div class="tp-kpi-card">
                        <div class="tp-kpi-icon kpi-green">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <div class="tp-kpi-meta">
                            <span class="tp-kpi-label">Tests Completed</span>
                            <span class="tp-kpi-count">{{ $completedTestsCount }}</span>
                            <span class="tp-kpi-sub">{{ $subjectCompletionPct }}% overall progress</span>
                        </div>
                    </div>

                    <div class="tp-kpi-card">
                        <div class="tp-kpi-icon kpi-amber">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.5l2.85 5.78 6.38.93-4.61 4.5 1.09 6.35L12 17.06l-5.71 3 1.09-6.35-4.61-4.5 6.38-.93L12 2.5z"/>
                            </svg>
                        </div>
                        <div class="tp-kpi-meta">
                            <span class="tp-kpi-label">Average Accuracy</span>
                            <span class="tp-kpi-count">{{ $subjectAvgAccuracy !== null ? $subjectAvgAccuracy . '%' : '—' }}</span>
                            <span class="tp-kpi-sub">
                                @if($subjectAvgAccuracy === null)
                                    Take a test to score
                                @elseif($subjectAvgAccuracy >= 80)
                                    Strong mastery
                                @elseif($subjectAvgAccuracy >= 61)
                                    Getting better
                                @else
                                    Keep practicing
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Filter Pills, Sort & Search Toolbar -->
                <div class="tp-toolbar">
                    <div class="tp-filter-pills" role="tablist" aria-label="Filter topics by status">
                        <a href="{{ route('student.assessments.topics', [$subject->id] + $baseQuery) }}"
                           class="tp-filter-pill {{ $statusFilter === 'all' ? 'active' : '' }}">
                            All ({{ $statusCounts['all'] ?? 0 }})
                        </a>
                        <a href="{{ route('student.assessments.topics', [$subject->id] + $baseQuery + ['status' => 'in-progress']) }}"
                           class="tp-filter-pill {{ $statusFilter === 'in-progress' ? 'active' : '' }}">
                            In Progress ({{ $statusCounts['in-progress'] ?? 0 }})
                        </a>
                        <a href="{{ route('student.assessments.topics', [$subject->id] + $baseQuery + ['status' => 'completed']) }}"
                           class="tp-filter-pill {{ $statusFilter === 'completed' ? 'active' : '' }}">
                            Completed ({{ $statusCounts['completed'] ?? 0 }})
                        </a>
                        <a href="{{ route('student.assessments.topics', [$subject->id] + $baseQuery + ['status' => 'not-started']) }}"
                           class="tp-filter-pill {{ $statusFilter === 'not-started' ? 'active' : '' }}">
                            Not Started ({{ $statusCounts['not-started'] ?? 0 }})
                        </a>
                    </div>

                    <form method="GET" action="{{ route('student.assessments.topics', $subject->id) }}" id="topicsFilterForm" class="tp-toolbar-right">
                        @if($statusFilter !== 'all')
                            <input type="hidden" name="status" value="{{ $statusFilter }}">
                        @endif

                        <div class="tp-sort-group">
                            <label for="topicsSortSelect" class="tp-sort-label">Sort by:</label>
                            <select name="sort" id="topicsSortSelect" class="tp-select" onchange="this.form.submit()" aria-label="Sort topics">
                                <option value="name_asc" {{ $sortBy === 'name_asc' ? 'selected' : '' }}>Topic Name (A to Z)</option>
                                <option value="name_desc" {{ $sortBy === 'name_desc' ? 'selected' : '' }}>Topic Name (Z to A)</option>
                                <option value="tests_desc" {{ $sortBy === 'tests_desc' ? 'selected' : '' }}>Most Tests</option>
                                <option value="progress_desc" {{ $sortBy === 'progress_desc' ? 'selected' : '' }}>Progress (High to Low)</option>
                            </select>
                        </div>

                        <div class="tp-search-box">
                            <span class="tp-search-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                            </span>
                            <input type="text"
                                   name="topic_name"
                                   id="topicSearchInput"
                                   class="tp-search-input"
                                   placeholder="Search topics..."
                                   value="{{ request('topic_name') }}"
                                   autocomplete="off"
                                   aria-label="Search topics">
                            @if(request()->filled('topic_name'))
                                <a href="{{ route('student.assessments.topics', [$subject->id] + array_filter(['status' => $statusFilter !== 'all' ? $statusFilter : null, 'sort' => $sortBy !== 'name_asc' ? $sortBy : null])) }}"
                                   class="tp-search-clear"
                                   title="Clear search"
                                   aria-label="Clear search">
                                    <i class="feather icon-x"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Topics Directory Table Card -->
                <div class="tp-card">
                    <div class="tp-card-header">
                        <div>
                            <h2 class="tp-card-title">Available Topics</h2>
                            <p class="tp-card-subtitle">Select a topic to browse subtopics and start practice papers.</p>
                        </div>
                        @if($topics->total() > 0)
                            <span class="tp-count-chip">
                                Showing {{ $topics->firstItem() }}–{{ $topics->lastItem() }} of {{ $topics->total() }}
                            </span>
                        @endif
                    </div>

                    @if($topics->isEmpty())
                        <div class="tp-empty-box">
                            <div class="tp-empty-icon">
                                <i class="feather icon-search"></i>
                            </div>
                            <h4 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">No Topics Found</h4>
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                We couldn't find any topics matching your current search or filter criteria.
                            </p>
                            @if($hasActiveFilter)
                                <a href="{{ route('student.assessments.topics', $subject->id) }}" class="btn btn-primary btn-sm px-2" style="border-radius: 10px;">
                                    <i class="feather icon-refresh-cw mr-25"></i> Reset Filters
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="tp-table-wrap">
                            <table class="tp-table">
                                <thead>
                                    <tr>
                                        <th>Topic</th>
                                        <th>Progress</th>
                                        <th>Difficulty</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topics as $topic)
                                        @php
                                            $topicUrl = route('student.topics.subtopics', $topic->id);
                                            $pct = $topic->progress_percentage ?? 0;
                                            $isComplete = $topic->status_key === 'completed';
                                            $acc = $topic->average_accuracy;
                                            $accClass = $acc !== null
                                                ? ($acc >= 80 ? 'tp-acc-strong' : ($acc >= 61 ? 'tp-acc-developing' : 'tp-acc-focus'))
                                                : '';
                                            $statusLabel = match ($topic->status_key) {
                                                'completed'   => 'Completed',
                                                'in-progress' => 'In Progress',
                                                default       => 'Not Started',
                                            };
                                        @endphp
                                        <tr class="tp-topic-row" data-href="{{ $topicUrl }}">
                                            <td>
                                                <a href="{{ $topicUrl }}" class="tp-topic-title-link">
                                                    {{ $topic->name }}
                                                </a>
                                                <div class="tp-topic-submeta">
                                                    <span>
                                                        {{ $topic->subtopics_count ?? 0 }} {{ \Illuminate\Support\Str::plural('Subtopic', $topic->subtopics_count ?? 0) }}
                                                    </span>
                                                    @if($acc !== null)
                                                        <span class="tp-acc-pill {{ $accClass }}">Avg {{ $acc }}%</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="tp-prog-cell">
                                                <div class="tp-prog-labels">
                                                    <span>{{ $topic->test_attempted }}/{{ $topic->test_available }} tests</span>
                                                    <span>{{ $pct }}%</span>
                                                </div>
                                                <div class="tp-prog-track" aria-label="{{ $pct }}% completed">
                                                    <div class="tp-prog-fill {{ $isComplete ? 'is-complete' : '' }}" style="width: {{ $pct }}%;"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="tp-diff-group">
                                                    <span class="tp-diff-pill {{ $topic->easy_count > 0 ? 'tp-diff-easy' : 'tp-diff-muted' }}">
                                                        Easy {{ $topic->easy_count }}
                                                    </span>
                                                    <span class="tp-diff-pill {{ $topic->medium_count > 0 ? 'tp-diff-medium' : 'tp-diff-muted' }}">
                                                        Med {{ $topic->medium_count }}
                                                    </span>
                                                    <span class="tp-diff-pill {{ $topic->hard_count > 0 ? 'tp-diff-hard' : 'tp-diff-muted' }}">
                                                        Hard {{ $topic->hard_count }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="tp-status-badge tp-status-{{ $topic->status_key }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ $topicUrl }}" class="tp-go-btn">
                                                    <span>Go To Tests</span>
                                                    <i class="feather icon-arrow-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($topics->hasPages())
                            <div class="tp-pagination-wrap">
                                {{ $topics->links() }}
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Bottom Guidance Banner -->
                <div class="tp-tip-banner">
                    <div class="tp-tip-left">
                        <span class="tp-tip-icon">
                            <i class="feather icon-crosshair font-medium-3"></i>
                        </span>
                        <div>
                            <h4 class="tp-tip-title">Focus on lower-scoring {{ $subject->title }} topics first</h4>
                            <p class="tp-tip-desc">Check your Focus Areas dashboard to spot which topics need extra revision.</p>
                        </div>
                    </div>
                    <a href="{{ route('student.focusareas') }}" class="tp-tip-link">
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
            document.querySelectorAll('.tp-topic-row').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, button')) return;
                    var href = this.getAttribute('data-href');
                    if (href) {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
@endpush
