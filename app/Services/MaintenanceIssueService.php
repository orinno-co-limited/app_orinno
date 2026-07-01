<?php

namespace App\Services;

use App\Models\MaintenanceIssue;
use App\Models\Property;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class MaintenanceIssueService
{
    use ResponseTrait;

    public function getAll()
    {
        return MaintenanceIssue::where('owner_user_id', getOwnerUserId())->get();
    }

    public function getActiveAll()
    {
        $ownerUserId = auth()->user()->role == USER_ROLE_OWNER ? auth()->id() : auth()->user()->owner_user_id;
        return MaintenanceIssue::where('owner_user_id', $ownerUserId)->where('status', ACTIVE)->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            MaintenanceIssue::updateOrCreate([
                'id' => $request->id,
                'owner_user_id' => getOwnerUserId(),
            ], [
                'name' => $request->name,
                'owner_user_id' => getOwnerUserId(),
                'status' => $request->status,
            ]);
            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function getInfo($id)
    {
        return MaintenanceIssue::findOrFail($id);
    }

    public function delete($id)
    {
        $issue = MaintenanceIssue::where('owner_user_id', getOwnerUserId())->findOrFail($id);
        $issue->delete();
        return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
    }
    public function getPropertyId()
    {
        return Property::all();
    }
}
