@extends('maintainer.layouts.app')

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
                                    <span class="iconify orange-color" data-icon="bxs:home-circle"></span>
                                </div>
                                <p class="mt-2">{{ __('Total Property') }}</p>
                                <h2 class="mt-1">{{ count($properties) }}</h2>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify orange-color" data-icon="fluent:folder-open-16-filled"></span>
                                </div>
                                <p class="mt-2">{{ __('Open Ticket') }}</p>
                                <h2 class="mt-1">{{ $totalOpenTickets }}</h2>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify primary-color" data-icon="dashicons:tickets-alt"></span>
                                </div>
                                <p class="mt-2">{{ __('Resolved Ticket') }}</p>
                                <h2 class="mt-1">{{ $totalResolvedTickets }}</h2>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="dashboard-feature-item bg-off-white theme-border radius-4 p-20 mb-25">
                                <div
                                    class="dashboard-feature-item-icon-wrap font-20 d-flex align-items-center justify-content-center bg-white radius-4">
                                    <span class="iconify red-color" data-icon="gridicons:cross-circle"></span>
                                </div>
                                <p class="mt-2">{{ __('Close Ticket') }}</p>
                                <h2 class="mt-1">{{ $totalCloseTickets }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="dashboard-properties-table">
                                <div class="bg-off-white theme-border p-20 radius-4 mb-25">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between mb-25">
                                                <h4 class="mb-0">{{ __('Tickets') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table theme-border p-20">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Ticket No') }}</th>
                                                            <th>{{ __('Title') }}</th>
                                                            <th>{{ __('Details') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tickets as $ticket)
                                                            <tr>
                                                                <td>{{ $ticket->ticket_no }}</td>
                                                                <td>{{ $ticket->title }}</td>
                                                                <td>{{ Str::limit($ticket->details, 50, '...') }}</td>
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
                        @if (ownerCurrentPackage(auth()->user()->owner_user_id)?->notice_support == ACTIVE)
                            <div class="col-lg-4">
                                <div class="dashboard-properties-table bg-off-white theme-border p-20 radius-4 mb-25">
                                    <div class="">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="dashboard-properties-table bg-off-white theme-border p-20 radius-4 mb-25">
                                    <div class="">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-between mb-25">
                                                    <h4 class="mb-0">{{ __('Properties') }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table tenant-portal-notice-tbl theme-border p-20">
                                                        <tbody>
                                                            @forelse ($properties as $property)
                                                                <tr>
                                                                    <td>
                                                                        <div class="tenant-portal-notice-tbl-item">
                                                                            {{ $loop->iteration }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="tenant-portal-notice-tbl-item">
                                                                            <h5>{{ Str::limit($property->name, 40, '...') }}
                                                                            </h5>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                {{ __('No Data Found') }}
                                                            @endforelse
                                                        </tbody>
                                                    </table>
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
    </div>
@endsection
