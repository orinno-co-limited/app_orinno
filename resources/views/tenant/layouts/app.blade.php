<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if (isset($_SERVER['HTTPS']))
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @endif
    <meta name="title" content="{{ getOption('app_name') }}">
    <meta name="description" content="{{ getOption('meta_description') }}">
    <meta name="keywords" content="{{ getOption('meta_keyword') }}">
    <meta name="author" content="{{ getOption('meta_author') }}">

    <meta property="og:type" content="Property">
    <meta property="og:title" content="{{ getOption('app_name') }}">
    <meta property="og:description" content="{{ getOption('meta_description') }}">
    <meta property="og:image" content="{{ getSettingImage('app_logo') }}">

    <meta name="twitter:card" content="{{ getOption('app_name') }}">
    <meta name="twitter:title" content="{{ getOption('app_name') }}">
    <meta name="twitter:description" content="{{ getOption('meta_description') }}">
    <meta name="twitter:image" content="{{ getSettingImage('app_logo') }}">

    <meta name="msapplication-TileImage" content="{{ getSettingImage('app_logo') }}">

    <meta name="msapplication-TileColor" content="#F8F8F8">
    <meta name="theme-color" content="#3686FC">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ getOption('app_name') . ' - ' . @$pageTitle }}</title>

    @include('common.layouts.style')
    @stack('style')
</head>

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">
    @if (getOption('app_preloader_status') == 1)
        <div id="preloader">
            <div id="preloaderInner"><img src="{{ getSettingImage('app_preloader') }}" alt="img"></div>
        </div>
    @endif

    <div id="layout-wrapper">
        @include('tenant.layouts.navbar')
        @include('tenant.layouts.sidebar')
        @yield('content')
    </div>

    @include('common.layouts.script')
    @stack('script')

    <!-- App Custom js -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <input type="hidden" id="deleteConfirmButtonText" value="{{__('Yes, Delete It!')}}">
    <input type="hidden" id="cancelButtonText" value="{{__('Cancel')}}">
</body>

</html>
