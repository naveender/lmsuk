<!-- BEGIN: Header-->
@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
    $theme = session('theme', 'light');
    $user = auth()->user();
@endphp

@if ($isAdmin)
    <!-- =========================================================================
         ADMIN MODERN FLOATING NAVBAR
         ========================================================================= -->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu admin-modern-navbar navbar-light">
        <div class="navbar-wrapper w-100">
            <div class="navbar-container content d-flex align-items-center justify-content-between w-100">
                
                <!-- Left Section: Mobile Toggle & Brand Logo -->
                <div class="navbar-left-area d-flex align-items-center">
                    <button type="button" class="admin-nav-icon-btn mobile-menu-btn d-xl-none mr-1" id="adminMobileMenuToggle" aria-label="Toggle Navigation">
                        <i class="feather icon-menu"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="admin-header-brand-logo d-none d-sm-flex align-items-center">
                        <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Horizontal-Full-Logo.png') }}" alt="Aspire Learners" height="38">
                    </a>
                </div>

                <!-- Right Section: Notification Bell & User Profile (Matches Reference Image) -->
                <div class="navbar-right-area d-flex align-items-center" style="gap: 16px;">
                    
                    <!-- Notification Bell with Red Badge '3' -->
                    <div class="dropdown">
                        <button class="header-icon-badge-btn" type="button" id="adminNotificationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notifications">
                            <i class="feather icon-bell"></i>
                            <span class="badge-count">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right animated-dropdown shadow-lg border-0 notifications-dropdown-menu" aria-labelledby="adminNotificationsDropdown" style="width: 320px; border-radius: 12px; margin-top: 8px;">
                            <div class="d-flex align-items-center justify-content-between px-2 py-1 border-bottom">
                                <h6 class="font-weight-bold mb-0 text-dark">Notifications</h6>
                                <span class="badge badge-pill badge-light-primary font-small-1 font-weight-bold">3 New</span>
                            </div>
                            <div class="notifications-list scrollable-container" style="max-height: 280px; overflow-y: auto;">
                                <a href="{{ route('admin.students.index') }}" class="dropdown-item d-flex align-items-start py-1 px-2 border-bottom">
                                    <div class="notif-icon-circle bg-light-primary text-primary mr-1 mt-25">
                                        <i class="feather icon-user-check"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="font-weight-bold text-dark mb-25 font-small-2">New student registration</p>
                                        <small class="text-muted d-block font-small-1">James Wilson enrolled in Year 7</small>
                                        <small class="text-primary font-small-1 font-weight-bold mt-25 d-inline-block">Just now</small>
                                    </div>
                                </a>
                                <a href="{{ route('admin.announcements.index') }}" class="dropdown-item d-flex align-items-start py-1 px-2 border-bottom">
                                    <div class="notif-icon-circle bg-light-danger text-danger mr-1 mt-25">
                                        <i class="feather icon-bell"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="font-weight-bold text-dark mb-25 font-small-2">System Announcement</p>
                                        <small class="text-muted d-block font-small-1">Term assessments schedule live</small>
                                        <small class="text-muted font-small-1 mt-25 d-inline-block">2 hours ago</small>
                                    </div>
                                </a>
                                <a href="{{ route('logs') }}" class="dropdown-item d-flex align-items-start py-1 px-2">
                                    <div class="notif-icon-circle bg-light-info text-info mr-1 mt-25">
                                        <i class="feather icon-activity"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="font-weight-bold text-dark mb-25 font-small-2">Audit Logs Synced</p>
                                        <small class="text-muted d-block font-small-1">Activity logs updated</small>
                                        <small class="text-muted font-small-1 mt-25 d-inline-block">Today</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Pill Dropdown -->
                    <div class="dropdown dropdown-user">
                        <button class="header-user-profile-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="header-user-avatar" src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="Admin Avatar">
                            <div class="header-user-info d-none d-md-flex">
                                <span class="header-user-name">{{ $user->name ?? 'Admin User' }}</span>
                                <span class="header-user-role">Admin</span>
                            </div>
                            <i class="feather icon-chevron-down header-chevron d-none d-md-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right animated-dropdown shadow-lg border-0" style="min-width: 200px; border-radius: 12px; margin-top: 8px;">
                            <div class="p-2 border-bottom">
                                <h6 class="font-weight-bold text-dark mb-25">{{ $user->name ?? 'Admin User' }}</h6>
                                <span class="font-small-2 text-muted d-block">{{ $user->email ?? 'admin@aspire.com' }}</span>
                            </div>
                            <div class="py-50">
                                <a class="dropdown-item d-flex align-items-center py-75 px-2 font-small-3" href="{{ route('account.index') }}">
                                    <i class="feather icon-user mr-1 text-primary"></i>
                                    <span>My Account</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center py-75 px-2 font-small-3" href="{{ route('admin.system-configs.index') }}">
                                    <i class="feather icon-settings mr-1 text-info"></i>
                                    <span>System Configs</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center py-75 px-2 font-small-3" href="{{ route('logs') }}">
                                    <i class="feather icon-file-text mr-1 text-warning"></i>
                                    <span>Activity Logs</span>
                                </a>
                            </div>
                            <div class="dropdown-divider my-25"></div>
                            <div class="p-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-block btn-sm font-weight-bold d-flex align-items-center justify-content-center">
                                        <i class="feather icon-power mr-50"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </nav>

    <!-- Global Helper Script for Admin Header Interactions -->
    <script>
        function focusAdminSidebarSearch() {
            var searchInput = document.getElementById('sidebarSearch');
            if (searchInput) {
                // If desktop sidebar is collapsed, expand it
                if (document.body.classList.contains('sidebar-collapsed') && window.innerWidth >= 1024) {
                    if (typeof setSidebarCollapsedState === 'function') {
                        setSidebarCollapsedState(false);
                    } else {
                        document.body.classList.remove('sidebar-collapsed');
                    }
                }
                // If mobile, open mobile sidebar
                if (window.innerWidth < 1024 && typeof openMobileSidebar === 'function') {
                    openMobileSidebar();
                }
                setTimeout(function() {
                    searchInput.focus();
                    searchInput.select();
                }, 100);
            }
        }

        // Fullscreen Toggle
        document.addEventListener('DOMContentLoaded', function() {
            var fsBtn = document.getElementById('adminFullscreenBtn');
            var fsIcon = document.getElementById('fullscreenIcon');
            if (fsBtn) {
                fsBtn.addEventListener('click', function() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(function(err) {});
                        if (fsIcon) {
                            fsIcon.classList.remove('icon-maximize');
                            fsIcon.classList.add('icon-minimize');
                        }
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                        if (fsIcon) {
                            fsIcon.classList.remove('icon-minimize');
                            fsIcon.classList.add('icon-maximize');
                        }
                    }
                });
            }

            // Sync Mobile Menu Toggle Button
            var mobileToggle = document.getElementById('adminMobileMenuToggle');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof openMobileSidebar === 'function') {
                        openMobileSidebar();
                    } else {
                        var sidebar = document.getElementById('adminSidebar');
                        var overlay = document.getElementById('sidebarOverlay');
                        if (sidebar) sidebar.classList.add('mobile-open');
                        if (overlay) overlay.classList.add('visible');
                        document.body.classList.add('sidebar-open-no-scroll');
                    }
                });
            }
        });
    </script>

