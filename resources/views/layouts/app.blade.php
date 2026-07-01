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

    <meta name="msapplication-TileImage" content="assets/img/logo.png">

    <meta name="msapplication-TileColor" content="#F8F8F8">
    <meta name="theme-color" content="#3686FC">

    <title>@stack('title' ?? '') {{ getOption('app_name') }}</title>

    <link href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/') }}assets/libs/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/') }}assets/css/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/') }}assets/libs/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}assets/libs/owl-carousel/owl.theme.default.min.css">

    <link rel="stylesheet" href="{{ asset('/') }}assets/libs/venobox/venobox.min.css">

    <link href="{{ asset('/') }}assets/css/icons.min.css" rel="stylesheet">
    <link href="{{ asset('/') }}assets/css/style.css" rel="stylesheet">

    <link href="{{ asset('/') }}assets/css/extra-style.css" rel="stylesheet">

    <!-- RTL Style Start -->
    @if (selectedLanguage()->rtl == 1)
        <link href="{{ asset('/') }}assets/css/rtl-style.css" rel="stylesheet">
    @endif
    <!-- RTL Style End -->

    <link rel="stylesheet" href="{{ asset('/') }}assets/css/responsive.css">

    <!-- FAVICONS -->
    <link rel="icon" href="{{ getSettingImage('app_fav_icon') }}.png" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
    @stack('style')
    <style>
        :root {
            @if (getOption('website_color_mode', 0) == ACTIVE)
                --primary-color: {{ getOption('website_primary_color', '#3686FC') }};
                --secondary-color: {{ getOption('website_secondary_color', '#8253FB') }};
                --button-primary-color: {{ getOption('button_primary_color', '#3686FC') }};
                --button-hover-color: {{ getOption('button_hover_color', '#0063E6') }};
            @else
                --primary-color: #3686FC;
                --secondary-color: #8253FB;
                --button-primary-color: #3686FC;
                --button-hover-color: #0063E6;
            @endif
        }
    </style>
</head>

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">
    @if (getOption('app_preloader_status') == 1)
        <div id="preloader">
            <div id="preloaderInner"><img src="{{ getSettingImage('app_preloader') }}" alt="img"></div>
        </div>
    @endif

    @yield('content')

    @if(session('payment_form_html'))
        <div id="virtualPaymentForm" style="display: none;">
            {!! session('payment_form_html') !!}
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Find the form inside the hidden div and submit it automatically
                var virtualForm = document.getElementById('virtualPaymentForm').querySelector('form');
                if (virtualForm) {
                    virtualForm.submit();
                }
            });
        </script>
    @endif
    <script src="{{ asset('/') }}assets/libs/jquery/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/jquery-easing/jquery.easing.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/jquery-ui/jquery-ui.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/owl-carousel/owl.carousel.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/venobox/venobox.min.js"></script>
    <script src="{{ asset('/') }}assets/js/iconify.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{ asset('/') }}assets/libs/simplebar/simplebar.min.js"></script>

    <script src="{{ asset('/') }}assets/js/custom.js"></script>
    @stack('script')

    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        @if (Session::has('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if (Session::has('error'))
            toastr.error("{{ session('error') }}");
        @endif
        @if (Session::has('info'))
            toastr.info("{{ session('info') }}");
        @endif
        @if (Session::has('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    @if (@$errors->any())
        <script>
            "use strict";
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        </script>
    @endif
    <input type="hidden" id="deleteConfirmButtonText" value="{{__('Yes, Delete It!')}}">
    <input type="hidden" id="cancelButtonText" value="{{__('Cancel')}}">
</body>

</html>
