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
                                                title="{{ __('Settings') }}">{{ __('Settings') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ __('Basic Setting') }}
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
                                                        <h4>{{ __('Basic Setting') }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('owner.setting.general-setting.update') }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="settings-inner-box bg-white theme-border radius-4 mb-25">
                                                    <div class="settings-inner-box-title border-bottom p-20">
                                                        <h5>{{ __('App Setting') }}</h5>
                                                    </div>
                                                    <div class="settings-inner-box-fields p-20 pb-0">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('App Name') }}</label>
                                                                <input type="text" name="app_name"
                                                                    value="{{ getOption('app_name') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Type app name') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('App Email') }}</label>
                                                                <input type="text" name="app_email"
                                                                    value="{{ getOption('app_email') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Type app email') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('App Contact Number') }}</label>
                                                                <input type="text" name="app_contact_number"
                                                                    value="{{ getOption('app_contact_number') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Type mobile number') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('App Location') }}</label>
                                                                <input type="text" name="app_location"
                                                                    value="{{ getOption('app_location') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Type app location') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('App Copyright') }}</label>
                                                                <input type="text" name="app_copyright"
                                                                    value="{{ getOption('app_copyright') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('2022 © Copyright Reserved') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Developed By') }}</label>
                                                                <input type="text" name="app_developed_by"
                                                                    value="{{ getOption('app_developed_by') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Type developed by') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Default Currency') }}</label>
                                                                <select name="currency_id"
                                                                    class="form-select flex-shrink-0">
                                                                    <option value="">{{ __('Select Option') }}
                                                                    </option>
                                                                    @foreach ($currencies as $currency)
                                                                        <option value="{{ $currency->id }}"
                                                                            {{ $currency->id == @$current_currency->id ? 'selected' : '' }}>
                                                                            {{ $currency->currency_code . '(' . $currency->symbol . ')' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Default Language') }}</label>
                                                                <select name="language_id"
                                                                    class="form-select flex-shrink-0">
                                                                    <option value="">{{ __('Select Option') }}
                                                                    </option>
                                                                    @foreach ($languages as $language)
                                                                        <option value="{{ $language->id }}"
                                                                            {{ $language->id == @$default_language->id ? 'selected' : '' }}>
                                                                            {{ $language->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('App Preloader Status') }}</label>
                                                                <select name="app_preloader_status"
                                                                    class="form-select flex-shrink-0">
                                                                    <option value="1"
                                                                        {{ getOption('app_preloader_status') == 1 ? 'selected' : '' }}>
                                                                        {{ __('Active') }}</option>
                                                                    <option value="2"
                                                                        {{ getOption('app_preloader_status') != 1 ? 'selected' : '' }}>
                                                                        {{ __('Deactivate') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <div class="app-logo-favicon-preloader-box">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label
                                                                                class="label-text-title color-heading font-medium mb-2">{{ __('App Logo') }}</label>
                                                                            <div
                                                                                class="upload-app-logo bg-light radius-4 text-center p-3">
                                                                                <div
                                                                                    class="profile-user position-relative d-inline-block">
                                                                                    <img src="
                                                                                    @if (empty(getOption('app_logo'))) {{ asset('assets/images/users/empty-user.jpg') }}
                                                                                    @else
                                                                                         {{ getSettingImage('app_logo') }} @endif"
                                                                                        class="rounded avatar-xl app-logo-user-profile-image"
                                                                                        alt="user-profile-image">
                                                                                    <div
                                                                                        class="avatar-xs p-0 rounded-circle app-logo-profile-photo-edit">
                                                                                        <input
                                                                                            id="app-logo-profile-img-file-input"
                                                                                            name="app_logo" type="file"
                                                                                            class="app-logo-profile-img-file-input">
                                                                                        <label
                                                                                            for="app-logo-profile-img-file-input"
                                                                                            class="app-logo-profile-photo-edit avatar-xs">
                                                                                            <span
                                                                                                class="avatar-title rounded-circle"
                                                                                                title="{{ __('Upload Image') }}">
                                                                                                <i
                                                                                                    class="ri-camera-fill"></i>
                                                                                            </span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <span
                                                                                class="text-info">{{ __('Recomended size') }}
                                                                                : 150 x 50</span>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label
                                                                                class="label-text-title color-heading font-medium mb-2">{{ __('App Favicon') }}</label>
                                                                            <div
                                                                                class="upload-app-logo bg-light radius-4 text-center p-3">
                                                                                <div
                                                                                    class="profile-user position-relative d-inline-block">
                                                                                    <img src="@if (empty(getOption('app_fav_icon'))) {{ asset('assets/images/users/empty-user.jpg') }}
                                                                                    @else
                                                                                        {{ getSettingImage('app_fav_icon') }} @endif"
                                                                                        class="rounded-circle avatar-xl app-favicon-user-profile-image"
                                                                                        alt="user-profile-image">
                                                                                    <div
                                                                                        class="avatar-xs p-0 rounded-circle app-favicon-profile-photo-edit">
                                                                                        <input
                                                                                            id="app-favicon-profile-img-file-input"
                                                                                            name="app_fav_icon"
                                                                                            type="file"
                                                                                            class="app-favicon-profile-img-file-input">
                                                                                        <label
                                                                                            for="app-favicon-profile-img-file-input"
                                                                                            class="app-favicon-profile-photo-edit avatar-xs">
                                                                                            <span
                                                                                                class="avatar-title rounded-circle"
                                                                                                title="{{ __('Upload Image') }}">
                                                                                                <i
                                                                                                    class="ri-camera-fill"></i>
                                                                                            </span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <span
                                                                                class="text-info">{{ __('Recomended size') }}
                                                                                : 64 x 64</span>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label
                                                                                class="label-text-title color-heading font-medium mb-2">{{ __('Preloader') }}</label>
                                                                            <div
                                                                                class="upload-app-logo bg-light radius-4 text-center p-3">
                                                                                <div
                                                                                    class="profile-user position-relative d-inline-block">
                                                                                    <img src="@if (empty(getOption('app_preloader'))) {{ asset('assets/images/users/empty-user.jpg') }}
                                                                                    @else
                                                                                        {{ getSettingImage('app_preloader') }} @endif"
                                                                                        class="rounded avatar-xl app-preloader-user-profile-image"
                                                                                        alt="user-profile-image">
                                                                                    <div
                                                                                        class="avatar-xs p-0 rounded-circle app-preloader-profile-photo-edit">
                                                                                        <input
                                                                                            id="app-preloader-profile-img-file-input"
                                                                                            name="app_preloader"
                                                                                            type="file"
                                                                                            class="app-preloader-profile-img-file-input">
                                                                                        <label
                                                                                            for="app-preloader-profile-img-file-input"
                                                                                            class="app-preloader-profile-photo-edit avatar-xs">
                                                                                            <span
                                                                                                class="avatar-title rounded-circle"
                                                                                                title="{{ __('Upload Image') }}">
                                                                                                <i
                                                                                                    class="ri-camera-fill"></i>
                                                                                            </span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <span
                                                                                class="text-info">{{ __('Recomended size') }}
                                                                                : 150 x 50</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Sign In Text Title') }}</label>
                                                                <input type="text" name="sign_in_text_title"
                                                                    class="form-control"
                                                                    value="{{ getOption('sign_in_text_title') }}"
                                                                    placeholder="{{ __('Type sign in text title') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Sign In Text Subtitle') }}</label>
                                                                <input type="text" name="sign_in_text_subtitle"
                                                                    class="form-control"
                                                                    value="{{ getOption('sign_in_text_subtitle') }}"
                                                                    placeholder="{{ __('Sign in text subtitle') }}">
                                                            </div>
                                                            <div class="col-md-4 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Sign In Image') }}</label>
                                                                <div
                                                                    class="upload-app-logo bg-light radius-4 text-center p-3">
                                                                    <div
                                                                        class="profile-user position-relative d-inline-block">
                                                                        <img src="@if (empty(getOption('sign_in_image'))) {{ asset('assets/images/users/empty-user.jpg') }}
                                                                                    @else
                                                                                        {{ getSettingImage('sign_in_image') }} @endif"
                                                                            class="rounded avatar-xl signin-user-profile-image"
                                                                            alt="user-profile-image">
                                                                        <div
                                                                            class="avatar-xs p-0 rounded-circle signin-profile-photo-edit">
                                                                            <input id="signin-profile-img-file-input"
                                                                                name="sign_in_image" type="file"
                                                                                class="signin-profile-img-file-input">
                                                                            <label for="signin-profile-img-file-input"
                                                                                class="signin-profile-photo-edit avatar-xs">
                                                                                <span class="avatar-title rounded-circle"
                                                                                    title="Upload Image">
                                                                                    <i class="ri-camera-fill"></i>
                                                                                </span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="text-info">{{ __('Recomended size') }} : 576
                                                                    x 458</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="settings-inner-box bg-white theme-border radius-4 mb-25">
                                                    <div class="settings-inner-box-title border-bottom p-20">
                                                        <h5>{{ __('SEO Setting') }}</h5>
                                                    </div>
                                                    <div class="settings-inner-box-fields p-20 pb-0">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Meta Keyword') }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="meta_keyword"
                                                                    value="{{ getOption('meta_keyword') }}"
                                                                    placeholder="{{ __('Meta Keyword') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Meta Author') }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="meta_author"
                                                                    value="{{ getOption('meta_author') }}"
                                                                    placeholder="{{ __('Meta Author') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Revisit') }}</label>
                                                                <input type="text" class="form-control" name="revisit"
                                                                    value="{{ getOption('revisit') }}"
                                                                    placeholder="{{ __('01') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Sitemap Link') }}</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ getOption('sitemap_link') }}"
                                                                    name="sitemap_link" placeholder="#">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Meta Description') }}</label>
                                                                <textarea class="form-control" name="meta_description" placeholder="{{ __('Meta Description') }}">{{ getOption('meta_description') }}</textarea>
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

@push('script')
    <script src="{{ asset('assets/js/pages/app-logo-setting.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/app-favicon-img-setting.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/app-preloader-img-setting.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/signin-img-setting.init.js') }}"></script>
@endpush
