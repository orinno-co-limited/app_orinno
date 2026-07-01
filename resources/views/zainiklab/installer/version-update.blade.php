<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @if (isset($_SERVER['HTTPS']))
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
    <title>Update Your Application </title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('zaifiles/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('zaifiles/assets/style.css') }}">
</head>

<body>
    @yield('preloader')

    <div class="overlay-wrap">
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
                            <form action="{{ route('process-update') }}" method="POST">
                                @csrf
                                @if (config('app.build_version') == getOption('build_version'))
                                    <p class="me-2 my-2"><span class="text-danger">*</span>Your application is upto date
                                    </p>
                                @else
                                    <p class="me-2 mb-2"><span class="text-danger">*</span> New version
                                        {{ config('app.current_version') }} </p>
                                    <p class="me-2 mb-2"><span class="text-danger">*</span> Current version
                                        {{ getOption('current_version', '1.0') }} </p>
                                    <p class="me-2 mb-2"><span class="text-danger">*</span> Download your database and
                                        present script to avoid any errors. (Safety first) </p>
                                    <p class="me-2 mb-2"><span class="text-danger">*</span> Please click Update now
                                        button, may its need sometime</p>
                                    <div class="mt-3">
                                        <div class="single-section">
                                            <h4 class="section-title mb-2">Please enter your Item purchase code and
                                                customer email</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Customer E-mail</label>
                                                        <input type="email" class="form-control" id="email"
                                                            name="email" value="{{ old('email') }}"
                                                            placeholder="example@example.com" />
                                                    </div>
                                                    @if ($errors->has('email'))
                                                        <div class="error text-danger">{{ $errors->first('email') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="purchase_code">Item purchase code</label>
                                                        <input type="text" class="form-control" id="purchase_code"
                                                            name="purchase_code" value="{{ old('purchase_code') }}"
                                                            placeholder="31200164-dd02-49ea-baef-3865c90acc123" />
                                                    </div>
                                                    @if ($errors->has('purchase_code'))
                                                        <div class="error text-danger">
                                                            {{ $errors->first('purchase_code') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <button class="primary-btn next" id="submitNext" type="submit">Update
                                            Now</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('frontend/assets/vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

    @stack('script')
</body>

</html>