@else
    <!-- =========================================================================
         NON-ADMIN (STUDENT / PARENT / TUTOR) NAVBAR
         ========================================================================= -->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-fixed navbar-brand-center">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item"><a class="navbar-brand" href="/" style="padding: 15px 0;">
                        <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Horizontal-Full-Logo.png') }}" alt="Aspire Learner Logo" style="max-width: 160px; height: auto; object-fit: contain;">
                    </a></li>
            </ul>
        </div>
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a
                                    class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                                        class="ficon feather icon-menu"></i></a></li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none"><span
                                        class="user-name text-bold-600">{{ ucfirst(auth()->user()->name) }}</span>
                                    <span class="user-status">{{ ucfirst(auth()->user()->role) }}</span>
                                </div><span><img class="round"
                                        src={{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}
                                        alt="avatar" height="40" width="40"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('account.index') }}"><i
                                        class="feather icon-user"></i>
                                    My Account</a>
                                <a class="dropdown-item" href="{{ route('settings.index') }}"><i
                                        class="feather icon-settings"></i>
                                    Settings</a>
                                <a class="dropdown-item" href="{{ route('change.theme') }}" id="themeToggleBtn"><i
                                        class="feather icon-{{ $theme === 'dark' ? 'sun' : 'moon' }}"></i>
                                    Enable {{ $theme === 'dark' ? 'Light' : 'Dark' }} Mode</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item btn mb-1 btn-block waves-effect waves-light"
                                        style="width:100%">
                                        <i class="feather icon-power"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
@endif
<!-- END: Header-->
