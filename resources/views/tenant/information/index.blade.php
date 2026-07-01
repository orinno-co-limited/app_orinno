@extends('tenant.layouts.app')

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
                                        <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="tenant-portal-information-item-wrap">
                            <div class="row">
                                @forelse ($information as $info)
                                    <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3">
                                        <div
                                            class="property-item tenant-information-item bg-off-white theme-border radius-10 mb-25">
                                            <a href="#" data-bs-toggle="modal"
                                                class="property-item-img-wrap information-item-wrap d-block position-relative overflow-hidden radius-10">
                                                <div class="property-item-img">
                                                    <img src="{{ $info->image }}" alt=""
                                                        class="fit-image radius-4">
                                                </div>
                                            </a>
                                            <div class="property-item-content p-20">
                                                <h4 class="property-item-title">
                                                    <a href="#" data-bs-toggle="modal"
                                                        class="color-heading link-hover-effect">{{ $info->name }}</a>
                                                </h4>
                                                <p class="mt-15">{{ Str::limit($info->additional_information, 50, '...') }}
                                                </p>
                                                <p class="font-medium mt-15">{{ __('Distance') }}:<span
                                                        class="ms-2 color-heading">{{ $info->distance }}</span></p>
                                                <p class="font-medium mt-15">{{ __('Contact Number') }}:<span
                                                        class="ms-2 color-heading">{{ $info->contact_number }}</span></p>

                                                <button type="button" class="theme-btn mt-20 w-100 view"
                                                    data-id="{{ $info->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#viewTenantInformationModal"
                                                    title="{{ __('View Details') }}">{{ __('View Details') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                                            <div class="empty-properties-box text-center">
                                                <img src="{{ asset('assets/images/empty-img.png') }}" alt=""
                                                    class="img-fluid">
                                                <h3 class="mt-25">{{ __('Empty Information') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal  --}}
    <div class="modal fade" id="viewTenantInformationModal" tabindex="-1" aria-labelledby="viewTenantInformationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewTenantInformationModalLabel">{{ __('Information') }}</h4>
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
    <input type="hidden" id="getInfoRoute" value="{{ route('tenant.information.get.info') }}">
@endsection

@push('script')
    <script src="{{ asset('assets/js/custom/information-view.js') }}"></script>
@endpush
