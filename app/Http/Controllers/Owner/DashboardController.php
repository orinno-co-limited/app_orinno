<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Maintainer;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Services\OwnerService;
use App\Services\PropertyService;
use App\Services\TicketService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public $propertyService;
    public $ticketService;

    public function __construct()
    {
        $this->propertyService = new PropertyService;
        $this->ticketService = new TicketService;
    }

    public function dashboard()
    {
        $data['pageTitle'] = __('Dashboard');
        $data['totalProperties'] = Property::where('owner_user_id', getOwnerUserId())->count();
        $data['totalUnits'] = PropertyUnit::query()->join('properties', 'property_units.property_id', '=', 'properties.id')->where('properties.owner_user_id', getOwnerUserId())->count();
        $data['totalTenants'] = Tenant::where('owner_user_id', getOwnerUserId())->where('status', TENANT_STATUS_ACTIVE)->count();
        $data['properties'] = $this->propertyService->getAllCount()->take(3);
        $data['tickets'] = $this->ticketService->getAll();
        $data['totalMaintainers'] = Maintainer::where('owner_user_id', getOwnerUserId())->count();

        // Chart Rent overview
        $data['months'] = array_values(month());
        $invoices = Invoice::query()
            ->select(
                DB::raw('sum(amount) as `total`'),
                DB::raw("month"),
                DB::raw('max(created_at) as createdAt')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->where('owner_user_id', getOwnerUserId())
            ->where('status', INVOICE_STATUS_PAID)
            ->get();
        $data['yearlyTotalAmount'] = $invoices->sum('total');

        $invoiceMonthlyAmount = [];
        foreach ($data['months'] as $month) {
            $valueMonth = $invoices->where('month', $month)->first();
            if (!is_null($valueMonth)) {
                array_push($invoiceMonthlyAmount, $valueMonth->total);
            } else {
                array_push($invoiceMonthlyAmount, 0);
            }
        }
        $data['invoiceMonthlyAmount'] = $invoiceMonthlyAmount;
        return view('owner.dashboard')->with($data);
    }

    public function notification()
    {
        $data['pageTitle'] = __('Notification');
        Notification::query()
            ->where(function ($q) {
                $q->where('notifications.user_id', getOwnerUserId())
                    ->orWhere('notifications.user_id', null);
            })
            ->update(['is_seen' => ACTIVE]);
        return view('owner.notification')->with($data);
    }

    public function topSearch(Request $request)
    {
        $data['status'] = false;
        if ($request->keyword) {
            $ownerService = new OwnerService;
            $searchContent = $ownerService->topSearch($request);
            $data['data'] = view('owner.top-search-append', $searchContent)->render();
            $data['status'] = $searchContent['status'];
        }
        return response()->json($data);
    }
}
