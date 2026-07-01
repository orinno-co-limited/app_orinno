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
                        <h4 class="mb-20">{{ __('All Maintanance') }}</h4>
                        <div class="property-top-search-bar">
                            <div class="property-search-inner-bg bg-off-white theme-border radius-4 p-25 mb-25">
                                <div class="row align-items-center rg-25">
                                    <div class="col-md-6">
                                        <div class="property-top-search-bar-left">
                                                    <select class="form-select flex-shrink-0 " id="search_property">
                                                        <option value="" selected>{{ __('Select Property') }}</option>
                                                        @foreach ($properties as $property)
                                                            <option value="{{ $property->name }}">{{ $property->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="property-top-search-bar-right text-md-end">
                                            <button type="button" class="theme-btn" id="add"
                                                title="{{ __('Add Maintenance Request') }}">{{ __('Add Maintenance Request') }}</button>
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
                                <table id="allMaintenanceDataTable" class="table bg-off-white aaa theme-border dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Request') }}</th>
                                            <th>{{ __('Property') }}</th>
                                            <th class="text-nowrap">{{ __('Unit Name') }}</th>
                                            <th data-priority="1">{{ __('Issue Name') }}</th>
                                            <th>{{ __('Details') }}</th>
                                            <th class="text-nowrap">{{ __('Created Date') }}</th>
                                            <th class="text-nowrap">{{ __('Resolved Date') }}</th>
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
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">{{ __('Add Maintenance Request') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.maintenance-request.store') }}" method="POST"
                    data-handler="getShowMessage">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="" selected>--{{ __('Select Property') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}</label>
                                    <select class="form-select flex-shrink-0 unit_id" name="unit_id">
                                        <option value="">--{{ __('Select Unit') }}--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Issue') }}</label>
                                    <select class="form-select flex-shrink-0 issue_id" name="issue_id">
                                        <option value="">--{{ __('Select Issue') }}--</option>
                                        @foreach ($issues as $issue)
                                            <option value="{{ $issue->id }}">{{ $issue->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select class="form-select flex-shrink-0 status" name="status">
                                        <option value="1">{{ __('Completed') }}</option>
                                        <option value="2">{{ __('In Progress') }}</option>
                                        <option value="3">{{ __('Pending') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-25">
                                <label
                                    class="label-text-title color-heading font-medium mb-2">{{ __('Created Date') }}</label>
                                <div class="custom-datepicker">
                                    <div class="custom-datepicker-inner position-relative">
                                        <input type="text" class="datepicker form-control start_date"
                                               name="created_date" autocomplete="off" placeholder="dd-mm-yy" required>
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea class="form-control details" name="details" placeholder="{{ __('Details') }}"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Attach') }}</label>
                                    <input type="file" class="form-control" name="attach">
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Add Request') }}">{{ __('Add Request') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">{{ __('Edit Maintenance Request') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.maintenance-request.store') }}" method="POST"
                    data-handler="getShowMessage">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="" selected>--{{ __('Select Property') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}</label>
                                    <select class="form-select flex-shrink-0 unit_id" name="unit_id">
                                        <option value="">--{{ __('Select Unit') }}--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Issue') }}</label>
                                    <select class="form-select flex-shrink-0 issue_id" name="issue_id">
                                        <option value="">--{{ __('Select Issue') }}--</option>
                                        @foreach ($issues as $issue)
                                            <option value="{{ $issue->id }}">{{ $issue->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select class="form-select flex-shrink-0 status" name="status">
                                        <option value="1">{{ __('Completed') }}</option>
                                        <option value="2">{{ __('In Progress') }}</option>
                                        <option value="3">{{ __('Pending') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Created Date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" class="datepicker form-control created_date"
                                                   name="created_date" autocomplete="off" placeholder="dd-mm-yy" required>
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea class="form-control details" name="details" placeholder="{{ __('Details') }}"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Attach') }}</label>
                                    <input type="file" class="form-control" name="attach">
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
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

    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewModalLabel">{{ __('Details') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.maintenance-request.status.change') }}" method="POST"
                    data-handler="getShowMessage">
                    <input type="hidden" name="id" id="viewId">
                    <div class="modal-body">
                        <div class="view-information-page-modal-content">
                            <div class="maintenance-request-view-top-box mb-25">
                                <div class="row align-items-start">
                                    <div class="col-md-8">
                                        <div class="view-information-page-box mb-25">
                                            <label
                                                class="label-text-title color-heading font-medium mb-2">{{ __('Issue') }}</label>
                                            <p class="issue_name"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 text-start text-lg-end">
                                        <select class="form-select status" name="status" id="statusSelect">
                                            <option value="1">{{ __('Completed') }}</option>
                                            <option value="2">{{ __('In Progress') }}</option>
                                            <option value="3">{{ __('Pending') }}</option>
                                        </select>
                                    </div>
                                    <div class="resolved-date-field d-none-content">
                                        <div class="col-md-6 mb-25">
                                            <label class="label-text-title color-heading font-medium mb-2">{{__('Resolved Date')}}</label>
                                            <div class="custom-datepicker">
                                                <div class="custom-datepicker-inner position-relative">
                                                    <input type="text" class="datepicker form-control resolved_date" name="resolved_date"
                                                           autocomplete="off" placeholder="dd-mm-yy">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-start">
                                    <div class="col-md-6">
                                        <div class="view-information-page-box ">
                                            <label
                                                class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}
                                                : </label>
                                            <span class="property_name"></span>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="view-information-page-box ">
                                            <label
                                                class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}
                                                : </label>
                                            <span class="unit_name"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="view-information-page-box mb-25">
                                <label class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                <p class="view_details"></p>
                            </div>
                            <div class="view-information-page-box mb-25">
                                <label class="label-text-title color-heading font-medium mb-2">{{ __('Attach') }} :
                                </label>
                                <a href="" class="attach" target="_blank"></a>
                            </div>
                            <div class="view-information-page-box mb-25">
                                <label class="label-text-title color-heading font-medium mb-2">{{ __('Amount') }}</label>
                                <input type="number" class="form-control amount" name="amount" step="any"
                                    value="0" placeholder="{{ __('Amount') }}">
                            </div>
                            <div class="view-information-page-box mb-25">
                                <label class="label-text-title color-heading font-medium mb-2">{{ __('Invoice') }}</label>
                                <input type="file" class="form-control" name="invoice">
                                <small><a href="" target="_blank" class="invoice"></a></small>
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
    <!-- Add Information Modal End -->
    <input type="hidden" id="getInfoRoute" value="{{ route('owner.maintenance-request.get.info') }}">
    <input type="hidden" id="route" value="{{ route('owner.maintenance-request.index') }}">
    <input type="hidden" id="getPropertyUnitsRoute" value="{{ route('owner.property.getPropertyUnits') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/maintenance-request.js') }}"></script>
@endpush
