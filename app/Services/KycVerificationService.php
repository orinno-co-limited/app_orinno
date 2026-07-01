<?php

namespace App\Services;

use App\Models\FileManager;
use App\Models\KycConfig;
use App\Models\KycVerification;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KycVerificationService
{
    use ResponseTrait;
    public function getAll()
    {
        $kycVerifications = KycVerification::query()
            ->with(['fileAttachFront', 'fileAttachBack'])
            ->join('tenants', 'kyc_verifications.tenant_id', '=', 'tenants.id')
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->join('kyc_configs', 'kyc_verifications.kyc_config_id', '=', 'kyc_configs.id')
            ->join('properties', 'tenants.property_id', '=', 'properties.id')
            ->join('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->where('users.status', '!=', USER_STATUS_DELETED)
            ->where('kyc_verifications.owner_user_id', getOwnerUserId())
            ->select('kyc_verifications.*', 'kyc_configs.name as config_name', 'users.first_name', 'users.last_name', 'properties.name as property_name', 'property_units.unit_name')
            ->get();

        foreach ($kycVerifications as $kycVerification) {
            $kycVerification->front_file = getFileUrl($kycVerification->fileAttachFront?->folder_name, $kycVerification->fileAttachFront?->file_name);
            $kycVerification->back_file = getFileUrl($kycVerification->fileAttachBack?->folder_name, $kycVerification->fileAttachBack?->file_name);
        }

        return $kycVerifications?->makeHidden(['front_id', 'back_id', 'created_at', 'updated_at', 'deleted_at']);
    }

    public function getAllData()
    {
        $property_id = request()->property_id;
        $unit_id = request()->unit_id;
        $kycVerifications = KycVerification::query()
            ->join('tenants', 'kyc_verifications.tenant_id', '=', 'tenants.id')
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->join('kyc_configs', 'kyc_verifications.kyc_config_id', '=', 'kyc_configs.id')
            ->join('properties', 'tenants.property_id', '=', 'properties.id')
            ->join('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->where('users.status', '!=', USER_STATUS_DELETED)
            ->whereNull('users.deleted_at')
            ->when($property_id != 0, function ($q) use ($property_id) {
                $q->where('properties.id', $property_id);
            })
            ->when($unit_id != 0, function ($q) use ($unit_id) {
                $q->where('property_units.id', $unit_id);
            })
            ->where('kyc_verifications.owner_user_id', getOwnerUserId())
            ->select('kyc_verifications.*', 'kyc_configs.name as config_name', 'users.first_name', 'users.last_name', 'properties.name as property_name', 'property_units.unit_name');

        return datatables($kycVerifications)
            ->addIndexColumn()
            ->addColumn('front', function ($data) {
                $html = '<div class="tenants-tbl-info-object d-flex align-items-center" title="Download">
                        <div class="flex-shrink-0">
                            <a href="' . $data->front . '" download>';
                if (pathinfo($data->front, PATHINFO_EXTENSION) == 'pdf') {
                    $html .= '<img src="' . asset('assets/images/pdf-file-img.png') . '">';
                } else {
                    $html .= '<img class="rounded avatar-md tbl-user-image" src="' . $data->front . '">';
                }
                $html .= '</a>
                        </div>
                    </div>';
                return $html;
            })
            ->addColumn('back', function ($data) {
                $html = '<div class="tenants-tbl-info-object d-flex align-items-center" title="Download">
                        <div class="flex-shrink-0">
                            <a href="' . $data->back . '" download>';
                if (pathinfo($data->back, PATHINFO_EXTENSION) == 'pdf') {
                    $html .= '<img src="' . asset('assets/images/pdf-file-img.png') . '">';
                } else {
                    $html .= '  <img class="rounded avatar-md tbl-user-image" src="' . $data->back . '">';
                }
                $html .= '</a>
                        </div>
                    </div>';
                return $html;
            })
            ->addColumn('config_name', function ($data) {
                return $data->config_name;
            })
            ->addColumn('tenant_name', function ($data) {
                return $data->first_name . ' ' . $data->last_name;
            })
            ->addColumn('status', function ($data) {
                $html = '';
                if ($data->status == KYC_STATUS_ACCEPTED) {
                    $html = ' <div class="status-btn status-btn-green font-13 radius-4">Accepted</div>';
                } elseif ($data->status == KYC_STATUS_PENDING) {
                    $html = ' <div class="status-btn status-btn-orange font-13 radius-4">Pending</div>';
                } elseif ($data->status == KYC_STATUS_REJECTED) {
                    $html = ' <div class="status-btn status-btn-blue font-13 radius-4">Rejected</div>';
                }
                return $html;
            })
            ->addColumn('action', function ($data) {
                return '<div class="tbl-action-btns d-inline-flex">
                        <button type="button" data-id="' . $data->id . '" class="p-1 tbl-action-btn view" title="' . __('View') . '"><span class="iconify" data-icon="carbon:view-filled"></span></button>
                        <button type="button" data-url="' . route('owner.documents.status', $data->id) . '" class="p-1 tbl-action-btn accept" title="' . __('Accept') . '"><span class="iconify" data-icon="material-symbols:check-box-outline"></span></button>
                        <button type="button" data-id="' . $data->id . '" class="p-1 tbl-action-btn reject" title="' . __('Reject') . '"><span class="iconify" data-icon="charm:circle-cross"></span></button>
                        <button onclick="deleteItem(\'' . route('owner.documents.delete', $data->id) . '\', \'allDataTableDoc\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>
                    </div>';
            })
            ->rawColumns(['front', 'back', 'status', 'action'])
            ->make(true);
    }

    public function getAllByTenantId($id)
    {
        $kycVerifications = KycVerification::where('kyc_verifications.tenant_id', $id)
            ->join('kyc_configs', 'kyc_verifications.kyc_config_id', '=', 'kyc_configs.id')
            ->join('tenants', 'kyc_verifications.tenant_id', '=', 'tenants.id')
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->join('properties', 'tenants.property_id', '=', 'properties.id')
            ->join('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->whereNull('users.deleted_at')
            ->select('kyc_verifications.*', 'kyc_configs.name as config_name', 'kyc_configs.is_both', 'users.first_name', 'users.last_name', 'properties.name as property_name', 'property_units.unit_name')
            ->get();
        foreach ($kycVerifications as $kycVerification) {
            $kycVerification->front = $kycVerification->front;
            $kycVerification->back = $kycVerification->back;
        }
        return $kycVerifications?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $kycVerification = KycVerification::findOrFail($request->id);
                $frontFile = FileManager::where('origin_type', 'App\Models\KycVerification')->where('id', $kycVerification->front_id)->first();
                $backFile = FileManager::where('origin_type', 'App\Models\KycVerification')->where('id', $kycVerification->back_id)->first();
            } else {
                $kycConfig = KycConfig::find($request->kyc_config_id);
                if ($kycConfig->is_both == ACTIVE) {
                    if (!$request->hasFile('front') || !$request->hasFile('back')) {
                        throw new Exception('Front Side and Back Side File is Required');
                    }
                } else {
                    if (!$request->hasFile('front')) {
                        throw new Exception('Front Side File is Required');
                    }
                }
                $kycVerification = new KycVerification();
                $frontFile = new FileManager();
                $backFile = new FileManager();
            }

            /*File Manager Call upload*/
            if ($request->hasFile('front')) {
                if (is_null($kycVerification->front_id)) {
                    $upload = $frontFile->upload('KycVerification', $request->front, 'front');
                } else {
                    $frontFile->removeFile();
                    $upload = $frontFile->updateUpload($kycVerification->front_id, 'KycVerification', $request->front, 'front');
                }

                if ($upload['status']) {
                    $upload['file']->origin_type = "App\Models\KycVerification";
                    $upload['file']->save();
                    $kycVerification->front_id =  $upload['file']->id;
                    $kycVerification->status = KYC_STATUS_PENDING;
                } else {
                    throw new Exception($upload['message']);
                }
            }

            if ($request->hasFile('back')) {
                if (is_null($kycVerification->back_id)) {
                    $upload = $backFile->upload('KycVerification', $request->back, 'back');
                } else {
                    $backFile->removeFile();
                    $upload = $backFile->updateUpload($kycVerification->back_id, 'KycVerification', $request->back, 'back');
                }
                if ($upload['status']) {
                    $upload['file']->origin_type = "App\Models\KycVerification";
                    $upload['file']->save();
                    $kycVerification->back_id = $upload['file']->id;
                    $kycVerification->status = KYC_STATUS_PENDING;
                } else {
                    throw new Exception($upload['message']);
                }
            }

            /*End*/

            $kycVerification->tenant_id = auth()->user()->tenant->id ?? $request->tenant_id;
            $kycVerification->owner_user_id = auth()->user()->owner_user_id; // for tenant upload
            $kycVerification->kyc_config_id = $request->kyc_config_id;
            $kycVerification->save();
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
        $kycVerification = KycVerification::query()
            ->join('kyc_configs', 'kyc_verifications.kyc_config_id', '=', 'kyc_configs.id')
            ->join('tenants', 'kyc_verifications.tenant_id', '=', 'tenants.id')
            ->join('users', 'tenants.user_id', '=', 'users.id')
            ->join('properties', 'tenants.property_id', '=', 'properties.id')
            ->join('property_units', 'tenants.unit_id', '=', 'property_units.id')
            ->whereNull('users.deleted_at')
            ->select('kyc_verifications.*', 'kyc_configs.name as config_name', 'kyc_configs.is_both', 'users.first_name', 'users.last_name', 'properties.name as property_name', 'property_units.unit_name')
            ->findOrFail($id);
        return $kycVerification?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getConfigInfo($id)
    {
        $kycConfig = KycConfig::findOrFail($id);
        return $kycConfig?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'owner_user_id']);
    }

    public function statusChange($id)
    {
        try {
            $kycVerification = KycVerification::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $kycVerification->status = KYC_STATUS_ACCEPTED;
            $kycVerification->save();
            $message = __(STATUS_UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function rejectReasonStore($request)
    {
        DB::beginTransaction();
        try {
            $kycVerification = KycVerification::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
            $kycVerification->reason = $request->reason;
            $kycVerification->status = KYC_STATUS_REJECTED;
            $kycVerification->save();
            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function delete($id)
    {
        if (auth()->user()->role == USER_ROLE_OWNER) {
            $ownerUserId = getOwnerUserId();
        } else {
            $ownerUserId = auth()->user()->owner_user_id;
        }

        try {
            $kycVerification = KycVerification::query()
                ->where('owner_user_id', $ownerUserId)
                ->when(auth()->user()->role == USER_ROLE_TENANT, function ($q) {
                    $q->where('tenant_id', auth()->user()->tenant->id);
                })
                ->findOrFail($id);
            $kycVerification->delete();
            return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }
    }

    public function deleteItem($id)
    {
        try {
            if (auth()->user()->role == USER_ROLE_OWNER) {
                $ownerUserId = getOwnerUserId();
            } else {
                $ownerUserId = auth()->user()->owner_user_id;
            }
            $kycVerification = KycVerification::query()
                ->where('owner_user_id', $ownerUserId)
                ->when(auth()->user()->role == USER_ROLE_TENANT, function ($q) {
                    $q->where('tenant_id', auth()->user()->tenant->id);
                })
                ->findOrFail($id);
            $kycVerification->delete();
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
