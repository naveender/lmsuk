@php
    $theme = session('theme', 'light');
    $user = auth()->user();
@endphp

<!-- Load Lucide Icons CDN -->
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Immediate Inline Script to Prevent FOUC -->
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

<!-- Custom Overlay Backdrop for Drawer Behavior -->
<div class="sidebar-overlay-backdrop" id="sidebarOverlay"></div>

<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed modern-sidebar" id="adminSidebar" data-scroll-to-active="true">

    <!-- Sidebar Header (Logo and Toggle Button) -->
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo-link">
            <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Horizontal-Full-Logo.png') }}"
                alt="Aspire Learners" class="logo-full">
            <div class="logo-icon d-none" style="color: #fff; font-weight: 800; font-size: 20px;">AL</div>
        </a>
        <button type="button" class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar"
            title="Collapse Sidebar">
            <i data-lucide="chevron-left" class="toggle-icon-arrow" style="width: 16px; height: 16px;"></i>
        </button>
    </div>

    <!-- Search Section inside Sidebar -->
    <div class="sidebar-search-box" id="searchBoxContainer">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" class="search-input" id="sidebarSearch" placeholder="Search pages..." autocomplete="off"
            aria-label="Search pages">
        <span class="search-shortcut">/</span>
    </div>

    <!-- Scrollable Menu Content -->
    <div class="sidebar-menu-content">
        <!-- Search Empty State -->
        <div class="search-empty-state" id="searchEmptyState"
            style="display: none; padding: 20px; text-align: center; color: var(--sb-text-muted);">
            <i data-lucide="search-x" style="width: 32px; height: 32px; margin-bottom: 8px;"></i>
            <p style="margin: 0; font-size: 13px;">No pages found</p>
        </div>

        <ul class="menu-items" id="mainMenuList">

            <!-- Dashboard -->
            <li class="menu-item-wrapper {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                data-title="Dashboard" data-route="{{ route('admin.dashboard') }}" data-icon="home">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i data-lucide="home"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- Users (Submenu) -->
            @php
                $userGroupActive = request()->routeIs('admin.users.*') || request()->routeIs('admin.students.*') || request()->routeIs('admin.parents.*');
            @endphp
            <li class="menu-item-wrapper has-submenu {{ $userGroupActive ? 'open' : '' }}" data-title="Users">
                <div class="menu-link submenu-toggle" tabindex="0" role="button"
                    aria-expanded="{{ $userGroupActive ? 'true' : 'false' }}">
                    <i data-lucide="users"></i>
                    <span class="menu-text">Users</span>
                    <i data-lucide="chevron-down" class="submenu-arrow"></i>
                </div>
                <ul class="submenu-items">
                    <li class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}"
                        data-title="Add New User">
                        <a href="{{ route('admin.users.create') }}" class="menu-link">
                            <i data-lucide="user-plus"></i>
                            <span class="menu-text">Add New User</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}" data-title="All Users">
                        <a href="{{ route('admin.users.index') }}" class="menu-link">
                            <i data-lucide="users"></i>
                            <span class="menu-text">All Users</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}"
                        data-title="Manage Students">
                        <a href="{{ route('admin.students.index') }}" class="menu-link">
                            <i data-lucide="graduation-cap"></i>
                            <span class="menu-text">Manage Students</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.parents.*') ? 'active' : '' }}" data-title="Manage Parents">
                        <a href="{{ route('admin.parents.index') }}" class="menu-link">
                            <i data-lucide="heart"></i>
                            <span class="menu-text">Manage Parents</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Question Bank (Submenu) -->
            @php
                $qBankActive = request()->routeIs('admin.questions.*');
            @endphp
            <li class="menu-item-wrapper has-submenu {{ $qBankActive ? 'open' : '' }}" data-title="Question Bank">
                <div class="menu-link submenu-toggle" tabindex="0" role="button"
                    aria-expanded="{{ $qBankActive ? 'true' : 'false' }}">
                    <i data-lucide="help-circle"></i>
                    <span class="menu-text">Question Bank</span>
                    <i data-lucide="chevron-down" class="submenu-arrow"></i>
                </div>
                <ul class="submenu-items">
                    <li class="{{ request()->routeIs('admin.questions.create') ? 'active' : '' }}"
                        data-title="Add New Question">
                        <a href="{{ route('admin.questions.create') }}" class="menu-link">
                            <i data-lucide="plus-circle"></i>
                            <span class="menu-text">Add New Question</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.questions.index') ? 'active' : '' }}"
                        data-title="Questions Directory">
                        <a href="{{ route('admin.questions.index') }}" class="menu-link">
                            <i data-lucide="folder-open"></i>
                            <span class="menu-text">Questions Directory</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.questions.import-form') ? 'active' : '' }}"
                        data-title="Import Questions">
                        <a href="{{ route('admin.questions.import-form') }}" class="menu-link">
                            <i data-lucide="upload"></i>
                            <span class="menu-text">Import Questions</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Quiz/Test Manager (Submenu) -->
            @php
                $papersActive = request()->routeIs('admin.papers.*');
            @endphp
            <li class="menu-item-wrapper has-submenu {{ $papersActive ? 'open' : '' }}" data-title="Quiz/Test Manager">
                <div class="menu-link submenu-toggle" tabindex="0" role="button"
                    aria-expanded="{{ $papersActive ? 'true' : 'false' }}">
                    <i data-lucide="file-text"></i>
                    <span class="menu-text">Quiz/Test Manager</span>
                    <i data-lucide="chevron-down" class="submenu-arrow"></i>
                </div>
                <ul class="submenu-items">
                    <li class="{{ request()->routeIs('admin.papers.create') ? 'active' : '' }}"
                        data-title="Create New Quiz/Test">
                        <a href="{{ route('admin.papers.create') }}" class="menu-link">
                            <i data-lucide="file-plus"></i>
                            <span class="menu-text">Create New Quiz/Test</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.papers.index') ? 'active' : '' }}"
                        data-title="Quizzes Directory">
                        <a href="{{ route('admin.papers.index') }}" class="menu-link">
                            <i data-lucide="files"></i>
                            <span class="menu-text">Quizzes Directory</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Homework Management (Submenu) -->
            @php
                $homeworksActive = request()->routeIs('admin.homeworks.*');
            @endphp
            <li class="menu-item-wrapper has-submenu {{ $homeworksActive ? 'open' : '' }}"
                data-title="Homework Management">
                <div class="menu-link submenu-toggle" tabindex="0" role="button"
                    aria-expanded="{{ $homeworksActive ? 'true' : 'false' }}">
                    <i data-lucide="book-open"></i>
                    <span class="menu-text">Homework Management</span>
                    <i data-lucide="chevron-down" class="submenu-arrow"></i>
                </div>
                <ul class="submenu-items">
                    <li class="{{ request()->routeIs('admin.homeworks.create') ? 'active' : '' }}"
                        data-title="Create New Homework">
                        <a href="{{ route('admin.homeworks.create') }}" class="menu-link">
                            <i data-lucide="file-plus"></i>
                            <span class="menu-text">Create New Homework</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.homeworks.index') ? 'active' : '' }}"
                        data-title="Homework Directory">
                        <a href="{{ route('admin.homeworks.index') }}" class="menu-link">
                            <i data-lucide="files"></i>
                            <span class="menu-text">Homework Directory</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Create a Report -->
            <li class="menu-item-wrapper" data-title="Create a Report">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i data-lucide="bar-chart-2"></i>
                    <span class="menu-text">Create a Report</span>
                </a>
            </li>

            <!-- Manage Files -->
            <li class="menu-item-wrapper {{ request()->routeIs('admin.media-files.*') ? 'active' : '' }}"
                data-title="Manage Files">
                <a href="{{ route('admin.media-files.index') }}" class="menu-link">
                    <i data-lucide="folder"></i>
                    <span class="menu-text">Manage Files</span>
                </a>
            </li>

            <!-- Invoice Creator -->
            <li class="menu-item-wrapper" data-title="Invoice Creator">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i data-lucide="credit-card"></i>
                    <span class="menu-text">Invoice Creator</span>
                </a>
            </li>

            <!-- Cohort Report -->
            <li class="menu-item-wrapper" data-title="Cohort Report">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i data-lucide="pie-chart"></i>
                    <span class="menu-text">Cohort Report</span>
                </a>
            </li>

            <!-- Manage Announcement -->
            <li class="menu-item-wrapper {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}"
                data-title="Manage Announcement">
                <a href="{{ route('admin.announcements.index') }}" class="menu-link">
                    <i data-lucide="volume-2"></i>
                    <span class="menu-text">Manage Announcement</span>
                </a>
            </li>

            <!-- Content Manager (Submenu) -->
            @php
                $contentActive = request()->routeIs('admin.classes.*') || request()->routeIs('admin.academic-years.*') || request()->routeIs('admin.year-groups.*') || request()->routeIs('admin.subjects.*') || request()->routeIs('topics*') || request()->routeIs('add.topic*') || request()->routeIs('admin.courses.*');
            @endphp
            <li class="menu-item-wrapper has-submenu {{ $contentActive ? 'open' : '' }}" data-title="Content Manager">
                <div class="menu-link submenu-toggle" tabindex="0" role="button"
                    aria-expanded="{{ $contentActive ? 'true' : 'false' }}">
                    <i data-lucide="book-open"></i>
                    <span class="menu-text">Content Manager</span>
                    <i data-lucide="chevron-down" class="submenu-arrow"></i>
                </div>
                <ul class="submenu-items">
                    <li class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" data-title="Courses">
                        <a href="{{ route('admin.courses.index') }}" class="menu-link">
                            <i data-lucide="award"></i>
                            <span class="menu-text">Courses</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.classes.index') ? 'active' : '' }}" data-title="Classes">
                        <a href="{{ route('admin.classes.index') }}" class="menu-link">
                            <i data-lucide="shapes"></i>
                            <span class="menu-text">Classes</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.academic-years.*') ? 'active' : '' }}"
                        data-title="Academic Years">
                        <a href="{{ route('admin.academic-years.index') }}" class="menu-link">
                            <i data-lucide="calendar"></i>
                            <span class="menu-text">Academic Years</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.year-groups.*') ? 'active' : '' }}"
                        data-title="Year Groups">
                        <a href="{{ route('admin.year-groups.index') }}" class="menu-link">
                            <i data-lucide="layers"></i>
                            <span class="menu-text">Year Groups</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}" data-title="Subjects">
                        <a href="{{ route('admin.subjects.index') }}" class="menu-link">
                            <i data-lucide="book"></i>
                            <span class="menu-text">Subjects</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('topics') ? 'active' : '' }}" data-title="Topics">
                        <a href="{{ route('topics') }}" class="menu-link">
                            <i data-lucide="hash"></i>
                            <span class="menu-text">Topics / Sub Topics</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Global Settings (Submenu) -->
            @php
                $settingsActive = request()->routeIs('admin.system-configs.*') || request()->routeIs('logs') || request()->routeIs('settings.*');
            @endphp
            <li class="menu-item-wrapper has-submenu {{ $settingsActive ? 'open' : '' }}" data-title="Global Settings">
                <div class="menu-link submenu-toggle" tabindex="0" role="button"
                    aria-expanded="{{ $settingsActive ? 'true' : 'false' }}">
                    <i data-lucide="settings"></i>
                    <span class="menu-text">Global Settings</span>
                    <i data-lucide="chevron-down" class="submenu-arrow"></i>
                </div>
                <ul class="submenu-items">

                    <li class="{{ request()->routeIs('admin.system-configs.*') ? 'active' : '' }}"
                        data-title="System Configs">
                        <a href="{{ route('admin.system-configs.index') }}" class="menu-link">
                            <i data-lucide="cpu"></i>
                            <span class="menu-text">System Configs</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('logs') ? 'active' : '' }}" data-title="Activity Logs">
                        <a href="{{ route('logs') }}" class="menu-link">
                            <i data-lucide="file-text"></i>
                            <span class="menu-text">Activity Logs</span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>

    <!-- User Profile Section at Bottom (Matches Reference) -->
    <div class="sidebar-profile-section" id="sidebarProfileSection" role="button" tabindex="0" aria-haspopup="dialog" aria-expanded="false" title="Click to Sign Out">
        <div class="profile-card" id="profileCard">
            <img src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="Admin Avatar"
                class="profile-avatar">
            <div class="profile-info">
                <span class="profile-name">{{ $user->name ?? 'Admin User' }}</span>
                <span class="profile-role">Admin</span>
            </div>
            <i data-lucide="chevrons-up-down" class="profile-more-icon"></i>
        </div>
    </div>

