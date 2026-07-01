<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Services\GatewayService;
use App\Services\InvoiceService;
use App\Services\TenantService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ResponseTrait;
    public $invoiceService;
    public $tenantService;
    public $gatewayService;

    public function __construct()
    {
        $this->invoiceService = new InvoiceService;
        $this->tenantService = new TenantService();
        $this->gatewayService = new GatewayService;
    }

    public function index()
    {
        $data['invoices'] = $this->invoiceService->getByTenantId(auth()->user()->tenant->id);
        return $this->success($data);
    }

    public function details($id)
    {
        $data['invoice'] = $this->invoiceService->getById($id);
        $data['items'] = $this->invoiceService->getItemsByInvoiceId($id);
        $data['tenant'] = $this->tenantService->getDetailsById($data['invoice']->tenant_id);
        $data['order'] = $this->invoiceService->getOrderById($data['invoice']->order_id);
        return $this->success($data);
    }

    public function pay($id)
    {
        $data['invoice'] = $this->invoiceService->getByIdCheckTenantAuthId($id);
        $data['gateways'] = $this->gatewayService->getActiveAllWithCurrencies(auth()->user()->owner_user_id);
        $data['banks'] = $this->gatewayService->getActiveBanks();
        return $this->success($data);
    }

    public function getCurrencyByGateway(Request $request)
    {
        $data = $this->invoiceService->getCurrencyByGatewayId($request->id);
        return $this->success($data);
    }
}
