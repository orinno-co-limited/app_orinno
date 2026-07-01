<?php

namespace App\Services;

use App\Models\Faq;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class FaqService
{
    use ResponseTrait;

    public function getAll()
    {
        return Faq::all();
    }

    public function getActiveAll()
    {
        return Faq::where('status', ACTIVE)->get();
    }

    public function getInfo($id)
    {
        return Faq::findOrFail($id);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $faq = Faq::findOrFail($request->id);
            } else {
                $faq = new Faq();
            }
            $faq->question = $request->question;
            $faq->answer = $request->answer;
            $faq->status = $request->status;
            $faq->save();

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
            $faq = Faq::findOrFail($id);
            $faq->delete();
            return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }
}
