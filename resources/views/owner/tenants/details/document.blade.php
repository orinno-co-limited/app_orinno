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
                            <!-- Account settings Area Right Side Start-->
                            <div class="col-md-12 col-lg-12 col-xl-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <!-- Tenants Details Documents Start -->
                                    <div class="tenants-profile-information">
                                        <!-- Account Settings Content Box Start -->
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ __('Documents') }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Show Uploaded Files and Documents Start -->
                                            <div class="col-md-12">
                                                @forelse ($tenant->documents as $document)
                                                    <div class="show-uploaded-documents d-flex align-items-center mb-25">
                                                        <div
                                                            class="show-uploaded-documents-img flex-shrink-0 bg-blue-transparent radius-4 overflow-hidden p-2 me-2">
                                                            <img class="img-fluid"
                                                                src="{{ asset('assets/images/file-text-line.svg') }}"
                                                                alt="File-Image">
                                                        </div>
                                                        <div
                                                            class="show-uploaded-documents-content flex-grow-1 d-inline-flex align-items-center">
                                                            <h5 class="me-3">{{ $document->file_name }}</h5>
                                                            <div class="d-inline-flex">
                                                                <a href="{{ $document->FileUrl }}" {{ __('Download') }}
                                                                    class="uploaded-document-icon font-20 theme-link me-2"
                                                                    title="Download"><i class="ri-download-2-line"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <!-- Empty Properties row -->
                                                    <div class="row justify-content-center">
                                                        <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                                                            <div class="empty-properties-box text-center">
                                                                <img src="{{ asset('assets/images/empty-img.png') }}"
                                                                    alt="" class="img-fluid">
                                                                <h3 class="mt-25">{{ __('Empty') }}</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Empty Properties row -->
                                                @endforelse
                                            </div>
                                            <!-- Show Uploaded Files and Documents End -->
                                        </div>
                                        <!-- Account Settings Content Box End -->
                                    </div>
                                    <!-- Tenants Details Documents End -->
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
@endsection
