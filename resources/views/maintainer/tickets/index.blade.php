@extends('maintainer.layouts.app')

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
                        <div class="tickets-item-wrap">
                            <div class="row">
                                @forelse ($tickets as $ticket)
                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3">
                                        <div class="ticket-item bg-off-white theme-border radius-10 mb-25">
                                            <div class="ticket-item-content p-20">
                                                <div
                                                    class="ticket-item-top-bar d-flex align-items-center border-bottom pb-20 mb-20">
                                                    <h4 class="mb-1">{{ __('Ticket') }} #{{ $ticket->ticket_no }}</h4>
                                                    <div class="flex-grow-1 ms-2">
                                                        @if ($ticket->status == TICKET_STATUS_OPEN)
                                                            <p class="status-btn status-btn-orange">{{ __('Open') }}</p>
                                                        @elseif ($ticket->status == TICKET_STATUS_INPROGRESS)
                                                            <p class="status-btn status-btn-blue">{{ __('Inprogress') }}
                                                            </p>
                                                        @elseif ($ticket->status == TICKET_STATUS_REOPEN)
                                                            <p class="status-btn status-btn-red">{{ __('Reopen') }}</p>
                                                        @elseif ($ticket->status == TICKET_STATUS_RESOLVED)
                                                            <p class="status-btn status-btn-green">{{ __('Resolved') }}
                                                            </p>
                                                        @else
                                                            <p class="status-btn status-btn-red">{{ __('Close') }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="ticket-item-dropdown text-end ms-2">
                                                        <div class="dropdown">
                                                            <a class="dropdown-toggle dropdown-toggle-nocaret"
                                                                href="#" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="ri-more-2-fill"></i>
                                                            </a>
                                                            <ul
                                                                class="dropdown-menu {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">

                                                                <li><a class="dropdown-item font-13 statusChange"
                                                                        data-url="{{ route('maintainer.ticket.status.change') }}"
                                                                        data-id="{{ $ticket->id }}" data-status="2"
                                                                        href="javascript:;"
                                                                        title="{{ __('In Processing') }}">{{ __('Inprocessing') }}</a>
                                                                </li>
                                                                <li><a class="dropdown-item font-13 statusChange"
                                                                        data-url="{{ route('maintainer.ticket.status.change') }}"
                                                                        data-id="{{ $ticket->id }}" data-status="3"
                                                                        href="javascript:;"
                                                                        title="{{ __('Close') }}">{{ __('Close') }}</a>
                                                                </li>
                                                                <li><a class="dropdown-item font-13 statusChange"
                                                                        data-url="{{ route('maintainer.ticket.status.change') }}"
                                                                        data-id="{{ $ticket->id }}" data-status="5"
                                                                        href="javascript:;"
                                                                        title="{{ __('Re Solved') }}">{{ __('Resolved') }}</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ticket-item-content-box mb-3">
                                                    <h6 class="mb-2">{{ __('Title') }}</h6>
                                                    <p class="font-13">{{ Str::limit($ticket->title, 30, '...') }}</p>
                                                </div>
                                                <div class="ticket-item-content-box mb-3">
                                                    <h6 class="mb-2">{{ __('Details') }}</h6>
                                                    <p class="font-13">{{ Str::limit($ticket->details, 60, '...') }}</p>
                                                </div>
                                                <div
                                                    class="ticket-item-content-box attachment-ticket-item-content-box border-bottom">
                                                    <h6 class="mb-2">{{ __('Attachments') }}</h6>
                                                    <div class="tickets-attachment-gallery row">
                                                        @foreach ($ticket->attachments->take(3) as $attachment)
                                                            <div class="col">
                                                                @if (in_array(pathinfo($attachment->file_name, PATHINFO_EXTENSION), imageExtensionList()))
                                                                    <a href="{{ $attachment->FileUrl }}"
                                                                        class="venobox tickets-attachment-item"
                                                                        data-gall="attach{{ $attachment->id }}">
                                                                        <img src="{{ $attachment->FileUrl }}"
                                                                            alt="" class="fit-image">
                                                                    </a>
                                                                @else
                                                                    <a href="{{ $attachment->FileUrl }}" class=""
                                                                        download>
                                                                        {{ $attachment->file_name }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @if ($ticket->status == TICKET_STATUS_OPEN)
                                                    <a href="{{ route('maintainer.ticket.details', $ticket->id) }}"
                                                        class="theme-btn w-100"
                                                        title="{{ __('Close') }}">{{ __('Details') }}</a>
                                                @elseif ($ticket->status == TICKET_STATUS_INPROGRESS)
                                                    <a href="{{ route('maintainer.ticket.details', $ticket->id) }}"
                                                        class="theme-btn w-100"
                                                        title="{{ __('Close') }}">{{ __('Details') }}</a>
                                                @elseif ($ticket->status == TICKET_STATUS_RESOLVED)
                                                    <a href="{{ route('maintainer.ticket.details', $ticket->id) }}"
                                                        class="theme-btn w-100"
                                                        title="{{ __('Close') }}">{{ __('Details') }}</a>
                                                @else
                                                    <a href="{{ route('maintainer.ticket.details', $ticket->id) }}"
                                                        class="theme-btn-red w-100"
                                                        title="{{ __('Close') }}">{{ __('Close') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                                            <div class="empty-properties-box text-center">
                                                <img src="{{ asset('assets/images/empty-img.png') }}" alt=""
                                                    class="img-fluid">
                                                <h3 class="mt-25">{{ __('Empty Ticket') }}</h3>
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
@endsection
@push('script')
    <script src="{{ asset('assets/js/custom/ticket.js') }}"></script>
@endpush
