<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketReplyRequest;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public $ticketService;

    public function __construct()
    {
        $this->ticketService = new TicketService;
    }

    public function index(Request $request)
    {
        if (isAddonInstalled('PROTYSAAS') > 1) {
            if (ownerCurrentPackage(getOwnerUserId())?->ticket_support != ACTIVE) {
                return abort(403);
            }
        }
        $data['pageTitle'] = __('Tickets');
        if (getOption('app_card_data_show', 1) == 1) {
            $data['tickets'] = $this->ticketService->getAll();
        }
        if ($request->ajax()) {
            return $this->ticketService->getAllData();
        }
        return view('owner.tickets.index', $data);
    }

    public function details($id)
    {
        $data['pageTitle'] = __('Ticket Details');
        $data['navmmActiveClass'] = 'mm-active';
        $data['navActiveClass'] = 'active';
        $data['ticket']  = $this->ticketService->getById($id);
        $data['replies'] = $this->ticketService->getRepliesByTicketId($id);
        return view('owner.tickets.details', $data);
    }

    public function reply(TicketReplyRequest $request)
    {
        return $this->ticketService->reply($request);
    }

    public function statusChange(Request $request)
    {
        return $this->ticketService->statusChange($request);
    }
}
