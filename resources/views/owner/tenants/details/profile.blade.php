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
                                        <div class="remove-tenants-item">
                                            @if ($tenant->status != TENANT_STATUS_CLOSE)
                                                <button type="button" class="account-settings-menu-item red-color"
                                                    data-bs-toggle="modal" data-bs-target="#tenantCloseModal"
                                                    title="{{ __('Close Tenant') }}">
                                                    <span class="bg-red-transparent radius-4 overflow-hidden px-2 me-2"><i
                                                            class="ri-delete-back-2-line"></i></span>{{ __('Close Tenant') }}
                                                </button>
                                            @endif
                                            <button type="button" class="account-settings-menu-item red-color"
                                                data-bs-toggle="modal" data-bs-target="#tenantDeleteModal"
                                                title="{{ __('Delete Tenant') }}">
                                                <span class="bg-red-transparent radius-4 overflow-hidden px-2 me-2"><i
                                                        class="ri-delete-bin-2-line"></i></span>{{ __('Delete Tenant') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Account settings Left Side End-->

                            <!-- Account settings Area Right Side Start-->
                            <div class="col-md-12 col-lg-12 col-xl-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">

                                    <!-- Tenants Profile Information Start -->
                                    <div class="tenants-profile-information">

                                        <!-- Upload Profile Photo Box Start -->
                                        <div class="upload-profile-photo-box upload-profile-photo-with-delete-btn mb-25">
                                            <div class="profile-user position-relative d-inline-block">
                                                <img src="{{ $tenant->image }}"
                                                    class="rounded-circle avatar-xl user-profile-image"
                                                    alt="user-profile-image">
                                            </div>
                                        </div>
                                        <!-- Upload Profile Photo Box End -->

                                        <!-- Account Settings Content Box Start -->
                                        <div class="account-settings-content-box bg-white theme-border radius-4 mb-25 p-20">

                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ __('Personal Information') }}</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="property-details-right text-end">
                                                            <a href="{{ route('owner.tenant.edit', $tenant->id) }}"
                                                                class="edit-btn"
                                                                title="{{ __('Edit Info') }}">{{ __('Edit Info') }}<i
                                                                    class="ri-arrow-right-line ms-2"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="account-settings-info-box">
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Name') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->first_name }} {{ $tenant->last_name }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Contact Number') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->contact_number }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Email') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->email }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Age') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->age }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Family Members') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->family_member }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Job') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->job }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Account Settings Content Box End -->

                                        <!-- Account Settings Content Box Start -->
                                        <div class="account-settings-content-box bg-white theme-border radius-4 mb-25 p-20">

                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ __('Previous Address') }}</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="account-settings-info-box">
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Address') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->previous_address }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('City') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->previous_city_id }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('State') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->previous_state_id }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Zip Code') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->previous_zip_code }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Country') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->previous_country_id }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Account Settings Content Box End -->

                                        <!-- Account Settings Content Box Start -->
                                        <div class="account-settings-content-box bg-white theme-border radius-4 p-20">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ __('Permanent Address') }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="account-settings-info-box">
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Address') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->permanent_address }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('City') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->permanent_city_id }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('State') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->permanent_state_id }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Zip Code') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->permanent_zip_code }}</p>
                                                    </div>
                                                </div>
                                                <div class="row account-settings-info-item">
                                                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-2">
                                                        <p class="color-heading">{{ __('Country') }}:</p>
                                                    </div>
                                                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-10">
                                                        <p>{{ $tenant->permanent_country_id }}
                                                        </p>
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
    <!-- Add Currency Modal Start -->
    <div class="modal fade" id="tenantCloseModal" tabindex="-1" aria-labelledby="tenantCloseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tenantCloseModalLabel">{{ __('Tenant Close') }}</h4>
                    <div class="account-settings-menu-item "
                        title="{{ __('This Tenant have') }} {{ $paymentDueInvoiceCount }} {{ __('Invoice Due') }}">
                        {{ __('This Tenant have') }} <a href=""><span
                                class="bg-red-transparent red-color radius-4 overflow-hidden px-2 mx-2">{{ $paymentDueInvoiceCount }}</span></a>
                        {{ __('Due Invoice') }}
                    </div>
                </div>
                <form class="ajax" action="{{ route('owner.tenant.close.history.store', $tenant->id) }}"
                    method="POST" data-handler="closeStatusChange">
                    @csrf
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <div class="table-responsive">
                                        <table id="datatableBilling1" class="table theme-border p-20 dt-responsive">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="tenants-tbl-info-object d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ $tenant->image }}"
                                                                    class="rounded-circle avatar-md tbl-user-image"
                                                                    alt="">
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6>{{ $tenant->first_name }} {{ $tenant->last_name }}
                                                                </h6>
                                                                <p class="font-13">{{ $tenant->email }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td colspan="2">
                                                        <h6>{{ $tenant->property_name }}</h6>
                                                        <p class="font-13">{{ $tenant->unit_name }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>{{ __('General Rent') }}</h6>
                                                        <p class="font-13">{{ $tenant->general_rent }}</p>
                                                    </td>
                                                    <td>
                                                        <h6>{{ __('Securiry Deposit') }}</h6>
                                                        <p class="font-13">{{ $tenant->security_deposit }}</p>
                                                    </td>
                                                    <td>
                                                        <h6>{{ __('Late fee') }}</h6>
                                                        <p class="font-13">{{ $tenant->late_fee }}</p>
                                                    </td>
                                                    <td>
                                                        <h6>{{ __('Incident Receipt') }}</h6>
                                                        <p class="font-13">{{ $tenant->incident_receipt }}</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Refund Amount') }}</label>
                                    <input type="number" step="any" value="0" min="0"
                                        name="close_refund_amount" class="form-control"
                                        placeholder="{{ __('Refund Amount') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Closing Charge') }}</label>
                                    <input type="number" step="any" value="0" min="0"
                                        name="close_charge" class="form-control"
                                        placeholder="{{ __('Closing Charge') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Closing Date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" class="datepicker form-control" autocomplete="off"
                                                placeholder="dd-mm-yy" name="close_date">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Lease End Date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" class="datepicker form-control" autocomplete="off"
                                                placeholder="dd-mm-yy" value="{{ $tenant->lease_end_date }}" disabled>
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Closing Reason') }}</label>
                                    <textarea name="close_reason" id="close_reason" class="form-control" placeholder="{{ __('Reason') }}"></textarea>
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

    <div class="modal fade" id="tenantDeleteModal" tabindex="-1" aria-labelledby="tenantDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tenantDeleteModalLabel">{{ __('Tenant Delete') }}</h4>
                    <div class="account-settings-menu-item "
                        title="{{ __('This Tenant have') }} {{ $paymentDueInvoiceCount }} {{ __('Invoice Due') }}">
                        {{ __('This Tenant have') }} <a href=""><span
                                class="bg-red-transparent red-color radius-4 overflow-hidden px-2 mx-2">{{ $paymentDueInvoiceCount }}</span></a>
                        {{ __('Due Invoice') }}
                    </div>
                </div>
                <form class="ajax" action="{{ route('owner.tenant.delete') }}" method="POST"
                    data-handler="deleteShowResponse">
                    @csrf
                    <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <div class="table-responsive">
                                        <table id="datatableBilling1" class="table theme-border p-20 dt-responsive">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="tenants-tbl-info-object d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ $tenant->image }}"
                                                                    class="rounded-circle avatar-md tbl-user-image"
                                                                    alt="">
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6>{{ $tenant->first_name }} {{ $tenant->last_name }}
                                                                </h6>
                                                                <p class="font-13">{{ $tenant->email }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td colspan="2">
                                                        <h6>{{ $tenant->property_name }}</h6>
                                                        <p class="font-13">{{ $tenant->unit_name }}</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Write your tenant\'s email which you want to delete') }}</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="{{ __('Write your tenant\'s email which you want to delete') }}">
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->

                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Delete') }}">{{ __('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Currency Modal End -->
    <input type="hidden" id="tenantListRoute" value="{{ route('owner.tenant.index') }}">
@endsection
@push('script')
    <script src="{{ asset('assets/js/custom/tenant.js') }}"></script>
@endpush
