<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\ExpenseRequest;
use App\Services\ExpenseService;
use App\Traits\ResponseTrait;

class ExpenseController extends Controller
{
    use ResponseTrait;
    public $expenseService;

    public function __construct()
    {
        $this->expenseService = new ExpenseService;
    }

    public function index()
    {
        $data = $this->expenseService->getAllExpenses();
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
}
