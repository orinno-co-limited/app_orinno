<?php

namespace App\Services;

use App\Models\Feature;
use App\Models\FileManager;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class FeatureService
{
    use ResponseTrait;
    public function getAll()
    {
        return Feature::all();
    }

    public function getActiveAll()
    {
        return Feature::where('status', ACTIVE)->get();
    }

    public function getInfo($id)
    {
        return Feature::findOrFail($id);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $amazingFeature = Feature::findOrFail($request->id);
            } else {
                $amazingFeature = new Feature();
            }
            $amazingFeature->title = $request->title;
            $amazingFeature->summary = $request->summary;
            $amazingFeature->status = $request->status;
            $amazingFeature->save();

            /*File Manager Call upload*/
            if ($request->hasFile('icon')) {
                $new_file = FileManager::where('origin_type', 'App\Models\Feature')->where('origin_id', $amazingFeature->id)->first();

                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'Feature', $request->icon);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('Feature', $request->icon);
                }

                if ($upload['status']) {
                    $upload['file']->origin_id = $amazingFeature->id;
                    $upload['file']->origin_type = "App\Models\Feature";
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

    public function delete($id)
    {
        try {
            $amazingFeature = Feature::findOrFail($id);
            $amazingFeature->delete();
            return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
