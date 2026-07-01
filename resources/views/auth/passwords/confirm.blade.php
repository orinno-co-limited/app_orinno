@extends('layouts.app')
@push('title')
    {{ __('Confirm Password') }} -
@endpush
@section('content')
    <div class="main-content__area bg-img">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8 col-md-10">
                    <div class="authentication__item">
                        <div class="authentication__item__logo">
                            <a href="{{ route('frontend') }}">
                                <img src="{{ getSettingImage('app_logo') }}" alt="icon">
                            </a>
                        </div>
                        <div class="authentication__item__title mb-30">
                            <h2>{{ __('Confirm Password') }}</h2>
                            <h3>{{ __('Please confirm your password before continuing.') }}</h3>
                        </div>
                        <div class="authentication__item__content">
                            <form method="POST" action="{{ route('password.confirm') }}">
                                @csrf
                                <div class="input__group mb-20">
                                    <label>{{ __('Password') }}</label>
                                    <div class="input-overlay">
                                        <input id="pass" type="password" name="password" required
                                            autocomplete="current-password" placeholder="Email">
                                        <div class="overlay">
                                            <img src="{{ asset('assets') }}/images/icons/lock.svg" alt="icon">
                                        </div>
                                        <div class="password-visibility">
                                            <img src="{{ asset('assets') }}/images/icons/eye.svg" alt="icon">
                                        </div>
                                    </div>
                                    @error('password')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="item__group__between mb-20">
                                    <div class="input__group">
                                        <input type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember">{{ __('Remember me') }}</label>
                                    </div>
                                    <div class="input__group">
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}">
                                                {{ __('Forgot Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="input__group mb-27">
                                    <button type="submit" class="btn btn-blue">{{ __('Confirm Password') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
