@php
    $theme = session('theme', 'light');
    $user = auth()->user();
@endphp

<!-- Load Lucide Icons CDN -->
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Immediate Inline Script to Prevent FOUC (Flash of Unstyled Content) -->
<script>
    (function () {
        var collapsed = localStorage.getItem('admin-sidebar-collapsed') === 'true';
        if (collapsed && window.innerWidth >= 1024) {
            document.body.classList.add('sidebar-collapsed');
        } else {
            document.body.classList.remove('sidebar-collapsed');
        }
    })();
</script>

<style>
    /* ==========================================================================
       MODERN PREMIUM SIDEBAR DESIGN SYSTEM
       ========================================================================== */
    :root {
        --sb-accent: #7367f0;
        --sb-accent-hover: #5e50eb;
        --sb-accent-glow: rgba(115, 103, 240, 0.15);

        --sidebar-w-expanded: 280px;
        --sidebar-w-collapsed: 80px;
        --sidebar-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

        /* Light Theme Tokens */
        --sb-bg-light: #ffffff;
        --sb-border-light: #eef0f3;
        --sb-text-light: #1e293b;
        --sb-muted-light: #64748b;
        --sb-hover-light: #f8fafc;
        --sb-active-light: #f0efff;
        --sb-shadow-light: 0 4px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.05);
        --sb-tooltip-bg-light: #0f172a;
        --sb-tooltip-text-light: #f8fafc;

        /* Dark Theme Tokens */
        --sb-bg-dark: #0f172a;
        --sb-border-dark: #1e293b;
        --sb-text-dark: #f8fafc;
        --sb-muted-dark: #94a3b8;
        --sb-hover-dark: rgba(255, 255, 255, 0.05);
        --sb-active-dark: rgba(115, 103, 240, 0.18);
        --sb-shadow-dark: 0 4px 30px rgba(0, 0, 0, 0.3), 0 1px 3px rgba(0, 0, 0, 0.1);
        --sb-tooltip-bg-dark: #f8fafc;
        --sb-tooltip-text-dark: #0f172a;
    }

    /* Assign Variable Mapping Based on Vuexy Classes */
    .main-menu.modern-sidebar {
        --sb-bg: var(--sb-bg-light);
        --sb-border: var(--sb-border-light);
        --sb-text: var(--sb-text-light);
        --sb-muted: var(--sb-muted-light);
        --sb-hover: var(--sb-hover-light);
        --sb-active: var(--sb-active-light);
        --sb-shadow: var(--sb-shadow-light);
        --sb-tooltip-bg: var(--sb-tooltip-bg-light);
        --sb-tooltip-text: var(--sb-tooltip-text-light);
    }

    body.dark-layout .main-menu.modern-sidebar {
        --sb-bg: var(--sb-bg-dark);
        --sb-border: var(--sb-border-dark);
        --sb-text: var(--sb-text-dark);
        --sb-muted: var(--sb-muted-dark);
        --sb-hover: var(--sb-hover-dark);
        --sb-active: var(--sb-active-dark);
        --sb-shadow: var(--sb-shadow-dark);
        --sb-tooltip-bg: var(--sb-tooltip-bg-dark);
        --sb-tooltip-text: var(--sb-tooltip-text-dark);
    }

    body.semi-dark-layout .main-menu.modern-sidebar {
        --sb-bg: #111827;
        --sb-border: rgba(255, 255, 255, 0.07);
        --sb-text: #f9fafb;
        --sb-muted: #9ca3af;
        --sb-hover: rgba(255, 255, 255, 0.05);
        --sb-active: rgba(115, 103, 240, 0.2);
        --sb-shadow: var(--sb-shadow-dark);
        --sb-tooltip-bg: #ffffff;
        --sb-tooltip-text: #111827;
    }

    /* Desktop Layout Padding Adjustments */
    @media (min-width: 1024px) {

        body.vertical-layout.vertical-menu-modern:not(.sidebar-collapsed) .app-content,
        body.vertical-layout.vertical-menu-modern:not(.sidebar-collapsed) .header-navbar {
            margin-left: var(--sidebar-w-expanded) !important;
            transition: var(--sidebar-transition) !important;
        }

        body.vertical-layout.vertical-menu-modern:not(.sidebar-collapsed) .header-navbar.floating-nav {
            width: calc(100vw - (100vw - 100%) - 4.4rem - var(--sidebar-w-expanded)) !important;
            transition: var(--sidebar-transition) !important;
            left: 0 !important;
        }

        body.vertical-layout.vertical-menu-modern.sidebar-collapsed .app-content,
        body.vertical-layout.vertical-menu-modern.sidebar-collapsed .header-navbar {
            margin-left: var(--sidebar-w-collapsed) !important;
            transition: var(--sidebar-transition) !important;
        }

        body.vertical-layout.vertical-menu-modern.sidebar-collapsed .header-navbar.floating-nav {
            width: calc(100vw - (100vw - 100%) - 4.4rem - var(--sidebar-w-collapsed)) !important;
            transition: var(--sidebar-transition) !important;
            left: 0 !important;
        }

        /* Hide original toggle buttons in layout to avoid overlaps */
        .modern-nav-toggle {
            display: none !important;
        }
    }

    /* Base Sidebar Wrapper Styles */
    .main-menu.modern-sidebar {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        height: 100vh !important;
        width: var(--sidebar-w-expanded) !important;
        background-color: var(--sb-bg) !important;
        border-right: 1px solid var(--sb-border) !important;
        box-shadow: var(--sb-shadow) !important;
        transition: var(--sidebar-transition) !important;
        display: flex !important;
        flex-direction: column !important;
        z-index: 1030 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: visible !important;
    }

    body.sidebar-collapsed .main-menu.modern-sidebar {
        width: var(--sidebar-w-collapsed) !important;
    }

    /* Header Section (Logo + Collapse Button) */
    .modern-sidebar .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 70px;
        padding: 0 20px;
        border-bottom: 1px solid var(--sb-border);
        transition: var(--sidebar-transition);
        position: relative;
    }

    .modern-sidebar .sidebar-logo-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }

    .modern-sidebar .logo-full {
        max-width: 160px;
        height: auto;
        object-fit: contain;
        transition: opacity 0.2s ease, transform 0.2s ease;
        opacity: 1;
    }

    .modern-sidebar .logo-icon {
        display: none;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--sb-accent), var(--sb-accent-hover));
        color: #ffffff;
        font-weight: 800;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(115, 103, 240, 0.3);
        transition: transform 0.2s ease;
    }

    body.sidebar-collapsed .modern-sidebar .logo-full {
        opacity: 0;
        pointer-events: none;
        position: absolute;
        width: 0;
    }

    body.sidebar-collapsed .modern-sidebar .logo-icon {
        display: flex;
    }

    /* Sidebar Toggle Button */
    .modern-sidebar .sidebar-toggle-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid var(--sb-border);
        background-color: var(--sb-bg);
        color: var(--sb-muted);
        cursor: pointer;
        transition: all 0.2s ease;
        outline: none;
        position: absolute;
        right: -14px;
        top: calc(50% - 14px);
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .modern-sidebar .sidebar-toggle-btn:hover {
        background-color: var(--sb-hover);
        color: var(--sb-text);
        border-color: var(--sb-accent);
        transform: scale(1.05);
    }

    .modern-sidebar .sidebar-toggle-btn:focus-visible {
        box-shadow: 0 0 0 2px var(--sb-accent-glow);
    }

    .modern-sidebar .toggle-icon-arrow {
        width: 16px;
        height: 16px;
        transition: transform 0.3s ease;
    }

    body.sidebar-collapsed .modern-sidebar .toggle-icon-arrow {
        transform: rotate(180deg);
    }

    /* Search Section */
    .modern-sidebar .sidebar-search-wrapper {
        padding: 16px;
        border-bottom: 1px solid var(--sb-border);
        transition: var(--sidebar-transition);
    }

    .modern-sidebar .sidebar-search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        background-color: var(--sb-hover);
        border: 1px solid var(--sb-border);
        border-radius: 12px;
        transition: all 0.2s ease;
        position: relative;
        cursor: text;
    }

    .modern-sidebar .sidebar-search-box:hover {
        border-color: rgba(115, 103, 240, 0.4);
    }

    .modern-sidebar .sidebar-search-box.focused {
        background-color: var(--sb-bg);
        border-color: var(--sb-accent);
        box-shadow: 0 0 0 3px var(--sb-accent-glow);
    }

    .modern-sidebar .search-icon {
        width: 16px;
        height: 16px;
        color: var(--sb-muted);
        flex-shrink: 0;
    }

    .modern-sidebar .search-input {
        border: none;
        background: transparent;
        color: var(--sb-text);
        font-size: 0.9rem;
        outline: none;
        width: 100%;
        padding: 0;
    }

    .modern-sidebar .search-input::placeholder {
        color: var(--sb-muted);
        opacity: 0.8;
    }

    .modern-sidebar .search-shortcut {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--sb-muted);
        padding: 2px 6px;
        background: var(--sb-bg);
        border: 1px solid var(--sb-border);
        border-radius: 6px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
    }

    body.sidebar-collapsed .modern-sidebar .sidebar-search-wrapper {
        padding: 16px 12px;
    }

    body.sidebar-collapsed .modern-sidebar .sidebar-search-box {
        justify-content: center;
        padding: 10px;
    }

    body.sidebar-collapsed .modern-sidebar .search-input,
    body.sidebar-collapsed .modern-sidebar .search-shortcut {
        display: none;
    }

    /* Scrollable Menu Area */
    .modern-sidebar .sidebar-menu-content {
        flex: 1;
        overflow-y: auto;
        padding: 16px 12px;
    }

    /* Custom Premium Scrollbar styling */
    .modern-sidebar .sidebar-menu-content::-webkit-scrollbar {
        width: 5px;
    }

    .modern-sidebar .sidebar-menu-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .modern-sidebar .sidebar-menu-content::-webkit-scrollbar-thumb {
        background: var(--sb-border);
        border-radius: 10px;
    }

    .modern-sidebar .sidebar-menu-content::-webkit-scrollbar-thumb:hover {
        background: var(--sb-muted);
    }

    /* Menu Sections */
    .modern-sidebar .menu-section {
        margin-bottom: 24px;
    }

    .modern-sidebar .menu-section-header {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--sb-muted);
        letter-spacing: 0.06em;
        margin: 0 8px 8px 8px;
        display: flex;
        align-items: center;
        transition: var(--sidebar-transition);
    }

    body.sidebar-collapsed .modern-sidebar .menu-section-header {
        opacity: 0;
        height: 0;
        margin: 0;
        overflow: hidden;
    }

    .modern-sidebar .menu-items {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* Menu Item Wrappers */
    .modern-sidebar .menu-item-wrapper {
        position: relative;
        border-radius: 12px;
        transition: background-color 0.2s ease;
    }

    .modern-sidebar .menu-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        color: var(--sb-muted);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s ease;
        position: relative;
        cursor: pointer;
        border: 1px solid transparent;
        outline: none;
    }

    .modern-sidebar .menu-link i {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        transition: transform 0.25s ease;
    }

    .modern-sidebar .menu-text {
        white-space: nowrap;
        opacity: 1;
        transition: opacity 0.15s ease;
        flex-grow: 1;
    }

    body.sidebar-collapsed .modern-sidebar .menu-text {
        opacity: 0;
        pointer-events: none;
        width: 0;
    }

    /* Hover & Active States */
    .modern-sidebar .menu-item-wrapper:not(.has-submenu) .menu-link:hover,
    .modern-sidebar .menu-item-wrapper.has-submenu .submenu-toggle:hover {
        background-color: var(--sb-hover);
        color: var(--sb-text);
    }

    .modern-sidebar .menu-item-wrapper:not(.has-submenu) .menu-link:focus-visible,
    .modern-sidebar .menu-item-wrapper.has-submenu .submenu-toggle:focus-visible {
        background-color: var(--sb-hover);
        border-color: var(--sb-accent);
        color: var(--sb-text);
        box-shadow: 0 0 0 2px var(--sb-accent-glow);
    }

    .modern-sidebar .menu-item-wrapper:not(.has-submenu).active .menu-link {
        background-color: var(--sb-active);
        color: var(--sb-accent);
        font-weight: 600;
    }

    .modern-sidebar .menu-item-wrapper:not(.has-submenu).active .menu-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        height: 60%;
        width: 3px;
        background-color: var(--sb-accent);
        border-radius: 0 4px 4px 0;
    }

    /* Star / Pin Button Styling */
    .modern-sidebar .pin-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%) scale(0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        color: var(--sb-muted);
        opacity: 0;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 4px;
        border-radius: 6px;
        outline: none;
        z-index: 5;
    }

    .modern-sidebar .pin-btn i {
        width: 14px;
        height: 14px;
    }

    .modern-sidebar .menu-item-wrapper:not(.has-submenu):hover .pin-btn,
    .modern-sidebar .submenu-items li:hover .pin-btn {
        opacity: 0.6;
    }

    .modern-sidebar .pin-btn:hover {
        opacity: 1 !important;
        color: #eab308 !important;
        /* gold star */
        background-color: var(--sb-hover);
        transform: translateY(-50%) scale(1.05);
    }

    .modern-sidebar .pin-btn.pinned {
        opacity: 1 !important;
        color: #eab308 !important;
    }

    body.sidebar-collapsed .modern-sidebar .pin-btn {
        display: none !important;
    }

    /* Submenu styling (Accordion) */
    .modern-sidebar .submenu-toggle {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .modern-sidebar .submenu-arrow {
        width: 16px !important;
        height: 16px !important;
        color: var(--sb-muted);
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    body.sidebar-collapsed .modern-sidebar .submenu-arrow {
        display: none;
    }

    .modern-sidebar .menu-item-wrapper.has-submenu.open .submenu-arrow {
        transform: rotate(180deg);
    }

    .modern-sidebar .submenu-items {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        gap: 2px;
        padding-left: 12px;
        border-left: 1px solid var(--sb-border);
        margin-left: 20px;
        margin-top: 2px;
    }

    body.sidebar-collapsed .modern-sidebar .submenu-items {
        display: none !important;
    }

    .modern-sidebar .menu-item-wrapper.has-submenu.open .submenu-items {
        max-height: 800px;
        /* High enough to contain submenu */
    }

    .modern-sidebar .submenu-items li {
        position: relative;
    }

    .modern-sidebar .submenu-items .menu-link {
        font-size: 0.9rem;
        padding: 8px 12px;
        color: var(--sb-muted);
    }

    .modern-sidebar .submenu-items .menu-link i {
        width: 14px;
        height: 14px;
    }

    .modern-sidebar .submenu-items li.active .menu-link {
        background-color: var(--sb-active);
        color: var(--sb-accent);
        font-weight: 600;
    }

    /* Tooltips for Collapsed Sidebar */
    body.sidebar-collapsed .modern-sidebar .menu-item-wrapper[data-tooltip]::after {
        content: attr(data-tooltip);
        position: absolute;
        left: calc(var(--sidebar-w-collapsed) + 6px);
        top: 50%;
        transform: translateY(-50%) scale(0.95);
        background-color: var(--sb-tooltip-bg);
        color: var(--sb-tooltip-text);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        transition: opacity 0.15s ease, transform 0.15s ease;
        z-index: 1000;
    }

    body.sidebar-collapsed .modern-sidebar .menu-item-wrapper[data-tooltip]:hover::after {
        opacity: 1;
        transform: translateY(-50%) scale(1);
    }

    /* User Profile Section at Bottom */
    .modern-sidebar .sidebar-profile-section {
        padding: 16px;
        border-top: 1px solid var(--sb-border);
        position: relative;
        transition: var(--sidebar-transition);
    }

    .modern-sidebar .profile-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px;
        border-radius: 12px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        outline: none;
        user-select: none;
        border: 1px solid transparent;
    }

    .modern-sidebar .profile-card:hover,
    .modern-sidebar .profile-card:focus-visible {
        background-color: var(--sb-hover);
    }

    .modern-sidebar .profile-card:focus-visible {
        border-color: var(--sb-accent);
        box-shadow: 0 0 0 2px var(--sb-accent-glow);
    }

    .modern-sidebar .profile-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        background-color: var(--sb-accent);
        flex-shrink: 0;
        position: relative;
    }

    .modern-sidebar .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modern-sidebar .profile-initials {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-weight: 700;
        font-size: 0.85rem;
        color: #ffffff;
    }

    .modern-sidebar .profile-info {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        overflow: hidden;
        transition: var(--sidebar-transition);
        text-align: left;
    }

    .modern-sidebar .profile-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--sb-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .modern-sidebar .profile-role {
        font-size: 0.75rem;
        color: var(--sb-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .modern-sidebar .profile-more-icon {
        width: 16px;
        height: 16px;
        color: var(--sb-muted);
        flex-shrink: 0;
        transition: var(--sidebar-transition);
    }

    body.sidebar-collapsed .modern-sidebar .sidebar-profile-section {
        padding: 16px 12px;
    }

    body.sidebar-collapsed .modern-sidebar .profile-card {
        justify-content: center;
        padding: 6px;
    }

    body.sidebar-collapsed .modern-sidebar .profile-info,
    body.sidebar-collapsed .modern-sidebar .profile-more-icon {
        display: none;
    }

    /* Profile Dropdown Popup Menu */
    .modern-sidebar .profile-dropdown {
        position: absolute;
        bottom: 80px;
        left: 16px;
        right: 16px;
        background-color: var(--sb-bg);
        border: 1px solid var(--sb-border);
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        padding: 6px;
        display: none;
        flex-direction: column;
        gap: 2px;
        z-index: 1100;
        animation: dropdownSlideUp 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: bottom center;
    }

    body.sidebar-collapsed .modern-sidebar .profile-dropdown {
        left: 70px;
        bottom: 16px;
        width: 220px;
        right: auto;
    }

    .modern-sidebar .profile-dropdown.open {
        display: flex;
    }

    .modern-sidebar .dropdown-header-info {
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .modern-sidebar .dropdown-header-info strong {
        font-size: 0.85rem;
        color: var(--sb-text);
    }

    .modern-sidebar .dropdown-header-info span {
        font-size: 0.75rem;
        color: var(--sb-muted);
        word-break: break-all;
    }

    .modern-sidebar .dropdown-divider {
        height: 1px;
        background-color: var(--sb-border);
        margin: 4px 0;
        border: none;
    }

    .modern-sidebar .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        color: var(--sb-muted);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.15s ease;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        cursor: pointer;
        outline: none;
    }

    .modern-sidebar .dropdown-item:hover,
    .modern-sidebar .dropdown-item:focus-visible {
        background-color: var(--sb-hover);
        color: var(--sb-text);
    }

    .modern-sidebar .dropdown-item:focus-visible {
        box-shadow: 0 0 0 2px var(--sb-accent-glow);
    }

    .modern-sidebar .dropdown-item i {
        width: 16px;
        height: 16px;
    }

    .modern-sidebar .dropdown-item.logout-btn {
        color: #ea5455;
    }

    .modern-sidebar .dropdown-item.logout-btn:hover {
        background-color: rgba(234, 84, 85, 0.08);
    }

    @keyframes dropdownSlideUp {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Skeleton Loading Shimmer States */
    .modern-sidebar .skeleton-container {
        padding: 4px 8px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .modern-sidebar .skeleton-item {
        display: flex;
        align-items: center;
        gap: 12px;
        height: 38px;
        border-radius: 10px;
        background-color: var(--sb-hover);
        position: relative;
        overflow: hidden;
    }

    .modern-sidebar .skeleton-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
        animation: sb-shimmer 1.5s infinite linear;
        transform: translateX(-100%);
    }

    body:not(.dark-layout) .modern-sidebar .skeleton-item::after {
        background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.03), transparent);
    }

    .modern-sidebar .skeleton-icon {
        width: 18px;
        height: 18px;
        border-radius: 6px;
        background-color: var(--sb-border);
        margin-left: 12px;
    }

    .modern-sidebar .skeleton-text {
        height: 12px;
        width: 50%;
        background-color: var(--sb-border);
        border-radius: 4px;
    }

    @keyframes sb-shimmer {
        100% {
            transform: translateX(100%);
        }
    }

    /* Empty state for search */
    .modern-sidebar .search-empty-state {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
        text-align: center;
        color: var(--sb-muted);
    }

    .modern-sidebar .search-empty-state i {
        width: 32px;
        height: 32px;
        margin-bottom: 8px;
        opacity: 0.6;
    }

    .modern-sidebar .search-empty-state p {
        font-size: 0.85rem;
        margin: 0;
    }

    /* ==========================================================================
       RESPONSIVE & ADAPTIVE STYLES
       ========================================================================== */

    /* Tablet Portrait & Landscape Layout (768px to 1023px) */
    @media (min-width: 768px) and (max-width: 1023px) {
        body.vertical-layout.vertical-menu-modern .main-menu.modern-sidebar {
            width: var(--sidebar-w-collapsed) !important;
        }

        body.vertical-layout.vertical-menu-modern .app-content,
        body.vertical-layout.vertical-menu-modern .header-navbar {
            margin-left: var(--sidebar-w-collapsed) !important;
        }

        /* Hovering or clicking collapsed sidebar items in Tablet: expand drawer overlay */
        .main-menu.modern-sidebar.tablet-expanded {
            width: var(--sidebar-w-expanded) !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
        }

        .main-menu.modern-sidebar.tablet-expanded .menu-text {
            opacity: 1;
            width: auto;
        }

        .main-menu.modern-sidebar.tablet-expanded .logo-full {
            opacity: 1;
            width: auto;
            position: static;
        }

        .main-menu.modern-sidebar.tablet-expanded .logo-icon {
            display: none;
        }

        .main-menu.modern-sidebar.tablet-expanded .sidebar-search-box .search-input {
            display: block;
        }

        .main-menu.modern-sidebar.tablet-expanded .submenu-arrow {
            display: block;
        }

        .main-menu.modern-sidebar.tablet-expanded .submenu-items {
            display: flex !important;
        }

        .main-menu.modern-sidebar.tablet-expanded .profile-info,
        .main-menu.modern-sidebar.tablet-expanded .profile-more-icon {
            display: flex;
        }

        .main-menu.modern-sidebar.tablet-expanded .pin-btn {
            display: flex !important;
        }

        .modern-sidebar .sidebar-toggle-btn {
            display: none !important;
            /* Hide toggle button on tablet */
        }
    }

    /* Mobile Layout (<768px) */
    @media (max-width: 767.98px) {

        body.vertical-layout.vertical-menu-modern .app-content,
        body.vertical-layout.vertical-menu-modern .header-navbar {
            margin-left: 0 !important;
        }

        .main-menu.modern-sidebar {
            width: var(--sidebar-w-expanded) !important;
            transform: translateX(-100%) !important;
            box-shadow: none !important;
        }

        .main-menu.modern-sidebar.mobile-open {
            transform: translateX(0) !important;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.25) !important;
        }

        .modern-sidebar .sidebar-toggle-btn {
            display: none !important;
            /* Hide toggle button on mobile */
        }

        body.sidebar-open-no-scroll {
            overflow: hidden !important;
        }
    }

    /* Overlay Backdrop Background styling */
    .sidebar-overlay-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 1025;
        display: none;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .sidebar-overlay-backdrop.visible {
        display: block;
        opacity: 1;
    }
