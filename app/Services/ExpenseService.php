<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\FileManager;
use App\Models\Property;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    use ResponseTrait;

    public function getAll()
    {
        $response['pageTitle'] = __('All Expenses');
        $response['expenses'] = Expense::where('owner_user_id', getOwnerUserId())->get();
        $response['properties'] = Property::where('owner_user_id', getOwnerUserId())->get();
        $response['expenseTypes'] = ExpenseType::where('owner_user_id', getOwnerUserId())->active()->get();
        return $response;
    }

    public function getAllExpenses()
    {
        $data = Expense::where('owner_user_id', getOwnerUserId())->get();
        return $data?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getById($id)
    {
        return Expense::where('owner_user_id', getOwnerUserId())->findOrFail($id);
    }

    public function getAllData($request)
    {
        $expense = Expense::query()->where('owner_user_id', getOwnerUserId());

        return datatables($expense)
            ->addColumn('property', function ($expense) {
                return '<h6>' . @$expense->property->name . '</h6><p class="font-13">' . @$expense->propertyUnit->unit_name . '</p>';
            })
            ->addColumn('expense_type_name', function ($expense) {
                return '<div class="status-btn status-btn-blue font-13 radius-4">' . @$expense->expenseType->name . '</div>';
            })
            ->addColumn('responsibility', function ($expense) {
                if ($expense->responsibilities->tenant && $expense->responsibilities->owner) {
                    $responsibility = 'Tenant, Property Owner';
                } elseif ($expense->responsibilities->tenant) {
                    $responsibility = 'Tenant';
                } else {
                    $responsibility = 'Property Owner';
                }
                return $responsibility;
            })
            ->addColumn('total_amount', function ($expense) {
                return currencyPrice($expense->total_amount);
            })
            ->addColumn('action', function ($expense) {
                return '<div class="tbl-action-btns d-inline-flex">
                            <button type="button" class="p-1 tbl-action-btn edit" data-detailsurl="' . route('owner.expense.details', $expense->id) . '" title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>
                            <button onclick="deleteItem(\'' . route('owner.expense.destroy', $expense->id) . '\', \'expensesDatatable\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>
                        </div>';
            })
            ->rawColumns(['property', 'expense_type_name', 'action'])->make(true);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $expense = Expense::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
            } else {
                $expense = new Expense();
            }
            $expense->name = $request->name;
            $expense->property_id = $request->property_id;
            $expense->owner_user_id = getOwnerUserId();
            $expense->property_unit_id = $request->property_unit_id;
            $expense->expense_type_id = $request->expense_type_id;
            $expense->description = $request->description;
            $expense->total_amount = $request->total_amount;
            $responsibilities = [
                "tenant" => @$request->responsibilities[0],
                "owner" => @$request->responsibilities[1],
            ];
            $expense->responsibilities = $responsibilities;
            $expense->save();

            /*File Manager Call upload*/
            if ($request->file('file')) {

                $new_file = FileManager::where('origin_type', 'App\Models\Expense')->where('origin_id', $expense->id)->first();
                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'Expense', $request->file);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('Expense', $request->file);
                }

                if ($upload['status']) {
                    $upload['file']->origin_id = $expense->id;
                    $upload['file']->origin_type = "App\Models\Expense";
                    $upload['file']->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }
            /*End*/
            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $expense = Expense::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $expense->delete();
            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function expenseTypeStore($request)
    {
        DB::beginTransaction();
        try {
            $expenseType = new ExpenseType();
            $expenseType->name = $request->type_name;
            $expenseType->owner_user_id = getOwnerUserId();
            $expenseType->status = ACTIVE;
            $expenseType->save();

            DB::commit();
            $message = __(CREATED_SUCCESSFULLY);
            $data['types'] = ExpenseType::where('owner_user_id', getOwnerUserId())->active()->get();
            return $this->success($data, $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
