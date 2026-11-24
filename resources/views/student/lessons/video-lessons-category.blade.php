@extends('layouts.app')

@section('title', 'Lessons - Aspire Learners')

@push('styles')
<style>
    /* -------------------------------------------------------------
       LESSONS PAGE STYLING (Exact match to reference UI)
       ------------------------------------------------------------- */
    .lessons-content-container {
        padding: 0.5rem 0.5rem 2.5rem;
    }

    /* Page Header */
    .lessons-header-box {
        margin-bottom: 1.5rem;
    }

    .lessons-page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #063080;
        letter-spacing: -0.025em;
        line-height: 1.2;
        margin-bottom: 0.35rem;
    }

    .dark-layout .lessons-page-title {
        color: #f8fafc;
    }

    .lessons-page-subtitle {
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    .dark-layout .lessons-page-subtitle {
        color: #94a3b8;
    }

    /* Toolbar / Filters & Controls Row */
    .lessons-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    /* Filter Pills */
    .lessons-filter-pills {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.65rem;
    }

    .lesson-filter-pill {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none !important;
        white-space: nowrap;
    }

    .lesson-filter-pill:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-1px);
    }

    .lesson-filter-pill.active {
        background: #2477F9 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(36, 119, 249, 0.35);
        border-color: #2477F9 !important;
    }

    .dark-layout .lesson-filter-pill {
        background: #181d36;
        color: #94a3b8;
        border-color: #2d3748;
    }

    .dark-layout .lesson-filter-pill:hover {
        background: #252b48;
        color: #f1f5f9;
    }

    .dark-layout .lesson-filter-pill.active {
        background: #2477F9 !important;
        color: #ffffff !important;
    }

    /* Right Controls */
    .lessons-toolbar-controls {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .sort-by-text {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
        margin-right: 0.2rem;
    }

    .dark-layout .sort-by-text {
        color: #94a3b8;
    }

    .sort-dropdown-toggle {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        padding: 0.45rem 1.15rem;
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }

    .dark-layout .sort-dropdown-toggle {
        background: #1e2440;
        border-color: #2d3748;
        color: #f1f5f9;
    }

    .sort-dropdown-toggle:hover {
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .sort-dropdown-toggle::after {
        margin-left: 0.2rem;
        vertical-align: middle;
        color: #64748b;
    }

    .sort-dropdown-menu {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 0.4rem;
        min-width: 150px;
    }

    .dark-layout .sort-dropdown-menu {
        background: #1e2440;
        border-color: #2d3748;
    }

    .sort-dropdown-menu .dropdown-item {
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.45rem 0.85rem;
        color: #475569;
    }

    .dark-layout .sort-dropdown-menu .dropdown-item {
        color: #cbd5e1;
    }

    .sort-dropdown-menu .dropdown-item:hover,
    .sort-dropdown-menu .dropdown-item.active {
        background: #eff6ff;
        color: #2477F9;
    }

    .dark-layout .sort-dropdown-menu .dropdown-item:hover,
    .dark-layout .sort-dropdown-menu .dropdown-item.active {
        background: rgba(36, 119, 249, 0.15);
        color: #2477F9;
    }

    /* View Switchers */
    .view-switchers-group {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .view-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
    }

    .dark-layout .view-btn {
        background: #1e2440;
        border-color: #2d3748;
        color: #94a3b8;
    }

    .view-btn:hover {
        background: #f8fafc;
        color: #1e293b;
    }

    .view-btn.active {
        background: #2477F9 !important;
        border-color: #2477F9 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(36, 119, 249, 0.3);
    }

    /* -------------------------------------------------------------
       LESSON CARDS (GRID VIEW)
       ------------------------------------------------------------- */
    .lessons-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        transition: all 0.3s ease;
    }

    @media (max-width: 1199.98px) {
        .lessons-cards-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
    }

    @media (max-width: 767.98px) {
        .lessons-cards-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    .lesson-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .dark-layout .lesson-card {
        background: #1e2440;
        border-color: #2d3748;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }

    .lesson-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        border-color: rgba(36, 119, 249, 0.3);
    }

    /* Thumbnail Area */
    .lesson-thumb-container {
        position: relative;
        width: 100%;
        height: 195px;
        overflow: hidden;
        background: #f1f5f9;
        display: block;
        text-decoration: none;
    }

    .lesson-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
        display: block;
    }

    .lesson-card:hover .lesson-thumb-img {
        transform: scale(1.04);
    }

    /* Center Circular Play Button */
    .lesson-play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.04);
        transition: background 0.2s ease;
        pointer-events: none;
    }

    .lesson-card:hover .lesson-play-overlay {
        background: rgba(0, 0, 0, 0.12);
    }

    .play-btn-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(15, 23, 42, 0.78);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
        transition: transform 0.25s ease, background 0.25s ease;
    }

    .lesson-card:hover .play-btn-circle {
        transform: scale(1.12);
        background: rgba(15, 23, 42, 0.9);
    }

    .play-icon-tri {
        margin-left: 3px;
    }

    /* Duration Badge */
    .lesson-duration-badge {
        position: absolute;
        bottom: 10px;
        right: 12px;
        background: rgba(0, 0, 0, 0.78);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 11.5px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        letter-spacing: 0.02em;
        pointer-events: none;
    }

    /* Card Details */
    .lesson-card-body {
        padding: 16px 18px 18px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }

    .lesson-meta-top {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    /* Subject Icon Box */
    .subject-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .subject-icon-box.icon-english {
        background: #ef4444;
        color: #ffffff;
    }

    .subject-icon-box.icon-maths {
        background: #2477F9;
        color: #ffffff;
    }

    .subject-icon-box.icon-vr {
        background: #f59e0b;
        color: #ffffff;
    }

    .subject-icon-box.icon-default {
        background: #6366f1;
        color: #ffffff;
    }

    /* Title */
    .lesson-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.35;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dark-layout .lesson-card-title {
        color: #f1f5f9;
    }

    .lesson-card-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .lesson-card-title a:hover {
        color: #2477F9;
    }

    /* Middle Row: Tag + Progress */
    .lesson-middle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 16px;
        min-height: 24px;
    }

    .subject-pill-tag {
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        letter-spacing: 0.01em;
    }

    .subject-pill-tag.tag-english {
        background: #fef2f2;
        color: #ef4444;
    }

    .subject-pill-tag.tag-maths {
        background: #eff6ff;
        color: #2477F9;
    }

    .subject-pill-tag.tag-vr {
        background: #fefce8;
        color: #d97706;
    }

    .subject-pill-tag.tag-default {
        background: #eef2ff;
        color: #6366f1;
    }

    .dark-layout .subject-pill-tag.tag-english {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
    }

    .dark-layout .subject-pill-tag.tag-maths {
        background: rgba(36, 119, 249, 0.15);
        color: #60a5fa;
    }

    .dark-layout .subject-pill-tag.tag-vr {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
    }

    /* Progress Bar in card */
    .lesson-card-progress-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-grow: 1;
        max-width: 140px;
        margin-left: auto;
    }

    .lesson-progress-track {
        height: 6px;
        border-radius: 9999px;
        background: #f1f5f9;
        flex-grow: 1;
        overflow: hidden;
    }

    .dark-layout .lesson-progress-track {
        background: #2d3748;
    }

    .lesson-progress-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.6s ease;
    }

    .lesson-progress-fill.fill-english {
        background: #ef4444;
    }

    .lesson-progress-fill.fill-maths {
        background: #2477F9;
    }

    .lesson-progress-fill.fill-vr {
        background: #f59e0b;
    }

    .lesson-progress-fill.fill-default {
        background: #6366f1;
    }

    .lesson-progress-percent {
        font-size: 11.5px;
        font-weight: 700;
        min-width: 28px;
        text-align: right;
    }

    .lesson-progress-percent.pct-english {
        color: #ef4444;
    }

    .lesson-progress-percent.pct-maths {
        color: #2477F9;
    }

    .lesson-progress-percent.pct-vr {
        color: #d97706;
    }

    .dark-layout .lesson-progress-percent.pct-english {
        color: #f87171;
    }

    .dark-layout .lesson-progress-percent.pct-maths {
        color: #60a5fa;
    }

    .dark-layout .lesson-progress-percent.pct-vr {
        color: #fbbf24;
    }

    /* Card Action Button */
    .lesson-action-btn {
        width: 100%;
        padding: 9px 16px;
        border-radius: 9999px;
        font-size: 13.5px;
        font-weight: 600;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        border: none;
    }

    .lesson-action-btn:hover {
        transform: translateY(-1px);
    }

    /* English Button: Soft red */
    .lesson-action-btn.btn-english {
        background: #fff1f2;
        color: #ef4444;
    }

    .lesson-action-btn.btn-english:hover {
        background: #ffe4e6;
        color: #dc2626;
    }

    .dark-layout .lesson-action-btn.btn-english {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
    }

    .dark-layout .lesson-action-btn.btn-english:hover {
        background: rgba(239, 68, 68, 0.25);
        color: #ffffff;
    }

    /* Maths Button: Soft blue */
    .lesson-action-btn.btn-maths {
        background: #eff6ff;
        color: #2477F9;
    }

    .lesson-action-btn.btn-maths:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .dark-layout .lesson-action-btn.btn-maths {
        background: rgba(36, 119, 249, 0.15);
        color: #60a5fa;
    }

    .dark-layout .lesson-action-btn.btn-maths:hover {
        background: rgba(36, 119, 249, 0.25);
        color: #ffffff;
    }

    /* Verbal Reasoning Button: Soft yellow/amber */
    .lesson-action-btn.btn-vr {
        background: #fefce8;
        color: #d97706;
    }

    .lesson-action-btn.btn-vr:hover {
        background: #fef9c3;
        color: #b45309;
    }

    .dark-layout .lesson-action-btn.btn-vr {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
    }

    .dark-layout .lesson-action-btn.btn-vr:hover {
        background: rgba(245, 158, 11, 0.25);
        color: #ffffff;
    }

    /* Default Button */
    .lesson-action-btn.btn-default {
        background: #eef2ff;
        color: #4f46e5;
    }

    .lesson-action-btn.btn-default:hover {
        background: #e0e7ff;
        color: #4338ca;
    }

    /* -------------------------------------------------------------
       LIST VIEW STYLING
       ------------------------------------------------------------- */
    .lessons-cards-grid.list-view {
        grid-template-columns: 1fr !important;
        gap: 1rem;
    }

    .lessons-cards-grid.list-view .lesson-card {
        flex-direction: row;
        align-items: center;
        padding: 12px;
    }

    .lessons-cards-grid.list-view .lesson-thumb-container {
        width: 220px;
        min-width: 220px;
        height: 130px;
        border-radius: 12px;
    }

    .lessons-cards-grid.list-view .lesson-card-body {
        padding: 0 16px;
        width: 100%;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .lessons-cards-grid.list-view .lesson-info-col {
        flex: 1;
        margin-right: 1.5rem;
    }

    .lessons-cards-grid.list-view .lesson-action-col {
        width: 180px;
        flex-shrink: 0;
    }

    @media (max-width: 767.98px) {
        .lessons-cards-grid.list-view .lesson-card {
            flex-direction: column;
        }

        .lessons-cards-grid.list-view .lesson-thumb-container {
            width: 100%;
            height: 180px;
        }

        .lessons-cards-grid.list-view .lesson-card-body {
            flex-direction: column;
            padding: 14px 0 0;
        }

        .lessons-cards-grid.list-view .lesson-info-col {
            margin-right: 0;
            width: 100%;
        }

        .lessons-cards-grid.list-view .lesson-action-col {
            width: 100%;
            margin-top: 12px;
        }
    }

    /* Empty state */
    .lessons-empty-state {
        grid-column: 1 / -1;
        background: #ffffff;
        border-radius: 18px;
        border: 1.5px dashed #cbd5e1;
        padding: 3.5rem 1.5rem;
        text-align: center;
    }

    .dark-layout .lessons-empty-state {
        background: #1e2440;
        border-color: #2d3748;
    }

    .empty-state-icon-wrap {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2477F9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.25rem;
    }

    .empty-state-heading {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .dark-layout .empty-state-heading {
        color: #f1f5f9;
    }

    .empty-state-msg {
        font-size: 0.95rem;
        color: #64748b;
        max-width: 420px;
        margin: 0 auto 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body lessons-content-container">

            <!-- 1. Page Header -->
            <div class="lessons-header-box">
                <h1 class="lessons-page-title">Lessons</h1>
                <p class="lessons-page-subtitle">Watch your uploaded lesson videos and continue learning at your own pace.</p>
            </div>

            <!-- 2. Toolbar: Filter Pills + Sort & View Controls -->
            <div class="lessons-toolbar">
                <!-- Subject Filter Tabs -->
                <div class="lessons-filter-pills" id="subjectFilterPills">
                    <button type="button" class="lesson-filter-pill {{ empty($selectedSubjectId) ? 'active' : '' }}" data-subject-id="all">
                        All
                    </button>
                    @foreach($subjects as $subj)
                        @php
                            $isSubActive = (!empty($selectedSubjectId) && $selectedSubjectId == $subj->id);
                            $cleanSubName = strtolower($subj->title);
                            if (str_contains($cleanSubName, 'math')) {
                                $displaySubName = 'Maths';
                            } elseif (str_contains($cleanSubName, 'verbal')) {
                                $displaySubName = 'Verbal Reasoning';
                            } elseif (str_contains($cleanSubName, 'english')) {
                                $displaySubName = 'English';
                            } else {
                                $displaySubName = $subj->title;
                            }
                        @endphp
                        <button type="button" class="lesson-filter-pill {{ $isSubActive ? 'active' : '' }}" data-subject-id="{{ $subj->id }}">
                            {{ $displaySubName }}
                        </button>
                    @endforeach
                </div>

                <!-- Right Controls: Sort Dropdown + View Toggle -->
                <div class="lessons-toolbar-controls">
                    <div class="d-flex align-items-center">
                        <span class="sort-by-text">Sort by</span>
                        <div class="dropdown">
                            <button class="sort-dropdown-toggle dropdown-toggle" type="button" id="sortDropdownMenuBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span id="currentSortText">Latest</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right sort-dropdown-menu shadow" aria-labelledby="sortDropdownMenuBtn">
                                <a class="dropdown-item sort-item active" href="javascript:void(0);" data-sort="latest">Latest</a>
                                <a class="dropdown-item sort-item" href="javascript:void(0);" data-sort="oldest">Oldest</a>
                                <a class="dropdown-item sort-item" href="javascript:void(0);" data-sort="title">Title (A-Z)</a>
                                <a class="dropdown-item sort-item" href="javascript:void(0);" data-sort="progress">Progress</a>
                            </div>
                        </div>
                    </div>

                    <!-- View Switcher -->
                    <div class="view-switchers-group">
                        <button type="button" class="view-btn active" id="gridToggleBtn" title="Grid View" aria-label="Grid View">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            </svg>
                        </button>
                        <button type="button" class="view-btn" id="listToggleBtn" title="List View" aria-label="List View">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="4" y1="6" x2="20" y2="6"></line>
                                <line x1="4" y1="12" x2="20" y2="12"></line>
                                <line x1="4" y1="18" x2="20" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. Lessons Cards Container -->
            <div class="lessons-cards-grid" id="lessonsGridContainer">
                @forelse($mediaFiles as $video)
                    @php
                        $progress = $videoProgressMap[$video->id] ?? null;
                        $isCompleted = $progress && $progress->is_completed;
                        $watchPercent = 0;
                        if ($isCompleted) {
                            $watchPercent = 100;
                        } elseif ($progress && $progress->watch_time > 0 && $video->duration) {
                            $parts = explode(':', $video->duration);
                            $durSec = count($parts) === 3
                                ? ((int)$parts[0] * 3600 + (int)$parts[1] * 60 + (int)$parts[2])
                                : (count($parts) === 2 ? ((int)$parts[0] * 60 + (int)$parts[1]) : (int)$video->duration);
                            $watchPercent = $durSec > 0 ? min(99, round(($progress->watch_time / $durSec) * 100)) : 0;
                        }

                        $subTitle = $video->subject ? $video->subject->title : 'General';
                        $subLower = strtolower($subTitle);

                        if (str_contains($subLower, 'math')) {
                            $themeKey = 'maths';
                            $displayCategory = 'Maths';
                            $iconClass = 'icon-maths';
                            $tagClass = 'tag-maths';
                            $fillClass = 'fill-maths';
                            $pctClass = 'pct-maths';
                            $btnClass = 'btn-maths';
                        } elseif (str_contains($subLower, 'verbal')) {
                            $themeKey = 'vr';
                            $displayCategory = 'Verbal Reasoning';
                            $iconClass = 'icon-vr';
                            $tagClass = 'tag-vr';
                            $fillClass = 'fill-vr';
                            $pctClass = 'pct-vr';
                            $btnClass = 'btn-vr';
                        } else {
                            $themeKey = 'english';
                            $displayCategory = 'English';
                            $iconClass = 'icon-english';
                            $tagClass = 'tag-english';
                            $fillClass = 'fill-english';
                            $pctClass = 'pct-english';
                            $btnClass = 'btn-english';
                        }

                        $playUrl = route('student.videolessonslist', ['subject_id' => $video->subject_id, 'video_id' => $video->id]);
                    @endphp

                    <div class="lesson-card-item"
                         data-id="{{ $video->id }}"
                         data-subject-id="{{ $video->subject_id }}"
                         data-title="{{ strtolower($video->title) }}"
                         data-subject="{{ strtolower($displayCategory) }}"
                         data-created="{{ $video->created_at ? $video->created_at->timestamp : 0 }}"
                         data-progress="{{ $watchPercent }}">

                        <div class="lesson-card">
                            <!-- Thumbnail Area -->
                            <a href="{{ $playUrl }}" class="lesson-thumb-container">
                                @if($video->thumbnail_url)
                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="lesson-thumb-img" loading="lazy">
                                @else
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);">
                                        <i class="feather icon-video font-large-2 text-primary"></i>
                                    </div>
                                @endif

                                <!-- Centered Play Button Circle -->
                                <div class="lesson-play-overlay">
                                    <div class="play-btn-circle">
                                        <svg class="play-icon-tri" width="18" height="18" viewBox="0 0 24 24" fill="#ffffff">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Duration Badge -->
                                @if($video->duration)
                                    <span class="lesson-duration-badge">{{ $video->duration }}</span>
                                @endif
                            </a>

                            <!-- Card Body -->
                            <div class="lesson-card-body">
                                <div class="lesson-info-col">
                                    <!-- Meta Top: Icon + Title -->
                                    <div class="lesson-meta-top">
                                        <div class="subject-icon-box {{ $iconClass }}">
                                            @if($themeKey === 'maths')
                                                <!-- Calculator icon -->
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="4" y="2" width="16" height="20" rx="3"></rect>
                                                    <line x1="8" y1="6" x2="16" y2="6"></line>
                                                    <line x1="16" y1="14" x2="16" y2="18"></line>
                                                    <path d="M16 10h.01"></path>
                                                    <path d="M12 10h.01"></path>
                                                    <path d="M8 10h.01"></path>
                                                    <path d="M12 14h.01"></path>
                                                    <path d="M8 14h.01"></path>
                                                    <path d="M12 18h.01"></path>
                                                    <path d="M8 18h.01"></path>
                                                </svg>
                                            @elseif($themeKey === 'vr')
                                                <!-- Brain / logic icon -->
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04z"></path>
                                                    <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04z"></path>
                                                </svg>
                                            @else
                                                <!-- Open Book icon -->
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <h5 class="lesson-card-title">
                                            <a href="{{ $playUrl }}">{{ $video->title }}</a>
                                        </h5>
                                    </div>

                                    <!-- Middle Row: Category Tag + Progress -->
                                    <div class="lesson-middle-row">
                                        <span class="subject-pill-tag {{ $tagClass }}">{{ $displayCategory }}</span>

                                        @if($watchPercent > 0)
                                            <div class="lesson-card-progress-wrap">
                                                <div class="lesson-progress-track">
                                                    <div class="lesson-progress-fill {{ $fillClass }}" style="width: {{ $watchPercent }}%;"></div>
                                                </div>
                                                <span class="lesson-progress-percent {{ $pctClass }}">{{ $watchPercent }}%</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="lesson-action-col">
                                    <a href="{{ $playUrl }}" class="lesson-action-btn {{ $btnClass }}">
                                        <span>{{ ($watchPercent > 0 && $watchPercent < 100) ? 'Continue Watching' : 'Watch Now' }}</span>
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="lessons-empty-state">
                        <div class="empty-state-icon-wrap">
                            <i class="feather icon-video"></i>
                        </div>
                        <h4 class="empty-state-heading">No Lesson Videos Available</h4>
                        <p class="empty-state-msg">There are no lesson videos currently published for your academic group. Please check back later or contact your instructor.</p>
                    </div>
                @endforelse

                <!-- Client-side No Results Box -->
                <div class="lessons-empty-state d-none" id="noResultsState">
                    <div class="empty-state-icon-wrap">
                        <i class="feather icon-search"></i>
                    </div>
                    <h4 class="empty-state-heading">No Matching Lessons Found</h4>
                    <p class="empty-state-msg">We couldn't find any lessons matching your search or selected filter. Try choosing another category or clearing your search.</p>
                    <button type="button" class="btn btn-primary" id="resetFiltersBtn" style="border-radius: 9999px; padding: 8px 24px;">Reset Filters</button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var container = document.getElementById('lessonsGridContainer');
    var filterPills = document.querySelectorAll('#subjectFilterPills .lesson-filter-pill');
    var sortItems = document.querySelectorAll('.sort-dropdown-menu .sort-item');
    var currentSortText = document.getElementById('currentSortText');
    var gridBtn = document.getElementById('gridToggleBtn');
    var listBtn = document.getElementById('listToggleBtn');
    var noResultsState = document.getElementById('noResultsState');
    var resetFiltersBtn = document.getElementById('resetFiltersBtn');

    var currentSubject = 'all';
    @if(!empty($selectedSubjectId))
        currentSubject = '{{ $selectedSubjectId }}';
    @endif
    var currentSort = 'latest';
    var currentSearch = '';

    // Check header search input
    var headerSearch = document.getElementById('studentHeaderSearch');
    if (headerSearch) {
        headerSearch.addEventListener('input', function(e) {
            currentSearch = e.target.value.trim().toLowerCase();
            applyFilters();
        });
    }

    // Filter pill click handler
    filterPills.forEach(function(pill) {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            filterPills.forEach(function(p) { p.classList.remove('active'); });
            pill.classList.add('active');
            currentSubject = pill.getAttribute('data-subject-id');
            applyFilters();
        });
    });

    // Sort item click handler
    sortItems.forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            sortItems.forEach(function(s) { s.classList.remove('active'); });
            item.classList.add('active');
            currentSort = item.getAttribute('data-sort');
            if (currentSortText) {
                currentSortText.textContent = item.textContent.trim();
            }
            applyFilters();
        });
    });

    // View toggle handlers
    if (gridBtn && listBtn) {
        gridBtn.addEventListener('click', function() {
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
            if (container) {
                container.classList.remove('list-view');
            }
            try { localStorage.setItem('student_lessons_view', 'grid'); } catch(e) {}
        });

        listBtn.addEventListener('click', function() {
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
            if (container) {
                container.classList.add('list-view');
            }
            try { localStorage.setItem('student_lessons_view', 'list'); } catch(e) {}
        });

        // Restore view preference
        try {
            var savedView = localStorage.getItem('student_lessons_view');
            if (savedView === 'list') {
                listBtn.click();
            }
        } catch(e) {}
    }

    // Reset filters
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            currentSubject = 'all';
            currentSearch = '';
            if (headerSearch) headerSearch.value = '';
            var allPill = document.querySelector('#subjectFilterPills .lesson-filter-pill[data-subject-id="all"]');
            if (allPill) {
                filterPills.forEach(function(p) { p.classList.remove('active'); });
                allPill.classList.add('active');
            }
            applyFilters();
        });
    }

    // Master filter and sort function
    function applyFilters() {
        if (!container) return;
        var cards = Array.from(container.querySelectorAll('.lesson-card-item'));
        var visibleCount = 0;

        cards.forEach(function(card) {
            var cardSubjectId = card.getAttribute('data-subject-id');
            var cardTitle = card.getAttribute('data-title') || '';
            var cardSubject = card.getAttribute('data-subject') || '';

            // Subject match
            var matchesSubject = (currentSubject === 'all' || cardSubjectId === currentSubject);

            // Search match
            var matchesSearch = true;
            if (currentSearch.length > 0) {
                matchesSearch = cardTitle.includes(currentSearch) || cardSubject.includes(currentSearch);
            }

            if (matchesSubject && matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sorting
        cards.sort(function(a, b) {
            if (currentSort === 'latest') {
                var cA = parseInt(a.getAttribute('data-created') || 0, 10);
                var cB = parseInt(b.getAttribute('data-created') || 0, 10);
                return cB - cA;
            } else if (currentSort === 'oldest') {
                var cA = parseInt(a.getAttribute('data-created') || 0, 10);
                var cB = parseInt(b.getAttribute('data-created') || 0, 10);
                return cA - cB;
            } else if (currentSort === 'title') {
                var tA = a.getAttribute('data-title') || '';
                var tB = b.getAttribute('data-title') || '';
                return tA.localeCompare(tB);
            } else if (currentSort === 'progress') {
                var pA = parseInt(a.getAttribute('data-progress') || 0, 10);
                var pB = parseInt(b.getAttribute('data-progress') || 0, 10);
                return pB - pA;
            }
            return 0;
        });

        // Re-append sorted cards
        cards.forEach(function(card) {
            container.appendChild(card);
        });

        // Show/hide empty state
        if (noResultsState) {
            if (visibleCount === 0 && cards.length > 0) {
                noResultsState.classList.remove('d-none');
                container.appendChild(noResultsState);
            } else {
                noResultsState.classList.add('d-none');
            }
        }
    }

    // Initial run
    applyFilters();
});
</script>
@endpush
