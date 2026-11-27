@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $isStudent = auth()->check() && auth()->user()->role === 'student';
    $theme = session('theme', request()->cookie('theme', 'light')); // default light
    if (!in_array($theme, ['light', 'dark'])) {
        $theme = 'light';
    }
    $themeLayoutClass = $theme . '-layout'; // dark-layout / light-layout
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="{{ $theme }}" class="{{ $themeLayoutClass }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aspire Learners')</title>
    
    <!-- Instant Zero-Flicker Theme Synchronization -->
    <script>
        (function() {
            try {
                var storedTheme = localStorage.getItem('theme');
                if (storedTheme === 'dark' || storedTheme === 'light') {
                    var html = document.documentElement;
                    var oldClass = (storedTheme === 'dark' ? 'light' : 'dark') + '-layout';
                    var newClass = storedTheme + '-layout';
                    html.classList.remove(oldClass);
                    html.classList.add(newClass);
                    html.setAttribute('data-theme', storedTheme);
                }
            } catch (e) {}
        })();
    </script>

    @stack('styles')
    @include('partials.includetop')
</head>

<body
    class="{{ $isAdmin 
        ? 'vertical-layout vertical-menu-modern ' . $themeLayoutClass . ' 2-columns navbar-sticky footer-static admin-theme-active' 
        : ($isStudent 
            ? 'vertical-layout vertical-menu-modern ' . $themeLayoutClass . ' 2-columns navbar-sticky footer-static student-theme-active'
            : 'horizontal-layout horizontal-menu ' . $themeLayoutClass . ' 2-columns navbar-floating footer-static') }}"
    data-open="{{ ($isAdmin || $isStudent) ? 'click' : 'hover' }}"
    data-menu="{{ ($isAdmin || $isStudent) ? 'vertical-menu-modern' : 'horizontal-menu' }}"
    data-col="2-columns"
    data-layout="{{ $themeLayoutClass }}"
>

    @include('partials.header')
  

    @if (auth()->user()?->role === 'admin')
        @include('partials.sidebar')
    @elseif (auth()->user()?->role === 'student')
        @include('partials.student-sidebar')
    @else
        @include('partials.horizontalbar')
    @endif

    @yield('content')

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>


    @include('partials.footer')
    @include('partials.includebottom')


    @stack('scripts')
</body>

</html>
