<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MonitoringService;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public $monitoringService;

    public function __construct()
    {
        $this->monitoringService = new MonitoringService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Monitoring');
        return view('admin.monitoring.index', $data);
    }

    public function landlordTenants(Request $request)
    {
        return $this->monitoringService->getLandlordTenantCounts($request);
    }

    public function allTenants(Request $request)
    {
        return $this->monitoringService->getAllTenants($request);
    }
}
