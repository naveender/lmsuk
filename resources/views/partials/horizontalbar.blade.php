<!-- BEGIN: Main Menu-->
<div class="horizontal-menu-wrapper">
    <div class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-dark navbar-without-dd-arrow navbar-shadow menu-border"
        role="navigation" data-menu="menu-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="/" style="padding: 15px 0;">
                        <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Horizontal-Full-Logo.png') }}"
                            alt="Aspire Learner Logo" style="max-width: 160px; height: auto; object-fit: contain;">
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                            class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i
                            class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"
                            data-ticon="icon-disc"></i></a></li>
            </ul>
        </div>
        <!-- Horizontal menu content-->
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <!-- include ../../../includes/mixins-->
            <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }} dropdown nav-item"
                    data-menu="">
                    <a class="nav-link" href="{{ route('student.dashboard') }}"><i
                            class="feather icon-activity"></i>Dashboard</a>
                </li>

                <li class="{{ request()->routeIs('student.videolessonscategories') || request()->routeIs('student.videolessonslist') ? 'active' : '' }} dropdown nav-item"
                    data-menu="dropdown">
                    <a class="dropdown-toggle nav-link" href="{{ route('student.videolessonscategories') }}" data-toggle="dropdown">
                        <i class="feather icon-package"></i>
                        <span data-i18n="videoLessons">Lessons</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li data-menu="">
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('student.videolessonscategories') }}">
                                <i class="feather icon-grid"></i>All Categories
                            </a>
                        </li>
                        @if(isset($navSubjects) && $navSubjects->isNotEmpty())
                            <div class="dropdown-divider"></div>
                            @foreach($navSubjects as $subject)
                                @php
                                    $titleLower = strtolower($subject->title);
                                    $icon = 'icon-book';
                                    if (strpos($titleLower, 'math') !== false) {
                                        $icon = 'icon-award';
                                    } elseif (strpos($titleLower, 'english') !== false) {
                                        $icon = 'icon-feather';
                                    } elseif (strpos($titleLower, 'verbal') !== false) {
                                        $icon = 'icon-cpu';
                                    }
                                @endphp
                                <li data-menu="">
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('student.videolessonslist', ['subject_id' => $subject->id]) }}">
                                        <i class="feather {{ $icon }}"></i>{{ $subject->title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </li>
                <li class="{{ request()->routeIs('student.analytics') ? 'active' : '' }} dropdown nav-item"
                    data-menu="">
                    <a class="nav-link" href="{{ route('student.analytics') }}"><i
                            class="feather icon-activity"></i>Analytics</a>
                </li>
                <li class="{{ request()->routeIs('student.assessments') ? 'active' : '' }} dropdown nav-item"
                    data-menu="">
                    <a class="nav-link" href="{{ route('student.assessments') }}"><i
                            class="feather icon-activity"></i>Assessments</a>
                </li>
                <li class="{{ request()->routeIs('student.focusareas') ? 'active' : '' }} dropdown nav-item"
                    data-menu="">
                    <a class="nav-link" href="{{ route('student.focusareas') }}"><i
                            class="feather icon-activity"></i>Focus Areas</a>
                </li>

                <li class="{{ request()->routeIs('student.announcements') ? 'active' : '' }} dropdown nav-item"
                    data-menu="">
                    <a class="nav-link" href="{{ route('student.announcements') }}"><i
                            class="feather icon-activity"></i>Announcements</a>
                </li>
                <li class="{{ request()->routeIs('student.centretestscores') ? 'active' : '' }} dropdown nav-item"
                    data-menu="">
                    <a class="nav-link" href="{{ route('student.centretestscores') }}"><i
                            class="feather icon-activity"></i>Center Test Scores</a>
                </li>
                <li class="{{ request()->routeIs('student.weeklytests') ? 'active' : '' }} dropdown nav-item"
                    data-menu="">
                    <a class="nav-link" href="{{ route('student.weeklytests') }}"><i
                            class="feather icon-activity"></i>Weekly Test</a>
                </li>




            </ul>
        </div>
    </div>
</div>
<!-- END: Main Menu-->