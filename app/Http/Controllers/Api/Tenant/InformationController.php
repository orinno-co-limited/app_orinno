<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Services\InformationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    use ResponseTrait;
    public $informationService;

    public function __construct()
    {
        $this->informationService = new InformationService;
    }

    public function index()
    {
        $data['information'] = $this->informationService->getByPropertyId(auth()->user()->tenant->property_id);;
        return $this->success($data);
    }

    public function getInfo(Request $request)
    {
        $data = $this->informationService->getInfo($request->id);
        $data->image = $data->image;
        return $this->success($data);
    }
}
