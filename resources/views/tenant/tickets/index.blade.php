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
                        <!-- Property Top Search Bar Start -->
                        <div class="property-top-search-bar">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="property-top-search-bar-left">

                                        <div class="row">
                                            <div class="col-md-6 col-lg-6 col-xl-4 mb-25">
                                                <select class="form-select flex-shrink-0 statusSearch">
                                                    <option value="" selected>{{ __('Search Status') }}</option>
                                                    <option value="1">{{ __('Open') }}</option>
                                                    <option value="2">{{ __('Inprogress') }}</option>
                                                    <option value="3">{{ __('Close') }}</option>
                                                    <option value="4">{{ __('Reopen') }}</option>
                                                    <option value="5">{{ __('Re Solved') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-25">
                                                <div class="page-inner-search position-relative">
                                                    <input type="text" class="form-control textSearch"
                                                        placeholder="{{ __('Search') }}">
                                                    <span class="ri-search-line"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="property-top-search-bar-right text-end">
                                        <button type="button" class="theme-btn mb-25" data-bs-toggle="modal"
                                            data-bs-target="#addTicketModal"
                                            title="{{ __('Create Ticket') }}">{{ __('Create Ticket') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tickets-item-wrap">
                            <div class="row" id="ticketAppend">
                               @include('tenant.tickets.single-view')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal  --}}
    <div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addTicketModalLabel">{{ __('Create Ticket') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('tenant.ticket.store') }}" method="POST"
                    data-handler="getShowMessage">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        placeholder="{{ __('Title') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea class="form-control" name="details" placeholder="{{ __('Details') }}"></textarea>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Topic') }}</label>
                                    <select class="form-select flex-shrink-0" name="topic_id">
                                        <option value="">--{{ __('Select Topic') }}--</option>
                                        @foreach ($topics as $topic)
                                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <label class="label-text-title color-heading font-medium mb-2"
                                        for="{{ __('Attachments') }}">{{ __('Attachments') }}</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="file" id="attachments" name="attachments[]" class="dropify"
                                                data-height="220" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Create Ticket') }}">{{ __('Create Ticket') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editTicketModal" tabindex="-1" aria-labelledby="editTicketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editTicketModalLabel">{{ __('Edit Ticket') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('tenant.ticket.store') }}" method="POST"
                    data-handler="getShowMessage">
                    <input type="hidden" class="id" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" class="form-control title" name="title"
                                        placeholder="{{ __('Title') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea class="form-control details" name="details" placeholder="{{ __('Details') }}"></textarea>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Topic') }}</label>
                                    <select class="form-select flex-shrink-0 topic" name="topic_id">
                                        <option value="">--{{ __('Select Topic') }}--</option>
                                        @foreach ($topics as $topic)
                                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <label class="label-text-title color-heading font-medium mb-2"
                                        for="{{ __('Attachments') }}">{{ __('Attachments') }}</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="file" id="attachments" name="attachments[]" class="dropify"
                                                data-height="220" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="getInfoRoute" value="{{ route('tenant.ticket.get.info') }}">
    <input type="hidden" id="searchRoute" value="{{ route('tenant.ticket.search') }}">
@endsection

@push('script')
    <script src="{{ asset('assets/js/custom/ticket.js') }}"></script>
    <script src="{{ asset('assets/js/custom/ticket-search.js') }}"></script>
@endpush
