<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketReplyRequest;
use App\Services\TicketService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use ResponseTrait;
    public $ticketService;

    public function __construct()
    {
        $this->ticketService = new TicketService;
    }

    public function index(Request $request)
    {
        if (isAddonInstalled('PROTYSAAS') > 1) {
            if (ownerCurrentPackage(auth()->id())?->ticket_support != ACTIVE) {
                return abort(403);
            }
        }
        $data['tickets'] = $this->ticketService->getAll();
        return $this->success($data);
    }

    public function details($id)
    {
        $data  = $this->ticketService->getById($id);
        return $this->success($data);
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
