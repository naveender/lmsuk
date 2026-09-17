@extends('layouts.app')

@section('title', 'Academy Announcements - Aspire Learners')

@push('styles')
    <style>
        /* Announcements Hero Banner */
        .announcements-hero-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 45%, #312e81 100%);
            border-radius: 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .announcements-hero-card::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -15%;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.28) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
        }

        .announcements-hero-card::after {
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

        .announcements-badge {
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

        .announcements-hero-title {
            font-size: 2.15rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
            line-height: 1.25;
            margin-bottom: 0.4rem;
        }

        .announcements-hero-subtitle {
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

        /* Urgent Alert Banner */
        .urgent-alert-banner {
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            border: 1.5px solid #fecdd3;
            border-left: 6px solid #f43f5e;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 6px 20px rgba(244, 63, 94, 0.1);
        }

        .dark-layout .urgent-alert-banner {
            background: linear-gradient(135deg, #38121e 0%, #291017 100%);
            border-color: #881337;
            border-left-color: #f43f5e;
        }

        .urgent-pulse-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffe4e6;
            color: #e11d48;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.7);
            animation: urgentIconPulse 2s infinite;
        }

        .dark-layout .urgent-pulse-icon {
            background: #881337;
            color: #fda4af;
        }

        @keyframes urgentIconPulse {
            0% { box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.5); }
            70% { box-shadow: 0 0 0 10px rgba(244, 63, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(244, 63, 94, 0); }
        }

        .urgent-title {
            color: #9f1239;
            font-weight: 800;
            font-size: 1.05rem;
        }

        .dark-layout .urgent-title {
            color: #fecdd3;
        }

        .urgent-subtitle {
            color: #881337;
            font-size: 0.88rem;
        }

        .dark-layout .urgent-subtitle {
            color: #fda4af;
        }

        .btn-urgent-action {
            background: #e11d48;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 0.5rem 1.1rem;
            border-radius: 9999px;
            border: none;
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.35);
            transition: all 0.25s ease;
        }

        .btn-urgent-action:hover {
            background: #be123c;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(225, 29, 72, 0.45);
        }

        /* Controls & Filter Card */
        .announcements-controls-card {
            background: #ffffff;
            border-radius: 1.15rem;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
            border: 1px solid #edf2f7;
        }

        .dark-layout .announcements-controls-card {
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

        .announcement-search-control {
            padding-left: 2.8rem;
            padding-right: 2.5rem;
            height: 44px;
            border-radius: 0.85rem;
            border: 1.5px solid #e2e8f0;
            font-size: 0.92rem;
            transition: all 0.25s ease;
            background-color: #f8fafc;
        }

        .announcement-search-control:focus {
            background-color: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .dark-layout .announcement-search-control {
            background-color: #181d36;
            border-color: #2d3748;
            color: #e2e8f0;
        }

        .dark-layout .announcement-search-control:focus {
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

        /* Announcement Cards */
        .announcement-card {
            border-radius: 1.25rem;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.04);
            border: 1px solid #edf2f7;
            background-color: #ffffff;
            position: relative;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 1.5rem;
        }

        .dark-layout .announcement-card {
            background-color: #1e2440;
            border-color: #2d3748;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.09);
            border-color: #cbd5e1;
        }

        .dark-layout .announcement-card:hover {
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.5);
            border-color: #4a5568;
        }

        .priority-strip-high {
            border-left: 6px solid #f43f5e;
        }

        .priority-strip-medium {
            border-left: 6px solid #f59e0b;
        }

        .priority-strip-low {
            border-left: 6px solid #6366f1;
        }

        /* Author Profile & Badges */
        .card-author-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .author-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            flex-shrink: 0;
        }

        .author-meta {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .author-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dark-layout .author-name {
            color: #f1f5f9;
        }

        .verified-badge {
            color: #3b82f6;
            font-size: 0.9rem;
        }

        .author-date {
            font-size: 0.78rem;
            color: #64748b;
        }

        .dark-layout .author-date {
            color: #94a3b8;
        }

        /* Status & Priority Pills */
        .badge-unread-pulse {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.25);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .dark-layout .badge-unread-pulse {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            margin-right: 5px;
            display: inline-block;
            animation: pulseGlow 1.8s infinite;
        }

        @keyframes pulseGlow {
            0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1.15); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .badge-priority-high {
            background: rgba(244, 63, 94, 0.12);
            color: #e11d48;
            border: 1px solid rgba(244, 63, 94, 0.25);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            text-transform: uppercase;
        }

        .dark-layout .badge-priority-high {
            background: rgba(244, 63, 94, 0.2);
            color: #fb7185;
        }

        .badge-priority-medium {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.25);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            text-transform: uppercase;
        }

        .dark-layout .badge-priority-medium {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .badge-priority-low {
            background: rgba(99, 102, 241, 0.12);
            color: #4f46e5;
            border: 1px solid rgba(99, 102, 241, 0.25);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            text-transform: uppercase;
        }

        .dark-layout .badge-priority-low {
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
        }

        .badge-read {
            background: rgba(100, 116, 139, 0.1);
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
        }

        .dark-layout .badge-read {
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
        }

        /* Typography & Content */
        .announcement-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.35;
            margin-bottom: 0.6rem;
        }

        .dark-layout .announcement-title {
            color: #f1f5f9;
        }

        .announcement-desc-callout {
            background: #f0fdfa;
            border-left: 4px solid #0d9488;
            padding: 0.65rem 0.85rem;
            border-radius: 0 0.65rem 0.65rem 0;
            font-size: 0.88rem;
            color: #0f766e;
            margin-bottom: 0.85rem;
            font-style: italic;
        }

        .dark-layout .announcement-desc-callout {
            background: #134e4a22;
            border-left-color: #2dd4bf;
            color: #5eead4;
        }

        .announcement-body-snippet {
            font-size: 0.92rem;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            white-space: pre-wrap;
        }

        .dark-layout .announcement-body-snippet {
            color: #cbd5e1;
        }

        /* Media & Attachment Showcase */
        .media-preview-box {
            margin: 1rem 0;
        }

        .announcement-thumbnail-img {
            max-height: 160px;
            max-width: 100%;
            object-fit: cover;
            border-radius: 0.75rem;
            border: 1.5px solid #e2e8f0;
            cursor: zoom-in;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .announcement-thumbnail-img:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .dark-layout .announcement-thumbnail-img {
            border-color: #2d3748;
        }

        .file-attachment-card {
            display: inline-flex;
            align-items: center;
            padding: 0.65rem 1rem;
            background-color: #fff1f2;
            border: 1.5px solid #fecdd3;
            border-radius: 0.75rem;
            color: #9f1239;
            transition: all 0.25s ease;
            text-decoration: none !important;
            gap: 10px;
        }

        .dark-layout .file-attachment-card {
            background-color: #38121e;
            border-color: #881337;
            color: #fecdd3;
        }

        .file-attachment-card:hover {
            background-color: #ffe4e6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.15);
            color: #881337;
        }

        .file-attachment-icon {
            font-size: 1.4rem;
            color: #e11d48;
        }

        .dark-layout .file-attachment-icon {
            color: #fb7185;
        }

        .file-name-text {
            font-weight: 700;
            font-size: 0.85rem;
            max-width: 260px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Card Action Button */
        .btn-read-notice {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0.55rem 1.25rem;
            border-radius: 0.75rem;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.3s ease;
        }

        .btn-read-notice:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
            filter: brightness(1.08);
        }

        .btn-read-notice i {
            transition: transform 0.25s ease;
        }

        .btn-read-notice:hover i {
            transform: translateX(4px);
        }

        /* Sidebar Widgets */
        .announcement-sidebar-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid #edf2f7;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .dark-layout .announcement-sidebar-card {
            background: #1e2440;
            border-color: #2d3748;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.25);
        }

        .sidebar-header {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 1rem;
            color: #1e293b;
        }

        .dark-layout .sidebar-header {
            border-bottom-color: #2d3748;
            color: #f1f5f9;
        }

        .sidebar-body {
            padding: 1.25rem;
        }

        .legend-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .legend-item:last-child {
            margin-bottom: 0;
        }

        .legend-icon-badge {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .legend-icon-high {
            background: rgba(244, 63, 94, 0.12);
            color: #e11d48;
        }

        .legend-icon-medium {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
        }

        .legend-icon-low {
            background: rgba(99, 102, 241, 0.12);
            color: #4f46e5;
        }

        .legend-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 0.2rem;
        }

        .dark-layout .legend-title {
            color: #f1f5f9;
        }

        .legend-desc {
            font-size: 0.78rem;
            color: #64748b;
            line-height: 1.45;
            margin-bottom: 0;
        }

        .dark-layout .legend-desc {
            color: #94a3b8;
        }

        /* Redesigned Modal */
        .announcement-modal-content {
            border-radius: 1.25rem;
            overflow: hidden;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
        }

        .dark-layout .announcement-modal-content {
            background-color: #1e2440;
        }

        .announcement-modal-header {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            color: #ffffff;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .announcement-modal-title {
            color: #ffffff;
            font-size: 1.15rem;
            font-weight: 800;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-author-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px dashed #e2e8f0;
        }

        .dark-layout .modal-author-strip {
            border-bottom-color: #2d3748;
        }

        .modal-full-content {
            font-size: 1rem;
            line-height: 1.7;
            color: #334155;
            white-space: pre-wrap;
        }

        .dark-layout .modal-full-content {
            color: #e2e8f0;
        }

        .modal-media-wrapper {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-top: 1.5rem;
            text-align: center;
        }

        .dark-layout .modal-media-wrapper {
            background: #181d36;
            border-color: #2d3748;
        }

        .modal-img-display {
            max-height: 380px;
            max-width: 100%;
            border-radius: 0.75rem;
            object-fit: contain;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        /* Empty State */
        .empty-state-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1.5px dashed #cbd5e1;
            padding: 3.5rem 1.5rem;
            text-align: center;
        }

        .dark-layout .empty-state-card {
            background: #1e2440;
            border-color: #2d3748;
        }

        .empty-state-icon {
            font-size: 3.5rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                
                @php
                    $unreadAnnouncements = $announcements->whereNotIn('id', $viewedIds);
                    $unreadCount = $unreadAnnouncements->count();
                    $highCount = $announcements->where('priority', 'high')->count();
                    $unreadHighPriority = $unreadAnnouncements->where('priority', 'high');
                    $unreadHighPriorityCount = $unreadHighPriority->count();
                    $mediaCount = $announcements->filter(fn($a) => !empty($a->media))->count();
                @endphp

                <!-- Hero Header Banner -->
                <div class="announcements-hero-card mb-2 mb-md-3">
                    <div class="p-2 p-md-4">
                        <div class="row align-items-center">
                            <div class="col-lg-7 col-md-12 mb-2 mb-lg-0">
                                <div class="announcements-badge mb-1">
                                    <i class="feather icon-volume-2 mr-50"></i>
                                    <span>ACADEMY BULLETIN &amp; NOTICES</span>
                                </div>
                                <h1 class="announcements-hero-title">Academy Announcements</h1>
                                <p class="announcements-hero-subtitle mb-0">
                                    Stay informed on term schedules, mock tests, revision reminders, and important messages from your tutors and staff.
                                </p>
                            </div>

                            <div class="col-lg-5 col-md-12">
                                <div class="hero-stats-matrix">
                                    <div class="hero-stat-box">
                                        <span class="hero-stat-num text-primary">{{ $announcements->count() }}</span>
                                        <span class="hero-stat-lbl">Total Notices</span>
                                    </div>
                                    <div class="hero-stat-box">
                                        <span class="hero-stat-num text-success d-flex align-items-center justify-content-center">
                                            @if($unreadCount > 0)
                                                <span class="pulse-dot mr-50"></span>
                                            @endif
                                            <span id="hero-unread-count">{{ $unreadCount }}</span>
                                        </span>
                                        <span class="hero-stat-lbl">Unread</span>
                                    </div>
                                    <div class="hero-stat-box">
                                        <span class="hero-stat-num text-danger" id="hero-high-count">{{ $highCount }}</span>
                                        <span class="hero-stat-lbl">High Priority</span>
                                    </div>
                                    <div class="hero-stat-box">
                                        <span class="hero-stat-num text-info" id="hero-media-count">{{ $mediaCount }}</span>
                                        <span class="hero-stat-lbl">With Files</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Urgent Attention Alert Banner -->
                <div class="urgent-alert-banner mb-3 {{ $unreadHighPriorityCount > 0 ? '' : 'd-none' }}" id="urgentAttentionBanner">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <div class="urgent-pulse-icon mr-1">
                                <i class="feather icon-alert-triangle"></i>
                            </div>
                            <div>
                                <h5 class="urgent-title mb-25">Action Required: Urgent Notice</h5>
                                <p class="urgent-subtitle mb-0">
                                    You have <strong id="urgent-unread-count">{{ $unreadHighPriorityCount }}</strong> high-priority notice(s) requiring your immediate attention.
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-urgent-action mt-1 mt-md-0" id="filterUrgentBtn">
                            <i class="feather icon-eye mr-50"></i> View Urgent Notices
                        </button>
                    </div>
                </div>

                <!-- Interactive Search and Filter Bar -->
                <div class="announcements-controls-card mb-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between p-1 p-md-2">
                        <div class="search-input-group flex-grow-1 mr-md-2 mb-1 mb-md-0 position-relative">
                            <i class="feather icon-search search-icon"></i>
                            <input type="text" id="announcement-search" class="form-control announcement-search-control" placeholder="Search notices by title, keyword, or content..." autocomplete="off">
                            <button type="button" id="search-clear" class="clear-search-btn d-none" title="Clear search">
                                <i class="feather icon-x"></i>
                            </button>
                        </div>
                        
                        <div class="filter-pills-wrapper d-flex align-items-center flex-wrap">
                            <button type="button" class="filter-pill active" data-filter="all">
                                <i class="feather icon-grid mr-25"></i> All (<span id="count-all">{{ $announcements->count() }}</span>)
                            </button>
                            <button type="button" class="filter-pill" data-filter="unread">
                                <i class="feather icon-mail mr-25"></i> Unread (<span id="count-unread">{{ $unreadCount }}</span>)
                            </button>
                            <button type="button" class="filter-pill" data-filter="high">
                                <i class="feather icon-alert-triangle mr-25"></i> High Priority (<span id="count-high">{{ $highCount }}</span>)
                            </button>
                            <button type="button" class="filter-pill" data-filter="media">
                                <i class="feather icon-paperclip mr-25"></i> Attachments (<span id="count-media">{{ $mediaCount }}</span>)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Layout: Feed + Sidebar Widgets -->
                <div class="row">
                    <!-- Left Feed Column -->
                    <div class="col-lg-8 col-12" id="announcements-container">
                        @forelse($announcements as $announcement)
                            @php
                                $isRead = in_array($announcement->id, $viewedIds);
                                $priorityClass = 'priority-strip-' . ($announcement->priority ?? 'low');
                                $dateObj = $announcement->show_from ?: $announcement->created_at;
                            @endphp

                            <div class="card announcement-card {{ $priorityClass }}" 
                                 id="announcement-{{ $announcement->id }}"
                                 data-id="{{ $announcement->id }}"
                                 data-priority="{{ $announcement->priority ?? 'low' }}"
                                 data-read="{{ $isRead ? 'true' : 'false' }}"
                                 data-media="{{ !empty($announcement->media) ? 'true' : 'false' }}">
                                
                                <div class="card-body p-2 p-md-3">
                                    <!-- Card Header: Author Profile & Status Badges -->
                                    <div class="card-author-row justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="author-avatar">
                                                <i class="feather icon-shield"></i>
                                            </div>
                                            <div class="author-meta ml-1">
                                                <span class="author-name">
                                                    Academy Administration 
                                                    <i class="feather icon-check-circle verified-badge" title="Official Notice"></i>
                                                </span>
                                                <span class="author-date">
                                                    <i class="feather icon-calendar mr-25"></i> 
                                                    {{ $dateObj ? $dateObj->format('M d, Y · h:i A') : 'Recent' }}
                                                    @if($dateObj)
                                                        <span class="mx-25">·</span>
                                                        <span class="text-muted font-italic">{{ $dateObj->diffForHumans() }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-1 mt-1 mt-sm-0">
                                            @if(!$isRead)
                                                <span class="badge-unread-pulse" id="unread-pill-{{ $announcement->id }}">
                                                    <span class="pulse-dot"></span> New
                                                </span>
                                            @endif

                                            @if($announcement->priority === 'high')
                                                <span class="badge-priority-high">
                                                    <i class="feather icon-alert-triangle mr-25"></i> High Priority
                                                </span>
                                            @elseif($announcement->priority === 'medium')
                                                <span class="badge-priority-medium">
                                                    <i class="feather icon-star mr-25"></i> Featured
                                                </span>
                                            @else
                                                <span class="badge-priority-low">
                                                    General
                                                </span>
                                            @endif

                                            <span class="badge-read {{ !$isRead ? 'd-none' : '' }}" id="read-badge-{{ $announcement->id }}">
                                                <i class="feather icon-check mr-25"></i> Read
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Announcement Title -->
                                    <h3 class="announcement-title text-left">{{ $announcement->title }}</h3>

                                    <!-- Description Highlight Callout (Type 3) -->
                                    @if(!empty($announcement->description))
                                        <div class="announcement-desc-callout text-left">
                                            <i class="feather icon-info mr-25"></i> {{ $announcement->description }}
                                        </div>
                                    @endif

                                    <!-- Content Body Snippet -->
                                    @if(!empty($announcement->content))
                                        <p class="announcement-body-snippet text-left">{{ $announcement->content }}</p>
                                    @endif

                                    <!-- Media Preview Thumbnail / Document Box -->
                                    @if(!empty($announcement->media))
                                        <div class="media-preview-box text-left">
                                            @if(preg_match('/\.(jpeg|jpg|gif|png|webp|svg)/i', $announcement->media))
                                                <div class="d-inline-block position-relative">
                                                    <img src="{{ asset('storage/' . $announcement->media) }}" 
                                                         class="announcement-thumbnail-img" 
                                                         alt="Notice image" 
                                                         loading="lazy">
                                                </div>
                                            @else
                                                <a href="{{ asset('storage/' . $announcement->media) }}" target="_blank" class="file-attachment-card">
                                                    <i class="feather icon-file-text file-attachment-icon"></i>
                                                    <div class="text-left">
                                                        <div class="file-name-text">{{ basename($announcement->media) }}</div>
                                                        <small class="text-muted">Click to Download Attachment</small>
                                                    </div>
                                                    <i class="feather icon-download-cloud ml-1"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Card Action Footer -->
                                    <div class="d-flex align-items-center justify-content-between pt-1 border-top mt-2">
                                        <div class="font-small-2 text-muted">
                                            <i class="feather icon-bookmark mr-25"></i> Aspire Learners Academy Bulletin
                                        </div>
                                        <button type="button" 
                                                class="btn-read-notice view-announcement-btn"
                                                data-id="{{ $announcement->id }}"
                                                data-title="{{ $announcement->title }}"
                                                data-content="{{ $announcement->content }}"
                                                data-desc="{{ $announcement->description }}"
                                                data-priority="{{ $announcement->priority ?? 'low' }}"
                                                data-media="{{ !empty($announcement->media) ? asset('storage/' . $announcement->media) : '' }}"
                                                data-media-name="{{ basename($announcement->media ?? '') }}"
                                                data-date="{{ $dateObj ? $dateObj->format('M d, Y · h:i A') : 'Recent' }}"
                                                data-view-url="{{ route('student.announcements.view', $announcement->id) }}">
                                            <span>Read Full Notice</span>
                                            <i class="feather icon-arrow-right"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="empty-state-card py-5">
                                <div class="empty-state-icon">
                                    <i class="feather icon-bell-off"></i>
                                </div>
                                <h4 class="font-weight-bold">No announcements published</h4>
                                <p class="text-muted">There are currently no active announcements or bulletins for your cohort.</p>
                            </div>
                        @endforelse

                        <!-- No Results Placeholder for Filters / Search -->
                        <div class="empty-state-card py-5 d-none" id="empty-state-placeholder">
                            <div class="empty-state-icon text-muted">
                                <i class="feather icon-search"></i>
                            </div>
                            <h4 class="font-weight-bold">No matching announcements</h4>
                            <p class="text-muted">We couldn't find any notices matching your selected filter or search keywords.</p>
                            <button type="button" class="btn btn-primary mt-1" id="reset-filters-btn">
                                <i class="feather icon-refresh-cw mr-50"></i> Reset Filters
                            </button>
                        </div>
                    </div>

                    <!-- Right Sidebar Column -->
                    <div class="col-lg-4 col-12">
                        <!-- Pinned / Urgent Notice Spotlight Widget -->
                        @if($announcements->isNotEmpty())
                            @php
                                $spotlightNotice = $announcements->where('priority', 'high')->first() ?? $announcements->first();
                            @endphp
                            <div class="announcement-sidebar-card">
                                <div class="sidebar-header">
                                    <i class="feather icon-zap text-warning mr-50"></i>
                                    <span>Spotlight Notice</span>
                                </div>
                                <div class="sidebar-body text-left">
                                    <span class="badge badge-pill badge-light-{{ $spotlightNotice->priority === 'high' ? 'danger' : 'primary' }} mb-50 font-small-1 font-weight-bold text-uppercase">
                                        {{ $spotlightNotice->priority === 'high' ? 'High Priority' : 'Featured Bulletin' }}
                                    </span>
                                    <h5 class="font-weight-bold text-dark mb-50">{{ Str::limit($spotlightNotice->title, 55) }}</h5>
                                    <p class="text-muted font-small-3 mb-1">
                                        {{ Str::limit($spotlightNotice->description ?: $spotlightNotice->content, 90) }}
                                    </p>
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-sm btn-block view-announcement-btn"
                                            data-id="{{ $spotlightNotice->id }}"
                                            data-title="{{ $spotlightNotice->title }}"
                                            data-content="{{ $spotlightNotice->content }}"
                                            data-desc="{{ $spotlightNotice->description }}"
                                            data-priority="{{ $spotlightNotice->priority ?? 'low' }}"
                                            data-media="{{ !empty($spotlightNotice->media) ? asset('storage/' . $spotlightNotice->media) : '' }}"
                                            data-media-name="{{ basename($spotlightNotice->media ?? '') }}"
                                            data-date="{{ $spotlightNotice->created_at ? $spotlightNotice->created_at->format('M d, Y · h:i A') : 'Recent' }}"
                                            data-view-url="{{ route('student.announcements.view', $spotlightNotice->id) }}">
                                        Read Spotlight <i class="feather icon-arrow-right ml-25"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Notice Priority Legend Card -->
                        <div class="announcement-sidebar-card">
                            <div class="sidebar-header">
                                <i class="feather icon-info text-info mr-50"></i>
                                <span>Priority Classifications</span>
                            </div>
                            <div class="sidebar-body text-left">
                                <div class="legend-item">
                                    <div class="legend-icon-badge legend-icon-high">
                                        <i class="feather icon-alert-circle"></i>
                                    </div>
                                    <div>
                                        <h6 class="legend-title">High Priority</h6>
                                        <p class="legend-desc">Requires immediate action, exam timetable dates, or critical portal alerts.</p>
                                    </div>
                                </div>

                                <div class="legend-item">
                                    <div class="legend-icon-badge legend-icon-medium">
                                        <i class="feather icon-star"></i>
                                    </div>
                                    <div>
                                        <h6 class="legend-title">Featured Updates</h6>
                                        <p class="legend-desc">Course homework updates, mock test releases, and tutor reminders.</p>
                                    </div>
                                </div>

                                <div class="legend-item">
                                    <div class="legend-icon-badge legend-icon-low">
                                        <i class="feather icon-layers"></i>
                                    </div>
                                    <div>
                                        <h6 class="legend-title">General Bulletin</h6>
                                        <p class="legend-desc">Weekly study tips, term calendars, and general student community news.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academy Student Help & Contacts Card -->
                        <div class="announcement-sidebar-card">
                            <div class="sidebar-header">
                                <i class="feather icon-help-circle text-primary mr-50"></i>
                                <span>Academy Support Desk</span>
                            </div>
                            <div class="sidebar-body text-left">
                                <p class="text-muted font-small-3 mb-1">
                                    Have a question regarding any notice, test schedule, or portal update?
                                </p>
                                <div class="d-flex align-items-center mb-50 font-small-3">
                                    <i class="feather icon-mail text-primary mr-50"></i>
                                    <span class="text-dark font-weight-bold">support@aspirelearners.co.uk</span>
                                </div>
                                <div class="d-flex align-items-center font-small-3 text-muted">
                                    <i class="feather icon-clock text-secondary mr-50"></i>
                                    <span>Mon - Fri: 9:00 AM - 5:00 PM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Redesigned Executive Notice Details Modal -->
    <div class="modal fade" id="announcementDetailModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content announcement-modal-content">
                <div class="announcement-modal-header">
                    <h5 class="announcement-modal-title" id="modalLabel">
                        <i class="feather icon-book-open text-primary"></i>
                        <span>Notice Details</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-2 p-md-3 text-left">
                    <!-- Author & Meta Info Row -->
                    <div class="modal-author-strip">
                        <div class="d-flex align-items-center">
                            <div class="author-avatar">
                                <i class="feather icon-shield"></i>
                            </div>
                            <div class="author-meta ml-1">
                                <span class="author-name">
                                    Academy Administration 
                                    <i class="feather icon-check-circle verified-badge"></i>
                                </span>
                                <span class="author-date" id="detail-date-text">
                                    <i class="feather icon-calendar mr-25"></i> Recently
                                </span>
                            </div>
                        </div>

                        <span id="detail-priority-badge" class="badge-priority-medium">
                            Featured
                        </span>
                    </div>

                    <!-- Notice Title -->
                    <h2 id="detail-title" class="announcement-title font-weight-bold mb-1" style="font-size: 1.45rem;">Title</h2>

                    <!-- Description Callout (if available) -->
                    <div id="detail-desc-container" class="announcement-desc-callout mb-2" style="display:none;">
                        <i class="feather icon-info mr-25"></i> <span id="detail-desc"></span>
                    </div>

                    <!-- Full Body Content -->
                    <div id="detail-content" class="modal-full-content mb-2">
                        Content
                    </div>

                    <!-- Media / Attachment Area -->
                    <div id="detail-media-container" class="modal-media-wrapper" style="display:none;">
                        <!-- Image Box -->
                        <div id="detail-image-box" style="display:none;">
                            <a href="" id="detail-img-link" target="_blank" title="Click to view full image in new tab">
                                <img src="" id="detail-img" class="modal-img-display" alt="Attached preview">
                            </a>
                            <div class="mt-50 text-muted font-small-2">
                                <i class="feather icon-maximize-2 mr-25"></i> Click image to view in high resolution
                            </div>
                        </div>

                        <!-- Document Download Box -->
                        <div id="detail-file-box" style="display:none;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-1 border rounded bg-white">
                                <div class="d-flex align-items-center">
                                    <i class="feather icon-file-text font-large-1 text-danger mr-1"></i>
                                    <div class="text-left">
                                        <div id="detail-file-name" class="font-weight-bold text-dark text-truncate" style="max-width: 320px;">document.pdf</div>
                                        <small class="text-muted">Official Document Attachment</small>
                                    </div>
                                </div>
                                <a href="" id="detail-file-download" class="btn btn-danger btn-sm font-weight-bold" target="_blank" download>
                                    <i class="feather icon-download mr-50"></i> Download Document
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex align-items-center justify-content-between" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <div class="text-success font-small-3 font-weight-bold d-flex align-items-center">
                        <i class="feather icon-check-circle mr-50 font-medium-1"></i> Status: Viewed
                    </div>
                    <button type="button" class="btn btn-secondary px-2" data-dismiss="modal">Close Notice</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('announcement-search');
            var clearIcon = document.getElementById('search-clear');
            var filterPills = document.querySelectorAll('.filter-pill');
            var cards = document.querySelectorAll('.announcement-card');
            var emptyState = document.getElementById('empty-state-placeholder');
            var resetBtn = document.getElementById('reset-filters-btn');
            var filterUrgentBtn = document.getElementById('filterUrgentBtn');

            var currentFilter = 'all';
            var currentSearch = '';

            // Update Dynamic Counter Badges
            function updateLiveCounters() {
                var total = cards.length;
                var unread = 0;
                var high = 0;
                var media = 0;
                var unreadHigh = 0;

                cards.forEach(function(card) {
                    var isRead = card.getAttribute('data-read') === 'true';
                    var isHigh = card.getAttribute('data-priority') === 'high';
                    var hasMedia = card.getAttribute('data-media') === 'true';

                    if (!isRead) unread++;
                    if (isHigh) high++;
                    if (hasMedia) media++;
                    if (!isRead && isHigh) unreadHigh++;
                });

                var countAll = document.getElementById('count-all');
                var countUnread = document.getElementById('count-unread');
                var countHigh = document.getElementById('count-high');
                var countMedia = document.getElementById('count-media');
                var heroUnread = document.getElementById('hero-unread-count');
                var heroHigh = document.getElementById('hero-high-count');
                var heroMedia = document.getElementById('hero-media-count');
                var urgentUnread = document.getElementById('urgent-unread-count');
                var urgentBanner = document.getElementById('urgentAttentionBanner');

                if (countAll) countAll.textContent = total;
                if (countUnread) countUnread.textContent = unread;
                if (countHigh) countHigh.textContent = high;
                if (countMedia) countMedia.textContent = media;
                if (heroUnread) heroUnread.textContent = unread;
                if (heroHigh) heroHigh.textContent = high;
                if (heroMedia) heroMedia.textContent = media;
                if (urgentUnread) urgentUnread.textContent = unreadHigh;

                if (urgentBanner) {
                    if (unreadHigh > 0) {
                        urgentBanner.classList.remove('d-none');
                    } else {
                        urgentBanner.classList.add('d-none');
                    }
                }
            }

            updateLiveCounters();

            // Apply Filters and Search
            function applyFilters() {
                var query = currentSearch.toLowerCase().trim();
                var visibleCount = 0;

                if (clearIcon) {
                    if (query.length > 0) {
                        clearIcon.classList.remove('d-none');
                    } else {
                        clearIcon.classList.add('d-none');
                    }
                }

                cards.forEach(function(card) {
                    var title = (card.querySelector('.announcement-title')?.textContent || '').toLowerCase();
                    var desc = (card.querySelector('.announcement-desc-callout')?.textContent || '').toLowerCase();
                    var content = (card.querySelector('.announcement-body-snippet')?.textContent || '').toLowerCase();
                    
                    var isRead = card.getAttribute('data-read') === 'true';
                    var isHigh = card.getAttribute('data-priority') === 'high';
                    var hasMedia = card.getAttribute('data-media') === 'true';

                    // 1. Filter Match
                    var matchesFilter = false;
                    if (currentFilter === 'all') matchesFilter = true;
                    else if (currentFilter === 'unread' && !isRead) matchesFilter = true;
                    else if (currentFilter === 'high' && isHigh) matchesFilter = true;
                    else if (currentFilter === 'media' && hasMedia) matchesFilter = true;

                    // 2. Search Query Match
                    var matchesQuery = !query || title.indexOf(query) !== -1 || desc.indexOf(query) !== -1 || content.indexOf(query) !== -1;

                    if (matchesFilter && matchesQuery) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (emptyState) {
                    if (visibleCount === 0 && cards.length > 0) {
                        emptyState.classList.remove('d-none');
                    } else {
                        emptyState.classList.add('d-none');
                    }
                }
            }

            // Search Event Listeners
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    currentSearch = this.value;
                    applyFilters();
                });
            }

            if (clearIcon) {
                clearIcon.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    currentSearch = '';
                    applyFilters();
                    if (searchInput) searchInput.focus();
                });
            }

            // Filter Pills Click Handlers
            filterPills.forEach(function(pill) {
                pill.addEventListener('click', function() {
                    filterPills.forEach(function(p) { p.classList.remove('active'); });
                    this.classList.add('active');
                    currentFilter = this.getAttribute('data-filter');
                    applyFilters();
                });
            });

            // Urgent Banner Button Click
            if (filterUrgentBtn) {
                filterUrgentBtn.addEventListener('click', function() {
                    filterPills.forEach(function(p) {
                        if (p.getAttribute('data-filter') === 'high') {
                            p.classList.add('active');
                        } else {
                            p.classList.remove('active');
                        }
                    });
                    currentFilter = 'high';
                    applyFilters();
                    window.scrollTo({ top: document.getElementById('announcements-container').offsetTop - 120, behavior: 'smooth' });
                });
            }

            // Reset Filters Click
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    currentSearch = '';
                    currentFilter = 'all';
                    filterPills.forEach(function(p) {
                        if (p.getAttribute('data-filter') === 'all') {
                            p.classList.add('active');
                        } else {
                            p.classList.remove('active');
                        }
                    });
                    applyFilters();
                });
            }

            // View Notice Modal Event
            $(document).on('click', '.view-announcement-btn', function() {
                var btn = $(this);
                var id = btn.data('id');
                var title = btn.data('title') || '';
                var content = btn.data('content') || '';
                var desc = btn.data('desc') || '';
                var priority = btn.data('priority') || 'low';
                var media = btn.data('media') || '';
                var mediaName = btn.data('media-name') || '';
                var date = btn.data('date') || '';
                var viewUrl = btn.data('view-url') || '';

                // Populate Modal Fields
                $('#detail-title').text(title);
                $('#detail-date-text').html('<i class="feather icon-calendar mr-25"></i> ' + date);

                // Priority Badge in Modal
                var pBadge = $('#detail-priority-badge');
                pBadge.removeClass('badge-priority-high badge-priority-medium badge-priority-low');
                if (priority === 'high') {
                    pBadge.addClass('badge-priority-high').html('<i class="feather icon-alert-triangle mr-25"></i> High Priority');
                } else if (priority === 'medium') {
                    pBadge.addClass('badge-priority-medium').html('<i class="feather icon-star mr-25"></i> Featured');
                } else {
                    pBadge.addClass('badge-priority-low').text('General Notice');
                }

                // Description Callout
                var descContainer = $('#detail-desc-container');
                if (desc && desc.trim().length > 0) {
                    $('#detail-desc').text(desc);
                    descContainer.show();
                } else {
                    descContainer.hide();
                }

                // Full Content
                var contentDiv = $('#detail-content');
                if (content && content.trim().length > 0) {
                    contentDiv.text(content).show();
                } else {
                    contentDiv.hide();
                }

                // Media Showcase
                var mediaContainer = $('#detail-media-container');
                var imgBox = $('#detail-image-box');
                var fileBox = $('#detail-file-box');

                mediaContainer.hide();
                imgBox.hide();
                fileBox.hide();

                if (media && media.length > 0) {
                    mediaContainer.show();
                    if (media.match(/\.(jpeg|jpg|gif|png|webp|svg)/i)) {
                        $('#detail-img').attr('src', media);
                        $('#detail-img-link').attr('href', media);
                        imgBox.show();
                    } else {
                        $('#detail-file-name').text(mediaName);
                        $('#detail-file-download').attr('href', media);
                        fileBox.show();
                    }
                }

                // Trigger Modal
                $('#announcementDetailModal').modal('show');

                // Mark Notice as Viewed via Axios
                var targetCard = document.getElementById('announcement-' + id);
                if (targetCard) {
                    var isRead = targetCard.getAttribute('data-read') === 'true';
                    if (!isRead) {
                        axios.post(viewUrl)
                            .then(function(response) {
                                if (response.data && response.data.success) {
                                    targetCard.setAttribute('data-read', 'true');
                                    var unreadPill = document.getElementById('unread-pill-' + id);
                                    if (unreadPill) unreadPill.remove();
                                    var readBadge = document.getElementById('read-badge-' + id);
                                    if (readBadge) readBadge.classList.remove('d-none');
                                    updateLiveCounters();
                                }
                            })
                            .catch(function(err) {
                                console.warn('Could not record announcement view:', err);
                            });
                    }
                }
            });
        });
    </script>
@endpush
