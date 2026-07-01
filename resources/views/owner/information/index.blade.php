@extends('owner.layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="page-content-wrapper bg-white p-30 radius-20">
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
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="property-top-search-bar">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <div class="property-top-search-bar-left">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6 col-xl-4 mb-25">
                                                <select class="form-select flex-shrink-0 " id="search_property">
                                                    <option value="">--{{ __('Search Property') }}--</option>
                                                    @foreach ($properties as $property)
                                                        <option value="{{ $property->name }}">{{ $property->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="property-top-search-bar-right text-end">
                                        <button type="button" class="theme-btn mb-25" id="add"
                                            title="{{ __('Add New Information') }}">{{ __('Add New Information') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="information-table-area">
                            <div class="bg-off-white theme-border radius-4 p-25">
                                <table id="allInfoDataTable" class="table bg-off-white theme-border dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th>{{ __('Image') }}</th>
                                            <th data-priority="1">{{ __('Name') }}</th>
                                            <th>{{ __('Property') }}</th>
                                            <th>{{ __('Distance') }}</th>
                                            <th>{{ __('Contact Number') }}</th>
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

    <div class="modal fade" id="informationModal" tabindex="-1" aria-labelledby="informationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="informationModalLabel">{{ __('Add Information') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.information.store') }}" method="POST"
                    data-handler="getShowMessage">
                    <div class="modal-body">
                        @csrf
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Select Property') }}
                                        <strong class="text-danger">*</strong></label>
                                    <select class="form-select flex-shrink-0" name="property_id">
                                        <option value="" selected>{{ __('Select Property') }}</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h4 class="mb-15">{{ __('Information') }}</h4>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}
                                        <strong class="text-danger">*</strong> </label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Distance') }}
                                        <strong class="text-danger">*</strong> </label>
                                    <input type="text" name="distance" class="form-control"
                                        placeholder="{{ __('Distance') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}</label>
                                    <input type="text" name="contact_number" class="form-control"
                                        placeholder="{{ __('Contact Number') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Additional Information') }}</label>
                                    <textarea class="form-control" name="additional_information" placeholder="{{ __('Additional Information') }}"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Upload Image') }}
                                        <strong class="text-danger">*</strong></label>
                                    <input class="form-control" type="file" name="image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Submit') }}">{{ __('Submit') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editInformationModal" tabindex="-1" aria-labelledby="editInformationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editInformationModalLabel">{{ __('Edit Information') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.information.store') }}" method="POST"
                    data-handler="getShowMessage">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Select Property') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="" selected>{{ __('Select Property') }}</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->

                        <!-- Modal Inner Form Box Start -->
                        <h4 class="mb-15">{{ __('Information') }}</h4>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control name"
                                        placeholder="{{ __('Name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Distance') }}</label>
                                    <input type="text" name="distance" class="form-control distance"
                                        placeholder="{{ __('Distance') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}</label>
                                    <input type="text" step="any" name="contact_number"
                                        class="form-control contact_number" placeholder="{{ __('Contact Number') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Additional Information') }}</label>
                                    <textarea class="form-control additional_information" name="additional_information"
                                        placeholder="{{ __('Additional Information') }}"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Upload Image') }}</label>
                                    <input class="form-control" type="file" name="image">
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Submit') }}">{{ __('Update') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="viewInformationModal" tabindex="-1" aria-labelledby="viewInformationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewInformationModalLabel">{{ __('Information') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <div class="modal-body">
                    <div class="view-information-page-modal-content">
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Image') }}</label>
                            <div class="information-details-img radius-4 mb-25">
                                <img class="fit-image radius-4 image">
                            </div>
                        </div>
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Name') }} :
                            </label> <span class="name"></span>
                        </div>
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Property') }} : </label>
                            <span class="property"></span>
                        </div>

                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Distance') }} : </label>
                            <span class="distance"></span>
                        </div>

                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }} :
                            </label>
                            <span class="contact_number"></span>
                        </div>

                        <div class="view-information-page-box">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Additional Information') }}
                                : </label>
                            <span class="additional_information"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Information Modal End -->
    <input type="hidden" id="getInfoRoute" value="{{ route('owner.information.get.info') }}">
    <input type="hidden" id="route" value="{{ route('owner.information.index') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/information.js') }}"></script>
@endpush
