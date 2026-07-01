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
                                                <div class="row rg-24 align-items-center">
                                                    <div class="col-xl-6">
                                                        <h4>{{ $pageTitle }}</h4>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="property-details-right text-xl-end">
                                                            @if (env('DS_CLIENT_ID'))
                                                                <a href="https://{{ env('DS_AUTH_SERVER') }}/oauth/auth?response_type=code&scope={{ env('DS_JWT_SCOPE') }}&client_id={{ env('DS_CLIENT_ID') }}&redirect_uri={{ url('/') }}/admin/agreement/callback"
                                                                    class="theme-btn-green"
                                                                    title="{{ __('Request Allow') }}">
                                                                    {{ __('Request Allow') }}
                                                                </a>
                                                            @endif
                                                            <button type="button" class="theme-btn mt-1"
                                                                data-bs-toggle="modal" data-bs-target="#testModal"
                                                                title="{{ __('Test Agreement Send') }}">
                                                                {{ __('Test Send') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('admin.setting.general-setting-env.update') }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="settings-inner-box bg-white theme-border radius-4 mb-25">
                                                    <div class="settings-inner-box-fields p-20 pb-0">
                                                        <div class="row">
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                                                <select name="DS_STATUS" class="form-control">
                                                                    <option value="0"
                                                                        {{ env('DS_STATUS') == '0' ? 'selected' : '' }}>
                                                                        {{ __('Disable') }}</option>
                                                                    <option value="1"
                                                                        {{ env('DS_STATUS') == '1' ? 'selected' : '' }}>
                                                                        {{ __('Enable') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('DS Client Id') }}
                                                                    / {{ __('Integration Key') }}</label>
                                                                <input type="password" name="DS_CLIENT_ID"
                                                                    value="{{ env('DS_CLIENT_ID') }}" class="form-control"
                                                                    placeholder="{{ __('DS Client ID') }}">
                                                                <small><a
                                                                        href="https://admindemo.docusign.com/apps-and-keys"
                                                                        target="_blank">{{ __('Integration Key') }}</a></small>
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('DS User Id') }}</label>
                                                                <input type="password" name="DS_IMPERSONATED_USER_ID"
                                                                    value="{{ env('DS_IMPERSONATED_USER_ID') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('DS User Id') }}">
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('DS Auth Server') }}</label>
                                                                <select name="DS_AUTH_SERVER" class="form-control">
                                                                    <option value="account-d.docusign.com"
                                                                        {{ env('DS_AUTH_SERVER') == 'account-d.docusign.com' ? 'selected' : '' }}>
                                                                        {{ __('Demo') }}</option>
                                                                    <option value="account.docusign.com"
                                                                        {{ env('DS_AUTH_SERVER') == 'account.docusign.com' ? 'selected' : '' }}>
                                                                        {{ __('Live') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-xl-6 mb-25 d-none">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('DS JWT Scope') }}</label>
                                                                <input type="text" name="DS_JWT_SCOPE"
                                                                    value="{{ env('DS_JWT_SCOPE') != null ? env('DS_JWT_SCOPE') : 'signature impersonation' }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('DS JWT SCOPE') }}">
                                                            </div>
                                                            <div class="col-xl-6 mb-25 d-none">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('DS URI Suffix') }}</label>
                                                                <input type="text" name="DS_ESIGN_URI_SUFFIX"
                                                                    value="{{ env('DS_ESIGN_URI_SUFFIX') != null ? env('DS_ESIGN_URI_SUFFIX') : '/restapi' }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('DS URI Suffix') }}">
                                                            </div>
                                                            <div class="col-xl-6 mb-25 d-none">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('DS Key Path') }}</label>
                                                                <input type="text" name="DS_KEY_PATH"
                                                                    value="{{ env('DS_KEY_PATH') != null ? env('DS_KEY_PATH') : 'app/jwt/private.key' }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('DS Key Path') }}">
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('DS Private Key') }}
                                                                    ({{ __('RSA Private Key') }})</label>
                                                                <textarea type="text" name="DS_PRIVATE_KEY" class="form-control" placeholder="{{ __('DS Private Key') }}">{{ file_get_contents(storage_path('app/jwt/private.key')) }}</textarea>
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
    <div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="testModalLabel">{{ __('Test Agreement Send') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('admin.agreement.test.send') }}" method="post"
                    data-handler="getShowMessage">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Sender Name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Sender Name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Sender Email') }}</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="{{ __('Sender Email') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Upload agreement doc, docx or pdf') }}</label>
                                    <input type="file" name="file" class="form-control">
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
