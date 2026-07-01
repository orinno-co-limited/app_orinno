<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\NoticeBoard;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Ticket;
use App\Traits\ResponseTrait;

class DashboardController extends Controller
{
    use ResponseTrait;
    public function dashboard()
    {
        $tenantUser = auth()->user()->tenant;
        $data['property'] = Property::select(['name', 'thumbnail_image', 'description'])->findOrFail($tenantUser->property_id);
        $data['unit'] = PropertyUnit::select(['unit_name'])->findOrFail($tenantUser->unit_id);
        $data['tenant'] = $tenantUser->select(['general_rent']);
        $data['invoices'] = Invoice::select(['invoice_no', 'name', 'amount', 'due_date', 'created_at'])->where('tenant_id', $tenantUser->id)->get();
        $data['totalTickets'] = Ticket::query()->where('unit_id', $tenantUser->unit_id)->count();
        $data['today'] = date('Y-m-d');
        $data['notices'] = NoticeBoard::query()
            ->where(function ($q) use ($tenantUser) {
                $q->where('unit_id', $tenantUser->unit_id)
                    ->orWhere('unit_all', ACTIVE);
            })
            ->where('start_date', '<=', $data['today'])
            ->where('end_date', '>=', $data['today'])
            ->orderByDesc('id')
            ->select(['title', 'details', 'start_date', 'end_date'])
            ->get();
        return $this->success($data);
    }
}
