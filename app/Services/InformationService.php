<?php

namespace App\Services;

use App\Models\FileManager;
use App\Models\Information;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class InformationService
{
    use ResponseTrait;

    public function getAll()
    {
        $information = Information::query()
            ->join('properties', 'information.property_id', '=', 'properties.id')
            ->where('information.owner_user_id', getOwnerUserId())
            ->select('information.id', 'information.name', 'information.distance', 'information.additional_information', 'information.contact_number', 'properties.name as property_name')
            ->get();
        return $information?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'owner_user_id']);
    }

    public function getAllData()
    {
        $information = Information::query()
            ->join('properties', 'information.property_id', '=', 'properties.id')
            ->where('information.owner_user_id', getOwnerUserId())
            ->select('information.id', 'information.name', 'information.distance', 'information.additional_information', 'information.contact_number', 'properties.name as property_name');
        return datatables($information)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                return '<div class="tenants-tbl-info-object tbl-info-property-img d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="' . $data->image . '"
                                class="rounded avatar-md tbl-user-image"
                                alt="">
                            </div>
                        </div>';
            })
            ->addColumn('property', function ($data) {
                return $data->property_name;
            })
            ->addColumn('action', function ($data) {
                $id = $data->id;
                return '<div class="tbl-action-btns d-inline-flex">
                            <button type="button" class="p-1 tbl-action-btn view" data-id="' . $id . '" title="' . __('View') . '">
                                <span class="iconify" data-icon="carbon:view-filled"></span>
                            </button>
                            <button type="button" class="p-1 tbl-action-btn edit" data-id="' . $id . '" title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>
                            <button onclick="deleteItem(\'' . route('owner.information.delete', $id) . '\', \'allDatatable\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>
                        </div>';
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }


    public function store($request)
    {
        DB::beginTransaction();
        try {
            $information =  Information::updateOrCreate([
                'id' => $request->id,
                'owner_user_id' => getOwnerUserId()
            ], [
                'property_id' => $request->property_id,
                'owner_user_id' => getOwnerUserId(),
                'name' => $request->name,
                'distance' => $request->distance,
                'additional_information' => $request->additional_information,
                'contact_number' => $request->contact_number,
            ]);

            /*File Manager Call upload*/
            if ($request->hasFile('image')) {
                $new_file = FileManager::where('origin_type', 'App\Models\Information')->where('origin_id', $information->id)->first();

                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'Information', $request->image);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('Information', $request->image);
                }

                if ($upload['status']) {
                    $upload['file']->origin_id = $information->id;
                    $upload['file']->origin_type = "App\Models\Information";
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

    public function getById($id)
    {
        return Information::findOrFail($id);
    }

    public function getInfo($id)
    {
        $information = Information::query()
            ->join('properties', 'information.property_id', '=', 'properties.id')
            ->select('properties.name as property_name', 'information.*')
            ->findOrFail($id);
        return $information?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'owner_user_id']);
    }

    public function getByPropertyId($id)
    {
        $information = Information::query()
            ->where('information.owner_user_id', auth()->user()->owner_user_id)
            ->join('properties', 'information.property_id', '=', 'properties.id')
            ->select('properties.name as property_name', 'information.*')
            ->when(auth()->user()->role == USER_ROLE_MAINTAINER, function ($q) use ($id) {
                $q->whereIn('information.property_id', $id);
            })
            ->when(auth()->user()->role == USER_ROLE_TENANT, function ($q) use ($id) {
                $q->where('information.property_id', $id);
            })
            ->get();
        foreach ($information as $info) {
            $info->image = $info->image;
        }
        return $information?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'owner_user_id', 'file_attach']);
    }

    public function deleteById($id)
    {
        DB::beginTransaction();
        try {
            $information =  Information::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $information->delete();
            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
