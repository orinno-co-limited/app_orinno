<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseTypeRequest;
use App\Services\ExpenseTypeService;

class ExpenseTypeController extends Controller
{
    public $expenseTypeService;

    public function __construct()
    {
        $this->expenseTypeService = new ExpenseTypeService;
    }

    public function index()
    {
        $data['pageTitle'] = __("Expense Type");
        $data['subExpenseTypeActiveClass'] = 'active';
        $data['expenseTypes'] = $this->expenseTypeService->getAll();
        return view('owner.setting.expense-type')->with($data);
    }

    public function store(ExpenseTypeRequest $request)
    {
        return $this->expenseTypeService->store($request);
    }

    public function destroy($id)
    {
        $this->expenseTypeService->delete($id);
        return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
    }
}
