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
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('owner.property.allProperty') }}"
                                                title="{{ __('Properties') }}">{{ __('Properties') }}</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- All Property Area row Start -->
                    <div class="all-property-area">
                        <!-- Add Property Stepper Area Start -->
                        <div class="add-property-stepper-area">
                            <div class="row">
                                <!-- Stepper Start -->
                                <div class="col-12">
                                    <div id="msform">
                                        <!-- progressbar -->
                                        <div class="stepper-progressbar-wrap radius-10 theme-border p-25 mb-25">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active" id="accountInformationStep">
                                                    <span class="form-stepper-nav-icon"><i
                                                            class="ri-home-4-fill"></i></span>
                                                    <span>{{ __('Property Information') }}</span>
                                                </li>
                                                <li id="locationStep">
                                                    <span class="form-stepper-nav-icon"><i
                                                            class="ri-map-pin-2-fill"></i></span>
                                                    <span>{{ __('Location') }}</span>
                                                </li>
                                                <li id="unitStep">
                                                    <span class="form-stepper-nav-icon"><i
                                                            class="ri-layout-4-fill"></i></span>
                                                    <span>{{ __('Unit') }}</span>
                                                </li>
                                                <li id="rentChargesStep">
                                                    <span class="form-stepper-nav-icon"><i
                                                            class="ri-file-text-fill"></i></span>
                                                    <span>{{ __('Rent & Charges') }}</span>
                                                </li>
                                                <li id="imageStep">
                                                    <span class="form-stepper-nav-icon"><i
                                                            class="ri-image-add-fill"></i></span>
                                                    <span>{{ __('Image') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- Start:: fieldSets -->
                                        <fieldset>
                                            <div id="addHtmlForm"></div>
                                        </fieldset>
                                        <!-- End:: fieldSets -->
                                    </div>
                                </div>
                                <!-- Stepper End -->
                            </div>
                        </div>
                        <!-- Add Property Stepper Area End -->
                    </div>
                    <!-- All Property Area row End -->
                </div>
                <!-- Page Content Wrapper End -->
            </div>
        </div>
        <!-- End Page-content -->
    </div>

    <input type="hidden" id="property_id" value="{{ @$property->id }}">
    <input type="hidden" id="getStateListRoute" value="{{ route('owner.location.state.list') }}">
    <input type="hidden" id="getCityListRoute" value="{{ route('owner.location.city.list') }}">
    <input type="hidden" id="imageStoreRoute" value="{{ route('owner.property.image.store') }}">
    <input type="hidden" id="imageDoc" value="{{ route('owner.property.image.doc') }}">
    <input type="hidden" id="getPropertyInformationRoute" value="{{ route('owner.property.getPropertyInformation') }}">
    <input type="hidden" id="getLocationRoute" value="{{ route('owner.property.getLocation') }}">
    <input type="hidden" id="getUnitRoute" value="{{ route('owner.property.getUnitByPropertyId') }}">
    <input type="hidden" id="getRentChargeRoute" value="{{ route('owner.property.getRentCharge') }}">
@endsection

@push('script')
    <script src="{{ asset('assets/js/custom/property.js') }}"></script>
@endpush
