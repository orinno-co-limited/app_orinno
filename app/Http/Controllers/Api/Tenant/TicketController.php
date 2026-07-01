<?php

namespace App\Http\Controllers\Api\Tenant;

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
        $data['tickets'] = $this->ticketService->getAllByUnitId(auth()->user()->tenant->unit_id);
        return $this->success($data);
    }

    public function search(Request $request)
    {
        $data['tickets'] = $this->ticketService->searchByUnitId($request, auth()->user()->tenant->unit_id);
        $view = view('tenant.tickets.single-view', $data)->render();
        return $this->success($view);
    }

    public function details($id)
    {
        $data['ticket']  = $this->ticketService->getById($id);
        $data['replies'] = $this->ticketService->getRepliesByTicketId($id);
        return $this->success($data);
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
        return $this->ticketService->deleteById($id);
    }

    public function topics()
    {
        return $data['topics'] = $this->ticketTopicService->getActiveAll();
    }
}
