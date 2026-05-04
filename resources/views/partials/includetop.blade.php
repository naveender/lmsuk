<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" href="{{ asset('theme/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/vendors/css/charts/apexcharts.css') }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('theme/app-assets/vendors/css/tables/datatable/extensions/dataTables.checkboxes.css') }}">
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
@php
    $menuType = auth()->user()?->role === 'admin' ? 'vertical' : 'horizontal';
@endphp

<link rel="stylesheet" href="{{ asset("theme/app-assets/css/core/menu/menu-types/{$menuType}-menu.css") }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/css/plugins/file-uploaders/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/css/core/colors/palette-gradient.css') }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/css/pages/authentication.css') }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/css/pages/dashboard-ecommerce.css') }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/css/pages/card-analytics.css') }}">
<link rel="stylesheet" href="{{ asset('theme/app-assets/css/pages/data-list-view.css') }}">

<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" href="{{ asset('theme/app-assets/css/style.css') }}">