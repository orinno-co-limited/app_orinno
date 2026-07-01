<?php

use App\Http\Controllers\AddonUpdateController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\VersionUpdateController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('notification', [DashboardController::class, 'notification'])->name('notification');

    Route::group(['prefix' => 'owner', 'as' => 'owner.'], function () {
        Route::get('/', [OwnerController::class, 'index'])->name('index');
        Route::get('get-info', [OwnerController::class, 'getInfo'])->name('get.info');
        Route::get('delete/{id}', [OwnerController::class, 'delete'])->name('delete');
        Route::post('store', [OwnerController::class, 'store'])->name('store')->middleware('isDemo');
        Route::post('update', [OwnerController::class, 'update'])->name('update')->middleware('isDemo');
    });

    Route::group(['prefix' => 'language', 'as' => 'language.'], function () {
        Route::get('/', [LanguageController::class, 'index'])->name('index');
        Route::post('store', [LanguageController::class, 'store'])->name('store')->middleware('isDemo');
        Route::post('update/{id}', [LanguageController::class, 'update'])->name('update')->middleware('isDemo');
        Route::delete('delete/{id}', [LanguageController::class, 'delete'])->name('delete');

        Route::get('translate/{id}/{iso_code?}', [LanguageController::class, 'translateLanguage'])->name('translate');
        Route::get('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate');
        Route::post('import', [LanguageController::class, 'import'])->name('import');
    });

    Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
        Route::get('general-setting', [SettingController::class, 'generalSetting'])->name('general-setting');
        Route::post('general-settings-update', [SettingController::class, 'generalSettingUpdate'])->name('general-setting.update');
        Route::get('color-setting', [SettingController::class, 'colorSetting'])->name('color-setting');
        Route::get('smtp-setting', [SettingController::class, 'smtpSetting'])->name('smtp.setting');
        Route::get('recaptcha-setting', [SettingController::class, 'recaptchaSetting'])->name('recaptcha.setting');
        Route::get('map-box-setting', [SettingController::class, 'mapBoxSetting'])->name('map-box.setting')->middleware('isDemo');
        Route::post('general-settings-env-update', [SettingController::class, 'generalSettingEnvUpdate'])->name('general-setting-env.update');
        Route::get('sms-setting', [SettingController::class, 'smsSetting'])->name('sms.setting');
        Route::get('tenancy-setting', [SettingController::class, 'tenancySetting'])->name('tenancy.setting');
        Route::get('frontend-setting', [SettingController::class, 'frontendSetting'])->name('frontend.setting');
        Route::get('listing-setting', [SettingController::class, 'listingSetting'])->name('listing.setting');
        Route::get('agreement-setting', [SettingController::class, 'agreementSetting'])->name('agreement.setting');
        Route::get('reminder-setting', [SettingController::class, 'reminderSetting'])->name('reminder.setting');
        Route::get('cron-setting', [SettingController::class, 'cronSetting'])->name('cron.setting');

        Route::group(['prefix' => 'currency', 'as' => 'currency.'], function () {
            Route::get('/', [CurrencyController::class, 'index'])->name('index');
            Route::post('store', [CurrencyController::class, 'store'])->name('store');
            Route::put('update/{id}', [CurrencyController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [CurrencyController::class, 'delete'])->name('destroy');
        });

        Route::get('storage-link', [SettingController::class, 'storageLink']);
        Route::get('migrate-seed', [SettingController::class, 'migrateSeed']);
        Route::get('cache-clear', [SettingController::class, 'cacheClear']);
    });

    Route::group(['prefix' => 'mail', 'as' => 'mail.'], function () {
        Route::post('test-send', [MailController::class, 'testSend'])->name('test.send');
    });

    // version update
    Route::get('version-update', [VersionUpdateController::class, 'versionFileUpdate'])->name('file-version-update');
    Route::post('version-update', [VersionUpdateController::class, 'versionFileUpdateStore'])->name('file-version-update-store');
    Route::get('version-update-execute', [VersionUpdateController::class, 'versionUpdateExecute'])->name('file-version-update-execute');
    Route::get('version-delete', [VersionUpdateController::class, 'versionFileUpdateDelete'])->name('file-version-delete');

    Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
        Route::get('details/{code}', [AddonUpdateController::class, 'addonSaasDetails'])->name('details')->withoutMiddleware(['addon.update']);
        Route::post('store', [AddonUpdateController::class, 'addonSaasFileStore'])->name('store')->withoutMiddleware(['addon.update']);
        Route::post('execute', [AddonUpdateController::class, 'addonSaasFileExecute'])->name('execute')->withoutMiddleware(['addon.update']);
        Route::get('delete/{code}', [AddonUpdateController::class, 'addonSaasFileDelete'])->name('delete')->withoutMiddleware(['addon.update']);
    });
});
