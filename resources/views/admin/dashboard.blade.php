@extends('admin.layouts.app')

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
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify orange-color" data-icon="material-symbols:patient-list"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Owner') }}</p>
                                <h2 class="mt-1">{{ $totalOwner }}</h2>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify primary-color" data-icon="bxs:home-circle"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Property') }}</p>
                                <h2 class="mt-1">{{ $totalProperty }}</h2>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify orange-color" data-icon="material-symbols:garage-home"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Unit') }}</p>
                                <h2 class="mt-1">{{ $totalUnit }}</h2>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify green-color" data-icon="mdi:user"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Tenant') }}</p>
                                <h2 class="mt-1">{{ $totalTenant }}</h2>
                            </div>
                        </div>
                    </div>
                    @if (isAddonInstalled('PROTYSAAS') > 1)
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="dashboard-properties-table bg-off-white theme-border p-20 radius-4 mb-25">
                                    <div class="">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-between mb-25">
                                                    <h4 class="mb-0">{{ __('Orders') }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table theme-border p-20">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Package') }}</th>
                                                                <th>{{ __('Total') }}</th>
                                                                <th>{{ __('Gateway') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($orders as $order)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="theme-text-color">
                                                                            {{ $order->packageName }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>{{ currencyPrice($order->total) }}</td>
                                                                    <td>{{ $order->gatewayTitle }}</td>
                                                                    <td>{{ $order->total_tenant }}
                                                                        @if ($order->payment_status == ORDER_PAYMENT_STATUS_PAID)
                                                                            <div
                                                                                class="status-btn status-btn-blue font-13 radius-4">
                                                                                {{ __('Paid') }}</div>
                                                                        @elseif ($order->payment_status == ORDER_PAYMENT_STATUS_PENDING)
                                                                            <div
                                                                                class="status-btn status-btn-red font-13 radius-4">
                                                                                {{ __('Pending') }}</div>
                                                                        @else
                                                                            <div
                                                                                class="status-btn status-btn-orange font-13 radius-4">
                                                                                {{ __('Cancelled') }}</div>
                                                                        @endif
                                                                    </td>
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
                                                            href="{{ route('admin.subscriptions.orders') }}">
                                                            {{ __('View All') }}<i class="ri-arrow-right-line ms-2"></i>
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="dashboard-properties-table bg-off-white theme-border p-20 radius-4 mb-25">
                                    <div class="">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-between mb-25">
                                                    <h4 class="mb-0">{{ __('Packages') }}</h4>
                                                    <div>
                                                        <a class="theme-link font-14 font-medium d-flex align-items-center justify-content-center"
                                                            href="{{ route('admin.packages.index') }}">
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
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Name') }}</th>
                                                                <th>{{ __('Monthly Price') }}</th>
                                                                <th>{{ __('Yearly Price') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($packages as $package)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="flex-grow-1">
                                                                                <h6>{{ Str::limit($package->name, 25, '...') }}
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ currencyPrice($package->monthly_price) }}</td>
                                                                    <td>{{ currencyPrice($package->yearly_price) }}</td>
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
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
