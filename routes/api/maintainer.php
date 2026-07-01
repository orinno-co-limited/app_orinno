<?php

use App\Http\Controllers\Api\Maintainer\DashboardController;
use App\Http\Controllers\Api\Maintainer\InformationController;
use App\Http\Controllers\Api\Maintainer\MaintenanceRequestController;
use App\Http\Controllers\Api\Maintainer\TicketController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api', 'maintainer'], 'prefix' => 'maintainer'], function () {

    Route::get('dashboard', [DashboardController::class, 'dashboard']);

    // information
    Route::get('information', [InformationController::class, 'index']);
    Route::get('information-details', [InformationController::class, 'getInfo']);

    // maintenance request
    Route::get('maintenance-requests', [MaintenanceRequestController::class, 'index']);
    Route::get('maintenance-request-details', [MaintenanceRequestController::class, 'getInfo']);
    Route::post('maintenance-request-status-change', [MaintenanceRequestController::class, 'statusChange']);

    // ticket
    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('ticket-details/{id}', [TicketController::class, 'details']);
    Route::post('ticket-reply', [TicketController::class, 'reply']);
    Route::get('ticket-status-change', [TicketController::class, 'statusChange']);
});
