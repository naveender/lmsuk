@php
    $theme = session('theme', 'light');
    $user = auth()->user();

    $mathSubject = \App\Models\Subject::where('title', 'like', '%math%')->first();
    $englishSubject = \App\Models\Subject::where('title', 'like', '%english%')->first();
    $vrSubject = \App\Models\Subject::where('title', 'like', '%verbal%')->first();

    $mathUrl = $mathSubject ? route('student.videolessonslist', ['subject_id' => $mathSubject->id]) : route('student.videolessonscategories');
    $englishUrl = $englishSubject ? route('student.videolessonslist', ['subject_id' => $englishSubject->id]) : route('student.videolessonscategories');
    $vrUrl = $vrSubject ? route('student.videolessonslist', ['subject_id' => $vrSubject->id]) : route('student.videolessonscategories');

    $isLessonsActive = request()->routeIs('student.videolessonscategories') || request()->routeIs('student.videolessonslist');
    $currentSubjectId = request('subject_id');
@endphp

<!-- Mobile Overlay Backdrop -->
<div class="sidebar-overlay-backdrop" id="studentSidebarOverlay"></div>

<!-- BEGIN: Student Main Menu -->
<div class="main-menu menu-fixed modern-sidebar student-sidebar" id="studentSidebar">

    <!-- Sidebar Header (Logo) -->
    <div class="sidebar-header student-sidebar-header text-center">
        <a href="{{ route('student.dashboard') }}" class="sidebar-logo-link justify-content-center w-100">
            <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Full-Logo.png') }}"
                 alt="Aspire Learners" class="student-logo-img">
        </a>
    </div>

    <!-- Scrollable Menu Content -->
    <div class="sidebar-menu-content student-menu-content">
        <ul class="menu-items" id="studentMenuList">

            <!-- 1. Dashboard -->
            <li class="menu-item-wrapper {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" data-title="Dashboard">
                <a href="{{ route('student.dashboard') }}" class="menu-link">
                    <i class="feather icon-home"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- 2. Lessons (with expandable submenu) -->
            <li class="menu-item-wrapper has-submenu {{ $isLessonsActive ? 'open' : '' }}" data-title="Lessons">
                <a href="javascript:void(0);" class="menu-link submenu-toggle" id="studentLessonsToggle">
                    <i class="feather icon-book-open"></i>
                    <span class="menu-text">Lessons</span>
                    <i class="feather icon-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu-items student-submenu">
                    <!-- English -->
                    <li class="{{ (request()->routeIs('student.videolessonslist') && $englishSubject && $currentSubjectId == $englishSubject->id) ? 'active' : '' }}">
                        <a href="{{ $englishUrl }}" class="menu-link">
                            <span class="subject-dot dot-red"></span>
                            <span class="menu-text">English</span>
                        </a>
                    </li>
                    <!-- Maths -->
                    <li class="{{ (request()->routeIs('student.videolessonslist') && $mathSubject && $currentSubjectId == $mathSubject->id) ? 'active' : '' }}">
                        <a href="{{ $mathUrl }}" class="menu-link">
                            <span class="subject-dot dot-blue"></span>
                            <span class="menu-text">Maths</span>
                        </a>
                    </li>
                    <!-- Verbal Reasoning -->
                    <li class="{{ (request()->routeIs('student.videolessonslist') && $vrSubject && $currentSubjectId == $vrSubject->id) ? 'active' : '' }}">
                        <a href="{{ $vrUrl }}" class="menu-link">
                            <span class="subject-dot dot-yellow"></span>
                            <span class="menu-text">Verbal Reasoning</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 3. Analytics -->
            <li class="menu-item-wrapper {{ request()->routeIs('student.analytics*') ? 'active' : '' }}" data-title="Analytics">
                <a href="{{ route('student.analytics') }}" class="menu-link">
                    <i class="feather icon-bar-chart-2"></i>
                    <span class="menu-text">Analytics</span>
                </a>
            </li>

            <!-- 4. Assessments -->
            <li class="menu-item-wrapper {{ (request()->routeIs('student.assessments*') || request()->routeIs('student.attempts*')) ? 'active' : '' }}" data-title="Assessments">
                <a href="{{ route('student.assessments') }}" class="menu-link">
                    <i class="feather icon-target"></i>
                    <span class="menu-text">Assessments</span>
                </a>
            </li>

            <!-- 5. Focus Areas -->
            <li class="menu-item-wrapper {{ request()->routeIs('student.focusareas*') ? 'active' : '' }}" data-title="Focus Areas">
                <a href="{{ route('student.focusareas') }}" class="menu-link">
                    <i class="feather icon-crosshair"></i>
                    <span class="menu-text">Focus Areas</span>
                </a>
            </li>

            <!-- 6. Centre Test Scores -->
            <li class="menu-item-wrapper {{ request()->routeIs('student.centretestscores*') ? 'active' : '' }}" data-title="Centre Test Scores">
                <a href="{{ route('student.centretestscores') }}" class="menu-link">
                    <i class="feather icon-award"></i>
                    <span class="menu-text">Centre Test Scores</span>
                </a>
            </li>

            <!-- 7. Weekly Test -->
            <li class="menu-item-wrapper {{ request()->routeIs('student.weeklytests*') ? 'active' : '' }}" data-title="Weekly Test">
                <a href="{{ route('student.weeklytests') }}" class="menu-link">
                    <i class="feather icon-calendar"></i>
                    <span class="menu-text">Weekly Test</span>
                </a>
            </li>

            <!-- 8. Announcements -->
            <li class="menu-item-wrapper {{ request()->routeIs('student.announcements*') ? 'active' : '' }}" data-title="Announcements">
                <a href="{{ route('student.announcements') }}" class="menu-link">
                    <i class="feather icon-volume-2"></i>
                    <span class="menu-text">Announcements</span>
                </a>
            </li>

        </ul>
    </div>

    <!-- Bottom User Profile Card -->
    <div class="student-sidebar-footer">
        <div class="student-profile-strip">
            <div class="d-flex align-items-center">
                <img src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                     alt="{{ $user->name ?? 'Student' }}" class="student-strip-avatar">
                <div class="student-strip-meta">
                    <span class="student-strip-name">{{ $user->name ?? 'Student User' }}</span>
                    <span class="student-strip-role">Student</span>
                </div>
                <a href="{{ route('account.index') }}" class="student-strip-settings ml-auto" title="Account Settings">
                    <i class="feather icon-settings"></i>
                </a>
            </div>
        </div>

        <!-- Decorative Ribbon & Slogan -->
        <div class="student-sidebar-brand-wave">
            <svg class="brand-wave-svg" viewBox="0 0 260 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Golden Yellow Wave -->
                <path d="M0 28C45 28 80 12 140 22C195 31 230 18 260 20V42H0V28Z" fill="#FBDA33" fill-opacity="0.85"/>
                <!-- Vibrant Red Wave -->
                <path d="M0 32C50 32 95 18 160 26C210 33 240 24 260 25V42H0V32Z" fill="#FA2833" fill-opacity="0.9"/>
                <!-- Vibrant Blue Wave -->
                <path d="M0 36C60 36 110 24 175 30C225 35 245 28 260 30V42H0V36Z" fill="#2477F9" fill-opacity="0.95"/>
            </svg>
            <div class="brand-slogan-wrap">
                <span class="brand-slogan-text">Learn &middot; Practice &middot; Grow</span>
            </div>
        </div>
    </div>

</div>

<!-- Submenu Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggle = document.getElementById('studentLessonsToggle');
        if (toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                var parent = toggle.closest('.has-submenu');
                if (parent) {
                    parent.classList.toggle('open');
                }
            });
        }

        // Mobile drawer backdrop click
        var overlay = document.getElementById('studentSidebarOverlay');
        if (overlay) {
            overlay.addEventListener('click', function() {
                var sb = document.getElementById('studentSidebar');
                if (sb) sb.classList.remove('mobile-open');
                overlay.classList.remove('visible');
                document.body.classList.remove('sidebar-open-no-scroll');
            });
        }
    });
</script>
