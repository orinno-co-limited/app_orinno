<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintainerRequest;
use App\Services\MaintainerService;
use App\Traits\ResponseTrait;
use Exception;

class MaintainerController extends Controller
{
    use ResponseTrait;
    public $maintainerService;

    public function __construct()
    {
        $this->maintainerService = new MaintainerService;
    }

    public function index()
    {
        $data['maintainers'] = $this->maintainerService->allData();
        return $this->success($data);
    }

    public function store(MaintainerRequest $request)
    {
        return $this->maintainerService->store($request);
    }

    public function details($id)
    {
        try {
            $data = $this->maintainerService->details($id);
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
