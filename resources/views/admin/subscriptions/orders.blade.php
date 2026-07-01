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
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            <div class="tbl-tab-wrap border-bottom pb-25 mb-25">
                                <ul class="nav nav-tabs billing-center-nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="tableAll-tab" data-bs-toggle="tab"
                                            data-bs-target="#tableAll-tab-pane" type="button" role="tab"
                                            aria-controls="tableAll-tab-pane" aria-selected="true">
                                            {{ __('All') }}
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tablePaid-tab" data-bs-toggle="tab"
                                            data-bs-target="#tablePaid-tab-pane" type="button" role="tab"
                                            aria-controls="tablePaid-tab-pane" aria-selected="false">
                                            {{ __('Paid') }}
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tablePending-tab" data-bs-toggle="tab"
                                            data-bs-target="#tablePending-tab-pane" type="button" role="tab"
                                            aria-controls="tablePending-tab-pane" aria-selected="false">
                                            {{ __('Pending') }}
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tableBank-tab" data-bs-toggle="tab"
                                            data-bs-target="#tableBank-tab-pane" type="button" role="tab"
                                            aria-controls="tableBank-tab-pane" aria-selected="false">
                                            {{ __('Bank Pending') }}
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tableCancelled-tab" data-bs-toggle="tab"
                                            data-bs-target="#tableCancelled-tab-pane" type="button" role="tab"
                                            aria-controls="tableCancelled-tab-pane" aria-selected="false">
                                            {{ __('Cancelled') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tableAll-tab-pane" role="tabpanel"
                                    aria-labelledby="tableAll-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table id="allOrderDataTable" class="table theme-border dt-responsive">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SL') }}</th>
                                                    <th data-priority="1">{{ __('Name') }}</th>
                                                    <th>{{ __('Package') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Gateway') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tablePaid-tab-pane" role="tabpanel"
                                    aria-labelledby="tablePaid-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table id="allPaidDataTable" class="table theme-border dt-responsive">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SL') }}</th>
                                                    <th data-priority="1">{{ __('Name') }}</th>
                                                    <th>{{ __('Package') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Gateway') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tablePending-tab-pane" role="tabpanel"
                                    aria-labelledby="tablePending-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table id="allPendingDataTable" class="table theme-border dt-responsive">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SL') }}</th>
                                                    <th data-priority="1">{{ __('Name') }}</th>
                                                    <th>{{ __('Package') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Gateway') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tableBank-tab-pane" role="tabpanel"
                                    aria-labelledby="tableBank-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table id="bankPendingInvoiceDataTable" class="table theme-border dt-responsive">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SL') }}</th>
                                                    <th data-priority="1">{{ __('Name') }}</th>
                                                    <th>{{ __('Package') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Gateway') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tableCancelled-tab-pane" role="tabpanel"
                                    aria-labelledby="tableCancelled-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table id="allCancelledDataTable" class="table theme-border dt-responsive">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SL') }}</th>
                                                    <th data-priority="1">{{ __('Name') }}</th>
                                                    <th>{{ __('Package') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Gateway') }}</th>
                                                    <th>{{ __('Date') }}</th>
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
            </div>
        </div>
    </div>
    {{-- modal  --}}

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
                <form class="ajax" action="{{ route('admin.subscriptions.order.payment.status.change') }}"
                    method="post" data-handler="getShowMessage">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20 pb-0">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select class="form-select flex-shrink-0" name="status">
                                        <option value="0">{{ __('Pending') }}</option>
                                        <option value="1">{{ __('Paid') }}</option>
                                        <option value="2">{{ __('Cancelled') }}</option>
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

    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title theme-link pointer-auto" id="previewModalLabel" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="iconify me-2" data-icon="eva:arrow-back-fill"></span>{{ __('Back') }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="invoice-preview-wrap">
                        <div class="invoice-heading-part mb-3">
                            <div class="invoice-heading-left">
                                <h4 class="invoice-generate-title invoice-heading-color">{{ __('Transaction Details') }}
                                </h4>
                            </div>
                            <div class="invoice-heading-right">
                                <div class="invoice-heading-right-status-btn invoiceStatus"></div>
                            </div>
                        </div>
                        <div class="transaction-table-part">
                            <div class="table-responsive">
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
@endsection

<input type="hidden" id="ordersRoute" value="{{ route('admin.subscriptions.orders') }}">
<input type="hidden" id="ordersGetInfoRoute" value="{{ route('admin.subscriptions.orders.get.info') }}">
<input type="hidden" id="ordersPaidRoute"
    value="{{ route('admin.subscriptions.orders.payment.status', ['status' => 'paid']) }}">
<input type="hidden" id="ordersPendingRoute"
    value="{{ route('admin.subscriptions.orders.payment.status', ['status' => 'pending']) }}">
<input type="hidden" id="ordersBankRoute"
    value="{{ route('admin.subscriptions.orders.payment.status', ['status' => 'bank']) }}">
<input type="hidden" id="ordersCancelledRoute"
    value="{{ route('admin.subscriptions.orders.payment.status', ['status' => 'cancelled']) }}">

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')

    <script src="{{ asset('assets/js/custom/orders.js') }}"></script>
@endpush
