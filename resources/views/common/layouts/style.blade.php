<link href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">

<!-- Google Font CSS -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/libs/owl-carousel/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/owl-carousel/owl.theme.default.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/libs/venobox/venobox.min.css') }}">
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">

<!-- Dropzone css -->
<link href="{{ asset('assets/libs/dropzone/dropzone.css') }}" rel="stylesheet">
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
<link href="{{ asset('assets/css/style.css?v=2') }}" rel="stylesheet">
<link href="{{ asset('assets/css/extra-style.css') }}" rel="stylesheet">

<!-- RTL Style Start -->
@if (selectedLanguage()->rtl == 1)
    <link href="{{ asset('assets/css/rtl-style.css') }}" rel="stylesheet">
@endif
<!-- RTL Style End -->

<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

<!-- FAVICONS -->
<link rel="icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/png" sizes="16x16">
<link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}">


<!-- Sweetalert & Toastr -->
<link rel="stylesheet" href="{{asset('assets/sweetalert2/sweetalert2.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dropify.css') }}">

<!-- Select2 -->
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
