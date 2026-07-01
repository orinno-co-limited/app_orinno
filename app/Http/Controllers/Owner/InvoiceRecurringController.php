<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRecurringRequest;
use App\Services\InvoiceRecurringService;
use App\Services\InvoiceTypeService;
use App\Services\PropertyService;
use App\Services\TenantService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class InvoiceRecurringController extends Controller
{
    use ResponseTrait;
    public $invoiceRecurringService;
    public $propertyService;
    public $invoiceTypeService;
    public $tenantService;

    public function __construct()
    {
        $this->invoiceRecurringService = new InvoiceRecurringService;
        $this->propertyService = new PropertyService;
        $this->invoiceTypeService = new InvoiceTypeService;
        $this->tenantService = new TenantService();
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Recurring Invoice Setting');
        if ($request->ajax()) {
            return $this->invoiceRecurringService->getAllData($request);
        } else {
            $data['properties'] = $this->propertyService->getAll();
            $data['invoiceTypes'] = $this->invoiceTypeService->getAll();
            return view('owner.invoice.recurring', $data);
        }
    }
    
    public function getTenantRent($unitId)
    {
        $tenant = $this->tenantService->getActiveTenantByUnitId($unitId);
        if (!$tenant) {
            return $this->error([], __('No active tenant found for this unit'));
        }
        return $this->success(['rent' => $tenant->general_rent]);
    }   

    public function store(InvoiceRecurringRequest $request)
    {
        return $this->invoiceRecurringService->store($request);
    }

    public function details($id)
    {
        $data['invoice'] = $this->invoiceRecurringService->getById($id);
        $data['items'] = $this->invoiceRecurringService->getItemsByInvoiceRecurringId($id);
        $data['tenant'] = $this->tenantService->getDetailsById($data['invoice']->tenant_id);
        return $this->success($data);
    }

    public function destroy($id)
    {
        return $this->invoiceRecurringService->destroy($id);
    }
}
