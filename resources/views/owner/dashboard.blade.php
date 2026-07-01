@extends('owner.layouts.app')

@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <div class="page-content-wrapper bg-white p-30 radius-20">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex flex-column flex-sm-row align-items-sm-center justify-content-between g-20">
                                <div class="page-title-left">
                                    <h2 class="mb-sm-0">{{ __('Dashboard') }}</h2>
                                    <p>{{ __('Welcome back') }}, {{ auth()->user()->name }} <span class="iconify font-24"
                                            data-icon="openmoji:waving-hand"></span></p>
                                </div>
                                @can('Manage Property')
                                <div class="page-title-right">
                                    <a href="{{ route('owner.property.add') }}" class="theme-btn"
                                        title="{{ __('Add Property') }}">{{ __('Add Property') }}</a>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify orange-color" data-icon="bxs:home-circle"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Property') }}</p>
                                <h2 class="mt-1">{{ $totalProperties }}</h2>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify primary-color" data-icon="material-symbols:garage-home"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Units') }}</p>
                                <h2 class="mt-1">{{ $totalUnits }}</h2>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify orange-color" data-icon="bi:bar-chart-line-fill"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Tenants') }}</p>
                                <h2 class="mt-1">{{ $totalTenants }}</h2>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify green-color" data-icon="bi:bar-chart-line-fill"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Maintainers') }}</p>
                                <h2 class="mt-1">{{ $totalMaintainers }}</h2>
                            </div>
                        </div>
                    </div>
                    <!-- dashboard-feature-item row -->

                    <!-- Chart row -->
                    <div class="row">
                        <div class="col-12 col-lg-12 col-xl-12">
                            <div class="bg-off-white radius-4 mb-25 theme-border p-20 w-100">
                                <div class="bg-transparent">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ __('Rent Overview') }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <h2>{{ currencyPrice($yearlyTotalAmount) }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div id="chart1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Chart row -->

                    <div class="row">
                        @can('Manage Property')
                        <div class="col-lg-7">
                            <div class="dashboard-properties-table bg-off-white theme-border p-20 radius-4 mb-25">
                                <div class="">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between mb-25">
                                                <h4 class="mb-0">{{ __('My Properties') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table theme-border p-20">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('Units') }}</th>
                                                            <th>{{ __('Available Unit') }}</th>
                                                            <th>{{ __('Tenants') }}</th>
                                                            <th>{{ __('Maintainer') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($properties as $property)
                                                            <tr>
                                                                <td>
                                                                    <h6 class="theme-text-color">{{ $property->name }}
                                                                    </h6>
                                                                    <p class="font-13">{{ $property->address }}</p>
                                                                </td>
                                                                <td>{{ $property->number_of_unit }}</td>
                                                                <td>{{ $property->number_of_unit - $property->total_tenant }}
                                                                </td>
                                                                <td>{{ $property->total_tenant }}</td>
                                                                <td>{{ $property->total_maintainers }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="text-center">{{ __('No data found') }}</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>

                                                <div>
                                                    <a class="theme-link font-14 font-medium d-flex align-items-center justify-content-center mt-20"
                                                        href="{{ route('owner.property.allProperty') }}">
                                                        {{ __('View All') }}<i class="ri-arrow-right-line ms-2"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @if (isAddonInstalled('PROTYSAAS') < 1 || ownerCurrentPackage(getOwnerUserId())?->ticket_support == ACTIVE)
                        <div class="col-lg-5">
                            @can('Manage Ticket')
                            <div class="dashboard-properties-table bg-off-white theme-border p-20 radius-4 mb-25">
                                <div class="">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between mb-25">
                                                <h4 class="mb-0">{{ __('Tickets') }}</h4>
                                                <div>
                                                    <a class="theme-link font-14 font-medium d-flex align-items-center justify-content-center"
                                                        href="{{ route('owner.ticket.index') }}">
                                                        {{ __('View All') }}<i class="ri-arrow-right-line ms-2"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table theme-border p-20">
                                                    <tbody>
                                                        @forelse ($tickets as $ticket)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0">
                                                                            <div
                                                                                class="h-36 w-36 overflow-hidden radius-50">
                                                                                <img src="{{ $ticket->user?->image }}"
                                                                                    alt="" class="img-fluid h-36">
                                                                            </div>
                                                                        </div>
                                                                        <div class="flex-grow-1 ms-3">
                                                                            <h6>{{ Str::limit($ticket->title, 25, '...') }}
                                                                            </h6>
                                                                            <div>
                                                                                <a href="{{ route('owner.ticket.details', $ticket->id) }}"
                                                                                    class="primary-color font-13 me-2">{{ Str::limit($ticket->topic->name, 25, '...') }}</a>
                                                                                <span href="#"
                                                                                    class="orange-color font-13 me-2">{{ __('Issue') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="text-center">{{ __('No data found') }}</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endcan
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const MONTHS = @json($months);
        const INVOICEMONTLYAMOUNT = @json($invoiceMonthlyAmount);
    </script>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/index-charts.js') }}"></script>
@endpush
