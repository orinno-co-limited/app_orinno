@extends('layouts.app')
@push('title')
    {{ __('Login') }} -
@endpush
@push('style')
    <style>
        .auth-topbar .sign-up-top-logo {
            display: inline-flex;
        }

        .auth-signup-pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 22px;
            border: 1px solid var(--border-color-2);
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            color: var(--heading-color) !important;
            white-space: nowrap;
        }

        .auth-signup-pill:hover {
            border-color: var(--primary-color);
            background-color: var(--primary-color-transparent);
            color: var(--primary-color) !important;
        }

        .auth-subtitle {
            color: var(--body-font-color);
        }
    </style>
@endpush
@section('content')
    <div id="headless-wrapper">
        <section class="sign-up-page bg-white">
            <div class="container-fluid p-0">
                <div class="row sign-up-page-wrap-row">
                    <div class="col-md-6">
                        <div class="sign-up-right-content bg-white">
                            <form action="{{ route('login') }}" method="post">
                                @csrf
                                <div class="mb-30 d-flex align-items-center justify-content-between auth-topbar">
                                    <a href="/" class="sign-up-top-logo mb-0">
                                        <span class="logo-lg">
                                            <img src="{{ getSettingImage('app_logo') }}">
                                        </span>
                                    </a>
                                    <a href="{{ route('owner.register.form') }}" class="auth-signup-pill"
                                        title="{{ __('Sign Up') }}">{{ __('Sign Up') }}</a>
                                </div>
                                <h1 class="mb-2">{{ __('Sign in') }}</h1>
                                <p class="font-16 mb-30 auth-subtitle">{{ __('Welcome back! Please enter your details to continue.') }}</p>
                                <div class="row mb-25">
                                    <div class="col-md-12">
                                        <label
                                            class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}</label>
                                        <input type="text" name="email" class="form-control email"
                                            placeholder="{{ __('Email') }}">
                                    </div>
                                </div>
                                <div class="row mb-25">
                                    <div class="col-md-12">
                                        <label
                                            class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}</label>
                                        <div class="form-group mb-0 position-relative">
                                            <input class="form-control password" name="password"
                                                placeholder="{{ __('Password') }}" type="password">
                                            <span class="toggle cursor fas fa-eye pass-icon"></span>
                                        </div>
                                    </div>
                                </div>
                                @if (getOption('GOOGLE_RECAPTCHA_MAIL_STATUS', 0) == ACTIVE)
                                    <div class="row mb-25">
                                        <div class="col-md-12">
                                            <div class="g-recaptcha"
                                                data-sitekey="{{ getOption('GOOGLE_RECAPTCHA_KEY') }}">
                                            </div>
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span
                                                    class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-25">
                                    <div class="col-md-6">
                                        <div>
                                            <div class="form-group custom-checkbox" title="{{ __('Remember Me') }}">
                                                <input type="checkbox" id="rememberMe" name="remember" value="1">
                                                <label class="fw-normal" for="rememberMe">{{ __('Remember Me') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"><a href="{{ route('password.request') }}"
                                            class="theme-link d-block text-start text-md-end"
                                            title="{{ __('Forgot Password?') }}">{{ __('Forgot Password?') }}</a></div>
                                </div>
                                <div class="row mb-25">
                                    <div class="col-md-12">
                                        <button type="submit"
                                            class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100"
                                            title="{{ __('Sign In') }}">{{ __('Sign In') }}</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (env('LOGIN_HELP') == 'active')
                                            <div class="table-responsive login-info-table mt-3">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2" id="adminCredentialShow" class="login-info">
                                                                <b>Admin:</b> admin@gmail.com | 123456  <strong></strong>
                                                                <span class="badge bg-danger "><a href="{{LINK_SAAS_ADDON}}" target="_blank" style="color: white">{{ __('SAAS Addon') }}</a></span>

                                                                {{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <td colspan="2" id="adminCredentialShow" class="login-info">--}}
                                                                <p class="font-16 pt-2">
                                                                    <a  href="/" class="secondary-color font-medium">SAAS Landing Page</a>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" id="ownerCredentialShow" class="login-info">
                                                                <b>Owner:</b> owner@gmail.com | 123456
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" id="tenantCredentialShow" class="login-info">
                                                                <b>Tenant:</b> tenant@gmail.com | 123456
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" id="maintainerCredentialShow"
                                                                class="login-info">
                                                                <b>Maintainer:</b> maintainer@gmail.com | 123456
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sign-up-left-content position-relative text-center">
                            <div class="sign-up-bottom-img mb-25">
                                <img src="{{ getSettingImage('sign_in_image') }}" alt="{{ getOption('app_name') }}"
                                    class="img-fluid">
                            </div>
                            <h1 class="text-white">{{ __(getOption('sign_in_text_title')) }}</h1>
                            <p class="mt-25 w-75 mx-auto">{{ __(getOption('sign_in_text_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        "use strict"
        $('#adminCredentialShow').on('click', function() {
            $('.email').val('admin@gmail.com');
            $('.password').val('123456');
        });
        $('#ownerCredentialShow').on('click', function() {
            $('.email').val('owner@gmail.com');
            $('.password').val('123456');
        });
        $('#tenantCredentialShow').on('click', function() {
            $('.email').val('tenant@gmail.com');
            $('.password').val('123456');
        });
        $('#maintainerCredentialShow').on('click', function() {
            $('.email').val('maintainer@gmail.com');
            $('.password').val('123456');
        });
    </script>
@endpush
