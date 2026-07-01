<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Owner;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class OwnerService
{
    use ResponseTrait;
    public function getAllData($request)
    {
        $owners = Owner::query()
            ->join('users', 'owners.user_id', '=', 'users.id')
            ->leftJoin('domains', 'owners.user_id', '=', 'domains.owner_user_id')
            ->whereNull('users.deleted_at')
            ->select('users.*')
            ->orderBy('owners.id', 'desc');

        return datatables($owners)
            ->addIndexColumn()
            ->addColumn('name', function ($owner) {
                return $owner->first_name . ' ' . $owner->last_name;
            })
            ->addColumn('email', function ($owner) {
                return $owner->email;
            })
            ->addColumn('contact_number', function ($owner) {
                return $owner->contact_number;
            })
            ->addColumn('domain', function ($owner) {
                if ($owner->domain) {
                    return $owner->domain;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('status', function ($owner){
                if ($owner->status == USER_STATUS_ACTIVE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">Active</div>';
                } else if ($owner->status == USER_STATUS_INACTIVE){
                    return '<div class="status-btn status-btn-purple font-13 radius-4">Inactive</div>';
                } else if ($owner->status == USER_STATUS_DELETED){
                    return '<div class="status-btn status-btn-red font-13 radius-4">Deleted</div>';
                } else if ($owner->status == USER_STATUS_UNVERIFIED){
                    return '<div class="status-btn status-btn-orange font-13 radius-4">Unverified</div>';
                }
            })
            ->addColumn('action', function ($owner) {
                return '<div class="tbl-action-btns d-inline-flex">
                            <button type="button" class="p-1 tbl-action-btn edit" data-id="' . $owner->id . '" title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>
                            <button onclick="deleteItem(\'' . route('admin.owner.delete', $owner->id) . '\', \'allOwnerDataTable\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>
                        </div>';
            })
            ->rawColumns(['name', 'status', 'trail', 'action'])
            ->make(true);
    }

    public function getAll()
    {
        $owners = Owner::query()
            ->join('users', 'owners.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->select('users.*')
            ->orderBy('owners.id', 'desc')
            ->get();
        return $owners->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function topSearch($request)
    {
        $data['status'] = false;
        $data['tenants'] =  Tenant::query()
            ->where('tenants.owner_user_id', getOwnerUserId())
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->where(DB::raw("concat(users.first_name, ' ', users.last_name)"), 'LIKE', "%" . $request->keyword . "%")
            ->select(DB::raw("tenants.id"), DB::raw("concat(users.first_name, ' ', users.last_name) as name"))
            ->get();

        $data['properties'] =  Property::query()
            ->where('owner_user_id', getOwnerUserId())
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->select('id', 'name')
            ->get();

        $data['invoices'] =  Invoice::query()
            ->where('owner_user_id', getOwnerUserId())
            ->where('invoice_no', 'LIKE', '%' . $request->keyword . '%')
            ->select('id', 'invoice_no')
            ->get();

        if (count($data['tenants']) > 0 || count($data['properties']) > 0 || count($data['invoices']) > 0) {
            $data['status'] = true;
        }
        return $data;
    }

    public function delete($id)
    {
        try {
            $user = User::query()
                ->where('role', USER_ROLE_OWNER)
                ->find($id);
            if (is_null($user)) {
                throw new Exception(__('No Data Found'));
            } else {
                $user->delete();
            }
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}
