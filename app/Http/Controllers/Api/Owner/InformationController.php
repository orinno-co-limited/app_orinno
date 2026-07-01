<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\InformationRequest;
use App\Services\InformationService;
use App\Traits\ResponseTrait;

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
        $data['information'] = $this->informationService->getAll();
        return $this->success($data);
    }

    public function store(InformationRequest $request)
    {
        return $this->informationService->store($request);
    }

    public function getInfo($id)
    {
        $data = $this->informationService->getInfo($id);
        $data->image = $data->image;
        return $this->success($data);
    }

    public function delete($id)
    {
        return $this->informationService->deleteById($id);
    }
}
