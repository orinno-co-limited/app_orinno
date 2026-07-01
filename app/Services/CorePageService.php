<?php

namespace App\Services;

use App\Models\CorePage;
use App\Models\FileManager;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class CorePageService
{
    use ResponseTrait;

    public function getAll()
    {
        return CorePage::all();
    }

    public function getActiveAll()
    {
        return CorePage::where('status', ACTIVE)->get();
    }

    public function getInfo($id)
    {
        return CorePage::findOrFail($id);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $corePage = CorePage::findOrFail($request->id);
            } else {
                $corePage = new CorePage();
            }
            $corePage->name = $request->name;
            $corePage->title = $request->title;
            $corePage->summary = $request->summary;
            $corePage->content = $request->content;
            $corePage->status = $request->status;
            $corePage->save();

            /*File Manager Call upload*/
            if ($request->hasFile('image')) {
                $new_file = FileManager::where('origin_type', 'App\Models\CorePage')->where('origin_id', $corePage->id)->first();

                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'CorePage', $request->image);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('CorePage', $request->image);
                }

                if ($upload['status']) {
                    $upload['file']->origin_id = $corePage->id;
                    $upload['file']->origin_type = "App\Models\CorePage";
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
            $corePage = CorePage::findOrFail($id);
            $corePage->delete();
            return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __(SOMETHING_WENT_WRONG));
        }
    }
}
