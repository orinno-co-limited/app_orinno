<?php

namespace App\Http\Controllers\Api\Maintainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketReplyRequest;
use App\Services\PropertyService;
use App\Services\TicketService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use ResponseTrait;
    public $ticketService, $propertyService;

    public function __construct()
    {
        $this->ticketService = new TicketService;
        $this->propertyService = new PropertyService;
    }

    public function index()
    {
        $maintainer = auth()->user()->maintainer;
        $propertyIds = $this->propertyService->getPropertyIdsByMaintainerIds($maintainer->user_id);
        $data['tickets'] = $this->ticketService->getAllByPropertyId($propertyIds);
        return $this->success($data);
    }

    public function details($id)
    {
        $data['ticket']  = $this->ticketService->getById($id);
        $data['replies'] = $this->ticketService->getRepliesByTicketId($id);
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
