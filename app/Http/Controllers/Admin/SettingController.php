<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function generalSetting()
    {
        $data['pageTitle'] = __("Basic Setting");
        $data['subGeneralSettingActiveClass'] = 'active';
        $data['navApplicationSettingParentActiveClass'] = 'mm-active';
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['currencies'] = Currency::all();
        $data['current_currency'] = Currency::where('current_currency', ACTIVE)->first();
        $data['languages'] = Language::all();
        $data['default_language'] = Language::where('default', 1)->first();
        return view('admin.setting.general-setting')->with($data);
    }

    public function generalSettingUpdate(Request $request)
    {
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
            } elseif ($request->hasFile('app_logo_white') && $key == 'app_logo_white') {
                $upload = settingImageStoreUpdate($option->id, $request->app_logo_white, 'logo-white');
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
            } elseif ($request->hasFile('home_hero_image') && $key == 'home_hero_image') {
                $upload = settingImageStoreUpdate($option->id, $request->home_hero_image, 'hero-main');
                $option->option_value = $upload;
                $option->save();
            } elseif ($request->hasFile('home_features_image') && $key == 'home_features_image') {
                $upload = settingImageStoreUpdate($option->id, $request->home_features_image, 'home-feature');
                $option->option_value = $upload;
                $option->save();
            } elseif ($request->hasFile('home_about_us_image_one') && $key == 'home_about_us_image_one') {
                $upload = settingImageStoreUpdate($option->id, $request->home_about_us_image_one, 'home-about');
                $option->option_value = $upload;
                $option->save();
            }elseif ($request->hasFile('home_about_us_image_two') && $key == 'home_about_us_image_two') {
                $upload = settingImageStoreUpdate($option->id, $request->home_about_us_image_two, 'home-about');
                $option->option_value = $upload;
                $option->save();
            } elseif ($request->hasFile('home_integration_section_image') && $key == 'home_integration_section_image') {
                $upload = settingImageStoreUpdate($option->id, $request->home_integration_section_image, 'home-integration');
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

        return redirect()->back()->with('success', __(UPDATED_SUCCESSFULLY));
    }

    public function colorSetting()
    {
        $data['pageTitle'] = __("Color Setting");
        $data['subColorSettingActiveClass'] = 'active';
        return view('admin.setting.color-setting')->with($data);
    }

    public function smtpSetting()
    {
        $data['pageTitle'] = __("SMTP Setting");
        $data['subSmtpSettingActiveClass'] = 'active';
        return view('admin.setting.smtp-setting')->with($data);
    }

    public function recaptchaSetting()
    {
        $data['pageTitle'] = __("reCaptcha Setting");
        $data['subRecaptchaSettingActiveClass'] = 'active';
        return view('admin.setting.recaptcha-setting')->with($data);
    }
    public function mapBoxSetting()
    {
        $data['pageTitle'] = __("Map Box Setting");
        $data['subMapBoxSettingActiveClass'] = 'active';
        return view('admin.setting.map-box-setting')->with($data);
    }

    public function smsSetting()
    {
        if (isAddonInstalled('PROTYSMS') < 1) {
            abort(404);
        }
        $data['pageTitle'] = __("Twilio Sms Setting");
        $data['subSmsSettingActiveClass'] = 'active';
        return view('admin.setting.sms-setting')->with($data);
    }

    public function reminderSetting()
    {
        if (isAddonInstalled('PROTYSMS') < 1) {
            abort(404);
        }
        $data['pageTitle'] = __("Reminder Invoice Setting");
        $data['subReminderSettingActiveClass'] = 'active';
        return view('admin.setting.reminder-setting')->with($data);
    }

    public function tenancySetting()
    {
        if (isAddonInstalled('PROTYTENANCY') < 1) {
            abort(404);
        }
        $data['pageTitle'] = __("Tenancy Setting");
        $data['subTenancySettingActiveClass'] = 'active';
        return view('admin.setting.tenancy-setting')->with($data);
    }

    public function frontendSetting()
    {
        if (isAddonInstalled('PROTYSAAS') < 1) {
            abort(404);
        }
        $data['pageTitle'] = __("Frontend Setting");
        $data['subFrontendSettingActiveClass'] = 'active';
        return view('admin.setting.frontend-setting')->with($data);
    }

    public function listingSetting()
    {
        if (isAddonInstalled('PROTYLISTING') < 1) {
            abort(404);
        }
        $data['pageTitle'] = __("Listing Setting");
        $data['subListingSettingActiveClass'] = 'active';
        return view('admin.setting.listing-setting')->with($data);
    }

    public function agreementSetting()
    {
        if (isAddonInstalled('PROTYAGREEMENT') < 1) {
            abort(404);
        }
        $path = storage_path('app/jwt/private.key');
        if (!file_exists(storage_path('app/jwt'))) {
            mkdir(storage_path('app/jwt'), 0777, true);
        }
        if (!file_exists($path)) {
            fopen($path, 'w');
        }
        $data['pageTitle'] = __("Docu Sign Setting");
        $data['subAgreementSettingActiveClass'] = 'active';
        return view('admin.setting.agreement-setting')->with($data);
    }

    public function cronSetting()
    {
        $data['pageTitle'] = __("Recurring Invoice Url Cron Setting");
        $data['subCronSettingActiveClass'] = 'active';
        return view('admin.setting.cron-setting')->with($data);
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
            } elseif ($key == 'DS_PRIVATE_KEY') {
                $path = storage_path('app/jwt/private.key');
                if (!file_exists(storage_path('app/jwt'))) {
                    mkdir(storage_path('app/jwt'), 0777, true);
                }
                if (!file_exists($path)) {
                    fopen($path, 'w');
                }
                file_put_contents($path, $value);
            }

            if ($key != 'DS_PRIVATE_KEY') {
                setEnvironmentValue($key, $value);
            }
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

    public function cacheClear()
    {
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        return redirect()->back()->with('success', 'Cached Clear Successfully');
    }
}
