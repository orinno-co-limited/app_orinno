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
                                    <h3 class="mb-sm-0">{{ $pageTitle }}</h3>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
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
                                    <div class="currency-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-xl-6">
                                                        <h4>{{ $pageTitle }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tickets-topic-table-area">
                                                <div class="bg-white theme-border radius-4 p-25">
                                                    <table id="allDataTable"
                                                        class="table bg-white theme-border p-20 dt-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('SL') }}</th>
                                                                <th data-priority="1">{{ __('Name') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="row-item">
                                                                <td>{{ __('1') }}</td>
                                                                <td>{{ __('Hero Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_hero_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editHeroModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-item">
                                                                <td>{{ __('2') }}</td>
                                                                <td>{{ __('Amazing Features Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_amazing_feature_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editAmazingFeaturesModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-item">
                                                                <td>{{ __('3') }}</td>
                                                                <td>{{ __('About Us Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_about_us_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editAboutUsModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-item">
                                                                <td>{{ __('4') }}</td>
                                                                <td>{{ __('How it Works Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_how_it_word_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editHowItWorkModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-item">
                                                                <td>{{ __('5') }}</td>
                                                                <td>{{ __('Advance Feature Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_advance_feature_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#advanceFeatureModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-item">
                                                                <td>{{ __('6') }}</td>
                                                                <td>{{ __('Pricing Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_pricing_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editPriceModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-item d-none">
                                                                <td>{{ __('7') }}</td>
                                                                <td>{{ __('Integrations Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_integration_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editIntegrationModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-item">
                                                                <td>{{ __('7') }}</td>
                                                                <td>{{ __('Testimonial Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_testimonial_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editTestimonialModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="row-item">
                                                                <td>{{ __('8') }}</td>
                                                                <td>{{ __('Faq Section') }}</td>
                                                                <td>
                                                                    @if (getOption('home_faq_section_status', 1) == ACTIVE)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Active') }}</div>
                                                                    @else
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Deactivate') }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        <button type="button" class="p-1 tbl-action-btn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editFaqModal">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
        </div>
    </div>

    <div class="modal fade" id="editHeroModal" tabindex="-1" aria-labelledby="editHeroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editHeroModalLabel">{{ __('Hero Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_hero_title" class="form-control"
                                        value="{{ getOption('home_hero_title') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Summary') }}</label>
                                    <textarea name="home_hero_summery" id="home_hero_summery" class="form-control">{{ getOption('home_hero_summery') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Image Main') }}</label>
                                    <input type="file" class="form-control" name="home_hero_image">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_hero_section_status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_hero_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_hero_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAmazingFeaturesModal" tabindex="-1" aria-labelledby="editAmazingFeaturesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editAmazingFeaturesModalLabel">{{ __('Features Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="home_amazing_features_name" class="form-control"
                                        value="{{ getOption('home_amazing_features_name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_amazing_features_title" class="form-control"
                                        value="{{ getOption('home_amazing_features_title') }}">
                                </div>

                                <div class="col-md-12 mb-25 d-none">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Summary') }}</label>
                                    <textarea name="home_features_summery" id="home_features_summery" class="form-control">{{ getOption('home_features_summery') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-25 d-none">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Image') }}</label>
                                    <input type="file" class="form-control" name="home_features_image">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_amazing_feature_section_status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_amazing_feature_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_amazing_feature_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAboutUsModal" tabindex="-1" aria-labelledby="editAboutUsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editAboutUsModalLabel">{{ __('About Us Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_about_us_title" class="form-control"
                                        value="{{ getOption('home_about_us_title') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Sub Title') }}</label>
                                    <input type="text" name="home_about_us_sub_title" class="form-control"
                                        value="{{ getOption('home_about_us_sub_title') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                                    <input type="text" name="home_about_us_description" class="form-control"
                                        value="{{ getOption('home_about_us_description') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Counter One Title') }}</label>
                                    <input type="text" name="home_about_us_counter_one_title" class="form-control"
                                        value="{{ getOption('home_about_us_counter_one_title') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Counter One Number') }}</label>
                                    <input type="text" name="home_about_us_counter_one_number" class="form-control"
                                        value="{{ getOption('home_about_us_counter_one_number') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Counter Two Title') }}</label>
                                    <input type="text" name="home_about_us_counter_two_title" class="form-control"
                                        value="{{ getOption('home_about_us_counter_two_title') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Counter Two Number') }}</label>
                                    <input type="text" name="home_about_us_counter_two_number" class="form-control"
                                        value="{{ getOption('home_about_us_counter_two_number') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('About Us Image One') }}</label>
                                    <input type="file" class="form-control" name="home_about_us_image_one">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('About Us Image Two') }}</label>
                                    <input type="file" class="form-control" name="home_about_us_image_two">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_about_us_section_status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_about_us_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_about_us_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editHowItWorkModal" tabindex="-1" aria-labelledby="editHowItWorkModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editHowItWorkModalLabel">{{ __('How It Work Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="home_how_it_word_section_name" class="form-control"
                                        value="{{ getOption('home_how_it_word_section_name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_how_it_word_section_title" class="form-control"
                                        value="{{ getOption('home_how_it_word_section_title') }}">
                                </div>
                                <div class="col-md-12 mb-25 d-none">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Summary') }}</label>
                                    <textarea name="home_how_it_word_section_summery" class="form-control">{{ getOption('home_how_it_word_section_summery') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_how_it_word_section_status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_how_it_word_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_how_it_word_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="advanceFeatureModal" tabindex="-1" aria-labelledby="advanceFeatureModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="advanceFeatureModalLabel">{{ __('Advance Feature Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="home_advance_feature_section_name" class="form-control"
                                        value="{{ getOption('home_advance_feature_section_name') }}"
                                        placeholder="{{ __('Name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_advance_feature_section_title" class="form-control"
                                        value="{{ getOption('home_advance_feature_section_title') }}"
                                        placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-md-12 mb-25 d-none">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Summary') }}</label>
                                    <textarea name="home_core_pages_section_summery" class="form-control">{{ getOption('home_core_pages_section_summery') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_advance_feature_section_status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_advance_feature_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_advance_feature_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPriceModal" tabindex="-1" aria-labelledby="editPriceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editPriceModalLabel">{{ __('Price Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="home_price_section_name" class="form-control"
                                        value="{{ getOption('home_price_section_name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_price_section_title" class="form-control"
                                        value="{{ getOption('home_price_section_title') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_pricing_section_status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_pricing_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_pricing_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editIntegrationModal" tabindex="-1" aria-labelledby="editIntegrationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editIntegrationModalLabel">{{ __('Integration Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="home_integration_section_name" class="form-control"
                                        value="{{ getOption('home_integration_section_name') }}"
                                        placeholder="{{ __('Name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_integration_section_title" class="form-control"
                                        value="{{ getOption('home_integration_section_title') }}"
                                        placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Summary') }}</label>
                                    <textarea name="home_integration_section_summary" class="form-control" placeholder="{{ __('Summary') }}">{{ getOption('home_integration_section_summary') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Image Main') }}</label>
                                    <input type="file" class="form-control" name="home_integration_section_image">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_integration_section_status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_integration_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_integration_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTestimonialModal" tabindex="-1" aria-labelledby="editTestimonialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editTestimonialModalLabel">{{ __('Testimonial Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="home_testimonial_section_name"
                                        value="{{ getOption('home_testimonial_section_name') }}" class="form-control"
                                        placeholder="{{ __('Name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_testimonial_section_title"
                                        value="{{ getOption('home_testimonial_section_title') }}" class="form-control"
                                        placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_testimonial_section_status" id="status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_testimonial_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_testimonial_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFaqModal" tabindex="-1" aria-labelledby="editFaqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editFaqModalLabel">{{ __('Faq Section') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.general-setting.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="home_faq_section_name"
                                        value="{{ getOption('home_faq_section_name') }}" class="form-control">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="home_faq_section_title"
                                        value="{{ getOption('home_faq_section_title') }}" class="form-control">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="home_faq_section_status" id="status" class="form-control">
                                        <option value="1"
                                            {{ getOption('home_faq_section_status', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="0"
                                            {{ getOption('home_faq_section_status', 1) == 0 ? 'selected' : '' }}>
                                            {{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/pages/alldatatables.init.js') }}"></script>
@endpush
