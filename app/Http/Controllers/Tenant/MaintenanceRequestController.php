<?php

namespace App\Http\Controllers\Tenant;

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
    public $maintenanceRequestService, $maintenanceIssueService,$propertyService;

    public function __construct()
    {
        $this->propertyService = new PropertyService;
        $this->maintenanceRequestService = new MaintenanceRequestService;
        $this->maintenanceIssueService = new MaintenanceIssueService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Maintenance');
        $data['issues'] = $this->maintenanceIssueService->getActiveAll();
        $data['tenant'] = auth()->user()->tenant;
        if ($request->ajax()) {
            return $this->maintenanceRequestService->getAllDataByTenant();
        }
        return view('tenant.maintenance-request', $data);
    }

    public function store(MaintenanceRequest $request)
    {
        return $this->maintenanceRequestService->store($request);
    }

    public function getInfo(Request $request)
    {
        $data = $this->maintenanceRequestService->getById($request->id);
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->maintenanceRequestService->deleteById($id);
    }

    public function view($id)
    {
        $data['maintenanceRequest'] = $this->maintenanceRequestService->viewById($id);
        $data['properties'] = $this->propertyService->getAll();
        $data['units'] = $this->propertyService->getUnitId();
        $data['issues'] = $this->maintenanceIssueService->getActiveAll();

        return view('owner.maintains.view', $data);
    }
}
