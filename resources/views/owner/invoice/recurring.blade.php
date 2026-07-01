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
                    <!-- All Property Area row Start -->
                    <div class="row">
                        <!-- Property Top Search Bar Start -->
                        <div class="property-top-search-bar">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="property-top-search-bar-left">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6 col-xl-4 mb-25">
                                                <select class="form-select flex-shrink-0" id="search_property">
                                                    <option value="" selected>{{ __('Select Property') }}</option>
                                                    @foreach ($properties as $property)
                                                        <option value="{{ $property->name }}">{{ $property->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="property-top-search-bar-right text-end">
                                        <button type="button" class="theme-btn mb-25" id="add"
                                            title="{{ __('New Recurring Setting') }}">{{ __('New Recurring Setting') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Property Top Search Bar End -->
                        <!-- Billing Center Area Start -->
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            <!-- datatable Start -->
                            <table id="allInvoiceDataTable" class="table theme-border dt-responsive">
                                <thead>
                                    <tr>
                                        <th>{{ __('Prefix') }}</th>
                                        <th>{{ __('Property') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Amount') }}</th>
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
    </div>

    {{-- Modal  --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">{{ __('New Recurring Setting') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.invoice.recurring-setting.store') }}" method="post"
                    data-handler="getShowMessage">
                    <div class="modal-body">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20 pb-0">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Invoice Prefix') }}</label>
                                    <input type="text" name="invoice_prefix" value="INV" class="form-control"
                                        placeholder="{{ __('Invoice Prefix') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="">--{{ __('Select Property') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}</label>
                                    <select class="form-select flex-shrink-0 propertyUnitSelectOption"
                                        name="property_unit_id">
                                        <option value="">--{{ __('Select Unit') }}--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Recurring Type') }}</label>
                                    <select class="form-select flex-shrink-0 recurring_type" name="recurring_type">
                                        <option value="">--{{ __('Select Type') }}--</option>
                                        <option value="1">{{ __('Monthly') }}</option>
                                        <option value="2">{{ __('Yearly') }}</option>
                                        <option value="3">{{ __('Custom') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25 d-none recurring_day">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Cycle Day') }}</label>
                                    <input type="number" name="cycle_day" class="form-control" autocomplete="off"
                                        placeholder="{{ __('Day') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Due Date After Invoice Creation') }}</label>
                                    <input type="number" name="due_day_after" class="form-control" autocomplete="off"
                                        placeholder="{{ __('5') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select class="form-select flex-shrink-0" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="multi-field-wrapper">
                            <div class="multi-fields">
                                <div class="multi-field border-bottom pb-25 mb-25">
                                    <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20">
                                        <input type="hidden" name="invoiceItem[id][]" class="" value="">
                                        <div class="row">
                                            <div class="col-md-6 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Invoice Type') }}</label>
                                                <select class="form-select flex-shrink-0 invoiceItem-invoice_type_id"
                                                    name="invoiceItem[invoice_type_id][]">
                                                    <option value="">--{{ __('Select Type') }}--</option>
                                                    @foreach ($invoiceTypes as $invoiceType)
                                                        <option value="{{ $invoiceType->id }}">{{ $invoiceType->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Amount') }}</label>
                                                <input type="number" name="invoiceItem[amount][]"
                                                    class="form-control invoiceItem-amount"
                                                    placeholder="{{ __('Amount') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                                                <textarea class="form-control invoiceItem-description" name="invoiceItem[description][]"
                                                    placeholder="{{ __('Description') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="remove-field red-color">{{ __('Remove') }}</button>
                                </div>
                            </div>
                            <button type="button" class="add-field theme-btn-purple pull-right">+
                                {{ __('Add Items') }}</button>
                        </div>
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

    <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editInvoiceModalLabel">{{ __('Edit Recurring Setting') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.invoice.recurring-setting.store') }}" method="post"
                    data-handler="getShowMessage">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20 pb-0">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Invoice Prefix') }}</label>
                                    <input type="text" name="invoice_prefix" value="INV" class="form-control"
                                        placeholder="{{ __('Invoice Prefix') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="">--{{ __('Select Property') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}</label>
                                    <select class="form-select flex-shrink-0 propertyUnitSelectOption"
                                        name="property_unit_id">
                                        <option value="">--{{ __('Select Option') }}--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Recurring Type') }}</label>
                                    <select class="form-select flex-shrink-0 recurring_type" name="recurring_type">
                                        <option value="">--{{ __('Select Type') }}--</option>
                                        <option value="1">{{ __('Monthly') }}</option>
                                        <option value="2">{{ __('Yearly') }}</option>
                                        <option value="3">{{ __('Custom') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25 d-none recurring_day">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Cycle Day') }}</label>
                                    <input type="number" name="cycle_day" class="form-control" autocomplete="off"
                                        placeholder="{{ __('Day') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Due Date After Invoice Creation') }}</label>
                                    <input type="number" name="due_day_after" class="form-control" autocomplete="off"
                                        placeholder="{{ __('5') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select class="form-select flex-shrink-0" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="multi-field-wrapper">
                            <div class="multi-fields">
                            </div>
                            <button type="button" class="add-field theme-btn-purple pull-right">+
                                {{ __('Add Items') }}</button>
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

    <div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-labelledby="invoicePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title theme-link pointer-auto" id="invoicePreviewModalLabel" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="iconify me-2" data-icon="eva:arrow-back-fill"></span>{{ __('Back') }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="invoice-preview-wrap">
                        <div class="invoice-heading-part">
                            <div class="invoice-heading-left">
                                <img src="{{ getSettingImage('app_logo') }}" alt="">
                                <h4 class="invoiceNo"></h4>
                            </div>
                            <div class="invoice-heading-right">
                                <div class="invoice-heading-right-status-btn invoiceStatus"></div>
                            </div>
                        </div>

                        <div class="invoice-address-part">
                            <div class="invoice-address-part-left">
                                <h4 class="invoice-generate-title">{{ __('Invoice To') }}</h4>
                                <div class="invoice-address">
                                    <h5 class="tenantName"></h5>
                                    <small class="tenantEmail"></small>
                                    <h6 class="propertyName"></h6>
                                    <small class="unitName"></small>
                                    <h6><span>{{ __('Recurring') }} : </span><span class="recurring"></span></h6>
                                </div>
                            </div>
                            <div class="invoice-address-part-right">
                                <h4 class="invoice-generate-title">{{ __('Pay To') }}</h4>
                                <div class="invoice-address">
                                    <h5>{{ getOption('app_name') }}</h5>
                                    <h6>{{ getOption('app_location') }}</h6>
                                    <small>{{ getOption('app_contact_number') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-table-part">
                            <h4 class="invoice-generate-title invoice-heading-color">{{ __('Invoice Items') }}</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="invoice-heading-color">{{ __('Type') }}</th>
                                            <th class="invoice-heading-color">{{ __('Description') }}</th>
                                            <th class="invoice-tbl-last-field invoice-heading-color">{{ __('Amount') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceItems">
                                    </tbody>
                                </table>
                            </div>

                            <div class="show-total-box">
                                <div class="invoice-tbl-last-field">{{ __('Total') }}: <span
                                        class="invoice-heading-color total"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" class="invoiceTypes" value="{{ $invoiceTypes }}">
    <input type="hidden" id="getPropertyUnitsRoute" value="{{ route('owner.property.getPropertyUnits') }}">
    <input type="hidden" id="invoiceRecurring" value="{{ route('owner.invoice.recurring-setting.index') }}">
    <input type="hidden" id="getTenantRentRoute" value="{{ route('owner.invoice.recurring-setting.tenant-rent', ':unitId') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/invoice-recurring.js') }}"></script>
@endpush
