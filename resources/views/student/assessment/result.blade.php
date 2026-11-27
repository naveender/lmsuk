@extends('layouts.app')

@section('title', 'Test Results: ' . $paper->title)

@push('styles')
    <style>
        /* ==========================================================================
           STUDENT TEST RESULTS - MODERN UI/UX (LIGHT & DARK MODE COMPATIBLE)
           ========================================================================== */
        .rs-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            margin-bottom: 0.85rem;
        }

        .rs-breadcrumb a {
            color: #2477F9;
            text-decoration: none;
        }

        .rs-breadcrumb a:hover {
            text-decoration: underline;
        }

        .rs-breadcrumb-sep {
            opacity: 0.55;
        }

        .rs-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.4rem;
        }

        .rs-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .rs-subject-avatar {
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

        .rs-subject-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
        }

        .rs-title-meta-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 4px;
        }

        .rs-page-title {
            color: var(--text-primary, #0b1f51);
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0;
        }

        .rs-subj-tag {
            padding: 4px 11px;
            border-radius: 999px;
            background-color: var(--subj-soft-bg, #ebf3ff);
            color: var(--subj-soft-text, #1757C1);
            border: 1px solid var(--subj-soft-border, #bfdbfe);
            font-size: 0.76rem;
            font-weight: 700;
        }

        .rs-page-subtitle {
            color: var(--text-muted, #64748b);
            font-size: 0.92rem;
            font-weight: 500;
            margin: 0;
        }

        .rs-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .rs-btn-pill {
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
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .rs-btn-pill:hover {
            border-color: #2477F9;
            color: #2477F9 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(36, 119, 249, 0.14);
        }

        .rs-btn-primary {
            background: linear-gradient(135deg, #2477F9 0%, #1757C1 100%);
            border: none;
            color: #ffffff !important;
            border-radius: 12px;
            padding: 9px 18px;
            font-size: 0.86rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none !important;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.28);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .rs-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(36, 119, 249, 0.38);
            color: #ffffff !important;
        }

        /* Overview Summary Grid */
        .rs-overview-grid {
            display: grid;
            grid-template-columns: minmax(270px, 0.95fr) minmax(0, 2.05fr);
            gap: 18px;
            margin-bottom: 1.4rem;
            align-items: stretch;
        }

        .rs-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.035);
        }

        /* Left Score Donut Card */
        .rs-score-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .rs-donut-wrap {
            position: relative;
            width: 142px;
            height: 142px;
            margin-bottom: 14px;
        }

        .rs-donut-svg {
            width: 100%;
            height: 100%;
        }

        .rs-donut-track {
            stroke: #edf2f7;
        }

        .rs-donut-center {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .rs-donut-pct {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.05;
            font-variant-numeric: tabular-nums;
        }

        .rs-donut-lbl {
            font-size: 0.74rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 3px;
        }

        .rs-verdict-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .verdict-strong {
            background-color: #dcfce7;
            color: #15803d;
        }

        .verdict-developing {
            background-color: #fef3c7;
            color: #b45309;
        }

        .verdict-focus {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .rs-score-subtext {
            font-size: 0.86rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            margin: 0;
        }

        /* Right Side: 2x2 KPI Grid + Breakdown Bar */
        .rs-right-overview {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .rs-kpi-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .rs-kpi-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 3px 14px rgba(15, 23, 42, 0.03);
        }

        .rs-kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rs-kpi-icon.kpi-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2477F9 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.24);
        }

        .rs-kpi-icon.kpi-green {
            background: linear-gradient(135deg, #2ecc71 0%, #16a34a 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.24);
        }

        .rs-kpi-icon.kpi-red {
            background: linear-gradient(135deg, #ff4d4f 0%, #ef4444 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.24);
        }

        .rs-kpi-icon.kpi-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.24);
        }

        .rs-kpi-meta {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .rs-kpi-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-body, #334155);
            line-height: 1.2;
        }

        .rs-kpi-val {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.15;
            margin: 2px 0;
            font-variant-numeric: tabular-nums;
        }

        .rs-kpi-sub {
            font-size: 0.76rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
        }

        /* Stacked Breakdown Bar Card */
        .rs-breakdown-card {
            padding: 16px 20px;
        }

        .rs-breakdown-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .rs-breakdown-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0;
        }

        .rs-breakdown-legend {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
        }

        .rs-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .rs-legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
        }

        .rs-stacked-track {
            width: 100%;
            height: 12px;
            border-radius: 999px;
            background-color: #edf2f7;
            overflow: hidden;
            display: flex;
        }

        .rs-seg-correct {
            background: linear-gradient(90deg, #22c55e, #16a34a);
            height: 100%;
        }

        .rs-seg-incorrect {
            background: linear-gradient(90deg, #fb7185, #ef4444);
            height: 100%;
        }

        .rs-seg-unanswered {
            background-color: #cbd5e1;
            height: 100%;
        }

        /* Topic Breakdown Strip */
        .rs-topic-breakdown-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .rs-topic-chip-card {
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 12px;
            padding: 12px 14px;
            background-color: #f8fafc;
            text-decoration: none !important;
            transition: border-color 0.18s ease, transform 0.18s ease;
            display: block;
        }

        .rs-topic-chip-card:hover {
            border-color: #2477F9;
            transform: translateY(-1px);
        }

        .rs-topic-chip-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 6px;
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
        }

        .rs-topic-chip-bar {
            height: 7px;
            border-radius: 999px;
            background-color: #e2e8f0;
            overflow: hidden;
            margin-bottom: 4px;
        }

        /* Question-by-Question Review Section */
        .rs-review-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 1.25rem;
        }

        .rs-review-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 2px;
        }

        .rs-review-subtitle {
            font-size: 0.86rem;
            color: var(--text-muted, #64748b);
            margin: 0;
        }

        .rs-filter-pills {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .rs-filter-pill {
            border: 1px solid transparent;
            background-color: #e8eef7;
            color: #334155;
            font-size: 0.83rem;
            font-weight: 600;
            padding: 7px 15px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.18s ease;
            outline: none !important;
        }

        .rs-filter-pill:hover {
            background-color: #dce5f3;
            color: #0b1f51;
        }

        .rs-filter-pill.active[data-filter="all"] {
            background: linear-gradient(135deg, #2477F9 0%, #1757C1 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.25);
        }

        .rs-filter-pill.active[data-filter="correct"] {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
        }

        .rs-filter-pill.active[data-filter="incorrect"] {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .rs-filter-pill.active[data-filter="unanswered"] {
            background: #475569;
            color: #ffffff;
        }

        .rs-review-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 20px;
            align-items: start;
        }

        /* Question Review Card */
        .rs-q-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .rs-q-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
            overflow: hidden;
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .rs-q-card.is-correct {
            border-left: 4px solid #22c55e;
        }

        .rs-q-card.is-incorrect {
            border-left: 4px solid #ef4444;
        }

        .rs-q-card.is-unanswered {
            border-left: 4px solid #94a3b8;
        }

        .rs-q-header {
            padding: 14px 20px;
            background-color: #f8fafc;
            border-bottom: 1px solid var(--border-subtle, #eef2f7);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .rs-q-header-left {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .rs-q-num {
            font-size: 0.96rem;
            font-weight: 800;
            color: #2477F9;
        }

        .rs-meta-chip {
            padding: 3px 10px;
            border-radius: 999px;
            background-color: #eef2f7;
            color: #475569;
            font-size: 0.74rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .rs-flag-chip {
            padding: 3px 10px;
            border-radius: 999px;
            background-color: #fef3c7;
            color: #b45309;
            font-size: 0.74rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .rs-q-header-right {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .rs-marks-pill {
            padding: 4px 10px;
            border-radius: 8px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: var(--text-primary, #0b1f51);
            font-size: 0.76rem;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }

        .rs-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .rs-status-correct {
            background-color: #dcfce7;
            color: #15803d;
        }

        .rs-status-incorrect {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .rs-status-unanswered {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .rs-q-body {
            padding: 20px;
        }

        .rs-q-title {
            font-size: 1.06rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0 0 8px;
            line-height: 1.45;
        }

        .rs-q-desc {
            font-size: 0.95rem;
            color: var(--text-body, #334155);
            line-height: 1.65;
            margin-bottom: 16px;
        }

        .rs-q-desc p:last-child {
            margin-bottom: 0;
        }

        .rs-q-images {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 16px;
        }

        .img-zoomable {
            max-height: 220px;
            object-fit: contain;
            cursor: zoom-in;
            border-radius: 10px;
            border: 1px solid var(--border-subtle, #e6edf7);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .img-zoomable:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
        }

        /* Side-by-Side Comparison Boxes */
        .rs-compare-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .rs-answer-box {
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .rs-answer-box.box-correct {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .rs-answer-box.box-incorrect {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }

        .rs-answer-box.box-unanswered {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .rs-answer-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }

        .rs-answer-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .box-correct .rs-answer-circle {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .box-incorrect .rs-answer-circle {
            background-color: #fee2e2;
            color: #ef4444;
        }

        .box-unanswered .rs-answer-circle {
            background-color: #e2e8f0;
            color: #64748b;
        }

        .rs-answer-kicker {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted, #64748b);
        }

        .rs-answer-label {
            font-size: 0.82rem;
            font-weight: 700;
        }

        .rs-answer-value {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text-primary, #0b1f51);
            word-break: break-word;
            line-height: 1.5;
        }

        .rs-choice-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .rs-choice-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(15, 23, 42, 0.08);
            font-size: 0.84rem;
            font-weight: 600;
        }

        /* Explanation Callout (Focus Areas style) */
        .rs-explanation-box {
            margin-top: 16px;
            background: linear-gradient(135deg, #eef4ff 0%, #e5efff 100%);
            border: 1px solid #d5e5ff;
            border-radius: 14px;
            padding: 15px 18px;
        }

        .rs-explanation-head {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1d4ed8;
            font-size: 0.84rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 8px;
        }

        .rs-explanation-body {
            font-size: 0.9rem;
            color: #334155;
            line-height: 1.6;
        }

        .rs-explanation-body p:last-child {
            margin-bottom: 0;
        }

        /* Sticky Question Navigator */
        .rs-nav-card {
            position: sticky;
            top: 88px;
            padding: 18px 20px;
        }

        .rs-nav-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-subtle, #eef2f7);
        }

        .rs-nav-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary, #0b1f51);
            margin: 0;
        }

        .rs-nav-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 16px;
        }

        .rs-nav-dot {
            height: 36px;
            border-radius: 9px;
            font-size: 0.84rem;
            font-weight: 700;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.18s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            outline: none !important;
        }

        .rs-nav-dot.dot-correct {
            background-color: #dcfce7;
            color: #15803d;
            border-color: #bbf7d0;
        }

        .rs-nav-dot.dot-correct:hover {
            background-color: #16a34a;
            color: #ffffff;
        }

        .rs-nav-dot.dot-incorrect {
            background-color: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .rs-nav-dot.dot-incorrect:hover {
            background-color: #ef4444;
            color: #ffffff;
        }

        .rs-nav-dot.dot-unanswered {
            background-color: #f1f5f9;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .rs-nav-dot.dot-unanswered:hover {
            background-color: #64748b;
            color: #ffffff;
        }

        .rs-nav-legend {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid var(--border-subtle, #eef2f7);
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted, #64748b);
        }

        .rs-nav-legend-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rs-nav-legend-left {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .rs-legend-box {
            width: 12px;
            height: 12px;
            border-radius: 4px;
            display: inline-block;
        }

        .rs-top-btn {
            width: 100%;
            margin-top: 14px;
            padding: 8px 12px;
            border-radius: 10px;
            background-color: #f8fafc;
            border: 1px solid var(--border-color, #dfe7f2);
            color: var(--text-primary, #1e293b);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.16s ease;
        }

        .rs-top-btn:hover {
            border-color: #2477F9;
            color: #2477F9;
        }

        /* Animations */
        @keyframes rsPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(36, 119, 249, 0.35);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(36, 119, 249, 0);
            }
            100% {
                box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
            }
        }

        .rs-highlight-pulse {
            animation: rsPulse 1.3s ease-in-out;
        }

        /* Responsive */
        @media (max-width: 1199.98px) {
            .rs-overview-grid {
                grid-template-columns: 1fr;
            }
            .rs-review-layout {
                grid-template-columns: 1fr;
            }
            .rs-nav-card {
                position: static;
            }
        }

        @media (max-width: 767.98px) {
            .rs-kpi-grid {
                grid-template-columns: 1fr;
            }
            .rs-compare-grid {
                grid-template-columns: 1fr;
            }
            .rs-card {
                padding: 18px 16px;
            }
        }

        /* Dark Mode Support */
        body.dark-layout .rs-card,
        body.dark-layout .rs-q-card,
        body.dark-layout .rs-kpi-card,
        body.dark-layout .rs-btn-pill,
        body.dark-layout .rs-marks-pill,
        body.dark-layout .rs-top-btn {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-layout .rs-page-title,
        body.dark-layout .rs-donut-pct,
        body.dark-layout .rs-kpi-val,
        body.dark-layout .rs-breakdown-title,
        body.dark-layout .rs-review-title,
        body.dark-layout .rs-q-title,
        body.dark-layout .rs-answer-value,
        body.dark-layout .rs-nav-title,
        body.dark-layout .rs-topic-chip-top,
        body.dark-layout .rs-btn-pill,
        body.dark-layout .rs-marks-pill,
        body.dark-layout .rs-top-btn {
            color: #f8fafc !important;
        }

        body.dark-layout .rs-page-subtitle,
        body.dark-layout .rs-donut-lbl,
        body.dark-layout .rs-score-subtext,
        body.dark-layout .rs-kpi-sub,
        body.dark-layout .rs-breakdown-legend,
        body.dark-layout .rs-review-subtitle,
        body.dark-layout .rs-answer-kicker,
        body.dark-layout .rs-nav-legend {
            color: #94a3b8 !important;
        }

        body.dark-layout .rs-kpi-label,
        body.dark-layout .rs-q-desc,
        body.dark-layout .rs-explanation-body {
            color: #cbd5e1 !important;
        }

        body.dark-layout .rs-q-header,
        body.dark-layout .rs-topic-chip-card {
            background-color: #0f172a !important;
            border-color: #334155 !important;
        }

        body.dark-layout .rs-donut-track {
            stroke: #334155;
        }

        body.dark-layout .rs-stacked-track,
        body.dark-layout .rs-topic-chip-bar,
        body.dark-layout .rs-meta-chip {
            background-color: #0f172a;
            color: #94a3b8;
        }

        body.dark-layout .rs-filter-pill {
            background-color: #1e293b;
            color: #cbd5e1;
            border-color: #334155;
        }

        body.dark-layout .rs-filter-pill:hover {
            background-color: #334155;
            color: #ffffff;
        }

        body.dark-layout .rs-answer-box.box-correct {
            background-color: rgba(34, 197, 94, 0.12);
            border-color: rgba(34, 197, 94, 0.3);
        }

        body.dark-layout .rs-answer-box.box-incorrect {
            background-color: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.3);
        }

        body.dark-layout .rs-answer-box.box-unanswered {
            background-color: rgba(148, 163, 184, 0.1);
            border-color: #334155;
        }

        body.dark-layout .rs-choice-chip {
            background-color: rgba(15, 23, 42, 0.7);
            border-color: #334155;
            color: #f1f5f9;
        }

        body.dark-layout .rs-explanation-box {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16) 0%, rgba(30, 41, 59, 0.95) 100%);
            border-color: #334155;
        }

        body.dark-layout .rs-explanation-head {
            color: #93c5fd;
        }

        body.dark-layout .verdict-strong,
        body.dark-layout .rs-status-correct,
        body.dark-layout .rs-nav-dot.dot-correct {
            background-color: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.35);
            color: #86efac !important;
        }

        body.dark-layout .verdict-developing {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fde68a;
        }

        body.dark-layout .verdict-focus,
        body.dark-layout .rs-status-incorrect,
        body.dark-layout .rs-nav-dot.dot-incorrect {
            background-color: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.35);
            color: #fca5a5 !important;
        }

        body.dark-layout .rs-status-unanswered,
        body.dark-layout .rs-nav-dot.dot-unanswered {
            background-color: rgba(148, 163, 184, 0.16);
            border-color: #334155;
            color: #cbd5e1 !important;
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

        $pct = $percentage ?? ($maxScore > 0 ? (int) round(($attempt->score / $maxScore) * 100) : 0);
        $circumference = 326.73; // 2 * pi * 52
        $dashLen = round(($pct / 100) * $circumference, 1);

        if ($pct >= 80) {
            $ringColor = '#16a34a';
            $verdictClass = 'verdict-strong';
            $verdictText = 'Excellent · Strong Mastery';
        } elseif ($pct >= 61) {
            $ringColor = '#f59e0b';
            $verdictClass = 'verdict-developing';
            $verdictText = 'Good Effort · Developing';
        } else {
            $ringColor = '#ef4444';
            $verdictClass = 'verdict-focus';
            $verdictText = 'Needs Practice · Focus Area';
        }

        $completedAtDisplay = ($attempt->completed_at ?? $attempt->created_at)?->format('d M Y, h:i A') ?? 'Recently';
        $totalQ = max(1, $totalQuestions);
        $correctPct = round(($correctCount / $totalQ) * 100, 1);
        $incorrectPct = round(($incorrectCount / $totalQ) * 100, 1);
        $unansweredPct = max(0, round(100 - $correctPct - $incorrectPct, 1));
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
            <div class="content-body" id="resultPageTop">

                <!-- Breadcrumb Trail -->
                <nav class="rs-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('student.dashboard') }}">Dashboard</a>
                    <span class="rs-breadcrumb-sep">/</span>
                    <a href="{{ route('student.assessments') }}">Assessments</a>
                    @if($paper->subject_id && $paper->subject)
                        <span class="rs-breadcrumb-sep">/</span>
                        <a href="{{ route('student.assessments.topics', $paper->subject_id) }}">{{ $paper->subject->title }}</a>
                    @endif
                    @if($paper->topic_id && $paper->topic)
                        <span class="rs-breadcrumb-sep">/</span>
                        <a href="{{ route('student.topics.subtopics', $paper->topic_id) }}">{{ $paper->topic->name }}</a>
                    @endif
                    <span class="rs-breadcrumb-sep">/</span>
                    <span>Test Results</span>
                </nav>

                <!-- Page Header -->
                <div class="rs-page-header">
                    <div class="rs-header-left">
                        <div class="rs-subject-avatar">
                            @if($paper->subject && !empty($paper->subject->image))
                                <img src="{{ asset('storage/' . $paper->subject->image) }}"
                                     alt="{{ $paper->subject->title }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                <span class="d-none">
                                    <i class="feather icon-award font-large-1"></i>
                                </span>
                            @elseif($theme['icon'] === 'book-open')
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
                        </div>

                        <div>
                            <div class="rs-title-meta-row">
                                <h1 class="rs-page-title">{{ $paper->title }}</h1>
                                @if($paper->subject)
                                    <span class="rs-subj-tag">{{ $paper->subject->title }}</span>
                                @endif
                            </div>
                            <p class="rs-page-subtitle">
                                Completed on {{ $completedAtDisplay }}
                            </p>
                        </div>
                    </div>

                    <div class="rs-header-actions">
                        <a href="{{ $backUrl }}" class="rs-btn-pill">
                            <i class="feather icon-arrow-left"></i>
                            <span>Back to Tests</span>
                        </a>
                        <a href="{{ route('student.focusareas') }}" class="rs-btn-pill">
                            <i class="feather icon-crosshair text-danger"></i>
                            <span>Focus Areas</span>
                        </a>
                        @if($activeAttemptForPaper)
                            <a href="{{ route('student.attempts.take', $activeAttemptForPaper->id) }}" class="rs-btn-primary">
                                <i class="feather icon-play"></i>
                                <span>Resume Current Attempt</span>
                            </a>
                        @else
                            <form action="{{ route('student.papers.start', $paper->id) }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="rs-btn-primary">
                                    <i class="feather icon-refresh-cw"></i>
                                    <span>Retake Test</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Performance Summary Overview Grid -->
                <div class="rs-overview-grid">
                    <!-- Score Donut Card -->
                    <div class="rs-card rs-score-card">
                        <div class="rs-donut-wrap">
                            <svg class="rs-donut-svg" viewBox="0 0 124 124">
                                <circle cx="62" cy="62" r="52" fill="none" class="rs-donut-track" stroke-width="11" />
                                <circle cx="62" cy="62" r="52" fill="none" stroke="{{ $ringColor }}" stroke-width="11"
                                        stroke-linecap="round"
                                        stroke-dasharray="{{ $dashLen }} {{ $circumference }}"
                                        transform="rotate(-90 62 62)" />
                            </svg>
                            <div class="rs-donut-center">
                                <span class="rs-donut-pct">{{ $pct }}%</span>
                                <span class="rs-donut-lbl">Accuracy</span>
                            </div>
                        </div>

                        <span class="rs-verdict-pill {{ $verdictClass }}">{{ $verdictText }}</span>
                        <p class="rs-score-subtext">
                            Scored <strong>{{ $attempt->score }}</strong> out of <strong>{{ $maxScore }}</strong> marks
                        </p>
                    </div>

                    <!-- Right KPI Grid & Accuracy Bar -->
                    <div class="rs-right-overview">
                        <div class="rs-kpi-grid">
                            <div class="rs-kpi-card">
                                <div class="rs-kpi-icon kpi-blue">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="8" r="7"/>
                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                                    </svg>
                                </div>
                                <div class="rs-kpi-meta">
                                    <span class="rs-kpi-label">Total Marks</span>
                                    <span class="rs-kpi-val">{{ $attempt->score }} / {{ $maxScore }}</span>
                                    <span class="rs-kpi-sub">Final score</span>
                                </div>
                            </div>

                            <div class="rs-kpi-card">
                                <div class="rs-kpi-icon kpi-green">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                </div>
                                <div class="rs-kpi-meta">
                                    <span class="rs-kpi-label">Correct Answers</span>
                                    <span class="rs-kpi-val">{{ $correctCount }}</span>
                                    <span class="rs-kpi-sub">Out of {{ $totalQuestions }} {{ \Illuminate\Support\Str::plural('question', $totalQuestions) }}</span>
                                </div>
                            </div>

                            <div class="rs-kpi-card">
                                <div class="rs-kpi-icon kpi-red">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="15" y1="9" x2="9" y2="15"/>
                                        <line x1="9" y1="9" x2="15" y2="15"/>
                                    </svg>
                                </div>
                                <div class="rs-kpi-meta">
                                    <span class="rs-kpi-label">Incorrect / Skipped</span>
                                    <span class="rs-kpi-val">{{ $incorrectCount + $unansweredCount }}</span>
                                    <span class="rs-kpi-sub">{{ $incorrectCount }} wrong &middot; {{ $unansweredCount }} skipped</span>
                                </div>
                            </div>

                            <div class="rs-kpi-card">
                                <div class="rs-kpi-icon kpi-amber">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                </div>
                                <div class="rs-kpi-meta">
                                    <span class="rs-kpi-label">Time Spent</span>
                                    <span class="rs-kpi-val">{{ $formattedTimeSpent }}</span>
                                    <span class="rs-kpi-sub">Avg {{ $avgSecondsPerQ }}s per question</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stacked Response Breakdown Bar -->
                        <div class="rs-card rs-breakdown-card">
                            <div class="rs-breakdown-header">
                                <h3 class="rs-breakdown-title">Response Breakdown</h3>
                                <div class="rs-breakdown-legend">
                                    <span class="rs-legend-item">
                                        <span class="rs-legend-dot" style="background-color: #16a34a;"></span>
                                        Correct ({{ $correctCount }})
                                    </span>
                                    <span class="rs-legend-item">
                                        <span class="rs-legend-dot" style="background-color: #ef4444;"></span>
                                        Incorrect ({{ $incorrectCount }})
                                    </span>
                                    <span class="rs-legend-item">
                                        <span class="rs-legend-dot" style="background-color: #cbd5e1;"></span>
                                        Unanswered ({{ $unansweredCount }})
                                    </span>
                                </div>
                            </div>
                            <div class="rs-stacked-track" role="img" aria-label="Correct {{ $correctCount }}, Incorrect {{ $incorrectCount }}, Unanswered {{ $unansweredCount }}">
                                @if($correctPct > 0)
                                    <div class="rs-seg-correct" style="width: {{ $correctPct }}%;"></div>
                                @endif
                                @if($incorrectPct > 0)
                                    <div class="rs-seg-incorrect" style="width: {{ $incorrectPct }}%;"></div>
                                @endif
                                @if($unansweredPct > 0)
                                    <div class="rs-seg-unanswered" style="width: {{ $unansweredPct }}%;"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Optional Per-Topic Breakdown Card (useful especially for multi-topic papers) -->
                @if($topicBreakdown->count() > 1)
                    <div class="rs-card mb-2">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px;">
                            <div>
                                <h3 class="rs-breakdown-title mb-25">Topic Breakdown</h3>
                                <p class="text-muted mb-0" style="font-size: 0.84rem;">Accuracy across each topic tested in this paper.</p>
                            </div>
                            <a href="{{ route('student.focusareas') }}" class="font-small-3 font-weight-bold text-primary">
                                View Focus Areas &rarr;
                            </a>
                        </div>

                        <div class="rs-topic-breakdown-grid">
                            @foreach($topicBreakdown as $tb)
                                @php
                                    $tbColor = $tb['accuracy'] >= 80 ? '#16a34a' : ($tb['accuracy'] >= 61 ? '#f59e0b' : '#ef4444');
                                @endphp
                                <a href="{{ $tb['url'] }}" class="rs-topic-chip-card">
                                    <div class="rs-topic-chip-top">
                                        <span class="text-truncate">{{ $tb['name'] }}</span>
                                        <span style="color: {{ $tbColor }};">{{ $tb['accuracy'] }}%</span>
                                    </div>
                                    <div class="rs-topic-chip-bar">
                                        <div style="width: {{ $tb['accuracy'] }}%; height: 100%; background-color: {{ $tbColor }}; border-radius: 999px;"></div>
                                    </div>
                                    <small class="text-muted">{{ $tb['correct'] }} of {{ $tb['total'] }} correct</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Detailed Questions Review -->
                @if($paper->display_result_question_by_question)
                    <div class="rs-card">
                        <div class="rs-review-header">
                            <div>
                                <h2 class="rs-review-title">Question-by-Question Review</h2>
                                <p class="rs-review-subtitle">Inspect each question, your response, key solution, and explanation.</p>
                            </div>

                            <div class="rs-filter-pills" role="tablist" aria-label="Filter reviewed questions">
                                <button type="button" class="rs-filter-pill active" data-filter="all" onclick="filterQuestions('all', this)">
                                    All ({{ $questions->count() }})
                                </button>
                                <button type="button" class="rs-filter-pill" data-filter="correct" onclick="filterQuestions('correct', this)">
                                    Correct ({{ $correctCount }})
                                </button>
                                <button type="button" class="rs-filter-pill" data-filter="incorrect" onclick="filterQuestions('incorrect', this)">
                                    Incorrect ({{ $incorrectCount }})
                                </button>
                                <button type="button" class="rs-filter-pill" data-filter="unanswered" onclick="filterQuestions('unanswered', this)">
                                    Unanswered ({{ $unansweredCount }})
                                </button>
                            </div>
                        </div>

                        <div class="rs-review-layout">
                            <!-- Question Review Cards -->
                            <div class="rs-q-list" id="reviewQuestionsList">
                                @foreach($questions as $index => $question)
                                    @php
                                        $ans = $answers->get($question->id);
                                        $isCorrect = $ans && $ans->is_correct;

                                        $statusType = 'unanswered';
                                        $cardClass = 'is-unanswered';
                                        $statusBadgeClass = 'rs-status-unanswered';
                                        $statusText = 'Unanswered';
                                        $statusIcon = 'feather icon-minus-circle';
                                        $userBoxClass = 'box-unanswered';
                                        $userStatusColor = 'text-secondary';

                                        if ($ans) {
                                            if ($isCorrect) {
                                                $statusType = 'correct';
                                                $cardClass = 'is-correct';
                                                $statusBadgeClass = 'rs-status-correct';
                                                $statusText = 'Correct';
                                                $statusIcon = 'feather icon-check';
                                                $userBoxClass = 'box-correct';
                                                $userStatusColor = 'text-success';
                                            } else {
                                                $statusType = 'incorrect';
                                                $cardClass = 'is-incorrect';
                                                $statusBadgeClass = 'rs-status-incorrect';
                                                $statusText = 'Incorrect';
                                                $statusIcon = 'feather icon-x';
                                                $userBoxClass = 'box-incorrect';
                                                $userStatusColor = 'text-danger';
                                            }
                                        }

                                        $qMaxMarks = $questionMarksMap[$question->id] ?? ($question->marks ?? 1);
                                        $qEarnedMarks = $ans ? ($ans->marks_obtained ?? 0) : 0;
                                        $optionsById = $question->options->keyBy('id');
                                    @endphp

                                    <div class="rs-q-card {{ $cardClass }} review-card-item"
                                         id="question-{{ $question->id }}"
                                         data-status="{{ $statusType }}">

                                        <div class="rs-q-header">
                                            <div class="rs-q-header-left">
                                                <span class="rs-q-num">Question {{ $index + 1 }}</span>
                                                @if($question->topic)
                                                    <span class="rs-meta-chip">{{ $question->topic->name }}</span>
                                                @endif
                                                <span class="rs-meta-chip">{{ $question->type_label }}</span>
                                                @if($ans && $ans->is_flagged)
                                                    <span class="rs-flag-chip">
                                                        <i class="feather icon-flag"></i> Flagged
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="rs-q-header-right">
                                                @if($ans && $ans->time_spent > 0)
                                                    <span class="rs-meta-chip">
                                                        <i class="feather icon-clock"></i> {{ $ans->time_spent }}s
                                                    </span>
                                                @endif
                                                <span class="rs-marks-pill">{{ $qEarnedMarks }} / {{ $qMaxMarks }} {{ \Illuminate\Support\Str::plural('mark', $qMaxMarks) }}</span>
                                                <span class="rs-status-badge {{ $statusBadgeClass }}">
                                                    <i class="{{ $statusIcon }}"></i>
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="rs-q-body">
                                            @if(!empty($question->title) && trim(strip_tags($question->title)) !== trim(strip_tags($question->description)))
                                                <h4 class="rs-q-title">{{ $question->title }}</h4>
                                            @endif

                                            @if(!empty($question->description))
                                                <div class="rs-q-desc">{!! $question->description !!}</div>
                                            @endif

                                            @if($question->image || ($question->images && count($question->images) > 0))
                                                <div class="rs-q-images">
                                                    @if($question->image)
                                                        <img src="{{ asset('storage/' . $question->image) }}"
                                                             class="img-fluid img-zoomable"
                                                             alt="Question Image">
                                                    @endif
                                                    @if($question->images)
                                                        @foreach($question->images as $img)
                                                            <img src="{{ asset('storage/' . $img) }}"
                                                                 class="img-fluid img-zoomable"
                                                                 alt="Question Image">
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Side-by-Side Response Comparison -->
                                            <div class="rs-compare-grid">
                                                <!-- Student Response -->
                                                <div class="rs-answer-box {{ $userBoxClass }}">
                                                    <div class="rs-answer-head">
                                                        <span class="rs-answer-circle">
                                                            <i class="{{ $statusIcon }}"></i>
                                                        </span>
                                                        <div>
                                                            <span class="rs-answer-kicker">Your Response</span>
                                                            <span class="rs-answer-label {{ $userStatusColor }}">
                                                                {{ $ans ? $statusText : 'Not Attempted' }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="rs-answer-value">
                                                        @if(in_array($question->type, ['single_choice_radio', 'single_choice_dropdown', 'picture_choice'], true))
                                                            @php
                                                                $selectedOpt = ($ans && $ans->selected_option_id)
                                                                    ? ($optionsById->get($ans->selected_option_id) ?? $ans->selectedOption)
                                                                    : null;
                                                            @endphp
                                                            @if($selectedOpt)
                                                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                                                    @if(!empty($selectedOpt->image))
                                                                        <img src="{{ asset('storage/' . $selectedOpt->image) }}" class="img-zoomable" style="max-height: 60px;" alt="Option image">
                                                                    @endif
                                                                    <span>{{ $selectedOpt->option_text }}</span>
                                                                </div>
                                                            @else
                                                                <span class="text-muted font-italic font-weight-normal">No Answer Selected</span>
                                                            @endif
                                                        @elseif($question->type === 'multiple_choice')
                                                            @if($ans && is_array($ans->selected_options) && count($ans->selected_options) > 0)
                                                                <div class="rs-choice-chips">
                                                                    @foreach($ans->selected_options as $optId)
                                                                        @php $optObj = $optionsById->get((int) $optId); @endphp
                                                                        @if($optObj)
                                                                            <span class="rs-choice-chip">{{ $optObj->option_text }}</span>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted font-italic font-weight-normal">No Answer Selected</span>
                                                            @endif
                                                        @elseif(in_array($question->type, ['matching_drag_drop', 'matching_text'], true))
                                                            @if($ans && $ans->answer_text)
                                                                @php
                                                                    $decoded = json_decode($ans->answer_text, true);
                                                                    $pairs = $question->metadata['matching_pairs'] ?? [];
                                                                @endphp
                                                                @if(is_array($decoded) && !empty($pairs))
                                                                    <div class="rs-choice-chips">
                                                                        @foreach($pairs as $pairIdx => $pair)
                                                                            <span class="rs-choice-chip">
                                                                                {{ $pair['left'] }} &rarr; {{ $decoded[$pairIdx] ?? 'N/A' }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    {{ $ans->answer_text }}
                                                                @endif
                                                            @else
                                                                <span class="text-muted font-italic font-weight-normal">No Answer Selected</span>
                                                            @endif
                                                        @else
                                                            @if($ans && $ans->answer_text)
                                                                @php $decoded = json_decode($ans->answer_text, true); @endphp
                                                                @if(is_array($decoded))
                                                                    <div class="rs-choice-chips">
                                                                        @foreach($decoded as $part)
                                                                            <span class="rs-choice-chip">{{ $part }}</span>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    {{ $ans->answer_text }}
                                                                @endif
                                                            @else
                                                                <span class="text-muted font-italic font-weight-normal">No Answer Entered</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Correct Solution -->
                                                <div class="rs-answer-box box-correct">
                                                    <div class="rs-answer-head">
                                                        <span class="rs-answer-circle">
                                                            <i class="feather icon-check-square"></i>
                                                        </span>
                                                        <div>
                                                            <span class="rs-answer-kicker">Correct Response</span>
                                                            <span class="rs-answer-label text-success">Key Solution</span>
                                                        </div>
                                                    </div>

                                                    <div class="rs-answer-value text-success">
                                                        @if($question->type === 'fill_in_the_blanks')
                                                            @php
                                                                $correctBlanks = $question->metadata['blank_answers'] ?? [];
                                                            @endphp
                                                            @if(!empty($correctBlanks))
                                                                <div class="rs-choice-chips">
                                                                    @foreach($correctBlanks as $blankAns)
                                                                        <span class="rs-choice-chip text-success">{{ $blankAns }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                @php $correctOption = $question->options->where('is_correct', true)->first(); @endphp
                                                                {{ $correctOption ? $correctOption->option_text : 'N/A' }}
                                                            @endif
                                                        @elseif(in_array($question->type, ['single_choice_radio', 'single_choice_dropdown', 'picture_choice', 'multiple_choice'], true))
                                                            @php
                                                                $correctOptions = $question->options->where('is_correct', true);
                                                            @endphp
                                                            @if($correctOptions->count() > 1)
                                                                <div class="rs-choice-chips">
                                                                    @foreach($correctOptions as $cOpt)
                                                                        <span class="rs-choice-chip text-success">{{ $cOpt->option_text }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @elseif($correctOptions->count() === 1)
                                                                @php $cOpt = $correctOptions->first(); @endphp
                                                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                                                    @if(!empty($cOpt->image))
                                                                        <img src="{{ asset('storage/' . $cOpt->image) }}" class="img-zoomable" style="max-height: 60px;" alt="Correct option image">
                                                                    @endif
                                                                    <span>{{ $cOpt->option_text }}</span>
                                                                </div>
                                                            @else
                                                                N/A
                                                            @endif
                                                        @elseif(in_array($question->type, ['matching_drag_drop', 'matching_text'], true))
                                                            @php $pairs = $question->metadata['matching_pairs'] ?? []; @endphp
                                                            @if(!empty($pairs))
                                                                <div class="rs-choice-chips">
                                                                    @foreach($pairs as $pair)
                                                                        <span class="rs-choice-chip text-success">
                                                                            {{ $pair['left'] }} &rarr; {{ $pair['right'] }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                N/A
                                                            @endif
                                                        @else
                                                            @php $correctOption = $question->options->where('is_correct', true)->first(); @endphp
                                                            {{ $correctOption ? $correctOption->option_text : 'Reviewed by Tutor' }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Explanation Block -->
                                            @if($question->explanation || ($question->explanation_images && count($question->explanation_images) > 0))
                                                <div class="rs-explanation-box">
                                                    <div class="rs-explanation-head">
                                                        <i class="feather icon-help-circle"></i>
                                                        <span>Explanation &amp; Concept Review</span>
                                                    </div>
                                                    @if($question->explanation)
                                                        <div class="rs-explanation-body mb-1">
                                                            {!! $question->explanation !!}
                                                        </div>
                                                    @endif
                                                    @if($question->explanation_images && count($question->explanation_images) > 0)
                                                        <div class="d-flex flex-wrap align-items-center mt-1" style="gap: 10px;">
                                                            @foreach($question->explanation_images as $expImg)
                                                                <img src="{{ asset('storage/' . $expImg) }}"
                                                                     class="img-fluid img-zoomable"
                                                                     style="max-height: 130px;"
                                                                     alt="Explanation Image">
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Empty Filter Placeholder -->
                                <div class="text-center py-4 d-none" id="noFilteredQuestionsBox">
                                    <p class="text-muted mb-1">No questions match this filter.</p>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="filterQuestions('all', document.querySelector('.rs-filter-pill[data-filter=all]'))">
                                        Show All Questions
                                    </button>
                                </div>
                            </div>

                            <!-- Sticky Sidebar Navigator -->
                            <aside class="rs-card rs-nav-card">
                                <div class="rs-nav-head">
                                    <h3 class="rs-nav-title">Navigator</h3>
                                    <span class="rs-meta-chip">{{ $correctCount }}/{{ $questions->count() }}</span>
                                </div>

                                <div class="rs-nav-grid">
                                    @foreach($questions as $index => $question)
                                        @php
                                            $ans = $answers->get($question->id);
                                            $isCorrect = $ans && $ans->is_correct;
                                            $statusType = 'unanswered';
                                            $dotClass = 'dot-unanswered';
                                            if ($ans) {
                                                $statusType = $isCorrect ? 'correct' : 'incorrect';
                                                $dotClass = $isCorrect ? 'dot-correct' : 'dot-incorrect';
                                            }
                                        @endphp
                                        <button type="button"
                                                class="rs-nav-dot nav-dot {{ $dotClass }}"
                                                data-status="{{ $statusType }}"
                                                onclick="scrollToQuestion(event, 'question-{{ $question->id }}')">
                                            {{ $index + 1 }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="rs-nav-legend">
                                    <div class="rs-nav-legend-row">
                                        <span class="rs-nav-legend-left">
                                            <span class="rs-legend-box" style="background-color: #22c55e;"></span>
                                            Correct
                                        </span>
                                        <span>{{ $correctCount }}</span>
                                    </div>
                                    <div class="rs-nav-legend-row">
                                        <span class="rs-nav-legend-left">
                                            <span class="rs-legend-box" style="background-color: #ef4444;"></span>
                                            Incorrect
                                        </span>
                                        <span>{{ $incorrectCount }}</span>
                                    </div>
                                    <div class="rs-nav-legend-row">
                                        <span class="rs-nav-legend-left">
                                            <span class="rs-legend-box" style="background-color: #94a3b8;"></span>
                                            Unanswered
                                        </span>
                                        <span>{{ $unansweredCount }}</span>
                                    </div>
                                </div>

                                <button type="button" class="rs-top-btn" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })">
                                    <i class="feather icon-arrow-up mr-25"></i> Back to Top
                                </button>
                            </aside>
                        </div>
                    </div>
                @else
                    <div class="rs-card text-center py-4">
                        <div class="rs-kpi-icon kpi-blue mx-auto mb-1" style="width: 52px; height: 52px;">
                            <i class="feather icon-lock font-medium-4"></i>
                        </div>
                        <h4 class="font-weight-bold mb-50" style="color: var(--text-primary, #0b1f51);">
                            Detailed Question Review Disabled
                        </h4>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                            Individual question feedback is disabled for this assessment. Your overall score and time summary are shown above.
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lightboxHtml = `
                <div id="premium-image-lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 99999; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.25s ease; cursor: zoom-out;">
                    <span style="position: absolute; top: 20px; right: 25px; color: #f8fafc; font-size: 38px; font-weight: 300; cursor: pointer;" id="close-lightbox-btn">&times;</span>
                    <img id="lightbox-zoomed-img" src="" style="max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 10px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); transform: scale(0.94); transition: transform 0.25s ease;" alt="Zoomed Image">
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', lightboxHtml);

            const lightbox = document.getElementById('premium-image-lightbox');
            const lightboxImg = document.getElementById('lightbox-zoomed-img');
            const closeBtn = document.getElementById('close-lightbox-btn');

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('img-zoomable')) {
                    lightboxImg.src = e.target.src;
                    lightbox.style.display = 'flex';
                    lightbox.offsetHeight;
                    lightbox.style.opacity = '1';
                    lightboxImg.style.transform = 'scale(1)';
                }
            });

            function closeLightbox() {
                lightbox.style.opacity = '0';
                lightboxImg.style.transform = 'scale(0.94)';
                setTimeout(function () {
                    lightbox.style.display = 'none';
                }, 250);
            }

            lightbox.addEventListener('click', function (e) {
                if (e.target !== lightboxImg) closeLightbox();
            });
            closeBtn.addEventListener('click', closeLightbox);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && lightbox.style.display === 'flex') closeLightbox();
            });
        });

        function filterQuestions(status, element) {
            document.querySelectorAll('.rs-filter-pill').forEach(function (pill) {
                pill.classList.remove('active');
            });
            if (element) {
                element.classList.add('active');
            }

            var cards = document.querySelectorAll('.review-card-item');
            var visibleCount = 0;

            cards.forEach(function (card) {
                if (status === 'all' || card.getAttribute('data-status') === status) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            var noBox = document.getElementById('noFilteredQuestionsBox');
            if (noBox) {
                noBox.classList.toggle('d-none', visibleCount > 0 || cards.length === 0);
            }

            document.querySelectorAll('.nav-dot').forEach(function (dot) {
                if (status === 'all' || dot.getAttribute('data-status') === status) {
                    dot.style.opacity = '1';
                    dot.style.pointerEvents = 'auto';
                } else {
                    dot.style.opacity = '0.25';
                    dot.style.pointerEvents = 'none';
                }
            });
        }

        function scrollToQuestion(event, id) {
            event.preventDefault();
            var target = document.getElementById(id);
            if (!target) return;

            var offset = 96;
            var bodyRect = document.body.getBoundingClientRect().top;
            var targetRect = target.getBoundingClientRect().top;
            var offsetPosition = targetRect - bodyRect - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });

            target.classList.add('rs-highlight-pulse');
            setTimeout(function () {
                target.classList.remove('rs-highlight-pulse');
            }, 1300);
        }
    </script>
@endpush
