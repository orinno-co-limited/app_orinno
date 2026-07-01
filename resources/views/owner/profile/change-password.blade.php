@extends('layouts.app')

@section('content')
    <!-- Right Content Start -->
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
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                                title="Dashboard">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item">{{ __('Profile') }}</li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Profile Page Area row Start -->
                    <div class="row">
                        <!-- Profile Page Content Area Start -->
                        <div class="profile-page-content-area">
                            <form action="{{ route('change-password.update') }}" method="post">
                                @csrf
                                <div class="settings-inner-box bg-white theme-border radius-4 mb-25">
                                    <div class="settings-inner-box-fields p-20 pb-0">
                                        <div class="row">
                                            <div class="col-md-12 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Current Password') }}</label>
                                                <input type="password" name="current_password" class="form-control"
                                                    placeholder="********" required>
                                                @error('current_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('New Password') }}</label>
                                                <input type="password" name="password" class="form-control"
                                                    placeholder="********" required>
                                                @error('password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Confirm Password') }}</label>
                                                <input type="password" name="password_confirmation" class="form-control"
                                                    placeholder="********" required>
                                            </div>
                                            <div class="col-md-12 mb-25">
                                                <button type="submit" class="theme-btn"
                                                    title="{{ __('Update') }}">{{ __('Update') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Profile Page Content Area End -->
                    </div>
                    <!-- Profile Page Area row End -->
                </div>
                <!-- Page Content Wrapper End -->
            </div>
        </div>
        <!-- End Page-content -->
    </div>
    <!-- Right Content End -->
@endsection
