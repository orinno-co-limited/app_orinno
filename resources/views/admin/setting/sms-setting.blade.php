@extends('admin.layouts.app')

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
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item"><a href="#"
                                                title="{{ __('Settings') }}">{{ __('Settings') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-page-layout-wrap position-relative">
                        <div class="row">
                            @include('admin.setting.sidebar')
                            <div class="col-md-12 col-lg-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <div class="language-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ $pageTitle }}</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="property-details-right text-end">
                                                            <button type="button" class="theme-btn" data-bs-toggle="modal"
                                                                data-bs-target="#testSmsModal" title="{{ __('Test Sms') }}">
                                                                {{ __('Test Sms') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('admin.setting.general-setting.update') }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="settings-inner-box bg-white theme-border radius-4 mb-25">
                                                    <div class="settings-inner-box-fields p-20 pb-0">
                                                        <div class="row">
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                                                <select name="TWILIO_STATUS" class="form-control">
                                                                    <option value="0"
                                                                        {{ getOption('TWILIO_STATUS') == '0' ? 'selected' : '' }}>
                                                                        {{ __('Disable') }}</option>
                                                                    <option value="1"
                                                                        {{ getOption('TWILIO_STATUS') == '1' ? 'selected' : '' }}>
                                                                        {{ __('Enable') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Twilio account SID') }}</label>
                                                                <input type="password" name="TWILIO_ACCOUNT_SID"
                                                                    value="{{ getOption('TWILIO_ACCOUNT_SID') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Twilio Account SID') }}">
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Twilio Auth Token') }}</label>
                                                                <input type="password" name="TWILIO_AUTH_TOKEN"
                                                                    value="{{ getOption('TWILIO_AUTH_TOKEN') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Twilio auth token') }}">
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Twilio Phone Number') }}</label>
                                                                <input type="text" name="TWILIO_PHONE_NUMBER"
                                                                    value="{{ getOption('TWILIO_PHONE_NUMBER') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Twilio Phone Number') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="settings-inner-box bg-white theme-border radius-4 mb-25">
                                                    <div class="settings-inner-box-fields p-20 pb-0">
                                                        <div class="row">
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Send Email Status') }}</label>
                                                                <select name="send_email_status"
                                                                    class="form-select flex-shrink-0">
                                                                    <option value="1"
                                                                        {{ getOption('send_email_status', 0) == SEND_EMAIL_STATUS_ACTIVE ? 'selected' : '' }}>
                                                                        {{ __('Active') }}</option>
                                                                    <option value="0"
                                                                        {{ getOption('send_email_status', 0) != SEND_EMAIL_STATUS_ACTIVE ? 'selected' : '' }}>
                                                                        {{ __('Deactivate') }}</option>
                                                                </select>
                                                                <small
                                                                    class="small">{{ __('Sent mail to Owner sign Up, New invoice generate, Subscription payment success, New tenant add, New maintainer add, New contact message etc.') }}</small>
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Email Verify Status') }}</label>
                                                                <select name="email_verification_status"
                                                                    class="form-select flex-shrink-0">
                                                                    <option value="1"
                                                                        {{ getOption('email_verification_status', 0) == EMAIL_VERIFICATION_STATUS_ACTIVE ? 'selected' : '' }}>
                                                                        {{ __('Active') }}</option>
                                                                    <option value="0"
                                                                        {{ getOption('email_verification_status', 0) != EMAIL_VERIFICATION_STATUS_ACTIVE ? 'selected' : '' }}>
                                                                        {{ __('Deactivate') }}</option>
                                                                </select>
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
    <div class="modal fade" id="testSmsModal" tabindex="-1" aria-labelledby="testSmsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="testSmsModalLabel">{{ __('Test Sms') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('sms-mail.sms.test.send') }}" method="post"
                    data-handler="getShowMessage">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone_number" class="form-control"
                                        placeholder="{{ __('Phone Number') }}">
                                </div>

                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Message') }}</label>
                                    <textarea name="message" id="message" class="form-control" placeholder="{{ __('Message') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Send') }}">{{ __('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
