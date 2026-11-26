@extends('layouts.app')

@section('title', 'Focus Areas - Aspire Learners')

@push('styles')
    <style>
        /* ==========================================================================
           STUDENT FOCUS AREAS - MODERN UI/UX (LIGHT & DARK MODE COMPATIBLE)
           ========================================================================== */
        .fa-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.4rem;
        }

        .fa-page-title-wrap {
            min-width: 0;
        }

        .fa-title-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .fa-page-title {
            color: var(--text-primary, #0b1f51);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .fa-page-subtitle {
            color: var(--text-muted, #64748b);
            font-size: 0.96rem;
            font-weight: 500;
            margin: 0;
        }

        .fa-preview-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            background-color: #e8f1ff;
            color: #1d4ed8;
            border: 1px solid #cfe0ff;
            font-size: 0.76rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        /* Period Dropdown Button */
        .fa-period-btn {
            background-color: var(--card-bg, #ffffff) !important;
            border: 1px solid var(--border-color, #dfe7f2) !important;
            color: var(--text-primary, #1e293b) !important;
            border-radius: 12px !important;
            padding: 9px 16px !important;
            font-size: 0.88rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .fa-period-btn:hover,
        .fa-period-btn:focus {
            border-color: #2477F9 !important;
            box-shadow: 0 4px 14px rgba(36, 119, 249, 0.14) !important;
        }

        .fa-period-btn::after {
            display: none !important;
        }

        .fa-period-menu {
            border-radius: 14px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7) !important;
            padding: 6px;
            min-width: 185px;
        }

        .fa-period-menu .dropdown-item {
            color: var(--text-body, #334155);
            font-size: 0.86rem;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 12px;
        }

        .fa-period-menu .dropdown-item:hover {
            background-color: var(--theme-primary-light, #ebf3ff);
            color: #2477F9;
        }

        .fa-period-menu .dropdown-item.active {
            background-color: #2477F9;
            color: #ffffff;
            font-weight: 600;
        }

        /* Top 4 KPI Summary Cards */
        .fa-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 1.35rem;
        }

        .fa-kpi-card {
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

        .fa-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            border-color: #cbd5e1;
        }

        .fa-kpi-card.is-active {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.14), 0 8px 20px rgba(36, 119, 249, 0.08);
        }

        .fa-kpi-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-strong {
            background: linear-gradient(135deg, #2ecc71 0%, #16a34a 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
        }

        .kpi-developing {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .kpi-focus {
            background: linear-gradient(135deg, #ff4d4f 0%, #ef4444 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .kpi-total {
            background-color: #dcebff;
            color: #2563eb;
        }

        .fa-kpi-meta {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .fa-kpi-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-body, #334155);
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fa-kpi-count {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.15;
            margin: 2px 0;
            font-variant-numeric: tabular-nums;
        }

        .fa-kpi-sub {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Filter & Search Toolbar */
        .fa-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .fa-toolbar-controls {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .fa-filter-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .fa-filter-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted, #475569);
            margin: 0;
            white-space: nowrap;
        }

        .fa-select {
            height: 40px;
            padding: 6px 36px 6px 14px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-primary, #1e293b);
            cursor: pointer;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 11px center;
            background-size: 15px 15px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .fa-select:hover,
        .fa-select:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .fa-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 36px;
            padding: 0 12px;
            border-radius: 999px;
            background-color: #ebf3ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .fa-status-chip-clear {
            background: transparent;
            border: none;
            color: inherit;
            padding: 0;
            line-height: 1;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .fa-search-box {
            position: relative;
            width: 100%;
            max-width: 260px;
            flex-shrink: 0;
        }

        .fa-search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: inline-flex;
        }

        .fa-search-input {
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

        .fa-search-input::placeholder {
            color: #94a3b8;
        }

        .fa-search-input:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .fa-search-clear {
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

        .fa-search-clear:hover {
            color: #ef4444;
        }

        /* Main 2-Column Content Layout */
        .fa-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.72fr) minmax(310px, 1fr);
            gap: 20px;
            align-items: start;
        }

        .fa-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
        }

        .fa-card-header {
            margin-bottom: 14px;
        }

        .fa-card-header-flex {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .fa-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
            line-height: 1.3;
        }

        .fa-card-subtitle {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            margin: 0;
        }

        /* Topic Performance Table */
        .fa-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .fa-perf-table {
            width: 100%;
            border-collapse: collapse;
        }

        .fa-perf-table thead th {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            padding: 10px 10px 12px;
            border-bottom: 1px solid var(--border-subtle, #eef2f7);
            white-space: nowrap;
            text-align: left;
        }

        .fa-perf-table thead th.col-subj {
            text-align: center;
        }

        .fa-perf-table thead th.col-acc {
            text-align: left;
        }

        .fa-perf-table thead th.col-status {
            text-align: right;
        }

        .fa-topic-row {
            border-bottom: 1px solid var(--border-subtle, #f1f5f9);
            transition: background-color 0.16s ease;
            cursor: pointer;
        }

        .fa-topic-row:last-child {
            border-bottom: none;
        }

        .fa-topic-row:hover {
            background-color: #f8fbff;
        }

        .fa-perf-table tbody td {
            padding: 12px 10px;
            vertical-align: middle;
        }

        .fa-topic-link {
            color: var(--text-primary, #1e293b);
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.16s ease;
        }

        .fa-topic-row:hover .fa-topic-link {
            color: #2477F9;
        }

        /* Subject Badges */
        .fa-subj-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 68px;
            padding: 4px 12px;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 700;
            line-height: 1.25;
            white-space: nowrap;
        }

        .subj-english {
            background-color: #fee2e2;
            color: #ef4444;
        }

        .subj-maths {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .subj-vr {
            background-color: #fef3c7;
            color: #d97706;
        }

        .subj-nvr {
            background-color: #ede9fe;
            color: #7c3aed;
        }

        .subj-science {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .subj-default {
            background-color: #e2e8f0;
            color: #475569;
        }

        .fa-acc-val {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .fa-prog-cell {
            min-width: 145px;
            width: 32%;
        }

        .fa-prog-track {
            width: 100%;
            height: 11px;
            border-radius: 999px;
            background-color: #edf2f7;
            overflow: hidden;
        }

        .fa-prog-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 0.5s ease;
        }

        .fill-strong {
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
        }

        .fill-developing {
            background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%);
        }

        .fill-focus {
            background: linear-gradient(90deg, #fb923c 0%, #f97316 55%, #ef4444 100%);
        }

        .fa-status-text {
            font-size: 0.86rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-strong {
            color: #16a34a;
        }

        .status-developing {
            color: #eab308;
        }

        .status-focus {
            color: #ef4444;
        }

        /* Empty States */
        .fa-empty-box {
            text-align: center;
            padding: 2.75rem 1.25rem;
        }

        .fa-empty-icon {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        /* Right Side Stack */
        .fa-side-stack {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* Subject Overview Chart & Legend */
        .fa-chart-box {
            min-height: 190px;
            margin-top: 2px;
        }

        .fa-subject-legend-grid {
            display: grid;
            grid-template-columns: repeat(var(--subj-cols, 3), minmax(0, 1fr));
            gap: 6px;
            text-align: center;
            margin-top: -4px;
            padding-left: 38px;
            padding-right: 4px;
        }

        .fa-subj-legend-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .fa-subj-legend-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-primary, #1e293b);
            line-height: 1.2;
        }

        .fa-subj-legend-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
        }

        /* Areas to Improve Card */
        .fa-view-all-btn {
            background: transparent;
            border: none;
            padding: 2px 0;
            color: #2563eb;
            font-size: 0.86rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            transition: color 0.16s ease, transform 0.16s ease;
            outline: none !important;
        }

        .fa-view-all-btn:hover {
            color: #1d4ed8;
            transform: translateX(2px);
        }

        .fa-improve-list {
            display: flex;
            flex-direction: column;
        }

        .fa-improve-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 4px;
            border-bottom: 1px solid var(--border-subtle, #f1f5f9);
            text-decoration: none !important;
            transition: background-color 0.16s ease, padding 0.16s ease;
            border-radius: 8px;
        }

        .fa-improve-row:last-child {
            border-bottom: none;
        }

        .fa-improve-row:hover {
            background-color: #fff5f5;
            padding-left: 8px;
            padding-right: 8px;
        }

        .fa-improve-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .fa-improve-rank {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: #fee2e2;
            color: #ef4444;
            font-size: 0.8rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .fa-improve-name {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text-primary, #1e293b);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fa-improve-score {
            font-size: 0.92rem;
            font-weight: 700;
            color: #ef4444;
            font-variant-numeric: tabular-nums;
            flex-shrink: 0;
        }

        .fa-improve-empty {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            background-color: #f0fdf4;
            color: #15803d;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 6px;
        }

        /* Keep Going Tip Banner */
        .fa-tip-card {
            background: linear-gradient(135deg, #eef4ff 0%, #e5efff 100%);
            border: 1px solid #d5e5ff;
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            box-shadow: 0 3px 12px rgba(36, 119, 249, 0.06);
        }

        .fa-tip-icon {
            color: #1677ff;
            flex-shrink: 0;
            margin-top: 2px;
            display: inline-flex;
        }

        .fa-tip-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 4px;
        }

        .fa-tip-text {
            font-size: 0.85rem;
            font-weight: 500;
            color: #475569;
            margin: 0;
            line-height: 1.45;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1199.98px) {
            .fa-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .fa-main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .fa-kpi-grid {
                grid-template-columns: 1fr;
            }
            .fa-search-box {
                max-width: 100%;
            }
            .fa-card {
                padding: 18px 16px;
            }
        }

        /* Dark Mode Support */
        body.dark-layout .fa-kpi-card,
        body.dark-layout .fa-card,
        body.dark-layout .fa-period-btn,
        body.dark-layout .fa-period-menu,
        body.dark-layout .fa-select,
        body.dark-layout .fa-search-input {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-layout .fa-page-title,
        body.dark-layout .fa-kpi-count,
        body.dark-layout .fa-card-title,
        body.dark-layout .fa-topic-link,
        body.dark-layout .fa-acc-val,
        body.dark-layout .fa-subj-legend-label,
        body.dark-layout .fa-improve-name,
        body.dark-layout .fa-tip-title,
        body.dark-layout .fa-select,
        body.dark-layout .fa-search-input,
        body.dark-layout .fa-period-btn {
            color: #f8fafc !important;
        }

        body.dark-layout .fa-page-subtitle,
        body.dark-layout .fa-kpi-sub,
        body.dark-layout .fa-filter-label,
        body.dark-layout .fa-card-subtitle,
        body.dark-layout .fa-perf-table thead th,
        body.dark-layout .fa-tip-text {
            color: #94a3b8 !important;
        }

        body.dark-layout .fa-kpi-label {
            color: #cbd5e1 !important;
        }

        body.dark-layout .fa-perf-table thead th,
        body.dark-layout .fa-topic-row,
        body.dark-layout .fa-improve-row {
            border-color: #334155 !important;
        }

        body.dark-layout .fa-topic-row:hover {
            background-color: rgba(36, 119, 249, 0.08);
        }

        body.dark-layout .fa-improve-row:hover {
            background-color: rgba(239, 68, 68, 0.1);
        }

        body.dark-layout .fa-prog-track {
            background-color: #0f172a;
        }

        body.dark-layout .kpi-total {
            background-color: rgba(37, 99, 235, 0.22);
            color: #93c5fd;
        }

        body.dark-layout .fa-preview-badge {
            background-color: rgba(37, 99, 235, 0.2);
            color: #93c5fd;
            border-color: rgba(59, 130, 246, 0.35);
        }

        body.dark-layout .fa-tip-card {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16) 0%, rgba(30, 41, 59, 0.95) 100%);
            border-color: #334155;
        }

        body.dark-layout .fa-improve-empty {
            background-color: rgba(34, 197, 94, 0.15);
            color: #86efac;
        }

        body.dark-layout .subj-english {
            background-color: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        body.dark-layout .subj-maths {
            background-color: rgba(37, 99, 235, 0.2);
            color: #93c5fd;
        }

        body.dark-layout .subj-vr {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fde68a;
        }

        body.dark-layout .subj-nvr {
            background-color: rgba(139, 92, 246, 0.2);
            color: #c4b5fd;
        }

        body.dark-layout .subj-science {
            background-color: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }

        body.dark-layout .fa-improve-rank {
            background-color: rgba(239, 68, 68, 0.22);
            color: #fca5a5;
        }

        body.dark-layout .fa-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        }
    </style>
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <!-- Page Header & Time Period Selector -->
                <div class="fa-page-header">
                    <div class="fa-page-title-wrap">
                        <div class="fa-title-row">
                            <h1 class="fa-page-title">Focus Areas</h1>
                            @if($isSamplePreview)
                                <span class="fa-preview-badge">
                                    <i class="feather icon-eye"></i>
                                    <span>Sample Preview &middot; Complete tests to see your live scores</span>
                                </span>
                            @endif
                        </div>
                        <p class="fa-page-subtitle">Identify your strengths and areas to improve.</p>
                    </div>

                    <div class="dropdown">
                        <button class="btn fa-period-btn dropdown-toggle"
                                type="button"
                                id="focusPeriodDropdown"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.78;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>{{ $selectedPeriodLabel }}</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.72;">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right fa-period-menu shadow-lg border-0" aria-labelledby="focusPeriodDropdown">
                            @foreach($periodLabels as $key => $label)
                                <a class="dropdown-item {{ $period === $key ? 'active' : '' }}"
                                   href="{{ route('student.focusareas', ['period' => $key]) }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Row: 4 KPI Summary Cards -->
                <div class="fa-kpi-grid">
                    <!-- Strong Areas -->
                    <button type="button" class="fa-kpi-card" data-status-filter="strong" title="Filter Strong Areas (80%+)">
                        <div class="fa-kpi-icon kpi-strong">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.5l2.85 5.78 6.38.93-4.61 4.5 1.09 6.35L12 17.06l-5.71 3 1.09-6.35-4.61-4.5 6.38-.93L12 2.5z"/>
                            </svg>
                        </div>
                        <div class="fa-kpi-meta">
                            <span class="fa-kpi-label">Strong Areas</span>
                            <span class="fa-kpi-count">{{ $strongCount }}</span>
                            <span class="fa-kpi-sub">Keep it up!</span>
                        </div>
                    </button>

                    <!-- Developing Areas -->
                    <button type="button" class="fa-kpi-card" data-status-filter="developing" title="Filter Developing Areas (61% - 79%)">
                        <div class="fa-kpi-icon kpi-developing">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="4" y="13" width="4" height="7" rx="1.2"/>
                                <rect x="10" y="9" width="4" height="11" rx="1.2"/>
                                <rect x="16" y="4" width="4" height="16" rx="1.2"/>
                            </svg>
                        </div>
                        <div class="fa-kpi-meta">
                            <span class="fa-kpi-label">Developing Areas</span>
                            <span class="fa-kpi-count">{{ $developingCount }}</span>
                            <span class="fa-kpi-sub">Getting better</span>
                        </div>
                    </button>

                    <!-- Areas to Improve -->
                    <button type="button" class="fa-kpi-card" data-status-filter="focus" title="Filter Areas to Improve (60% & below)">
                        <div class="fa-kpi-icon kpi-focus">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="10.4" y="4.5" width="3.2" height="10" rx="1.6"/>
                                <circle cx="12" cy="18.2" r="1.8"/>
                            </svg>
                        </div>
                        <div class="fa-kpi-meta">
                            <span class="fa-kpi-label">Areas to Improve</span>
                            <span class="fa-kpi-count">{{ $focusCount }}</span>
                            <span class="fa-kpi-sub">Need more practice</span>
                        </div>
                    </button>

                    <!-- Total Topics -->
                    <button type="button" class="fa-kpi-card" data-status-filter="all" title="Show All Topics">
                        <div class="fa-kpi-icon kpi-total">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="4.5"/>
                                <path d="M10 12.2l1.5 1.5L16.5 8.5"/>
                            </svg>
                        </div>
                        <div class="fa-kpi-meta">
                            <span class="fa-kpi-label">Total Topics</span>
                            <span class="fa-kpi-count">{{ $totalTopicsCount }}</span>
                            <span class="fa-kpi-sub">Across all subjects</span>
                        </div>
                    </button>
                </div>

                <!-- Toolbar: Subject Filter, Sort By & Search Topics -->
                <div class="fa-toolbar">
                    <div class="fa-toolbar-controls">
                        <div class="fa-filter-group">
                            <label for="subjectFilter" class="fa-filter-label">Subject:</label>
                            <select id="subjectFilter" class="fa-select" aria-label="Filter by subject">
                                <option value="all">All Subjects</option>
                                @foreach($subjectOptions as $opt)
                                    <option value="{{ $opt['key'] }}">{{ $opt['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="fa-filter-group">
                            <label for="sortFilter" class="fa-filter-label">Sort by:</label>
                            <select id="sortFilter" class="fa-select" aria-label="Sort topics">
                                <option value="accuracy_desc">Accuracy (High to Low)</option>
                                <option value="accuracy_asc">Accuracy (Low to High)</option>
                                <option value="name_asc">Topic Name (A to Z)</option>
                                <option value="name_desc">Topic Name (Z to A)</option>
                            </select>
                        </div>

                        <span class="fa-status-chip d-none" id="activeStatusChip">
                            <span id="activeStatusLabel">Showing: Focus</span>
                            <button type="button" class="fa-status-chip-clear" id="clearStatusChipBtn" aria-label="Clear status filter">
                                <i class="feather icon-x"></i>
                            </button>
                        </span>
                    </div>

                    <div class="fa-search-box">
                        <span class="fa-search-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </span>
                        <input type="text"
                               id="topicSearchInput"
                               class="fa-search-input"
                               placeholder="Search topics..."
                               autocomplete="off"
                               aria-label="Search topics">
                        <button type="button" id="clearTopicSearchBtn" class="fa-search-clear d-none" aria-label="Clear search">
                            <i class="feather icon-x"></i>
                        </button>
                    </div>
                </div>

                <!-- Main 2-Column Content Grid -->
                <div class="fa-main-grid">

                    <!-- Left Column: Topic Performance Table -->
                    <div class="fa-card">
                        <div class="fa-card-header">
                            <h2 class="fa-card-title">Topic Performance</h2>
                            <p class="fa-card-subtitle">Your accuracy in each topic.</p>
                        </div>

                        @if($topicItems->isEmpty())
                            <div class="fa-empty-box">
                                <div class="fa-empty-icon">
                                    <i class="feather icon-calendar" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">
                                    No topic attempts in {{ strtolower($selectedPeriodLabel) }}
                                </h4>
                                <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                    Try switching the time range to All Time or complete a practice test to see topic accuracy here.
                                </p>
                                <div class="d-inline-flex flex-wrap justify-content-center" style="gap: 10px;">
                                    @if($period !== 'all')
                                        <a href="{{ route('student.focusareas', ['period' => 'all']) }}" class="btn btn-primary btn-sm px-2" style="border-radius: 10px;">
                                            View All Time
                                        </a>
                                    @endif
                                    <a href="{{ route('student.assessments') }}" class="btn btn-outline-primary btn-sm px-2" style="border-radius: 10px;">
                                        Browse Assessments
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="fa-table-wrap">
                                <table class="fa-perf-table">
                                    <thead>
                                        <tr>
                                            <th>Topic</th>
                                            <th class="col-subj">Subject</th>
                                            <th class="col-acc">Accuracy</th>
                                            <th>Performance</th>
                                            <th class="col-status"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="topicPerformanceBody">
                                        @foreach($topicItems as $index => $topic)
                                            @php
                                                $subjClass = in_array($topic['subject_key'], ['english', 'maths', 'vr', 'nvr', 'science'], true)
                                                    ? 'subj-' . $topic['subject_key']
                                                    : 'subj-default';
                                            @endphp
                                            <tr class="fa-topic-row"
                                                data-topic-name="{{ strtolower($topic['name']) }}"
                                                data-subject="{{ $topic['subject_key'] }}"
                                                data-accuracy="{{ $topic['accuracy'] }}"
                                                data-status="{{ $topic['status'] }}"
                                                data-href="{{ $topic['url'] }}"
                                                data-index="{{ $index }}">
                                                <td>
                                                    <a href="{{ $topic['url'] }}" class="fa-topic-link">
                                                        <span>{{ $topic['name'] }}</span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fa-subj-pill {{ $subjClass }}">{{ $topic['subject_badge'] }}</span>
                                                </td>
                                                <td>
                                                    <span class="fa-acc-val">{{ $topic['accuracy'] }}%</span>
                                                </td>
                                                <td class="fa-prog-cell">
                                                    <div class="fa-prog-track" aria-label="{{ $topic['accuracy'] }}% accuracy">
                                                        <div class="fa-prog-fill fill-{{ $topic['status'] }}" style="width: {{ $topic['accuracy'] }}%;"></div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <span class="fa-status-text status-{{ $topic['status'] }}">{{ $topic['status_label'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Empty Client-Side Filter State -->
                            <div class="fa-empty-box d-none" id="noMatchFilterState">
                                <div class="fa-empty-icon">
                                    <i class="feather icon-search" style="font-size: 1.45rem;"></i>
                                </div>
                                <h5 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">No matching topics found</h5>
                                <p class="text-muted mb-2" style="font-size: 0.88rem;">
                                    Try clearing your search or changing the subject/status filter.
                                </p>
                                <button type="button" class="btn btn-primary btn-sm px-2" id="resetFiltersBtn" style="border-radius: 10px;">
                                    Reset Filters
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Subject Overview, Areas to Improve & Keep Going -->
                    <div class="fa-side-stack">

                        <!-- Subject Overview Card -->
                        <div class="fa-card">
                            <div class="fa-card-header mb-1">
                                <h2 class="fa-card-title">Subject Overview</h2>
                                <p class="fa-card-subtitle">Average accuracy by subject.</p>
                            </div>

                            <div id="subjectOverviewChart" class="fa-chart-box"></div>

                            @if(!empty($subjectOverview))
                                <div class="fa-subject-legend-grid" id="subjectLegendGrid" style="--subj-cols: {{ count($subjectOverview) }};">
                                    @foreach($subjectOverview as $subj)
                                        <div class="fa-subj-legend-item">
                                            <span class="fa-subj-legend-label">{{ $subj['title'] }}</span>
                                            <span class="fa-subj-legend-icon" style="color: {{ $subj['color'] }};" title="{{ $subj['title'] }}">
                                                @if($subj['icon'] === 'book-open')
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M11.2 5.2C9.5 4.2 7.1 3.7 4 4a1 1 0 0 0-.9 1v13.2a1 1 0 0 0 1.1 1c2.8-.3 5.2.3 6.9 1.3V5.2zm1.6 0v15.3c1.7-1 4.1-1.6 6.9-1.3a1 1 0 0 0 1.1-1V5a1 1 0 0 0-.9-1c-3.1-.3-5.5.2-7.1 1.2z"/>
                                                    </svg>
                                                @elseif($subj['icon'] === 'calculator')
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm1.5 3.5v3h9v-3h-9zm1 5.5a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm-7 4a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4zm3.5 0a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4z"/>
                                                    </svg>
                                                @elseif($subj['icon'] === 'brain')
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04z" fill="currentColor" fill-opacity="0.22"/>
                                                        <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04z" fill="currentColor" fill-opacity="0.22"/>
                                                    </svg>
                                                @else
                                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                                        <polyline points="2 17 12 22 22 17"/>
                                                        <polyline points="2 12 12 17 22 12"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Areas to Improve Card -->
                        <div class="fa-card">
                            <div class="fa-card-header-flex">
                                <div>
                                    <h2 class="fa-card-title">Areas to Improve</h2>
                                    <p class="fa-card-subtitle">Topics to practice more.</p>
                                </div>
                                @if($focusCount > 0)
                                    <button type="button" class="fa-view-all-btn" id="viewAllImproveBtn">
                                        <span>View All</span>
                                        <span aria-hidden="true">&rarr;</span>
                                    </button>
                                @endif
                            </div>

                            @if($areasToImprove->isNotEmpty())
                                <div class="fa-improve-list">
                                    @foreach($areasToImprove as $idx => $item)
                                        <a href="{{ $item['url'] }}" class="fa-improve-row">
                                            <div class="fa-improve-left">
                                                <span class="fa-improve-rank">{{ $idx + 1 }}</span>
                                                <span class="fa-improve-name">{{ $item['name'] }}</span>
                                            </div>
                                            <span class="fa-improve-score">{{ $item['accuracy'] }}%</span>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="fa-improve-empty">
                                    <i class="feather icon-check-circle"></i>
                                    <span>All evaluated topics are above 60% right now!</span>
                                </div>
                            @endif
                        </div>

                        <!-- Keep Going! Tip Banner -->
                        <div class="fa-tip-card">
                            <span class="fa-tip-icon" aria-hidden="true">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 21h6v-1.5H9V21zm3-19a7 7 0 0 0-4.2 12.6c.7.54 1.2 1.38 1.35 2.4h5.7c.15-1.02.65-1.86 1.35-2.4A7 7 0 0 0 12 2zm-2 16h4v-1h-4v1z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="fa-tip-title">Keep Going!</h3>
                                <p class="fa-tip-text">
                                    Focus on the highlighted areas and try more practice tests to improve your scores.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var isDark = document.documentElement.getAttribute('data-theme') === 'dark' || document.body.classList.contains('dark-layout');
            var labelColor = isDark ? '#94a3b8' : '#64748b';
            var gridColor = isDark ? '#334155' : '#eef2f7';
            var valueTextColor = isDark ? '#f8fafc' : '#0b1f51';

            // ---------------------------------------------------------------------
            // 1. Subject Overview Column Chart (ApexCharts)
            // ---------------------------------------------------------------------
            var subjectsData = @json($subjectOverview);
            var chartEl = document.querySelector('#subjectOverviewChart');
            var legendGrid = document.querySelector('#subjectLegendGrid');

            function syncLegendAlignment() {
                if (!chartEl || !legendGrid) return;
                var gridRectEl = chartEl.querySelector('.apexcharts-grid');
                if (!gridRectEl) return;
                var containerRect = chartEl.getBoundingClientRect();
                var plotRect = gridRectEl.getBoundingClientRect();
                if (plotRect.width > 0 && containerRect.width > 0) {
                    var leftOffset = Math.max(0, Math.round(plotRect.left - containerRect.left));
                    legendGrid.style.paddingLeft = leftOffset + 'px';
                    legendGrid.style.paddingRight = Math.max(0, Math.round(containerRect.right - plotRect.right)) + 'px';
                }
            }

            if (chartEl && typeof ApexCharts !== 'undefined' && Array.isArray(subjectsData) && subjectsData.length > 0) {
                var categories = subjectsData.map(function (s) { return s.title; });
                var scores = subjectsData.map(function (s) { return s.score; });
                var colors = subjectsData.map(function (s) { return s.color; });
                var gradientTargets = subjectsData.map(function (s) { return s.color_light || s.color; });

                var subjectChartOptions = {
                    chart: {
                        type: 'bar',
                        height: 195,
                        toolbar: { show: false },
                        parentHeightOffset: 0,
                        fontFamily: 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                        events: {
                            mounted: function () { setTimeout(syncLegendAlignment, 30); },
                            updated: function () { setTimeout(syncLegendAlignment, 30); }
                        }
                    },
                    series: [{
                        name: 'Average Accuracy',
                        data: scores
                    }],
                    colors: colors,
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            shadeIntensity: 0.25,
                            gradientToColors: gradientTargets,
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 0.92,
                            stops: [0, 100]
                        }
                    },
                    plotOptions: {
                        bar: {
                            distributed: true,
                            borderRadius: 7,
                            borderRadiusApplication: 'end',
                            columnWidth: subjectsData.length <= 3 ? '52%' : '60%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) { return val + '%'; },
                        offsetY: -22,
                        style: {
                            fontSize: '13px',
                            fontWeight: '700',
                            colors: [valueTextColor]
                        }
                    },
                    legend: { show: false },
                    xaxis: {
                        categories: categories,
                        labels: { show: false },
                        axisBorder: { show: true, color: gridColor },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        min: 0,
                        max: 100,
                        tickAmount: 4,
                        labels: {
                            formatter: function (val) { return Math.round(val) + '%'; },
                            style: {
                                colors: labelColor,
                                fontSize: '11px',
                                fontWeight: 500
                            }
                        }
                    },
                    grid: {
                        borderColor: gridColor,
                        strokeDashArray: 4,
                        padding: {
                            top: 12,
                            bottom: -4,
                            left: 6,
                            right: 6
                        },
                        yaxis: { lines: { show: true } },
                        xaxis: { lines: { show: false } }
                    },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
                        y: {
                            formatter: function (val) { return val + '% Average Accuracy'; }
                        }
                    }
                };

                var chart = new ApexCharts(chartEl, subjectChartOptions);
                chart.render();
                window.addEventListener('resize', function () {
                    setTimeout(syncLegendAlignment, 60);
                });
            }

            // ---------------------------------------------------------------------
            // 2. Topic Performance Filtering, Sorting, Search & Interactive Links
            // ---------------------------------------------------------------------
            var tbody = document.getElementById('topicPerformanceBody');
            var subjectSelect = document.getElementById('subjectFilter');
            var sortSelect = document.getElementById('sortFilter');
            var searchInput = document.getElementById('topicSearchInput');
            var clearSearchBtn = document.getElementById('clearTopicSearchBtn');
            var resetFiltersBtn = document.getElementById('resetFiltersBtn');
            var noMatchState = document.getElementById('noMatchFilterState');
            var kpiCards = document.querySelectorAll('.fa-kpi-card[data-status-filter]');
            var statusChip = document.getElementById('activeStatusChip');
            var statusChipLabel = document.getElementById('activeStatusLabel');
            var clearStatusChipBtn = document.getElementById('clearStatusChipBtn');
            var viewAllImproveBtn = document.getElementById('viewAllImproveBtn');

            var activeStatus = 'all';
            var statusNames = {
                strong: 'Strong Areas',
                developing: 'Developing Areas',
                focus: 'Areas to Improve'
            };

            function updateKpiStates() {
                kpiCards.forEach(function (card) {
                    var filterKey = card.getAttribute('data-status-filter');
                    var shouldHighlight = (activeStatus !== 'all' && filterKey === activeStatus);
                    card.classList.toggle('is-active', shouldHighlight);
                });

                if (statusChip && statusChipLabel) {
                    if (activeStatus !== 'all') {
                        statusChipLabel.textContent = 'Showing: ' + (statusNames[activeStatus] || activeStatus);
                        statusChip.classList.remove('d-none');
                    } else {
                        statusChip.classList.add('d-none');
                    }
                }
            }

            function applyTableFiltersAndSort() {
                if (!tbody) return;

                var rows = Array.prototype.slice.call(tbody.querySelectorAll('.fa-topic-row'));
                var selectedSubject = subjectSelect ? subjectSelect.value : 'all';
                var selectedSort = sortSelect ? sortSelect.value : 'accuracy_desc';
                var query = (searchInput ? searchInput.value : '').trim().toLowerCase();

                if (clearSearchBtn) {
                    clearSearchBtn.classList.toggle('d-none', query.length === 0);
                }

                rows.sort(function (a, b) {
                    var accA = parseInt(a.getAttribute('data-accuracy') || '0', 10);
                    var accB = parseInt(b.getAttribute('data-accuracy') || '0', 10);
                    var nameA = a.getAttribute('data-topic-name') || '';
                    var nameB = b.getAttribute('data-topic-name') || '';

                    if (selectedSort === 'accuracy_asc') {
                        return accA !== accB ? accA - accB : nameA.localeCompare(nameB);
                    }
                    if (selectedSort === 'name_asc') {
                        return nameA.localeCompare(nameB);
                    }
                    if (selectedSort === 'name_desc') {
                        return nameB.localeCompare(nameA);
                    }
                    return accB !== accA ? accB - accA : nameA.localeCompare(nameB);
                });

                var visibleCount = 0;
                rows.forEach(function (row) {
                    tbody.appendChild(row);
                    var rowSubject = row.getAttribute('data-subject') || '';
                    var rowStatus = row.getAttribute('data-status') || '';
                    var rowName = row.getAttribute('data-topic-name') || '';

                    var matchesSubject = (selectedSubject === 'all') || (rowSubject === selectedSubject);
                    var matchesStatus = (activeStatus === 'all') || (rowStatus === activeStatus);
                    var matchesQuery = !query || rowName.indexOf(query) !== -1;

                    if (matchesSubject && matchesStatus && matchesQuery) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (noMatchState) {
                    noMatchState.classList.toggle('d-none', !(visibleCount === 0 && rows.length > 0));
                }

                updateKpiStates();
            }

            if (subjectSelect) {
                subjectSelect.addEventListener('change', applyTableFiltersAndSort);
            }

            if (sortSelect) {
                sortSelect.addEventListener('change', applyTableFiltersAndSort);
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyTableFiltersAndSort);
            }

            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function () {
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.focus();
                    }
                    applyTableFiltersAndSort();
                });
            }

            kpiCards.forEach(function (card) {
                card.addEventListener('click', function () {
                    var targetStatus = this.getAttribute('data-status-filter') || 'all';
                    activeStatus = (targetStatus === activeStatus) ? 'all' : targetStatus;
                    applyTableFiltersAndSort();
                });
            });

            if (clearStatusChipBtn) {
                clearStatusChipBtn.addEventListener('click', function () {
                    activeStatus = 'all';
                    applyTableFiltersAndSort();
                });
            }

            if (viewAllImproveBtn) {
                viewAllImproveBtn.addEventListener('click', function () {
                    activeStatus = 'focus';
                    if (sortSelect) {
                        sortSelect.value = 'accuracy_asc';
                    }
                    applyTableFiltersAndSort();
                    var tableCard = tbody ? tbody.closest('.fa-card') : null;
                    if (tableCard) {
                        tableCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            }

            if (resetFiltersBtn) {
                resetFiltersBtn.addEventListener('click', function () {
                    activeStatus = 'all';
                    if (subjectSelect) subjectSelect.value = 'all';
                    if (sortSelect) sortSelect.value = 'accuracy_desc';
                    if (searchInput) searchInput.value = '';
                    applyTableFiltersAndSort();
                });
            }

            // Clicking anywhere on a topic row opens its practice tests
            if (tbody) {
                tbody.addEventListener('click', function (e) {
                    if (e.target.closest('a')) return;
                    var row = e.target.closest('.fa-topic-row');
                    if (row) {
                        var href = row.getAttribute('data-href');
                        if (href) {
                            window.location.href = href;
                        }
                    }
                });
            }
        });
    </script>
@endpush
