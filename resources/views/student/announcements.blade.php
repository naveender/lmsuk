@extends('layouts.app')

@section('title', 'Announcements - Aspire Learners')

@push('styles')
    <style>
        /* ==========================================================================
           STUDENT ANNOUNCEMENTS - MODERN UI/UX (LIGHT & DARK MODE COMPATIBLE)
           ========================================================================== */
        .ann-page-header {
            margin-bottom: 1.5rem;
        }

        .ann-page-title {
            color: var(--text-primary, #0b1f51);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .ann-page-subtitle {
            color: var(--text-muted, #64748b);
            font-size: 0.96rem;
            font-weight: 500;
            margin: 0;
        }

        /* Top 4 KPI Summary Cards */
        .ann-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 1.35rem;
        }

        .ann-kpi-card {
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
        }

        .ann-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            border-color: #cbd5e1;
        }

        .ann-kpi-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-icon-blue {
            background-color: #dcebff;
            color: #1677ff;
        }

        .kpi-icon-green {
            background-color: #d6f5e3;
            color: #16a34a;
        }

        .kpi-icon-amber {
            background-color: #fef0d3;
            color: #d97706;
        }

        .kpi-icon-purple {
            background-color: #ebe4ff;
            color: #7c3aed;
        }

        .ann-kpi-meta {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .ann-kpi-count {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--text-primary, #0b1f51);
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
        }

        .ann-kpi-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted, #64748b);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Filter Pills & Search Toolbar */
        .ann-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .ann-filter-pills {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .ann-filter-pill {
            border: none;
            background-color: #e8eef7;
            color: #1e293b;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.18s ease;
            outline: none !important;
            line-height: 1.2;
        }

        .ann-filter-pill:hover {
            background-color: #dce5f3;
            color: #0b1f51;
        }

        .ann-filter-pill.active {
            background: linear-gradient(135deg, #1677ff 0%, #0b5ed7 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(22, 119, 255, 0.25);
        }

        .ann-search-box {
            position: relative;
            width: 100%;
            max-width: 280px;
            flex-shrink: 0;
        }

        .ann-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            display: inline-flex;
        }

        .ann-search-input {
            width: 100%;
            height: 40px;
            padding: 8px 34px 8px 38px;
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dfe7f2);
            border-radius: 10px;
            font-size: 0.88rem;
            color: var(--text-primary, #0b1f51);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .ann-search-input::placeholder {
            color: #94a3b8;
        }

        .ann-search-input:focus {
            border-color: #1677ff;
            box-shadow: 0 0 0 3px rgba(22, 119, 255, 0.12);
        }

        .ann-search-clear {
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

        .ann-search-clear:hover {
            color: #ef4444;
        }

        /* Announcement Row Cards */
        .ann-list-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .ann-row-card {
            background-color: var(--card-bg, #ffffff);
            border: 1px solid var(--border-subtle, #e6edf7);
            border-radius: 14px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
            outline: none;
        }

        .ann-row-card:hover,
        .ann-row-card:focus-visible {
            transform: translateY(-1px);
            border-color: #c7d7ee;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        }

        .ann-row-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
            flex: 1;
        }

        .ann-row-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .row-icon-important {
            background-color: #fee2e2;
            color: #ef4444;
        }

        .row-icon-upcoming {
            background-color: #dcebff;
            color: #1677ff;
        }

        .row-icon-academic {
            background-color: #d6f5e3;
            color: #16a34a;
        }

        .row-icon-academic-info {
            background-color: #dcebff;
            color: #1677ff;
        }

        .row-icon-general {
            background-color: #ebe4ff;
            color: #7c3aed;
        }

        .row-icon-centre_update {
            background-color: #fef0d3;
            color: #d97706;
        }

        .ann-row-body {
            min-width: 0;
            flex: 1;
        }

        .ann-row-title-line {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 4px;
        }

        .ann-row-title {
            color: var(--text-primary, #0b1f51);
            font-size: 1.02rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.3;
        }

        .ann-cat-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 8px;
            line-height: 1.3;
            display: inline-flex;
            align-items: center;
        }

        .cat-badge-important {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .cat-badge-upcoming {
            background-color: #fef3c7;
            color: #b45309;
        }

        .cat-badge-academic {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .cat-badge-general {
            background-color: #ede9fe;
            color: #6d28d9;
        }

        .cat-badge-centre_update {
            background-color: #dcfce7;
            color: #15803d;
        }

        .ann-unread-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #1677ff;
            background-color: #e8f1ff;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .ann-unread-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #1677ff;
        }

        .ann-attach-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.74rem;
            font-weight: 600;
            color: #64748b;
        }

        .ann-row-snippet {
            color: var(--text-body, #475569);
            font-size: 0.875rem;
            line-height: 1.48;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .ann-row-right {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .ann-row-date {
            color: var(--text-muted, #64748b);
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .ann-row-chevron {
            color: var(--text-primary, #1e293b);
            display: inline-flex;
            transition: transform 0.18s ease;
        }

        .ann-row-card:hover .ann-row-chevron {
            transform: translateX(3px);
            color: #1677ff;
        }

        /* Empty State */
        .ann-empty-card {
            background-color: var(--card-bg, #ffffff);
            border: 1.5px dashed var(--border-color, #cbd5e1);
            border-radius: 16px;
            padding: 3rem 1.5rem;
            text-align: center;
        }

        .ann-empty-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #f1f5fb;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        /* Detail Modal */
        .announcement-modal-content {
            border-radius: 18px;
            overflow: hidden;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        }

        .announcement-modal-header {
            background: linear-gradient(135deg, #0b1f51 0%, #1d4ed8 100%);
            color: #ffffff;
            padding: 1.2rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .announcement-modal-title {
            color: #ffffff !important;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-meta-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid #eef2f7;
        }

        .modal-detail-title {
            color: #0b1f51;
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            line-height: 1.35;
        }

        .modal-desc-callout {
            background-color: #f1f5fb;
            border-left: 3px solid #1677ff;
            border-radius: 0 10px 10px 0;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .modal-full-content {
            font-size: 0.96rem;
            line-height: 1.7;
            color: #334155;
            white-space: pre-wrap;
        }

        .modal-media-wrapper {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.25rem;
            text-align: center;
        }

        .modal-img-display {
            max-height: 360px;
            max-width: 100%;
            border-radius: 10px;
            object-fit: contain;
        }

        .modal-file-card {
            background-color: #ffffff;
            border-color: #e2e8f0 !important;
        }

        .modal-file-name {
            color: #0b1f51;
        }

        .dark-layout .modal-file-card {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        .dark-layout .modal-file-name {
            color: #f8fafc !important;
        }

        /* Responsive */
        @media (max-width: 1199.98px) {
            .ann-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .ann-search-box {
                max-width: 100%;
            }
            .ann-row-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 14px 16px;
                gap: 10px;
            }
            .ann-row-right {
                width: 100%;
                justify-content: space-between;
                padding-left: 62px;
            }
        }

        @media (max-width: 575.98px) {
            .ann-kpi-grid {
                grid-template-columns: 1fr;
            }
            .ann-row-right {
                padding-left: 0;
            }
        }

        /* Dark Mode Support */
        .dark-layout .ann-kpi-card,
        .dark-layout .ann-row-card,
        .dark-layout .ann-empty-card,
        .dark-layout .announcement-modal-content {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        .dark-layout .ann-page-title,
        .dark-layout .ann-kpi-count,
        .dark-layout .ann-row-title,
        .dark-layout .ann-row-chevron,
        .dark-layout .modal-detail-title {
            color: #f8fafc !important;
        }

        .dark-layout .ann-page-subtitle,
        .dark-layout .ann-kpi-label,
        .dark-layout .ann-row-date,
        .dark-layout .ann-attach-tag {
            color: #94a3b8 !important;
        }

        .dark-layout .ann-row-snippet,
        .dark-layout .modal-full-content {
            color: #cbd5e1 !important;
        }

        .dark-layout .ann-filter-pill {
            background-color: #1e293b;
            color: #cbd5e1;
            border: 1px solid #334155;
        }

        .dark-layout .ann-filter-pill:hover {
            background-color: #334155;
            color: #ffffff;
        }

        .dark-layout .ann-filter-pill.active {
            border-color: transparent;
        }

        .dark-layout .ann-search-input {
            background-color: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }

        .dark-layout .modal-meta-strip {
            border-bottom-color: #334155;
        }

        .dark-layout .modal-desc-callout,
        .dark-layout .modal-media-wrapper {
            background-color: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }

        .dark-layout .announcement-modal-content .modal-footer {
            background-color: #0f172a !important;
            border-top-color: #334155 !important;
        }
    </style>
@endpush

@section('content')
    @php
        $totalCount = $totalAnnouncementsCount ?? $announcements->count();
        $importantTotal = $importantCount ?? $announcements->where('priority', 'high')->count();
        $upcomingTotal = $upcomingEventsCount ?? $announcements->where('category_key', 'upcoming')->count();
        $generalTotal = $generalUpdatesCount ?? max(0, $totalCount - $importantTotal - $upcomingTotal);
    @endphp

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <!-- Page Header -->
                <div class="ann-page-header">
                    <h1 class="ann-page-title">Announcements</h1>
                    <p class="ann-page-subtitle">Stay updated with the latest news, important dates and information from Aspire Learners.</p>
                </div>

                <!-- 4 KPI Summary Cards -->
                <div class="ann-kpi-grid">
                    <button type="button" class="ann-kpi-card" onclick="triggerCategoryFilter('all')">
                        <div class="ann-kpi-icon kpi-icon-blue">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 10v4a1 1 0 0 0 1 1h2.2l3.4 4.25A1 1 0 0 0 11.4 19V5a1 1 0 0 0-1.8-.6L6.2 8.6H4a1 1 0 0 0-1 1zm14.5 2a4.5 4.5 0 0 0-2.5-4.03v8.06A4.5 4.5 0 0 0 17.5 12zm2-8.77v2.06A7 7 0 0 1 21 12a7 7 0 0 1-1.5 4.35v2.06A9 9 0 0 0 23 12a9 9 0 0 0-3.5-8.77z"/>
                            </svg>
                        </div>
                        <div class="ann-kpi-meta">
                            <span class="ann-kpi-count" id="kpi-total-count">{{ $totalCount }}</span>
                            <span class="ann-kpi-label">Total Announcements</span>
                        </div>
                    </button>

                    <button type="button" class="ann-kpi-card" onclick="triggerCategoryFilter('important')">
                        <div class="ann-kpi-icon kpi-icon-green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 3l5 5-2 2-1.5-.5-3.5 3.5.5 3.5-2 2-3.5-3.5-4.5 4.5L3 21l1.5-1.5L9 15l-3.5-3.5 2-2 3.5.5L14.5 6.5 14 5l2-2z"/>
                            </svg>
                        </div>
                        <div class="ann-kpi-meta">
                            <span class="ann-kpi-count" id="kpi-important-count">{{ $importantTotal }}</span>
                            <span class="ann-kpi-label">Important</span>
                        </div>
                    </button>

                    <button type="button" class="ann-kpi-card" onclick="triggerCategoryFilter('upcoming')">
                        <div class="ann-kpi-icon kpi-icon-amber">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                                <rect x="7" y="14" width="3" height="3" fill="currentColor" stroke="none"/>
                            </svg>
                        </div>
                        <div class="ann-kpi-meta">
                            <span class="ann-kpi-count" id="kpi-upcoming-count">{{ $upcomingTotal }}</span>
                            <span class="ann-kpi-label">Upcoming Events</span>
                        </div>
                    </button>

                    <button type="button" class="ann-kpi-card" onclick="triggerCategoryFilter('general')">
                        <div class="ann-kpi-icon kpi-icon-purple">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="8" y1="13" x2="16" y2="13"/>
                                <line x1="8" y1="17" x2="16" y2="17"/>
                            </svg>
                        </div>
                        <div class="ann-kpi-meta">
                            <span class="ann-kpi-count" id="kpi-general-count">{{ $generalTotal }}</span>
                            <span class="ann-kpi-label">General Updates</span>
                        </div>
                    </button>
                </div>

                <!-- Filter Pills & Search Bar -->
                <div class="ann-toolbar">
                    <div class="ann-filter-pills" role="tablist" aria-label="Filter announcements">
                        <button type="button" class="ann-filter-pill active" data-filter="all">All</button>
                        <button type="button" class="ann-filter-pill" data-filter="important">Important</button>
                        <button type="button" class="ann-filter-pill" data-filter="upcoming">Upcoming Events</button>
                        <button type="button" class="ann-filter-pill" data-filter="general">General</button>
                        <button type="button" class="ann-filter-pill" data-filter="academic">Academic</button>
                        <button type="button" class="ann-filter-pill" data-filter="centre_update">Centre Updates</button>
                    </div>

                    <div class="ann-search-box">
                        <span class="ann-search-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </span>
                        <input type="text"
                               id="announcement-search"
                               class="ann-search-input"
                               placeholder="Search announcements..."
                               autocomplete="off">
                        <button type="button" id="search-clear" class="ann-search-clear d-none" title="Clear search" aria-label="Clear search">
                            <i class="feather icon-x"></i>
                        </button>
                    </div>
                </div>

                <!-- Announcements List -->
                <div class="ann-list-stack" id="announcements-container">
                    @forelse($announcements as $announcement)
                        @php
                            $isRead = in_array($announcement->id, $viewedIds);
                            $categoryKey = $announcement->category_key ?? ($announcement->priority === 'high' ? 'important' : 'general');
                            $categoryLabel = $announcement->category_label ?? ($announcement->priority === 'high' ? 'Important' : 'General');
                            $dateObj = $announcement->show_from ?: $announcement->created_at;
                            $snippetSource = trim(strip_tags((string) ($announcement->description ?: $announcement->content)));
                            $isResourceInfo = $categoryKey === 'academic' && str_contains(strtolower($announcement->title ?? ''), 'resource');
                            $rowIconClass = $isResourceInfo ? 'row-icon-academic-info' : ('row-icon-' . $categoryKey);
                        @endphp

                        <div class="ann-row-card view-announcement-btn"
                             id="announcement-{{ $announcement->id }}"
                             tabindex="0"
                             role="button"
                             data-id="{{ $announcement->id }}"
                             data-category="{{ $categoryKey }}"
                             data-category-label="{{ $categoryLabel }}"
                             data-priority="{{ $announcement->priority ?? 'low' }}"
                             data-read="{{ $isRead ? 'true' : 'false' }}"
                             data-title="{{ $announcement->title }}"
                             data-content="{{ $announcement->content }}"
                             data-desc="{{ $announcement->description }}"
                             data-media="{{ !empty($announcement->media) ? asset('storage/' . $announcement->media) : '' }}"
                             data-media-name="{{ basename($announcement->media ?? '') }}"
                             data-date="{{ $dateObj ? $dateObj->format('d M Y · h:i A') : 'Recent' }}"
                             data-view-url="{{ route('student.announcements.view', $announcement->id) }}">

                            <div class="ann-row-left">
                                <div class="ann-row-icon {{ $rowIconClass }}">
                                    @if($categoryKey === 'important')
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M16 3l5 5-2 2-1.5-.5-3.5 3.5.5 3.5-2 2-3.5-3.5-4.5 4.5L3 21l1.5-1.5L9 15l-3.5-3.5 2-2 3.5.5L14.5 6.5 14 5l2-2z"/>
                                        </svg>
                                    @elseif($categoryKey === 'upcoming')
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                            <rect x="7" y="14" width="3" height="3" fill="currentColor" stroke="none"/>
                                        </svg>
                                    @elseif($categoryKey === 'academic' && $isResourceInfo)
                                        <svg width="21" height="21" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 15h-2v-6h2zm0-8h-2V7h2z"/>
                                        </svg>
                                    @elseif($categoryKey === 'academic')
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="8" y1="13" x2="16" y2="13"/>
                                            <line x1="8" y1="17" x2="13" y2="17"/>
                                        </svg>
                                    @elseif($categoryKey === 'centre_update')
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 4h-2V3a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v1H5a1 1 0 0 0-1 1v3a4 4 0 0 0 3.3 3.93A5 5 0 0 0 11 14.9V18H8v2h8v-2h-3v-3.1a5 5 0 0 0 3.7-2.97A4 4 0 0 0 20 8V5a1 1 0 0 0-1-1zM6 8V6h1v3.8A2 2 0 0 1 6 8zm12 0a2 2 0 0 1-1 1.72V6h1v2z"/>
                                        </svg>
                                    @else
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                        </svg>
                                    @endif
                                </div>

                                <div class="ann-row-body">
                                    <div class="ann-row-title-line">
                                        <h3 class="ann-row-title">{{ $announcement->title }}</h3>
                                        <span class="ann-cat-badge cat-badge-{{ $categoryKey }}">{{ $categoryLabel }}</span>

                                        @if(!$isRead)
                                            <span class="ann-unread-pill" id="unread-pill-{{ $announcement->id }}">
                                                <span class="ann-unread-dot"></span> New
                                            </span>
                                        @endif

                                        @if(!empty($announcement->media))
                                            <span class="ann-attach-tag">
                                                <i class="feather icon-paperclip"></i> Attachment
                                            </span>
                                        @endif
                                    </div>

                                    @if($snippetSource !== '')
                                        <p class="ann-row-snippet">{{ $snippetSource }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="ann-row-right">
                                <span class="ann-row-date">{{ $dateObj ? $dateObj->format('d M Y') : 'Recent' }}</span>
                                <span class="ann-row-chevron" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="ann-empty-card">
                            <div class="ann-empty-icon">
                                <i class="feather icon-bell-off" style="font-size: 1.6rem;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">No announcements published</h4>
                            <p class="text-muted mb-0">There are currently no active announcements or notices for your cohort.</p>
                        </div>
                    @endforelse

                    <!-- Empty Filter / Search State -->
                    <div class="ann-empty-card d-none" id="empty-state-placeholder">
                        <div class="ann-empty-icon">
                            <i class="feather icon-search" style="font-size: 1.6rem;"></i>
                        </div>
                        <h4 class="font-weight-bold mb-1" style="color: var(--text-primary, #0b1f51);">No matching announcements</h4>
                        <p class="text-muted mb-3">We couldn't find any announcements matching your selected category or search query.</p>
                        <button type="button" class="btn btn-primary px-3" id="reset-filters-btn" style="border-radius: 10px;">
                            <i class="feather icon-refresh-cw mr-50"></i> Reset Filters
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Notice Details Modal -->
    <div class="modal fade" id="announcementDetailModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content announcement-modal-content">
                <div class="announcement-modal-header">
                    <h5 class="announcement-modal-title" id="modalLabel">
                        <i class="feather icon-bell"></i>
                        <span>Announcement Details</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-3 p-md-4 text-left">
                    <div class="modal-meta-strip">
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <span id="detail-category-badge" class="ann-cat-badge cat-badge-general">General</span>
                            <span class="text-muted" id="detail-date-text" style="font-size: 0.85rem;">
                                <i class="feather icon-calendar mr-25"></i> Recent
                            </span>
                        </div>
                        <span class="text-success font-weight-bold" style="font-size: 0.82rem;">
                            <i class="feather icon-check-circle mr-25"></i> Viewed
                        </span>
                    </div>

                    <h2 id="detail-title" class="modal-detail-title">Title</h2>

                    <div id="detail-desc-container" class="modal-desc-callout" style="display: none;">
                        <span id="detail-desc"></span>
                    </div>

                    <div id="detail-content" class="modal-full-content mb-2">Content</div>

                    <div id="detail-media-container" class="modal-media-wrapper" style="display: none;">
                        <div id="detail-image-box" style="display: none;">
                            <a href="" id="detail-img-link" target="_blank" title="Click to view full image">
                                <img src="" id="detail-img" class="modal-img-display" alt="Announcement attachment">
                            </a>
                            <div class="mt-2 text-muted" style="font-size: 0.8rem;">
                                <i class="feather icon-maximize-2 mr-25"></i> Click image to open full size
                            </div>
                        </div>

                        <div id="detail-file-box" style="display: none;">
                            <div class="modal-file-card d-flex align-items-center justify-content-between flex-wrap p-2 border rounded" style="gap: 12px;">
                                <div class="d-flex align-items-center">
                                    <i class="feather icon-file-text font-large-1 text-primary mr-2"></i>
                                    <div class="text-left">
                                        <div id="detail-file-name" class="modal-file-name font-weight-bold text-truncate" style="max-width: 320px;">document.pdf</div>
                                        <small class="text-muted">Attachment</small>
                                    </div>
                                </div>
                                <a href="" id="detail-file-download" class="btn btn-primary btn-sm font-weight-bold" target="_blank" download>
                                    <i class="feather icon-download mr-50"></i> Download File
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e6edf7;">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function triggerCategoryFilter(filterKey) {
            var pills = document.querySelectorAll('.ann-filter-pill');
            pills.forEach(function (pill) {
                pill.classList.toggle('active', pill.getAttribute('data-filter') === filterKey);
            });
            if (typeof window.applyAnnouncementFilters === 'function') {
                window.applyAnnouncementFilters(filterKey);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var searchInput = document.getElementById('announcement-search');
            var clearIcon = document.getElementById('search-clear');
            var filterPills = document.querySelectorAll('.ann-filter-pill');
            var cards = document.querySelectorAll('.ann-row-card');
            var emptyState = document.getElementById('empty-state-placeholder');
            var resetBtn = document.getElementById('reset-filters-btn');

            var currentFilter = 'all';
            var currentSearch = '';

            function applyFilters(overrideFilter) {
                if (typeof overrideFilter === 'string') {
                    currentFilter = overrideFilter;
                }
                var query = currentSearch.toLowerCase().trim();
                var visibleCount = 0;

                if (clearIcon) {
                    clearIcon.classList.toggle('d-none', query.length === 0);
                }

                cards.forEach(function (card) {
                    var title = (card.getAttribute('data-title') || '').toLowerCase();
                    var desc = (card.getAttribute('data-desc') || '').toLowerCase();
                    var content = (card.getAttribute('data-content') || '').toLowerCase();
                    var category = card.getAttribute('data-category') || 'general';

                    var matchesFilter = (currentFilter === 'all') || (category === currentFilter);
                    var matchesQuery = !query || title.indexOf(query) !== -1 || desc.indexOf(query) !== -1 || content.indexOf(query) !== -1;

                    if (matchesFilter && matchesQuery) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (emptyState) {
                    emptyState.classList.toggle('d-none', !(visibleCount === 0 && cards.length > 0));
                }
            }

            window.applyAnnouncementFilters = applyFilters;

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    currentSearch = this.value;
                    applyFilters();
                });
            }

            if (clearIcon) {
                clearIcon.addEventListener('click', function () {
                    if (searchInput) searchInput.value = '';
                    currentSearch = '';
                    applyFilters();
                    if (searchInput) searchInput.focus();
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
                    currentSearch = '';
                    currentFilter = 'all';
                    filterPills.forEach(function (p) {
                        p.classList.toggle('active', p.getAttribute('data-filter') === 'all');
                    });
                    applyFilters();
                });
            }

            // Keyboard support for row cards
            cards.forEach(function (card) {
                card.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });

            // Open detail modal & mark as viewed
            $(document).on('click', '.view-announcement-btn', function () {
                var btn = $(this);
                var id = btn.data('id');
                var title = btn.data('title') || '';
                var content = btn.data('content') || '';
                var desc = btn.data('desc') || '';
                var categoryKey = btn.data('category') || 'general';
                var categoryLabel = btn.data('category-label') || 'General';
                var media = btn.data('media') || '';
                var mediaName = btn.data('media-name') || '';
                var date = btn.data('date') || '';
                var viewUrl = btn.data('view-url') || '';

                $('#detail-title').text(title);
                $('#detail-date-text').html('<i class="feather icon-calendar mr-25"></i> ' + date);

                var catBadge = $('#detail-category-badge');
                catBadge.removeClass('cat-badge-important cat-badge-upcoming cat-badge-general cat-badge-academic cat-badge-centre_update');
                catBadge.addClass('cat-badge-' + categoryKey).text(categoryLabel);

                var descContainer = $('#detail-desc-container');
                if (desc && String(desc).trim().length > 0) {
                    $('#detail-desc').text(desc);
                    descContainer.show();
                } else {
                    descContainer.hide();
                }

                var contentDiv = $('#detail-content');
                if (content && String(content).trim().length > 0) {
                    contentDiv.text(content).show();
                } else {
                    contentDiv.hide();
                }

                var mediaContainer = $('#detail-media-container');
                var imgBox = $('#detail-image-box');
                var fileBox = $('#detail-file-box');

                mediaContainer.hide();
                imgBox.hide();
                fileBox.hide();

                if (media && String(media).length > 0) {
                    mediaContainer.show();
                    if (String(media).match(/\.(jpeg|jpg|gif|png|webp|svg)/i)) {
                        $('#detail-img').attr('src', media);
                        $('#detail-img-link').attr('href', media);
                        imgBox.show();
                    } else {
                        $('#detail-file-name').text(mediaName);
                        $('#detail-file-download').attr('href', media);
                        fileBox.show();
                    }
                }

                $('#announcementDetailModal').modal('show');

                var targetCard = document.getElementById('announcement-' + id);
                if (targetCard && targetCard.getAttribute('data-read') !== 'true') {
                    axios.post(viewUrl)
                        .then(function (response) {
                            if (response.data && response.data.success) {
                                targetCard.setAttribute('data-read', 'true');
                                var unreadPill = document.getElementById('unread-pill-' + id);
                                if (unreadPill) unreadPill.remove();
                            }
                        })
                        .catch(function (err) {
                            console.warn('Could not record announcement view:', err);
                        });
                }
            });
        });
    </script>
@endpush