</div>

<!-- Apple-Style Logout Confirmation Modal with Glassmorphism & Blur Overlay -->
<div class="apple-logout-modal-backdrop" id="appleLogoutModal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="apple-logout-card" role="document">
        
        <!-- Apple Close Pill Button -->
        <button type="button" class="apple-modal-close-btn" id="closeAppleLogoutBtn" aria-label="Close dialog">
            <i data-lucide="x" style="width: 15px; height: 15px;"></i>
        </button>

        <!-- Top Profile Avatar Pill with Danger Badge -->
        <div class="apple-modal-avatar-area">
            <div class="apple-avatar-halo">
                <img src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="User Avatar" class="apple-modal-avatar">
                <div class="apple-avatar-badge">
                    <i data-lucide="log-out" style="width: 13px; height: 13px;"></i>
                </div>
            </div>
            <h5 class="apple-modal-title">Sign Out?</h5>
            <p class="apple-modal-desc">
                Are you sure you want to log out of <strong>Aspire Learners</strong>? You will need to sign back in to continue your session.
            </p>
        </div>

        <!-- User Identity Chip -->
        <div class="apple-user-identity-chip">
            <div class="apple-chip-avatar">
                <img src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="Avatar" width="32" height="32" style="border-radius: 50%; object-fit: cover;">
            </div>
            <div class="apple-chip-meta">
                <span class="apple-chip-name">{{ auth()->user()->name ?? 'Admin User' }}</span>
                <span class="apple-chip-email">{{ auth()->user()->email ?? 'admin@aspire.com' }}</span>
            </div>
            <span class="apple-chip-badge">Admin</span>
        </div>

        <!-- Apple Action Buttons -->
        <div class="apple-modal-actions">
            <button type="button" class="apple-btn-cancel" id="cancelAppleLogoutBtn">
                <span>Cancel</span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="m-0 flex-grow-1">
                @csrf
                <button type="submit" class="apple-btn-logout" id="confirmAppleLogoutBtn">
                    <i data-lucide="log-out" style="width: 15px; height: 15px;"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>

    </div>
