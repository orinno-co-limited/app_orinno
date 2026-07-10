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

                    <div class="row">
                        <div class="col-12 mb-25">
                            <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                                <h4 class="mb-20">{{ __('Tenants Per Landlord') }}</h4>
                                <table id="landlordTenantDataTable" class="table responsive theme-border p-20">
                                    <thead>
                                        <th>{{ __('SL') }}</th>
                                        <th data-priority="1">{{ __('Landlord') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Contact Number') }}</th>
                                        <th>{{ __('Properties') }}</th>
                                        <th>{{ __('Tenants') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                                <h4 class="mb-20">{{ __('All Tenants') }}</h4>
                                <table id="allPlatformTenantDataTable" class="table responsive theme-border p-20">
                                    <thead>
                                        <th>{{ __('SL') }}</th>
                                        <th data-priority="1">{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Contact Number') }}</th>
                                        <th>{{ __('Landlord') }}</th>
                                        <th>{{ __('Property') }}</th>
                                        <th>{{ __('Unit') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="landlordTenantRoute" value="{{ route('admin.monitoring.landlord-tenants') }}">
    <input type="hidden" id="allPlatformTenantRoute" value="{{ route('admin.monitoring.all-tenants') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/monitoring.js') }}"></script>
@endpush