</style>

<!-- Custom Overlay Backdrop for Drawer Behavior -->
<div class="sidebar-overlay-backdrop" id="sidebarOverlay"></div>

<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed modern-sidebar" id="adminSidebar" data-scroll-to-active="true">

    <!-- Sidebar Header (Logo and Toggle Button) -->
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo-link">
            <!-- Full Brand Logo -->
            <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Horizontal-Full-Logo.png') }}"
                alt="Aspire Learner Logo" class="logo-full">
            <!-- Icon/Favicon Initials Fallback Logo -->
            <div class="logo-icon">AL</div>
        </a>
        <!-- Sidebar Toggle Button (Hidden on Mobile/Tablet) -->
        <button type="button" class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
            <i data-lucide="chevron-left" class="toggle-icon-arrow"></i>
        </button>
    </div>

    <!-- Search Section inside Sidebar -->
    <div class="sidebar-search-wrapper">
        <div class="sidebar-search-box" id="searchBoxContainer">
            <i data-lucide="search" class="search-icon"></i>
            <input type="text" class="search-input" id="sidebarSearch" placeholder="Search pages... (/)"
                autocomplete="off" aria-label="Search sidebar items">
            <span class="search-shortcut">/</span>
        </div>
    </div>

    <!-- Scrollable Menu Content -->
    <div class="sidebar-menu-content">
        <!-- Search Empty State -->
        <div class="search-empty-state" id="searchEmptyState">
            <i data-lucide="search-code"></i>
            <p>No matches found</p>
        </div>

        <!-- Favorites / Pinned Section -->
        <div class="menu-section" id="sectionPinned">
            <div class="menu-section-header">
                <span>Pinned</span>
            </div>
            <!-- Simulated Skeleton state during initial client-side rendering -->
            <div class="skeleton-container" id="pinnedSkeleton">
                <div class="skeleton-item">
                    <div class="skeleton-icon"></div>
                    <div class="skeleton-text"></div>
                </div>
            </div>
            <ul class="menu-items" id="pinnedList" style="display: none;">
                <!-- Dynamically filled by JS -->
            </ul>
        </div>

        <!-- Recently Visited Pages Section -->
        <div class="menu-section" id="sectionRecents">
            <div class="menu-section-header">
                <span>Recently Visited</span>
            </div>
            <!-- Simulated Skeleton state -->
            <div class="skeleton-container" id="recentsSkeleton">
                <div class="skeleton-item">
                    <div class="skeleton-icon"></div>
                    <div class="skeleton-text"></div>
                </div>
            </div>
            <ul class="menu-items" id="recentsList" style="display: none;">
                <!-- Dynamically filled by JS -->
            </ul>
        </div>

        <!-- Main Navigation Directory -->
        <div class="menu-section" id="sectionMain">
            <div class="menu-section-header">
                <span>Directory</span>
            </div>
            <ul class="menu-items" id="mainMenuList">

                <!-- Dashboard -->
                <li class="menu-item-wrapper {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    data-title="Dashboard" data-route="{{ route('admin.dashboard') }}" data-icon="home"
                    data-tooltip="Dashboard">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link">
                        <i data-lucide="home"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                    <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Dashboard">
                        <i data-lucide="star"></i>
                    </button>
                </li>

                <!-- User Management (Submenu) -->
                @php
                    $userGroupActive = request()->routeIs('admin.users.*') || request()->routeIs('admin.students.*') || request()->routeIs('admin.parents.*');
                @endphp
                <li class="menu-item-wrapper has-submenu {{ $userGroupActive ? 'open' : '' }}" data-title="Users"
                    data-tooltip="Users">
                    <div class="menu-link submenu-toggle" tabindex="0" aria-haspopup="true"
                        aria-expanded="{{ $userGroupActive ? 'true' : 'false' }}">
                        <i data-lucide="users"></i>
                        <span class="menu-text">Users</span>
                        <i data-lucide="chevron-down" class="submenu-arrow"></i>
                    </div>
                    <ul class="submenu-items">
                        <li class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}"
                            data-title="Add New User" data-route="{{ route('admin.users.create') }}"
                            data-icon="user-plus">
                            <a href="{{ route('admin.users.create') }}" class="menu-link">
                                <i data-lucide="user-plus"></i>
                                <span class="menu-text">Add New User</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Add New User">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}" data-title="All Users"
                            data-route="{{ route('admin.users.index') }}" data-icon="users">
                            <a href="{{ route('admin.users.index') }}" class="menu-link">
                                <i data-lucide="users"></i>
                                <span class="menu-text">All Users</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin All Users">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.students.index') ? 'active' : '' }}"
                            data-title="Manage Students" data-route="{{ route('admin.students.index') }}"
                            data-icon="graduation-cap">
                            <a href="{{ route('admin.students.index') }}" class="menu-link">
                                <i data-lucide="graduation-cap"></i>
                                <span class="menu-text">Manage Students</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Manage Students">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.parents.index') ? 'active' : '' }}"
                            data-title="Manage Parents" data-route="{{ route('admin.parents.index') }}"
                            data-icon="heart">
                            <a href="{{ route('admin.parents.index') }}" class="menu-link">
                                <i data-lucide="heart"></i>
                                <span class="menu-text">Manage Parents</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Manage Parents">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                    </ul>
                </li>

                <!-- Question Bank (Submenu) -->
                @php
                    $qBankActive = request()->routeIs('admin.questions.*');
                @endphp
                <li class="menu-item-wrapper has-submenu {{ $qBankActive ? 'open' : '' }}" data-title="Question Bank"
                    data-tooltip="Question Bank">
                    <div class="menu-link submenu-toggle" tabindex="0" aria-haspopup="true"
                        aria-expanded="{{ $qBankActive ? 'true' : 'false' }}">
                        <i data-lucide="help-circle"></i>
                        <span class="menu-text">Question Bank</span>
                        <i data-lucide="chevron-down" class="submenu-arrow"></i>
                    </div>
                    <ul class="submenu-items">
                        <li class="{{ request()->routeIs('admin.questions.create') ? 'active' : '' }}"
                            data-title="Add New Question" data-route="{{ route('admin.questions.create') }}"
                            data-icon="plus-circle">
                            <a href="{{ route('admin.questions.create') }}" class="menu-link">
                                <i data-lucide="plus-circle"></i>
                                <span class="menu-text">Add New Question</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Add New Question">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.questions.index') ? 'active' : '' }}"
                            data-title="Questions Directory" data-route="{{ route('admin.questions.index') }}"
                            data-icon="folder-open">
                            <a href="{{ route('admin.questions.index') }}" class="menu-link">
                                <i data-lucide="folder-open"></i>
                                <span class="menu-text">Questions Directory</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Questions Directory">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.questions.import-form') ? 'active' : '' }}"
                            data-title="Import Questions" data-route="{{ route('admin.questions.import-form') }}"
                            data-icon="upload">
                            <a href="{{ route('admin.questions.import-form') }}" class="menu-link">
                                <i data-lucide="upload"></i>
                                <span class="menu-text">Import Questions</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Import Questions">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li data-title="Import Using AI" data-route="" data-icon="cpu">
                            <a href="" class="menu-link">
                                <i data-lucide="cpu"></i>
                                <span class="menu-text">Import Using AI</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Import Using AI">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                    </ul>
                </li>

                <!-- Papers Manager (Submenu) -->
                @php
                    $papersActive = request()->routeIs('admin.papers.*');
                @endphp
                <li class="menu-item-wrapper has-submenu {{ $papersActive ? 'open' : '' }}"
                    data-title="Assessment Center" data-tooltip="Assessment Center">
                    <div class="menu-link submenu-toggle" tabindex="0" aria-haspopup="true"
                        aria-expanded="{{ $papersActive ? 'true' : 'false' }}">
                        <i data-lucide="file-text"></i>
                        <span class="menu-text">Assessment Center</span>
                        <i data-lucide="chevron-down" class="submenu-arrow"></i>
                    </div>
                    <ul class="submenu-items">
                        <li class="{{ request()->routeIs('admin.papers.create') ? 'active' : '' }}"
                            data-title="Create New Assessment" data-route="{{ route('admin.papers.create') }}"
                            data-icon="file-plus">
                            <a href="{{ route('admin.papers.create') }}" class="menu-link">
                                <i data-lucide="file-plus"></i>
                                <span class="menu-text">Create New Assessment</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Create New Assessment">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.papers.index') ? 'active' : '' }}"
                            data-title="Assessment Directory" data-route="{{ route('admin.papers.index') }}"
                            data-icon="files">
                            <a href="{{ route('admin.papers.index') }}" class="menu-link">
                                <i data-lucide="files"></i>
                                <span class="menu-text">Assessment Directory</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Assessment Directory">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                    </ul>
                </li>

                <!-- Create a Report -->
                <li class="menu-item-wrapper" data-title="Create a Report" data-route="#" data-icon="bar-chart-2"
                    data-tooltip="Create a Report">
                    <a href="#" class="menu-link">
                        <i data-lucide="bar-chart-2"></i>
                        <span class="menu-text">Create a Report</span>
                    </a>
                    <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Create a Report">
                        <i data-lucide="star"></i>
                    </button>
                </li>

                <!-- Manage Files -->
                <li class="menu-item-wrapper {{ request()->routeIs('admin.media-files.*') ? 'active' : '' }}" data-title="Manage Files" data-route="{{ route('admin.media-files.index') }}" data-icon="folder"
                    data-tooltip="Manage Files">
                    <a href="{{ route('admin.media-files.index') }}" class="menu-link">
                        <i data-lucide="folder"></i>
                        <span class="menu-text">Manage Files</span>
                    </a>
                    <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Manage Files">
                        <i data-lucide="star"></i>
                    </button>
                </li>

                <!-- Invoice Creator -->
                <li class="menu-item-wrapper" data-title="Invoice Creator" data-route="#" data-icon="credit-card"
                    data-tooltip="Invoice Creator">
                    <a href="#" class="menu-link">
                        <i data-lucide="credit-card"></i>
                        <span class="menu-text">Invoice Creator</span>
                    </a>
                    <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Invoice Creator">
                        <i data-lucide="star"></i>
                    </button>
                </li>

                <!-- Cohort Report -->
                <li class="menu-item-wrapper" data-title="Cohort Report" data-route="#" data-icon="pie-chart"
                    data-tooltip="Cohort Report">
                    <a href="#" class="menu-link">
                        <i data-lucide="pie-chart"></i>
                        <span class="menu-text">Cohort Report</span>
                    </a>
                    <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Cohort Report">
                        <i data-lucide="star"></i>
                    </button>
                </li>

                <!-- Manage Announcement -->
                <li class="menu-item-wrapper {{ request()->routeIs('admin.announcements.index') ? 'active' : '' }}"
                    data-title="Manage Announcement" data-route="{{ route('admin.announcements.index') }}"
                    data-icon="megaphone" data-tooltip="Manage Announcement">
                    <a href="{{ route('admin.announcements.index') }}" class="menu-link">
                        <i data-lucide="megaphone"></i>
                        <span class="menu-text">Manage Announcement</span>
                    </a>
                    <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Manage Announcement">
                        <i data-lucide="star"></i>
                    </button>
                </li>

                <!-- Content Manager (Submenu) -->
                @php
                    $contentActive = request()->routeIs('admin.classes.*') || request()->routeIs('admin.academic-years.*') || request()->routeIs('admin.year-groups.*') || request()->routeIs('admin.subjects.*') || request()->routeIs('topics*') || request()->routeIs('add.topic*') || request()->routeIs('admin.courses.*');
                @endphp
                <li class="menu-item-wrapper has-submenu {{ $contentActive ? 'open' : '' }}"
                    data-title="Content Manager" data-tooltip="Content Manager">
                    <div class="menu-link submenu-toggle" tabindex="0" aria-haspopup="true"
                        aria-expanded="{{ $contentActive ? 'true' : 'false' }}">
                        <i data-lucide="book-open"></i>
                        <span class="menu-text">Content Manager</span>
                        <i data-lucide="chevron-down" class="submenu-arrow"></i>
                    </div>
                    <ul class="submenu-items">
                        <li class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" data-title="Courses"
                            data-route="{{ route('admin.courses.index') }}" data-icon="award">
                            <a href="{{ route('admin.courses.index') }}" class="menu-link">
                                <i data-lucide="award"></i>
                                <span class="menu-text">Courses</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Courses">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.classes.index') ? 'active' : '' }}" data-title="Classes"
                            data-route="{{ route('admin.classes.index') }}" data-icon="shapes">
                            <a href="{{ route('admin.classes.index') }}" class="menu-link">
                                <i data-lucide="shapes"></i>
                                <span class="menu-text">Classes</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Classes">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.academic-years.*') ? 'active' : '' }}"
                            data-title="Academic Years" data-route="{{ route('admin.academic-years.index') }}"
                            data-icon="calendar">
                            <a href="{{ route('admin.academic-years.index') }}" class="menu-link">
                                <i data-lucide="calendar"></i>
                                <span class="menu-text">Academic Years</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Academic Years">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.year-groups.*') ? 'active' : '' }}"
                            data-title="Year Groups" data-route="{{ route('admin.year-groups.index') }}"
                            data-icon="layers">
                            <a href="{{ route('admin.year-groups.index') }}" class="menu-link">
                                <i data-lucide="layers"></i>
                                <span class="menu-text">Year Groups</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Year Groups">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}" data-title="Subjects"
                            data-route="{{ route('admin.subjects.index') }}" data-icon="book">
                            <a href="{{ route('admin.subjects.index') }}" class="menu-link">
                                <i data-lucide="book"></i>
                                <span class="menu-text">Subjects</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites" aria-label="Pin Subjects">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('topics') ? 'active' : '' }}" data-title="Topics/Sub Topics"
                            data-route="{{ route('topics') }}" data-icon="hash">
                            <a href="{{ route('topics') }}" class="menu-link">
                                <i data-lucide="hash"></i>
                                <span class="menu-text">Topics/Sub Topics</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Topics/Sub Topics">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                    </ul>
                </li>

                <!-- Global Settings (Submenu) -->
                @php
                    $settingsActive = request()->routeIs('admin.system-configs.*');
                @endphp
                <li class="menu-item-wrapper has-submenu {{ $settingsActive ? 'open' : '' }}" data-title="Global Settings" data-tooltip="Global Settings">
                    <div class="menu-link submenu-toggle" tabindex="0" aria-haspopup="true" aria-expanded="{{ $settingsActive ? 'true' : 'false' }}">
                        <i data-lucide="settings"></i>
                        <span class="menu-text">Global Settings</span>
                        <i data-lucide="chevron-down" class="submenu-arrow"></i>
                    </div>
                    <ul class="submenu-items">
                        <li data-title="Tests/Exam Settings" data-route="#" data-icon="sliders">
                            <a href="#" class="menu-link">
                                <i data-lucide="sliders"></i>
                                <span class="menu-text">Tests/Exam Settings</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin Tests/Exam Settings">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                        <li class="{{ request()->routeIs('admin.system-configs.*') ? 'active' : '' }}" data-title="System Configurations" data-route="{{ route('admin.system-configs.index') }}" data-icon="settings">
                            <a href="{{ route('admin.system-configs.index') }}" class="menu-link">
                                <i data-lucide="settings"></i>
                                <span class="menu-text">System Configs</span>
                            </a>
                            <button type="button" class="pin-btn" title="Pin to favorites"
                                aria-label="Pin System Configs">
                                <i data-lucide="star"></i>
                            </button>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!-- User Profile & Action Menu Section -->
    <div class="sidebar-profile-section">
        <div class="profile-card" id="profileCard" tabindex="0" aria-haspopup="true" aria-expanded="false">
            <div class="profile-avatar">
                <!-- Fallback Initials Div and Profile Image -->
                <img src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar"
                    class="profile-img"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="profile-initials" style="display: none;">{{ strtoupper(substr($user->name ?? 'AD', 0, 2)) }}
                </div>
            </div>
            <div class="profile-info">
                <span class="profile-name">{{ ucfirst($user->name ?? 'Admin') }}</span>
                <span class="profile-role">Admin</span>
            </div>
            <i data-lucide="chevrons-up-down" class="profile-more-icon"></i>
        </div>

        <!-- Profile Dropdown popup menu -->
        <div class="profile-dropdown" id="profileDropdown" role="menu">
            <div class="dropdown-header-info">
                <strong>{{ ucfirst($user->name ?? 'Admin User') }}</strong>
                <span>{{ $user->email ?? 'admin@aspire.com' }}</span>
            </div>
            <hr class="dropdown-divider">
            <a href="{{ route('edit-profile') }}" class="dropdown-item" role="menuitem">
                <i data-lucide="user"></i> My Account
            </a>
            <a href="{{ route('edit-profile') }}" class="dropdown-item" role="menuitem">
                <i data-lucide="settings"></i> Settings
            </a>
            <a href="{{ route('change.theme') }}" class="dropdown-item" role="menuitem">
                <i data-lucide="{{ $theme === 'dark' ? 'sun' : 'moon' }}"></i> Toggle Theme
            </a>
            <hr class="dropdown-divider">
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="submit" class="dropdown-item logout-btn" role="menuitem">
                    <i data-lucide="log-out"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
