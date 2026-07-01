<?php

namespace App\Services;

use App\Models\InvoiceType;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class InvoiceTypeService
{
    use ResponseTrait;

    public function getAll()
    {
        return InvoiceType::where('owner_user_id', getOwnerUserId())->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $invoiceType = InvoiceType::findOrFail($request->id);
            } else {
                $invoiceType = new InvoiceType();
            }
            $invoiceType->name = $request->name;
            $invoiceType->tax = $request->tax;
            $invoiceType->owner_user_id = getOwnerUserId();
            $invoiceType->status = $request->status ?? ACTIVE;
            $invoiceType->save();
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
            $invoiceType = InvoiceType::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $invoiceType->delete();
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
