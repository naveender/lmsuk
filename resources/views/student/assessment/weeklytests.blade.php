@extends('layouts.app')

@section('title', 'Weekly Tests & Resources')

@push('styles')
    <style>
        /* ==========================================================================
           STUDENT WEEKLY TESTS & LEARNING PORTAL - MODERN UI/UX (LIGHT & DARK MODE)
           ========================================================================== */
        .wt-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin-bottom: 0.85rem;
        }

        .wt-breadcrumb a {
            color: #2477F9;
            text-decoration: none;
        }

        .wt-breadcrumb a:hover {
            text-decoration: underline;
        }

        .wt-breadcrumb-sep {
            opacity: 0.55;
        }

        /* Header Area matching reference layout */
        .wt-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 1.35rem;
        }

        .wt-header-left {
            min-width: 0;
            flex: 1 1 360px;
        }

        .wt-title-badges {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 6px;
        }

        .wt-context-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 999px;
            background-color: #ebf3ff;
            color: #1757c1;
            border: 1px solid #bfdbfe;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .wt-page-title {
            color: var(--text-primary, #0b1f51);
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0 0 6px;
        }

        .wt-page-subtitle {
            color: var(--text-muted, #64748b);
            font-size: 0.94rem;
            font-weight: 500;
            margin: 0 0 10px;
        }

        /* Prominent Final Deadline Pill */
        .wt-deadline-pill {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 7px 14px;
            border-radius: 12px;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 0.84rem;
            font-weight: 600;
        }

        .wt-deadline-pill.is-unset {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
        }

        .wt-deadline-pill strong {
            font-weight: 800;
            color: #dc2626;
        }

        .wt-deadline-pill.is-unset strong {
            color: #334155;
        }

        .wt-deadline-tag {
            padding: 2px 9px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Right Header Controls: Subject + Week Switcher near each other */
        .wt-controls-form {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        .wt-primary-controls {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .wt-pill-group {
            display: inline-flex;
            align-items: center;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            border-radius: 12px;
            height: 44px;
            padding: 0 6px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .wt-pill-group:focus-within {
            border-color: #2477F9;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12);
        }

        .wt-pill-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            color: #2477F9;
            flex-shrink: 0;
        }

        .wt-pill-select {
            border: none;
            background: transparent;
            color: var(--text-primary, #0b1f51);
            font-size: 0.88rem;
            font-weight: 700;
            padding: 6px 28px 6px 4px;
            cursor: pointer;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 14px 14px;
            min-width: 145px;
        }

        .wt-week-divider {
            width: 1px;
            height: 22px;
            background-color: #e2e8f0;
            margin: 0 2px;
        }

        .wt-week-nav-btn {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.16s ease, color 0.16s ease;
        }

        .wt-week-nav-btn:hover:not(:disabled) {
            background-color: #eff6ff;
            color: #2477F9;
        }

        .wt-week-nav-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }

        .wt-secondary-controls {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .wt-mini-filter {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .wt-mini-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin: 0;
        }

        .wt-mini-select {
            height: 36px;
            padding: 4px 28px 4px 12px;
            border-radius: 10px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            color: var(--text-primary, #1e293b);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 13px 13px;
        }

        .wt-mini-select:focus {
            border-color: #2477F9;
        }

        .wt-clear-btn {
            height: 36px;
            padding: 0 12px;
            border-radius: 10px;
            background-color: #ffffff;
            border: 1px solid #fecaca;
            color: #dc2626 !important;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none !important;
            transition: background-color 0.16s ease;
        }

        .wt-clear-btn:hover {
            background-color: #fef2f2;
        }

        /* 5-Card KPI Bar matching reference image */
        .wt-kpi-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 1.5rem;
        }

        .wt-kpi-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 3px 14px rgba(15, 23, 42, 0.035);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            min-width: 0;
        }

        .wt-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        }

        .wt-kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .wt-kpi-icon.kpi-blue-sq {
            border-radius: 14px;
            background: linear-gradient(135deg, #3b82f6 0%, #2477F9 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.25);
        }

        .wt-kpi-icon.kpi-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.24);
        }

        .wt-kpi-icon.kpi-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.24);
        }

        .wt-kpi-icon.kpi-red {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .wt-score-ring-wrap {
            position: relative;
            width: 50px;
            height: 50px;
            flex-shrink: 0;
        }

        .wt-score-ring-wrap svg {
            width: 50px;
            height: 50px;
            transform: rotate(-90deg);
        }

        .wt-ring-track {
            fill: none;
            stroke: #e2e8f0;
            stroke-width: 5;
        }

        .wt-ring-fill {
            fill: none;
            stroke: #22c55e;
            stroke-width: 5;
            stroke-linecap: round;
        }

        .wt-kpi-meta {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .wt-kpi-value {
            font-size: 1.42rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .wt-kpi-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Main 40% / 60% Split Grid */
        .wt-main-grid {
            display: grid;
            grid-template-columns: minmax(320px, 5fr) minmax(0, 7fr);
            gap: 22px;
            align-items: start;
        }

        .wt-left-stack {
            display: flex;
            flex-direction: column;
            gap: 22px;
            min-width: 0;
        }

        .wt-right-col {
            min-width: 0;
        }

        .wt-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .wt-section-title {
            font-size: 1.08rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            margin: 0;
            letter-spacing: -0.2px;
        }

        .wt-count-chip {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-muted, #64748b);
            background-color: #f1f5f9;
            padding: 4px 11px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .wt-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
            overflow: hidden;
        }

        /* Section 1: Core Video Player */
        .wt-video-frame {
            background: #0f172a;
            border-radius: 12px;
            overflow: hidden;
            min-height: 230px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wt-video-frame iframe,
        .wt-video-frame video {
            width: 100%;
            height: 235px;
            border: none;
            display: block;
        }

        .wt-video-info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 14px;
            padding: 12px 14px;
            border-radius: 12px;
            background-color: #f8fafc;
            border: 1px solid #eef2f7;
        }

        .wt-video-play-sq {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background-color: #0b2240;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .wt-video-title {
            font-size: 0.94rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
        }

        .wt-video-status {
            font-size: 0.8rem;
            font-weight: 600;
            color: #16a34a;
        }

        .wt-playlist-box {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #eef2f7;
        }

        .wt-playlist-heading {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .playlist-scroll-area {
            max-height: 220px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .playlist-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background-color: #ffffff;
            cursor: pointer;
            transition: all 0.18s ease;
            position: relative;
            overflow: hidden;
        }

        .playlist-item:hover {
            background-color: #f8fbff !important;
            border-color: #2477F9 !important;
        }

        .playlist-item.active-playlist-item {
            border-color: #2477F9 !important;
            background-color: #f0f6ff !important;
        }

        .playlist-completed {
            background-color: #f0fdf4 !important;
            border-color: #86efac !important;
        }

        .playlist-completed:hover {
            background-color: #dcfce7 !important;
        }

        /* Section 3: Homework & Resource Cards inside Left Stack */
        .wt-hw-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .wt-hw-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 14px;
            border-radius: 14px;
            background-color: #f8fafc;
            border: 1px solid #e6edf7;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .wt-hw-item:hover {
            border-color: #bfdbfe;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.08);
        }

        .wt-hw-left {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .wt-hw-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .wt-hw-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 3px;
            line-height: 1.3;
        }

        .wt-hw-desc {
            font-size: 0.8rem;
            color: var(--text-muted, #64748b);
            margin: 0 0 6px;
            line-height: 1.4;
        }

        .wt-hw-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            font-size: 0.74rem;
            font-weight: 600;
            color: #64748b;
        }

        .wt-hw-chip {
            padding: 2px 8px;
            border-radius: 6px;
            background-color: #e8eef7;
            color: #334155;
        }

        .wt-hw-download-btn {
            padding: 7px 14px;
            border-radius: 9px;
            background-color: #ffffff;
            color: #2477F9 !important;
            border: 1.5px solid #bfdbfe;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none !important;
            white-space: nowrap;
            flex-shrink: 0;
            transition: all 0.16s ease;
        }

        .wt-hw-download-btn:hover {
            background-color: #2477F9;
            border-color: #2477F9;
            color: #ffffff !important;
        }

        /* Section 2: Right Column Online Assignment Evaluation Table */
        .wt-table-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
            overflow: hidden;
        }

        .wt-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .wt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .wt-table thead th {
            background-color: #0b1f51;
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            padding: 13px 14px;
            white-space: nowrap;
            border: none;
            text-align: left;
        }

        .wt-table thead th.text-center {
            text-align: center;
        }

        .wt-table thead th.text-right {
            text-align: right;
        }

        .wt-table tbody tr {
            border-bottom: 1px solid var(--border-subtle, #f1f5f9);
            transition: background-color 0.16s ease;
        }

        .wt-table tbody tr:last-child {
            border-bottom: none;
        }

        .wt-table tbody tr:hover {
            background-color: #f8fbff;
        }

        .wt-table tbody tr.wt-row-pending {
            background-color: #fffbeb;
        }

        .wt-table tbody tr.wt-row-pending:hover {
            background-color: #fef3c7;
        }

        .wt-table tbody td {
            padding: 13px 14px;
            vertical-align: middle;
            font-size: 0.86rem;
            color: var(--text-body, #334155);
        }

        .wt-type-sq {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .wt-type-quiz {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .wt-type-hw {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .wt-type-exam {
            background-color: #ede9fe;
            color: #6d28d9;
        }

        .wt-type-test {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .wt-module-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            line-height: 1.3;
        }

        .wt-row-pending .wt-module-title {
            color: #b45309;
        }

        .wt-module-sub {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 3px;
            font-size: 0.76rem;
            color: var(--text-muted, #64748b);
        }

        .wt-diff-chip {
            padding: 1px 7px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        .wt-diff-easy {
            background-color: #dcfce7;
            color: #15803d;
        }

        .wt-diff-medium {
            background-color: #fef3c7;
            color: #b45309;
        }

        .wt-diff-hard {
            background-color: #fee2e2;
            color: #dc2626;
        }

        /* Table Action Buttons matching reference image */
        .wt-btn-review {
            padding: 6px 16px;
            border-radius: 8px;
            background-color: #ffffff;
            color: #2477F9 !important;
            border: 1.5px solid #bfdbfe;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            text-decoration: none !important;
            transition: all 0.16s ease;
        }

        .wt-btn-review:hover {
            background-color: #2477F9;
            border-color: #2477F9;
            color: #ffffff !important;
        }

        .wt-btn-start {
            padding: 6px 16px;
            border-radius: 8px;
            background-color: #dc2626;
            color: #ffffff !important;
            border: 1.5px solid #dc2626;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(220, 38, 38, 0.24);
            transition: all 0.16s ease;
        }

        .wt-btn-start:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
            transform: translateY(-1px);
        }

        .wt-btn-resume {
            padding: 6px 16px;
            border-radius: 8px;
            background-color: #f59e0b;
            color: #ffffff !important;
            border: 1.5px solid #f59e0b;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            text-decoration: none !important;
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.24);
            transition: all 0.16s ease;
        }

        .wt-btn-resume:hover {
            background-color: #d97706;
            border-color: #d97706;
            transform: translateY(-1px);
        }

        /* Empty States */
        .wt-empty-box {
            text-align: center;
            padding: 2.25rem 1rem;
        }

        .wt-empty-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2477F9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 10px;
        }

        /* Pagination */
        .wt-pagination-wrap {
            display: flex;
            justify-content: center;
            padding: 14px;
            border-top: 1px solid var(--border-subtle, #eef2f7);
        }

        .wt-pagination-wrap .pagination {
            margin-bottom: 0;
            gap: 4px;
        }

        .wt-pagination-wrap .page-link {
            border-radius: 8px !important;
            border: 1px solid var(--border-color, #dfe7f2);
            color: var(--text-primary, #1e293b);
            font-weight: 600;
            font-size: 0.84rem;
            padding: 6px 11px;
        }

        .wt-pagination-wrap .page-item.active .page-link {
            background-color: #2477F9;
            border-color: #2477F9;
            color: #ffffff;
        }

        /* Responsive */
        @media (max-width: 1279.98px) {
            .wt-kpi-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 991.98px) {
            .wt-controls-form {
                align-items: flex-start;
                width: 100%;
            }
            .wt-main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .wt-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .wt-page-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .wt-kpi-grid {
                grid-template-columns: 1fr;
            }
            .wt-pill-group {
                width: 100%;
                justify-content: space-between;
            }
            .wt-pill-select {
                flex: 1;
            }
        }

        /* Dark Mode Support */
        body.dark-layout .wt-kpi-card,
        body.dark-layout .wt-card,
        body.dark-layout .wt-table-card,
        body.dark-layout .wt-pill-group,
        body.dark-layout .wt-mini-select,
        body.dark-layout .playlist-item,
        body.dark-layout .wt-hw-item,
        body.dark-layout .wt-video-info-row {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-layout .wt-page-title,
        body.dark-layout .wt-kpi-value,
        body.dark-layout .wt-section-title,
        body.dark-layout .wt-video-title,
        body.dark-layout .wt-playlist-heading,
        body.dark-layout .wt-hw-title,
        body.dark-layout .wt-module-title,
        body.dark-layout .wt-pill-select,
        body.dark-layout .wt-mini-select {
            color: #f8fafc !important;
        }

        body.dark-layout .wt-page-subtitle,
        body.dark-layout .wt-kpi-label,
        body.dark-layout .wt-mini-label,
        body.dark-layout .wt-hw-desc,
        body.dark-layout .wt-hw-meta,
        body.dark-layout .wt-module-sub {
            color: #94a3b8 !important;
        }

        body.dark-layout .wt-table tbody td {
            color: #cbd5e1 !important;
        }

        body.dark-layout .wt-table tbody tr {
            border-color: #334155 !important;
        }

        body.dark-layout .wt-table tbody tr:hover {
            background-color: rgba(36, 119, 249, 0.08);
        }

        body.dark-layout .wt-table tbody tr.wt-row-pending {
            background-color: rgba(245, 158, 11, 0.1);
        }

        body.dark-layout .wt-row-pending .wt-module-title {
            color: #fde68a !important;
        }

        body.dark-layout .wt-ring-track {
            stroke: #334155;
        }

        body.dark-layout .wt-week-divider {
            background-color: #334155;
        }

        body.dark-layout .wt-week-nav-btn {
            color: #cbd5e1;
        }

        body.dark-layout .wt-week-nav-btn:hover:not(:disabled) {
            background-color: rgba(36, 119, 249, 0.2);
            color: #93c5fd;
        }

        body.dark-layout .wt-count-chip,
        body.dark-layout .wt-hw-chip {
            background-color: #0f172a;
            color: #cbd5e1;
        }

        body.dark-layout .wt-deadline-pill {
            background-color: rgba(239, 68, 68, 0.14);
            border-color: rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        body.dark-layout .wt-deadline-pill strong {
            color: #fecaca;
        }

        body.dark-layout .wt-deadline-tag {
            background-color: rgba(239, 68, 68, 0.24);
            color: #fecaca;
        }

        body.dark-layout .wt-deadline-pill.is-unset {
            background-color: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }

        body.dark-layout .wt-deadline-pill.is-unset strong {
            color: #e2e8f0;
        }

        body.dark-layout .wt-btn-review,
        body.dark-layout .wt-hw-download-btn {
            background-color: #0f172a;
            border-color: #334155;
            color: #60a5fa !important;
        }

        body.dark-layout .wt-btn-review:hover,
        body.dark-layout .wt-hw-download-btn:hover {
            background-color: #2477F9;
            border-color: #2477F9;
            color: #ffffff !important;
        }

        body.dark-layout .wt-clear-btn {
            background-color: #1e293b;
            border-color: rgba(239, 68, 68, 0.4);
            color: #fca5a5 !important;
        }

        body.dark-layout .wt-pill-select,
        body.dark-layout .wt-mini-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        }

        body.dark-layout .wt-pill-select option,
        body.dark-layout .wt-mini-select option {
            background-color: #1e293b;
            color: #f8fafc;
        }

        body.dark-layout .wt-pagination-wrap {
            border-color: #334155;
        }

        body.dark-layout .wt-pagination-wrap .page-link {
            background-color: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }
    </style>
@endpush

@section('content')
    @php
        $scoreCircumference = 2 * pi() * 20;
        $scoreDashOffset = $metrics['has_score']
            ? $scoreCircumference * (1 - min(100, max(0, $metrics['score_pct'])) / 100)
            : $scoreCircumference;
    @endphp

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <!-- Breadcrumb Trail -->
                <nav class="wt-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('student.dashboard') }}">Dashboard</a>
                    <span class="wt-breadcrumb-sep">/</span>
                    <a href="{{ route('student.assessments') }}">Assessments</a>
                    <span class="wt-breadcrumb-sep">/</span>
                    <span>Weekly Tests</span>
                </nav>

                <!-- Portal Header + Subject & Week Selection + Final Deadline -->
                <div class="wt-page-header">
                    <div class="wt-header-left">
                        @if(!empty($yearGroupLabel) || $selectedSubject)
                            <div class="wt-title-badges">
                                @if(!empty($yearGroupLabel))
                                    <span class="wt-context-chip">{{ $yearGroupLabel }}</span>
                                @endif
                                @if($selectedSubject)
                                    <span class="wt-context-chip">{{ $selectedSubject->title }}</span>
                                @endif
                            </div>
                        @endif

                        <h1 class="wt-page-title">{{ $courseTitle }} — {{ $selectedWeekName }}</h1>
                        <p class="wt-page-subtitle">
                            Here is your learning plan, tasks and resources{{ $selectedWeekName !== 'All Weeks' ? ' for ' . $selectedWeekName : '' }}.
                        </p>

                        <!-- Final Deadline Display -->
                        <div class="wt-deadline-pill {{ empty($selectedWeekDueDate) ? 'is-unset' : '' }}">
                            <i class="feather icon-clock"></i>
                            <span>Final Deadline:</span>
                            <strong>{{ $metrics['deadline'] }}</strong>
                            @if(!empty($metrics['deadline_subtext']))
                                <span class="wt-deadline-tag">{{ $metrics['deadline_subtext'] }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Right Header Controls: Subject Selection near Week Selection -->
                    <form method="GET" action="{{ route('student.weeklytests') }}" id="weeklyFilterForm" class="wt-controls-form">
                        <div class="wt-primary-controls">
                            <!-- Subject Selection Pill (near Week selection) -->
                            <div class="wt-pill-group">
                                <span class="wt-pill-icon" title="Subject">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                    </svg>
                                </span>
                                <select name="subject_id"
                                        id="subject_filter"
                                        class="wt-pill-select"
                                        onchange="this.form.submit()"
                                        aria-label="Select subject">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Week Switcher Pill (< Week >) -->
                            <div class="wt-pill-group">
                                <span class="wt-pill-icon" title="Week">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                </span>
                                <button type="button" id="weekPrevBtn" class="wt-week-nav-btn" aria-label="Previous week" title="Previous week">
                                    <i class="feather icon-chevron-left"></i>
                                </button>
                                <span class="wt-week-divider"></span>
                                <select name="week"
                                        id="week_filter"
                                        class="wt-pill-select"
                                        onchange="this.form.submit()"
                                        aria-label="Select week">
                                    <option value="">All Weeks</option>
                                    @foreach($allWeeks as $wk)
                                        <option value="{{ $wk->id }}" data-course-id="{{ $wk->course_id }}" {{ request('week') == $wk->id ? 'selected' : '' }}>
                                            {{ $wk->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="wt-week-divider"></span>
                                <button type="button" id="weekNextBtn" class="wt-week-nav-btn" aria-label="Next week" title="Next week">
                                    <i class="feather icon-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Compact Course & Status Filters -->
                        <div class="wt-secondary-controls">
                            <div class="wt-mini-filter">
                                <label for="course_filter" class="wt-mini-label">Course:</label>
                                <select name="course_id" id="course_filter" class="wt-mini-select">
                                    <option value="">All Courses</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="wt-mini-filter">
                                <label for="status_filter" class="wt-mini-label">Status:</label>
                                <select name="status" id="status_filter" class="wt-mini-select" onchange="this.form.submit()">
                                    <option value="">All Statuses</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Paused</option>
                                    <option value="Not completed" {{ request('status') === 'Not completed' ? 'selected' : '' }}>Not Completed</option>
                                </select>
                            </div>

                            @if(request()->anyFilled(['subject_id', 'course_id', 'week', 'status']))
                                <a href="{{ route('student.weeklytests') }}" class="wt-clear-btn">
                                    <i class="feather icon-x"></i>
                                    <span>Clear</span>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Top Row: 5 KPI Summary Cards matching reference image -->
                <div class="wt-kpi-grid">
                    <!-- 1. Assignment Score Ring -->
                    <div class="wt-kpi-card">
                        <div class="wt-score-ring-wrap">
                            <svg viewBox="0 0 48 48">
                                <circle class="wt-ring-track" cx="24" cy="24" r="20"/>
                                <circle class="wt-ring-fill"
                                        cx="24"
                                        cy="24"
                                        r="20"
                                        stroke-dasharray="{{ number_format($scoreCircumference, 2, '.', '') }}"
                                        stroke-dashoffset="{{ number_format($scoreDashOffset, 2, '.', '') }}"/>
                            </svg>
                        </div>
                        <div class="wt-kpi-meta">
                            <span class="wt-kpi-value">{{ $metrics['score'] }}</span>
                            <span class="wt-kpi-label">Assignment Score</span>
                        </div>
                    </div>

                    <!-- 2. Tasks Completed -->
                    <div class="wt-kpi-card">
                        <div class="wt-kpi-icon kpi-blue-sq">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <div class="wt-kpi-meta">
                            <span class="wt-kpi-value">{{ $metrics['completion'] }}</span>
                            <span class="wt-kpi-label">Tasks Completed</span>
                        </div>
                    </div>

                    <!-- 3. Estimated Runtime -->
                    <div class="wt-kpi-card">
                        <div class="wt-kpi-icon kpi-amber">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <polyline points="12 7 12 12 15 15"/>
                            </svg>
                        </div>
                        <div class="wt-kpi-meta">
                            <span class="wt-kpi-value">{{ $metrics['estimated_text'] }}</span>
                            <span class="wt-kpi-label">Estimated Runtime</span>
                        </div>
                    </div>

                    <!-- 4. Time Taken -->
                    <div class="wt-kpi-card">
                        <div class="wt-kpi-icon kpi-purple">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="13" r="8"/>
                                <path d="M12 9v4l2 2"/>
                                <path d="M9 2h6"/>
                            </svg>
                        </div>
                        <div class="wt-kpi-meta">
                            <span class="wt-kpi-value">{{ $metrics['taken_text'] }}</span>
                            <span class="wt-kpi-label">Time Taken</span>
                        </div>
                    </div>

                    <!-- 5. Pending / Overdue Tasks -->
                    <div class="wt-kpi-card">
                        <div class="wt-kpi-icon kpi-red">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        </div>
                        <div class="wt-kpi-meta">
                            <span class="wt-kpi-value">{{ $metrics['pending_count'] }}</span>
                            <span class="wt-kpi-label">
                                {{ $metrics['is_deadline_passed'] ? 'Overdue' : 'Pending' }} {{ \Illuminate\Support\Str::plural('Task', $metrics['pending_count']) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- MAIN Split Layout: Left Stack (Videos + Homework) / Right (Evaluation Table) -->
                <div class="wt-main-grid">

                    <!-- Left Column -->
                    <div class="wt-left-stack">

                        <!-- 1. CORE VIDEO LECTURE STREAMS -->
                        <div>
                            <div class="wt-section-header">
                                <h2 class="wt-section-title">1. Core Video Lecture Streams</h2>
                                @if($mediaFiles->isNotEmpty())
                                    <span class="wt-count-chip">
                                        {{ $mediaFiles->count() }} {{ \Illuminate\Support\Str::plural('Lecture', $mediaFiles->count()) }}
                                    </span>
                                @endif
                            </div>

                            @if($mediaFiles->isNotEmpty())
                                @php $firstVideo = $mediaFiles->first(); @endphp
                                <div class="wt-card">
                                    <div class="wt-video-frame" id="videoContainer" data-active-id="{{ $firstVideo->id }}">
                                        @if($firstVideo->type === 'iframe')
                                            {!! $firstVideo->embed_url !!}
                                        @elseif(in_array($firstVideo->type, ['video_file', 'video_url', 's3', 'wasabi']))
                                            <video id="trackedHtml5Player" controls src="{{ $firstVideo->embed_url }}"></video>
                                        @else
                                            <iframe id="videoIframe"
                                                    src="{{ $firstVideo->embed_url }}"
                                                    frameborder="0"
                                                    allowfullscreen
                                                    allow="autoplay"></iframe>
                                        @endif
                                    </div>

                                    @php $firstProgress = $videoProgressMap[$firstVideo->id] ?? null; @endphp
                                    <div class="wt-video-info-row">
                                        <div class="d-flex align-items-center min-width-0" style="gap: 12px;">
                                            <span class="wt-video-play-sq">
                                                @if($firstProgress && $firstProgress->is_completed)
                                                    <i class="feather icon-check"></i>
                                                @else
                                                    <i class="feather icon-play"></i>
                                                @endif
                                            </span>
                                            <div class="min-width-0">
                                                <h6 class="wt-video-title text-truncate" id="activeVideoTitle">{{ $firstVideo->title }}</h6>
                                                <span class="wt-video-status" id="activeVideoDuration">
                                                    @if($firstVideo->duration)
                                                        <i class="feather icon-check-circle mr-25"></i>Runtime: {{ $firstVideo->duration }}
                                                    @else
                                                        <i class="feather icon-play-circle mr-25"></i>Video Lecture Stream
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div id="activeVideoProgressBadge" class="flex-shrink-0">
                                            @if($firstProgress && $firstProgress->is_completed)
                                                <span class="badge badge-success px-1 py-50"><i class="feather icon-check mr-25"></i>Completed</span>
                                            @elseif($firstProgress && $firstProgress->watch_time > 0)
                                                <span class="badge badge-light-warning px-1 py-50"><i class="feather icon-clock mr-25"></i>{{ gmdate('H:i:s', $firstProgress->watch_time) }} watched</span>
                                            @else
                                                <span class="badge badge-light-secondary px-1 py-50">Not started</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($mediaFiles->count() > 1)
                                        <div class="wt-playlist-box">
                                            <div class="wt-playlist-heading">
                                                <i class="feather icon-list text-primary"></i>
                                                <span>Weekly Video Playlist ({{ $mediaFiles->count() }} lectures)</span>
                                            </div>
                                            <div class="playlist-scroll-area">
                                                @foreach($mediaFiles as $video)
                                                    @php
                                                        $vProgress = $videoProgressMap[$video->id] ?? null;
                                                        $watchPct = 0;
                                                        if ($vProgress && $video->duration) {
                                                            $parts = explode(':', $video->duration);
                                                            $durSec = count($parts) === 3
                                                                ? ((int) $parts[0] * 3600 + (int) $parts[1] * 60 + (int) $parts[2])
                                                                : (count($parts) === 2 ? ((int) $parts[0] * 60 + (int) $parts[1]) : (int) $video->duration);
                                                            $watchPct = $durSec > 0 ? min(100, round(($vProgress->watch_time / $durSec) * 100)) : 0;
                                                        }
                                                        $isVCompleted = $vProgress && $vProgress->is_completed;
                                                    @endphp
                                                    <div class="playlist-item {{ $loop->first ? 'active-playlist-item' : '' }} {{ $isVCompleted ? 'playlist-completed' : '' }}"
                                                         data-id="{{ $video->id }}"
                                                         data-embed="{{ $video->embed_url }}"
                                                         data-title="{{ $video->title }}"
                                                         data-duration="{{ $video->duration }}"
                                                         data-type="{{ $video->type }}"
                                                         data-completed="{{ $isVCompleted ? '1' : '0' }}"
                                                         data-watchtime="{{ $vProgress ? $vProgress->watch_time : 0 }}">
                                                        <div class="mr-1">
                                                            @if($isVCompleted)
                                                                <i class="feather icon-check-circle text-success font-medium-3"></i>
                                                            @else
                                                                <i class="feather icon-play-circle text-primary font-medium-3"></i>
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1 min-width-0">
                                                            <h6 class="text-truncate font-small-3 font-weight-bold mb-0 wt-video-title">
                                                                {{ $video->title }}
                                                            </h6>
                                                            <small class="font-small-1 text-muted">
                                                                @if($video->duration)
                                                                    <i class="feather icon-clock mr-25"></i>{{ $video->duration }}
                                                                @else
                                                                    Video Lecture
                                                                @endif
                                                                @if($vProgress && $vProgress->watch_time > 0 && !$isVCompleted)
                                                                    <span class="text-warning ml-50">• {{ gmdate('i:s', $vProgress->watch_time) }} watched</span>
                                                                @endif
                                                            </small>
                                                        </div>
                                                        @if($isVCompleted)
                                                            <span class="badge badge-success font-small-1 ml-50" style="white-space: nowrap;">✓ Done</span>
                                                        @elseif($watchPct > 0)
                                                            <span class="badge badge-light-warning font-small-1 ml-50" style="white-space: nowrap;">{{ $watchPct }}%</span>
                                                        @endif

                                                        @if($watchPct > 0 && !$isVCompleted)
                                                            <div class="playlist-progress-bar"
                                                                 style="position: absolute; bottom: 0; left: 0; height: 3px; width: {{ $watchPct }}%; background: #f59e0b; transition: width 0.3s;">
                                                            </div>
                                                        @elseif($isVCompleted)
                                                            <div class="playlist-progress-bar"
                                                                 style="position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; background: #22c55e;">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="wt-card">
                                    <div class="wt-empty-box">
                                        <div class="wt-empty-icon">
                                            <i class="feather icon-video"></i>
                                        </div>
                                        <h6 class="font-weight-bold mb-25 wt-section-title" style="font-size: 0.96rem;">No Video Lectures Scheduled</h6>
                                        <p class="mb-0 text-muted font-small-3">
                                            There are no core video lecture streams assigned to this schedule yet.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- 3. HOMEWORK TASKS (Compact cards stacked under videos matching reference image) -->
                        <div>
                            <div class="wt-section-header">
                                <h2 class="wt-section-title">3. HOMEWORK TASKS</h2>
                                @if(isset($homeworks) && $homeworks->isNotEmpty())
                                    <span class="wt-count-chip">
                                        {{ $homeworks->count() }} {{ \Illuminate\Support\Str::plural('Assignment', $homeworks->count()) }}
                                    </span>
                                @endif
                            </div>

                            <div class="wt-card">
                                @if(isset($homeworks) && $homeworks->isNotEmpty())
                                    <div class="wt-hw-list">
                                        @foreach($homeworks as $homework)
                                            <div class="wt-hw-item">
                                                <div class="wt-hw-left">
                                                    <span class="wt-hw-icon" title="{{ strtoupper($homework->file_type ?? 'HW') }}">
                                                        <i class="feather icon-file-text"></i>
                                                    </span>
                                                    <div class="min-width-0">
                                                        <h4 class="wt-hw-title">{{ $homework->title }}</h4>
                                                        @if($homework->description)
                                                            <p class="wt-hw-desc">
                                                                {{ \Illuminate\Support\Str::limit(strip_tags($homework->description), 130) }}
                                                            </p>
                                                        @endif
                                                        <div class="wt-hw-meta">
                                                            <span class="wt-hw-chip">{{ $homework->subject->title ?? 'General' }}</span>
                                                            @if($homework->topic)
                                                                <span>{{ $homework->topic->name }}</span>
                                                            @endif
                                                            @if($selectedWeekDueDate)
                                                                <span>• Due {{ \Carbon\Carbon::parse($selectedWeekDueDate)->format('d M Y') }}</span>
                                                            @endif
                                                            @if($homework->formatted_file_size)
                                                                <span>• {{ strtoupper($homework->file_type ?? 'FILE') }} ({{ $homework->formatted_file_size }})</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($homework->file_path)
                                                    <a href="{{ route('student.homeworks.download', $homework->id) }}"
                                                       class="wt-hw-download-btn"
                                                       title="Download {{ $homework->file_name ?: 'Attached File' }}">
                                                        <i class="feather icon-download"></i>
                                                        <span>Download</span>
                                                    </a>
                                                @else
                                                    <span class="wt-count-chip">No file</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="wt-empty-box">
                                        <div class="wt-empty-icon">
                                            <i class="feather icon-book-open"></i>
                                        </div>
                                        <h6 class="font-weight-bold mb-25 wt-section-title" style="font-size: 0.96rem;">No Homework Tasks Scheduled</h6>
                                        <p class="mb-0 text-muted font-small-3">
                                            There are no homework tasks assigned for this course and week schedule.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: 2. ONLINE ASSIGNMENT TASKS EVALUATION -->
                    <div class="wt-right-col">
                        <div class="wt-section-header">
                            <h2 class="wt-section-title">2. Online Assignment Tasks Evaluation</h2>
                            @if($papers->total() > 0)
                                <span class="wt-count-chip">
                                    Showing {{ $papers->firstItem() }}–{{ $papers->lastItem() }} of {{ $papers->total() }}
                                </span>
                            @endif
                        </div>

                        <div class="wt-table-card">
                            <div class="wt-table-wrap">
                                <table class="wt-table">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Assignment / Challenge Module Title</th>
                                            <th class="text-center">Target</th>
                                            <th class="text-center">Completed</th>
                                            <th class="text-center">Score</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($papers as $paper)
                                            @php
                                                $typeKey = strtolower($paper->type ?? 'test');
                                                [$typeChar, $typeSqClass] = match ($typeKey) {
                                                    'quiz'     => ['Q', 'wt-type-quiz'],
                                                    'homework' => ['H', 'wt-type-hw'],
                                                    'exam'     => ['E', 'wt-type-exam'],
                                                    default    => ['T', 'wt-type-test'],
                                                };

                                                $diffKey = strtolower($paper->difficulty ?? '');
                                                $diffClass = match ($diffKey) {
                                                    'easy'   => 'wt-diff-easy',
                                                    'medium' => 'wt-diff-medium',
                                                    'hard'   => 'wt-diff-hard',
                                                    default  => '',
                                                };
                                                $isPendingRow = $paper->attempt_status === 'Not completed';
                                            @endphp
                                            <tr class="{{ $isPendingRow ? 'wt-row-pending' : '' }}">
                                                <td>
                                                    <span class="wt-type-sq {{ $typeSqClass }}" title="{{ ucfirst($paper->type ?? 'Test') }}">
                                                        {{ $typeChar }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="wt-module-title">{{ $paper->title }}</div>
                                                    <div class="wt-module-sub">
                                                        <span>{{ $paper->subject->title ?? 'General' }}</span>
                                                        <span>&bull;</span>
                                                        <span>{{ ucfirst($paper->type ?? 'Test') }}</span>
                                                        @if($diffClass)
                                                            <span class="wt-diff-chip {{ $diffClass }}">{{ $paper->difficulty }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center text-nowrap font-weight-600">
                                                    @if($paper->total_time > 0)
                                                        {{ $paper->total_time }}m 00s
                                                    @else
                                                        No Limit
                                                    @endif
                                                </td>
                                                <td class="text-center text-nowrap">
                                                    @if($paper->attempt_status === 'completed' && $paper->completed_at)
                                                        {{ $paper->completed_at }}
                                                    @else
                                                        &mdash;
                                                    @endif
                                                </td>
                                                <td class="text-center font-weight-bold text-nowrap">
                                                    @if($paper->attempt_status === 'completed')
                                                        @php
                                                            $percentage = $paper->score_percentage ?? 0;
                                                            $colorClass = $percentage >= 80
                                                                ? 'text-success'
                                                                : ($percentage >= 50 ? 'text-warning' : 'text-danger');
                                                        @endphp
                                                        <span class="{{ $colorClass }}">{{ $percentage }}%</span>
                                                    @elseif($paper->attempt_status === 'paused')
                                                        <span class="text-warning">Paused</span>
                                                    @else
                                                        <span class="text-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="text-right text-nowrap">
                                                    @if($paper->attempt_status === 'completed')
                                                        @if(!$paper->hide_result && $paper->completed_attempt_id)
                                                            <a href="{{ route('student.attempts.result', $paper->completed_attempt_id) }}"
                                                               class="wt-btn-review">
                                                                Review
                                                            </a>
                                                        @else
                                                            <span class="wt-count-chip">Submitted</span>
                                                        @endif
                                                    @elseif($paper->attempt_status === 'paused' && $paper->active_attempt)
                                                        <a href="{{ route('student.attempts.take', $paper->active_attempt->id) }}"
                                                           class="wt-btn-resume">
                                                            Resume
                                                        </a>
                                                    @else
                                                        <form action="{{ route('student.papers.start', $paper->id) }}"
                                                              method="POST"
                                                              class="d-inline mb-0">
                                                            @csrf
                                                            <button type="submit" class="wt-btn-start">
                                                                Start
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    <div class="wt-empty-box">
                                                        <div class="wt-empty-icon">
                                                            <i class="feather icon-award"></i>
                                                        </div>
                                                        <h5 class="font-weight-bold mb-25 wt-section-title">No Weekly Tests Found</h5>
                                                        <p class="mb-0 text-muted font-small-3">
                                                            We couldn't find any weekly tests matching your selected filters.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($papers->hasPages())
                                <div class="wt-pagination-wrap">
                                    {{ $papers->links() }}
                                </div>
                            @endif
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
            const filterForm = document.getElementById('weeklyFilterForm');
            const courseFilter = document.getElementById('course_filter');
            const weekFilter = document.getElementById('week_filter');
            const weekPrevBtn = document.getElementById('weekPrevBtn');
            const weekNextBtn = document.getElementById('weekNextBtn');

            function syncWeekNavButtons() {
                if (!weekFilter || !weekPrevBtn || !weekNextBtn) return;
                const totalOptions = weekFilter.options.length;
                const idx = weekFilter.selectedIndex;
                weekPrevBtn.disabled = totalOptions <= 1 || idx <= 0;
                weekNextBtn.disabled = totalOptions <= 1 || idx >= totalOptions - 1;
            }

            if (courseFilter && weekFilter) {
                const originalWeeks = Array.from(weekFilter.options).map(opt => ({
                    value: opt.value,
                    text: opt.text,
                    courseId: opt.getAttribute('data-course-id') || '',
                    selected: opt.selected
                }));

                function updateWeeks(isInitialLoad = false) {
                    const selectedCourseId = courseFilter.value;
                    const currentSelectedWeek = weekFilter.value;

                    weekFilter.innerHTML = '';
                    let hasSelected = false;

                    originalWeeks.forEach(opt => {
                        if (opt.value === '' || !selectedCourseId || opt.courseId === selectedCourseId) {
                            const newOpt = document.createElement('option');
                            newOpt.value = opt.value;
                            newOpt.text = opt.text;
                            if (opt.courseId) {
                                newOpt.setAttribute('data-course-id', opt.courseId);
                            }

                            if (opt.value === currentSelectedWeek) {
                                newOpt.selected = true;
                                hasSelected = true;
                            }

                            weekFilter.appendChild(newOpt);
                        }
                    });

                    if (!hasSelected && !isInitialLoad) {
                        weekFilter.value = '';
                    }

                    syncWeekNavButtons();
                }

                courseFilter.addEventListener('change', function () {
                    updateWeeks(false);
                    if (filterForm) {
                        filterForm.submit();
                    }
                });

                updateWeeks(true);
            } else {
                syncWeekNavButtons();
            }

            if (weekPrevBtn && weekFilter && filterForm) {
                weekPrevBtn.addEventListener('click', function () {
                    if (weekFilter.selectedIndex > 0) {
                        weekFilter.selectedIndex = weekFilter.selectedIndex - 1;
                        filterForm.submit();
                    }
                });
            }

            if (weekNextBtn && weekFilter && filterForm) {
                weekNextBtn.addEventListener('click', function () {
                    if (weekFilter.selectedIndex < weekFilter.options.length - 1) {
                        weekFilter.selectedIndex = weekFilter.selectedIndex + 1;
                        filterForm.submit();
                    }
                });
            }

            // --- VIDEO TRACKING MODULE ---
            let activeVideoId = null;
            let lastPosition = 0;
            let accumulatedWatchTime = 0;
            let heartbeatInterval = null;
            let isTrackingActive = false;

            const container = document.getElementById('videoContainer');
            if (container) {
                activeVideoId = container.getAttribute('data-active-id');
                setupVideoTracking();
            }

            function setupVideoTracking() {
                if (heartbeatInterval) {
                    clearInterval(heartbeatInterval);
                }
                accumulatedWatchTime = 0;
                lastPosition = 0;
                isTrackingActive = false;

                const videoEl = document.getElementById('trackedHtml5Player');
                if (videoEl) {
                    let lastTime = videoEl.currentTime;

                    videoEl.addEventListener('play', () => {
                        if (!isTrackingActive) {
                            isTrackingActive = true;
                            lastTime = videoEl.currentTime;
                            startHeartbeatLoop(videoEl);
                        }
                    });

                    videoEl.addEventListener('pause', () => {
                        isTrackingActive = false;
                        sendWatchProgress(videoEl.currentTime);
                    });

                    videoEl.addEventListener('ended', () => {
                        isTrackingActive = false;
                        sendWatchProgress(videoEl.currentTime);
                    });

                    videoEl.addEventListener('timeupdate', () => {
                        if (videoEl.paused || videoEl.seeking) {
                            lastTime = videoEl.currentTime;
                            return;
                        }
                        const diff = videoEl.currentTime - lastTime;
                        if (diff > 0 && diff < 2.5) {
                            accumulatedWatchTime += diff;
                        }
                        lastTime = videoEl.currentTime;
                    });
                }
            }

            function startHeartbeatLoop(videoEl) {
                heartbeatInterval = setInterval(() => {
                    if (!isTrackingActive || videoEl.paused) {
                        clearInterval(heartbeatInterval);
                        return;
                    }
                    sendWatchProgress(videoEl.currentTime);
                }, 5000);
            }

            function sendWatchProgress(currentPos) {
                if (!activeVideoId) return;
                const roundedIncrement = Math.round(accumulatedWatchTime);
                if (roundedIncrement === 0 && Math.abs(currentPos - lastPosition) < 1) {
                    return;
                }

                accumulatedWatchTime = 0;
                lastPosition = currentPos;

                axios.post('{{ route("student.media.progress") }}', {
                    media_file_id: activeVideoId,
                    last_position: currentPos,
                    increment_watch_time: roundedIncrement
                }, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => {
                        if (response.data.success) {
                            updateProgressBadge(response.data);
                        }
                    })
                    .catch(err => {
                        console.error('Failed to send watch progress', err);
                    });
            }

            function updateProgressBadge(data) {
                const badgeEl = document.getElementById('activeVideoProgressBadge');
                if (!badgeEl) return;

                if (data.is_completed) {
                    badgeEl.innerHTML = '<span class="badge badge-success px-1 py-50"><i class="feather icon-check mr-25"></i>Completed</span>';
                } else if (data.watch_time > 0) {
                    const h = Math.floor(data.watch_time / 3600);
                    const m = Math.floor((data.watch_time % 3600) / 60);
                    const s = data.watch_time % 60;
                    const timeStr = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                    badgeEl.innerHTML = `<span class="badge badge-light-warning px-1 py-50"><i class="feather icon-clock mr-25"></i>${timeStr} watched</span>`;
                }

                const playlistItem = document.querySelector(`.playlist-item[data-id="${activeVideoId}"]`);
                if (playlistItem && data.is_completed) {
                    playlistItem.classList.add('playlist-completed');
                    playlistItem.setAttribute('data-completed', '1');
                    const iconEl = playlistItem.querySelector('.mr-1 i');
                    if (iconEl) {
                        iconEl.className = 'feather icon-check-circle text-success font-medium-3';
                    }
                    const badge = playlistItem.querySelector('.badge');
                    if (badge) {
                        badge.className = 'badge badge-success font-small-1 ml-50';
                        badge.textContent = '✓ Done';
                        badge.style.whiteSpace = 'nowrap';
                    }
                    const progBar = playlistItem.querySelector('.playlist-progress-bar');
                    if (progBar) {
                        progBar.style.width = '100%';
                        progBar.style.background = '#22c55e';
                    } else {
                        const bar = document.createElement('div');
                        bar.className = 'playlist-progress-bar';
                        bar.style.cssText = 'position:absolute;bottom:0;left:0;height:3px;width:100%;background:#22c55e;';
                        playlistItem.appendChild(bar);
                    }
                }
            }

            document.querySelectorAll('.playlist-item').forEach(item => {
                item.addEventListener('click', function () {
                    const currentVideoEl = document.getElementById('trackedHtml5Player');
                    if (currentVideoEl) {
                        sendWatchProgress(currentVideoEl.currentTime);
                    }

                    document.querySelectorAll('.playlist-item').forEach(i => {
                        i.classList.remove('active-playlist-item');
                    });
                    this.classList.add('active-playlist-item');

                    const videoId = this.getAttribute('data-id');
                    const embedUrl = this.getAttribute('data-embed');
                    const title = this.getAttribute('data-title');
                    const duration = this.getAttribute('data-duration');
                    const type = this.getAttribute('data-type');

                    const titleEl = document.getElementById('activeVideoTitle');
                    const durEl = document.getElementById('activeVideoDuration');

                    activeVideoId = videoId;
                    if (container) {
                        container.setAttribute('data-active-id', videoId);
                    }

                    if (titleEl) titleEl.textContent = title;
                    if (durEl) {
                        durEl.innerHTML = duration
                            ? `<i class="feather icon-check-circle mr-25"></i>Runtime: ${duration}`
                            : '<i class="feather icon-play-circle mr-25"></i>Video Lecture Stream';
                    }

                    if (container) {
                        let playerHtml = '';
                        if (type === 'iframe') {
                            playerHtml = embedUrl;
                        } else if (type === 'video_file' || type === 'video_url' || type === 's3' || type === 'wasabi') {
                            playerHtml = `<video id="trackedHtml5Player" controls src="${embedUrl}" autoplay></video>`;
                        } else {
                            playerHtml = `<iframe src="${embedUrl}" frameborder="0" allowfullscreen allow="autoplay"></iframe>`;
                        }
                        container.innerHTML = playerHtml;
                    }

                    setupVideoTracking();
                });
            });
        });
    </script>
@endpush
