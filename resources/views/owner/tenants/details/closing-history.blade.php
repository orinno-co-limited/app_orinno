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
                                        <li class="breadcrumb-item"><a href="{{ route('owner.tenant.index') }}"
                                                title="{{ __('Tenants') }}">{{ __('Tenants') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Tenants Details Layout Wrap Area row Start -->
                    <div class="tenants-details-layout-wrap position-relative">
                        <div class="row">
                            <!-- Account settings Left Side Start-->
                            <div class="col-md-12 col-lg-12 col-xl-4 col-xxl-3">
                                <div class="account-settings-leftside bg-white theme-border radius-4 p-20 mb-25">
                                    <div class="tenants-details-leftsidebar-wrap d-flex">
                                        @include('owner.tenants.details.sidenav')
                                    </div>
                                </div>
                            </div>
                            <!-- Account settings Left Side End-->

                            <!-- Account settings Area Right Side Start-->
                            <div class="col-md-12 col-lg-12 col-xl-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">

                                    <!-- Tenants Profile Information Start -->
                                    <div class="tenants-profile-information">
                                        <!-- Account Settings Content Box Start -->
                                        <div class="account-settings-content-box bg-white theme-border radius-4 mb-25 p-20">

                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ __('Closing History') }}</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="account-settings-info-box">
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                                                        <p class="color-heading">{{ __('Closing Refund Amount') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                                        <p>{{ $tenant->close_refund_amount }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                                                        <p class="color-heading">{{ __('Closing Charge') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                                        <p>{{ $tenant->close_charge }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                                                        <p class="color-heading">{{ __('Closing Date') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                                        <p>{{ $tenant->close_date }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                                                        <p class="color-heading">{{ __('Closing Reason') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                                                        <p>{{ $tenant->close_reason }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Account Settings Content Box End -->
                                    </div>
                                    <!-- Tenants Profile Information End -->
                                </div>
                            </div>
                            <!-- Account settings Area Right Side End-->
                        </div>
                    </div>
                    <!-- Tenants Details Layout Wrap Area row End -->
                </div>
                <!-- Page Content Wrapper End -->
            </div>
        </div>
        <!-- End Page-content -->
    </div>
@endsection
