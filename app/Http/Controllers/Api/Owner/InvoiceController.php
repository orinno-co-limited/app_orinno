<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\NotificationRequest;
use App\Http\Requests\PaymentStatusRequest;
use App\Services\InvoiceService;
use App\Services\TenantService;
use App\Traits\ResponseTrait;
use Exception;

class InvoiceController extends Controller
{
    use ResponseTrait;
    public $invoiceService, $tenantService;

    public function __construct()
    {
        $this->tenantService = new TenantService();
        $this->invoiceService = new InvoiceService();
    }

    public function index()
    {
        $data['invoices']  = $this->invoiceService->getAll();
        return $this->success($data);
    }

    public function store(InvoiceRequest $request)
    {
        return $this->invoiceService->store($request);
    }

    public function details($id)
    {
        $data['invoice'] = $this->invoiceService->getById($id);
        $data['items'] = $this->invoiceService->getItemsByInvoiceId($id);
        $data['tenant'] = $this->tenantService->getDetailsById($data['invoice']->tenant_id);
        $data['order'] = $this->invoiceService->getOrderById($data['invoice']->order_id);
        return $this->success($data);
    }

    public function types()
    {
        $data['types'] = $this->invoiceService->types();
        return $this->success($data);
    }

    public function destroy($id)
    {
        return $this->invoiceService->destroy($id);
    }

    public function paymentStatus(PaymentStatusRequest $request)
    {
        return $this->invoiceService->paymentStatusChange($request);
    }

    public function sendNotification(NotificationRequest $request)
    {
        try {
            if ($request->notification_type == NOTIFICATION_TYPE_SINGLE) {
                return $this->invoiceService->sendSingleNotification($request);
            } elseif ($request->notification_type == NOTIFICATION_TYPE_MULTIPLE) {
                return $this->invoiceService->sendMultiNotification($request);
            }
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
