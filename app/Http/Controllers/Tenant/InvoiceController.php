<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
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
        $data['pageTitle'] = __('Invoices');
        $data['invoices'] = $this->invoiceService->getByTenantId(auth()->user()->tenant->id);
        return view('tenant.invoices.index', $data);
    }

    public function details($id)
    {
        $data['invoice'] = $this->invoiceService->getById($id);
        $data['items'] = $this->invoiceService->getItemsByInvoiceId($id);
        $data['owner'] = $this->invoiceService->ownerInfo(auth()->user()->owner_user_id);
        $data['tenant'] = $this->tenantService->getDetailsById($data['invoice']->tenant_id);
        $data['order'] = $this->invoiceService->getOrderById($data['invoice']->order_id);
        return view('tenant.invoices.print', $data);
    }

    public function view($id)
    {
        $data['invoice'] = $this->invoiceService->getById($id);
        $data['items'] = $this->invoiceService->getItemsByInvoiceId($id);
        $data['owner'] = $this->invoiceService->ownerInfo(auth()->user()->owner_user_id);
        $data['tenant'] = $this->tenantService->getDetailsById($data['invoice']->tenant_id);
        $data['order'] = $this->invoiceService->getOrderById($data['invoice']->order_id);
        return view('tenant.invoices.view', $data);
    }

    public function pay($id)
    {
        $data['pageTitle'] = __('Invoices Pay');
        $data['navInvoiceMMActiveClass'] = 'mm-active';
        $data['navInvoiceActiveClass'] = 'active';
        $data['invoice'] = $this->invoiceService->getByIdCheckTenantAuthId($id);
        $data['gateways'] = $this->gatewayService->getActiveAll(auth()->user()->owner_user_id);
        $data['banks'] = $this->gatewayService->getActiveBanks();
        if ($data['invoice']->due_date < date('Y-m-d')) {
            $tenant = Tenant::findOrFail($data['invoice']->tenant_id);
            if ($tenant->late_fee_type == TYPE_PERCENTAGE) {
                $lateFeeAmount = $data['invoice']->amount * $tenant->late_fee * 0.01;
            } else {
                $lateFeeAmount =  $tenant->late_fee;
            }
            $data['invoice']->update(['late_fee' => $lateFeeAmount]);
        }
        return view('tenant.invoices.pay', $data);
    }

    public function getCurrencyByGateway(Request $request)
    {
        $data = $this->invoiceService->getCurrencyByGatewayId($request->id);
        return $this->success($data);
    }
}
