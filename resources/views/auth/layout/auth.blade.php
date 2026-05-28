<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>@yield('title', 'Authenticate')</title>
    <link rel="apple-touch-icon" href="{{ asset('theme/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" href="{{ asset('theme/app-assets/vendors/css/vendors.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/themes/semi-dark-layout') }}">
    <!-- END: Theme CSS-->


    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/pages/authentication.css') }}">
    <!-- END: Page CSS-->
</head>
<!-- END: Head-->

<body
    class="vertical-layout vertical-menu-modern semi-dark-layout 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page"
    data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-layout="semi-dark-layout">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">

            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('theme/app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('theme/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('theme/app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('theme/app-assets/js/scripts/components.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @stack('scripts')
</body>

</html>
