<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\InformationRequest;
use App\Services\InformationService;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    use ResponseTrait;
    public $informationService;
    public $propertyService;

    public function __construct()
    {
        $this->informationService = new InformationService;
        $this->propertyService = new PropertyService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Information');
        $data['properties'] = $this->propertyService->getAll();;
        if ($request->ajax()) {
            return $this->informationService->getAllData();
        }
        return view('owner.information.index', $data);
    }

    public function store(InformationRequest $request)
    {
        return $this->informationService->store($request);
    }

    public function getInfo(Request $request)
    {
        $data = $this->informationService->getInfo($request->id);
        $data->image = $data->image;
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->informationService->deleteById($id);
    }
}
