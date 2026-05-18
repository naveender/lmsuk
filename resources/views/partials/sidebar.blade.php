<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-{{ $theme}} menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('dashboard') }}" style="margin-top: 10px;">
                    <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Horizontal-Full-Logo.png') }}"
                        alt="Aspire Learner Logo" style="max-width: 180px; height: auto; object-fit: contain;">
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i
                        class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"
                        data-ticon="icon-disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }} nav-item">
                <a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i><span class="menu-title"
                        data-i18n="Email">Dashboard</span></a>
            </li>
            <li
                class="{{ request()->routeIs('admin.users.*') || request()->routeIs('admin.students.*') || request()->routeIs('admin.parents.*') ? 'active' : '' }} nav-item">
                <a href="#"><i class="feather icon-user"></i><span class="menu-title"
                        data-i18n="User">User</span></a>
                <ul class="menu-content">

                    <li class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}"><a
                            href="{{ route('admin.users.create') }}"><i class="feather icon-circle"></i><span
                                class="menu-item" data-i18n="Add New User">Add New User</span></a>
                    </li>
                    <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}"><a
                            href="{{ route('admin.users.index') }}"><i class="feather icon-circle"></i><span
                                class="menu-item" data-i18n="All Users">All Users</span></a>
                    </li>
                    <li class="{{ request()->routeIs('admin.students.index') ? 'active' : '' }}"><a
                            href="{{ route('admin.students.index') }}"><i class="feather icon-circle"></i><span
                                class="menu-item" data-i18n="Manage Students">Manage Students</span></a>
                    </li>
                    <li class="{{ request()->routeIs('admin.parents.index') ? 'active' : '' }}"><a
                            href="{{ route('admin.parents.index') }}"><i class="feather icon-circle"></i><span
                                class="menu-item" data-i18n="Manage Parents">Manage Parents</span></a>
                    </li>
                </ul>
            </li>
            <li
                class="{{ request()->routeIs('admin.questions.*') ? 'active' : '' }} nav-item"><a href="#"><i class="feather icon-help-circle"></i><span class="menu-title"
                        data-i18n="Question Bank">Question Bank</span></a>
                <ul class="menu-content">

                    <li class="{{ request()->routeIs('admin.questions.create') ? 'active' : '' }}"><a
                            href="{{ route('admin.questions.create') }}"><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="Add New Question">Add New Question</span></a>
                    </li>
                    <li class="{{ request()->routeIs('admin.questions.index') ? 'active' : '' }}"><a
                            href="{{ route('admin.questions.index') }}"><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="Questions Directory">Questions Directory</span></a>
                    </li>
                    <li><a href="#"><i
                                class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="Import Questions">Import Questions</span></a>
                    </li>
                    <li><a href=""><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="Import Using AI">Import Using AI</span></a>
                    </li>
                </ul>
            </li>
            {{-- <li class="nav-item">
                <a href="#"><i class="feather icon-upload"></i><span class="menu-title"
                        data-i18n="Questions Manager">Questions Manager</span></a>
            </li> --}}

            <li class="nav-item">
                <a href="#"><i class="feather icon-bar-chart-2"></i><span class="menu-title"
                        data-i18n="Create a Report">Create a Report</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="feather icon-file"></i><span class="menu-title"
                        data-i18n="Manage Files">Manage Files</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="feather icon-file-text"></i><span class="menu-title"
                        data-i18n="Invoice Creater">Invoice Creater</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="feather icon-pie-chart"></i><span class="menu-title"
                        data-i18n="Cohort Report"> Cohort Report</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.announcements.index') }}"><i class="feather icon-message-square"></i><span
                        class="menu-title" data-i18n="Manage Announcement">Manage Announcement</span></a>
            </li>
            <li class=" nav-item"><a href="#"><i class="feather icon-list"></i><span class="menu-title"
                        data-i18n="User">Content Manager</span></a>
                <ul class="menu-content">

                    <li class="{{ request()->routeIs('admin.classes.index') ? 'active' : '' }}"><a
                            href="{{ route('admin.classes.index') }}"><i class="feather icon-circle"></i><span
                                class="menu-item" data-i18n="">Classes</span></a>
                    </li>
                    <li class="{{ request()->routeIs('admin.year-groups.*') ? 'active' : '' }}"><a
                            href="{{ route('admin.year-groups.index') }}"><i class="feather icon-circle"></i><span
                                class="menu-item" data-i18n="">Year Groups</span></a>
                    </li>
                    <li class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}"><a
                            href="{{ route('admin.subjects.index') }}"><i class="feather icon-circle"></i><span
                                class="menu-item" data-i18n="">Subjects</span></a>
                    </li>
                    <li class="{{ request()->routeIs('topics') ? 'active' : '' }}"><a
                            href="{{ route('topics') }}"><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="Manage Students">Topics/Sub Topics</span></a>
                    </li>
                </ul>
            </li>
            <li class=" nav-item"><a href="#"><i class="feather icon-list"></i><span class="menu-title"
                        data-i18n="User">Global Settings</span></a>
                <ul class="menu-content">
                    <li class="#"><a href="#"><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="">Tests/Exam Settings</span></a>
                    </li>
                   
                </ul>
            </li>
           
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
