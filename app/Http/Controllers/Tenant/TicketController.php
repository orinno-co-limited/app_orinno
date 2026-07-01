<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketReplyRequest;
use App\Http\Requests\TicketRequest;
use App\Services\TicketService;
use App\Services\TicketTopicService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use ResponseTrait;
    public $ticketService;
    public $ticketTopicService;

    public function __construct()
    {
        $this->ticketService = new TicketService;
        $this->ticketTopicService = new TicketTopicService;
    }

    public function index()
    {
        if (isAddonInstalled('PROTYSAAS') > 1) {
            if (ownerCurrentPackage(auth()->user()->owner_user_id)?->ticket_support != ACTIVE) {
                return abort(403);
            }
        }
        $data['pageTitle'] = __('Tickets');
        $data['tickets'] = $this->ticketService->getAllByUnitId(auth()->user()->tenant->unit_id);
        $data['topics'] = $this->ticketTopicService->getActiveAll();
        return view('tenant.tickets.index', $data);
    }

    public function search(Request $request)
    {
        $data['tickets'] = $this->ticketService->searchByUnitId($request, auth()->user()->tenant->unit_id);
        $view = view('tenant.tickets.single-view', $data)->render();
        return $this->success($view);
    }

    public function getInfo(Request $request)
    {
        $data = $this->ticketService->getById($request->id);
        return $this->success($data);
    }

    public function details($id)
    {
        $data['pageTitle'] = __('Ticket Details');
        $data['navTicketMMActiveClass'] = 'mm-active';
        $data['navTicketActiveClass'] = 'active';
        $data['ticket']  = $this->ticketService->getById($id);
        $data['replies'] = $this->ticketService->getRepliesByTicketId($id);

        return view('tenant.tickets.details', $data);
    }

    public function store(TicketRequest $request)
    {
        return $this->ticketService->store($request);
    }

    public function reply(TicketReplyRequest $request)
    {
        return $this->ticketService->reply($request);
    }

    public function statusChange(Request $request)
    {
        return $this->ticketService->statusChange($request);
    }

    public function delete($id)
    {
        return $this->ticketService->delete($id);
    }
}
