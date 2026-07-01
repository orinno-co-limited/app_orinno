<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintainerRequest;
use App\Services\MaintainerService;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class MaintainerController extends Controller
{
    use ResponseTrait;
    public $maintainerService;
    public $propertyService;

    public function __construct()
    {
        $this->maintainerService = new MaintainerService;
        $this->propertyService = new PropertyService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Maintenance Persons');
        $data['properties'] = $this->propertyService->getAll();
        if ($request->ajax()) {
            return $this->maintainerService->getAllData();
        }
        return view('owner.maintains.maintainer', $data);
    }

    public function store(MaintainerRequest $request)
    {
        return $this->maintainerService->store($request);
    }

    public function getInfo(Request $request)
    {
        try {
            $data = $this->maintainerService->getInfo($request->id);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function delete($id)
    {
        return $this->maintainerService->deleteById($id);
    }
}
