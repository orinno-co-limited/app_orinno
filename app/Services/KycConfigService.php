<?php

namespace App\Services;

use App\Models\FileManager;
use App\Models\KycConfig;
use App\Models\KycVerification;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class KycConfigService
{
    use ResponseTrait;

    public function getAll()
    {
        return KycConfig::query()
            ->leftJoin('tenants', 'kyc_configs.tenant_id', '=', 'tenants.id')
            ->leftJoin('users', 'tenants.user_id', '=', 'users.id')
            ->select('kyc_configs.*', 'users.first_name', 'users.last_name')
            ->where('kyc_configs.owner_user_id', getOwnerUserId())
            ->get();
    }

    public function getActiveAll()
    {
        return KycConfig::where('tenant_id', null)->where('status', ACTIVE)->get();
    }

    public function getActiveByTenantId($id)
    {
        $kycVerificationExistIds = KycVerification::query()
            ->where('tenant_id', $id)
            ->pluck('kyc_config_id')
            ->toArray();

        $configs = KycConfig::query()
            ->where(function ($query) use ($id) {
                $query->where('tenant_id', $id)
                    ->orWhereNull('tenant_id');
            })
            ->where('owner_user_id', auth()->user()->owner_user_id)
            ->where('status', ACTIVE)
            ->whereNotIn('id', $kycVerificationExistIds)
            ->get();
        return $configs?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'owner_user_id']);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $kycConfig = KycConfig::updateOrCreate([
                'id' => $request->id,
                'owner_user_id' => getOwnerUserId()
            ], [
                'name' => $request->name,
                'details' => $request->details,
                'status' => $request->status,
                'tenant_id' => $request->tenant_id,
                'owner_user_id' => getOwnerUserId(),
                'is_both' => $request->is_both == 'on' ? ACTIVE : DEACTIVATE,
            ]);

            /*File Manager Call upload*/
            if ($request->hasFile('demo')) {
                $existFile = FileManager::where('origin_type', 'App\Models\KycConfig')->where('origin_id', $kycConfig->id)->first();
                if ($existFile) {
                    $existFile->removeFile();
                    $upload = $existFile->updateUpload($existFile->id, 'KycConfig', $request->demo);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('KycConfig', $request->demo);
                }

                if ($upload['status']) {
                    $upload['file']->origin_id = $kycConfig->id;
                    $upload['file']->origin_type = "App\Models\KycConfig";
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

    public function getInfo($id)
    {
        return KycConfig::where('owner_user_id', getOwnerUserId())->findOrFail($id);
    }

    public function delete($id)
    {
        $kycConfig = KycConfig::where('owner_user_id', getOwnerUserId())->findOrFail($id);
        $kycConfig->delete();
        return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
    }
}
