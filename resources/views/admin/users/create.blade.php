@extends('layouts.app')

@push('styles')
<style>
    /* ==========================================================================
       Aspire Learners - Premium User Creation Interface
       ========================================================================== */
    .user-create-container {
        font-family: var(--font-family, 'Inter', sans-serif);
    }

    /* Form Section Cards */
    .premium-form-card {
        background: var(--card-bg, #FFFFFF);
        border: 1px solid var(--border-color, #E2E8F0);
        border-radius: 14px;
        box-shadow: 0 4px 20px -4px rgba(10, 27, 63, 0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .premium-form-card:hover {
        box-shadow: 0 8px 30px -4px rgba(10, 27, 63, 0.08);
    }

    .premium-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        background: var(--card-bg, #FFFFFF);
        border-bottom: 1px solid var(--border-subtle, #EDF2F7);
    }

    .premium-card-header-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .premium-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--theme-primary-light, #EBF3FF);
        color: var(--theme-primary-bright, #2477F9);
    }

    .premium-header-icon.role-icon-box {
        background: rgba(139, 92, 246, 0.12);
        color: #8B5CF6;
    }

    .premium-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary, #0A1B3F);
        margin: 0;
        line-height: 1.25;
    }

    .premium-card-subtitle {
        font-size: 0.8125rem;
        color: var(--text-muted, #7A8499);
        margin: 0.2rem 0 0 0;
    }

    .premium-badge-required {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #F1F5F9;
        color: #475569;
    }

    .premium-card-body {
        padding: 1.5rem;
    }

    /* Form Fields & Labels */
    .premium-form-group {
        margin-bottom: 1.35rem;
    }

    .premium-form-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary, #0A1B3F);
        margin-bottom: 0.45rem;
    }

    .premium-form-label .label-req {
        color: var(--theme-danger, #FA2833);
        margin-left: 2px;
    }

    .premium-form-label-meta {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-muted, #7A8499);
    }

    /* Input Group Styling */
    .premium-input-group {
        position: relative;
        display: flex;
        align-items: stretch;
        width: 100%;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .premium-input-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        pointer-events: none;
        z-index: 4;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }

    .premium-input-group .form-control {
        height: 46px;
        padding-left: 2.65rem;
        padding-right: 0.95rem;
        border-radius: 10px !important;
        border: 1.5px solid var(--border-color, #E2E8F0);
        background: var(--card-bg, #FFFFFF);
        color: var(--text-primary, #0A1B3F);
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .premium-input-group .form-control:focus {
        border-color: var(--theme-primary-bright, #2477F9);
        box-shadow: 0 0 0 3.5px var(--theme-primary-glow, rgba(36, 119, 249, 0.18));
        background: var(--card-bg, #FFFFFF);
    }

    .premium-input-group:focus-within .premium-input-icon {
        color: var(--theme-primary-bright, #2477F9);
    }

    /* Password Input Extras */
    .premium-input-group .form-control.has-toggle {
        padding-right: 3rem;
    }

    .premium-toggle-pw {
        position: absolute;
        right: 0.6rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #94A3B8;
        padding: 0.35rem 0.5rem;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
        transition: color 0.15s ease, background-color 0.15s ease;
    }

    .premium-toggle-pw:hover {
        color: var(--theme-primary-bright, #2477F9);
        background: rgba(36, 119, 249, 0.08);
    }

    .premium-input-help {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.775rem;
        color: var(--text-muted, #7A8499);
        margin-top: 0.4rem;
    }

    /* Username Suggestion Pill */
    .username-suggest-wrapper {
        margin-top: 0.5rem;
        display: none;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .username-suggest-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: var(--theme-primary-light, #EBF3FF);
        border: 1px solid rgba(36, 119, 249, 0.25);
        color: var(--theme-primary-bright, #2477F9);
        font-size: 0.775rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .username-suggest-badge * {
        pointer-events: none;
    }

    .username-suggest-badge:hover {
        background: var(--theme-primary-bright, #2477F9);
        color: #FFFFFF;
        border-color: var(--theme-primary-bright, #2477F9);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(36, 119, 249, 0.25);
    }

    .username-refresh-btn {
        background: transparent;
        border: none;
        padding: 0.25rem 0.4rem;
        color: var(--text-muted, #7A8499);
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }

    .username-refresh-btn:hover {
        color: var(--theme-primary-bright, #2477F9);
        background: var(--theme-primary-light, #EBF3FF);
    }

    /* Password Generator Button */
    .btn-generate-pw {
        background: transparent;
        border: 1px solid var(--border-color, #E2E8F0);
        color: var(--theme-primary-bright, #2477F9);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-generate-pw:hover {
        background: var(--theme-primary-light, #EBF3FF);
        border-color: var(--theme-primary-bright, #2477F9);
        color: var(--theme-primary-mid, #1757C1);
    }

    /* Password Strength Meter */
    .pw-strength-box {
        margin-top: 0.65rem;
        padding: 0.75rem;
        background: #F8FAFC;
        border: 1px solid var(--border-subtle, #EDF2F7);
        border-radius: 8px;
    }

    .pw-strength-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    .pw-strength-bars {
        display: flex;
        gap: 4px;
        height: 5px;
        margin-bottom: 0.55rem;
    }

    .pw-strength-bar {
        flex: 1;
        height: 100%;
        border-radius: 4px;
        background: #E2E8F0;
        transition: background-color 0.25s ease;
    }

    .pw-rules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 0.35rem;
    }

    .pw-rule-item {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.725rem;
        color: #94A3B8;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .pw-rule-item.valid {
        color: var(--theme-success, #1EB564);
        font-weight: 600;
    }

    .pw-rule-item svg {
        width: 13px;
        height: 13px;
    }

    /* Role Selection Grid */
    .role-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (max-width: 576px) {
        .role-grid {
            grid-template-columns: 1fr;
        }
    }

    .role-card {
        position: relative;
        border: 2px solid var(--border-color, #E2E8F0);
        border-radius: 12px;
        padding: 1.15rem;
        background: var(--card-bg, #FFFFFF);
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        user-select: none;
    }

    .role-card:hover {
        border-color: #CBD5E1;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    .role-card.active {
        border-color: var(--theme-primary-bright, #2477F9);
        background: rgba(36, 119, 249, 0.02);
        box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.12), 0 8px 20px rgba(36, 119, 249, 0.08);
    }

    .role-card.active-student {
        border-color: #2477F9;
    }
    .role-card.active-parent {
        border-color: #F59E0B;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15), 0 8px 20px rgba(245, 158, 11, 0.08);
    }
    .role-card.active-tutor {
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15), 0 8px 20px rgba(139, 92, 246, 0.08);
    }
    .role-card.active-admin {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15), 0 8px 20px rgba(16, 185, 129, 0.08);
    }

    .role-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .role-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .role-card:hover .role-icon-wrapper {
        transform: scale(1.05);
    }

    .role-student .role-icon-wrapper {
        background: #EBF3FF;
        color: #2477F9;
    }
    .role-parent .role-icon-wrapper {
        background: #FFF5E8;
        color: #F59E0B;
    }
    .role-tutor .role-icon-wrapper {
        background: #F3EBFC;
        color: #8B5CF6;
    }
    .role-admin .role-icon-wrapper {
        background: #EAF8F0;
        color: #10B981;
    }

    .role-radio-check {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid #CBD5E1;
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        transition: all 0.2s ease;
    }

    .role-card.active .role-radio-check {
        background: var(--theme-primary-bright, #2477F9);
        border-color: var(--theme-primary-bright, #2477F9);
    }

    .role-card.active-parent .role-radio-check {
        background: #F59E0B;
        border-color: #F59E0B;
    }
    .role-card.active-tutor .role-radio-check {
        background: #8B5CF6;
        border-color: #8B5CF6;
    }
    .role-card.active-admin .role-radio-check {
        background: #10B981;
        border-color: #10B981;
    }

    .role-card-content {
        flex: 1;
    }

    .role-title-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.35rem;
    }

    .role-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary, #0A1B3F);
        margin: 0;
    }

    .role-badge {
        font-size: 0.6875rem;
        font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .role-student .role-badge { background: #EBF3FF; color: #2477F9; }
    .role-parent .role-badge { background: #FFF5E8; color: #D97706; }
    .role-tutor .role-badge { background: #F3EBFC; color: #8B5CF6; }
    .role-admin .role-badge { background: #EAF8F0; color: #059669; }

    .role-desc {
        font-size: 0.8rem;
        line-height: 1.35;
        color: var(--text-muted, #7A8499);
        margin: 0;
    }

    /* Actions Bar */
    .form-actions-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.15rem 1.5rem;
        background: var(--card-bg, #FFFFFF);
        border: 1px solid var(--border-color, #E2E8F0);
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .btn-create-submit {
        background: linear-gradient(135deg, #2477F9 0%, #1757C1 100%);
        border: none;
        color: #FFFFFF;
        font-size: 0.95rem;
        font-weight: 600;
        padding: 0.75rem 1.75rem;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(36, 119, 249, 0.35);
        transition: all 0.25s ease;
    }

    .btn-create-submit:hover {
        background: linear-gradient(135deg, #1757C1 0%, #063080 100%);
        box-shadow: 0 6px 20px rgba(36, 119, 249, 0.45);
        transform: translateY(-1.5px);
        color: #FFFFFF;
    }

    .btn-create-cancel {
        background: #FFFFFF;
        border: 1.5px solid var(--border-color, #E2E8F0);
        color: #475569;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.7rem 1.4rem;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-create-cancel:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
        color: var(--theme-danger, #FA2833);
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-create-reset {
        background: transparent;
        border: none;
        color: var(--text-muted, #7A8499);
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .btn-create-reset:hover {
        color: var(--text-primary, #0A1B3F);
    }

    /* Right Column: Live User Preview Card */
    .preview-sticky-wrap {
        position: sticky;
        top: 85px;
    }

    .live-preview-card {
        background: var(--card-bg, #FFFFFF);
        border: 1px solid var(--border-color, #E2E8F0);
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(10, 27, 63, 0.08);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .preview-cover-banner {
        height: 80px;
        background: linear-gradient(135deg, #063080 0%, #1757C1 50%, #2477F9 100%);
        position: relative;
    }

    .preview-live-badge {
        position: absolute;
        top: 10px;
        right: 12px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(6px);
        color: #FFFFFF;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 9999px;
        letter-spacing: 0.02em;
    }

    .preview-live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10B981;
        box-shadow: 0 0 6px #10B981;
        animation: pulse-dot 1.8s infinite;
    }

    @keyframes pulse-dot {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.8; }
    }

    .preview-body {
        padding: 0 1.25rem 1.25rem 1.25rem;
        margin-top: -36px;
        position: relative;
    }

    .preview-avatar-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .preview-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        border: 4px solid var(--card-bg, #FFFFFF);
        background: linear-gradient(135deg, #2477F9, #1757C1);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        transition: background 0.3s ease;
    }

    .preview-avatar.role-student { background: linear-gradient(135deg, #2477F9, #1757C1); }
    .preview-avatar.role-parent { background: linear-gradient(135deg, #F59E0B, #D97706); }
    .preview-avatar.role-tutor { background: linear-gradient(135deg, #8B5CF6, #6D28D9); }
    .preview-avatar.role-admin { background: linear-gradient(135deg, #10B981, #059669); }

    .preview-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #EAF8F0;
        color: #10B981;
    }

    .preview-user-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-primary, #0A1B3F);
        margin: 0 0 0.15rem 0;
        word-break: break-word;
    }

    .preview-username-handle {
        font-size: 0.825rem;
        color: var(--text-muted, #7A8499);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-bottom: 0.75rem;
    }

    .preview-info-list {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        padding: 0.85rem 0;
        border-top: 1px dashed var(--border-color, #E2E8F0);
        border-bottom: 1px dashed var(--border-color, #E2E8F0);
        margin-bottom: 0.85rem;
    }

    .preview-info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8rem;
    }

    .preview-info-label {
        color: var(--text-muted, #7A8499);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .preview-info-val {
        font-weight: 600;
        color: var(--text-primary, #0A1B3F);
        max-width: 170px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .preview-role-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.65rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .preview-meta-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--text-muted, #7A8499);
    }

    /* Side Info Card (Permissions Highlights) */
    .permissions-info-card {
        background: var(--card-bg, #FFFFFF);
        border: 1px solid var(--border-color, #E2E8F0);
        border-radius: 14px;
        padding: 1.15rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.25rem;
    }

    .permissions-info-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-primary, #0A1B3F);
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .permissions-checklist {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .permissions-checklist li {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        font-size: 0.785rem;
        line-height: 1.35;
        color: var(--text-body, #334155);
    }

    .permissions-checklist li svg {
        color: var(--theme-success, #1EB564);
        flex-shrink: 0;
        margin-top: 2px;
        width: 14px;
        height: 14px;
    }

    /* Dark Mode Adjustments */
    body.dark-layout .premium-form-card,
    body.dark-layout .premium-card-header,
    body.dark-layout .form-actions-card,
    body.dark-layout .live-preview-card,
    body.dark-layout .permissions-info-card,
    body.dark-layout .role-card {
        background: var(--card-bg, #1E293B);
        border-color: var(--border-color, #334155);
    }

    body.dark-layout .premium-input-group .form-control {
        background: #0F172A;
        border-color: #334155;
        color: #F8FAFC;
    }

    body.dark-layout .premium-input-group .form-control:focus {
        background: #0F172A;
        border-color: #60A5FA;
    }

    body.dark-layout .pw-strength-box {
        background: #0F172A;
        border-color: #334155;
    }

    body.dark-layout .pw-strength-bar {
        background: #334155;
    }

    body.dark-layout .premium-badge-required {
        background: #334155;
        color: #CBD5E1;
    }

    body.dark-layout .btn-create-cancel {
        background: #1E293B;
        border-color: #334155;
        color: #CBD5E1;
    }

    body.dark-layout .btn-create-cancel:hover {
        background: #334155;
    }

    body.dark-layout .preview-avatar {
        border-color: #1E293B;
    }

    body.dark-layout .preview-info-list {
        border-color: #334155;
    }
</style>
@endpush

@section('content')
<div class="app-content content user-create-container">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    
    <div class="content-wrapper">
        
        <!-- Page Header & Breadcrumbs -->
        <div class="admin-page-header">
            <div class="admin-page-title-wrap">
                <div class="admin-title-row">
                    <h1 class="admin-page-heading">Add New User</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.users.index') }}">Users</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>Add</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Configure credentials, security attributes, and role-based permissions</p>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-admin-secondary">
                    <i data-lucide="arrow-left" style="width: 17px; height: 17px;"></i>
                    <span>Back to Users</span>
                </a>
            </div>
        </div>

        <div class="content-body">
            
            {{-- Validation Errors Alert --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border: 1px solid #FCD6D9; background: #FFF5F5; margin-bottom: 1.5rem;">
                    <div class="d-flex align-items-start">
                        <i data-lucide="alert-triangle" class="text-danger mr-1" style="width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px;"></i>
                        <div>
                            <strong style="color: #991B1B; font-size: 0.95rem;">Please review the errors below:</strong>
                            <ul class="mb-0 mt-1 pl-3" style="color: #B91C1C; font-size: 0.85rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
                @csrf

                <div class="row">
                    
                    {{-- ==================== LEFT COLUMN: FORM SECTIONS ==================== --}}
                    <div class="col-xl-8 col-lg-8 col-12">
                        
                        <!-- CARD 1: PERSONAL & LOGIN CREDENTIALS -->
                        <div class="premium-form-card">
                            <div class="premium-card-header">
                                <div class="premium-card-header-left">
                                    <div class="premium-header-icon">
                                        <i data-lucide="user-check" style="width: 22px; height: 22px;"></i>
                                    </div>
                                    <div>
                                        <h2 class="premium-card-title">Personal & Authentication Details</h2>
                                        <p class="premium-card-subtitle">User identity credentials for signing in to Aspire Learners</p>
                                    </div>
                                </div>
                                <span class="premium-badge-required">
                                    <i data-lucide="shield" style="width: 13px; height: 13px;"></i> Required
                                </span>
                            </div>

                            <div class="premium-card-body">
                                <div class="row">
                                    
                                    <!-- Full Name -->
                                    <div class="col-md-6 col-12">
                                        <div class="premium-form-group">
                                            <label class="premium-form-label" for="name">
                                                <span>Full Name <span class="label-req">*</span></span>
                                            </label>
                                            <div class="premium-input-group">
                                                <span class="premium-input-icon">
                                                    <i data-lucide="user" style="width: 18px; height: 18px;"></i>
                                                </span>
                                                <input type="text" id="name" name="name" 
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name') }}" 
                                                    placeholder="e.g. John Doe" 
                                                    required autofocus>
                                            </div>
                                            @error('name')
                                                <div class="text-danger mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                                            @enderror
                                            <div class="premium-input-help">
                                                <i data-lucide="info" style="width: 13px; height: 13px;"></i>
                                                <span>Official display name across records and certificates.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Username -->
                                    <div class="col-md-6 col-12">
                                        <div class="premium-form-group">
                                            <label class="premium-form-label" for="username">
                                                <span>Username <span class="label-req">*</span></span>
                                                <span class="premium-form-label-meta">Unique handle</span>
                                            </label>
                                            <div class="premium-input-group">
                                                <span class="premium-input-icon">
                                                    <i data-lucide="at-sign" style="width: 18px; height: 18px;"></i>
                                                </span>
                                                <input type="text" id="username" name="username" 
                                                    class="form-control @error('username') is-invalid @enderror"
                                                    value="{{ old('username') }}" 
                                                    placeholder="e.g. johndoe" 
                                                    autocomplete="off" required>
                                            </div>
                                            @error('username')
                                                <div class="text-danger mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                                            @enderror
                                            
                                            <!-- Interactive Suggestion Pill -->
                                            <div class="username-suggest-wrapper" id="usernameSuggestContainer">
                                                <span style="font-size: 0.75rem; color: var(--text-muted, #7A8499);">Suggested:</span>
                                                <span class="username-suggest-badge" id="suggest_username_badge" title="Click to use this username">
                                                    <span id="suggested_text"></span>
                                                    <i data-lucide="check" style="width: 12px; height: 12px;"></i>
                                                </span>
                                                <button type="button" class="username-refresh-btn" id="refreshSuggestBtn" title="Generate another username variation">
                                                    <i data-lucide="rotate-cw" style="width: 13px; height: 13px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email Address -->
                                    <div class="col-12">
                                        <div class="premium-form-group">
                                            <label class="premium-form-label" for="email">
                                                <span>Email Address <span class="label-req">*</span></span>
                                                <span class="premium-form-label-meta">For notifications & login</span>
                                            </label>
                                            <div class="premium-input-group">
                                                <span class="premium-input-icon">
                                                    <i data-lucide="mail" style="width: 18px; height: 18px;"></i>
                                                </span>
                                                <input type="email" id="email" name="email" 
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email') }}" 
                                                    placeholder="name@aspirelearners.co.uk" 
                                                    required>
                                            </div>
                                            @error('email')
                                                <div class="text-danger mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                                            @enderror
                                            <div class="premium-input-help">
                                                <i data-lucide="info" style="width: 13px; height: 13px;"></i>
                                                <span>System alerts and verification emails will be dispatched to this address.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-12">
                                        <div class="premium-form-group mb-0">
                                            <div class="premium-form-label">
                                                <span>Account Password <span class="label-req">*</span></span>
                                                <button type="button" class="btn-generate-pw" id="generatePasswordBtn">
                                                    <i data-lucide="key-round" style="width: 13px; height: 13px;"></i>
                                                    <span id="genBtnText">Generate Strong Password</span>
                                                </button>
                                            </div>
                                            <div class="premium-input-group">
                                                <span class="premium-input-icon">
                                                    <i data-lucide="lock" style="width: 18px; height: 18px;"></i>
                                                </span>
                                                <input type="password" id="password" name="password" 
                                                    class="form-control has-toggle @error('password') is-invalid @enderror"
                                                    placeholder="Enter at least 8 characters..." 
                                                    autocomplete="new-password" required>
                                                <button type="button" class="premium-toggle-pw" id="togglePasswordBtn" aria-label="Toggle password visibility">
                                                    <i data-lucide="eye" id="togglePasswordIcon" style="width: 18px; height: 18px;"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="text-danger mt-1" style="font-size: 0.775rem;">{{ $message }}</div>
                                            @enderror

                                            <!-- Password Strength Indicator Box -->
                                            <div class="pw-strength-box" id="pwStrengthBox">
                                                <div class="pw-strength-header">
                                                    <span style="color: var(--text-muted, #7A8499);">Security Strength:</span>
                                                    <span id="pwStrengthLabel" style="color: #94A3B8;">Enter a password</span>
                                                </div>
                                                <div class="pw-strength-bars">
                                                    <div class="pw-strength-bar" id="bar1"></div>
                                                    <div class="pw-strength-bar" id="bar2"></div>
                                                    <div class="pw-strength-bar" id="bar3"></div>
                                                    <div class="pw-strength-bar" id="bar4"></div>
                                                </div>
                                                <div class="pw-rules-grid">
                                                    <span class="pw-rule-item" id="ruleLen">
                                                        <i data-lucide="circle-dot"></i> 8+ Characters
                                                    </span>
                                                    <span class="pw-rule-item" id="ruleCase">
                                                        <i data-lucide="circle-dot"></i> Uppercase & Lower
                                                    </span>
                                                    <span class="pw-rule-item" id="ruleNum">
                                                        <i data-lucide="circle-dot"></i> At least 1 number
                                                    </span>
                                                    <span class="pw-rule-item" id="ruleSym">
                                                        <i data-lucide="circle-dot"></i> Special character
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- CARD 2: ROLE & PERMISSION SELECTION -->
                        <div class="premium-form-card">
                            <div class="premium-card-header">
                                <div class="premium-card-header-left">
                                    <div class="premium-header-icon role-icon-box">
                                        <i data-lucide="shield-check" style="width: 22px; height: 22px;"></i>
                                    </div>
                                    <div>
                                        <h2 class="premium-card-title">Role & Platform Permissions</h2>
                                        <p class="premium-card-subtitle">Choose the authorization scope and portal experience</p>
                                    </div>
                                </div>
                                <span class="premium-badge-required">
                                    <i data-lucide="layers" style="width: 13px; height: 13px;"></i> Select One
                                </span>
                            </div>

                            <div class="premium-card-body">
                                
                                {{-- Hidden Native Select for Seamless Form Compatibility --}}
                                <select name="role" id="role" class="d-none" required>
                                    <option value="student" {{ old('role', 'student') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="parent" {{ old('role', 'parent') == 'parent' ? 'selected' : '' }}>Parent</option>
                                    <option value="tutor" {{ old('role', 'tutor') == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>

                                <!-- Visual Interactive Role Grid -->
                                <div class="role-grid">
                                    
                                    <!-- Role 1: Student -->
                                    <div class="role-card role-student {{ old('role', 'student') == 'student' ? 'active active-student' : '' }}" 
                                        data-role="student">
                                        <div class="role-card-top">
                                            <div class="role-icon-wrapper">
                                                <i data-lucide="graduation-cap" style="width: 22px; height: 22px;"></i>
                                            </div>
                                            <div class="role-radio-check">
                                                <i data-lucide="check" style="width: 14px; height: 14px;"></i>
                                            </div>
                                        </div>
                                        <div class="role-card-content">
                                            <div class="role-title-row">
                                                <h3 class="role-name">Student</h3>
                                                <span class="role-badge">Learner</span>
                                            </div>
                                            <p class="role-desc">Enrolled student access for interactive lessons, tests, homework submissions, and results tracking.</p>
                                        </div>
                                    </div>

                                    <!-- Role 2: Parent -->
                                    <div class="role-card role-parent {{ old('role') == 'parent' ? 'active active-parent' : '' }}" 
                                        data-role="parent">
                                        <div class="role-card-top">
                                            <div class="role-icon-wrapper">
                                                <i data-lucide="users" style="width: 22px; height: 22px;"></i>
                                            </div>
                                            <div class="role-radio-check">
                                                <i data-lucide="check" style="width: 14px; height: 14px;"></i>
                                            </div>
                                        </div>
                                        <div class="role-card-content">
                                            <div class="role-title-row">
                                                <h3 class="role-name">Parent</h3>
                                                <span class="role-badge">Guardian</span>
                                            </div>
                                            <p class="role-desc">Guardian portal access to monitor enrolled children's attendance, test scores, homework, and reports.</p>
                                        </div>
                                    </div>

                                    <!-- Role 3: Tutor -->
                                    <div class="role-card role-tutor {{ old('role') == 'tutor' ? 'active active-tutor' : '' }}" 
                                        data-role="tutor">
                                        <div class="role-card-top">
                                            <div class="role-icon-wrapper">
                                                <i data-lucide="book-open" style="width: 22px; height: 22px;"></i>
                                            </div>
                                            <div class="role-radio-check">
                                                <i data-lucide="check" style="width: 14px; height: 14px;"></i>
                                            </div>
                                        </div>
                                        <div class="role-card-content">
                                            <div class="role-title-row">
                                                <h3 class="role-name">Tutor</h3>
                                                <span class="role-badge">Instructor</span>
                                            </div>
                                            <p class="role-desc">Instructor access to manage assigned classes, grade homework submissions, and monitor student metrics.</p>
                                        </div>
                                    </div>

                                    <!-- Role 4: Admin -->
                                    <div class="role-card role-admin {{ old('role') == 'admin' ? 'active active-admin' : '' }}" 
                                        data-role="admin">
                                        <div class="role-card-top">
                                            <div class="role-icon-wrapper">
                                                <i data-lucide="shield-alert" style="width: 22px; height: 22px;"></i>
                                            </div>
                                            <div class="role-radio-check">
                                                <i data-lucide="check" style="width: 14px; height: 14px;"></i>
                                            </div>
                                        </div>
                                        <div class="role-card-content">
                                            <div class="role-title-row">
                                                <h3 class="role-name">Administrator</h3>
                                                <span class="role-badge">Full Control</span>
                                            </div>
                                            <p class="role-desc">Unrestricted platform administration, user directory management, courses, finances, and system settings.</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ACTIONS FOOTER -->
                        <div class="form-actions-card">
                            <div class="d-flex align-items-center gap-2">
                                <button type="submit" class="btn-create-submit" id="submitBtn">
                                    <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                                    <span>Create User Account</span>
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn-create-cancel">
                                    <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                                    <span>Cancel</span>
                                </a>
                            </div>
                            <div>
                                <button type="reset" class="btn-create-reset" id="resetBtn">
                                    <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i>
                                    <span>Reset to Defaults</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- ==================== RIGHT COLUMN: LIVE PREVIEW & META ==================== --}}
                    <div class="col-xl-4 col-lg-4 col-12 mt-2 mt-lg-0">
                        <div class="preview-sticky-wrap">
                            
                            <!-- LIVE ACCOUNT PREVIEW CARD -->
                            <div class="live-preview-card">
                                <div class="preview-cover-banner">
                                    <div class="preview-live-badge">
                                        <span class="preview-live-dot"></span>
                                        <span>LIVE PREVIEW</span>
                                    </div>
                                </div>
                                <div class="preview-body">
                                    <div class="preview-avatar-row">
                                        <div class="preview-avatar role-student" id="previewAvatar">
                                            <span id="previewInitials">JD</span>
                                        </div>
                                        <span class="preview-status-pill">
                                            <i data-lucide="check-circle" style="width: 13px; height: 13px;"></i>
                                            <span>Active Status</span>
                                        </span>
                                    </div>

                                    <h3 class="preview-user-name" id="previewName">Full Name</h3>
                                    <div class="preview-username-handle">
                                        <span>@</span><span id="previewUsername">username</span>
                                    </div>

                                    <div class="preview-info-list">
                                        <div class="preview-info-item">
                                            <span class="preview-info-label">
                                                <i data-lucide="mail" style="width: 14px; height: 14px;"></i> Email
                                            </span>
                                            <span class="preview-info-val" id="previewEmail">name@domain.com</span>
                                        </div>
                                        <div class="preview-info-item">
                                            <span class="preview-info-label">
                                                <i data-lucide="shield" style="width: 14px; height: 14px;"></i> Role Assigned
                                            </span>
                                            <span class="preview-role-tag role-student" id="previewRoleBadge">Student</span>
                                        </div>
                                        <div class="preview-info-item">
                                            <span class="preview-info-label">
                                                <i data-lucide="lock" style="width: 14px; height: 14px;"></i> Password Status
                                            </span>
                                            <span class="preview-info-val text-muted" id="previewPwStatus">Not Set</span>
                                        </div>
                                    </div>

                                    <div class="preview-meta-footer">
                                        <span>Created: <strong>Today</strong></span>
                                        <span class="text-success"><i data-lucide="zap" style="width: 12px; height: 12px; display: inline;"></i> Instant Login</span>
                                    </div>
                                </div>
                            </div>

                            <!-- ROLE CAPABILITIES MATRIX -->
                            <div class="permissions-info-card">
                                <div class="permissions-info-title">
                                    <i data-lucide="check-circle-2" style="width: 17px; height: 17px; color: var(--theme-primary-bright, #2477F9);"></i>
                                    <span id="permissionsTitle">Student Access Highlights</span>
                                </div>
                                <ul class="permissions-checklist" id="permissionsList">
                                    <li><i data-lucide="check"></i> <span>Access interactive video lessons & learning notes</span></li>
                                    <li><i data-lucide="check"></i> <span>Take practice quizzes, exams, and diagnostic tests</span></li>
                                    <li><i data-lucide="check"></i> <span>Upload homework assignments for tutor evaluation</span></li>
                                    <li><i data-lucide="check"></i> <span>View personal progress reports & certificates</span></li>
                                </ul>
                            </div>

                            <!-- SECURITY NOTICE -->
                            <div class="permissions-info-card" style="background: rgba(36, 119, 249, 0.04); border-color: rgba(36, 119, 249, 0.2);">
                                <div class="permissions-info-title" style="color: var(--theme-primary-bright, #2477F9);">
                                    <i data-lucide="info" style="width: 16px; height: 16px;"></i>
                                    <span>Administrator Notice</span>
                                </div>
                                <p style="font-size: 0.775rem; color: var(--text-muted, #7A8499); line-height: 1.4; margin: 0;">
                                    User passwords are encrypted via Bcrypt with 12 hashing rounds. Roles and permissions can be audited or revised at any time in the Users management panel.
                                </p>
                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Lucide initialization
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Elements
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const roleSelect = document.getElementById('role');
    const roleCards = document.querySelectorAll('.role-card');

    // Username Suggestion
    const suggestContainer = document.getElementById('usernameSuggestContainer');
    const suggestBadge = document.getElementById('suggest_username_badge');
    const suggestedText = document.getElementById('suggested_text');
    const refreshSuggestBtn = document.getElementById('refreshSuggestBtn');

    // Password Elements
    const generatePwBtn = document.getElementById('generatePasswordBtn');
    const genBtnText = document.getElementById('genBtnText');
    const togglePwBtn = document.getElementById('togglePasswordBtn');
    const togglePwIcon = document.getElementById('togglePasswordIcon');
    const pwStrengthLabel = document.getElementById('pwStrengthLabel');
    const bars = [
        document.getElementById('bar1'),
        document.getElementById('bar2'),
        document.getElementById('bar3'),
        document.getElementById('bar4')
    ];
    const ruleLen = document.getElementById('ruleLen');
    const ruleCase = document.getElementById('ruleCase');
    const ruleNum = document.getElementById('ruleNum');
    const ruleSym = document.getElementById('ruleSym');

    // Live Preview Elements
    const previewName = document.getElementById('previewName');
    const previewUsername = document.getElementById('previewUsername');
    const previewEmail = document.getElementById('previewEmail');
    const previewAvatar = document.getElementById('previewAvatar');
    const previewInitials = document.getElementById('previewInitials');
    const previewRoleBadge = document.getElementById('previewRoleBadge');
    const previewPwStatus = document.getElementById('previewPwStatus');
    const permissionsTitle = document.getElementById('permissionsTitle');
    const permissionsList = document.getElementById('permissionsList');

    // Role definitions for dynamic preview & permissions list
    const roleConfig = {
        student: {
            title: 'Student Access Highlights',
            badgeClass: 'role-student',
            avatarClass: 'role-student',
            items: [
                'Access interactive video lessons & learning notes',
                'Take practice quizzes, exams, and diagnostic tests',
                'Upload homework assignments for tutor evaluation',
                'View personal progress reports & certificates'
            ]
        },
        parent: {
            title: 'Parent Portal Highlights',
            badgeClass: 'role-parent',
            avatarClass: 'role-parent',
            items: [
                'Link and monitor enrolled children records',
                'Real-time attendance & live lesson participation stats',
                'View graded quizzes, homework feedback, and term reports',
                'Direct messaging with tutors and academy staff'
            ]
        },
        tutor: {
            title: 'Tutor Capabilities Highlights',
            badgeClass: 'role-tutor',
            avatarClass: 'role-tutor',
            items: [
                'Manage assigned student classes and curriculum schedules',
                'Create and publish quizzes, homework, and assignments',
                'Grade student papers and provide tailored evaluations',
                'Track class attendance and individual student growth'
            ]
        },
        admin: {
            title: 'Administrator Full Access',
            badgeClass: 'role-admin',
            avatarClass: 'role-admin',
            items: [
                'Complete directory management of students, parents, tutors & admins',
                'Manage academic years, courses, topics, and question banks',
                'Comprehensive analytics, cohort progress & billing reports',
                'System settings, security configurations, backups & audit logs'
            ]
        }
    };

    // Helper: Compute Initials
    function getInitials(name) {
        if (!name || !name.trim()) return 'JD';
        const parts = name.trim().split(/\s+/);
        if (parts.length === 1) {
            return parts[0].substring(0, 2).toUpperCase();
        }
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    }

    // Helper: Generate clean username suggestion
    function generateSuggestedUsername() {
        if (!nameInput.value.trim()) {
            suggestContainer.style.display = 'none';
            return;
        }
        const clean = nameInput.value.toLowerCase().replace(/[^a-z0-9]/g, '');
        if (clean.length > 0) {
            const randomSuffix = Math.floor(10 + Math.random() * 90);
            const candidate = clean + randomSuffix;
            suggestedText.textContent = candidate;
            suggestContainer.style.display = 'inline-flex';
        } else {
            suggestContainer.style.display = 'none';
        }
    }

    // Input Event: Name Input
    nameInput.addEventListener('input', function () {
        const val = this.value.trim();
        previewName.textContent = val || 'Full Name';
        previewInitials.textContent = getInitials(val);
        generateSuggestedUsername();
    });

    // Username Suggestion Actions
    suggestBadge.addEventListener('click', function (e) {
        e.preventDefault();
        const text = suggestedText.textContent || this.innerText;
        if (text) {
            usernameInput.value = text.trim();
            usernameInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });

    refreshSuggestBtn.addEventListener('click', function (e) {
        e.preventDefault();
        generateSuggestedUsername();
    });

    // Input Event: Username Input
    usernameInput.addEventListener('input', function () {
        // Sanitize spaces or special characters on the fly
        const sanitized = this.value.toLowerCase().replace(/[^a-z0-9_.-]/g, '');
        if (this.value !== sanitized) {
            this.value = sanitized;
        }
        previewUsername.textContent = this.value || 'username';
    });

    // Input Event: Email Input
    emailInput.addEventListener('input', function () {
        previewEmail.textContent = this.value.trim() || 'name@domain.com';
    });

    // Password Show / Hide
    togglePwBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        
        // Update Icon
        if (isPassword) {
            togglePwIcon.setAttribute('data-lucide', 'eye-off');
        } else {
            togglePwIcon.setAttribute('data-lucide', 'eye');
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });

    // Strong Password Generator
    generatePwBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        const lowercase = 'abcdefghijkmnopqrstuvwxyz';
        const numbers = '23456789';
        const symbols = '!@#$%^&*';
        
        let pw = '';
        pw += uppercase[Math.floor(Math.random() * uppercase.length)];
        pw += lowercase[Math.floor(Math.random() * lowercase.length)];
        pw += numbers[Math.floor(Math.random() * numbers.length)];
        pw += symbols[Math.floor(Math.random() * symbols.length)];
        
        const allChars = uppercase + lowercase + numbers + symbols;
        for (let i = 0; i < 8; i++) {
            pw += allChars[Math.floor(Math.random() * allChars.length)];
        }
        
        pw = pw.split('').sort(() => 0.5 - Math.random()).join('');
        
        passwordInput.value = pw;
        passwordInput.setAttribute('type', 'text');
        togglePwIcon.setAttribute('data-lucide', 'eye-off');
        if (typeof lucide !== 'undefined') lucide.createIcons();
        
        passwordInput.dispatchEvent(new Event('input', { bubbles: true }));

        // Button feedback
        const originalText = genBtnText.textContent;
        genBtnText.textContent = 'Password Generated!';
        generatePwBtn.style.borderColor = '#10B981';
        generatePwBtn.style.color = '#10B981';
        setTimeout(() => {
            genBtnText.textContent = originalText;
            generatePwBtn.style.borderColor = '';
            generatePwBtn.style.color = '';
        }, 1800);
    });

    // Password Strength Evaluator
    passwordInput.addEventListener('input', function () {
        const val = this.value;
        
        if (!val) {
            previewPwStatus.textContent = 'Not Set';
            previewPwStatus.className = 'preview-info-val text-muted';
            pwStrengthLabel.textContent = 'Enter a password';
            pwStrengthLabel.style.color = '#94A3B8';
            bars.forEach(b => b.style.backgroundColor = '#E2E8F0');
            [ruleLen, ruleCase, ruleNum, ruleSym].forEach(r => {
                r.classList.remove('valid');
                const icon = r.querySelector('svg');
                if (icon) icon.setAttribute('data-lucide', 'circle-dot');
            });
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        previewPwStatus.textContent = 'Configured (' + val.length + ' chars)';
        previewPwStatus.className = 'preview-info-val text-success';

        const hasLen = val.length >= 8;
        const hasCase = /[A-Z]/.test(val) && /[a-z]/.test(val);
        const hasNum = /[0-9]/.test(val);
        const hasSym = /[^A-Za-z0-9]/.test(val);

        function updateRule(element, isValid) {
            if (isValid) {
                element.classList.add('valid');
                element.innerHTML = '<i data-lucide="check-circle" style="width: 13px; height: 13px;"></i> ' + element.textContent.trim();
            } else {
                element.classList.remove('valid');
                element.innerHTML = '<i data-lucide="circle-dot" style="width: 13px; height: 13px;"></i> ' + element.textContent.trim();
            }
        }

        updateRule(ruleLen, hasLen);
        updateRule(ruleCase, hasCase);
        updateRule(ruleNum, hasNum);
        updateRule(ruleSym, hasSym);
        if (typeof lucide !== 'undefined') lucide.createIcons();

        let score = 0;
        if (val.length >= 6) score++;
        if (hasLen) score++;
        if (hasCase) score++;
        if (hasNum && hasSym) score++;

        bars.forEach(b => b.style.backgroundColor = '#E2E8F0');

        if (score <= 1) {
            bars[0].style.backgroundColor = '#FA2833';
            pwStrengthLabel.textContent = 'Weak';
            pwStrengthLabel.style.color = '#FA2833';
        } else if (score === 2) {
            bars[0].style.backgroundColor = '#F59E0B';
            bars[1].style.backgroundColor = '#F59E0B';
            pwStrengthLabel.textContent = 'Fair';
            pwStrengthLabel.style.color = '#F59E0B';
        } else if (score === 3) {
            bars[0].style.backgroundColor = '#2477F9';
            bars[1].style.backgroundColor = '#2477F9';
            bars[2].style.backgroundColor = '#2477F9';
            pwStrengthLabel.textContent = 'Good';
            pwStrengthLabel.style.color = '#2477F9';
        } else {
            bars.forEach(b => b.style.backgroundColor = '#10B981');
            pwStrengthLabel.textContent = 'Strong & Secure';
            pwStrengthLabel.style.color = '#10B981';
        }
    });

    // Role Selection Handling
    function applyRole(roleKey) {
        roleSelect.value = roleKey;

        // Update cards styling
        roleCards.forEach(card => {
            const cardRole = card.getAttribute('data-role');
            card.classList.remove('active', 'active-student', 'active-parent', 'active-tutor', 'active-admin');
            if (cardRole === roleKey) {
                card.classList.add('active', 'active-' + roleKey);
            }
        });

        // Update Right Column Preview & Permissions
        const conf = roleConfig[roleKey] || roleConfig.student;
        
        // Avatar classes
        previewAvatar.className = 'preview-avatar ' + conf.avatarClass;
        
        // Role Badge in Preview
        previewRoleBadge.textContent = roleKey.toUpperCase();
        previewRoleBadge.className = 'preview-role-tag ' + conf.badgeClass;

        // Permissions Matrix
        permissionsTitle.textContent = conf.title;
        permissionsList.innerHTML = conf.items.map(it => `
            <li><i data-lucide="check"></i> <span>${it}</span></li>
        `).join('');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Role Card Clicks
    roleCards.forEach(card => {
        card.addEventListener('click', function () {
            const role = this.getAttribute('data-role');
            applyRole(role);
        });
    });

    // Reset Form Button
    const resetBtn = document.getElementById('resetBtn');
    resetBtn.addEventListener('click', function () {
        setTimeout(function () {
            applyRole('student');
            previewName.textContent = 'Full Name';
            previewUsername.textContent = 'username';
            previewEmail.textContent = 'name@domain.com';
            previewInitials.textContent = 'JD';
            previewPwStatus.textContent = 'Not Set';
            suggestContainer.style.display = 'none';
            if (passwordInput) passwordInput.dispatchEvent(new Event('input'));
        }, 50);
    });

    // Trigger initial values from old input or default
    if (nameInput.value) nameInput.dispatchEvent(new Event('input'));
    if (usernameInput.value) usernameInput.dispatchEvent(new Event('input'));
    if (emailInput.value) emailInput.dispatchEvent(new Event('input'));
    if (passwordInput.value) passwordInput.dispatchEvent(new Event('input'));
    
    const initialRole = roleSelect.value || 'student';
    applyRole(initialRole);
});
</script>
@endpush
