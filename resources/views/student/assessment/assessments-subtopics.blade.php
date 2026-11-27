@extends('layouts.app')

@section('title', $topic->name . ' - Subtopics & Tests')

@push('styles')
    <style>
        /* ==========================================================================
           STUDENT TOPIC SUBTOPICS & TESTS - MODERN UI/UX (LIGHT & DARK MODE)
           ========================================================================== */
        .st-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin-bottom: 0.85rem;
        }

        .st-breadcrumb a {
            color: #2477F9;
            text-decoration: none;
        }

        .st-breadcrumb a:hover {
            text-decoration: underline;
        }

        .st-breadcrumb-sep {
            opacity: 0.55;
        }

        .st-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.4rem;
        }

        .st-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .st-subject-avatar {
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

        .st-subject-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
        }

        .st-title-meta-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 4px;
        }

        .st-page-title {
            color: var(--text-primary, #0b1f51);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0;
        }

        .st-subj-tag {
            padding: 4px 11px;
            border-radius: 999px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            color: var(--subj-soft-text, #1757C1);
            border: 1px solid var(--subj-soft-border, #bfdbfe);
            font-size: 0.76rem;
            font-weight: 700;
        }

        .st-page-subtitle {
            color: var(--text-muted, #64748b);
            font-size: 0.95rem;
            font-weight: 500;
            margin: 0;
        }

        .st-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .st-header-pill-btn {
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

        .st-header-pill-btn:hover {
            border-color: #2477F9;
            color: #2477F9 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(36, 119, 249, 0.14);
        }

        /* Top 4 KPI Summary Cards */
        .st-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 1.35rem;
        }

        .st-kpi-card {
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

        .st-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        }

        .st-kpi-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .st-kpi-icon.kpi-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2477F9 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.25);
        }

        .st-kpi-icon.kpi-soft-blue {
            background-color: #dcebff;
            color: #2563eb;
        }

        .st-kpi-icon.kpi-green {
            background: linear-gradient(135deg, #2ecc71 0%, #16a34a 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
        }

        .st-kpi-icon.kpi-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .st-kpi-meta {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .st-kpi-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-body, #334155);
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .st-kpi-count {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.15;
            margin: 2px 0;
            font-variant-numeric: tabular-nums;
        }

        .st-kpi-sub {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Modern Pill Navigation Tabs */
        .st-tabs-nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            border-bottom: none;
            margin-bottom: 1.25rem;
        }

        .st-tabs-nav .nav-link {
            border: 1px solid transparent !important;
            background-color: #e8eef7;
            color: #334155 !important;
            font-size: 0.88rem;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 999px !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.18s ease;
        }

        .st-tabs-nav .nav-link:hover {
            background-color: #dce5f3;
            color: #0b1f51 !important;
        }

        .st-tabs-nav .nav-link.active {
            background: linear-gradient(135deg, #2477F9 0%, #1757C1 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.28);
        }

        .st-tab-badge {
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
            background-color: rgba(15, 23, 42, 0.08);
            color: inherit;
        }

        .st-tabs-nav .nav-link.active .st-tab-badge {
            background-color: rgba(255, 255, 255, 0.24);
            color: #ffffff;
        }

        /* Filter & Search Toolbar */
        .st-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .st-toolbar-left {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }

        .st-toolbar-right {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .st-filter-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .st-filter-label {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin: 0;
            white-space: nowrap;
        }

        .st-select {
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

        .st-select:hover,
        .st-select:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .st-search-box {
            position: relative;
            width: 250px;
            max-width: 100%;
        }

        .st-search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: inline-flex;
        }

        .st-search-input {
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

        .st-search-input::placeholder {
            color: #94a3b8;
        }

        .st-search-input:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .st-search-clear {
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

        .st-search-clear:hover {
            color: #ef4444;
        }

        .st-action-btn-light {
            height: 40px;
            padding: 0 14px;
            border-radius: 10px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            color: var(--text-primary, #1e293b) !important;
            font-size: 0.84rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none !important;
            transition: all 0.18s ease;
        }

        .st-action-btn-light:hover {
            border-color: #2477F9;
            color: #2477F9 !important;
        }

        /* Subtopic Accordion Cards */
        .st-subtopics-stack {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 1.5rem;
        }

        .st-subtopic-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
            overflow: hidden;
        }

        .st-subtopic-header {
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.16s ease;
        }

        .st-subtopic-header:hover {
            background-color: #f8fbff;
        }

        .st-subtopic-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .st-subtopic-accent {
            width: 5px;
            height: 28px;
            border-radius: 999px;
            background: var(--subj-gradient, linear-gradient(180deg, #2477F9, #1757C1));
            flex-shrink: 0;
        }

        .st-subtopic-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 3px;
            line-height: 1.25;
        }

        .st-subtopic-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
        }

        .st-mini-track {
            width: 80px;
            height: 7px;
            border-radius: 999px;
            background-color: #edf2f7;
            overflow: hidden;
            display: inline-block;
        }

        .st-mini-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .st-subtopic-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .st-papers-pill {
            padding: 5px 12px;
            border-radius: 999px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            color: var(--subj-soft-text, #1757C1);
            font-size: 0.78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .st-chevron-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.22s ease, background-color 0.2s ease;
        }

        .st-subtopic-header[aria-expanded="false"] .st-chevron-circle {
            transform: rotate(-90deg);
        }

        .st-subtopic-body {
            border-top: 1px solid var(--border-subtle, #eef2f7);
        }

        /* Paper Rows */
        .st-paper-row {
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border-bottom: 1px solid var(--border-subtle, #f1f5f9);
            transition: background-color 0.16s ease;
        }

        .st-paper-row:last-child {
            border-bottom: none;
        }

        .st-paper-row:hover {
            background-color: #f8fbff;
        }

        .st-paper-info {
            min-width: 0;
            flex: 1;
        }

        .st-paper-title-line {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 6px;
        }

        .st-paper-title {
            font-size: 0.98rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0;
        }

        .st-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        .st-pill-easy {
            background-color: #dcfce7;
            color: #15803d;
        }

        .st-pill-medium {
            background-color: #fef3c7;
            color: #b45309;
        }

        .st-pill-hard {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .st-pill-neutral {
            background-color: #f1f5f9;
            color: #475569;
        }

        .st-pill-paused {
            background-color: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        .st-pill-progress {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .st-pill-done {
            background-color: #f0fdf4;
            color: #15803d;
        }

        .st-pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: currentColor;
            display: inline-block;
        }

        .st-paper-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
            font-size: 0.82rem;
            color: var(--text-muted, #64748b);
        }

        .st-paper-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .st-paper-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        /* Start / Resume Buttons */
        .st-btn-start {
            padding: 8px 20px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2477F9 0%, #1757C1 100%);
            color: #ffffff !important;
            border: none;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.25);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .st-btn-start:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(36, 119, 249, 0.35);
        }

        .st-btn-resume {
            padding: 8px 20px;
            border-radius: 10px;
            border: none;
            color: #ffffff !important;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .st-btn-resume.is-paused {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.28);
        }

        .st-btn-resume.is-active {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.28);
        }

        .st-btn-resume:hover {
            transform: translateY(-1px);
        }

        /* Attempt History Card & Table */
        .st-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
            margin-bottom: 1.5rem;
        }

        .st-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .st-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
        }

        .st-card-subtitle {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            margin: 0;
        }

        .st-count-chip {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            background-color: #f1f5f9;
            padding: 5px 12px;
            border-radius: 999px;
        }

        .st-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .st-table {
            width: 100%;
            border-collapse: collapse;
        }

        .st-table thead th {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            padding: 10px 12px 12px;
            border-bottom: 1px solid var(--border-subtle, #eef2f7);
            white-space: nowrap;
            text-align: left;
        }

        .st-table thead th.text-right {
            text-align: right;
        }

        .st-table tbody tr {
            border-bottom: 1px solid var(--border-subtle, #f1f5f9);
            transition: background-color 0.16s ease;
        }

        .st-table tbody tr:last-child {
            border-bottom: none;
        }

        .st-table tbody tr:hover {
            background-color: #f8fbff;
        }

        .st-table tbody td {
            padding: 13px 12px;
            vertical-align: middle;
            font-size: 0.88rem;
            color: var(--text-body, #334155);
        }

        .st-acc-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 130px;
        }

        .st-acc-val {
            font-weight: 700;
            font-size: 0.86rem;
            min-width: 36px;
            font-variant-numeric: tabular-nums;
        }

        .st-acc-track {
            flex: 1;
            height: 8px;
            border-radius: 999px;
            background-color: #edf2f7;
            overflow: hidden;
        }

        .st-acc-fill {
            height: 100%;
            border-radius: 999px;
        }

        .fill-strong {
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .fill-developing {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
        }

        .fill-focus {
            background: linear-gradient(90deg, #fb923c, #ef4444);
        }

        .st-btn-outline {
            padding: 6px 14px;
            border-radius: 9px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            color: var(--subj-soft-text, #1757C1) !important;
            border: 1px solid var(--subj-soft-border, #bfdbfe);
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            white-space: nowrap;
            transition: all 0.16s ease;
        }

        .st-btn-outline:hover {
            background-color: #2477F9;
            color: #ffffff !important;
            border-color: #2477F9;
        }

        /* Mobile Attempt Cards */
        .st-mobile-attempt-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
        }

        /* Pagination */
        .st-pagination-wrap {
            display: flex;
            justify-content: center;
            margin-top: 1.25rem;
        }

        .st-pagination-wrap .pagination {
            margin-bottom: 0;
            gap: 4px;
        }

        .st-pagination-wrap .page-link {
            border-radius: 8px !important;
            border: 1px solid var(--border-color, #dfe7f2);
            color: var(--text-primary, #1e293b);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .st-pagination-wrap .page-item.active .page-link {
            background-color: #2477F9;
            border-color: #2477F9;
            color: #ffffff;
        }

        /* Empty States */
        .st-empty-box {
            text-align: center;
            padding: 2.75rem 1.25rem;
        }

        .st-empty-icon {
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
        .st-tip-banner {
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

        .st-tip-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .st-tip-icon {
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

        .st-tip-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
        }

        .st-tip-desc {
            font-size: 0.85rem;
            color: #475569;
            margin: 0;
        }

        .st-tip-link {
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
            .st-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .st-paper-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .st-paper-actions {
                width: 100%;
            }
            .st-paper-actions form,
            .st-btn-start,
            .st-btn-resume {
                width: 100%;
                justify-content: center;
            }
            .st-search-box {
                width: 100%;
            }
            .st-card {
                padding: 18px 16px;
            }
        }

        @media (max-width: 575.98px) {
            .st-kpi-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dark Mode Support */
        body.dark-layout .st-kpi-card,
        body.dark-layout .st-subtopic-card,
        body.dark-layout .st-card,
        body.dark-layout .st-mobile-attempt-card,
        body.dark-layout .st-header-pill-btn,
        body.dark-layout .st-select,
        body.dark-layout .st-search-input,
        body.dark-layout .st-action-btn-light {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-layout .st-page-title,
        body.dark-layout .st-kpi-count,
        body.dark-layout .st-subtopic-title,
        body.dark-layout .st-paper-title,
        body.dark-layout .st-card-title,
        body.dark-layout .st-tip-title,
        body.dark-layout .st-header-pill-btn,
        body.dark-layout .st-select,
        body.dark-layout .st-search-input,
        body.dark-layout .st-action-btn-light {
            color: #f8fafc !important;
        }

        body.dark-layout .st-page-subtitle,
        body.dark-layout .st-kpi-sub,
        body.dark-layout .st-subtopic-meta,
        body.dark-layout .st-paper-meta,
        body.dark-layout .st-card-subtitle,
        body.dark-layout .st-filter-label,
        body.dark-layout .st-table thead th,
        body.dark-layout .st-tip-desc {
            color: #94a3b8 !important;
        }

        body.dark-layout .st-kpi-label,
        body.dark-layout .st-table tbody td {
            color: #cbd5e1 !important;
        }

        body.dark-layout .st-subtopic-body,
        body.dark-layout .st-paper-row,
        body.dark-layout .st-table thead th,
        body.dark-layout .st-table tbody tr {
            border-color: #334155 !important;
        }

        body.dark-layout .st-subtopic-header:hover,
        body.dark-layout .st-paper-row:hover,
        body.dark-layout .st-table tbody tr:hover {
            background-color: rgba(36, 119, 249, 0.08);
        }

        body.dark-layout .st-mini-track,
        body.dark-layout .st-acc-track,
        body.dark-layout .st-chevron-circle,
        body.dark-layout .st-count-chip {
            background-color: #0f172a;
            color: #94a3b8;
        }

        body.dark-layout .st-tabs-nav .nav-link {
            background-color: #1e293b;
            color: #cbd5e1 !important;
            border-color: #334155 !important;
        }

        body.dark-layout .st-tabs-nav .nav-link:hover {
            background-color: #334155;
            color: #ffffff !important;
        }

        body.dark-layout .st-tabs-nav .nav-link.active {
            border-color: transparent !important;
        }

        body.dark-layout .st-kpi-icon.kpi-soft-blue {
            background-color: rgba(37, 99, 235, 0.22);
            color: #93c5fd;
        }

        body.dark-layout .st-pill-easy,
        body.dark-layout .st-pill-done {
            background-color: rgba(34, 197, 94, 0.2);
            color: #86efac;
        }

        body.dark-layout .st-pill-medium {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fde68a;
        }

        body.dark-layout .st-pill-hard {
            background-color: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        body.dark-layout .st-pill-paused {
            background-color: rgba(249, 115, 22, 0.2);
            border-color: rgba(249, 115, 22, 0.35);
            color: #fdba74;
        }

        body.dark-layout .st-pill-progress {
            background-color: rgba(36, 119, 249, 0.2);
            border-color: rgba(59, 130, 246, 0.35);
            color: #93c5fd;
        }

        body.dark-layout .st-pill-neutral {
            background-color: rgba(148, 163, 184, 0.16);
            color: #cbd5e1;
        }

        body.dark-layout .st-tip-banner {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16) 0%, rgba(30, 41, 59, 0.95) 100%);
            border-color: #334155;
        }

        body.dark-layout .st-tip-icon {
            background-color: #0f172a;
            color: #60a5fa;
        }

        body.dark-layout .st-pagination-wrap .page-link {
            background-color: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }

        body.dark-layout .st-select {
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

        $subtopicsTotal = $totalSubtopicsCount ?? $subtopics->count();
        $papersTotal = $totalPapersCount ?? $subtopics->sum(fn($s) => $s->papers->count());
        $papersCompleted = $completedPapersCount ?? 0;
        $topicPct = $completionRate ?? ($papersTotal > 0 ? (int) round(($papersCompleted / $papersTotal) * 100) : 0);
        $avgAcc = $averageAccuracy ?? null;

        $activeTab = (request()->filled('attempt_paper_name') || request()->filled('attempt_subtopic_id') || request()->filled('attempt_status') || request()->has('attempts_page'))
            ? 'attempts'
            : 'papers';

        $backUrl = $subject
            ? route('student.assessments.topics', $subject->id)
            : route('student.assessments');
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
                <nav class="st-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('student.dashboard') }}">Dashboard</a>
                    <span class="st-breadcrumb-sep">/</span>
                    <a href="{{ route('student.assessments') }}">Assessments</a>
                    @if($subject)
                        <span class="st-breadcrumb-sep">/</span>
                        <a href="{{ route('student.assessments.topics', $subject->id) }}">{{ $subject->title }} Topics</a>
                    @endif
                    <span class="st-breadcrumb-sep">/</span>
                    <span>{{ $topic->name }}</span>
                </nav>

                <!-- Page Header -->
                <div class="st-page-header">
                    <div class="st-header-left">
                        <div class="st-subject-avatar">
                            @if($subject && !empty($subject->image))
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
                            <div class="st-title-meta-row">
                                <h1 class="st-page-title">{{ $topic->name }}</h1>
                                @if($subject)
                                    <span class="st-subj-tag">{{ $subject->title }}</span>
                                @endif
                            </div>
                            <p class="st-page-subtitle">
                                Practice subtopic papers, resume paused tests, and review your attempt history.
                            </p>
                        </div>
                    </div>

                    <div class="st-header-actions">
                        <a href="{{ $backUrl }}" class="st-header-pill-btn">
                            <i class="feather icon-arrow-left"></i>
                            <span>Back to Topics</span>
                        </a>
                        <a href="{{ route('student.focusareas') }}" class="st-header-pill-btn">
                            <i class="feather icon-crosshair text-danger"></i>
                            <span>Focus Areas</span>
                        </a>
                    </div>
                </div>

                <!-- Top Row: 4 KPI Summary Cards -->
                <div class="st-kpi-grid">
                    <div class="st-kpi-card">
                        <div class="st-kpi-icon kpi-blue">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                <polyline points="2 17 12 22 22 17"/>
                                <polyline points="2 12 12 17 22 12"/>
                            </svg>
                        </div>
                        <div class="st-kpi-meta">
                            <span class="st-kpi-label">Subtopics</span>
                            <span class="st-kpi-count">{{ $subtopicsTotal }}</span>
                            <span class="st-kpi-sub">Under {{ $topic->name }}</span>
                        </div>
                    </div>

                    <div class="st-kpi-card">
                        <div class="st-kpi-icon kpi-soft-blue">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="9" y1="13" x2="15" y2="13"/>
                                <line x1="9" y1="17" x2="13" y2="17"/>
                            </svg>
                        </div>
                        <div class="st-kpi-meta">
                            <span class="st-kpi-label">Practice Papers</span>
                            <span class="st-kpi-count">{{ $papersTotal }}</span>
                            <span class="st-kpi-sub">Available to practice</span>
                        </div>
                    </div>

                    <div class="st-kpi-card">
                        <div class="st-kpi-icon kpi-green">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <div class="st-kpi-meta">
                            <span class="st-kpi-label">Completed</span>
                            <span class="st-kpi-count">{{ $papersCompleted }}</span>
                            <span class="st-kpi-sub">{{ $topicPct }}% topic progress</span>
                        </div>
                    </div>

                    <div class="st-kpi-card">
                        <div class="st-kpi-icon kpi-amber">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.5l2.85 5.78 6.38.93-4.61 4.5 1.09 6.35L12 17.06l-5.71 3 1.09-6.35-4.61-4.5 6.38-.93L12 2.5z"/>
                            </svg>
                        </div>
                        <div class="st-kpi-meta">
                            <span class="st-kpi-label">Average Accuracy</span>
                            <span class="st-kpi-count">{{ $avgAcc !== null ? $avgAcc . '%' : '—' }}</span>
                            <span class="st-kpi-sub">
                                @if($avgAcc === null)
                                    Take a test to score
                                @elseif($avgAcc >= 80)
                                    Strong mastery
                                @elseif($avgAcc >= 61)
                                    Getting better
                                @else
                                    Keep practicing
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <ul class="nav st-tabs-nav" id="assessmentTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'papers' ? 'active' : '' }}"
                           id="papers-tab"
                           data-toggle="tab"
                           href="#papers-content"
                           role="tab"
                           aria-controls="papers-content"
                           aria-selected="{{ $activeTab === 'papers' ? 'true' : 'false' }}">
                            <i class="feather icon-book-open"></i>
                            <span>Practice Papers</span>
                            <span class="st-tab-badge">{{ $papersTotal }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'attempts' ? 'active' : '' }}"
                           id="attempts-tab"
                           data-toggle="tab"
                           href="#attempts-content"
                           role="tab"
                           aria-controls="attempts-content"
                           aria-selected="{{ $activeTab === 'attempts' ? 'true' : 'false' }}">
                            <i class="feather icon-clock"></i>
                            <span>Attempt History &amp; Results</span>
                            @if($attempts->total() > 0)
                                <span class="st-tab-badge">{{ $attempts->total() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>

                <!-- Tab Contents -->
                <div class="tab-content mt-2" id="assessmentTabsContent">

                    <!-- Tab 1: Practice Papers -->
                    <div class="tab-pane fade {{ $activeTab === 'papers' ? 'show active' : '' }}"
                         id="papers-content"
                         role="tabpanel"
                         aria-labelledby="papers-tab">

                        <!-- Toolbar: Subtopic Filter, Paper Search & Collapse Toggle -->
                        <form method="GET" action="{{ route('student.topics.subtopics', $topic->id) }}" class="st-toolbar">
                            @foreach(request()->except(['subtopic_name', 'paper_name', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <div class="st-toolbar-left">
                                <div class="st-filter-group">
                                    <label for="subtopic_name" class="st-filter-label">Subtopic:</label>
                                    <select name="subtopic_name" id="subtopic_name" class="st-select" onchange="this.form.submit()">
                                        <option value="">All Subtopics</option>
                                        @foreach($allSubtopics as $sub)
                                            <option value="{{ $sub->name }}" {{ request('subtopic_name') == $sub->name ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if(request()->filled('subtopic_name') || request()->filled('paper_name'))
                                    @php
                                        $clearMainParams = request()->except(['subtopic_name', 'paper_name', 'page']);
                                    @endphp
                                    <a href="{{ route('student.topics.subtopics', [$topic->id] + $clearMainParams) }}"
                                       class="st-action-btn-light text-danger">
                                        <i class="feather icon-x"></i>
                                        <span>Clear Filters</span>
                                    </a>
                                @endif
                            </div>

                            <div class="st-toolbar-right">
                                <div class="st-search-box">
                                    <span class="st-search-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                    </span>
                                    <input type="text"
                                           name="paper_name"
                                           id="paper_name"
                                           class="st-search-input"
                                           placeholder="Search papers..."
                                           value="{{ request('paper_name') }}"
                                           autocomplete="off"
                                           aria-label="Search papers">
                                    @if(request()->filled('paper_name'))
                                        @php
                                            $clearPaperParams = request()->except(['paper_name', 'page']);
                                        @endphp
                                        <a href="{{ route('student.topics.subtopics', [$topic->id] + $clearPaperParams) }}"
                                           class="st-search-clear"
                                           title="Clear paper search"
                                           aria-label="Clear paper search">
                                            <i class="feather icon-x"></i>
                                        </a>
                                    @endif
                                </div>

                                @if($subtopics->isNotEmpty())
                                    <button type="button" class="st-action-btn-light" id="toggleAllSubtopicsBtn">
                                        <i class="feather icon-minimize-2"></i>
                                        <span>Collapse All</span>
                                    </button>
                                @endif
                            </div>
                        </form>

                        <!-- Subtopics List -->
                        <div class="st-subtopics-stack">
                            @forelse($subtopics as $subtopic)
                                @php
                                    $subtopicPapersCount = $subtopic->papers->count();
                                    $subtopicCompletedCount = $subtopic->papers->where('completed_attempts_count', '>', 0)->count();
                                    $subtopicProgress = $subtopicPapersCount > 0 ? round(($subtopicCompletedCount / $subtopicPapersCount) * 100) : 0;
                                @endphp
                                <div class="st-subtopic-card">
                                    <div class="st-subtopic-header"
                                         data-toggle="collapse"
                                         data-target="#collapseSubtopic{{ $subtopic->id }}"
                                         aria-expanded="true"
                                         aria-controls="collapseSubtopic{{ $subtopic->id }}">
                                        <div class="st-subtopic-title-wrap">
                                            <span class="st-subtopic-accent"></span>
                                            <div class="min-width-0">
                                                <h3 class="st-subtopic-title">{{ $subtopic->name }}</h3>
                                                <div class="st-subtopic-meta">
                                                    <span>Completed {{ $subtopicCompletedCount }} of {{ $subtopicPapersCount }} papers</span>
                                                    <span class="st-mini-track d-none d-sm-inline-block">
                                                        <span class="st-mini-fill d-block" style="width: {{ $subtopicProgress }}%;"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="st-subtopic-right">
                                            <span class="st-papers-pill">
                                                {{ $subtopicPapersCount }} {{ \Illuminate\Support\Str::plural('Paper', $subtopicPapersCount) }}
                                            </span>
                                            <span class="st-chevron-circle">
                                                <i class="feather icon-chevron-down"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div id="collapseSubtopic{{ $subtopic->id }}" class="collapse show st-subtopic-body">
                                        @forelse($subtopic->papers as $paper)
                                            @php
                                                $diffKey = strtolower($paper->difficulty ?? '');
                                                $diffBadgeClass = match ($diffKey) {
                                                    'easy'   => 'st-pill-easy',
                                                    'medium' => 'st-pill-medium',
                                                    'hard'   => 'st-pill-hard',
                                                    default  => 'st-pill-neutral',
                                                };
                                            @endphp
                                            <div class="st-paper-row">
                                                <div class="st-paper-info">
                                                    <div class="st-paper-title-line">
                                                        <h4 class="st-paper-title">{{ $paper->title }}</h4>

                                                        @if(!empty($paper->difficulty))
                                                            <span class="st-pill {{ $diffBadgeClass }}">
                                                                <span class="st-pill-dot"></span>
                                                                {{ $paper->difficulty }}
                                                            </span>
                                                        @endif

                                                        @if($paper->active_attempt)
                                                            @if($paper->active_attempt->status === 'paused')
                                                                <span class="st-pill st-pill-paused">
                                                                    <span class="st-pill-dot"></span>Paused
                                                                </span>
                                                            @else
                                                                <span class="st-pill st-pill-progress">
                                                                    <span class="st-pill-dot"></span>In Progress
                                                                </span>
                                                            @endif
                                                        @elseif($paper->completed_attempts_count > 0)
                                                            <span class="st-pill st-pill-done">
                                                                <i class="feather icon-check"></i> Completed
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="st-paper-meta">
                                                        <span>
                                                            <i class="feather icon-help-circle text-primary"></i>
                                                            <strong>{{ $paper->questions_count ?? 0 }}</strong> Questions
                                                        </span>
                                                        <span>
                                                            <i class="feather icon-clock text-success"></i>
                                                            <strong>{{ $paper->total_time ? $paper->total_time . ' mins' : 'Unlimited' }}</strong>
                                                        </span>
                                                        <span>
                                                            <i class="feather icon-check-square text-info"></i>
                                                            <strong>{{ $paper->completed_attempts_count }}</strong> Completed
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="st-paper-actions">
                                                    <form action="{{ route('student.papers.start', $paper->id) }}" method="POST" class="mb-0">
                                                        @csrf
                                                        @if($paper->active_attempt)
                                                            <button type="submit"
                                                                    class="st-btn-resume {{ $paper->active_attempt->status === 'paused' ? 'is-paused' : 'is-active' }}">
                                                                <span>Resume</span>
                                                                <i class="feather icon-play"></i>
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                    class="st-btn-start btn-start-trigger"
                                                                    data-paper-id="{{ $paper->id }}"
                                                                    data-paper-title="{{ $paper->title }}"
                                                                    data-paper-questions="{{ $paper->questions_count ?? 0 }}"
                                                                    data-paper-time="{{ $paper->total_time ? $paper->total_time . ' mins' : 'Unlimited' }}"
                                                                    data-paper-timed="{{ $paper->total_time > 0 ? '1' : '0' }}"
                                                                    data-paper-type="{{ $paper->display_type ?? ucfirst($paper->type ?? 'Test') }}"
                                                                    data-paper-marks="{{ $paper->total_marks ?? 0 }}"
                                                                    data-paper-subject="{{ $subject->title ?? ($paper->subject?->title ?? 'General') }}"
                                                                    data-paper-difficulty="{{ $diffKey }}"
                                                                    data-paper-instruction="{{ $paper->instruction ?? '' }}"
                                                                    data-action-url="{{ route('student.papers.start', $paper->id) }}">
                                                                <span>Start</span>
                                                                <i class="feather icon-arrow-right"></i>
                                                            </button>
                                                        @endif
                                                    </form>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="st-empty-box">
                                                <div class="st-empty-icon">
                                                    <i class="feather icon-file-text"></i>
                                                </div>
                                                <h5 class="font-weight-bold mb-50" style="color: var(--text-primary, #0b1f51);">No Papers Available</h5>
                                                <p class="text-muted mb-0" style="font-size: 0.88rem;">
                                                    There are no practice papers available for this subtopic right now.
                                                </p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <div class="st-card">
                                    <div class="st-empty-box">
                                        <div class="st-empty-icon">
                                            <i class="feather icon-layers"></i>
                                        </div>
                                        <h4 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">No Subtopics Found</h4>
                                        <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                            No subtopics matched your filter or are configured for this topic yet.
                                        </p>
                                        @if(request()->filled('subtopic_name') || request()->filled('paper_name'))
                                            <a href="{{ route('student.topics.subtopics', $topic->id) }}" class="btn btn-primary btn-sm px-2" style="border-radius: 10px;">
                                                <i class="feather icon-refresh-cw mr-25"></i> Clear Filters
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <!-- Tab 2: Attempt History & Results -->
                    <div class="tab-pane fade {{ $activeTab === 'attempts' ? 'show active' : '' }}"
                         id="attempts-content"
                         role="tabpanel"
                         aria-labelledby="attempts-tab">

                        <!-- Attempt Filters Toolbar -->
                        <form method="GET" action="{{ route('student.topics.subtopics', $topic->id) }}" class="st-toolbar">
                            @foreach(request()->except(['attempt_paper_name', 'attempt_subtopic_id', 'attempt_status', 'attempts_page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <div class="st-toolbar-left">
                                <div class="st-filter-group">
                                    <label for="attempt_subtopic_id" class="st-filter-label">Subtopic:</label>
                                    <select name="attempt_subtopic_id" id="attempt_subtopic_id" class="st-select" onchange="this.form.submit()">
                                        <option value="">All Subtopics</option>
                                        @foreach($allSubtopics as $sub)
                                            <option value="{{ $sub->id }}" {{ request('attempt_subtopic_id') == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="st-filter-group">
                                    <label for="attempt_status" class="st-filter-label">Status:</label>
                                    <select name="attempt_status" id="attempt_status" class="st-select" onchange="this.form.submit()">
                                        <option value="">All Statuses</option>
                                        <option value="completed" {{ request('attempt_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="in_progress" {{ request('attempt_status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="paused" {{ request('attempt_status') == 'paused' ? 'selected' : '' }}>Paused</option>
                                    </select>
                                </div>

                                @if(request()->filled('attempt_paper_name') || request()->filled('attempt_subtopic_id') || request()->filled('attempt_status'))
                                    @php
                                        $clearParams = request()->except(['attempt_paper_name', 'attempt_subtopic_id', 'attempt_status', 'attempts_page']);
                                    @endphp
                                    <a href="{{ route('student.topics.subtopics', [$topic->id] + $clearParams) }}"
                                       class="st-action-btn-light text-danger">
                                        <i class="feather icon-x"></i>
                                        <span>Clear Filters</span>
                                    </a>
                                @endif
                            </div>

                            <div class="st-toolbar-right">
                                <div class="st-search-box">
                                    <span class="st-search-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                    </span>
                                    <input type="text"
                                           name="attempt_paper_name"
                                           id="attempt_paper_name"
                                           class="st-search-input"
                                           placeholder="Search attempts..."
                                           value="{{ request('attempt_paper_name') }}"
                                           autocomplete="off"
                                           aria-label="Search attempts">
                                    @if(request()->filled('attempt_paper_name'))
                                        @php
                                            $clearAttemptSearch = request()->except(['attempt_paper_name', 'attempts_page']);
                                        @endphp
                                        <a href="{{ route('student.topics.subtopics', [$topic->id] + $clearAttemptSearch) }}"
                                           class="st-search-clear"
                                           title="Clear search"
                                           aria-label="Clear search">
                                            <i class="feather icon-x"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <!-- Attempt History Directory Card -->
                        <div class="st-card">
                            <div class="st-card-header">
                                <div>
                                    <h2 class="st-card-title">Attempt History</h2>
                                    <p class="st-card-subtitle">Review your completed results and resume unfinished attempts.</p>
                                </div>
                                @if($attempts->total() > 0)
                                    <span class="st-count-chip">
                                        Showing {{ $attempts->firstItem() }}–{{ $attempts->lastItem() }} of {{ $attempts->total() }}
                                    </span>
                                @endif
                            </div>

                            @if($attempts->isNotEmpty())
                                <!-- Mobile Cards (< 768px) -->
                                <div class="d-block d-md-none">
                                    @foreach($attempts as $attempt)
                                        @php
                                            $pct = $attempt->max_score > 0 ? round(($attempt->score / $attempt->max_score) * 100) : 0;
                                            $pctColor = $pct >= 80 ? 'text-success' : ($pct >= 61 ? 'text-warning' : 'text-danger');
                                            $h = floor($attempt->time_spent / 3600);
                                            $m = floor(($attempt->time_spent / 60) % 60);
                                            $s = $attempt->time_spent % 60;
                                            $spent = ($h > 0 ? $h . 'h ' : '') . ($m > 0 || $h > 0 ? $m . 'm ' : '') . $s . 's';
                                        @endphp
                                        <div class="st-mobile-attempt-card">
                                            <div class="d-flex justify-content-between align-items-start mb-75">
                                                <div class="pr-1">
                                                    <h6 class="font-weight-bold mb-25" style="color: var(--text-primary, #0b1f51);">
                                                        {{ $attempt->paper->title }}
                                                    </h6>
                                                    <span class="st-pill st-pill-neutral">
                                                        {{ $attempt->paper->subtopic->name ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                @if($attempt->status === 'completed')
                                                    <span class="st-pill st-pill-done">Completed</span>
                                                @elseif($attempt->status === 'paused')
                                                    <span class="st-pill st-pill-paused">Paused</span>
                                                @else
                                                    <span class="st-pill st-pill-progress">In Progress</span>
                                                @endif
                                            </div>

                                            <div class="d-flex justify-content-between font-small-3 text-muted mb-75">
                                                <span><i class="feather icon-calendar mr-25"></i>{{ $attempt->created_at->format('d M Y, h:i A') }}</span>
                                                <span><i class="feather icon-clock mr-25"></i>{{ $spent }}</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-75 border-top" style="border-color: var(--border-subtle, #eef2f7) !important;">
                                                <div class="font-small-3">
                                                    <strong>{{ $attempt->score }} / {{ $attempt->max_score }}</strong>
                                                    <span class="ml-50 font-weight-bold {{ $pctColor }}">({{ $pct }}%)</span>
                                                </div>
                                                @if($attempt->status === 'completed')
                                                    <a href="{{ route('student.attempts.result', $attempt->id) }}" class="st-btn-outline">
                                                        <span>Result</span>
                                                        <i class="feather icon-arrow-right"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('student.attempts.take', $attempt->id) }}" class="st-btn-outline">
                                                        <span>Resume</span>
                                                        <i class="feather icon-play"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Desktop Table -->
                                <div class="st-table-wrap d-none d-md-block">
                                    <table class="st-table">
                                        <thead>
                                            <tr>
                                                <th>Paper</th>
                                                <th>Subtopic</th>
                                                <th>Attempt Date</th>
                                                <th>Status</th>
                                                <th>Score</th>
                                                <th>Accuracy</th>
                                                <th>Time Spent</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($attempts as $attempt)
                                                @php
                                                    $pct = $attempt->max_score > 0 ? round(($attempt->score / $attempt->max_score) * 100) : 0;
                                                    $fillClass = $pct >= 80 ? 'fill-strong' : ($pct >= 61 ? 'fill-developing' : 'fill-focus');
                                                    $pctColor = $pct >= 80 ? 'text-success' : ($pct >= 61 ? 'text-warning' : 'text-danger');
                                                    $h = floor($attempt->time_spent / 3600);
                                                    $m = floor(($attempt->time_spent / 60) % 60);
                                                    $s = $attempt->time_spent % 60;
                                                    $spent = ($h > 0 ? $h . 'h ' : '') . ($m > 0 || $h > 0 ? $m . 'm ' : '') . $s . 's';
                                                @endphp
                                                <tr>
                                                    <td class="font-weight-bold" style="color: var(--text-primary, #0b1f51);">
                                                        {{ $attempt->paper->title }}
                                                    </td>
                                                    <td>{{ $attempt->paper->subtopic->name ?? 'N/A' }}</td>
                                                    <td class="text-nowrap">
                                                        <i class="feather icon-calendar mr-25 text-muted"></i>
                                                        {{ $attempt->created_at->format('d M Y, h:i A') }}
                                                    </td>
                                                    <td>
                                                        @if($attempt->status === 'completed')
                                                            <span class="st-pill st-pill-done">Completed</span>
                                                        @elseif($attempt->status === 'paused')
                                                            <span class="st-pill st-pill-paused">Paused</span>
                                                        @else
                                                            <span class="st-pill st-pill-progress">In Progress</span>
                                                        @endif
                                                    </td>
                                                    <td class="font-weight-bold text-nowrap">
                                                        {{ $attempt->score }} <span class="text-muted font-weight-normal">/ {{ $attempt->max_score }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="st-acc-wrap">
                                                            <span class="st-acc-val {{ $pctColor }}">{{ $pct }}%</span>
                                                            <div class="st-acc-track">
                                                                <div class="st-acc-fill {{ $fillClass }}" style="width: {{ $pct }}%;"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <i class="feather icon-clock mr-25 text-muted"></i>{{ $spent }}
                                                    </td>
                                                    <td class="text-right">
                                                        @if($attempt->status === 'completed')
                                                            <a href="{{ route('student.attempts.result', $attempt->id) }}" class="st-btn-outline">
                                                                <span>View Result</span>
                                                                <i class="feather icon-arrow-right"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('student.attempts.take', $attempt->id) }}" class="st-btn-outline">
                                                                <span>Resume</span>
                                                                <i class="feather icon-play"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($attempts->hasPages())
                                    <div class="st-pagination-wrap">
                                        {{ $attempts->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="st-empty-box">
                                    <div class="st-empty-icon">
                                        <i class="feather icon-clock"></i>
                                    </div>
                                    <h5 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">No Attempt History Available</h5>
                                    <p class="text-muted mb-0" style="font-size: 0.9rem;">
                                        @if(request()->filled('attempt_paper_name') || request()->filled('attempt_subtopic_id') || request()->filled('attempt_status'))
                                            No attempts matched your selected filters.
                                        @else
                                            You have not attempted any papers for this topic yet.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

                <!-- Bottom Guidance Banner -->
                <div class="st-tip-banner">
                    <div class="st-tip-left">
                        <span class="st-tip-icon">
                            <i class="feather icon-trending-up font-medium-3"></i>
                        </span>
                        <div>
                            <h4 class="st-tip-title">Track your topic mastery over time</h4>
                            <p class="st-tip-desc">Check Focus Areas after completing tests to see whether {{ $topic->name }} is Strong, Developing, or Needs Focus.</p>
                        </div>
                    </div>
                    <a href="{{ route('student.focusareas') }}" class="st-tip-link">
                        <span>View Focus Areas</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    @include('student.assessment.partials.start-test-modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggleAllBtn = document.getElementById('toggleAllSubtopicsBtn');
            var allExpanded = true;

            if (toggleAllBtn) {
                toggleAllBtn.addEventListener('click', function () {
                    allExpanded = !allExpanded;
                    var panels = $('.st-subtopic-card .collapse');
                    if (allExpanded) {
                        panels.collapse('show');
                        toggleAllBtn.innerHTML = '<i class="feather icon-minimize-2"></i><span>Collapse All</span>';
                    } else {
                        panels.collapse('hide');
                        toggleAllBtn.innerHTML = '<i class="feather icon-maximize-2"></i><span>Expand All</span>';
                    }
                });
            }
        });
    </script>
@endpush
