<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycConfigRequest;
use App\Services\KycConfigService;
use App\Services\TenantService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class KycConfigController extends Controller
{
    use ResponseTrait;

    public $kycConfigService;
    public $tenantService;

    public function __construct()
    {
        $this->kycConfigService =   new KycConfigService;
        $this->tenantService = new TenantService;
    }
    public function index()
    {
        $data['pageTitle'] = __('Document Config');
        $data['subDocumentConfigActiveClass'] = 'active';
        $data['kycConfigs'] = $this->kycConfigService->getAll();
        $data['tenants'] = $this->tenantService->getActiveAll();
        return view('owner.setting.document-config', $data);
    }

    public function store(KycConfigRequest $request)
    {
        return $this->kycConfigService->store($request);
    }

    public function getInfo(Request $request)
    {
        $data = $this->kycConfigService->getInfo($request->id);
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->kycConfigService->delete($id);
    }
}
