<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Maintainer;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Services\PropertyService;
use App\Services\TicketService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ResponseTrait;
    public $propertyService;
    public $ticketService;

    public function __construct()
    {
        $this->propertyService = new PropertyService;
        $this->ticketService = new TicketService;
    }

    public function dashboard()
    {
        try {
            $data['totalProperties'] = Property::where('owner_user_id', auth()->id())->count();
            $data['totalUnits'] = PropertyUnit::query()->join('properties', 'property_units.property_id', '=', 'properties.id')->where('properties.owner_user_id', auth()->id())->count();
            $data['totalTenants'] = Tenant::where('owner_user_id', auth()->id())->count();
            $data['properties'] = $this->propertyService->getAllCount();
            $data['tickets'] = $this->ticketService->getAll();
            $data['totalMaintainers'] = Maintainer::where('owner_user_id', auth()->id())->count();

            // Chart Rent overview
            $data['months'] = array_values(month());
            $invoices = Invoice::query()
                ->select(
                    DB::raw('sum(amount) as `total`'),
                    DB::raw("DATE_FORMAT(created_at,'%M') as month"),
                    DB::raw('max(created_at) as createdAt')
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->where('owner_user_id', auth()->id())
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
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
