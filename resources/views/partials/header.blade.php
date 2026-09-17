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
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav admin-modern-navbar {{ $theme === 'dark' ? 'navbar-dark' : 'navbar-light' }} navbar-shadow">
        <div class="navbar-wrapper w-100">
            <div class="navbar-container content d-flex align-items-center justify-content-between w-100">
                
                <!-- Left Section: Mobile Toggle & Quick Navigation / Create -->
                <div class="navbar-left-area d-flex align-items-center">
                    <!-- Mobile Hamburger Menu Button (visible below xl) -->
                    <button type="button" class="admin-nav-icon-btn mobile-menu-btn d-xl-none mr-1" id="adminMobileMenuToggle" aria-label="Toggle Navigation">
                        <i class="feather icon-menu"></i>
                    </button>

                    <!-- Portal Indicator / Breadcrumb Badge -->
                    <div class="portal-badge-wrap d-none d-md-flex align-items-center mr-1 mr-lg-2">
                        <span class="portal-badge">
                            <i class="feather icon-shield mr-25"></i>
                            <span class="portal-badge-text">Admin Portal</span>
                        </span>
                    </div>

                    <!-- Quick Action "+ Create" Dropdown -->
                    <div class="dropdown mr-1 mr-lg-2">
                        <button class="btn btn-sm btn-quick-create dropdown-toggle d-flex align-items-center" type="button" id="quickCreateDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="feather icon-plus mr-50"></i>
                            <span class="font-weight-bold">Create</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-left animated-dropdown shadow-lg border-0" aria-labelledby="quickCreateDropdown">
                            <div class="dropdown-header font-weight-bolder text-uppercase font-small-1 text-muted px-2 py-1">Quick Actions</div>
                            <a class="dropdown-item d-flex align-items-center py-75 px-2" href="{{ route('admin.questions.create') }}">
                                <div class="action-icon-circle bg-light-primary text-primary mr-1">
                                    <i class="feather icon-help-circle"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold d-block text-dark font-small-3">New Question</span>
                                    <small class="text-muted">Add question to bank</small>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center py-75 px-2" href="{{ route('admin.papers.create') }}">
                                <div class="action-icon-circle bg-light-success text-success mr-1">
                                    <i class="feather icon-file-text"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold d-block text-dark font-small-3">New Quiz / Test</span>
                                    <small class="text-muted">Create mock or assessment</small>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center py-75 px-2" href="{{ route('admin.homeworks.create') }}">
                                <div class="action-icon-circle bg-light-warning text-warning mr-1">
                                    <i class="feather icon-book-open"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold d-block text-dark font-small-3">New Homework</span>
                                    <small class="text-muted">Assign student homework</small>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center py-75 px-2" href="{{ route('admin.students.create') }}">
                                <div class="action-icon-circle bg-light-info text-info mr-1">
                                    <i class="feather icon-user-plus"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold d-block text-dark font-small-3">Register Student</span>
                                    <small class="text-muted">Enroll new student</small>
                                </div>
                            </a>
                            <div class="dropdown-divider my-25"></div>
                            <a class="dropdown-item d-flex align-items-center py-75 px-2" href="{{ route('admin.announcements.create') }}">
                                <div class="action-icon-circle bg-light-danger text-danger mr-1">
                                    <i class="feather icon-bell"></i>
                                </div>
                                <div>
                                    <span class="font-weight-bold d-block text-dark font-small-3">New Announcement</span>
                                    <small class="text-muted">Broadcast to portal</small>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Search Trigger Input Button -->
                    <button type="button" class="btn-navbar-search d-none d-lg-flex align-items-center" onclick="focusAdminSidebarSearch()" title="Focus Search (Ctrl + K)">
                        <i class="feather icon-search mr-50"></i>
                        <span class="search-placeholder">Search pages, questions, tests...</span>
                        <kbd class="navbar-kbd ml-auto">Ctrl + K</kbd>
                    </button>
                </div>

                <!-- Right Section: Tools, Notifications & User Menu -->
                <div class="navbar-right-area d-flex align-items-center">
                    
                    <!-- Theme Toggle Quick Button -->
                    <a href="{{ route('change.theme') }}" class="admin-nav-icon-btn theme-toggle-btn mr-50" title="Switch to {{ $theme === 'dark' ? 'Light' : 'Dark' }} Mode" id="adminThemeToggleBtn">
                        <i class="feather icon-{{ $theme === 'dark' ? 'sun' : 'moon' }}"></i>
                    </a>

                    <!-- Fullscreen Toggle Button -->
                    <button type="button" class="admin-nav-icon-btn fullscreen-toggle-btn mr-50 d-none d-sm-flex" id="adminFullscreenBtn" title="Toggle Fullscreen">
                        <i class="feather icon-maximize" id="fullscreenIcon"></i>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div class="dropdown mr-50">
                        <button class="admin-nav-icon-btn position-relative" type="button" id="adminNotificationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notifications & Activity">
                            <i class="feather icon-bell"></i>
                            <span class="nav-unread-dot"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right animated-dropdown shadow-lg border-0 notifications-dropdown-menu" aria-labelledby="adminNotificationsDropdown">
                            <div class="d-flex align-items-center justify-content-between px-2 py-1 border-bottom">
                                <h6 class="font-weight-bold mb-0 text-dark">Notifications</h6>
                                <span class="badge badge-pill badge-light-primary font-small-1 font-weight-bold">System Active</span>
                            </div>
                            <div class="notifications-list scrollable-container" style="max-height: 280px; overflow-y: auto;">
                                <a href="{{ route('admin.announcements.index') }}" class="dropdown-item d-flex align-items-start py-1 px-2 border-bottom">
                                    <div class="notif-icon-circle bg-light-primary text-primary mr-1 mt-25">
                                        <i class="feather icon-radio"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="font-weight-bold text-dark mb-25 font-small-2">Announcement System Active</p>
                                        <small class="text-muted d-block font-small-1">Student portal broadcasting enabled</small>
                                        <small class="text-primary font-small-1 font-weight-bold mt-25 d-inline-block">Live Feed</small>
                                    </div>
                                </a>
                                <a href="{{ route('admin.papers.index') }}" class="dropdown-item d-flex align-items-start py-1 px-2 border-bottom">
                                    <div class="notif-icon-circle bg-light-success text-success mr-1 mt-25">
                                        <i class="feather icon-check-circle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="font-weight-bold text-dark mb-25 font-small-2">Test Assessment Engine</p>
                                        <small class="text-muted d-block font-small-1">Automated auto-marking is running</small>
                                        <small class="text-muted font-small-1 mt-25 d-inline-block">System Healthy</small>
                                    </div>
                                </a>
                                <a href="{{ route('logs') }}" class="dropdown-item d-flex align-items-start py-1 px-2">
                                    <div class="notif-icon-circle bg-light-info text-info mr-1 mt-25">
                                        <i class="feather icon-activity"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="font-weight-bold text-dark mb-25 font-small-2">Audit Logs Synced</p>
                                        <small class="text-muted d-block font-small-1">System activity logs captured</small>
                                        <small class="text-muted font-small-1 mt-25 d-inline-block">Verified</small>
                                    </div>
                                </a>
                            </div>
                            <div class="p-1 text-center border-top">
                                <a href="{{ route('admin.announcements.index') }}" class="font-weight-bold font-small-2 text-primary">
                                    View All Announcements <i class="feather icon-arrow-right ml-25"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Vertical Divider -->
                    <div class="nav-vertical-divider d-none d-sm-block mr-1"></div>

                    <!-- Modern User Profile Pill Dropdown -->
                    <div class="dropdown dropdown-user">
                        <a class="dropdown-toggle user-pill-btn d-flex align-items-center" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="user-avatar-wrap position-relative mr-50">
                                <img class="round user-avatar-img" src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="38" width="38">
                                <span class="status-indicator-online" title="Online"></span>
                            </div>
                            <div class="user-text-wrap d-none d-md-flex flex-column text-left mr-50">
                                <span class="user-display-name font-weight-bold">{{ ucfirst($user->name ?? 'Admin') }}</span>
                                <span class="user-role-badge">Super Admin</span>
                            </div>
                            <i class="feather icon-chevron-down font-small-2 text-muted d-none d-md-inline-block"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right animated-dropdown shadow-lg border-0 user-dropdown-menu">
                            <!-- Dropdown User Info Card -->
                            <div class="dropdown-user-header p-2 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="mr-1">
                                        <img class="round" src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="46" width="46">
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-25">{{ ucfirst($user->name ?? 'Administrator') }}</h6>
                                        <span class="font-small-2 text-muted d-block text-truncate" style="max-width: 170px;">{{ $user->email ?? 'admin@aspire.com' }}</span>
                                        <span class="badge badge-pill badge-light-primary font-small-1 font-weight-bold text-uppercase mt-25">Administrator</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Links -->
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
                                <a class="dropdown-item d-flex align-items-center py-75 px-2 font-small-3" href="{{ route('admin.media-files.index') }}">
                                    <i class="feather icon-folder mr-1 text-secondary"></i>
                                    <span>Media Repository</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center py-75 px-2 font-small-3" href="{{ route('change.theme') }}">
                                    <i class="feather icon-{{ $theme === 'dark' ? 'sun' : 'moon' }} mr-1 text-success"></i>
                                    <span>{{ $theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode' }}</span>
                                </a>
                            </div>

                            <div class="dropdown-divider my-25"></div>

                            <!-- Logout Form -->
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
