<?php

namespace App\Http\Controllers\Maintainer;

use App\Http\Controllers\Controller;
use App\Models\NoticeBoard;
use App\Models\Notification;
use App\Models\Property;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['pageTitle'] = __('Dashboard');
        $authUser = auth()->user();
        $data['properties'] = Property::where('owner_user_id', $authUser->owner_user_id)->get();
        $data['totalOpenTickets'] = Ticket::query()->whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())->where('status', TICKET_STATUS_OPEN)->count();
        $data['totalResolvedTickets'] = Ticket::query()->whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())->where('status', TICKET_STATUS_RESOLVED)->count();
        $data['totalCloseTickets'] = Ticket::query()->whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())->where('status', TICKET_STATUS_CLOSE)->count();
        $data['today'] = date('Y-m-d');
        $data['notices'] = NoticeBoard::whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())->where('start_date', '<=', $data['today'])->where('end_date', '>=', $data['today'])->limit(10)->get();
        $data['tickets'] = Ticket::whereIn('property_id', $authUser->maintainer->properties->pluck('id')->toArray())->limit(10)->get();
        return view('maintainer.dashboard')->with($data);
    }

    public function notification()
    {
        $data['pageTitle'] = __('Notification');
        Notification::query()
            ->where(function ($q) {
                $q->where('notifications.user_id', auth()->id())
                    ->orWhere('notifications.user_id', null);
            })
            ->update(['is_seen' => ACTIVE]);
        return view('maintainer.notification')->with($data);
    }
}
