@extends('layouts.app')
@push('title')
    {{ __('Email') }} -
@endpush
@section('content')
    <div id="headless-wrapper">
        <section class="sign-up-page bg-white">
            <div class="container-fluid p-0">
                <div class="row sign-up-page-wrap-row">
                    <div class="col-md-6">
                        <div class="sign-up-right-content bg-white">
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-25 sign-up-top-logo">
                                    <a href="/">
                                        <span class="logo-lg">
                                            <img src="{{ getSettingImage('app_logo') }}">
                                        </span>
                                    </a>
                                </div>
                                <h2 class="mb-25">{{ __('Reset Password') }}</h2>
                                <div class="row mb-25">
                                    <div class="col-md-12 mb-3">
                                        @if (session('status'))
                                            <strong class="text-success">{{ session('status') }}</strong>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <label
                                            class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}</label>
                                        <input type="text" name="email" class="form-control"
                                            value="{{ old('email') }}" placeholder="{{ __('Email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-25">
                                    <div class="col-md-12">
                                        <button type="submit"
                                            class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100"
                                            title="{{ __('Send Password Reset Link') }}">{{ __('Send Password Reset Link') }}</button>
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
