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
                    <!-- All tenants Area row Start -->
                    <div class="row">
                        <!-- Tenants Top Bar Start -->
                        <div class="tenants-top-bar">
                            <div class="property-search-inner-bg bg-off-white theme-border radius-4 p-25 pb-0 mb-25">
                                <div class="row">
                                    <div class="col-xl-12 col-xxl-6 tenants-top-bar-left">
                                        <div class="row">
                                            @if (getOption('app_card_data_show', 1) == 1)
                                                <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-4 mb-25">
                                                    <select class="form-select flex-shrink-0 property_id">
                                                        <option value="0">--{{ __('Select Property') }}--</option>
                                                        @foreach ($properties as $property)
                                                            <option value="{{ $property->id }}">{{ $property->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-4 mb-25">
                                                    <select class="form-select flex-shrink-0 unit_id">
                                                        <option value="0" selected>--{{ __('Select Unit') }}--</option>
                                                    </select>
                                                </div>
                                                <div class="col-auto mb-25">
                                                    <button type="button" class="default-btn theme-btn-purple w-auto"
                                                        id="applySearch"
                                                        title="{{ __('Apply') }}">{{ __('Apply') }}</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-xxl-6 tenants-top-bar-right">
                                        <div class="row justify-content-end">
                                            <div class="col-auto mb-25">
                                                <a href="{{ route('owner.tenant.create') }}" class="theme-btn w-auto"
                                                    title="{{ __('Add New Tenant') }}">{{ __('Add New Tenant') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tenants Top Bar End -->

                        <!-- Tenants Item Wrap Start -->
                        <div class="properties-item-wrap">
                            <div class="row">
                                @if (getOption('app_card_data_show', 1) == 1)
                                    @forelse ($tenants as $tenant)
                                        <div
                                            class="single-tenant property-{{ $tenant->property_id }} up-{{ $tenant->property_id }}-{{ $tenant->unit_id }} col-md-6 col-lg-6 col-xl-6 col-xxl-4 d-none">
                                            <div
                                                class="property-item tenants-item bg-off-white theme-border radius-10 mb-25">
                                                <div class="property-item-content tenants-item-content p-20">
                                                    <div
                                                        class="property-item-address tenants-img-info-box d-flex align-items-center mb-20">
                                                        <div class="flex-shrink-0 font-13">
                                                            <div class="tenant-img bg-img-property radius-4"
                                                                style="background-image: url({{ $tenant->image }});"></div>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h4 class="mb-1">{{ $tenant->first_name }}
                                                                {{ $tenant->last_name }}</h4>
                                                            <p class="font-13 text-break">{{ $tenant->email }}</p>
                                                        </div>
                                                        <a href="{{ route('owner.tenant.edit', $tenant->id) }}"
                                                            class="p-1 tbl-action-btn" title="{{ __('Edit') }}"><span
                                                                class="iconify"
                                                                data-icon="material-symbols:edit-square-outline"></span></a>
                                                    </div>
                                                    <div class="tenants-item-info bg-white radius-4 theme-border">
                                                        <div class="border-bottom tenants-item-info-box">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-5">
                                                                    <div class="tenants-info-left font-13 color-heading">
                                                                        {{ __('Contact No.') }}</div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="tenants-info-right font-13 text-end"><i
                                                                            class="ri-phone-fill me-2"></i><a
                                                                            href="tel:{{ $tenant->contact_number }}">{{ $tenant->contact_number }}</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-bottom tenants-item-info-box">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-5">
                                                                    <div class="tenants-info-left font-13 color-heading">
                                                                        {{ __('Property') }}</div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="tenants-info-right font-13 text-end">
                                                                        {{ $tenant->property_name }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-bottom tenants-item-info-box">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-5">
                                                                    <div class="tenants-info-left font-13 color-heading">
                                                                        {{ __('Unit') }}</div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="tenants-info-right font-13 text-end">
                                                                        {{ $tenant->unit_name }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-bottom tenants-item-info-box">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-5">
                                                                    <div class="tenants-info-left font-13 color-heading">
                                                                        {{ __('Last Rent Paid') }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="tenants-info-right font-13 text-end">
                                                                        {{ $tenant->last_payment ? date('Y-m-d', strtotime($tenant->last_payment)) : 'N/A' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-bottom tenants-item-info-box">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-5">
                                                                    <div class="tenants-info-left font-13 color-heading">
                                                                        {{ __('Current Rent') }}</div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="tenants-info-right font-13 text-end">
                                                                        {{ $tenant->general_rent }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-bottom tenants-item-info-box">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-5">
                                                                    <div class="tenants-info-left font-13 color-heading">
                                                                        {{ __('Previous Due') }}</div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="tenants-info-right font-13 text-end"><span
                                                                            class="bg-red-transparent radius-4 px-2 py-1 red-color">{{ currencyPrice($tenant->due) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="border-bottom-0 tenants-item-info-box">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-5">
                                                                    <div class="tenants-info-left font-13 color-heading">
                                                                        {{ __('Status') }}</div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="tenants-info-right font-13 text-end">
                                                                        @if ($tenant->userStatus == USER_STATUS_DELETED)
                                                                            <span
                                                                                class="bg-red-transparent radius-4 px-2 py-1 red-color">{{ __('Deleted') }}</span>
                                                                        @else
                                                                            @if ($tenant->status == TENANT_STATUS_ACTIVE)
                                                                                <span
                                                                                    class="bg-green-transparent radius-4 px-2 py-1 green-color">{{ __('Active') }}</span>
                                                                            @elseif($tenant->status == TENANT_STATUS_INACTIVE)
                                                                                <span
                                                                                    class="bg-red-transparent radius-4 px-2 py-1 red-color">{{ __('Inactive') }}</span>
                                                                            @elseif($tenant->status == TENANT_STATUS_CLOSE)
                                                                                <span
                                                                                    class="bg-orange-transparent radius-4 px-2 py-1 orange-color">{{ __('Close') }}</span>
                                                                            @else
                                                                                <span
                                                                                    class="bg-blue-transparent radius-4 px-2 py-1 blue-color">{{ __('Draft') }}</span>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('owner.tenant.details', [$tenant->id, 'tab' => 'profile']) }}"
                                                        class="theme-btn mt-20 w-100"
                                                        title="{{ __('View Details') }}">{{ __('View Details') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <!-- Empty Properties row -->
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="empty-properties-box text-center">
                                                    <img src="{{ asset('assets/images/empty-img.png') }}" alt=""
                                                        class="img-fluid">
                                                    <h3 class="mt-25">{{ __('Empty') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Empty Properties row -->
                                    @endforelse
                                @else
                                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                        <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                            <div class="tenants-details-payment-history">
                                                <div class="account-settings-content-box">
                                                    <div class="tenants-details-payment-history-table">
                                                        <table id="allTenantDataTable"
                                                            class="table responsive theme-border p-20">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('SL') }}</th>
                                                                    <th data-priority="1">{{ __('Name') }}</th>
                                                                    <th></th>
                                                                    <th>{{ __('Property') }}</th>
                                                                    <th>{{ __('Unit') }}</th>
                                                                    <th>{{ __('Contact No.') }}</th>
                                                                    <th>{{ __('Current Rent') }}</th>
                                                                    <th>{{ __('Last Rent Paid') }}</th>
                                                                    <th>{{ __('Previous Due') }}</th>
                                                                    <th>{{ __('Status') }}</th>
                                                                    <th>{{ __('Action') }}</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Tenants Item Wrap End -->
                    </div>
                    <!-- All tenants Area row End -->
                </div>
                <!-- Page Content Wrapper End -->
            </div>
        </div>
        <!-- End Page-content -->
    </div>
    <input type="hidden" id="getAllTenantRoute" value="{{ route('owner.tenant.index', ['type' => 'all']) }}">
    <input type="hidden" id="getPropertyUnitsRoute" value="{{ route('owner.property.getPropertyUnits') }}">

@endsection
@if (getOption('app_card_data_show', 1) != 1)
    @push('style')
        @include('common.layouts.datatable-style')
    @endpush
    @push('script')
        @include('common.layouts.datatable-script')
        <script src="{{ asset('assets/js/custom/tenant-datatable.js') }}"></script>
    @endpush
@endif
@push('script')
    <script src="{{ asset('assets/js/custom/tenant-list.js') }}"></script>
@endpush
