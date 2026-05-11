<!-- BEGIN: Header-->
@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
@endphp

<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu {{ !$isAdmin ? 'navbar-fixed navbar-brand-center' : 'floating-nav navbar-dark navbar-shadow' }}">
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
                            <a class="dropdown-item" href="{{ route('edit-profile') }}"><i
                                    class="feather icon-user"></i>
                                My Account</a>
                            <a class="dropdown-item" href="{{ route('edit-profile') }}"><i
                                    class="feather icon-settings"></i>
                                Settings</a>
                            <a class="dropdown-item" href="{{ route('change.theme') }}" id="themeToggleBtn"><i
                                    class="feather icon-{{ $theme === 'dark' ? 'sun' : 'moon' }}"></i>
                                Enable {{ $theme === 'dark' ? 'Light' : 'Dark' }} Mode</a>
                            <div class="dropdown-divider"></div>
                            <!-- Logout Form -->
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

<!-- END: Header-->
