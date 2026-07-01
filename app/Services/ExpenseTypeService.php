<?php

namespace App\Services;

use App\Models\ExpenseType;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class ExpenseTypeService
{
    use ResponseTrait;

    public function getAll()
    {
        return ExpenseType::where('owner_user_id', getOwnerUserId())->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $expenseType = ExpenseType::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
            } else {
                $expenseType = new ExpenseType();
            }
            $expenseType->name = $request->type_name;
            $expenseType->owner_user_id = getOwnerUserId();
            $expenseType->tax = $request->tax ?? 0;
            $expenseType->status = $request->status ?? ACTIVE;
            $expenseType->save();
            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $expenseType = ExpenseType::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $expenseType->delete();
            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
