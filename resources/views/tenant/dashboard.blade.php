@extends('tenant.layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="page-content-wrapper bg-white p-30 radius-20">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <div class="page-title-left">
                                    <h2 class="mb-sm-0">{{ __('Dashboard') }}</h2>
                                    <p>{{ __('Welcome back') }}, {{ auth()->user()->name }} <span class="iconify font-24"
                                            data-icon="openmoji:waving-hand"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-4 col-xl-4">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify orange-color" data-icon="bxs:home-circle"></span>
                                </div>
                                <p class="mt-2">{{ $unit->unit_name }}</p>
                                <h2 class="mt-1">{{ $property->name }}</h2>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-4">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify orange-color" data-icon="bxs:home-circle"></span>
                                </div>
                                <p class="mt-2">{{ __('Current Rent') }}</p>
                                <h2 class="mt-1">{{ currencyPrice($tenant->general_rent) }}</h2>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-4">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify red-color" data-icon="dashicons:tickets-alt"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Tickets') }}</p>
                                <h2 class="mt-1">{{ $totalTickets }}</h2>
                            </div>
                        </div>

                    </div>
                    <!-- dashboard-feature-item row -->

                    <!-- Tenant Portal Dashboard Invoice and Notice Board row -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="dashboard-properties-table">

                                <!-- Paid Invoice Table Start -->
                                <div class="bg-off-white theme-border p-20 radius-4 mb-25">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between mb-25">
                                                <h4 class="mb-0">{{ __('Paid Invoice') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table theme-border p-20">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Invoice No.') }}</th>
                                                            <th>{{ __('Reference') }}</th>
                                                            <th>{{ __('Issus Date') }}</th>
                                                            <th>{{ __('Amount') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($invoices as $invoice)
                                                            @if ($invoice->status == ACTIVE)
                                                                <tr>
                                                                    <td>{{ $invoice->invoice_no }}</td>
                                                                    <td>{{ $invoice->name }}</td>
                                                                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                                                    <td>{{ currencyPrice($invoice->amount) }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Paid Invoice Table End -->

                                <!-- Unpaid Invoice Table Start -->
                                <div class="bg-off-white theme-border p-20 radius-4 mb-25">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between mb-25">
                                                <h4 class="mb-0">{{ __('Unpaid Invoice') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table theme-border p-20">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Invoice No.') }}</th>
                                                            <th>{{ __('Reference') }}</th>
                                                            <th>{{ __('Due Date') }}</th>
                                                            <th>{{ __('Amount') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($invoices as $invoice)
                                                            @if ($invoice->status != ACTIVE)
                                                                <tr>
                                                                    <td>{{ $invoice->invoice_no }}</td>
                                                                    <td>{{ $invoice->name }}</td>
                                                                    <td>{{ $invoice->due_date }}</td>
                                                                    <td>{{ currencyPrice($invoice->amount) }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Unpaid Invoice Table End -->
                            </div>
                        </div>
                        <!-- end col -->
                        @if (ownerCurrentPackage(auth()->user()->owner_user_id)?->notice_support == ACTIVE || isAddonInstalled('PROTYSAAS') < 1)
                            <div class="col-lg-4">
                                <div class="dashboard-properties-table bg-off-white theme-border p-20 radius-4 mb-25">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between mb-25">
                                                <h4 class="mb-0">{{ __('Notice Board') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table tenant-portal-notice-tbl theme-border p-20">
                                                    <tbody>
                                                        @forelse ($notices as $notice)
                                                            @if ($notice->start_date <= $today && $notice->end_date >= $today)
                                                                <tr>
                                                                    <td>
                                                                        <div class="tenant-portal-notice-tbl-item">
                                                                            <h5>{{ Str::limit($notice->title, 40, '...') }}
                                                                            </h5>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @empty
                                                            {{ __('No Data Found') }}
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @if (count($notices))
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <a href="{{ route('tenant.notices') }}">{{ __('See All') }}</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
