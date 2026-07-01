<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Setting;
use App\Models\TaxSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function generalSetting()
    {
        $data['pageTitle'] = __("General Setting");
        $data['subGeneralSettingActiveClass']  = 'active';
        $data['currencies'] = Currency::all();
        $data['current_currency'] = Currency::where('current_currency', ACTIVE)->first();
        $data['languages'] = Language::all();
        $data['default_language'] = Language::where('default', 1)->first();
        return view('owner.setting.general-setting')->with($data);
    }

    public function generalSettingUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $inputs = Arr::except($request->all(), ['_token']);
            $keys = [];

            foreach ($inputs as $k => $v) {
                $keys[$k] = $k;
            }

            foreach ($inputs as $key => $value) {
                $option = Setting::firstOrCreate(['option_key' => $key]);

                if ($request->hasFile('app_preloader') && $key == 'app_preloader') {
                    $upload = settingImageStoreUpdate($option->id, $request->app_preloader, 'preloader');
                    $option->option_value = $upload;
                    $option->save();
                } elseif ($request->hasFile('app_logo') && $key == 'app_logo') {
                    $upload = settingImageStoreUpdate($option->id, $request->app_logo, 'logo');
                    $option->option_value = $upload;
                    $option->save();
                } elseif ($request->hasFile('app_fav_icon') && $key == 'app_fav_icon') {
                    $upload = settingImageStoreUpdate($option->id, $request->app_fav_icon, 'favicon');
                    $option->option_value = $upload;
                    $option->save();
                } elseif ($request->hasFile('sign_in_image') && $key == 'sign_in_image') {
                    $upload = settingImageStoreUpdate($option->id, $request->sign_in_image, 'login');
                    $option->option_value = $upload;
                    $option->save();
                } else {
                    $option->option_value = $value;
                    $option->save();
                }
            }

            /**  ====== Set Currency ====== */
            if ($request->currency_id) {
                Currency::where('id', $request->currency_id)->update(['current_currency' => ACTIVE]);
                Currency::where('id', '!=', $request->currency_id)->update(['current_currency' => DEACTIVATE]);
            }

            /**  ====== Set Language ====== */
            if ($request->language_id) {
                Language::where('id', $request->language_id)->update(['default' => 1]);
                Language::where('id', '!=', $request->language_id)->update(['default' => 0]);
                $language = Language::where('default', 1)->first();
                if ($language) {
                    $ln = $language->iso_code;
                    session(['local' => $ln]);
                    Carbon::setLocale($ln);
                    App::setLocale(session()->get('local'));
                }
            }
            DB::commit();
            return redirect()->back()->with('success', __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __(SOMETHING_WENT_WRONG));
        }
    }

    public function colorSetting()
    {
        $data['pageTitle'] = __("Color Setting");
        $data['subColorSettingActiveClass']  = 'active';
        return view('owner.setting.color-setting')->with($data);
    }

    public function fontSetting()
    {
        $data['pageTitle'] = __('Font Setting');
        $data['subFontSettingActiveClass'] = 'active';
        return view('owner.setting.font-setting', $data);
    }

    public function taxSetting()
    {
        $data['pageTitle'] = __("Tax Setting");
        $data['subTaxSettingActiveClass']  = 'active';
        $data['tax'] = taxSetting(getOwnerUserId());
        return view('owner.setting.tax-setting')->with($data);
    }

    public function taxSettingUpdate(Request $request)
    {
        TaxSetting::updateOrCreate(['owner_user_id' => getOwnerUserId()], [
            'owner_user_id' => getOwnerUserId(),
            'type' => $request->type == TAX_TYPE_PERCENTAGE ? TAX_TYPE_PERCENTAGE : TAX_TYPE_FIXED,
        ]);

        return back()->with('success', __(UPDATED_SUCCESSFULLY));
    }

    public function smtpSetting()
    {
        $data['pageTitle'] = __("SMTP Setting");
        $data['subSmtpSettingActiveClass']  = 'active';
        return view('owner.setting.smtp-setting')->with($data);
    }

    public function generalSettingEnvUpdate(Request $request)
    {
        $inputs = Arr::except($request->all(), ['_token']);
        $keys = [];

        foreach ($inputs as $k => $v) {
            $keys[$k] = $k;
        }

        foreach ($inputs as $key => $value) {
            if ($key == 'app_mail_status') {
                $option = Setting::firstOrCreate(['option_key' => $key]);
                $option->option_value = $value;
                $option->type = 1;
                $option->save();
            }
            setEnvironmentValue($key, $value);
        }

        return redirect()->back()->with('success', __(UPDATED_SUCCESSFULLY));
    }

    public function storageLink()
    {
        Artisan::call('storage:link');
        return redirect()->back()->with('success', 'Created Storage Link Updated Successfully');
    }

    public function migrateSeed()
    {
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        return redirect()->back()->with('success', 'Migrate and Seed Updated Successfully');
    }
}
