<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileManager;
use App\Models\Language;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $data['pageTitle'] = __('Language');
        $data['subLanguageActiveClass'] = 'active';
        $data['languages'] = Language::query()->orderByDesc('id')->get();
        return view('admin.language.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:languages,name',
            'code' => 'required|unique:languages,code|in:' . implode(',', array_keys(languageIsoCode())),
        ]);

        DB::beginTransaction();
        try {
            $language = new Language();
            $language->name = $request->name;
            $language->code = $request->code;
            $language->rtl = $request->rtl;
            $language->status = $request->status;
            $language->default = $request->default;
            $language->save();

            /*File Manager Call upload*/
            if ($request->hasFile('icon')) {
                $new_file = new FileManager();
                $upload = $new_file->upload('Language', $request->icon, $request->code);
                if ($upload['status']) {
                    $upload['file']->origin_id = $language->id;
                    $upload['file']->origin_type = "App\Models\Language";
                    $upload['file']->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }

            if ($request->hasFile('font')) {
                $new_file = new FileManager();
                $upload = $new_file->upload('Language', $request->font, $request->code);
                if ($upload['status']) {
                    $upload['file']->origin_type = "App\Models\Language";
                    $upload['file']->save();

                    $language->font_id = $upload['file']->id;
                    $language->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }
            /*End*/

            // Start:: Default language setup local
            if ($request->default == ACTIVE) {
                Language::where('id', '!=', $language->id)->update(['default' => DEACTIVATE]);
            }

            $defaultLanguage = Language::where('default', ACTIVE)->first();
            if ($defaultLanguage) {
                $ln = $defaultLanguage->code;
                session(['locale' => $ln]);
                Carbon::setLocale($ln);
                App::setLocale(session()->get('locale'));
            }
            // End:: Default language setup local

            $path = resource_path('lang/');
            fopen($path . "$request->code.json", "w");
            file_put_contents($path . "$request->code.json", '{}');

            DB::commit();
            $message = __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:languages,name,' . $id,
        ]);
        DB::beginTransaction();
        try {
            $language = Language::findOrFail($id);
            $language->name = $request->name;
            $language->rtl = $request->rtl;
            $language->status = $request->status;
            $language->default = $request->default;
            $language->save();

            /*File Manager Call upload*/
            if ($request->hasFile('icon')) {
                $new_file = FileManager::where('origin_type', 'App\Models\Language')->where('origin_id', $language->id)->first();

                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'Language', $request->icon);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('Language', $request->icon);
                }

                if ($upload['status']) {
                    $upload['file']->origin_id = $language->id;
                    $upload['file']->origin_type = "App\Models\Language";
                    $upload['file']->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }
            if ($request->hasFile('font')) {
                $new_file = FileManager::where('origin_type', 'App\Models\Language')->where('id', $language->font_id)->first();

                if ($new_file) {
                    $new_file->removeFile();
                    $upload = $new_file->updateUpload($new_file->id, 'Language', $request->font, $language->code);
                } else {
                    $new_file = new FileManager();
                    $upload = $new_file->upload('Language', $request->font, $language->code);
                }

                if ($upload['status']) {
                    $upload['file']->origin_type = "App\Models\Language";
                    $upload['file']->save();

                    $language->font_id = $upload['file']->id;
                    $language->save();
                } else {
                    throw new Exception($upload['message']);
                }
            }
            /*End*/

            // Start:: Default language setup local
            if ($request->default == ACTIVE) {
                Language::where('id', '!=', $language->id)->update(['default' => 0]);
            }

            $defaultLanguage = Language::where('default', ACTIVE)->first();
            if ($defaultLanguage) {
                $ln = $defaultLanguage->code;
                session(['locale' => $ln]);
                Carbon::setLocale($ln);
                App::setLocale(session()->get('locale'));
            }
            // End:: Default language setup local

            $path = resource_path() . "/lang/$language->code.json";

            if (file_exists($path)) {
                $file_data = json_encode(file_get_contents($path));
                unlink($path);
                file_put_contents($path, json_decode($file_data));
            } else {
                fopen(resource_path() . "/lang/$language->code.json", "w");
                file_put_contents(resource_path() . "/lang/$language->code.json", '{}');
            }

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
        $language = Language::findOrFail($id);
        if ($language->default == ACTIVE) {
            return redirect()->back()->with('warning', 'You Cannot delete default language.');
        }elseif (Language::count() == 1){
            return redirect()->back()->with('warning','You need minimum one language to running this application');
        }

        $file = FileManager::where('origin_type', 'App\Models\Language')->where('origin_id', $language->id)->first();
        if ($file) {
            $file->removeFile();
            $file->delete();
        }

        $path = resource_path() . "/lang/$language->code.json";
        if (file_exists($path)) {
            @unlink($path);
        }

        $language->delete();
        return redirect()->route('admin.language.index')->with('success', __('Deleted Successfully.'));
    }

    public function translateLanguage($id)
    {
        $data['pageTitle'] = __('Translate');
        $data['subLanguageActiveClass'] = 'active';
        $data['language'] = Language::findOrFail($id);
        $code = $data['language']->code;

        $path = resource_path() . "/lang/$code.json";
        if (!file_exists($path)) {
            fopen(resource_path() . "/lang/$code.json", "w");
            file_put_contents(resource_path() . "/lang/$code.json", '{}');
        }

        $data['translators'] = json_decode(file_get_contents(resource_path() . "/lang/$code.json"), true);
        $data['languages'] = Language::where('code', '!=', $code)->get();

        return view('admin.language.translate', $data);
    }


    public function updateTranslate(Request $request, $id)
    {
        $request->validate([
            'key' => 'required',
            'val' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $language =  Language::findOrFail($id);
            $key = $request->key;
            $val = $request->val;
            $is_new = $request->is_new;
            $path = resource_path() . "/lang/$language->code.json";
            $file_data = json_decode(file_get_contents($path), 1);

            if (!array_key_exists($key, $file_data)) {
                $file_data = array($key => $val) + $file_data;
            } else if ($is_new) {
                $message = __("Already Exist");
                return $this->error([], $message);
            } else {
                $file_data[$key] = $val;
            }
            unlink($path);
            file_put_contents($path, json_encode($file_data));
            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY) ;
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }


    public function import(Request $request)
    {
        $request->validate([
            'import' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $language = Language::where('code', $request->import)->firstOrFail();
            $currentLang = Language::where('code', $request->current)->firstOrFail();
            $contents = file_get_contents(resource_path() . "/lang/$language->code.json");
            file_put_contents(resource_path() . "/lang/$currentLang->code.json", $contents);
            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }
}
