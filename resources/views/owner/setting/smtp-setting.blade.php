@extends('owner.layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="page-content-wrapper bg-white p-30 radius-20">
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between border-bottom mb-20">
                                <div class="page-title-left">
                                    <h3 class="mb-sm-0">{{ __('Settings') }}</h3>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item"><a href="#"
                                                title="Settings">{{ __('Settings') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ __('Smtp Setting') }}
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-page-layout-wrap position-relative">
                        <div class="row">
                            @include('owner.setting.sidebar')
                            <div class="col-md-12 col-lg-12 col-xl-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <div class="language-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ $pageTitle }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('owner.setting.general-setting-env.update') }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="settings-inner-box bg-white theme-border radius-4 mb-25">
                                                    <div class="settings-inner-box-fields p-20 pb-0">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                                                <select name="MAIL_STATUS" class="form-control">
                                                                    <option value="0"
                                                                        {{ env('MAIL_STATUS') == '0' ? 'selected' : '' }}>
                                                                        {{ __('Disable') }}</option>
                                                                    <option value="1"
                                                                        {{ env('MAIL_STATUS') == '1' ? 'selected' : '' }}>
                                                                        {{ __('Enable') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Mail Mailer') }}</label>
                                                                <select name="MAIL_MAILER" class="form-control">
                                                                    <option value="smtp"
                                                                        {{ env('MAIL_MAILER') == 'smtp' ? 'selected' : '' }}>
                                                                        {{ __('SMTP') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Mail Host') }}</label>
                                                                <input type="text" name="MAIL_HOST"
                                                                    value="{{ env('MAIL_HOST') }}" class="form-control"
                                                                    placeholder="{{ __('Mail Host') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Mail Port') }}</label>
                                                                <input type="text" name="MAIL_PORT"
                                                                    value="{{ env('MAIL_PORT') }}" class="form-control"
                                                                    placeholder="{{ __('Mail Port') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Mail Username') }}</label>
                                                                <input type="text" name="MAIL_USERNAME"
                                                                    value="{{ env('MAIL_USERNAME') }}" class="form-control"
                                                                    placeholder="{{ __('Mail Username') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Mail Password') }}</label>
                                                                <input type="text" name="MAIL_PASSWORD"
                                                                    value="{{ env('MAIL_PASSWORD') }}" class="form-control"
                                                                    placeholder="{{ __('Mail Password') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Mail Encryption') }}</label>

                                                                <input type="text" name="MAIL_ENCRYPTION"
                                                                    id="mail_encryption"
                                                                    value="{{ env('MAIL_ENCRYPTION') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Mail Encryption') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Mail From Address') }}</label>
                                                                <input type="text" name="MAIL_FROM_ADDRESS"
                                                                    id="mail_from_address"
                                                                    value="{{ env('MAIL_FROM_ADDRESS') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Mail From Address') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Mail From Name') }}</label>
                                                                <input type="text" name="MAIL_FROM_NAME"
                                                                    id="mail_from_name" value="{{ env('MAIL_FROM_NAME') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Mail From Name') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="theme-btn"
                                                    title="{{ __('Update') }}">{{ __('Update') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
