@extends('layouts.app')

@section('title', 'Analytics - Aspire Learners')

@push('styles')
    <style>
        /* ==========================================================================
           STUDENT ANALYTICS - MODERN UI/UX (LIGHT & DARK MODE COMPATIBLE)
           ========================================================================== */
        .an-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.4rem;
        }

        .an-page-title-wrap {
            min-width: 0;
        }

        .an-page-title {
            color: var(--text-primary, #0b1f51);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .an-page-subtitle {
            color: var(--text-muted, #64748b);
            font-size: 0.96rem;
            font-weight: 500;
            margin: 0;
        }

        .an-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .an-header-pill-btn {
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
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease, color 0.2s ease;
        }

        .an-header-pill-btn:hover {
            border-color: #2477F9;
            color: #2477F9 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(36, 119, 249, 0.14);
        }

        /* Period Dropdown Button (aligned with Focus Areas) */
        .an-period-btn {
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

        .an-period-btn:hover,
        .an-period-btn:focus {
            border-color: #2477F9 !important;
            box-shadow: 0 4px 14px rgba(36, 119, 249, 0.14) !important;
        }

        .an-period-btn::after {
            display: none !important;
        }

        .an-period-menu {
            border-radius: 14px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7) !important;
            padding: 6px;
            min-width: 195px;
        }

        .an-period-menu .dropdown-item {
            color: var(--text-body, #334155);
            font-size: 0.86rem;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .an-period-menu .dropdown-item:hover {
            background-color: var(--theme-primary-light, #ebf3ff);
            color: #2477F9;
        }

        .an-period-menu .dropdown-item.active {
            background-color: #2477F9;
            color: #ffffff;
            font-weight: 600;
        }

        /* Top 4 KPI Summary Cards */
        .an-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 1.35rem;
        }

        .an-kpi-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 3px 14px rgba(15, 23, 42, 0.035);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            min-width: 0;
        }

        .an-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            border-color: #cbd5e1;
        }

        .an-kpi-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .an-kpi-icon.kpi-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2477F9 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.25);
        }

        .an-kpi-icon.kpi-green {
            background: linear-gradient(135deg, #2ecc71 0%, #16a34a 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
        }

        .an-kpi-icon.kpi-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .an-kpi-icon.kpi-purple {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
        }

        .an-kpi-meta {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .an-kpi-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-body, #334155);
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .an-kpi-count {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.15;
            margin: 2px 0;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .an-kpi-sub {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .an-kpi-sub.is-up {
            color: #16a34a;
            font-weight: 700;
        }

        .an-kpi-sub.is-down {
            color: #ef4444;
            font-weight: 700;
        }

        /* Filter Pills & Search Toolbar */
        .an-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 1.35rem;
        }

        .an-filter-pills {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .an-filter-pill {
            border: 1px solid transparent;
            background-color: #e8eef7;
            color: #334155;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 999px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.18s ease;
            outline: none !important;
            line-height: 1.2;
        }

        .an-filter-pill:hover {
            background-color: #dce5f3;
            color: #0b1f51;
        }

        .an-filter-pill.active {
            background: linear-gradient(135deg, #2477F9 0%, #1757C1 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.28);
        }

        .an-pill-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .an-filter-pill.active .an-pill-dot {
            background-color: #ffffff !important;
        }

        .an-toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .an-search-box {
            position: relative;
            width: 260px;
            max-width: 100%;
        }

        .an-search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: inline-flex;
        }

        .an-search-input {
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

        .an-search-input::placeholder {
            color: #94a3b8;
        }

        .an-search-input:focus {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .an-search-clear {
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

        .an-search-clear:hover {
            color: #ef4444;
        }

        /* Shared Card & Section Typography */
        .an-charts-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.42fr) minmax(320px, 1fr);
            gap: 20px;
            margin-bottom: 1.35rem;
            align-items: stretch;
        }

        .an-bottom-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 1.35rem;
            align-items: stretch;
        }

        .an-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .an-card-header-flex {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .an-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
            line-height: 1.3;
        }

        .an-card-subtitle {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            margin: 0;
        }

        .an-meta-chips {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .an-meta-chip {
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--text-body, #334155);
            background-color: #f1f5f9;
            padding: 4px 10px;
            border-radius: 999px;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .an-meta-chip.chip-primary {
            background-color: #ebf3ff;
            color: #1757C1;
        }

        .an-view-all-link {
            color: #2563eb !important;
            font-size: 0.86rem;
            font-weight: 600;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            transition: color 0.16s ease, transform 0.16s ease;
        }

        .an-view-all-link:hover {
            color: #1d4ed8 !important;
            transform: translateX(2px);
        }

        /* Charts Containers & Subject Legend (consistent with Focus Areas) */
        .an-trend-chart-box {
            min-height: 265px;
            flex: 1;
        }

        .an-subject-chart-box {
            min-height: 205px;
        }

        .an-subject-legend-grid {
            display: grid;
            grid-template-columns: repeat(var(--subj-cols, 3), minmax(0, 1fr));
            gap: 6px;
            text-align: center;
            margin-top: -2px;
            padding-left: 38px;
            padding-right: 4px;
        }

        .an-subj-legend-btn {
            background: transparent;
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 6px 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: background-color 0.16s ease, border-color 0.16s ease, opacity 0.16s ease, transform 0.16s ease;
            outline: none !important;
        }

        .an-subj-legend-btn:hover {
            background-color: #f8fbff;
            transform: translateY(-1px);
        }

        .an-subj-legend-btn.is-active {
            background-color: #ebf3ff;
            border-color: #bfdbfe;
        }

        .an-subj-legend-btn.is-dimmed {
            opacity: 0.45;
        }

        .an-subj-legend-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-primary, #1e293b);
            line-height: 1.2;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .an-subj-legend-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
        }

        /* Recent Assessments Table (aligned with Focus Areas table) */
        .an-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .an-table {
            width: 100%;
            border-collapse: collapse;
        }

        .an-table thead th {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            padding: 8px 10px 12px;
            border-bottom: 1px solid var(--border-subtle, #eef2f7);
            white-space: nowrap;
            text-align: left;
        }

        .an-table thead th.col-subj {
            text-align: center;
        }

        .an-table thead th.col-score {
            text-align: right;
        }

        .an-assessment-row {
            border-bottom: 1px solid var(--border-subtle, #f1f5f9);
            transition: background-color 0.16s ease;
            cursor: pointer;
        }

        .an-assessment-row:last-child {
            border-bottom: none;
        }

        .an-assessment-row:hover {
            background-color: #f8fbff;
        }

        .an-table tbody td {
            padding: 12px 10px;
            vertical-align: middle;
        }

        .an-test-link {
            color: var(--text-primary, #0b1f51);
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none !important;
            display: block;
            line-height: 1.3;
            transition: color 0.16s ease;
        }

        .an-assessment-row:hover .an-test-link {
            color: #2477F9;
        }

        .an-test-meta {
            font-size: 0.76rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            margin-top: 2px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .an-date-cell {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            white-space: nowrap;
        }

        .an-score-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .an-score-val {
            font-size: 0.94rem;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .an-row-chevron {
            color: #94a3b8;
            transition: transform 0.16s ease, color 0.16s ease;
        }

        .an-assessment-row:hover .an-row-chevron {
            transform: translateX(2px);
            color: #2477F9;
        }

        /* Subject Badges (identical across Focus Areas & Analytics) */
        .an-subj-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 64px;
            padding: 4px 11px;
            border-radius: 7px;
            font-size: 0.76rem;
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

        /* Accuracy by Topic Progress List */
        .an-topic-list {
            display: flex;
            flex-direction: column;
        }

        .an-topic-row {
            display: grid;
            grid-template-columns: minmax(125px, 1.3fr) minmax(100px, 1.4fr) auto;
            align-items: center;
            gap: 14px;
            padding: 11px 8px;
            border-bottom: 1px solid var(--border-subtle, #f1f5f9);
            border-radius: 10px;
            text-decoration: none !important;
            transition: background-color 0.16s ease;
        }

        .an-topic-row:last-child {
            border-bottom: none;
        }

        .an-topic-row:hover {
            background-color: #f8fbff;
        }

        .an-topic-info {
            min-width: 0;
        }

        .an-topic-name {
            font-size: 0.91rem;
            font-weight: 700;
            color: var(--text-primary, #1e293b);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.16s ease;
        }

        .an-topic-row:hover .an-topic-name {
            color: #2477F9;
        }

        .an-topic-submeta {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 3px;
            font-size: 0.74rem;
            color: var(--text-muted, #64748b);
        }

        .an-topic-mini-badge {
            padding: 1px 7px;
            border-radius: 6px;
            font-size: 0.69rem;
            font-weight: 700;
        }

        .an-prog-track {
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background-color: #edf2f7;
            overflow: hidden;
        }

        .an-prog-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 0.6s ease;
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

        .an-topic-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            min-width: 96px;
        }

        .an-topic-pct {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            font-variant-numeric: tabular-nums;
        }

        .an-status-text {
            font-size: 0.78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-strong {
            color: #16a34a;
        }

        .status-developing {
            color: #d97706;
        }

        .status-focus {
            color: #ef4444;
        }

        /* Empty States */
        .an-empty-box {
            text-align: center;
            padding: 2.5rem 1.25rem;
            margin: auto 0;
        }

        .an-empty-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2477F9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .an-empty-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin-bottom: 4px;
        }

        .an-empty-desc {
            font-size: 0.86rem;
            color: var(--text-muted, #64748b);
            margin-bottom: 14px;
        }

        /* Bottom Guidance / Insight Strip (matches Assessments & Focus Areas) */
        .an-tip-banner {
            background: linear-gradient(135deg, #eef4ff 0%, #e5efff 100%);
            border: 1px solid #d5e5ff;
            border-radius: 16px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: 0 3px 12px rgba(36, 119, 249, 0.05);
        }

        .an-tip-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
            flex: 1;
        }

        .an-tip-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background-color: #ffffff;
            color: #2477F9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(36, 119, 249, 0.12);
        }

        .an-tip-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
        }

        .an-tip-desc {
            font-size: 0.85rem;
            color: #475569;
            margin: 0;
            line-height: 1.45;
        }

        .an-tip-link {
            font-size: 0.86rem;
            font-weight: 700;
            color: #2477F9 !important;
            text-decoration: none !important;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.16s ease, transform 0.16s ease;
        }

        .an-tip-link:hover {
            color: #1757C1 !important;
            transform: translateX(2px);
        }

        /* Responsive Breakpoints */
        @media (max-width: 1199.98px) {
            .an-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .an-charts-grid,
            .an-bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .an-search-box {
                width: 100%;
            }
            .an-card {
                padding: 18px 16px;
            }
        }

        @media (max-width: 575.98px) {
            .an-kpi-grid {
                grid-template-columns: 1fr;
            }
            .an-topic-row {
                grid-template-columns: minmax(0, 1fr) auto;
            }
            .an-topic-bar-col {
                display: none;
            }
        }

        /* Dark Mode Support */
        body.dark-layout .an-kpi-card,
        body.dark-layout .an-card,
        body.dark-layout .an-header-pill-btn,
        body.dark-layout .an-period-btn,
        body.dark-layout .an-period-menu,
        body.dark-layout .an-search-input {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-layout .an-page-title,
        body.dark-layout .an-kpi-count,
        body.dark-layout .an-card-title,
        body.dark-layout .an-test-link,
        body.dark-layout .an-topic-name,
        body.dark-layout .an-topic-pct,
        body.dark-layout .an-subj-legend-label,
        body.dark-layout .an-empty-title,
        body.dark-layout .an-tip-title,
        body.dark-layout .an-header-pill-btn,
        body.dark-layout .an-period-btn,
        body.dark-layout .an-search-input {
            color: #f8fafc !important;
        }

        body.dark-layout .an-page-subtitle,
        body.dark-layout .an-kpi-sub:not(.is-up):not(.is-down),
        body.dark-layout .an-card-subtitle,
        body.dark-layout .an-table thead th,
        body.dark-layout .an-test-meta,
        body.dark-layout .an-date-cell,
        body.dark-layout .an-topic-submeta,
        body.dark-layout .an-empty-desc,
        body.dark-layout .an-tip-desc {
            color: #94a3b8 !important;
        }

        body.dark-layout .an-kpi-label {
            color: #cbd5e1 !important;
        }

        body.dark-layout .an-period-menu .dropdown-item {
            color: #cbd5e1;
        }

        body.dark-layout .an-period-menu .dropdown-item:hover {
            background-color: rgba(36, 119, 249, 0.16);
            color: #93c5fd;
        }

        body.dark-layout .an-filter-pill {
            background-color: #1e293b;
            color: #cbd5e1;
            border-color: #334155;
        }

        body.dark-layout .an-filter-pill:hover {
            background-color: #334155;
            color: #ffffff;
        }

        body.dark-layout .an-filter-pill.active {
            border-color: transparent;
        }

        body.dark-layout .an-meta-chip {
            background-color: #0f172a;
            color: #cbd5e1;
        }

        body.dark-layout .an-meta-chip.chip-primary {
            background-color: rgba(36, 119, 249, 0.22);
            color: #93c5fd;
        }

        body.dark-layout .an-table thead th,
        body.dark-layout .an-assessment-row,
        body.dark-layout .an-topic-row {
            border-color: #334155 !important;
        }

        body.dark-layout .an-assessment-row:hover,
        body.dark-layout .an-topic-row:hover,
        body.dark-layout .an-subj-legend-btn:hover {
            background-color: rgba(36, 119, 249, 0.08);
        }

        body.dark-layout .an-subj-legend-btn.is-active {
            background-color: rgba(36, 119, 249, 0.18);
            border-color: rgba(59, 130, 246, 0.4);
        }

        body.dark-layout .an-prog-track {
            background-color: #0f172a;
        }

        body.dark-layout .an-empty-icon {
            background-color: rgba(36, 119, 249, 0.18);
            color: #60a5fa;
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

        body.dark-layout .subj-default {
            background-color: rgba(148, 163, 184, 0.16);
            color: #cbd5e1;
        }

        body.dark-layout .an-tip-banner {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16) 0%, rgba(30, 41, 59, 0.95) 100%);
            border-color: #334155;
        }

        body.dark-layout .an-tip-icon {
            background-color: #0f172a;
            color: #60a5fa;
        }
    </style>
@endpush

@section('content')
    @php
        $badgeClassFor = function (string $key): string {
            return in_array($key, ['english', 'maths', 'vr', 'nvr', 'science'], true)
                ? 'subj-' . $key
                : 'subj-default';
        };

        $overallSubtext = $overallProgress !== null
            ? 'Vs. previous period'
            : ($bestScore !== null ? 'Best score: ' . $bestScore . '%' : 'No prior period data');
    @endphp

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <!-- Page Header with Title, Quick Link & Time Period Dropdown -->
                <div class="an-page-header">
                    <div class="an-page-title-wrap">
                        <h1 class="an-page-title">Analytics</h1>
                        <p class="an-page-subtitle">Track your progress, score trends, and topic mastery over time.</p>
                    </div>

                    <div class="an-header-actions">
                        <a href="{{ route('student.focusareas', ['period' => $period]) }}" class="an-header-pill-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="6"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                            <span>Focus Areas</span>
                        </a>

                        <div class="dropdown">
                            <button class="btn an-period-btn dropdown-toggle"
                                    type="button"
                                    id="analyticsPeriodDropdown"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2477F9" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <span>{{ $selectedPeriodLabel }}</span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.72;">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right an-period-menu shadow-lg border-0" aria-labelledby="analyticsPeriodDropdown">
                                @foreach($periodLabels as $key => $label)
                                    @if($key === 'all')
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <a class="dropdown-item {{ $period === $key ? 'active' : '' }}"
                                       href="{{ route('student.analytics', ['period' => $key]) }}">
                                        <span>{{ $label }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Row: 4 KPI Summary Cards -->
                <div class="an-kpi-grid">
                    <!-- Card 1: Total Assessments -->
                    <div class="an-kpi-card">
                        <div class="an-kpi-icon kpi-blue">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="4" y="13" width="4" height="7" rx="1.2"/>
                                <rect x="10" y="8" width="4" height="12" rx="1.2"/>
                                <rect x="16" y="4" width="4" height="16" rx="1.2"/>
                            </svg>
                        </div>
                        <div class="an-kpi-meta">
                            <span class="an-kpi-label">Total Assessments</span>
                            <span class="an-kpi-count" id="kpiTotalAssessments">{{ $totalAssessments }}</span>
                            <span class="an-kpi-sub" id="kpiTotalSubtext">
                                @if($totalAssessments > 0)
                                    {{ $period === 'all' ? 'All completed tests' : $allTimeAssessments . ' completed all time' }}
                                @else
                                    No completed tests yet
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Card 2: Average Score -->
                    <div class="an-kpi-card">
                        <div class="an-kpi-icon kpi-green">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="4.5"/>
                                <path d="M10 12.2l1.5 1.5L16.5 8.5"/>
                            </svg>
                        </div>
                        <div class="an-kpi-meta">
                            <span class="an-kpi-label">Average Score</span>
                            <span class="an-kpi-count" id="kpiAverageScore">{{ $totalAssessments > 0 ? $averageScore . '%' : '—' }}</span>
                            <span class="an-kpi-sub {{ $scoreGrowth !== null && $scoreGrowth > 0 ? 'is-up' : ($scoreGrowth !== null && $scoreGrowth < 0 ? 'is-down' : '') }}" id="kpiAverageSubtext">
                                @if($scoreGrowth === null)
                                    {{ $totalAssessments > 0 ? 'Need 2+ tests for trend' : 'No completed tests' }}
                                @elseif($scoreGrowth > 0)
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                                    +{{ $scoreGrowth }}% recent trend
                                @elseif($scoreGrowth < 0)
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    {{ $scoreGrowth }}% recent trend
                                @else
                                    0% change
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Card 3: Overall Progress -->
                    <div class="an-kpi-card">
                        <div class="an-kpi-icon kpi-amber">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                                <polyline points="17 6 23 6 23 12"/>
                            </svg>
                        </div>
                        <div class="an-kpi-meta">
                            <span class="an-kpi-label">Overall Progress</span>
                            <span class="an-kpi-count {{ $overallProgress !== null && $overallProgress < 0 ? 'text-danger' : '' }}" id="kpiOverallProgress">
                                @if($overallProgress !== null)
                                    {{ ($overallProgress >= 0 ? '+' : '') . $overallProgress }}%
                                @else
                                    —
                                @endif
                            </span>
                            <span class="an-kpi-sub" id="kpiOverallSubtext">{{ $overallSubtext }}</span>
                        </div>
                    </div>

                    <!-- Card 4: Learning Streak -->
                    <div class="an-kpi-card">
                        <div class="an-kpi-icon kpi-purple">
                            <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.5l2.85 5.78 6.38.93-4.61 4.5 1.09 6.35L12 17.06l-5.71 3 1.09-6.35-4.61-4.5 6.38-.93L12 2.5z"/>
                            </svg>
                        </div>
                        <div class="an-kpi-meta">
                            <span class="an-kpi-label">Learning Streak</span>
                            <span class="an-kpi-count">{{ $streak }} {{ Str::plural('day', $streak) }}</span>
                            <span class="an-kpi-sub">{{ $streak > 0 ? 'Keep it going!' : 'Practice today!' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Filter & Search Toolbar (consistent with Assessments & Focus Areas) -->
                <div class="an-toolbar">
                    <div class="an-filter-pills" role="tablist" aria-label="Filter analytics by subject">
                        <button type="button"
                                class="an-filter-pill {{ $activeSubjectFilter === 'all' ? 'active' : '' }}"
                                data-subject-filter="all">
                            All Subjects
                        </button>
                        @foreach($subjectOptions as $opt)
                            <button type="button"
                                    class="an-filter-pill {{ $activeSubjectFilter === $opt['key'] ? 'active' : '' }}"
                                    data-subject-filter="{{ $opt['key'] }}">
                                <span class="an-pill-dot" style="background-color: {{ $opt['color'] }};"></span>
                                <span>{{ $opt['title'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="an-toolbar-right">
                        <div class="an-search-box">
                            <span class="an-search-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                            </span>
                            <input type="text"
                                   id="analyticsSearchInput"
                                   class="an-search-input"
                                   placeholder="Search tests or topics..."
                                   autocomplete="off"
                                   aria-label="Search tests or topics">
                            <button type="button"
                                    id="clearAnalyticsSearchBtn"
                                    class="an-search-clear d-none"
                                    aria-label="Clear search">
                                <i class="feather icon-x"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Middle Row: Performance Trend & Subject Performance -->
                <div class="an-charts-grid">
                    <!-- Left: Performance Trend Area Chart -->
                    <div class="an-card">
                        <div class="an-card-header-flex">
                            <div>
                                <h2 class="an-card-title">Performance Trend</h2>
                                <p class="an-card-subtitle">Your average score over time.</p>
                            </div>
                            <div class="an-meta-chips {{ empty($trendData['series']) ? 'd-none' : '' }}" id="trendMetaChips">
                                @if($bestScore !== null)
                                    <span class="an-meta-chip chip-primary" id="trendBestChip">Best: {{ $bestScore }}%</span>
                                @endif
                                @if($latestScore !== null)
                                    <span class="an-meta-chip" id="trendLatestChip">Latest: {{ $latestScore }}%</span>
                                @endif
                            </div>
                        </div>

                        <div id="performanceTrendChart"
                             class="an-trend-chart-box {{ empty($trendData['series']) ? 'd-none' : '' }}"></div>

                        <div id="trendEmptyState" class="an-empty-box {{ !empty($trendData['series']) ? 'd-none' : '' }}">
                            <div class="an-empty-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                                    <polyline points="17 6 23 6 23 12"/>
                                </svg>
                            </div>
                            <h4 class="an-empty-title" id="trendEmptyTitle">
                                No completed assessments found in {{ strtolower($selectedPeriodLabel) }}.
                            </h4>
                            <p class="an-empty-desc" id="trendEmptyDesc">
                                Complete a practice test to track your progress curve over time.
                            </p>
                            <div class="d-inline-flex flex-wrap justify-content-center" style="gap: 10px;">
                                <button type="button" class="btn btn-outline-primary btn-sm px-2 d-none" id="trendResetSubjectBtn" style="border-radius: 10px;">
                                    Show All Subjects
                                </button>
                                <a href="{{ route('student.assessments') }}" class="btn btn-primary btn-sm px-2" style="border-radius: 10px;">
                                    Start a Practice Test
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Subject Performance (aligned with Focus Areas Subject Overview) -->
                    <div class="an-card">
                        <div class="an-card-header-flex mb-1">
                            <div>
                                <h2 class="an-card-title">Subject Performance</h2>
                                <p class="an-card-subtitle">Average score by subject.</p>
                            </div>
                            @if(!empty($hasSubjectData) && !empty($subjectPerformance))
                                <span class="an-meta-chip">Click to filter</span>
                            @endif
                        </div>

                        @if(!empty($hasSubjectData) && !empty($subjectPerformance))
                            <div id="subjectPerformanceChart" class="an-subject-chart-box"></div>

                            <div class="an-subject-legend-grid" id="subjectLegendGrid" style="--subj-cols: {{ count($subjectPerformance) }};">
                                @foreach($subjectPerformance as $subj)
                                    <button type="button"
                                            class="an-subj-legend-btn"
                                            data-subject-legend="{{ $subj['key'] }}"
                                            title="Filter {{ $subj['title'] }} ({{ $subj['score'] }}% avg)">
                                        <span class="an-subj-legend-label">{{ $subj['title'] }}</span>
                                        <span class="an-subj-legend-icon" style="color: {{ $subj['color'] }};">
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
                                            @elseif($subj['icon'] === 'shapes')
                                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M8.3 10a.7.7 0 0 1-.626-1.079L11.4 3a.7.7 0 0 1 1.198-.043L16.3 8.9a.7.7 0 0 1-.572 1.1Z" fill="currentColor" fill-opacity="0.2"/>
                                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                                    <circle cx="17.5" cy="17.5" r="3.5"/>
                                                </svg>
                                            @elseif($subj['icon'] === 'zap')
                                                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                                </svg>
                                            @else
                                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                                    <polyline points="2 17 12 22 22 17"/>
                                                    <polyline points="2 12 12 17 22 12"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="an-empty-box">
                                <div class="an-empty-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <rect x="4" y="13" width="4" height="7" rx="1.2"/>
                                        <rect x="10" y="8" width="4" height="12" rx="1.2"/>
                                        <rect x="16" y="4" width="4" height="16" rx="1.2"/>
                                    </svg>
                                </div>
                                <h4 class="an-empty-title">No subject breakdown yet</h4>
                                <p class="an-empty-desc mb-0">Complete an assessment to view your subject breakdown.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bottom Row: Recent Assessments & Accuracy by Topic -->
                <div class="an-bottom-grid">
                    <!-- Left: Recent Assessments Table -->
                    <div class="an-card">
                        <div class="an-card-header-flex">
                            <div>
                                <h2 class="an-card-title">Recent Assessments</h2>
                                <p class="an-card-subtitle">Your latest completed tests and scores.</p>
                            </div>
                            <a href="{{ route('student.weeklytests') }}" class="an-view-all-link">
                                <span>View All</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>

                        @if($recentAssessments->isEmpty())
                            <div class="an-empty-box">
                                <div class="an-empty-icon">
                                    <i class="feather icon-inbox" style="font-size: 1.45rem;"></i>
                                </div>
                                <h4 class="an-empty-title">No recent assessments</h4>
                                <p class="an-empty-desc mb-0">No completed assessments found in this period yet.</p>
                            </div>
                        @else
                            <div class="an-table-wrap" id="recentAssessmentsTableWrap">
                                <table class="an-table">
                                    <thead>
                                        <tr>
                                            <th>Assessment</th>
                                            <th class="col-subj">Subject</th>
                                            <th>Date</th>
                                            <th class="col-score">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentAssessmentsBody">
                                        @foreach($recentAssessments as $recent)
                                            @php
                                                $subjBadgeCls = $badgeClassFor($recent->subject_badge_type);
                                                $tierTextCls = 'status-' . ($recent->status_tier ?? 'focus');
                                            @endphp
                                            <tr class="an-assessment-row" data-href="{{ route('student.attempts.result', $recent->id) }}">
                                                <td>
                                                    <a href="{{ route('student.attempts.result', $recent->id) }}"
                                                       class="an-test-link"
                                                       title="{{ $recent->paper?->title ?? 'Assessment' }}">
                                                        {{ $recent->paper?->title ?? 'Assessment' }}
                                                    </a>
                                                    @if(!empty($recent->topic_name) || !empty($recent->score_fraction))
                                                        <div class="an-test-meta">
                                                            @if(!empty($recent->topic_name))
                                                                <span>{{ $recent->topic_name }}</span>
                                                            @endif
                                                            @if(!empty($recent->topic_name) && !empty($recent->score_fraction))
                                                                <span>&middot;</span>
                                                            @endif
                                                            @if(!empty($recent->score_fraction))
                                                                <span>{{ $recent->score_fraction }} marks</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="an-subj-pill {{ $subjBadgeCls }}">{{ $recent->subject_badge_name }}</span>
                                                </td>
                                                <td class="an-date-cell">
                                                    {{ $recent->display_date ? $recent->display_date->format('d M Y') : '' }}
                                                </td>
                                                <td class="text-right">
                                                    <span class="an-score-wrap">
                                                        <span class="an-score-val {{ $tierTextCls }}">{{ $recent->score_pct }}%</span>
                                                        <i class="feather icon-chevron-right an-row-chevron"></i>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="an-empty-box d-none" id="recentNoMatchState">
                                <div class="an-empty-icon">
                                    <i class="feather icon-search" style="font-size: 1.4rem;"></i>
                                </div>
                                <h4 class="an-empty-title">No matching assessments</h4>
                                <p class="an-empty-desc">Try clearing your search or switching back to all subjects.</p>
                                <button type="button" class="btn btn-primary btn-sm px-2 js-reset-an-filters" style="border-radius: 10px;">
                                    Reset Filters
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Right: Accuracy by Topic Progress Bars -->
                    <div class="an-card">
                        <div class="an-card-header-flex">
                            <div>
                                <h2 class="an-card-title">Accuracy by Topic</h2>
                                <p class="an-card-subtitle">Your performance in key topics.</p>
                            </div>
                            <a href="{{ route('student.focusareas', ['period' => $period]) }}" class="an-view-all-link">
                                <span>View All</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>

                        @if(empty($accuracyByTopic))
                            <div class="an-empty-box">
                                <div class="an-empty-icon">
                                    <i class="feather icon-pie-chart" style="font-size: 1.45rem;"></i>
                                </div>
                                <h4 class="an-empty-title">No topic data yet</h4>
                                <p class="an-empty-desc mb-0">No topic performance data yet.</p>
                            </div>
                        @else
                            <div class="an-topic-list" id="accuracyTopicsList">
                                @foreach($accuracyByTopic as $topic)
                                    @php
                                        $subjBadgeCls = $badgeClassFor($topic['subject']);
                                        $statusKey = $topic['status'] ?? 'focus';
                                    @endphp
                                    <a href="{{ $topic['url'] }}" class="an-topic-row">
                                        <div class="an-topic-info">
                                            <div class="an-topic-name" title="{{ $topic['name'] }}">{{ $topic['name'] }}</div>
                                            <div class="an-topic-submeta">
                                                <span class="an-topic-mini-badge {{ $subjBadgeCls }}">{{ $topic['subject_badge'] }}</span>
                                                <span>{{ $topic['attempts'] }} {{ Str::plural('test', $topic['attempts']) }}</span>
                                            </div>
                                        </div>

                                        <div class="an-topic-bar-col">
                                            <div class="an-prog-track" aria-label="{{ $topic['accuracy'] }}% accuracy">
                                                <div class="an-prog-fill fill-{{ $statusKey }}" style="width: {{ $topic['accuracy'] }}%;"></div>
                                            </div>
                                        </div>

                                        <div class="an-topic-right">
                                            <span class="an-topic-pct">{{ $topic['accuracy'] }}%</span>
                                            <span class="an-status-text status-{{ $statusKey }}">{{ $topic['status_label'] }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="an-empty-box d-none" id="topicNoMatchState">
                                <div class="an-empty-icon">
                                    <i class="feather icon-search" style="font-size: 1.4rem;"></i>
                                </div>
                                <h4 class="an-empty-title">No matching topics</h4>
                                <p class="an-empty-desc">Try clearing your search or switching back to all subjects.</p>
                                <button type="button" class="btn btn-primary btn-sm px-2 js-reset-an-filters" style="border-radius: 10px;">
                                    Reset Filters
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bottom Guidance / Insight Strip (consistent with Focus Areas & Assessments) -->
                <div class="an-tip-banner">
                    <div class="an-tip-left">
                        <span class="an-tip-icon" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 21h6v-1.5H9V21zm3-19a7 7 0 0 0-4.2 12.6c.7.54 1.2 1.38 1.35 2.4h5.7c.15-1.02.65-1.86 1.35-2.4A7 7 0 0 0 12 2zm-2 16h4v-1h-4v1z"/>
                            </svg>
                        </span>
                        <div>
                            @if(!empty($lowestTopic))
                                <h3 class="an-tip-title">Recommended Focus: {{ $lowestTopic['name'] }}</h3>
                                <p class="an-tip-desc">
                                    Your accuracy in {{ $lowestTopic['name'] }} is currently {{ $lowestTopic['accuracy'] }}%. Targeted practice here will lift your overall average fastest.
                                </p>
                            @elseif($totalAssessments > 0)
                                <h3 class="an-tip-title">Great Momentum!</h3>
                                <p class="an-tip-desc">
                                    You are averaging {{ $averageScore }}% across {{ $totalAssessments }} {{ Str::plural('assessment', $totalAssessments) }} in {{ strtolower($selectedPeriodLabel) }}. Keep building consistency!
                                </p>
                            @else
                                <h3 class="an-tip-title">Start Tracking Your Progress</h3>
                                <p class="an-tip-desc">
                                    Complete a practice test to unlock trend charts, subject breakdowns, and personalised topic recommendations.
                                </p>
                            @endif
                        </div>
                    </div>

                    @if(!empty($lowestTopic))
                        <a href="{{ $lowestTopic['url'] }}" class="an-tip-link">
                            <span>Practice {{ $lowestTopic['name'] }}</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    @elseif($totalAssessments > 0)
                        <a href="{{ route('student.focusareas', ['period' => $period]) }}" class="an-tip-link">
                            <span>Explore Focus Areas</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    @else
                        <a href="{{ route('student.assessments') }}" class="an-tip-link">
                            <span>Browse Assessments</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function isDarkMode() {
                return document.documentElement.getAttribute('data-theme') === 'dark'
                    || document.body.classList.contains('dark-layout');
            }

            function getThemePalette() {
                var dark = isDarkMode();
                return {
                    isDark: dark,
                    labelColor: dark ? '#94a3b8' : '#64748b',
                    gridColor: dark ? '#334155' : '#eef2f7',
                    textColor: dark ? '#f8fafc' : '#0b1f51'
                };
            }

            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            function subjBadgeClass(key) {
                var valid = ['english', 'maths', 'vr', 'nvr', 'science'];
                return valid.indexOf(key) !== -1 ? 'subj-' + key : 'subj-default';
            }

            var periodLabelLower = @json(strtolower($selectedPeriodLabel));
            var allTimeCount = @json($allTimeAssessments);
            var isAllPeriod = @json($period === 'all');
            var subjectAnalyticsMap = @json($subjectAnalyticsMap);
            var subjectItems = @json($subjectPerformance ?? []);
            var allAttemptsPayload = @json($recentAssessmentsPayload ?? []);
            var allTopicsPayload = @json($allTopicAccuracies ?? []);
            var initialSubjectFilter = @json($activeSubjectFilter);

            var activeSubject = subjectAnalyticsMap[initialSubjectFilter] ? initialSubjectFilter : 'all';

            // -----------------------------------------------------------------
            // 1. Performance Trend Area Chart
            // -----------------------------------------------------------------
            var trendChartEl = document.querySelector('#performanceTrendChart');
            var trendEmptyState = document.getElementById('trendEmptyState');
            var trendEmptyTitle = document.getElementById('trendEmptyTitle');
            var trendResetSubjectBtn = document.getElementById('trendResetSubjectBtn');
            var trendMetaChips = document.getElementById('trendMetaChips');
            var trendBestChip = document.getElementById('trendBestChip');
            var trendLatestChip = document.getElementById('trendLatestChip');

            var initialTrend = (subjectAnalyticsMap[activeSubject] || subjectAnalyticsMap.all).trend;
            var activeTrendLabels = initialTrend.labels || [];
            var trendChart = null;

            function buildTrendOptions(trendObj, palette) {
                return {
                    chart: {
                        type: 'area',
                        height: 265,
                        toolbar: { show: false },
                        parentHeightOffset: 0,
                        zoom: { enabled: false },
                        fontFamily: 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                    },
                    series: [{
                        name: 'Score',
                        data: trendObj.series || []
                    }],
                    colors: ['#2477F9'],
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
                                { offset: 0, color: '#2477F9', opacity: 0.28 },
                                { offset: 100, color: '#2477F9', opacity: 0.02 }
                            ]
                        }
                    },
                    markers: {
                        size: 4,
                        colors: ['#2477F9'],
                        strokeColors: palette.isDark ? '#1e293b' : '#ffffff',
                        strokeWidth: 2,
                        hover: {
                            size: 6,
                            sizeOffset: 2
                        }
                    },
                    dataLabels: { enabled: false },
                    annotations: {
                        yaxis: [{
                            y: 80,
                            borderColor: '#16a34a',
                            strokeDashArray: 4,
                            opacity: 0.45,
                            label: {
                                borderColor: 'transparent',
                                style: {
                                    color: '#16a34a',
                                    background: 'transparent',
                                    fontSize: '10px',
                                    fontWeight: 700
                                },
                                text: '80% Target',
                                position: 'right',
                                offsetX: -4,
                                offsetY: -4
                            }
                        }]
                    },
                    xaxis: {
                        categories: trendObj.categories || [],
                        labels: {
                            style: {
                                colors: palette.labelColor,
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
                            formatter: function (val) { return Math.round(val) + '%'; },
                            style: {
                                colors: palette.labelColor,
                                fontSize: '11px'
                            }
                        }
                    },
                    grid: {
                        borderColor: palette.gridColor,
                        strokeDashArray: 4,
                        yaxis: { lines: { show: true } },
                        xaxis: { lines: { show: false } }
                    },
                    tooltip: {
                        theme: palette.isDark ? 'dark' : 'light',
                        y: {
                            formatter: function (val, opts) {
                                var ctxLabel = activeTrendLabels[opts.dataPointIndex] || '';
                                return ctxLabel ? (val + '% (' + ctxLabel + ')') : (val + '%');
                            }
                        }
                    }
                };
            }

            if (trendChartEl && typeof ApexCharts !== 'undefined' && initialTrend.series && initialTrend.series.length > 0) {
                trendChart = new ApexCharts(trendChartEl, buildTrendOptions(initialTrend, getThemePalette()));
                trendChart.render();
            }

            // -----------------------------------------------------------------
            // 2. Subject Performance Bar Chart (matching Focus Areas style)
            // -----------------------------------------------------------------
            var subjectChartEl = document.querySelector('#subjectPerformanceChart');
            var legendGrid = document.getElementById('subjectLegendGrid');
            var subjectChart = null;

            function syncLegendAlignment() {
                if (!subjectChartEl || !legendGrid) return;
                var gridRectEl = subjectChartEl.querySelector('.apexcharts-grid');
                if (!gridRectEl) return;
                var containerRect = subjectChartEl.getBoundingClientRect();
                var plotRect = gridRectEl.getBoundingClientRect();
                if (plotRect.width > 0 && containerRect.width > 0) {
                    var leftOffset = Math.max(0, Math.round(plotRect.left - containerRect.left));
                    legendGrid.style.paddingLeft = leftOffset + 'px';
                    legendGrid.style.paddingRight = Math.max(0, Math.round(containerRect.right - plotRect.right)) + 'px';
                }
            }

            function hexToRgba(hex, alpha) {
                var clean = String(hex || '#2477F9').replace('#', '');
                if (clean.length === 3) {
                    clean = clean[0] + clean[0] + clean[1] + clean[1] + clean[2] + clean[2];
                }
                var num = parseInt(clean, 16);
                if (isNaN(num)) return hex;
                var r = (num >> 16) & 255;
                var g = (num >> 8) & 255;
                var b = num & 255;
                return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
            }

            function getBarColorsForSubject(selectedKey) {
                return subjectItems.map(function (s) {
                    return (selectedKey === 'all' || s.key === selectedKey) ? s.color : hexToRgba(s.color, 0.25);
                });
            }

            function getBarGradientColorsForSubject(selectedKey) {
                return subjectItems.map(function (s) {
                    var target = s.color_light || s.color;
                    return (selectedKey === 'all' || s.key === selectedKey) ? target : hexToRgba(target, 0.25);
                });
            }

            if (subjectChartEl && subjectItems.length > 0 && typeof ApexCharts !== 'undefined') {
                var palette = getThemePalette();
                var subjectChartOptions = {
                    chart: {
                        type: 'bar',
                        height: 205,
                        toolbar: { show: false },
                        parentHeightOffset: 0,
                        fontFamily: 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                        events: {
                            mounted: function () { setTimeout(syncLegendAlignment, 30); },
                            updated: function () { setTimeout(syncLegendAlignment, 30); },
                            dataPointSelection: function (event, chartContext, config) {
                                var picked = subjectItems[config.dataPointIndex];
                                if (picked && picked.key) {
                                    setActiveSubject(activeSubject === picked.key ? 'all' : picked.key);
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Score',
                        data: subjectItems.map(function (s) { return s.score; })
                    }],
                    colors: getBarColorsForSubject(activeSubject),
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            shadeIntensity: 0.25,
                            gradientToColors: getBarGradientColorsForSubject(activeSubject),
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
                            columnWidth: subjectItems.length <= 3 ? '50%' : '58%',
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
                            colors: [palette.textColor]
                        }
                    },
                    legend: { show: false },
                    xaxis: {
                        categories: subjectItems.map(function (s) { return s.title; }),
                        labels: { show: false },
                        axisBorder: { show: true, color: palette.gridColor },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        min: 0,
                        max: 100,
                        tickAmount: 4,
                        labels: {
                            formatter: function (val) { return Math.round(val) + '%'; },
                            style: {
                                colors: palette.labelColor,
                                fontSize: '11px',
                                fontWeight: 500
                            }
                        }
                    },
                    grid: {
                        borderColor: palette.gridColor,
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
                        theme: palette.isDark ? 'dark' : 'light',
                        y: {
                            formatter: function (val, opts) {
                                var tests = subjectItems[opts.dataPointIndex] ? subjectItems[opts.dataPointIndex].tests : 0;
                                return val + '% Average (' + tests + ' ' + (tests === 1 ? 'test' : 'tests') + ')';
                            }
                        }
                    }
                };

                subjectChart = new ApexCharts(subjectChartEl, subjectChartOptions);
                subjectChart.render();
                window.addEventListener('resize', function () {
                    setTimeout(syncLegendAlignment, 60);
                });
            }

            // -----------------------------------------------------------------
            // 3. Interactive Subject Filter Pills, Search, KPIs & Tables
            // -----------------------------------------------------------------
            var filterPills = document.querySelectorAll('.an-filter-pill[data-subject-filter]');
            var legendBtns = document.querySelectorAll('.an-subj-legend-btn[data-subject-legend]');
            var searchInput = document.getElementById('analyticsSearchInput');
            var clearSearchBtn = document.getElementById('clearAnalyticsSearchBtn');
            var resetFilterBtns = document.querySelectorAll('.js-reset-an-filters');

            var kpiTotalEl = document.getElementById('kpiTotalAssessments');
            var kpiTotalSubEl = document.getElementById('kpiTotalSubtext');
            var kpiAvgEl = document.getElementById('kpiAverageScore');
            var kpiAvgSubEl = document.getElementById('kpiAverageSubtext');
            var kpiProgEl = document.getElementById('kpiOverallProgress');
            var kpiProgSubEl = document.getElementById('kpiOverallSubtext');

            var recentBody = document.getElementById('recentAssessmentsBody');
            var recentTableWrap = document.getElementById('recentAssessmentsTableWrap');
            var recentNoMatch = document.getElementById('recentNoMatchState');

            var topicsList = document.getElementById('accuracyTopicsList');
            var topicNoMatch = document.getElementById('topicNoMatchState');

            function getSubjectTitle(key) {
                var found = subjectItems.find(function (s) { return s.key === key; });
                return found ? found.title : 'selected subject';
            }

            function updateKpis(summary) {
                if (!summary) return;

                if (kpiTotalEl) kpiTotalEl.textContent = summary.total_assessments;
                if (kpiTotalSubEl) {
                    if (summary.total_assessments > 0) {
                        kpiTotalSubEl.textContent = activeSubject === 'all'
                            ? (isAllPeriod ? 'All completed tests' : allTimeCount + ' completed all time')
                            : ('In ' + getSubjectTitle(activeSubject));
                    } else {
                        kpiTotalSubEl.textContent = 'No completed tests yet';
                    }
                }

                if (kpiAvgEl) {
                    kpiAvgEl.textContent = summary.total_assessments > 0 ? (summary.average_score + '%') : '—';
                }
                if (kpiAvgSubEl) {
                    kpiAvgSubEl.classList.remove('is-up', 'is-down');
                    if (summary.score_growth === null || summary.score_growth === undefined) {
                        kpiAvgSubEl.textContent = summary.total_assessments > 0 ? 'Need 2+ tests for trend' : 'No completed tests';
                    } else if (summary.score_growth > 0) {
                        kpiAvgSubEl.classList.add('is-up');
                        kpiAvgSubEl.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg> +' + summary.score_growth + '% recent trend';
                    } else if (summary.score_growth < 0) {
                        kpiAvgSubEl.classList.add('is-down');
                        kpiAvgSubEl.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg> ' + summary.score_growth + '% recent trend';
                    } else {
                        kpiAvgSubEl.textContent = '0% change';
                    }
                }

                if (kpiProgEl) {
                    kpiProgEl.classList.remove('text-danger');
                    if (summary.overall_progress !== null && summary.overall_progress !== undefined) {
                        kpiProgEl.textContent = (summary.overall_progress >= 0 ? '+' : '') + summary.overall_progress + '%';
                        if (summary.overall_progress < 0) kpiProgEl.classList.add('text-danger');
                    } else {
                        kpiProgEl.textContent = '—';
                    }
                }
                if (kpiProgSubEl) {
                    if (summary.overall_progress !== null && summary.overall_progress !== undefined) {
                        kpiProgSubEl.textContent = 'Vs. previous period';
                    } else if (summary.best_score !== null && summary.best_score !== undefined) {
                        kpiProgSubEl.textContent = 'Best score: ' + summary.best_score + '%';
                    } else {
                        kpiProgSubEl.textContent = 'No prior period data';
                    }
                }
            }

            function updateTrendChart(scope) {
                if (!scope) return;
                var trendObj = scope.trend || { categories: [], series: [], labels: [] };
                var summary = scope.summary || {};
                var hasPoints = Array.isArray(trendObj.series) && trendObj.series.length > 0;
                activeTrendLabels = trendObj.labels || [];

                if (trendMetaChips) {
                    trendMetaChips.classList.toggle('d-none', !hasPoints);
                    if (trendBestChip && summary.best_score !== null && summary.best_score !== undefined) {
                        trendBestChip.textContent = 'Best: ' + summary.best_score + '%';
                    }
                    if (trendLatestChip && summary.latest_score !== null && summary.latest_score !== undefined) {
                        trendLatestChip.textContent = 'Latest: ' + summary.latest_score + '%';
                    }
                }

                if (trendChartEl && trendEmptyState) {
                    trendChartEl.classList.toggle('d-none', !hasPoints);
                    trendEmptyState.classList.toggle('d-none', hasPoints);

                    if (!hasPoints && trendEmptyTitle) {
                        trendEmptyTitle.textContent = activeSubject === 'all'
                            ? ('No completed assessments found in ' + periodLabelLower + '.')
                            : ('No completed ' + getSubjectTitle(activeSubject) + ' assessments in ' + periodLabelLower + '.');
                    }
                    if (trendResetSubjectBtn) {
                        trendResetSubjectBtn.classList.toggle('d-none', activeSubject === 'all');
                    }
                }

                if (hasPoints && trendChartEl && typeof ApexCharts !== 'undefined') {
                    var palette = getThemePalette();
                    if (!trendChart) {
                        trendChart = new ApexCharts(trendChartEl, buildTrendOptions(trendObj, palette));
                        trendChart.render();
                    } else {
                        trendChart.updateOptions({
                            xaxis: {
                                categories: trendObj.categories || [],
                                labels: { style: { colors: palette.labelColor } }
                            },
                            yaxis: {
                                labels: { style: { colors: palette.labelColor } }
                            },
                            grid: { borderColor: palette.gridColor },
                            markers: { strokeColors: palette.isDark ? '#1e293b' : '#ffffff' },
                            tooltip: { theme: palette.isDark ? 'dark' : 'light' }
                        }, false, true);
                        trendChart.updateSeries([{ name: 'Score', data: trendObj.series }]);
                        setTimeout(function () {
                            window.dispatchEvent(new Event('resize'));
                        }, 30);
                    }
                }
            }

            function renderRecentAssessments(query) {
                if (!recentBody) return;

                var filtered = allAttemptsPayload.filter(function (item) {
                    var matchSubj = activeSubject === 'all' || item.subject_key === activeSubject;
                    if (!matchSubj) return false;
                    if (!query) return true;
                    var hay = ((item.title || '') + ' ' + (item.topic_name || '') + ' ' + (item.subject_badge || '')).toLowerCase();
                    return hay.indexOf(query) !== -1;
                }).slice(0, 6);

                if (filtered.length === 0) {
                    if (recentTableWrap) recentTableWrap.classList.add('d-none');
                    if (recentNoMatch) recentNoMatch.classList.remove('d-none');
                    return;
                }

                if (recentTableWrap) recentTableWrap.classList.remove('d-none');
                if (recentNoMatch) recentNoMatch.classList.add('d-none');

                recentBody.innerHTML = filtered.map(function (item) {
                    var metaParts = [];
                    if (item.topic_name) metaParts.push(escapeHtml(item.topic_name));
                    if (item.score_fraction) metaParts.push(escapeHtml(item.score_fraction) + ' marks');
                    var metaHtml = metaParts.length > 0
                        ? '<div class="an-test-meta">' + metaParts.join(' &middot; ') + '</div>'
                        : '';

                    return '<tr class="an-assessment-row" data-href="' + escapeHtml(item.url) + '">'
                        + '<td>'
                        + '<a href="' + escapeHtml(item.url) + '" class="an-test-link" title="' + escapeHtml(item.title) + '">' + escapeHtml(item.title) + '</a>'
                        + metaHtml
                        + '</td>'
                        + '<td class="text-center"><span class="an-subj-pill ' + subjBadgeClass(item.subject_key) + '">' + escapeHtml(item.subject_badge) + '</span></td>'
                        + '<td class="an-date-cell">' + escapeHtml(item.date_display) + '</td>'
                        + '<td class="text-right"><span class="an-score-wrap"><span class="an-score-val status-' + escapeHtml(item.status_tier) + '">' + escapeHtml(item.score_pct) + '%</span><i class="feather icon-chevron-right an-row-chevron"></i></span></td>'
                        + '</tr>';
                }).join('');
            }

            function renderAccuracyTopics(query) {
                if (!topicsList) return;

                var filtered = allTopicsPayload.filter(function (topic) {
                    var matchSubj = activeSubject === 'all' || topic.subject === activeSubject;
                    if (!matchSubj) return false;
                    if (!query) return true;
                    var hay = ((topic.name || '') + ' ' + (topic.subject_badge || '')).toLowerCase();
                    return hay.indexOf(query) !== -1;
                }).slice(0, 8);

                if (filtered.length === 0) {
                    topicsList.classList.add('d-none');
                    if (topicNoMatch) topicNoMatch.classList.remove('d-none');
                    return;
                }

                topicsList.classList.remove('d-none');
                if (topicNoMatch) topicNoMatch.classList.add('d-none');

                topicsList.innerHTML = filtered.map(function (topic) {
                    var testWord = topic.attempts === 1 ? 'test' : 'tests';
                    var statusKey = topic.status || 'focus';
                    return '<a href="' + escapeHtml(topic.url) + '" class="an-topic-row">'
                        + '<div class="an-topic-info">'
                        + '<div class="an-topic-name" title="' + escapeHtml(topic.name) + '">' + escapeHtml(topic.name) + '</div>'
                        + '<div class="an-topic-submeta">'
                        + '<span class="an-topic-mini-badge ' + subjBadgeClass(topic.subject) + '">' + escapeHtml(topic.subject_badge) + '</span>'
                        + '<span>' + escapeHtml(topic.attempts) + ' ' + testWord + '</span>'
                        + '</div>'
                        + '</div>'
                        + '<div class="an-topic-bar-col">'
                        + '<div class="an-prog-track" aria-label="' + escapeHtml(topic.accuracy) + '% accuracy">'
                        + '<div class="an-prog-fill fill-' + escapeHtml(statusKey) + '" style="width: ' + escapeHtml(topic.accuracy) + '%;"></div>'
                        + '</div>'
                        + '</div>'
                        + '<div class="an-topic-right">'
                        + '<span class="an-topic-pct">' + escapeHtml(topic.accuracy) + '%</span>'
                        + '<span class="an-status-text status-' + escapeHtml(statusKey) + '">' + escapeHtml(topic.status_label) + '</span>'
                        + '</div>'
                        + '</a>';
                }).join('');
            }

            function syncSubjectUiState() {
                filterPills.forEach(function (pill) {
                    pill.classList.toggle('active', pill.getAttribute('data-subject-filter') === activeSubject);
                });

                legendBtns.forEach(function (btn) {
                    var key = btn.getAttribute('data-subject-legend');
                    btn.classList.toggle('is-active', activeSubject !== 'all' && key === activeSubject);
                    btn.classList.toggle('is-dimmed', activeSubject !== 'all' && key !== activeSubject);
                });

                if (subjectChart) {
                    subjectChart.updateOptions({
                        colors: getBarColorsForSubject(activeSubject),
                        fill: {
                            gradient: {
                                gradientToColors: getBarGradientColorsForSubject(activeSubject)
                            }
                        }
                    }, false, false);
                }
            }

            function applyAllFilters() {
                var query = (searchInput ? searchInput.value : '').trim().toLowerCase();
                if (clearSearchBtn) {
                    clearSearchBtn.classList.toggle('d-none', query.length === 0);
                }

                var scope = subjectAnalyticsMap[activeSubject] || subjectAnalyticsMap.all;
                updateKpis(scope ? scope.summary : null);
                updateTrendChart(scope);
                renderRecentAssessments(query);
                renderAccuracyTopics(query);
                syncSubjectUiState();
            }

            function setActiveSubject(nextKey) {
                activeSubject = subjectAnalyticsMap[nextKey] ? nextKey : 'all';
                applyAllFilters();
            }

            filterPills.forEach(function (pill) {
                pill.addEventListener('click', function () {
                    setActiveSubject(this.getAttribute('data-subject-filter') || 'all');
                });
            });

            legendBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var key = this.getAttribute('data-subject-legend') || 'all';
                    setActiveSubject(activeSubject === key ? 'all' : key);
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', applyAllFilters);
            }

            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function () {
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.focus();
                    }
                    applyAllFilters();
                });
            }

            if (trendResetSubjectBtn) {
                trendResetSubjectBtn.addEventListener('click', function () {
                    setActiveSubject('all');
                });
            }

            resetFilterBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    activeSubject = 'all';
                    if (searchInput) searchInput.value = '';
                    applyAllFilters();
                });
            });

            if (recentBody) {
                recentBody.addEventListener('click', function (e) {
                    if (e.target.closest('a')) return;
                    var row = e.target.closest('.an-assessment-row');
                    if (row) {
                        var href = row.getAttribute('data-href');
                        if (href) window.location.href = href;
                    }
                });
            }

            // Apply initial ?subject=... query filter if provided
            if (activeSubject !== 'all') {
                applyAllFilters();
            }

            // Keep ApexCharts theme colors synced on live Light/Dark toggle
            window.addEventListener('appThemeChanged', function () {
                var p = getThemePalette();
                if (trendChart) {
                    trendChart.updateOptions({
                        xaxis: { labels: { style: { colors: p.labelColor } } },
                        yaxis: { labels: { style: { colors: p.labelColor } } },
                        grid: { borderColor: p.gridColor },
                        markers: { strokeColors: p.isDark ? '#1e293b' : '#ffffff' },
                        tooltip: { theme: p.isDark ? 'dark' : 'light' }
                    }, false, false);
                }
                if (subjectChart) {
                    subjectChart.updateOptions({
                        dataLabels: { style: { colors: [p.textColor] } },
                        xaxis: { axisBorder: { color: p.gridColor } },
                        yaxis: { labels: { style: { colors: p.labelColor } } },
                        grid: { borderColor: p.gridColor },
                        tooltip: { theme: p.isDark ? 'dark' : 'light' }
                    }, false, false);
                }
            });
        });
    </script>
@endpush
