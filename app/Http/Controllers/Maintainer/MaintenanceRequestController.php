<?php

namespace App\Http\Controllers\Maintainer;

use App\Http\Controllers\Controller;
use App\Services\MaintenanceRequestService;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    use ResponseTrait;
    public $maintenanceRequestService,$propertyService;

    public function __construct()
    {
        $this->propertyService = new PropertyService;
        $this->maintenanceRequestService = new MaintenanceRequestService;
    }

    public function index()
    {
        $data['pageTitle'] = __('Maintenance Request');
        $data['requests'] = $this->maintenanceRequestService->getByPropertyId(auth()->user()->maintainer->properties->pluck('id')->toArray());;
        return view('maintainer.maintenance-request.index', $data);
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
    public function view($id)
    {
        $data['maintenanceRequest'] = $this->maintenanceRequestService->viewById($id);
        $data['properties'] = $this->propertyService->getPropertyId();
        $data['units'] = $this->propertyService->getUnitId();
        $data['issues'] = $this->propertyService->getAllIssue();
        return view('owner.maintains.view', $data);
    }
}
