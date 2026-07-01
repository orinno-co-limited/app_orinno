<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketTopicRequest;
use App\Services\TicketTopicService;

class TicketTopicController extends Controller
{
    public $ticketTopicService;

    public function __construct()
    {
        $this->ticketTopicService = new TicketTopicService;
    }

    public function index()
    {
        $data['pageTitle'] = __("Ticket Topic");
        $data['subTicketTopicActiveClass'] = 'active';
        $data['ticketTopics'] = $this->ticketTopicService->getAll();
        return view('owner.setting.ticket-topic')->with($data);
    }

    public function store(TicketTopicRequest $request)
    {
        return $this->ticketTopicService->store($request);
    }

    public function destroy($id)
    {
        return $this->ticketTopicService->delete($id);
    }
}
