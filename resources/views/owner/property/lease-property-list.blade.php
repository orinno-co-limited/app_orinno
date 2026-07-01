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
                                    <h3 class="mb-sm-0">{{ $pageTitle }} <span
                                            class="property-count theme-text-color">({{ $propertiesCount }})</span></h3>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('owner.property.leaseProperty') }}"
                                                title="{{ __('Properties') }}">{{ __('Properties') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- All Property Area row Start -->
                    <div class="row">
                        <!-- Property Top Search Bar Start -->
                        <div class="property-top-search-bar">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('owner.property.add') }}" class="theme-btn mb-25"
                                        title="Add New Proparty">{{ __('Add New Property') }}</a>
                                </div>
                                <div class="col-md-6">
                                    <div class="page-inner-search ms-auto position-relative mb-25">
                                        <input type="text" class="form-control" placeholder="Search properties">
                                        <span class="ri-search-line"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Property Top Search Bar End -->
                        <!-- Properties Item Wrap Start -->
                        <div class="properties-item-wrap">
                            <div class="row">
                                @if (getOption('app_card_data_show', 1) == 1)
                                    @forelse($properties as $property)
                                        <!-- Property Item Start -->
                                        <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3">
                                            <div
                                                class="property-item bg-off-white theme-border radius-10 position-relative mb-25">
                                                <a href="{{ route('owner.property.show', $property->id) }}"
                                                    class="property-item-img-wrap d-block position-relative overflow-hidden radius-10">
                                                    <div class="property-item-img">
                                                        <img src="{{ $property->thumbnail_image }}" alt=""
                                                            class="fit-image">
                                                    </div>
                                                </a>
                                                <div class="property-item-content p-20">
                                                    <h4 class="property-item-title position-relative">
                                                        <a href="{{ route('owner.property.show', $property->id) }}"
                                                            class="color-heading link-hover-effect me-3">{{ substr_replace($property->name, '...', 20) }}</a>
                                                        <!-- Property Item Action Dropdown Start -->
                                                        <div
                                                            class="property-item-dropdown position-absolute radius-3 text-end ms-2">
                                                            <div class="dropdown">
                                                                <a class="dropdown-toggle dropdown-toggle-nocaret"
                                                                    href="#" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    <i class="ri-more-2-fill"></i>
                                                                </a>
                                                                <ul
                                                                    class="dropdown-menu {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
                                                                    <li><a class="dropdown-item font-13"
                                                                            href="{{ route('owner.property.edit', $property->id) }}"
                                                                            title="{{ __('Edit') }}">{{ __('Edit') }}</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item font-13 deleteItem"
                                                                            data-formid="delete_row_form_{{ $property->id }}"
                                                                            href="#"
                                                                            title="{{ __('Delete') }}">{{ __('Delete') }}</a>
                                                                        <form
                                                                            action="{{ route('owner.property.destroy', [$property->id]) }}"
                                                                            method="post"
                                                                            id="delete_row_form_{{ $property->id }}">
                                                                            {{ method_field('DELETE') }}
                                                                            <input type="hidden" name="_token"
                                                                                value="{{ csrf_token() }}">
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <!-- Property Item Action Dropdown End -->
                                                    </h4>

                                                    <div class="property-item-address d-flex mt-15">
                                                        <div class="flex-shrink-0 font-13">
                                                            <i class="ri-map-pin-2-fill"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-1">
                                                            <p>{{ $property->propertyDetail?->address }}</p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="property-item-info d-flex mt-15 flex-wrap bg-white theme-border py-3 px-2 radius-4">
                                                        <div class="property-info-item font-13">
                                                            <i
                                                                class="ri-home-5-fill me-1 "></i>{{ $property->number_of_unit }}
                                                            {{ __('Unit') }}
                                                        </div>
                                                        <div class="property-info-item font-13">
                                                            <i
                                                                class="ri-dashboard-fill me-1 "></i>{{ propertyTotalRoom($property->id) }}
                                                            {{ __('rooms') }}
                                                        </div>
                                                        <div class="property-info-item font-13">
                                                            <i
                                                                class="ri-checkbox-circle-fill me-1 "></i>{{ $property->available_unit }}
                                                            {{ __('Available') }}
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('owner.property.show', $property->id) }}"
                                                        class="theme-btn mt-20 w-100"
                                                        title="{{ __('View Details') }}">{{ __('View Details') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Property Item End -->
                                    @empty
                                        <!-- Empty Properties row -->
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="empty-properties-box text-center">
                                                    <img src="{{ asset('assets/images/empty-img.png') }}" alt=""
                                                        class="img-fluid">
                                                    <h3 class="mt-25">{{ __('Empty Property') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Empty Properties row -->
                                    @endforelse
                                @else
                                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                        <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                            <div class="tenants-details-payment-history">
                                                <div class="account-settings-content-box">
                                                    <div class="tenants-details-payment-history-table">
                                                        <table id="allLeasePropertiesDataTable" class="table responsive theme-border p-20">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('SL') }}</th>
                                                                    <th>{{ __('Image') }}</th>
                                                                    <th data-priority="1">{{ __('Name') }}</th>
                                                                    <th>{{ __('Address') }}</th>
                                                                    <th>{{ __('Rooms') }}</th>
                                                                    <th>{{ __('Unit') }}</th>
                                                                    <th>{{ __('Available') }}</th>
                                                                    <th>{{ __('Action') }}</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Properties Item Wrap End -->
                    </div>
                    <!-- All Property Area row End -->
                </div>
                <!-- Page Content Wrapper End -->
            </div>
        </div>
        <!-- End Page-content -->
    </div>
    <input type="hidden" id="getAllPropertyRoute" value="{{ route('owner.property.leaseProperty') }}">
@endsection
@if (getOption('app_card_data_show', 1) != 1)
    @push('style')
        @include('common.layouts.datatable-style')
    @endpush
    @push('script')
        @include('common.layouts.datatable-script')
        <script src="{{ asset('assets/js/custom/propery-datatable.js') }}"></script>
    @endpush
@endif
