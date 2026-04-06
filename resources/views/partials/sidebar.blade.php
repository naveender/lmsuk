<!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="../html/backup-dashboard.html">
                        <!-- <div class="brand-logo"></div> -->
                        <div>
                            <i class="feather icon-upload-cloud"></i>
                        </div>
                        <h2 class="brand-text mb-0">Backup System</h2>
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
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }} nav-item">
                    <a href="{{ route('dashboard') }}"><i class="feather icon-home"></i><span class="menu-title"
                            data-i18n="Email">Project Dashboard</span></a>
                </li>
                <li class="{{ request()->routeIs('inventory') ? 'active' : '' }} nav-item">
                    <a href="{{ route('inventory') }}"><i class="feather icon-server"></i><span class="menu-title"
                            data-i18n="Email">Backup Inventory</span></a>
                </li>
                <li class="{{ request()->routeIs('manual-backup-manager') ? 'active' : '' }} nav-item">
                    <a href="{{ route('manual-backup-manager') }}"><i class="feather icon-upload"></i><span class="menu-title"
                            data-i18n="Email">Manual Backup</span></a>
                </li>
                <li class="{{ request()->routeIs('settings.index') ? 'active' : '' }} nav-item">
                    <a href="{{ route('settings.index') }}"><i class="feather icon-settings"></i><span class="menu-title"
                            data-i18n="Email">Settings</span></a>
                </li>
                <li class="{{ request()->routeIs('logs') ? 'active' : '' }} nav-item">
                    <a href="{{ route('logs') }}"><i class="feather icon-file-text"></i><span class="menu-title"
                            data-i18n="Email">Logs</span></a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->