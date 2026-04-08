<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Aspire Learners')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
    @include('partials.includetop')
</head>

@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $theme = session('theme', 'dark'); // default dark
    $themeLayoutClass = $theme . '-layout'; // dark-layout / light-layout
@endphp

<body
    class="{{ $isAdmin 
        ? 'vertical-layout vertical-menu-modern ' . $themeLayoutClass . ' 2-columns navbar-floating footer-static' 
        : 'horizontal-layout horizontal-menu dark-layout 2-columns navbar-floating footer-static' }}"
    data-open="{{ $isAdmin ? 'click' : 'hover' }}"
    data-menu="{{ $isAdmin ? 'vertical-menu-modern' : 'horizontal-menu' }}"
    data-col="2-columns"
    data-layout="{{ $themeLayoutClass }}"
>

    @include('partials.header')
  

    @if (auth()->user()?->role === 'admin')
        @include('partials.sidebar')
    @else
        @include('partials.horizontalbar')
    @endif

    @yield('content')

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>


    @include('partials.footer')
    @include('partials.includebottom')


    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