</div>

<!-- Modern Sidebar Interactions Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const searchInput = document.getElementById('sidebarSearch');
        const searchBox = document.getElementById('searchBoxContainer');
        const searchEmptyState = document.getElementById('searchEmptyState');
        const mainMenuList = document.getElementById('mainMenuList');
        const profileCard = document.getElementById('profileCard');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const COLLAPSED_KEY = 'admin-sidebar-collapsed';

        function setSidebarCollapsedState(collapsed) {
            if (collapsed) {
                document.body.classList.add('sidebar-collapsed');
                localStorage.setItem(COLLAPSED_KEY, 'true');
            } else {
                document.body.classList.remove('sidebar-collapsed');
                localStorage.setItem(COLLAPSED_KEY, 'false');
                setTimeout(() => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }, 250);
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                setSidebarCollapsedState(!isCollapsed);
            });
        }

        // Submenu Accordions
        const submenuToggles = document.querySelectorAll('.submenu-toggle');
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const parentWrapper = this.closest('.menu-item-wrapper.has-submenu');
                const isCurrentlyOpen = parentWrapper.classList.contains('open');

                if (document.body.classList.contains('sidebar-collapsed') && window.innerWidth >= 1024) {
                    setSidebarCollapsedState(false);
                    parentWrapper.classList.add('open');
                    this.setAttribute('aria-expanded', 'true');
                    return;
                }

                if (isCurrentlyOpen) {
                    parentWrapper.classList.remove('open');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    parentWrapper.classList.add('open');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        });

        // Search Filter in Sidebar
        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                const query = e.target.value.toLowerCase().trim();
                const items = mainMenuList.querySelectorAll('li.menu-item-wrapper');
                let matchCount = 0;

                if (query.length > 0 && document.body.classList.contains('sidebar-collapsed') && window.innerWidth >= 1024) {
                    setSidebarCollapsedState(false);
                }

                items.forEach(item => {
                    if (item.classList.contains('has-submenu')) {
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
                            if (query.length > 0) item.classList.add('open');
                        } else {
                            item.style.display = 'none';
                        }
                    } else {
                        const itemTitle = item.dataset.title.toLowerCase();
                        if (itemTitle.includes(query) || query.length === 0) {
                            item.style.display = 'block';
                            matchCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });

                if (matchCount === 0 && query.length > 0) {
                    searchEmptyState.style.display = 'block';
                } else {
                    searchEmptyState.style.display = 'none';
                }
            });

            // Shortcut '/' to focus search
            document.addEventListener('keydown', function (e) {
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) return;
                if (e.key === '/') {
                    e.preventDefault();
                    if (document.body.classList.contains('sidebar-collapsed')) {
                        setSidebarCollapsedState(false);
                    }
                    searchInput.focus();
                }
            });
        }

        // Mobile drawer handlers
        document.querySelectorAll('#adminMobileMenuToggle, .menu-toggle').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                sidebar.classList.toggle('mobile-open');
                if (sidebarOverlay) sidebarOverlay.classList.toggle('visible');
            });
        });

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('visible');
            });
        }

        // --- Apple-Style Logout Modal Interaction ---
        const profileSection = document.getElementById('sidebarProfileSection') || profileCard;
        const appleLogoutModal = document.getElementById('appleLogoutModal');
        const closeAppleLogoutBtn = document.getElementById('closeAppleLogoutBtn');
        const cancelAppleLogoutBtn = document.getElementById('cancelAppleLogoutBtn');

        function openLogoutModal() {
            if (!appleLogoutModal) return;
            appleLogoutModal.classList.add('is-visible');
            appleLogoutModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            if (profileSection) {
                profileSection.setAttribute('aria-expanded', 'true');
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            // Auto focus cancel button for accessibility
            setTimeout(() => {
                if (cancelAppleLogoutBtn) cancelAppleLogoutBtn.focus();
            }, 50);
        }

        function closeLogoutModal() {
            if (!appleLogoutModal) return;
            appleLogoutModal.classList.remove('is-visible');
            appleLogoutModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            if (profileSection) {
                profileSection.setAttribute('aria-expanded', 'false');
                profileSection.focus();
            }
        }

        if (profileSection) {
            profileSection.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openLogoutModal();
            });

            // Keyboard support (Enter / Space)
            profileSection.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openLogoutModal();
                }
            });
        }

        if (closeAppleLogoutBtn) {
            closeAppleLogoutBtn.addEventListener('click', function (e) {
                e.preventDefault();
                closeLogoutModal();
            });
        }

        if (cancelAppleLogoutBtn) {
            cancelAppleLogoutBtn.addEventListener('click', function (e) {
                e.preventDefault();
                closeLogoutModal();
            });
        }

        if (appleLogoutModal) {
            appleLogoutModal.addEventListener('click', function (e) {
                // If user clicks the backdrop overlay outside the card
                if (e.target === appleLogoutModal) {
                    closeLogoutModal();
                }
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && appleLogoutModal && appleLogoutModal.classList.contains('is-visible')) {
                closeLogoutModal();
            }
        });
    });
</script>