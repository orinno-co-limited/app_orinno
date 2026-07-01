<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseTypeRequest;
use App\Http\Requests\Owner\ExpenseRequest;
use App\Services\ExpenseService;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    use ResponseTrait;
    public $expenseService;

    public function __construct()
    {
        $this->expenseService = new ExpenseService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->expenseService->getAllData($request);
        } else {
            $responseData  = $this->expenseService->getAll();
            return view('owner.expense.index')->with($responseData);
        }
    }

    public function details($id)
    {
        $data = $this->expenseService->getById($id);
        $propertyService  = new PropertyService; // PropertyService
        $data->units = $propertyService->getUnitsByPropertyId($data->property_id)->getData()->data;
        return $this->success($data);
    }

    public function store(ExpenseRequest $request)
    {



        return $this->expenseService->store($request);
    }

    public function destroy($id)
    {
        return $this->expenseService->destroy($id);
    }

    public function expenseTypeStore(ExpenseTypeRequest $request)
    {
        return $this->expenseService->expenseTypeStore($request);
    }
}
