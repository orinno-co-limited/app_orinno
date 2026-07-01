    @forelse ($tickets as $ticket)
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 ticket-column status{{ $ticket->status }}">
            <div class="ticket-item bg-off-white theme-border radius-10 mb-25">
                <div class="ticket-item-content p-20">

                    <div class="ticket-item-top-bar d-flex align-items-center border-bottom pb-20 mb-20">
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
                                <p class="status-btn status-btn-green">{{ __('Resolved') }}</p>
                            @else
                                <p class="status-btn status-btn-red">{{ __('Close') }}</p>
                            @endif
                        </div>
                        <div class="ticket-item-dropdown text-end ms-2">
                            <div class="dropdown">
                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </a>
                                <ul
                                    class="dropdown-menu {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
                                    @if ($ticket->status == TICKET_STATUS_OPEN)
                                        <li><a class="dropdown-item font-13 edit" data-id="{{ $ticket->id }}"
                                                href="javascript:;"
                                                title="{{ __('Edit') }}">{{ __('Edit') }}</a>
                                        </li>
                                        <li><a class="dropdown-item font-13 deleteItem" href="javascript:;"
                                                data-formid="delete_row_form_{{ $ticket->id }}"
                                                title="{{ __('Delete') }}">{{ __('Delete') }}</a>
                                            <form action="{{ route('tenant.ticket.delete', [$ticket->id]) }}"
                                                method="post" id="delete_row_form_{{ $ticket->id }}">
                                                {{ method_field('DELETE') }}
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            </form>
                                        </li>
                                    @endif
                                    <li><a class="dropdown-item font-13 statusChange"
                                            data-url="{{ route('tenant.ticket.status.change') }}"
                                            data-id="{{ $ticket->id }}" data-status="3" href="javascript:;"
                                            title="{{ __('Close') }}">{{ __('Close') }}</a>
                                    </li>
                                    <li><a class="dropdown-item font-13 statusChange"
                                            data-url="{{ route('tenant.ticket.status.change') }}"
                                            data-id="{{ $ticket->id }}" data-status="5" href="javascript:;"
                                            title="{{ __('Re Solved') }}">{{ __('Resolved') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="ticket-item-content-box mb-3">
                        <h6 class="mb-2">{{ __('Title') }}</h6>
                        <p class="font-13 ticketTitle">
                            {{ Str::limit($ticket->title, 30, '...') }}</p>
                    </div>
                    <div class="ticket-item-content-box mb-3">
                        <h6 class="mb-2">{{ __('Details') }}</h6>
                        <p class="font-13">{{ Str::limit($ticket->details, 60, '...') }}</p>
                    </div>
                    <div class="ticket-item-content-box attachment-ticket-item-content-box border-bottom">
                        <h6 class="mb-2">{{ __('Attachments') }}</h6>
                        <div class="tickets-attachment-gallery row">
                            @foreach ($ticket->attachments->take(3) as $attachment)
                                <div class="col-auto">
                                    @if (in_array(pathinfo($attachment->file_name, PATHINFO_EXTENSION), imageExtensionList()))
                                        <a href="{{ $attachment->FileUrl }}" class="venobox tickets-attachment-item"
                                            data-gall="attach{{ $attachment->id }}">
                                            <img src="{{ $attachment->FileUrl }}" alt="" class="fit-image">
                                        </a>
                                    @else
                                        <a href="{{ $attachment->FileUrl }}" class="" download>
                                            {{ $attachment->file_name }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if ($ticket->status == TICKET_STATUS_OPEN)
                        <a href="{{ route('tenant.ticket.details', $ticket->id) }}" class="theme-btn w-100"
                            title="{{ __('Open') }}">{{ __('Details') }}</a>
                    @elseif ($ticket->status == TICKET_STATUS_INPROGRESS)
                        <a href="{{ route('tenant.ticket.details', $ticket->id) }}" class="theme-btn w-100"
                            title="{{ __('In Proprogress') }}">{{ __('Details') }}</a>
                    @elseif ($ticket->status == TICKET_STATUS_RESOLVED)
                        <a href="{{ route('tenant.ticket.details', $ticket->id) }}" class="theme-btn w-100"
                            title="{{ __('Resolved') }}">{{ __('Details') }}</a>
                    @else
                        <a href="{{ route('tenant.ticket.details', $ticket->id) }}" class="theme-btn-red w-100"
                            title="{{ __('Close') }}">{{ __('Details') }}</a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                <div class="empty-properties-box text-center">
                    <img src="{{ asset('assets/images/empty-img.png') }}" alt="" class="img-fluid">
                    <h3 class="mt-25">{{ __('Empty Ticket') }}</h3>
                    <div class="mt-25">
                        <a href="{{ route('tenant.ticket.index') }}" class="theme-btn"
                            title="{{ __('My Tickets') }}">{{ __('My Tickets') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
