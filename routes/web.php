<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserEmailVerifyController;
use App\Http\Controllers\VersionUpdateController;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);
Route::get('owner/register', [\App\Http\Controllers\Auth\OwnerRegisterController::class, 'showForm'])->name('owner.register.form');
Route::post('owner/register', [\App\Http\Controllers\Auth\OwnerRegisterController::class, 'store'])->name('owner.register.store');

Route::view('terms-and-conditions', 'legal.terms')->name('terms-conditions');

Route::get('/local/{ln}', function ($ln) {
    $language = Language::where('code', $ln)->first();
    if (!$language) {
        $language = Language::where('default', 1)->first();
        if ($language) {
            $ln = $language->code;
        }
    }

    session(['local' => $ln]);
    Carbon::setLocale($ln);
    App::setLocale(session()->get('local'));
    return redirect()->back();
})->name('local');

Route::group(['middleware' => ['isFrontend']], function () {
    Route::get('/', [CommonController::class, 'index'])->name('frontend');
    Route::get('recurring-generate-invoice', [CommonController::class, 'generateInvoice'])->name('recurring.generate.invoice');
    Route::get('about-us', [CommonController::class, 'aboutUs'])->name('about-us');
    Route::get('contact-us', [CommonController::class, 'contactUs'])->name('contact-us');
    Route::get('blog-list', [CommonController::class, 'blogList'])->name('blog-list');
    Route::get('blog-details/{blog_slug}', [CommonController::class, 'blogDetails'])->name('blog-details');
    Route::get('feature', [CommonController::class, 'feature'])->name('feature');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('profile', [ProfileController::class, 'myProfile'])->name('profile');
    Route::post('profile', [ProfileController::class, 'profileUpdate'])->name('profile.update');
    Route::get('change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('change-password', [ProfileController::class, 'changePasswordUpdate'])->name('change-password.update');
    Route::post('delete-my-account', [ProfileController::class, 'deleteMyAccount'])->name('delete-my-account');

    Route::get('notification-status/{id}', [NotificationController::class, 'status'])->name('notification.status');
});

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('email/verified/{token}', [UserEmailVerifyController::class, 'emailVerified'])->name('email.verified');
    Route::get('email/verify/{token}', [UserEmailVerifyController::class, 'emailVerify'])->name('email.verify');
    Route::post('email/verify/resend/{token}', [UserEmailVerifyController::class, 'emailVerifyResend'])->name('email.verify.resend');
});

Route::group(['prefix' => 'payment'], function () {
    Route::post('/', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::match(array('GET', 'POST'), 'verify', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::match(array('GET', 'POST'), 'failed', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::get('verify-redirect/{type?}', [PaymentController::class, 'verifyRedirect'])->name('payment.verify.redirect');
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('version-update', [VersionUpdateController::class, 'versionUpdate'])->name('version-update');
    Route::post('process-update', [VersionUpdateController::class, 'processUpdate'])->name('process-update');
    Route::get('version-check', [VersionUpdateController::class, 'versionCheck'])->name('versionCheck');
});


