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
                                        <li class="breadcrumb-item"><a href="{{ route('owner.property.allProperty') }}"
                                                title="{{ __('Properties') }}">{{ __('Properties') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Property Details Area row Start -->
                    <div class="row">
                        <!-- Property Details Top Bar Start -->
                        <div class="property-top-search-bar property-details-top-bar mb-25">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ $property->name }}</h4>
                                    <div class="property-item-address d-flex mt-2">
                                        <div class="flex-shrink-0 font-13">
                                            <i class="ri-map-pin-2-fill"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-1">
                                            <p>{{ $property->address }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="property-details-right text-end">
                                        {{-- <button type="button" class="theme-btn" data-bs-toggle="modal"
                                            data-bs-target="#tenantAssignModal"
                                            title="{{ __('Tenant Assign') }}">{{ __('Tenant Assign') }}<i
                                                class="user-add-line ms-2"></i></button> --}}
                                        <a href="{{ route('owner.property.edit', $property->id) }}" class="edit-btn"
                                            title="{{ __('Edit Info') }}">{{ __('Edit Info') }}<i
                                                class="ri-arrow-right-line ms-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Property Details Top Bar End -->

                        <!-- Property Details Wrap Start -->
                        <div class="property-details-area">
                            <!-- Property details content -->
                            <div class="property-details-content mb-25">
                                <div class="col-12">
                                    <div class="property-details-img radius-4 mb-25">
                                        <img src="{{ $property->thumbnail_image }}" alt=""
                                            class="fit-image radius-4">
                                    </div>
                                    <h4 class="mb-2">{{ __('Description') }}</h4>
                                    <p>{{ $property->description }}</p>
                                </div>
                            </div>

                            <!-- Property details gallery -->
                            <div class="property-details-gallery mb-25">
                                <div class="col-12">
                                    <h4 class="mb-3">{{ __('Image Gallery') }}</h4>
                                    <div class="gallery-slider-carousel owl-carousel owl-theme">
                                        @forelse (@$property->propertyImages as $propertyImage)
                                            <div class="gallery-item radius-4">
                                                <div class="gallery-img">
                                                    <a href="{{ @$propertyImage->single_image->file_url }}" class="venobox"
                                                        data-gall="gallery01">
                                                        <img src="{{ @$propertyImage->single_image->file_url }}"
                                                            alt="" class="img-fluid">
                                                    </a>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="gallery-item radius-4">
                                                <div class="gallery-img">
                                                    <a href="#" class="venobox" data-gall="gallery01">
                                                        <img src="{{ asset('assets/images/users/empty-user.jpg') }}"
                                                            alt="" class="img-fluid">
                                                    </a>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>

                                </div>
                            </div>

                            <!-- Property details table -->
                            <div class="property-details-table bg-off-white mb-25 p-25 radius-4">
                                <div class="property-details-table-title border-bottom mb-25 pb-25">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h4>{{ __('Property Details') }}</h4>
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <div class="property-details-right text-end">
                                                <a href="{{ route('owner.property.edit', $property->id) }}"
                                                    class="edit-btn" title="{{ __('Edit Info') }}">{{ __('Edit Info') }}<i
                                                        class="ri-arrow-right-line ms-2"></i></a>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table theme-border bg-off-white p-20">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('Total Unit') }}</th>
                                                    <th class="text-end">{{ count($units) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Available for Lease') }}</th>
                                                    <th class="text-end">{{ $property->available_unit }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Current Tenants') }}</th>
                                                    <th class="text-end">
                                                        {{ $property->number_of_unit - $property->available_unit }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Average Rent') }}</th>
                                                    <th class="text-end">{{ currencyPrice($property->avg_general_rent) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Security Deposit') }}</th>
                                                    <th class="text-end">
                                                        {{ currencyPrice($property->total_security_deposit) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Late fee') }}</th>
                                                    <th class="text-end">{{ currencyPrice($property->total_late_fee) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Maintainer Name') }}</th>
                                                    <th class="text-end">{{ $property->first_name }}
                                                        {{ $property->last_name }}
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- All Unit Detials details table -->
                            <div class="property-details-table bg-off-white mb-25 p-25 radius-4">
                                <div class="property-details-table-title border-bottom mb-25 pb-25">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h4>{{ __('All Unit Details') }}</h4>
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <div class="property-details-right text-end">
                                                <button type="button" class="theme-btn" data-bs-toggle="modal"
                                                    data-bs-target="#tenantAssignModal"
                                                    title="{{ __('Tenant Assign') }}">{{ __('Tenant Assign') }}<i
                                                        class="user-add-line ms-2"></i></button>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table theme-border bg-off-white p-20">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SL') }}</th>
                                                    <th>{{ __('Unit Name') }}</th>
                                                    <th>{{ __('Bedroom') }}</th>
                                                    <th>{{ __('Baths') }}</th>
                                                    <th>{{ __('Kitchen') }}</th>
                                                    <th>{{ __('Square Feet') }}</th>
                                                    <th>{{ __('Amenities') }}</th>
                                                    <th>{{ __('Parking') }}</th>
                                                    <th>{{ __('Condition') }}</th>
                                                    <th>{{ __('Description') }}</th>
                                                    <th>{{ __('Image') }}</th>
                                                    <th>{{ __('Availability') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($units as $propertyUnit)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $propertyUnit->unit_name }}</td>
                                                        <td>{{ $propertyUnit->bedroom }}</td>
                                                        <td>{{ $propertyUnit->bath }}</td>
                                                        <td>{{ $propertyUnit->kitchen }}</td>
                                                        <td>{{ $propertyUnit->square_feet }}</td>
                                                        <td>{{ $propertyUnit->amenities }}</td>
                                                        <td>{{ $propertyUnit->parking }}</td>
                                                        <td>{{ $propertyUnit->condition }}</td>
                                                        <td>{{ Str::limit($propertyUnit->description, 100, '...') }}</td>
                                                        <td>
                                                            <img class="rounded-circle avatar-md tbl-user-image"
                                                                src="{{ assetUrl($propertyUnit->folder_name . '/' . $propertyUnit->file_name) }}">
                                                        </td>
                                                        <td>
                                                            @if (@$propertyUnit->first_name != null)
                                                                <span class="red-color">{{ __('Not Available') }}</span>
                                                            @else
                                                                <span class="green-color">{{ __('Available') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">{{ __('No Unit Found') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Property Details Wrap End -->
                    </div>
                    <!-- Property Details Area row End -->
                </div>
                <!-- Page Content Wrapper End -->
            </div>
        </div>
        <!-- End Page-content -->
    </div>

    <div class="modal fade" id="tenantAssignModal" tabindex="-1" aria-labelledby="tenantAssignModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tenantAssignModalLabel">{{ __('Tenant Assign') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.invoice.store') }}" method="post"
                    data-handler="getShowMessage">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <div class="modal-body">
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20 pb-0">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}</label>
                                    <select class="form-select flex-shrink-0 propertyUnitSelectOption"
                                        name="property_unit_id">
                                        <option value="">--{{ __('Select Unit') }}--</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $propertyUnit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Assign') }}">{{ __('Assign') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
