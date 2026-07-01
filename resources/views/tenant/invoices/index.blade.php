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
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="information-table-area">
                            <div class="table-responsive bg-off-white theme-border radius-4 p-25">
                                <table id="allDataTable" class="table bg-off-white theme-border p-20 dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th class="all">{{ __('Invoice No') }}.</th>
                                            <th class="all">{{ __('Name') }}</th>
                                            <th class="desktop">{{ __('Issus Date') }}</th>
                                            <th class="desktop">{{ __('Due Date') }}</th>
                                            <th class="desktop">{{ __('Amount') }}</th>
                                            <th class="all">{{ __('Recept') }}</th>
                                            <th class="desktop">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $invoice)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $invoice->invoice_no }}</td>
                                                <td>{{ $invoice->name }}</td>
                                                <td>{{ $invoice->created_at->format('d M Y') }}</td>
                                                <td>
                                                    {{ $invoice->due_date }}
                                                    @if ($invoice->status != INVOICE_STATUS_PAID)
                                                        @if ($invoice->due_date < date('Y-m-d'))
                                                            <div class="status-btn status-btn-red mx-1"
                                                                title="{{ __('Over Due') }}">
                                                                {{ __('Over Due') }}</div>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ currencyPrice($invoice->amount) }}</td>
                                                <td>
                                                    <button type="button" onclick="getEditModal('{{route('tenant.invoice.view', $invoice->id)}}', '#viewModal')" class="download-invoice status-btn theme-btn-green" title="' . __('View') . '">
                                                        {{__('View')}}
                                                    </button>
                                                </td>
                                                <td>
                                                    @if ($invoice->status == INVOICE_STATUS_PENDING)
                                                        <a href="{{ route('tenant.invoice.pay', $invoice->id) }}"
                                                            class="status-btn theme-btn-purple"
                                                            title="{{ __('Pay Now') }}">{{ __('Pay Now') }}</a>
                                                    @elseif ($invoice->status == INVOICE_STATUS_PAID)
                                                        <div class="status-btn status-btn-green"
                                                            title="{{ __('Paid') }}">{{ __('Paid') }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('/') }}assets/js/pages/alldatatables.init.js"></script>
@endpush
