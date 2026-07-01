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
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="property-top-search-bar-right text-end">
                                        <button type="button" class="theme-btn mb-25" id="add"
                                                title="{{ __('Add Staff Users') }}">{{ __('Add Staff Users') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            <div class="table-responsive">
                                <table id="teamMemberDataTable" class="table theme-border p-20 ">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th data-priority="1">{{ __('Name') }}</th>
                                            <th class="d-none">{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Contact Number') }}</th>
                                            <th>{{ __('Status') }}</th>
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

    <!-- Add Team Member Modal Start -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel"><span class="modalTitle">{{ __('Add Staff Users') }}</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{route('owner.team-member.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" id="id" name="id" value="">
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name" class="form-control first_name"
                                           placeholder="{{ __('First Name') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name" class="form-control last_name"
                                           placeholder="{{ __('Last Name') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control email"
                                           placeholder="{{ __('Email') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}</label>
                                    <input type="text" name="contact_number" class="form-control contact_number"
                                           placeholder="{{ __('Contact Number') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}</label>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="{{ __('Password') }}">
                                </div>
                                <div class="col-md-6 mc-25">
                                    <label for="" class="label-text-title color-heading font-medium mb-2">{{__('Assign Role')}}</label>
                                    <div class="my-custom-select-box">
                                        <select name="role"
                                                class="form-control role">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mc-25">
                                    <label for="" class="label-text-title color-heading font-medium mb-2">{{__('Status')}}</label>
                                    <div class="my-custom-select-box">
                                        <select name="status" id="status" class="form-control status">
                                            <option value="{{ACTIVE}}">{{ __('Active') }}</option>
                                            <option value="{{DEACTIVATE}}">{{ __('Deactivate') }}</option>
                                        </select>
                                    </div>
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

    <div class="modal fade" id="editTeamMemberModal" tabindex="-1" aria-labelledby="editTeamMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <input type="hidden" id="teamMemberIndexRoute" value="{{ route('owner.team-member.index') }}">

@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/team-member.js') }}"></script>
@endpush
