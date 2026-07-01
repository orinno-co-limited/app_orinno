<?php

namespace App\Http\Controllers\Owner;

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
        $this->propertyService = new PropertyService;
        $this->maintenanceIssueService = new MaintenanceIssueService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Maintenance');
        $data['properties'] = $this->propertyService->getAll();
        $data['issues'] = $this->maintenanceIssueService->getActiveAll();
        if ($request->ajax()) {
            return $this->maintenanceRequestService->getAllData();
        }
        return view('owner.maintains.maintenance-request', $data);
    }

    public function store(MaintenanceRequest $request)
    {
        return $this->maintenanceRequestService->store($request);
    }

    public function getInfo(Request $request)
    {
        $data = $this->maintenanceRequestService->getInfo($request->id);
        $data->units = $this->propertyService->getUnitsByPropertyId($data->property_id)->getData()->data;
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

    public function view($id){

        $data['maintenanceRequest'] = $this->maintenanceRequestService->viewById($id);
        $data['properties'] = $this->propertyService->getAll();
        $data['units'] = $this->propertyService->getUnitId();
        $data['issues'] = $this->maintenanceIssueService->getActiveAll();

        return view('owner.maintains.view', $data);
    }
}
