@extends('tenant.layouts.app')

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
                        <h4 class="mb-20">{{ __('All Maintenance') }}</h4>
                        <div class="property-top-search-bar">
                            <div class="property-search-inner-bg bg-off-white theme-border radius-4 p-25 pb-0 mb-25">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="property-top-search-bar-right text-end">
                                            <button type="button" class="theme-btn mb-25" id="add"
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
                                <table id="allMaintenanceRequestDataTable" class="table bg-off-white aaa theme-border dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Request Id') }}</th>
                                            <th>{{ __('Issue Name') }}</th>
                                            <th>{{ __('Details') }}</th>
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
                <form class="ajax" action="{{ route('tenant.maintenance-request.store') }}" method="POST"
                    data-handler="getShowMessage">
                    <input type="hidden" name="property_id" value="{{ $tenant->property_id }}">
                    <input type="hidden" name="unit_id" value="{{ $tenant->unit_id }}">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Issue') }}</label>
                                    <select class="form-select flex-shrink-0 issue_id" name="issue_id">
                                        <option value="">--{{ __('Select Issue') }}--</option>
                                        @foreach ($issues as $issue)
                                            <option value="{{ $issue->id }}">{{ $issue->name }}</option>
                                        @endforeach
                                    </select>
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
                <form class="ajax" action="{{ route('tenant.maintenance-request.store') }}" method="POST"
                    data-handler="getShowMessage">
                    <input type="hidden" name="property_id" value="{{ $tenant->property_id }}">
                    <input type="hidden" name="unit_id" value="{{ $tenant->unit_id }}">
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Issue') }}</label>
                                    <select class="form-select flex-shrink-0 issue_id" name="issue_id">
                                        <option value="">--{{ __('Select Issue') }}--</option>
                                        @foreach ($issues as $issue)
                                            <option value="{{ $issue->id }}">{{ $issue->name }}</option>
                                        @endforeach
                                    </select>
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
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- Add Information Modal End -->
    <input type="hidden" id="maintenanceIndexRoute" value="{{ route('tenant.maintenance-request.index') }}">
    <input type="hidden" id="getInfoRoute" value="{{ route('tenant.maintenance-request.get.info') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/tenant-maintenance-request.js') }}"></script>
@endpush
