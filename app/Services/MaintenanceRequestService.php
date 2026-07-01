<?php

namespace App\Services;

use App\Models\FileManager;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaintenanceRequestService
{
    use ResponseTrait;

    public function getAll()
    {
        $maintenance = MaintenanceRequest::query()
            ->join('properties', 'maintenance_requests.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'maintenance_requests.unit_id', '=', 'property_units.id')
            ->join('maintenance_issues', 'maintenance_requests.issue_id', '=', 'maintenance_issues.id')
            ->where('maintenance_requests.owner_user_id', getOwnerUserId())
            ->select('maintenance_requests.id', 'maintenance_requests.details', 'maintenance_requests.status', 'properties.name as property_name', 'maintenance_issues.name as issue_name', 'property_units.unit_name')
            ->get();
        return $maintenance;
    }

    public function getAllData()
    {
        $maintenance = MaintenanceRequest::query()
            ->join('properties', 'maintenance_requests.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'maintenance_requests.unit_id', '=', 'property_units.id')
            ->join('maintenance_issues', 'maintenance_requests.issue_id', '=', 'maintenance_issues.id')
            ->where('maintenance_requests.owner_user_id', getOwnerUserId())
            ->select('maintenance_requests.*', 'properties.name as property_name', 'maintenance_issues.name as issue_name', 'property_units.unit_name');
        return datatables($maintenance)
            ->addIndexColumn()
            ->addColumn('details', function ($maintenance) {
                return Str::limit($maintenance->details, 50, '...');
            })
            ->addColumn('status', function ($maintenance) {
                if ($maintenance->status == MAINTENANCE_REQUEST_STATUS_COMPLETE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">Completed</div>';
                } elseif ($maintenance->status == MAINTENANCE_REQUEST_STATUS_INPROGRESS) {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">In Progress</div>';
                } else {
                    return '<div class="status-btn status-btn-red font-13 radius-4">Pending</div>';
                }
            })
            ->addColumn('action', function ($maintenance) {
                $id = $maintenance->id;
                return '<div class="tbl-action-btns d-inline-flex">
                            <button type="button" onclick="getEditModal(\'' . route('owner.maintenance-request.view', $id) . '\', \'#viewModal\')" class="p-1 tbl-action-btn reminder" title="' . __('View') . '">
                                <span class="iconify" data-icon="carbon:view-filled"></span>
                            </button>
                            <button type="button" class="p-1 tbl-action-btn edit" data-id="' . $id . '" title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>
                            <button onclick="deleteItem(\'' . route('owner.maintenance-request.delete', $id) . '\', \'allDataTableDoc\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>
                        </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getAllDataByTenant()
    {
        $maintenance = MaintenanceRequest::query()
            ->join('properties', 'maintenance_requests.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'maintenance_requests.unit_id', '=', 'property_units.id')
            ->join('maintenance_issues', 'maintenance_requests.issue_id', '=', 'maintenance_issues.id')
            ->where('maintenance_requests.property_id', auth()->user()->tenant->property_id)
            ->where('maintenance_requests.unit_id', auth()->user()->tenant->unit_id)
            ->select('maintenance_requests.*', 'properties.name as property_name', 'maintenance_issues.name as issue_name', 'property_units.unit_name');

        return datatables($maintenance)
            ->addIndexColumn()
            ->addColumn('details', function ($maintenance) {
                return Str::limit($maintenance->details, 50, '...');
            })
            ->addColumn('status', function ($maintenance) {
                if ($maintenance->status == MAINTENANCE_REQUEST_STATUS_COMPLETE) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">Completed</div>';
                } elseif ($maintenance->status == MAINTENANCE_REQUEST_STATUS_INPROGRESS) {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">In Progress</div>';
                } else {
                    return '<div class="status-btn status-btn-red font-13 radius-4">Pending</div>';
                }
            })
            ->addColumn('action', function ($maintenance) {
                $id = $maintenance->id;
                $button = '<div class="tbl-action-btns d-inline-flex">';
                if ($maintenance->status == MAINTENANCE_REQUEST_STATUS_PENDING) {
                    $button .= '<button type="button" onclick="getEditModal(\'' . route('tenant.maintenance-request.view', $id) . '\', \'#viewModal\')" class="p-1 tbl-action-btn reminder" title="' . __('View') . '">
                                <span class="iconify" data-icon="carbon:view-filled"></span>
                            </button>';
                    $button .= ' <button type="button" class="p-1 tbl-action-btn edit" data-id="' . $id . '" title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>';
                    $button .= '<button onclick="deleteItem(\'' . route('tenant.maintenance-request.delete', $id) . '\', \'allDataTableDoc\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>';
                }
                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getById($id)
    {
        if (auth()->user()->role == USER_ROLE_OWNER) {
            $userId = auth()->id();
        } else {
            $userId = auth()->user()->owner_user_id;
        }
        $maintenance = MaintenanceRequest::query()
            ->where('owner_user_id', $userId)
            ->findOrFail($id);
        return $maintenance?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getByPropertyId($id)
    {
        $maintenance = MaintenanceRequest::query()
            ->join('properties', 'maintenance_requests.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'maintenance_requests.unit_id', '=', 'property_units.id')
            ->join('maintenance_issues', 'maintenance_requests.issue_id', '=', 'maintenance_issues.id')
            ->select('maintenance_requests.*', 'properties.name as property_name', 'maintenance_issues.name as issue_name', 'property_units.unit_name')
            ->whereIn('maintenance_requests.property_id', $id)
            ->get();
        return $maintenance?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }


    public function store($request)
    {
        DB::beginTransaction();
        try {
            $authUser = auth()->user();
            if (($authUser->role == USER_ROLE_OWNER) || ($authUser->role == USER_ROLE_TEAM_MEMBER)) {
                $userId = getOwnerUserId();
            } else {
                $userId = $authUser->owner_user_id;
                if ($authUser->tenant->property_id != $request->property_id || $authUser->tenant->unit_id != $request->unit_id) {
                    throw new Exception(__(SOMETHING_WENT_WRONG));
                } else {
                    $request->merge(['status' => MAINTENANCE_REQUEST_STATUS_PENDING]);
                }
            }

            $id = $request->get('id', '');
            if ($id != '') {
                $maintenance = MaintenanceRequest::where('owner_user_id', $userId)->findOrFail($request->id);
            } else {
                $maintenance = new MaintenanceRequest();
            }

            // maintenance
            $maintenance->property_id = $request->property_id;
            $maintenance->owner_user_id = $userId;
            $maintenance->unit_id = $request->unit_id;
            $maintenance->issue_id = $request->issue_id;
            $maintenance->details = $request->details;
            $maintenance->created_date = $request->created_date ?? now();
            $maintenance->status = in_array($request->status, [MAINTENANCE_REQUEST_STATUS_COMPLETE, MAINTENANCE_REQUEST_STATUS_INPROGRESS, MAINTENANCE_REQUEST_STATUS_PENDING]) ? $request->status : MAINTENANCE_REQUEST_STATUS_PENDING;
            $maintenance->save();

            /*File Manager Call upload*/
            if ($request->hasFile('attach')) {
                $exitFile = FileManager::where('origin_type', 'App\Models\MaintenanceRequest')->where('id', $maintenance->attach_id)->first();
                if ($exitFile) {
                    $exitFile->removeFile();
                    $upload = $exitFile->updateUpload($exitFile->id, 'MaintenanceRequest', $request->attach);
                } else {
                    $newFile = new FileManager();
                    $upload = $newFile->upload('MaintenanceRequest', $request->attach);
                }

                if ($upload['status']) {
                    $maintenance->attach_id = $upload['file']->id;
                    $maintenance->save();
                    $upload['file']->origin_type = "App\Models\MaintenanceRequest";
                    $upload['file']->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }
            /*End*/
            $property = Property::find($request->property_id);
            if (isset($property)) {
                addNotification(__('New maintenance Request'), $request->details, null, null, $property->maintainer_id, getOwnerUserId());
            }

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getInfo($id)
    {
        $maintenance = MaintenanceRequest::query()
            ->join('properties', 'maintenance_requests.property_id', '=', 'properties.id')
            ->leftJoin('property_units', 'maintenance_requests.unit_id', '=', 'property_units.id')
            ->join('maintenance_issues', 'maintenance_requests.issue_id', '=', 'maintenance_issues.id')
            ->select('maintenance_requests.*', 'properties.name as property_name', 'maintenance_issues.name as issue_name', 'property_units.unit_name')
            ->where('maintenance_requests.id', $id)
            ->firstOrFail();

        $maintenance->attach = $maintenance->attach;
        $maintenance->invoice = $maintenance->invoice;
        return $maintenance?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function statusChange($request)
    {
        DB::beginTransaction();
        try {
            $ownerUserId = auth()->user()->role == USER_ROLE_OWNER ? auth()->id() : auth()->user()->owner_user_id;
            $maintenance = MaintenanceRequest::where('owner_user_id', $ownerUserId)->findOrFail($request->id);
            $maintenance->status = in_array($request->status, [MAINTENANCE_REQUEST_STATUS_COMPLETE, MAINTENANCE_REQUEST_STATUS_INPROGRESS, MAINTENANCE_REQUEST_STATUS_PENDING]) ? $request->status : MAINTENANCE_REQUEST_STATUS_PENDING;
            $maintenance->amount = $request->amount;
            $maintenance->resolved_date = $request->resolved_date;
            $maintenance->save();

            /*File Manager Call upload*/
            if ($request->hasFile('invoice')) {
                $exitFile = FileManager::where('origin_type', 'App\Models\MaintenanceRequest')->where('id', $maintenance->invoice_id)->first();
                if ($exitFile) {
                    $exitFile->removeFile();
                    $upload = $exitFile->updateUpload($exitFile->id, 'MaintenanceRequest', $request->invoice);
                } else {
                    $newFile = new FileManager();
                    $upload = $newFile->upload('MaintenanceRequest', $request->invoice);
                }

                if ($upload['status']) {
                    $maintenance->invoice_id = $upload['file']->id;
                    $maintenance->save();
                    $upload['file']->origin_type = "App\Models\MaintenanceRequest";
                    $upload['file']->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }
            /*End*/
            DB::commit();
            $message = __(STATUS_UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function deleteById($id)
    {
        DB::beginTransaction();
        try {
            $information = MaintenanceRequest::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $information->delete();
            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function viewById($id)
    {
        return MaintenanceRequest::leftJoin('users', 'users.id', '=', 'maintenance_requests.created_by')
            ->select('maintenance_requests.*', 'users.first_name','users.last_name','users.role')
            ->where('maintenance_requests.id', $id)
            ->firstOrFail();
    }

}
