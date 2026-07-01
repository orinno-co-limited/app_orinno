<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Services\InvoiceTypeService;
use App\Services\TenantService;
use App\Traits\ResponseTrait;
use Exception;

class TenantController extends Controller
{
    use ResponseTrait;
    public $tenantService, $invoiceTypeService;

    public function __construct()
    {
        $this->tenantService = new TenantService;
        $this->invoiceTypeService = new InvoiceTypeService;
    }

    public function index()
    {
        $data['tenants'] = $this->tenantService->getAll();
        return $this->success($data);
    }

    public function details($id)
    {
        try {
            $data['tenant'] = $this->tenantService->getDetailsById($id);
            $data['payments'] = $this->tenantService->getPaymentByTenantId($id);
            $data['documents'] = $this->tenantService->getById($id);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
