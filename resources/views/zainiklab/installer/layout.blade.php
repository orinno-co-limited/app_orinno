<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (isset($_SERVER['HTTPS']))
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @endif
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
    <title>@yield('title') | Zai-Installer </title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('zaifiles/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('zaifiles/assets/style.css') }}">
</head>

<body>
    @yield('preloader')
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="breadcrumb-text">
                        <a class="brand-logo" href="#"><img src="{{ getSettingImage('app_logo') }}"
                                alt="logo"></a>
                        <h2>{{ getOption('app_name') }}</h2>
                        <p>{{ \Carbon\Carbon::parse(now())->format('l, j F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pre-installation-area">
        <div class="container">
            <div class="section-wrap">
                <div class="section-wrap-header">
                    <div class="progres-stype">
                        <div class="single-stype {{ Route::is('ZaiInstaller::pre-install') ? 'active' : 'finished' }}">
                            <span>Pre-Installation</span>
                        </div>
                        <div class="single-stype {{ Route::is('ZaiInstaller::pre-install') ? '' : 'active' }}">
                            <span>Configuaration</span>
                        </div>
                        <div class="single-stype">
                            <span>Finish</span>
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('frontend/assets/vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    @stack('script')
</body>

</html>
