<?php

namespace App\Http\Controllers\Maintainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketReplyRequest;
use App\Services\PropertyService;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public $ticketService, $propertyService;

    public function __construct()
    {
        $this->ticketService = new TicketService;
        $this->propertyService = new PropertyService;
    }

    public function index()
    {
        $data['pageTitle'] = __('Tickets');
        $maintainer = auth()->user()->maintainer;
        $propertyIds = $this->propertyService->getPropertyIdsByMaintainerIds($maintainer->user_id);
        $data['tickets'] = $this->ticketService->getAllByPropertyId($propertyIds);
        return view('maintainer.tickets.index', $data);
    }

    public function details($id)
    {
        $data['pageTitle'] = __('Ticket Details');
        $data['navmmActiveClass'] = 'mm-active';
        $data['navActiveClass'] = 'active';
        $data['ticket']  = $this->ticketService->getById($id);
        $data['replies'] = $this->ticketService->getRepliesByTicketId($id);
        return view('maintainer.tickets.details', $data);
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
