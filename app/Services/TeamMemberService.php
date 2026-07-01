<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TeamMemberService
{
    use ResponseTrait;

    public function getRoles(){

        return Role::where('user_id', getOwnerUserId())->where('status',ACTIVE)->get();
    }

    public function getInfo()
    {
        $teamMembers = User::where('role',USER_ROLE_TEAM_MEMBER)->where('owner_user_id',getOwnerUserId())->orderBy('id','DESC')->get();

        return datatables($teamMembers)
            ->addIndexColumn()
            ->editColumn('name', function ($maintainer) {
                return $maintainer->first_name . ' ' . $maintainer->last_name;
            })
            ->addColumn('status', function ($teamMember) {
                if ($teamMember->status == USER_STATUS_ACTIVE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">Active</div>';
                } else {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">Deactivate</div>';
                }
            })
            ->addColumn('action', function ($teamMember) {
                $id = $teamMember->id;
                return '<div class="tbl-action-btns d-inline-flex">
                            <button type="button" onclick="getEditModal(\'' . route('owner.team-member.edit', $id) . '\', \'#editTeamMemberModal\')" class="p-1 tbl-action-btn"  title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>
                            <button onclick="deleteItem(\'' . route('owner.team-member.delete', $id) . '\', \'teamMemberDataTable\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>
                        </div>';
            })
            ->rawColumns(['status', 'action','name'])
            ->make(true);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $user = User::find($request->id);
            } else {
                $user = new User();
            }
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->role = USER_ROLE_TEAM_MEMBER;
            $user->owner_user_id = getOwnerUserId();
            $user->status = $request->status;
            $user->save();
            $user->syncRoles($request->role);

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function delete($id)
    {
        try {
            $role = User::find($id);
            $role->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getEditInfo($id){

        return User::where('role',USER_ROLE_TEAM_MEMBER)->findOrFail($id);
    }

}
