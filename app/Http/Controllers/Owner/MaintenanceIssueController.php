<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceIssueRequest;
use App\Services\MaintenanceIssueService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class MaintenanceIssueController extends Controller
{
    use ResponseTrait;
    public $maintenanceIssueService;

    public function __construct()
    {
        $this->maintenanceIssueService = new MaintenanceIssueService;
    }

    public function index()
    {
        $data['pageTitle'] = __('Maintenance Issue');
        $data['subMaintenanceIssueActiveClass'] = 'active';
        $data['maintenanceIssues'] = $this->maintenanceIssueService->getAll();
        return view('owner.setting.maintenance-issue', $data);
    }

    public function store(MaintenanceIssueRequest $request)
    {
        return $this->maintenanceIssueService->store($request);
    }

    public function getInfo(Request $request)
    {
        $data = $this->maintenanceIssueService->getInfo($request->id);
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->maintenanceIssueService->delete($id);
    }
}
