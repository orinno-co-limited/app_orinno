<?php


use App\Http\Controllers\Maintainer\DashboardController;
use App\Http\Controllers\Maintainer\InformationController;
use App\Http\Controllers\Maintainer\MaintenanceRequestController;
use App\Http\Controllers\Maintainer\TicketController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'maintainer', 'as' => 'maintainer.', 'middleware' => ['auth', 'maintainer']], function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('notification', [DashboardController::class, 'notification'])->name('notification');

    Route::group(['prefix' => 'information', 'as' => 'information.'], function () {
        Route::get('/', [InformationController::class, 'index'])->name('index');
        Route::get('get-info', [InformationController::class, 'getInfo'])->name('get.info'); // ajax
    });

    Route::group(['prefix' => 'maintenance-request', 'as' => 'maintenance-request.'], function () {
        Route::get('/', [MaintenanceRequestController::class, 'index'])->name('index');
        Route::get('get-info', [MaintenanceRequestController::class, 'getInfo'])->name('get.info'); // ajax
        Route::post('status-change', [MaintenanceRequestController::class, 'statusChange'])->name('status.change');
        Route::get('view/{id}', [MaintenanceRequestController::class, 'view'])->name('view');
    });

    Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('details/{id}', [TicketController::class, 'details'])->name('details');
        Route::post('reply', [TicketController::class, 'reply'])->name('reply');
        Route::get('status-change', [TicketController::class, 'statusChange'])->name('status.change');
    });
});