<!-- END: Main Menu-->

<!-- Modern Sidebar Interactions Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Initialize Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Elements
        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const searchInput = document.getElementById('sidebarSearch');
        const searchBox = document.getElementById('searchBoxContainer');
        const searchEmptyState = document.getElementById('searchEmptyState');
        const mainMenuList = document.getElementById('mainMenuList');
        const sectionMain = document.getElementById('sectionMain');
        const profileCard = document.getElementById('profileCard');
        const profileDropdown = document.getElementById('profileDropdown');
        const mobileMenuBtn = document.querySelector('.menu-toggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Local Storage Keys
        const COLLAPSED_KEY = 'admin-sidebar-collapsed';
        const PINNED_KEY = 'admin-sidebar-pinned';
        const RECENTS_KEY = 'admin-sidebar-recents';

        // ==========================================================================
        // SIDEBAR TOGGLING (COLLAPSE/EXPAND)
        // ==========================================================================

        function setSidebarCollapsedState(collapsed) {
            if (collapsed) {
                document.body.classList.add('sidebar-collapsed');
                localStorage.setItem(COLLAPSED_KEY, 'true');
            } else {
                document.body.classList.remove('sidebar-collapsed');
                localStorage.setItem(COLLAPSED_KEY, 'false');

                // Re-sync Lucide icons after transition to make sure rendering is clean
                setTimeout(() => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }, 300);
            }
        }

        // Toggle click handler
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                setSidebarCollapsedState(!isCollapsed);
            });
        }

        // Expanded on click for Tablet
        sidebar.addEventListener('click', function (e) {
            if (window.innerWidth >= 768 && window.innerWidth < 1024) {
                if (!sidebar.classList.contains('tablet-expanded')) {
                    sidebar.classList.add('tablet-expanded');
                    sidebarOverlay.classList.add('visible');
                    document.body.classList.add('sidebar-open-no-scroll');

                    setTimeout(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }, 100);
                }
            }
        });

        // Close tablet expanded sidebar on outside click
        function collapseTabletSidebar() {
            if (sidebar.classList.contains('tablet-expanded')) {
                sidebar.classList.remove('tablet-expanded');
                sidebarOverlay.classList.remove('visible');
                document.body.classList.remove('sidebar-open-no-scroll');
            }
        }

        // ==========================================================================
        // TABLET & MOBILE DRAWER BEHAVIOR
        // ==========================================================================

        function openMobileSidebar() {
            sidebar.classList.add('mobile-open');
            sidebarOverlay.classList.add('visible');
            document.body.classList.add('sidebar-open-no-scroll');
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('visible');
            document.body.classList.remove('sidebar-open-no-scroll');
        }

        // Capture standard hamburger clicks
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openMobileSidebar();
            });
        }

        // Overlay click closes both mobile and tablet menus
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                closeMobileSidebar();
                collapseTabletSidebar();
            });
        }

        // Close menus on Escape Key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeMobileSidebar();
                collapseTabletSidebar();
                closeProfileDropdown();
            }
        });

        // Close mobile menu on navigation item selection
        sidebar.addEventListener('click', function (e) {
            const link = e.target.closest('.menu-link');
            if (link && !link.classList.contains('submenu-toggle')) {
                if (window.innerWidth < 768) {
                    closeMobileSidebar();
                }
            }
        });

        // ==========================================================================
        // SUBMENU ACCORDION INTERACTIONS
        // ==========================================================================

        const submenuToggles = document.querySelectorAll('.submenu-toggle');
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const parentWrapper = this.closest('.menu-item-wrapper.has-submenu');
                const isCurrentlyOpen = parentWrapper.classList.contains('open');

                // If sidebar is collapsed on desktop, expand it first
                if (document.body.classList.contains('sidebar-collapsed') && window.innerWidth >= 1024) {
                    setSidebarCollapsedState(false);
                    parentWrapper.classList.add('open');
                    this.setAttribute('aria-expanded', 'true');
                    return;
                }

                // Close other sibling submenus
                const openSiblings = parentWrapper.parentNode.querySelectorAll('.menu-item-wrapper.has-submenu.open');
                openSiblings.forEach(sibling => {
                    if (sibling !== parentWrapper) {
                        sibling.classList.remove('open');
                        sibling.querySelector('.submenu-toggle').setAttribute('aria-expanded', 'false');
                    }
                });

                // Toggle current submenu
                if (isCurrentlyOpen) {
                    parentWrapper.classList.remove('open');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    parentWrapper.classList.add('open');
                    this.setAttribute('aria-expanded', 'true');
                }
            });

            // Accessibility Support (Space/Enter to toggle)
            toggle.addEventListener('keydown', function (e) {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        // ==========================================================================
        // SIDEBAR SEARCH FILTERING
        // ==========================================================================

        if (searchInput) {
            // Focus styles mapping
            searchInput.addEventListener('focus', () => searchBox.classList.add('focused'));
            searchInput.addEventListener('blur', () => searchBox.classList.remove('focused'));

            // Filtering Logic
            searchInput.addEventListener('input', function (e) {
                const query = e.target.value.toLowerCase().trim();
                const items = mainMenuList.querySelectorAll('li.menu-item-wrapper');
                let matchCount = 0;

                // If sidebar is collapsed and user types, expand it automatically
                if (query.length > 0 && document.body.classList.contains('sidebar-collapsed') && window.innerWidth >= 1024) {
                    setSidebarCollapsedState(false);
                }

                // Hide/Show Favorites & Recents during search
                const pinnedSec = document.getElementById('sectionPinned');
                const recentsSec = document.getElementById('sectionRecents');
                if (query.length > 0) {
                    if (pinnedSec) pinnedSec.style.display = 'none';
                    if (recentsSec) recentsSec.style.display = 'none';
                    sectionMain.querySelector('.menu-section-header').style.display = 'none';
                } else {
                    renderPinnedList();
                    renderRecentsList();
                    sectionMain.querySelector('.menu-section-header').style.display = 'flex';
                }

                items.forEach(item => {
                    if (item.classList.contains('has-submenu')) {
                        // Check if group title or any child links match query
                        const groupTitle = item.dataset.title.toLowerCase();
                        const subLinks = item.querySelectorAll('.submenu-items li');
                        let groupMatch = false;

                        subLinks.forEach(sub => {
                            const subTitle = sub.dataset.title.toLowerCase();
                            if (subTitle.includes(query) || groupTitle.includes(query)) {
                                sub.style.display = 'block';
                                groupMatch = true;
                                matchCount++;
                            } else {
                                sub.style.display = 'none';
                            }
                        });

                        if (groupMatch || query.length === 0) {
                            item.style.display = 'block';
                            if (query.length > 0) {
                                item.classList.add('open');
                            } else {
                                // Reset to defaults
                                if (!item.querySelector('.submenu-items .active')) {
                                    item.classList.remove('open');
                                }
                            }
                        } else {
                            item.style.display = 'none';
                            item.classList.remove('open');
                        }
                    } else {
                        // Singular Link Match
                        const itemTitle = item.dataset.title.toLowerCase();
                        if (itemTitle.includes(query) || query.length === 0) {
                            item.style.display = 'block';
                            matchCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });

                // Display Empty State if no match
                if (matchCount === 0 && query.length > 0) {
                    searchEmptyState.style.display = 'flex';
                    mainMenuList.style.display = 'none';
                } else {
                    searchEmptyState.style.display = 'none';
                    mainMenuList.style.display = 'flex';
                }
            });

            // Global hotkey: press '/' or 'Ctrl+K' to focus search
            document.addEventListener('keydown', function (e) {
                // Ignore if user is inside form inputs
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName) || document.activeElement.isContentEditable) {
                    return;
                }

                if (e.key === '/' || (e.ctrlKey && e.key === 'k')) {
                    e.preventDefault();
                    // Ensure sidebar is expanded to focus search properly
                    if (document.body.classList.contains('sidebar-collapsed') && window.innerWidth >= 1024) {
                        setSidebarCollapsedState(false);
                    }
                    searchInput.focus();
                    searchInput.select();
                }
            });

            // Escape clears search
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('input'));
                    searchInput.blur();
                }
            });
        }

        // ==========================================================================
        // FAVORITES / PINNED NAVIGATION ITEMS
        // ==========================================================================

        let pinnedList = JSON.parse(localStorage.getItem(PINNED_KEY)) || [];

        function renderPinnedList() {
            const container = document.getElementById('pinnedList');
            const headerSection = document.getElementById('sectionPinned');
            const skeleton = document.getElementById('pinnedSkeleton');

            if (!container || !headerSection) return;

            // Hide Skeleton
            if (skeleton) skeleton.style.display = 'none';

            if (pinnedList.length === 0 || (searchInput && searchInput.value.length > 0)) {
                headerSection.style.display = 'none';
                container.style.display = 'none';
                return;
            }

            // Render Links
            container.innerHTML = '';
            pinnedList.forEach(item => {
                const li = document.createElement('li');
                li.className = 'menu-item-wrapper';
                li.innerHTML = `
                <a href="${item.route}" class="menu-link">
                    <i data-lucide="${item.icon || 'star'}"></i>
                    <span class="menu-text">${item.title}</span>
                </a>
                <button type="button" class="pin-btn pinned" title="Unpin from favorites" aria-label="Unpin ${item.title}">
                    <i data-lucide="star"></i>
                </button>
            `;

                // Unpin action handler
                li.querySelector('.pin-btn').addEventListener('click', function (e) {
                    e.stopPropagation();
                    togglePin(item.title, item.route, item.icon);
                });

                container.appendChild(li);
            });

            headerSection.style.display = 'block';
            container.style.display = 'flex';

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function togglePin(title, route, icon) {
            const index = pinnedList.findIndex(p => p.title === title);
            if (index > -1) {
                pinnedList.splice(index, 1);
            } else {
                pinnedList.push({ title, route, icon });
            }
            localStorage.setItem(PINNED_KEY, JSON.stringify(pinnedList));

            // Sync star icons state in main menu
            updateMainStarIcons();

            // Re-render pinned links
            renderPinnedList();
        }

        function updateMainStarIcons() {
            const pinButtons = document.querySelectorAll('#mainMenuList .pin-btn');
            pinButtons.forEach(btn => {
                const wrapper = btn.closest('.menu-item-wrapper');
                const title = wrapper.dataset.title;
                const isPinned = pinnedList.some(p => p.title === title);

                if (isPinned) {
                    btn.classList.add('pinned');
                } else {
                    btn.classList.remove('pinned');
                }
            });
        }

        // Attach pin click handlers to main menu items
        const mainPinButtons = document.querySelectorAll('#mainMenuList .pin-btn');
        mainPinButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const wrapper = this.closest('.menu-item-wrapper');
                const title = wrapper.dataset.title;
                const route = wrapper.dataset.route;
                const icon = wrapper.dataset.icon;
                togglePin(title, route, icon);
            });
        });

        // ==========================================================================
        // RECENTLY VISITED LOGGING & RENDER
        // ==========================================================================

        let recentsList = JSON.parse(localStorage.getItem(RECENTS_KEY)) || [];

        function logCurrentPageVisit() {
            const currentPath = window.location.pathname;
            const currentSearch = window.location.search;
            const currentUrl = currentPath + currentSearch;

            // Find matching item in our main directory
            const matchItem = document.querySelector(`#mainMenuList li[data-route]:not([data-route="#"]):not([data-route=""])`);
            const matchingWrappers = document.querySelectorAll('#mainMenuList li[data-route]');

            let foundWrapper = null;
            for (let wrap of matchingWrappers) {
                // Match normalized routes
                const itemRoute = wrap.dataset.route;
                if (itemRoute && currentUrl.includes(itemRoute.replace(window.location.origin, ''))) {
                    foundWrapper = wrap;
                    break;
                }
            }

            if (foundWrapper) {
                const title = foundWrapper.dataset.title;
                const route = foundWrapper.dataset.route;
                const icon = foundWrapper.dataset.icon;

                // Remove existing duplicate
                recentsList = recentsList.filter(r => r.title !== title);

                // Add to front of history list
                recentsList.unshift({ title, route, icon });

                // Caps at 4 items
                if (recentsList.length > 4) {
                    recentsList.pop();
                }

                localStorage.setItem(RECENTS_KEY, JSON.stringify(recentsList));
            }
        }

        function renderRecentsList() {
            const container = document.getElementById('recentsList');
            const headerSection = document.getElementById('sectionRecents');
            const skeleton = document.getElementById('recentsSkeleton');

            if (!container || !headerSection) return;

            // Hide Skeleton
            if (skeleton) skeleton.style.display = 'none';

            // Filter out the page currently being visited so we don't display redundantly
            const currentPath = window.location.pathname;
            const filteredRecents = recentsList.filter(item => {
                const pathPart = item.route.replace(window.location.origin, '');
                return !currentPath.includes(pathPart) && pathPart !== '#';
            });

            if (filteredRecents.length === 0 || (searchInput && searchInput.value.length > 0)) {
                headerSection.style.display = 'none';
                container.style.display = 'none';
                return;
            }

            container.innerHTML = '';
            filteredRecents.forEach(item => {
                const li = document.createElement('li');
                li.className = 'menu-item-wrapper';
                li.innerHTML = `
                <a href="${item.route}" class="menu-link">
                    <i data-lucide="${item.icon || 'history'}"></i>
                    <span class="menu-text">${item.title}</span>
                </a>
            `;
                container.appendChild(li);
            });

            headerSection.style.display = 'block';
            container.style.display = 'flex';

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        // Log current page and render sections asynchronously with skeleton simulation
        logCurrentPageVisit();

        // 400ms delay to simulate loading skeleton state (premium SaaS feel)
        setTimeout(() => {
            renderPinnedList();
            renderRecentsList();
            updateMainStarIcons();
        }, 450);

        // ==========================================================================
        // USER PROFILE MENU (POPOVER DRAWER)
        // ==========================================================================

        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const isOpen = profileDropdown.classList.contains('open');

            if (isOpen) {
                closeProfileDropdown();
            } else {
                profileDropdown.classList.add('open');
                profileCard.setAttribute('aria-expanded', 'true');
            }
        }

        function closeProfileDropdown() {
            profileDropdown.classList.remove('open');
            profileCard.setAttribute('aria-expanded', 'false');
        }

        if (profileCard) {
            profileCard.addEventListener('click', toggleProfileDropdown);

            // Keydown support
            profileCard.addEventListener('keydown', function (e) {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    toggleProfileDropdown();
                }
            });
        }

        // Close profile dropdown on outside clicks
        document.addEventListener('click', function (e) {
            if (profileDropdown && !profileDropdown.contains(e.target) && !profileCard.contains(e.target)) {
                closeProfileDropdown();
            }
        });

        // ==========================================================================
        // ACCESSIBILITY: KEYBOARD ARROW KEY NAVIGATION
        // ==========================================================================

        sidebar.addEventListener('keydown', function (e) {
            if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') return;

            // Find all visible keyboard focusable elements in the navigation items
            const focusableSelectors = '.menu-link, .submenu-toggle, .pin-btn, #sidebarSearch, #profileCard, .profile-dropdown .dropdown-item';
            const focusables = Array.from(sidebar.querySelectorAll(focusableSelectors)).filter(el => {
                // Filter elements that are visible (not collapsed, not hidden by search)
                const wrapper = el.closest('.menu-item-wrapper');
                const submenu = el.closest('.submenu-items');

                if (el.id === 'sidebarSearch' || el.id === 'profileCard' || el.closest('.profile-dropdown')) {
                    return el.offsetParent !== null;
                }

                if (wrapper && wrapper.style.display === 'none') return false;
                if (submenu && !submenu.closest('.menu-item-wrapper.has-submenu').classList.contains('open')) return false;

                return el.offsetParent !== null;
            });

            const activeIndex = focusables.indexOf(document.activeElement);
            let nextIndex = activeIndex;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                nextIndex = activeIndex + 1 < focusables.length ? activeIndex + 1 : 0;
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                nextIndex = activeIndex - 1 >= 0 ? activeIndex - 1 : focusables.length - 1;
            }

            if (focusables[nextIndex]) {
                focusables[nextIndex].focus();
            }
        });
    });
</script>