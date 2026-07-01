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
                                        <li class="breadcrumb-item"><a href="{{ route('owner.tenant.index') }}"
                                                title="Home">{{ __('Tenants') }}</a></li>
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
                            <!-- Account settings Left Side End-->
                            <!-- Account settings Area Right Side Start-->
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">

                                    <!-- Tenants Details Payment History Start -->
                                    <div class="tenants-details-payment-history">

                                        <!-- Account Settings Content Box Start -->
                                        <div class="account-settings-content-box">
                                            <div class="tenants-details-payment-history-table">
                                                <!-- datatable Start -->
                                                <table id="allClosingTenantDataTable"
                                                    class="table responsive theme-border p-20">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('SL') }}</th>
                                                            <th data-priority="1">{{ __('Name') }}</th>
                                                            <th class="d-none"></th>
                                                            <th>{{ __('Property') }}</th>
                                                            <th>{{ __('Unit') }}</th>
                                                            <th>{{ __('General Rent') }}</th>
                                                            <th>{{ __('Status') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <!-- datatable End -->
                                            </div>
                                        </div>
                                        <!-- Account Settings Content Box End -->
                                    </div>
                                    <!-- Tenants Details Payment History End -->
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
    <input type="hidden" id="route" value="{{ route('owner.tenant.index', ['type' => 'history']) }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/tenant-history.js') }}"></script>
@endpush
