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
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Information Page Area row Start -->
                    <!-- Notice Board Page Area row Start -->
                    <div class="row">
                        <!-- Property Top Search Bar Start -->
                        <div class="property-top-search-bar">
                            <div class="row align-items-center">
                                <div class="col-md-12 text-end">
                                    <div class="property-top-search-bar-right">
                                        <button type="button" class="theme-btn mb-25" id="add"
                                            title="{{ __('Add New Notice') }}">{{ __('Add New Notice') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Property Top Search Bar End -->
                        <!-- Notice Board Table Area Start -->
                        <div class="notice-board-table-area">
                            <!-- datatable Start -->
                            <div class="bg-off-white theme-border radius-4 p-25">
                                <table id="allNoticeDataTable"
                                    class="table bg-off-white aaa theme-border p-20 dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th>{{ __('Notice Title') }}</th>
                                            <th>{{ __('Property') }}</th>
                                            <th>{{ __('Details') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- datatable End -->
                        </div>
                        <!-- Notice Board Table Area End -->
                    </div>
                    <!-- Notice Board Page Area row End -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Notice Modal Start -->
    <div class="modal fade" id="addNoticeModal" tabindex="-1" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addNoticeModalLabel">{{ __('Add Notice Board') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.noticeboard.store') }}" method="POST"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Notice Title') }}</label>
                                    <input type="text" name="title" class="form-control title"
                                        placeholder="{{ __('Notice Title') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property Name') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="" selected>--{{ __('Select Property') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}"
                                                data-units="{{ $property->propertyUnits }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-20">
                                        <div class="form-group custom-checkbox" title="{{ __('All Property') }}">
                                            <input type="checkbox" id="checkNoticeBoardAllProperty" name="all_property">
                                            <label class="color-heading font-medium"
                                                for="checkNoticeBoardAllProperty">{{ __('All Property') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}</label>
                                    <select class="form-select flex-shrink-0 unit_id" name="unit_id" id="unitOption">
                                        <option value="" selected>--{{ __('Select Unit') }}--</option>
                                    </select>
                                    <div class="mt-20">
                                        <div class="form-group custom-checkbox" title="{{ __('All Unit') }}">
                                            <input type="checkbox" id="checkNoticeBoardAllUnit" name="all_unit">
                                            <label class="color-heading font-medium"
                                                for="checkNoticeBoardAllUnit">{{ __('All Unit') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea class="form-control details" name="details" placeholder="{{ __('Write details here...') }}"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Notice Start Date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" class="datepicker form-control start_date"
                                                name="start_date" autocomplete="off" placeholder="dd-mm-yy">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Notice End date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" class="datepicker form-control end_date"
                                                name="end_date" autocomplete="off" placeholder="dd-mm-yy">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Upload Files') }}</label>
                                    <input class="form-control" type="file" name="image" multiple>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
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

    <div class="modal fade" id="editNoticeModal" tabindex="-1" aria-labelledby="editNoticeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editNoticeModalLabel">{{ __('Edit Notice Board') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.noticeboard.store') }}" method="POST"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Notice Title') }}</label>
                                    <input type="text" name="title" class="form-control title"
                                        placeholder="{{ __('Notice Title') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property Name') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="">--{{ __('Select Property') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}"
                                                data-units="{{ $property->propertyUnits }}">{{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="mt-20">
                                        <div class="form-group custom-checkbox" title="{{ __('All Property') }}">
                                            <input type="checkbox" id="checkNoticeBoardAllProperty" name="all_property">
                                            <label class="color-heading font-medium"
                                                for="checkNoticeBoardAllProperty">{{ __('All Property') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}</label>
                                    <select class="form-select flex-shrink-0 unit_id" name="unit_id" id="unitOption">
                                        <option value="" selected>--{{ __('Select Unit') }}--</option>
                                    </select>
                                    <div class="mt-20">
                                        <div class="form-group custom-checkbox" title="{{ __('All Unit') }}">
                                            <input type="checkbox" id="checkNoticeBoardAllUnit" name="all_unit">
                                            <label class="color-heading font-medium"
                                                for="checkNoticeBoardAllUnit">{{ __('All Unit') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea class="form-control details" name="details" placeholder="{{ __('Write details here...') }}"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Notice Start Date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" class="datepicker form-control start_date"
                                                name="start_date" autocomplete="off" placeholder="dd-mm-yy">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Notice End date') }}</label>
                                    <div class="custom-datepicker">
                                        <div class="custom-datepicker-inner position-relative">
                                            <input type="text" class="datepicker form-control end_date"
                                                name="end_date" autocomplete="off" placeholder="dd-mm-yy">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Upload Files') }}</label>
                                    <input class="form-control" type="file" name="image" multiple>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
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
    <!-- Add New Notice Modal End -->

    <!-- View Notice Board Details Modal Start -->
    <div class="modal fade" id="viewNoticeBoardDetailsModal" tabindex="-1"
        aria-labelledby="viewNoticeBoardtDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewNoticeBoardtDetailsModalLabel">{{ __('Notice') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <div class="modal-body pb-0">
                    <div class="view-information-page-modal-content">

                        <div class="view-information-page-box mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Notice Title') }}</label>
                            <p class="viewtitle"></p>
                        </div>

                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}</label>
                            <h4 class="theme-text-color font-14 font-normal property"></h4>
                        </div>
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}</label>
                            <h4 class="theme-text-color font-14 font-normal unit"></h4>
                        </div>

                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                            <p class="details"></p>
                        </div>

                        <div class="view-information-page-box mb-25">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="notice-view-start-date">
                                        <label
                                            class="label-text-title color-heading font-medium mb-2">{{ __('Start Date') }}</label>
                                        <p class="start_date"></p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="notice-view-start-date">
                                        <label
                                            class="label-text-title color-heading font-medium mb-2">{{ __('End Date') }}</label>
                                        <p class="end_date"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="view-information-page-box">
                            <!-- Show Uploaded Files and Documents Start -->
                            <div class="uploaded-documents-wrapper">
                            <div class="show-uploaded-documents d-flex align-items-center mb-15">
                                <div
                                    class="show-uploaded-documents-img flex-shrink-0 bg-blue-transparent radius-4 overflow-hidden p-2 me-2">
                                    <img class="img-fluid" src="{{ asset('assets/images/file-text-line.svg') }}"
                                        alt="File-Image">
                                </div>
                                <div class="show-uploaded-documents-content flex-grow-1 d-inline-flex align-items-center">
                                    <h5 class="me-3">{{ __('File') }}</h5>
                                    <div class="d-inline-flex">
                                        <a href="" class="uploaded-document-icon font-20 theme-link me-2 image"
                                            title="Download" download=""><i class="ri-download-2-line"></i></a>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <!-- Show Uploaded Files and Documents End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View Notice Board Details Modal End -->
    <input type="hidden" id="getInfoRoute" value="{{ route('owner.noticeboard.get.info') }}">
    <input type="hidden" id="route" value="{{ route('owner.noticeboard.index') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/noticeboard.js') }}"></script>
@endpush
