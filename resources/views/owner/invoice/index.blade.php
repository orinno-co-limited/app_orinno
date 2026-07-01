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
                                        <button type="button" class="theme-btn-purple me-2 mb-25" id="reminderGroup"
                                            title="{{ __('Send Group Reminder') }}">
                                            <span class="iconify font-12 me-2"
                                                data-icon="clarity:notification-solid"></span>{{ __('Send Group Reminder') }}
                                        </button>
                                        <button type="button" class="theme-btn mb-25" id="add"
                                            title="{{ __('New Invoice') }}">{{ __('New Invoice') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            <div class="tbl-tab-wrap border-bottom pb-25 mb-25">
                                <ul class="nav nav-tabs billing-center-nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="table1-tab" data-bs-toggle="tab"
                                                data-bs-target="#table1-tab-pane" type="button" role="tab"
                                                aria-controls="table1-tab-pane" aria-selected="true">
                                            {{ __('All') }} ({{ $totalInvoice }})
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="table2-tab" data-bs-toggle="tab"
                                                data-bs-target="#table2-tab-pane" type="button" role="tab"
                                                aria-controls="table2-tab-pane" aria-selected="false">
                                            {{ __('Paid') }} ({{ $totalPaidInvoice }})
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="table3-tab" data-bs-toggle="tab"
                                                data-bs-target="#table3-tab-pane" type="button" role="tab"
                                                aria-controls="table3-tab-pane" aria-selected="false">
                                            {{ __('Pending') }} ({{ $totalPendingInvoice }})
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tableBank-tab" data-bs-toggle="tab"
                                                data-bs-target="#tableBank-tab-pane" type="button" role="tab"
                                                aria-controls="tableBank-tab-pane" aria-selected="false">
                                            {{ __('Bank Pending') }} ({{ $totalBankPendingInvoice }})
                                        </button>
                                    </li>
                                    {{-- <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="table4-tab" data-bs-toggle="tab"
                                            data-bs-target="#table4-tab-pane" type="button" role="tab"
                                            aria-controls="table4-tab-pane" aria-selected="false">
                                            {{ __('OverDue') }} ({{ $totalOverDueInvoice }})
                                        </button>
                                    </li> --}}
                                </ul>
                            </div>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="table1-tab-pane" role="tabpanel"
                                    aria-labelledby="table1-tab" tabindex="0">
                                    <table id="allInvoiceDataTable" class="table theme-border dt-responsive">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice') }}</th>
                                                <th>{{ __('Property') }}</th>
                                                <th>{{ __('Due Date') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Amount') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Payment Status') }}</th>
                                                <th class="tablet-l">{{ __('Gateway') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="table2-tab-pane" role="tabpanel" aria-labelledby="table2-tab"
                                    tabindex="0">
                                    <table id="paidInvoiceDataTable" class="table theme-border dt-responsive">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice') }}</th>
                                                <th>{{ __('Property') }}</th>
                                                <th>{{ __('Due Date') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Amount') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Payment Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="table3-tab-pane" role="tabpanel"
                                    aria-labelledby="table3-tab" tabindex="0">
                                    <table id="pendingInvoiceDataTable" class="table theme-border dt-responsive">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice') }}</th>
                                                <th>{{ __('Property') }}</th>
                                                <th>{{ __('Due Date') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Amount') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Payment Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="tableBank-tab-pane" role="tabpanel"
                                    aria-labelledby="tableBank-tab" tabindex="0">
                                    <table id="bankPendingInvoiceDataTable" class="table theme-border dt-responsive">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice') }}</th>
                                                <th>{{ __('Property') }}</th>
                                                <th>{{ __('Due Date') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Amount') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Payment Status') }}</th>
                                                <th class="tablet-l">{{ __('Gateway') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="table4-tab-pane" role="tabpanel"
                                     aria-labelledby="table4-tab" tabindex="0">
                                    <!-- datatable Start -->
                                    <table id="overdueInvoiceDataTable" class="table theme-border dt-responsive">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice') }}</th>
                                                <th>{{ __('Property') }}</th>
                                                <th>{{ __('Due Date') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Amount') }}</th>
                                                <th class="tablet-l tablet-p">{{ __('Payment Status') }}</th>
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
        </div>
    </div>

    {{-- Modal  --}}
    <div class="modal fade" id="createNewInvoiceModal" tabindex="-1" aria-labelledby="createNewInvoiceModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createNewInvoiceModalLabel">{{ __('New Invoice') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.invoice.store') }}" method="post"
                      data-handler="getShowMessage">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20 pb-0">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Invoice Prefix') }}</label>
                                    <input type="text" name="name" value="INV" class="form-control">
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
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Month') }}</label>
                                    <select class="form-select flex-shrink-0" name="month">
                                        <option value="">--{{ __('Select Month') }}--</option>
                                        @foreach (month() as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Due Date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" name="due_date" class="datepicker form-control"
                                                   autocomplete="off" placeholder="{{ __('Due Date') }}">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
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
                                                <textarea class="form-control invoiceItem-description"
                                                          name="invoiceItem[description][]"
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
                                title="{{ __('Create Invoice') }}">{{ __('Create Invoice') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade edit_modal" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editInvoiceModalLabel">{{ __('Edit Invoice') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.invoice.store') }}" method="post"
                      data-handler="getShowMessage">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20 pb-0">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Invoice Prefix') }}</label>
                                    <input type="text" name="name" value="INV" class="form-control">
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
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Month') }}</label>
                                    <select class="form-select flex-shrink-0" name="month">
                                        <option value="">--{{ __('Select Month') }}--</option>
                                        @foreach (month() as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Due Date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" name="due_date" class="datepicker form-control"
                                                   autocomplete="off" placeholder="{{ __('Due Date') }}">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
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

    <div class="modal fade" id="payStatusChangeModal" tabindex="-1" aria-labelledby="payStatusChangeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="payStatusChangeModalLabel">{{ __('Payment Status Change') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.invoice.payment.status') }}" method="post"
                      data-handler="getShowMessage">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20 pb-0">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select class="form-select flex-shrink-0" name="status">
                                        <option value="">--{{ __('Select Option') }}--</option>
                                        <option value="0">{{ __('Pending') }}</option>
                                        <option value="1">{{ __('Paid') }}</option>
                                    </select>
                                </div>
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

    <div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-labelledby="invoicePreviewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title theme-link pointer-auto" id="invoicePreviewModalLabel" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="iconify me-2" data-icon="eva:arrow-back-fill"></span>{{ __('Back') }}
                    </h4>
                    <div>
                        <a href="#" id="invoicePay" class="theme-btn-purple" data-bs-toggle="modal"
                           data-bs-target="#invoicePayModal">
                            {{ __('Make Paid') }}
                            <span class="iconify ms-2" data-icon="wpf:paid"></span>
                        </a>

                        <a href="" id="downloadInvoice" class="download-invoice theme-btn-green"
                           target="_blank">{{ __('Print') }}<span class="iconify ms-2" data-icon="fa:print"></span></a>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="invoice-preview-wrap">
                        <div class="invoice-heading-part">
                            <div class="invoice-heading-left">
                                <img src="{{ getSettingImage('app_logo') }}" alt="">
                                <h4 class="invoiceNo"></h4>
                                <p class="invoicePayDate"></p>
                                <p class="invoiceMonth"></p>
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
                            <div class="">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="invoice-heading-color">{{ __('Type') }}</th>
                                        <th class="invoice-heading-color">{{ __('Description') }}</th>
                                        <th class="invoice-tbl-last-field invoice-heading-color">{{ __('Amount') }}
                                        </th>
                                        <th class="invoice-tbl-last-field invoice-heading-color">{{ __('Tax') }}
                                        </th>
                                        <th class="invoice-tbl-last-field invoice-heading-color">{{ __('Total') }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="invoiceItems">
                                    </tbody>
                                </table>
                            </div>
                            <div class="show-total-box">
                                <div class="invoice-tbl-last-field">{{ __('Late Fee') }}: <span
                                        class="invoice-heading-color late_fee"></span></div>
                                <div class="invoice-tbl-last-field">{{ __('Total') }}: <span
                                        class="invoice-heading-color total"></span></div>
                            </div>
                        </div>
                        <div class="transaction-table-part">
                            <h4 class="invoice-generate-title invoice-heading-color">{{ __('Transaction Details') }}</h4>
                            <div class="">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="invoice-heading-color">{{ __('Date') }}</th>
                                        <th class="invoice-heading-color">{{ __('Gateway') }}</th>
                                        <th class="invoice-heading-color">{{ __('Transaction ID') }}</th>
                                        <th class="invoice-tbl-last-field invoice-heading-color">{{ __('Amount') }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="orderDate"></td>
                                        <td class="orderPaymentTitle"></td>
                                        <td class="orderPaymentId"></td>
                                        <td class="orderTotal invoice-tbl-last-field"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- notification modal  --}}
    <div class="modal fade" id="reminderModal" tabindex="-1" aria-labelledby="reminderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="reminderModalLabel">{{ __('Send Reminder') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.invoice.send.notification') }}" method="post"
                      data-handler="getShowMessage">
                    <input type="hidden" name="invoice_id" class="" value="">
                    <input type="hidden" name="notification_type" value="2">
                    <div class="modal-body">

                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input name="title" class="form-control" placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Body') }}</label>
                                    <textarea class="form-control" name="body"
                                              placeholder="{{ __('Description') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                                title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                                title="{{ __('Send') }}">{{ __('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reminderGroupModal" tabindex="-1" aria-labelledby="reminderGroupModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="reminderGroupModalLabel">{{ __('Send Group Reminder') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.invoice.send.notification') }}" method="POST"
                      enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" name="notification_type" value="1">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property Name') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id" required>
                                        <option value="" selected>--{{ __('Select Property') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-20">
                                        <div class="form-group custom-checkbox" title="{{ __('All Property') }}">
                                            <input type="checkbox" id="checkNoticeBoardAllProperty" name="all_property">
                                            <label class="color-heading font-medium"
                                                   for="checkNoticeBoardAllProperty">{{ __('All Property') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}</label>
                                    <select class="form-select flex-shrink-0 propertyUnitSelectOption unit_id"
                                            name="unit_id" required>
                                        <option value="" selected>--{{ __('Select Unit') }}--</option>
                                    </select>
                                    <div class="mt-20">
                                        <div class="form-group custom-checkbox" title="{{ __('All Unit') }}">
                                            <input type="checkbox" id="checkNoticeBoardAllUnit" name="all_unit">
                                            <label class="color-heading font-medium"
                                                   for="checkNoticeBoardAllUnit">{{ __('All Unit') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input name="title" class="form-control" placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Body') }}</label>
                                    <textarea class="form-control" name="body"
                                              placeholder="{{ __('Description') }}"></textarea>
                                </div>
                            </div>
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

    <div class="modal fade" id="invoicePayModal" tabindex="-1" aria-labelledby="invoicePayModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editInvoiceModalLabel">{{__('Paid the Invoice')}} <span
                            class="invoiceNo"></span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="" id="invoicePayForm" method="post"
                      data-handler="getShowMessage">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 pb-0">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Gateway') }}</label>
                                    <select class="form-select flex-shrink-0" id="payment-gateway-id" name="gateway_id">
                                        @foreach ($gateways as $gateway)
                                            <option data-slug="{{$gateway->slug}}" value="{{ $gateway->id }}">{{ $gateway->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-25 d-none" id="bankDiv">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Bank') }}</label>
                                    <select class="form-select flex-shrink-0" name="bank_id">
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}"
                                                    data-details="{{ $bank->details }}">
                                                {{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Payment Currency') }}</label>
                                    <select class="form-select flex-shrink-0" id="payment-gateway-currency" name="currency_id">

                                    </select>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Transaction ID/Payment Note') }}</label>
                                    <input type="text" name="transactionId" class="form-control"
                                           placeholder="{{ __('Transaction ID') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                                title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                                title="{{ __('Make Paid') }}">{{ __('Make Paid') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" class="invoiceTypes" value="{{ $invoiceTypes }}">
    <input type="hidden" id="getPropertyUnitsRoute" value="{{ route('owner.property.getPropertyUnits') }}">
    <input type="hidden" id="invoiceIndex" value="{{ route('owner.invoice.index') }}">
    <input type="hidden" id="invoicePaid" value="{{ route('owner.invoice.paid') }}">
    <input type="hidden" id="invoicePending" value="{{ route('owner.invoice.pending') }}">
    <input type="hidden" id="invoiceBankPending" value="{{ route('owner.invoice.bank.pending') }}">
    <input type="hidden" id="invoiceOverdue" value="{{ route('owner.invoice.overdue') }}">
    <input type="hidden" id="invoicePrint" value="{{ route('owner.invoice.print', '@') }}">
    <input type="hidden" id="invoicePayUrl" value="{{ route('owner.invoice.pay', '@') }}">
    <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('owner.invoice.get.currency') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')

    <!-- Datatable init js -->
    <script src="{{ asset('/') }}assets/js/pages/billing-center-datatables.init.js"></script>
{{--    <script src="{{ asset('assets/js/custom/invoice.js') }}"></script>--}}

    <script>
        // (function ($) {
        //     "use strict"
        var stateSelector;
        var invoiceTypes = JSON.parse($('.invoiceTypes').val());
        var typesHtml = '';
        Object.entries(invoiceTypes).forEach((type) => {
            typesHtml += '<option value="' + type[1].id + '">' + type[1].name + '</option>';
        });
        $('#createReminderModal').on('click', function () {
            $('#showFormNoticeModal').modal('toggle');
        });

        $('#add').on('click', function () {
            var selector = $('#createNewInvoiceModal');
            selector.find('.is-invalid').removeClass('is-invalid');
            selector.find('.error-message').remove();
            selector.modal('show')
            selector.find('form').trigger('reset');
        });

        $(document).on("click", ".add-field", function () {
            $(this).closest('form').find('.multi-fields').append(
                `<div class="multi-field border-bottom pb-25 mb-25">
                <input type="hidden" name="invoiceItem[id][]" class="" value="">
                <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20">
                    <div class="row">
                        <div class="col-md-6 mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{__('Invoice Type')}}</label>
                            <select class="form-select flex-shrink-0 invoiceItem-invoice_type_id" name="invoiceItem[invoice_type_id][]">
                                <option value="">{{__('--Select Type--')}}</option>
                               ${typesHtml}
                            </select>
                        </div>
                        <div class="col-md-6 mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{__('Amount')}}</label>
                            <input type="text" name="invoiceItem[amount][]" class="form-control invoiceItem-amount" placeholder="{{__('Amount')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="label-text-title color-heading font-medium mb-2">{{__('Description')}}</label>
                            <textarea class="form-control invoiceItem-description" name="invoiceItem[description][]" placeholder="{{__('Description')}}"></textarea>
                        </div>
                    </div>
                </div>
                <button type="button" class="remove-field red-color">{{__('Remove')}}</button>
            </div>`
            )
        });

        $(document).on("click", ".remove-field", function () {
            $(this).parent(".multi-field").remove();
        });

        $(document).on("click", ".edit", function () {
            stateSelector = $('.edit_modal');
            let detailsUrl = $(this).data('detailsurl');
            commonAjax('GET', detailsUrl, getDataEditRes, getDataEditRes);
        })

        function getDataEditRes(response) {
            var selector = $('.edit_modal');
            selector.find('.is-invalid').removeClass('is-invalid');
            selector.find('.error-message').remove();
            selector.modal('show')
            selector.find('input[name=id]').val(response.data.invoice.id)
            selector.find('input[name=name]').val(response.data.invoice.name)
            selector.find('select[name=property_id]').val(response.data.invoice.property_id)
            getPropertyUnits(response.data.invoice.property_id)
            setTimeout(() => {
                selector.find('select[name=property_unit_id]').val(response.data.invoice.property_unit_id)
            }, 2000);
            selector.find('select[name=month]').val(response.data.invoice.month)
            selector.find('input[name=due_date]').val(response.data.invoice.due_date)

            var html = '';
            Object.entries(response.data.items).forEach((item) => {
                var typesHtml = '';
                Object.entries(invoiceTypes).forEach((type) => {
                    if (type[1].id == item[1].invoice_type_id) {
                        typesHtml += '<option value="' + type[1].id + '" selected>' + type[1].name + '</option>';
                    } else {
                        typesHtml += '<option value="' + type[1].id + '">' + type[1].name + '</option>';
                    }
                });
                html += `<div class="multi-field border-bottom pb-25 mb-25">
                        <input type="hidden" name="invoiceItem[id][]" class="" value="${item[1].id}">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{__('Invoice Type')}}</label>
                                    <select class="form-select flex-shrink-0 invoiceItem-invoice_type_id" name="invoiceItem[invoice_type_id][]">
                                        <option value="">{{__('--Select Type--')}}</option>
                                    ${typesHtml}
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{__('Amount')}}</label>
                                    <input type="text" name="invoiceItem[amount][]" class="form-control invoiceItem-amount" placeholder="{{__('Amount')}}" value="${item[1].amount}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium mb-2">{{__('Description')}}</label>
                                    <textarea class="form-control invoiceItem-description" name="invoiceItem[description][]" placeholder="{{__('Description')}}">${item[1].description}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="remove-field red-color">{{__('Remove')}}</button>
                    </div>`
            });
            selector.find('.multi-fields').html(html);
        }

        $(document).on('click', '.payStatus', function () {
            let detailsUrl = $(this).data('detailsurl');
            commonAjax('GET', detailsUrl, getDetailsShowRes, getDetailsShowRes);
        });

        function getDetailsShowRes(response) {
            const selector = $('#payStatusChangeModal');
            selector.find('input[name=id]').val(response.data.invoice.id)
            selector.find('select[name=status]').val(response.data.invoice.status)
            selector.modal('show')
        }

        window.view = function (url) {
            commonAjax('GET', url, getDetailsViewRes, getDetailsViewRes);
        }

        $(document).on('click', '.view', function () {
            let detailsUrl = $(this).data('detailsurl');
            commonAjax('GET', detailsUrl, getDetailsViewRes, getDetailsViewRes);
        });

        function getDetailsViewRes(response) {
            // console.log(response, 'response');

            const selector = $('#invoicePreviewModal');
            selector.modal('show')
            let invoicePrintUrl = $('#invoicePrint').val();
            let invoicePayUrl = $('#invoicePayUrl').val();
            $(document).find('#invoicePayModal').find('.invoiceNo').text(response.data.invoice.invoice_no)
            $(document).find('#invoicePayModal').find('#invoicePayForm').attr('action', invoicePayUrl.replace("@", response.data.invoice.id))
            selector.find('#downloadInvoice').attr('href', invoicePrintUrl.replace("@", response.data.invoice.id))
            selector.find('.invoiceNo').text(response.data.invoice.invoice_no)
            selector.find('.invoicePayDate').text(dateFormat(response.data.invoice.updated_at, "YY-MM-DD"))
            selector.find('.invoiceMonth').text(response.data.invoice.month)

            if(response.data.invoice.status !== 1){
                $(document).find('#invoicePay').removeClass('d-none');
            }else{
                $(document).find('#invoicePay').addClass('d-none');
            }
            var status = 'Pending';
            if (response.data.invoice.status == '1') {
                status = "Paid"
            }
            selector.find('.invoiceStatus').html(status)

            selector.find('.tenantName').html(response.data.tenant.first_name + ' ' + response.data.tenant.last_name)
            selector.find('.tenantEmail').html(response.data.tenant.email)
            selector.find('.propertyName').html(response.data.tenant.property_name)
            selector.find('.unitName').html(response.data.tenant.unit_name)

            var html = '';
            Object.entries(response.data.items).forEach((item) => {
                var typeName;
                Object.entries(invoiceTypes).forEach((type) => {
                    if (type[1].id == item[1].invoice_type_id) {
                        typeName = type[1].name;
                    }
                });
                html += `<tr>
                        <td>${typeName}</td>
                        <td>${item[1].description}</td>
                        <td class="invoice-tbl-last-field">${currencyPrice(item[1].amount)}</td>
                        <td class="invoice-tbl-last-field">${currencyPrice(item[1].tax_amount)}</td>
                        <td class="invoice-tbl-last-field">${currencyPrice(parseFloat(parseFloat(item[1].amount) + parseFloat(item[1].tax_amount)).toFixed(2))}</td>
                    </tr>`;
            });
            selector.find('#invoiceItems').html(html);
            var owner_html = '<h5>'+response.data.owner.print_name+'</h5>' +
                ' <h6>'+response.data.owner.print_address+'</h6>' +
                ' <small>'+response.data.owner.print_contact+'</small>';
            selector.find('.invoice-address').html(owner_html);
            selector.find('.total').html(currencyPrice(Number(response.data.invoice.amount) + Number(response.data.invoice.late_fee)));
            if (response.data.invoice.late_fee > 0) {
                selector.find('.late_fee').html(currencyPrice(Number(response.data.invoice.late_fee)));
            } else {
                selector.find('.late_fee').html(currencyPrice(0.00));
            }
            if (response.data.order != null) {
                selector.find('.orderDate').html(dateFormat(response.data.order.created_at, 'YYYY-MM-DD'))
                selector.find('.orderPaymentTitle').html(response.data.order.gatewayTitle ?? 'Cash')
                if (response.data.order.payment_id != null) {
                    selector.find('.orderPaymentId').html(response.data.order.payment_id)
                }else{
                    selector.find('.orderPaymentId').html(response.data.order.transaction_id)
                }
                selector.find('.orderTotal').html(currencyPrice(response.data.order.total))
            } else {
                selector.find('.orderDate').html()
                selector.find('.orderPaymentTitle').html()
                selector.find('.orderPaymentId').html()
                selector.find('.orderTotal').html()

            }
        }

        $(document).on('change', '.property_id', function () {
            stateSelector = $(this);
            getPropertyUnits(stateSelector.val());
        });

        function getPropertyUnits(property_id) {
            var getPropertyUnitsRoute = $('#getPropertyUnitsRoute').val();
            commonAjax('GET', getPropertyUnitsRoute, getPropertyUnitsRes, getPropertyUnitsRes, { "property_id": property_id });
        }

        function getPropertyUnitsRes(response) {
            var html = '<option value="">--Select Unit--</option>';
            response.data.forEach(function (opt) {
                if (opt.first_name != null) {
                    html += '<option value="' + opt.id + '">' + opt.unit_name + ' (' + opt.first_name + ' ' + opt.last_name + ')</option>';
                } else {
                    html += '<option value="' + opt.id + '">' + opt.unit_name + '</option>';
                }
            });
            stateSelector.closest('.modal').find('.propertyUnitSelectOption').html(html);
        }

        $(document).on('click', '.reminder', function () {
            let id = $(this).data('id');
            let reminderSelector = $('#reminderModal');
            reminderSelector.modal('show');
            reminderSelector.find('input[name=invoice_id]').val(id);
        });

        $(document).on('click', '#reminderGroup', function () {
            let reminderSelector = $('#reminderGroupModal');
            reminderSelector.modal('show');
        });

        $(document).on('change', '#checkNoticeBoardAllProperty', function () {
            stateSelector = $(this);
            if ($(this).is(':checked')) {
                stateSelector.closest('.modal').find('#checkNoticeBoardAllUnit').attr("disabled", true);
                stateSelector.closest('.modal').find('.unit_id').attr("disabled", true);
                stateSelector.closest('.modal').find('.property_id').attr("disabled", true);
                stateSelector.closest('.modal').find('.unit_id').val("");
                stateSelector.closest('.modal').find('.property_id').val("");
            } else {
                stateSelector.closest('.modal').find('.property_id').attr("disabled", false);
                if (stateSelector.closest('.modal').find('#checkNoticeBoardAllUnit').is(':checked')) {
                    stateSelector.closest('.modal').find('#checkNoticeBoardAllUnit').attr("disabled", false);
                    stateSelector.closest('.modal').find('.unit_id').attr("disabled", true);
                } else {
                    stateSelector.closest('.modal').find('#checkNoticeBoardAllUnit').attr("disabled", false);
                    stateSelector.closest('.modal').find('.unit_id').attr("disabled", false);
                }
            }
        });

        $(document).on('change', '#checkNoticeBoardAllUnit', function () {
            stateSelector = $(this);
            if ($(this).is(':checked')) {
                stateSelector.closest('.modal').find('.unit_id').attr("disabled", true);
                stateSelector.closest('.modal').find('.unit_id').val("");
            } else {
                stateSelector.closest('.modal').find('.unit_id').attr("disabled", false);
            }
        });

        var oTable;
        var paidInvoiceDataTable;
        var pendingInvoiceDataTable;
        var bankPendingInvoiceDataTable;
        var overdueInvoiceDataTable;

        $('#search_property').on('change', function () {
            oTable.search($(this).val()).draw();
        })

        $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function (event) {
            oTable.ajax.reload();
            paidInvoiceDataTable.ajax.reload();
            pendingInvoiceDataTable.ajax.reload();
            bankPendingInvoiceDataTable.ajax.reload();
            overdueInvoiceDataTable.ajax.reload();
        });
        oTable = $('#allInvoiceDataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: $('#invoiceIndex').val(),
            order: [1, 'desc'],
            ordering: false,
            autoWidth: false,
            drawCallback: function () {
                $(".dataTables_length select").addClass("form-select form-select-sm");
            },
            language: {
                'paginate': {
                    'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                    'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
                }
            },
            columns: [
                { "data": "invoice", "name": 'invoices.invoice_no' },
                { "data": "property", "name": 'properties.name' },
                { "data": "due_date", "name": 'invoices.due_date' },
                { "data": "amount", "name": 'property_units.unit_name' },
                { "data": "status", },
                { "data": "gateway", },
                { "data": "action", },
            ]
        });

        paidInvoiceDataTable = $('#paidInvoiceDataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: $('#invoicePaid').val(),
            order: [1, 'desc'],
            ordering: false,
            autoWidth: false,
            drawCallback: function () {
                $(".dataTables_length select").addClass("form-select form-select-sm");
            },
            language: {
                'paginate': {
                    'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                    'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
                }
            },
            columns: [
                { "data": "invoice", "name": 'invoices.invoice_no' },
                { "data": "property", "name": 'properties.name' },
                { "data": "due_date", "name": 'invoices.due_date' },
                { "data": "amount", "name": 'property_units.unit_name' },
                { "data": "status", },
                { "data": "action", },
            ]
        });

        pendingInvoiceDataTable = $('#pendingInvoiceDataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: $('#invoicePending').val(),
            order: [1, 'desc'],
            ordering: false,
            autoWidth: false,
            drawCallback: function () {
                $(".dataTables_length select").addClass("form-select form-select-sm");
            },
            language: {
                'paginate': {
                    'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                    'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
                }
            },
            columns: [
                { "data": "invoice", "name": 'invoices.invoice_no' },
                { "data": "property", "name": 'properties.name' },
                { "data": "due_date", "name": 'invoices.due_date' },
                { "data": "amount", "name": 'property_units.unit_name' },
                { "data": "status" },
                { "data": "action" },
            ]
        });

        bankPendingInvoiceDataTable = $('#bankPendingInvoiceDataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: $('#invoiceBankPending').val(),
            order: [1, 'desc'],
            ordering: false,
            autoWidth: false,
            drawCallback: function () {
                $(".dataTables_length select").addClass("form-select form-select-sm");
            },
            language: {
                'paginate': {
                    'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                    'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
                }
            },
            columns: [
                { "data": "invoice", "name": 'invoices.invoice_no' },
                { "data": "property", "name": 'properties.name' },
                { "data": "due_date", "name": 'invoices.due_date' },
                { "data": "amount", "name": 'property_units.unit_name' },
                { "data": "status", },
                { "data": "gateway", },
                { "data": "action", },
            ]
        });

        overdueInvoiceDataTable = $('#overdueInvoiceDataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: $('#invoiceOverdue').val(),
            order: [1, 'desc'],
            ordering: false,
            autoWidth: false,
            drawCallback: function () {
                $(".dataTables_length select").addClass("form-select form-select-sm");
            },
            language: {
                'paginate': {
                    'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                    'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
                }
            },
            columns: [
                { "data": "invoice", "name": 'invoices.invoice_no' },
                { "data": "property", "name": 'properties.name' },
                { "data": "due_date", "name": 'invoices.due_date' },
                { "data": "amount", "name": 'property_units.unit_name' },
                { "data": "status", },
                { "data": "action", },
            ]
        });

        $(document).on('change', '#payment-gateway-id', function () {
            // Clear the currency dropdown
            $('#payment-gateway-currency').html('');

            // Get the selected option's slug
            var selectedSlug = $(this).find(':selected').data('slug');

            // Check if the slug is 'bank'
            if (selectedSlug === 'bank') {
                // Remove the d-none class from #bankDiv if the slug is 'bank'
                $('#bankDiv').removeClass('d-none');
            } else {
                // Otherwise, add the d-none class to #bankDiv
                $('#bankDiv').addClass('d-none');
            }

            // Make the AJAX call to fetch currency data
            commonAjax('GET', $('#getCurrencyByGatewayRoute').val(), getCurrencyRes, getCurrencyRes, { 'id': $(this).val() });
        });


        function getCurrencyRes(response) {
            var html = ''; // Initialize an empty string for the options

            Object.entries(response.data).forEach((currency) => {
                // Create an option element with the currency value and label
                html += `<option value="${currency[1].id}">
                    ${currency[1].currency}
                 </option>`;
            });

            // Append the generated options to the select element with id #payment-gateway-currency
            $('#payment-gateway-currency').html(html);
        }

        $('#payment-gateway-id').trigger('change');

        // })(jQuery)


    </script>
    @if (request('id') && request('tab') == 'view')
        <script>
            view("{{ route('owner.invoice.details', request('id')) }}");
        </script>
    @endif
@endpush

