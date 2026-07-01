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
                                                                <select name="frontend_status" class="form-control">
                                                                    <option value="0"
                                                                        {{ getOption('frontend_status', 1) != ACTIVE ? 'selected' : '' }}>
                                                                        {{ __('Disable') }}</option>
                                                                    <option value="1"
                                                                        {{ getOption('frontend_status', 1) == ACTIVE ? 'selected' : '' }}>
                                                                        {{ __('Enable') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-xl-6 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Trial Package Duration(days)') }}</label>
                                                                <input type="number" name="trail_duration"
                                                                    value="{{ getOption('trail_duration', 1) }}"
                                                                    class="form-control" placeholder="3">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('App Copyright') }}</label>
                                                                <input type="text" name="app_copyright"
                                                                    value="{{ getOption('app_copyright') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ date('Y') }} {{ __('© Copyright Reserved') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Developed By') }}</label>
                                                                <input type="text" name="app_developed_by"
                                                                    value="{{ getOption('app_developed_by') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Type Developed By') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('App Footer Text') }}</label>
                                                                <input type="text" name="app_footer_text"
                                                                    value="{{ getOption('app_footer_text') }}"
                                                                    class="form-control"
                                                                    placeholder="{{ __('Website Footer Text') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Settings Inner Box -->
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
                                                <div class="settings-inner-box bg-white theme-border radius-4 mb-25">
                                                    <div class="settings-inner-box-title border-bottom p-20">
                                                        <h5>{{ __('Social Media Setting') }}</h5>
                                                    </div>
                                                    <div class="settings-inner-box-fields p-20 pb-0">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Facebook') }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="facebook_url"
                                                                    value="{{ getOption('facebook_url') }}"
                                                                    placeholder="{{ __('Facebook') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Twitter') }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="twitter_url"
                                                                    value="{{ getOption('twitter_url') }}"
                                                                    placeholder="{{ __('Twitter') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Linkedin') }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="linkedin_url"
                                                                    value="{{ getOption('linkedin_url') }}"
                                                                    placeholder="{{ __('Linkedin') }}">
                                                            </div>
                                                            <div class="col-md-12 mb-25">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Skype') }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="skype_url" value="{{ getOption('skype_url') }}"
                                                                    placeholder="{{ __('Skype') }}">
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
