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
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <a href="{{ route('owner.property.add') }}" class="theme-btn mb-25"
                                        title="Add New Proparty">{{ __('Add New Property') }}</a>
                                </div>
                            </div>
                            <div class="property-filter-bar bg-off-white theme-border radius-10 p-20 mb-25">
                                <div class="property-filter-row">
                                    <div class="property-filter-search page-inner-search position-relative">
                                        <span class="ri-search-line"></span>
                                        <input type="text" class="form-control property-search"
                                            placeholder="{{ __('Search by name, address, district...') }}">
                                    </div>
                                    <div class="property-filter-selects">
                                        <select class="form-select property-filter-category">
                                            <option value="">{{ __('All Categories') }}</option>
                                            @foreach (propertyCategoryOptions() as $categoryValue => $categoryLabel)
                                                <option value="{{ $categoryValue }}">{{ $categoryLabel }}</option>
                                            @endforeach
                                        </select>
                                        <select class="form-select property-filter-status">
                                            <option value="">{{ __('All Status') }}</option>
                                            <option value="available">{{ __('Available') }}</option>
                                            <option value="rented">{{ __('Fully Rented') }}</option>
                                        </select>
                                        <select class="form-select property-filter-district">
                                            <option value="">{{ __('All Districts') }}</option>
                                            @foreach ($districts ?? [] as $district)
                                                <option value="{{ $district }}">{{ $district }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="property-filter-view-toggle-wrap">
                                        <div class="property-view-toggle">
                                            <button type="button"
                                                class="view-toggle-btn {{ getOption('app_card_data_show', 1) == 1 ? 'active' : '' }}"
                                                data-view="grid"
                                                title="{{ __('Grid View') }}"><i class="ri-grid-fill"></i></button>
                                            <button type="button"
                                                class="view-toggle-btn {{ getOption('app_card_data_show', 1) == 1 ? '' : 'active' }}"
                                                data-view="list"
                                                title="{{ __('List View') }}"><i class="ri-list-check-2"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="property-filter-result-count font-13 text-muted mt-2" id="propertyResultCount"></div>
                            </div>
                        </div>
                        <!-- Property Top Search Bar End -->
                        <!-- Properties Item Wrap Start -->
                        <div id="propertyGridView" class="properties-item-wrap {{ getOption('app_card_data_show', 1) == 1 ? '' : 'd-none' }}">
                            <div class="row">
                                    @forelse($properties as $property)
                                        <!-- Property Item Start -->
                                        <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3 property-grid-item"
                                            data-category="{{ propertyFilterToken($property, 'category') }}"
                                            data-status="{{ propertyFilterToken($property, 'status') }}"
                                            data-district="{{ propertyFilterToken($property, 'district') }}"
                                            data-search="{{ strtolower($property->name . ' ' . $property->propertyDetail?->address . ' ' . $property->propertyDetail?->state_id) }}">
                                            <div
                                                class="property-item bg-off-white theme-border radius-10 position-relative mb-25">
                                                <a href="{{ route('owner.property.show', $property->id) }}"
                                                    class="property-item-img-wrap d-block position-relative overflow-hidden radius-10">
                                                    <div class="property-item-img">
                                                        <img src="{{ $property->thumbnail_image }}" alt=""
                                                            class="fit-image">
                                                    </div>
                                                </a>
                                                <div class="property-item-badges">
                                                    @if ($property->available_unit <= 0)
                                                        <div class="status-btn status-btn-red font-13 radius-4">{{ __('Fully Rented') }}</div>
                                                    @else
                                                        <div class="status-btn status-btn-green font-13 radius-4">{{ __('Available') }}</div>
                                                    @endif
                                                </div>
                                                <div class="property-item-type-badge">
                                                    <div class="status-btn status-btn-purple font-13 radius-4">
                                                        {{ $property->property_type == PROPERTY_TYPE_LEASE ? __('Lease') : __('Own') }}
                                                    </div>
                                                </div>
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
                                                            <p>{{ trim($property->propertyDetail?->address . ($property->propertyDetail?->state_id ? ', ' . $property->propertyDetail?->state_id : ''), ', ') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="property-item-meta-row">
                                                        <span><i class="ri-price-tag-3-fill"></i>{{ propertyCategoryLabel($property->category) }}</span>
                                                        <span><i class="ri-home-5-fill"></i>{{ $property->number_of_unit }} {{ __('Unit') }}</span>
                                                        <span><i class="ri-dashboard-fill"></i>{{ propertyTotalRoom($property->id) }} {{ __('rooms') }}</span>
                                                        <span><i class="ri-checkbox-circle-fill"></i>{{ $property->available_unit }} {{ __('Available') }}</span>
                                                    </div>
                                                    {!! renderPropertyAmenityBadges($property) !!}
                                                    <div class="property-item-footer">
                                                        <div>
                                                            @if ($property->starting_price)
                                                                <div class="property-item-price">{{ currencyPrice($property->starting_price) }}</div>
                                                                <div class="property-item-price-sub">{{ __('per month') }} &middot; {{ $property->property_type == PROPERTY_TYPE_LEASE ? __('Lease') : __('Own') }}</div>
                                                            @else
                                                                <div class="property-item-price text-muted">{{ __('N/A') }}</div>
                                                            @endif
                                                        </div>
                                                        <a href="{{ route('owner.property.show', $property->id) }}"
                                                            class="theme-btn" title="{{ __('View Details') }}">{{ __('View') }}</a>
                                                    </div>
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
                            </div>
                        </div>

                        <div id="propertyListView" class="properties-item-wrap {{ getOption('app_card_data_show', 1) == 1 ? 'd-none' : '' }}">
                            <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                        <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                            <div class="tenants-details-payment-history">
                                                <div class="account-settings-content-box">
                                                    <div class="tenants-details-payment-history-table">
                                                        <table id="allLeasePropertiesDataTable" class="table responsive theme-border p-20">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('SL') }}</th>
                                                                    <th data-priority="1">{{ __('Property') }}</th>
                                                                    <th>{{ __('Price') }}</th>
                                                                    <th class="d-none">{{ __('Type') }}</th>
                                                                    <th class="d-none">{{ __('Status') }}</th>
                                                                    <th class="d-none">{{ __('District') }}</th>
                                                                    <th>{{ __('Action') }}</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
@push('style')
    @include('common.layouts.datatable-style')
@endpush
@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/propery-datatable.js') }}"></script>
    <script src="{{ asset('assets/js/custom/property-view-toggle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/property-filters.js') }}"></script>
@endpush
