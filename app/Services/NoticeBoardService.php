<?php

namespace App\Services;

use App\Models\FileManager;
use App\Models\NoticeBoard;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NoticeBoardService
{
    use ResponseTrait;

    public function getAll()
    {
        $noticeboards = NoticeBoard::query()
            ->leftJoin('properties', 'notice_boards.property_id', '=', 'properties.id')
            ->where('notice_boards.owner_user_id', getOwnerUserId())
            ->select('notice_boards.*', 'properties.name as property_name')
            ->get();
        return $noticeboards;
    }

    public function getAllData()
    {
        $noticeboard = NoticeBoard::query()
            ->leftJoin('properties', 'notice_boards.property_id', '=', 'properties.id')
            ->where('notice_boards.owner_user_id', getOwnerUserId())
            ->select('notice_boards.*', 'properties.name as property_name');
        return datatables($noticeboard)
            ->addIndexColumn()
            ->addColumn('details', function ($data) {
                return Str::limit($data->details, 30, '...');
            })
            ->addColumn('property', function ($data) {
                return $data->property_name ?? 'All';
            })
            ->addColumn('action', function ($noticeboard) {
                $id = $noticeboard->id;
                return '<div class="tbl-action-btns d-inline-flex">
                            <button type="button" class="p-1 tbl-action-btn view" data-bs-toggle="modal" data-id="' . $id . '" data-bs-target="#viewNoticeBoardDetailsModal" title="' . __('View') . '"><span class="iconify" data-icon="carbon:view-filled"></span></button>
                            <button type="button" class="p-1 tbl-action-btn edit" data-id="' . $id . '" title="' . __('Edit') . '"><span class="iconify" data-icon="clarity:note-edit-solid"></span></button>
                            <button onclick="deleteItem(\'' . route('owner.noticeboard.delete', $id) . '\', \'allDatatable\')" class="p-1 tbl-action-btn"   title="' . __('Delete') . '"><span class="iconify" data-icon="ep:delete-filled"></span></button>
                        </div>';
            })
            ->rawColumns(['details', 'property', 'action'])
            ->make(true);
    }


    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $noticeboard = NoticeBoard::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
            } else {
                $noticeboard = new NoticeBoard();
            }
            $noticeboard->title = $request->title;
            $noticeboard->owner_user_id = getOwnerUserId();
            $noticeboard->details = $request->details;
            $noticeboard->start_date = $request->start_date;
            $noticeboard->end_date = $request->end_date;
            $noticeboard->property_id = $request->property_id;
            $noticeboard->unit_id = $request->unit_id;
            $noticeboard->property_all = $request->all_property ? ACTIVE : DEACTIVATE;
            $noticeboard->unit_all = $request->all_property ? ACTIVE : ($request->all_unit ? ACTIVE : DEACTIVATE);
            $noticeboard->save();
            $tenants = Tenant::query()
                ->where('status', TENANT_STATUS_ACTIVE)
                ->where('owner_user_id', getOwnerUserId())
                ->when($request->property_id, function ($q) use ($request) {
                    $q->where('property_id', $request->property_id);
                })
                ->when($request->unit_id, function ($q) use ($request) {
                    $q->where('unit_id', $request->unit_id);
                })
                ->select('user_id')
                ->get()
                ->pluck('user_id')
                ->toArray();
            $noticeboard->userNotices()->sync($tenants);

            /*File Manager Call upload*/
            if ($request->hasFile('image')) {
                $new_file = FileManager::where('origin_type', 'App\Models\NoticeBoard')->where('origin_id', $noticeboard->id)->first();
                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'NoticeBoard', $request->image);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('NoticeBoard', $request->image);
                }

                if ($upload['status']) {
                    $upload['file']->origin_id = $noticeboard->id;
                    $upload['file']->origin_type = "App\Models\NoticeBoard";
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
        return NoticeBoard::findOrFail($id);
    }

    public function getInfo($id)
    {
        $noticeboard = NoticeBoard::findOrFail($id);
        if (is_null($noticeboard->property_id)) {
            $noticeboard->property_name = 'All';
        } else {
            $property = Property::find($noticeboard->property_id);
            $noticeboard->property_name = $property->name;
        }
        if (is_null($noticeboard->unit_id)) {
            $noticeboard->unit_name = 'All';
        } else {
            $unit = PropertyUnit::find($noticeboard->unit_id);
            $noticeboard->unit_name = $unit->unit_name;
        }
        $noticeboard->image = $noticeboard->image;
        return $noticeboard?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function deleteById($id)
    {
        DB::beginTransaction();
        try {
            $noticeboard =  NoticeBoard::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            $noticeboard->delete();
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
