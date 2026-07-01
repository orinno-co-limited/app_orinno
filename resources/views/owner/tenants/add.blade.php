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
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Add Tenants Area row Start -->
                    <div class="all-property-area">

                        <!--Add Tenants Stepper Area Start -->
                        <div class="add-property-stepper-area add-tenants-stepper-area">
                            <div class="row">

                                <!-- Stepper Start -->
                                <div class="col-12">
                                    <div id="msform">
                                        <!-- progressbar -->
                                        <div class="stepper-progressbar-wrap radius-10 theme-border p-25 mb-25">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active" id="accountInformationStep">
                                                    <span class="form-stepper-nav-icon"><i
                                                            class="ri-account-circle-fill"></i></span>
                                                    <span>{{ __('Tenant Information') }}</span>
                                                </li>
                                                <li id="locationStep">
                                                    <span class="form-stepper-nav-icon"><i
                                                            class="ri-home-4-fill"></i></span>
                                                    <span>{{ __('Home Details') }}</span>
                                                </li>
                                                <li id="unitStep">
                                                    <span class="form-stepper-nav-icon"><i
                                                            class="ri-file-text-fill"></i></span>
                                                    <span>{{ __('Documents') }}</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- fieldsets 1 -->
                                        <fieldset>
                                            <form class="ajax" action="{{ route('owner.tenant.store') }}" method="POST"
                                                data-handler="stepChange">
                                                @csrf
                                                <input type="hidden" name="step" class="d-none" value="1">
                                                <div
                                                    class="form-card add-property-box bg-off-white theme-border radius-4 p-20">
                                                    <div class="add-property-title border-bottom pb-25 mb-25">
                                                        <h4>{{ __('Tenant Information') }}</h4>
                                                    </div>
                                                    <!-- Upload Profile Photo Box Start -->
                                                    <div class="upload-profile-photo-box mb-25">
                                                        <div class="profile-user position-relative d-inline-block">
                                                            <img src="{{ asset('assets/images/users/empty-user.jpg') }}"
                                                                class="rounded-circle avatar-xl user-profile-image"
                                                                alt="user-profile-image">
                                                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                                <input id="profile-img-file-input" name="image"
                                                                    type="file" class="profile-img-file-input">
                                                                <label for="profile-img-file-input"
                                                                    class="profile-photo-edit avatar-xs">
                                                                    <span class="avatar-title rounded-circle"
                                                                        title="Upload Image">
                                                                        <i class="ri-camera-fill"></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Upload Profile Photo Box End -->

                                                    <div
                                                        class="add-property-inner-box bg-white theme-border radius-4 p-20 pb-0 mb-25">
                                                        <div class="tenants-inner-box-block">
                                                            <div class="add-property-title border-bottom pb-25 mb-25">
                                                                <h4>{{ __('Personal Information') }}</h4>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('First Name') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="first_name"
                                                                        class="form-control" role="alert"
                                                                        placeholder="{{ __('First Name') }}">
                                                                </div>
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Last Name') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="last_name"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Last Name') }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="contact_number"
                                                                        name="contact_number" class="form-control"
                                                                        placeholder="{{ __('Contact Number') }}">
                                                                </div>
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Job') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="job"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Job') }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Age') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="number" name="age"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Age') }}">
                                                                </div>
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Family Members') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="number" name="family_member"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Family Members') }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="email" name="email"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Email') }}">
                                                                </div>
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="password" name="password"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Password') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="add-property-inner-box bg-white theme-border radius-4 p-20 pb-0 mb-25">
                                                        <div class="tenants-inner-box-block">
                                                            <div class="add-property-title border-bottom pb-25 mb-25">
                                                                <h4>{{ __('Previous Address') }}</h4>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Address') }}</label>
                                                                    <input type="text" name="previous_address"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Address') }}">
                                                                </div>
                                                            </div>
                                                            <div class="row location" id="previous">
                                                                <div class="col-md-3 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Country') }}</label>
                                                                    <input type="text" name="previous_country_id"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Country') }}">
                                                                </div>
                                                                <div class="col-md-3 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('State') }}</label>
                                                                    <input type="text" name="previous_state_id"
                                                                        class="form-control"
                                                                        placeholder="{{ __('State') }}">
                                                                </div>
                                                                <div class="col-md-3 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('City') }}</label>
                                                                    <input type="text" name="previous_city_id"
                                                                        class="form-control"
                                                                        placeholder="{{ __('City') }}">
                                                                </div>
                                                                <div class="col-md-3 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Zip Code') }}</label>
                                                                    <input type="text" name="previous_zip_code"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Zip Code') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="add-property-inner-box bg-white theme-border radius-4 p-20 pb-0">
                                                        <div class="tenants-inner-box-block">
                                                            <div class="add-property-title border-bottom pb-25 mb-25">
                                                                <h4>{{ __('Permanent Address') }}</h4>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Address') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="permanent_address"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Address') }}">
                                                                </div>
                                                            </div>
                                                            <div class="row location" id="permanent">
                                                                <div class="col-md-3 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Country') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="permanent_country_id"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Country') }}">
                                                                </div>
                                                                <div class="col-md-3 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('State') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="permanent_state_id"
                                                                        class="form-control"
                                                                        placeholder="{{ __('State') }}">
                                                                </div>
                                                                <div class="col-md-3 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('City') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="permanent_city_id"
                                                                        class="form-control"
                                                                        placeholder="{{ __('City') }}">
                                                                </div>
                                                                <div class="col-md-3 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Zip Code') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="permanent_zip_code"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Zip Code') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Next/Previous Button Start -->
                                                <button type="submit"
                                                    class="nextStep1 action-button theme-btn mt-25">{{ __('Next') }}</button>
                                            </form>
                                        </fieldset>

                                        <!-- fieldsets 2 -->
                                        <fieldset>
                                            <form class="ajax" action="{{ route('owner.tenant.store') }}"
                                                method="POST" data-handler="stepChange">
                                                @csrf
                                                <input type="hidden" name="step" class="d-none" value="2">
                                                <input type="hidden" name="id" value="">
                                                <div
                                                    class="form-card add-property-box bg-off-white theme-border radius-4 p-20">
                                                    <div class="add-property-title border-bottom pb-25 mb-25">
                                                        <h4>{{ __('Home Details') }}</h4>
                                                    </div>
                                                    <div
                                                        class="add-property-inner-box bg-white theme-border radius-4 p-20 pb-0 mb-25">
                                                        <div class="tenants-inner-box-block">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <select class="form-select flex-shrink-0 property_id"
                                                                        name="property_id">
                                                                        <option value="" selected>
                                                                            --{{ __('Select Property') }}--</option>
                                                                        @foreach ($properties as $property)
                                                                            <option value="{{ $property->id }}">
                                                                                {{ $property->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <select class="form-select flex-shrink-0 unit_id"
                                                                        name="unit_id" id="unitId">
                                                                        <option value="" selected>
                                                                            --{{ __('Select Unit') }}--</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Lease Start date') }}</label>
                                                                    <div class="custom-datepicker">
                                                                        <div
                                                                            class="custom-datepicker-inner position-relative">
                                                                            <input type="text"
                                                                                class="datepicker form-control"
                                                                                autocomplete="off" placeholder="yy-mm-dd"
                                                                                name="lease_start_date">
                                                                            <i class="ri-calendar-2-line"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Lease End date') }}</label>
                                                                    <div class="custom-datepicker">
                                                                        <div
                                                                            class="custom-datepicker-inner position-relative">
                                                                            <input type="text"
                                                                                class="datepicker form-control"
                                                                                autocomplete="off" placeholder="yy-mm-dd"
                                                                                name="lease_end_date">
                                                                            <i class="ri-calendar-2-line"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Tenants Home Details Info Box Start -->
                                                    <div class="add-property-inner-box bg-white theme-border radius-4 p-20 pb-0 mb-25 d-none"
                                                        id="propertyInformation">
                                                        <div class="tenants-inner-box-block">
                                                            <div class="row">
                                                                <!-- Property Item Start -->
                                                                <div class="col-md-12">
                                                                    <div
                                                                        class="tenants-property-item-list-view mb-25 d-flex align-items-center">
                                                                        <a href="#"
                                                                            class="property-item-img-wrap d-block position-relative overflow-hidden radius-10 flex-shrink-0">
                                                                            <div class="property-item-img">
                                                                                <img src="{{ asset('assets/images/users/empty-user.jpg') }}"
                                                                                    alt=""
                                                                                    class="fit-image propertyImg">
                                                                            </div>
                                                                        </a>
                                                                        <div
                                                                            class="property-item-content p-20 flex-grow-1 ms-2">
                                                                            <h3 class="property-item-title">
                                                                                <a href="#"
                                                                                    class="color-heading link-hover-effect">{{ __('N/A') }}</a>
                                                                            </h3>

                                                                            <div
                                                                                class="property-item-address d-flex mt-15">
                                                                                <div class="flex-shrink-0 font-13">
                                                                                    <i class="ri-map-pin-2-fill"></i>
                                                                                </div>
                                                                                <div class="flex-grow-1 ms-1">
                                                                                    <p>{{ __('N/A') }}</p>
                                                                                </div>
                                                                            </div>

                                                                            <div
                                                                                class="property-item-info mt-15 bg-white theme-border py-3 px-2 radius-4">
                                                                                <div class="row">
                                                                                    <div class="col-sm-6 col-md-6">
                                                                                        <div
                                                                                            class="property-info-item property-info-item-left font-13">
                                                                                            <i
                                                                                                class="ri-home-5-fill me-1 "></i>
                                                                                            <span
                                                                                                id="unit_name">{{ __('N/A') }}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6 col-md-6">
                                                                                        <div
                                                                                            class="property-info-item property-info-item-right font-13">
                                                                                            <i
                                                                                                class="ri-checkbox-circle-fill me-1 "></i>{{ __('Available For Tenant') }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Property Item End -->
                                                            </div>

                                                            <div class="add-property-title border-bottom pb-25 mb-25">
                                                                <h4>{{ __('Rent Information') }}</h4>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 col-lg-4 col-xl-3 col-xxl-2 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('General Rent') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <div class="input-group custom-input-group">
                                                                        <input type="number" step="any"
                                                                            class="form-control" id="general_rent"
                                                                            placeholder="{{ __('General Rent') }}"
                                                                            value="" name="general_rent">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-lg-4 col-xl-3 col-xxl-2 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Security Deposit') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <div class="input-group custom-input-group">
                                                                        <select name="security_deposit_type"
                                                                            class="form-control">
                                                                            <option value="0">
                                                                                {{ __('Fixed') }}</option>
                                                                            <option value="1">
                                                                                {{ __('Percentage') }}</option>
                                                                        </select>
                                                                        <input type="number" step="any"
                                                                            class="form-control" id="security_deposit"
                                                                            placeholder="{{ __('Security Deposit') }}"
                                                                            value="" name="security_deposit">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-lg-4 col-xl-3 col-xxl-2 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Late Fee') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <div class="input-group custom-input-group">
                                                                        <select name="late_fee_type" class="form-control">
                                                                            <option value="0">
                                                                                {{ __('Fixed') }}</option>
                                                                            <option value="1">
                                                                                {{ __('Percentage') }}</option>
                                                                        </select>
                                                                        <input type="number" step="any"
                                                                            class="form-control" id="late_fee"
                                                                            placeholder="{{ __('Late Fee') }}"
                                                                            value="" name="late_fee">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-lg-4 col-xl-3 col-xxl-2 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Incident Receipt') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <div class="input-group custom-input-group">
                                                                        <input type="number" step="any"
                                                                            class="form-control" id="incident_receipt"
                                                                            placeholder="{{ __('Incident Receipt') }}"
                                                                            value="" name="incident_receipt">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 col-lg-4 mb-25">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Payment due on date') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="number" id="payment_due_on_date"
                                                                        class="form-control" autocomplete="off"
                                                                        placeholder="{{ __('Due Date') }}"
                                                                        name="due_date">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <!-- Tenants Home Details Info Box End -->
                                                </div>
                                                <!-- Next/Previous Button Start -->
                                                <input type="button" name="previous"
                                                    class="previousStep action-button-previous theme-btn mt-25"
                                                    value="Back">
                                                <input type="submit" name="next"
                                                    class="nextStep2 action-button theme-btn mt-25" value="Next">
                                            </form>
                                        </fieldset>

                                        <!-- fieldsets 3 -->
                                        <fieldset>
                                            <form class="ajax" action="{{ route('owner.tenant.store') }}"
                                                method="POST" data-handler="stepChange" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="step" class="d-none" value="3">
                                                <input type="hidden" name="id" value="">
                                                <div
                                                    class="form-card add-property-box bg-off-white theme-border radius-4 p-20">
                                                    <div class="add-property-title border-bottom pb-25 mb-3">
                                                        <h4>{{ __('Personal Documents') }}</h4>
                                                    </div>
                                                    <div
                                                        class="add-property-inner-box bg-white theme-border radius-4 p-20">
                                                        <div class="row">
                                                            <!-- Files and Documents Upload Start -->
                                                            <div class="col-lg-12">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <input type="file" name="file"
                                                                            class="dropify"
                                                                            data-allowed-file-extensions="jpeg jpg png pdf"
                                                                            data-max-file-size-preview="3M" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Files and Documents Upload End -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Next/Previous Button Start -->
                                                <input type="button" name="previous"
                                                    class="previousStep action-button-previous theme-btn mt-25"
                                                    value="Back">
                                                <input type="submit" class="action-button theme-btn mt-25"
                                                    value="Save">
                                            </form>

                                        </fieldset>
                                    </div>
                                </div>
                                <!-- Stepper End -->

                            </div>
                        </div>
                        <!-- Add Tenants Stepper Area End -->

                    </div>
                    <!-- Add Tenants Area row End -->

                </div>
                <!-- Page Content Wrapper End -->

            </div>

        </div>
        <!-- End Page-content -->

    </div>
    <input type="hidden" id="getStateListRoute" value="{{ route('owner.location.state.list') }}">
    <input type="hidden" id="getCityListRoute" value="{{ route('owner.location.city.list') }}">
    <input type="hidden" id="propertyShowRoute" value="{{ route('owner.property.show', 0) }}">
    <input type="hidden" id="tenantStoreRoute" value="{{ route('owner.tenant.store') }}">
    <input type="hidden" id="tenantListRoute" value="{{ route('owner.tenant.index') }}">
    <input type="hidden" id="getPropertyWithUnitsByIdRoute"
        value="{{ route('owner.property.getPropertyWithUnitsById') }}">
@endsection

@push('script')
    <script src="{{ asset('/') }}assets/js/pages/profile-setting.init.js"></script>
    <script src="{{ asset('assets/js/custom/tenant.js') }}"></script>
@endpush
