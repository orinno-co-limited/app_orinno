<?php

namespace App\Http\Controllers\Api\Maintainer;

use App\Http\Controllers\Controller;
use App\Models\NoticeBoard;
use App\Models\Property;
use App\Models\Ticket;
use App\Traits\ResponseTrait;

class DashboardController extends Controller
{
    use ResponseTrait;
    public function dashboard()
    {
        $authUser = auth()->user();
        $data['totalOpenTickets'] = Ticket::query()->whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())->where('status', TICKET_STATUS_OPEN)->count();
        $data['totalResolvedTickets'] = Ticket::query()->whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())->where('status', TICKET_STATUS_RESOLVED)->count();
        $data['totalCloseTickets'] = Ticket::query()->whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())->where('status', TICKET_STATUS_CLOSE)->count();

        $data['properties'] = Property::query()
            ->where('owner_user_id', $authUser->owner_user_id)
            ->select(['name', 'property_type', 'number_of_unit', 'description', 'status', 'thumbnail_image'])
            ->get();
        $data['today'] = date('Y-m-d');
        $data['notices'] = NoticeBoard::query()
            ->whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())
            ->where('start_date', '<=', $data['today'])->where('end_date', '>=', $data['today'])
            ->orderByDesc('id')
            ->select(['title', 'details', 'start_date', 'end_date'])
            ->limit(10)
            ->get();
        $data['tickets'] = Ticket::query()
            ->whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())
            ->orderByDesc('id')
            ->limit(10)
            ->select(['title', 'details', 'status', 'ticket_no'])
            ->get();
        return $this->success($data);
    }
}
