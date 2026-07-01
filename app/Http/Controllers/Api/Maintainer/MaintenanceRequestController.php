<?php

namespace App\Http\Controllers\Api\Maintainer;

use App\Http\Controllers\Controller;
use App\Services\MaintenanceRequestService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    use ResponseTrait;
    public $maintenanceRequestService;

    public function __construct()
    {
        $this->maintenanceRequestService = new MaintenanceRequestService;
    }

    public function index()
    {
        $data['requests'] = $this->maintenanceRequestService->getByPropertyId(auth()->user()->maintainer->properties->pluck('id')->toArray());
        return $this->success($data);
    }

    public function getInfo(Request $request)
    {
        $data = $this->maintenanceRequestService->getInfo($request->id);
        return $this->success($data);
    }

    public function statusChange(Request $request)
    {
        return $this->maintenanceRequestService->statusChange($request);
    }
}
