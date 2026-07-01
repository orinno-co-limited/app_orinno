@extends('owner.layouts.app')

@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <!-- Page Content Wrapper Start -->
                <div class="page-content-wrapper bg-white p-30 radius-20">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between border-bottom mb-20">
                                <div class="page-title-left">
                                    <h3 class="mb-sm-0">{{ $pageTitle }}</h3>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}"
                                                title="Dashboard">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Information Page Area row Start -->
                    <div class="row">
                        <!-- Property Top Search Bar Start -->
                        <h4 class="mb-20">{{ __('All Maintainer') }}</h4>
                        <div class="property-top-search-bar">
                            <div class="property-search-inner-bg bg-off-white theme-border radius-4 p-25 pb-0 mb-25">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="property-top-search-bar-left">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-6 col-xl-4 mb-25">
                                                    <select class="form-select flex-shrink-0 " id="search_property">
                                                        <option value="" selected>--{{ __('Select Property') }}--
                                                        </option>
                                                        @foreach ($properties as $property)
                                                            <option value="{{ $property->name }}">{{ $property->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="property-top-search-bar-right text-end">
                                            <button type="button" class="theme-btn mb-25 add"
                                                title="{{ __('Add Maintainer') }}">{{ __('Add Maintainer') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Property Top Search Bar End -->

                        <!-- All Maintainer Table Area Start -->
                        <div class="all-maintainer-table-area">
                            <!-- datatable Start -->
                            <div class="bg-off-white theme-border radius-4 p-25">
                                <table id="allMaintainerDataTable"
                                    class="table bg-off-white aaa theme-border dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th>{{ __('Image') }}</th>
                                            <th data-priority="1">{{ __('Name') }}</th>
                                            <th class="d-none">{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Contact Number') }}</th>
                                            <th>{{ __('Property') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- datatable End -->
                        </div>
                        <!-- All Maintainer Table Area End -->
                    </div>
                    <!-- Information Page Area row End -->
                </div>
                <!-- Page Content Wrapper End -->
            </div>
        </div>
        <!-- End Page-content -->
    </div>

    <!-- Add Information Modal Start -->
    <div class="modal fade" id="addMaintainerModal" tabindex="-1" aria-labelledby="addMaintainerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="ajax" action="{{ route('owner.maintainer.store') }}" method="POST"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addMaintainerModalLabel">{{ __('Add Maintainer') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                class="iconify" data-icon="akar-icons:cross"></span></button>
                    </div>
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <!-- Upload Profile Photo Box Start -->
                                <div class="upload-profile-photo-box mb-25">
                                    <div class="profile-user position-relative d-inline-block">
                                        <img src="{{ asset('assets/images/users/empty-user.jpg') }}"
                                            class="rounded-circle avatar-xl maintainer-user-profile-image image"
                                            alt="">
                                        <div class="avatar-xs p-0 rounded-circle maintainer-profile-photo-edit">
                                            <input id="maintainer-profile-img-file-input" type="file"
                                                class="maintainer-profile-img-file-input" name="image">
                                            <label for="maintainer-profile-img-file-input"
                                                class="maintainer-profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle" title="Upload Image">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Upload Profile Photo Box End -->
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name" class="form-control first_name"
                                        placeholder="{{ __('First Name') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name" class="form-control last_name"
                                        placeholder="{{ __('Last Name') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control email"
                                        placeholder="{{ __('Email') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}</label>
                                    <input type="text" name="contact_number" class="form-control contact_number"
                                        placeholder="{{ __('Contact Number') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="{{ __('Password') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Assign Property') }}</label>
                                    <div class="my-custom-select-box">
                                        <select name="property_id[]" data-selected-text-format="count" multiple
                                            class="my-custom-select form-select selectpicker w-100 property_id">
                                            <option value="all">{{ __('All') }}</option>
                                            @foreach ($properties as $property)
                                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mc-25">
                                    <label for="" class="label-text-title color-heading font-medium mb-2">{{__('Status')}}</label>
                                    <div class="my-custom-select-box">
                                        <select name="status" id="status" class="form-control status">
                                            <option value="{{ACTIVE}}">{{ __('Active') }}</option>
                                            <option value="{{DEACTIVATE}}">{{ __('Deactivate') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Submit') }}">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- Add Information Modal End -->
    <input type="hidden" id="getInfoRoute" value="{{ route('owner.maintainer.get.info') }}">
    <input type="hidden" id="route" value="{{ route('owner.maintainer.index') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}">
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/maintainer-profile-photo.init.js') }}"></script>
    <script src="{{ asset('assets/js/custom/maintainer.js') }}"></script>
@endpush
