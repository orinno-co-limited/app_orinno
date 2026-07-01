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
                                @if ($tenant->status == TENANT_STATUS_ACTIVE)
                                    <div class="tenants-top-bar-right">
                                        <div class="row justify-content-end">
                                            <div class="col-auto mb-25">
                                                <button type="button" id="addInvoice" class="theme-btn w-auto"
                                                    title="{{ __('Add New Invoice') }}">{{ __('Add New Invoice') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <!-- Tenants Details Payment History Start -->
                                    <div class="tenants-details-payment-history">
                                        <!-- Account Settings Content Box Start -->
                                        <div class="account-settings-content-box">
                                            <div class="tenants-details-payment-history-table">
                                                <div class="account-settings-title border-bottom mb-20 pb-20">
                                                    <div class="row align-items-center">
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h4 class="mb-0">{{ __('Payment History') }}</h4>
                                                                <div class="dropdown">
                                                                    <a class="dropdown-toggle dropdown-toggle-nocaret"
                                                                        href="#" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        {{ __('This Year') }}
                                                                        <i
                                                                            class="mdi mdi-chevron-down d-xl-inline-block"></i>
                                                                    </a>
                                                                    <ul
                                                                        class="dropdown-menu {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:;">{{ __('This Year') }}</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:;">{{ __('This Month') }}</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Payment History Table Start -->
                                                <!-- datatable Start -->
                                                <div class="table-responsive">
                                                    <table id="allInvoicePaymentDataTable"
                                                        class="table theme-border p-20 responsive">
                                                        <thead>
                                                            <tr>
                                                                <th class="all">{{ __('SL') }}</th>
                                                                <th class="all">{{ __('Property') }}</th>
                                                                <th class="all">{{ __('Unit') }}</th>
                                                                <th class="all">{{ __('Month') }}</th>
                                                                <th class="all">{{ __('Invoice') }}</th>
                                                                <th class="all">{{ __('Issues Date') }}</th>
                                                                <th class="all">{{ __('Due Date') }}</th>
                                                                <th class="all">{{ __('Amount') }}</th>
                                                                <th class="all">{{ __('Status') }}</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
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
    {{-- modal  --}}
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
                                <input type="hidden" name="property_id" value="{{ $tenant->property_id }}">
                                <input type="hidden" name="property_unit_id" value="{{ $tenant->unit_id }}">
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
                            title="{{ __('Create Invoice') }}">{{ __('Create Invoice') }}</button>
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
    <input type="hidden" class="invoiceTypes" value="{{ $invoiceTypes }}">
    <input type="hidden" id="route" value="{{ route('owner.tenant.details', [$tenant->id, 'tab' => 'payment']) }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
{{--    <script src="{{ asset('assets/js/custom/tenant-payment.js') }}"></script>--}}
    <script>

        // (function ($) {
        //     "use strict";
        var invoiceTypes = JSON.parse($('.invoiceTypes').val());
        var typesHtml = '';
        Object.entries(invoiceTypes).forEach((type) => {
            typesHtml += '<option value="' + type[1].id + '">' + type[1].name + '</option>';
        });

        $('#addInvoice').on('click', function () {
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

        $('#allInvoicePaymentDataTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: $('#route').val(),
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
                { "data": 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false },
                { "data": "property_name", "name": "properties.name" },
                { "data": "unit_name", "name": "property_units.unit_name" },
                { "data": "month" },
                { "data": "invoice", },
                { "data": "created_at" },
                { "data": "due_date" },
                { "data": "amount", "name": "amount" },
                { "data": "status", "name": "status" },
            ]
        });
        // })(jQuery)
    </script>
@endpush
