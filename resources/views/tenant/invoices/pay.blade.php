@extends('tenant.layouts.app')

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
                                        <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item" aria-current="page">{{ __('Invoice') }}</li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Tenant Portal Invoice Details Left Side Start -->
                        <div class="col-lg-6">
                            <div
                                class="tenant-portal-invoice-details-leftside bg-off-white theme-border p-20 radius-4 mb-25">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-between mb-25">
                                            <h4 class="mb-0">{{ __('Invoice Details') }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table theme-border p-20">
                                                <tbody>
                                                    <tr>
                                                        <td>{{ __('Invoice No') }}.</td>
                                                        <td>
                                                            <h6 class="tenant-invoice-tbl-right-text text-end">
                                                                {{ $invoice->invoice_no }}</h6>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Name') }}</td>
                                                        <td>
                                                            <h6 class="tenant-invoice-tbl-right-text text-end">
                                                                {{ $invoice->name }}</h6>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Issus Date') }}</td>
                                                        <td>
                                                            <h6 class="tenant-invoice-tbl-right-text text-end">
                                                                {{ $invoice->created_at->format('Y-m-d') }}</h6>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Due Date') }}</td>
                                                        <td>
                                                            <h6 class="tenant-invoice-tbl-right-text text-end">
                                                                {{ $invoice->due_date }}</h6>
                                                        </td>
                                                    </tr>
                                                    @if ($invoice->due_date < date('Y-m-d'))
                                                        <tr>
                                                            <td>{{ __('Late Fee') }}</td>
                                                            <td>
                                                                <h6 class="tenant-invoice-tbl-right-text text-end">
                                                                    {{ currencyPrice($invoice->late_fee) }}</h6>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td>{{ __('Amount') }}</td>
                                                        <td>
                                                            <h6 class="tenant-invoice-tbl-right-text text-end">
                                                                {{ currencyPrice($invoice->amount) }}</h6>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="table theme-border p-20">
                                                <tbody id="currencyAppend"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tenant Portal Invoice Details Left Side End -->

                        <!-- Tenant Portal Invoice Details Right Side Start -->
                        <div class="col-lg-6">
                            <div
                                class="tenant-portal-invoice-details-rightside bg-off-white theme-border p-20 radius-4 mb-25">
                                <form class="" action="{{ route('payment.checkout') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                    <input type="hidden" id="selectGateway" name="gateway">
                                    <input type="hidden" id="selectCurrency" name="currency">

                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between mb-25">
                                                <h4 class="mb-0">{{ __('Select Payment Method') }}</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <ul class="nav nav-tabs invoice-payment-nav-tabs row" id="invoicePaymentTab"
                                        role="tablist">
                                        @foreach ($gateways as $gateway)
                                            <li class="nav-item col-md-12 col-xl-12 col-xxl-6 mb-25" role="presentation">
                                                <div data-bs-target="#{{ $gateway->slug }}"
                                                    class="cursor nav-link paymentGateway" id="invoice{{ $gateway->slug }}"
                                                    data-gateway="{{ $gateway->slug }}" data-bs-toggle="tab" role="tab"
                                                    aria-controls="{{ $gateway->slug }}" aria-selected="true">
                                                    <div class="custom-radiobox">
                                                        <input type="radio" value="{{ $gateway->id }}">
                                                        <label class="fs-5">{{ $gateway->title }}</label>
                                                    </div>
                                                    <div class="invoice-payment-img">
                                                        <img src="{{ $gateway->icon }}" alt="">
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="row">
                                        <div class="tab-content" id="invoicePaymentTabContent">

                                            <div class="tab-pane fade" id="bank" role="tabpanel"
                                                aria-labelledby="invoicebank" tabindex="0">
                                                <div class="invoice-payment-card-box bg-white radius-4 theme-border mb-25">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center border-bottom p-20">
                                                        <h4>{{ __('Bank Deposit') }}</h4>
                                                    </div>
                                                    <div class="p-20 pb-0">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-20">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Bank Name') }}</label>
                                                                <select name="bank_id" class="form-control" id="bank_id">
                                                                    <option value="">--{{ __('Bank') }}--
                                                                    </option>
                                                                    @foreach ($banks as $bank)
                                                                        <option value="{{ $bank->id }}"
                                                                            data-details="{{ $bank->details }}">
                                                                            {{ $bank->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('bank_id')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-12 mb-20 d-none" id="bankDetails">
                                                                <p class="my-2 ps-2"></p>
                                                            </div>
                                                            <div class="col-md-12 mb-20">
                                                                <label
                                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Upload Deposit Slip') }}</label>
                                                                <input type="file" class="form-control"
                                                                    name="bank_slip">
                                                                @error('bank_slip')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" class="theme-btn me-2 mb-1 w-100"
                                            id="payBtn">{{ __('Pay Now') }}
                                            <span class="ms-1" id="gatewayCurrencyAmount"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Tenant Portal Invoice Details Right Side End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($invoice->due_date < date('Y-m-d'))
        <input type="hidden" id="invoiceAmount" value="{{ $invoice->amount + $invoice->late_fee }}">
    @else
        <input type="hidden" id="invoiceAmount" value="{{ $invoice->amount }}">
    @endif
    <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('tenant.invoice.get.currency') }}">
@endsection

@push('script')
    <script>
        "use strict"
        $('#bank_id').on('change', function () {
            $('#bankDetails').removeClass('d-none');
            $('#bankDetails p').html($(this).find(':selected').data('details'));
        });

        $(document).on('click', '.paymentGateway', function () {
            $('#selectGateway').val($(this).data('gateway').replace(/\s+/g, ''))
            $('#selectCurrency').val('');
            commonAjax('GET', $('#getCurrencyByGatewayRoute').val(), getCurrencyRes, getCurrencyRes, { 'id': $(this).find('input').val() });
        });

        function getCurrencyRes(response) {
            var html = '';
            var invoiceAmount = parseFloat($('#invoiceAmount').val()).toFixed(2);
            Object.entries(response.data).forEach((currency) => {
                let currencyAmount = currency[1].conversion_rate * invoiceAmount;
                html += `<tr>
                    <td>
                        <div class="custom-radiobox gatewayCurrencyAmount">
                            <input type="radio" name="gateway_currency_amount" id="${currency[1].id}" class="" value="${gatewayCurrencyPrice(currencyAmount, currency[1].symbol)}">
                            <label for="${currency[1].id}">${currency[1].currency}</label>
                        </div>
                    </td>
                    <td><h6 class="tenant-invoice-tbl-right-text text-end">${gatewayCurrencyPrice(currencyAmount, currency[1].symbol)}</h6></td>
                </tr>`;
            });
            $('#currencyAppend').html(html);
        }

        $(document).on('click', '.gatewayCurrencyAmount', function () {
            var getCurrencyAmount = '(' + $(this).find('input').val() + ')';
            $('#gatewayCurrencyAmount').text(getCurrencyAmount)
            $('#selectCurrency').val($(this).text().replace(/\s+/g, ''));
        });

        $('#payBtn').on('click', function () {
            var gateway = $('#selectGateway').val()
            var currency = $('#selectCurrency').val();
            if (gateway == '') {
                toastr.error("{{__('Select Gateway')}}");
                $('#payBtn').attr('type', 'button');
            } else {
                if (currency == '') {
                    toastr.error("{{__('Select Currency')}}}");
                    $('#payBtn').attr('type', 'button');
                } else {
                    $('#payBtn').attr('type', 'submit');
                }
            }
        });

    </script>
{{--    <script src="{{ asset('assets/js/custom/tenant-invoice-pay.js') }}"></script>--}}
@endpush
