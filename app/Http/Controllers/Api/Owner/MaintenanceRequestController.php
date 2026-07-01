<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceRequest;
use App\Services\MaintenanceIssueService;
use App\Services\MaintenanceRequestService;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    use ResponseTrait;
    public $maintenanceRequestService;
    public $propertyService;
    public $maintenanceIssueService;

    public function __construct()
    {
        $this->maintenanceRequestService = new MaintenanceRequestService;
    }

    public function index()
    {
        $data['maintenanceRequests'] = $this->maintenanceRequestService->getAll();
        return $this->success($data);
    }

    public function store(MaintenanceRequest $request)
    {
        return $this->maintenanceRequestService->store($request);
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

    public function delete($id)
    {
        return $this->maintenanceRequestService->deleteById($id);
    }
}
