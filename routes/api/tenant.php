<?php

use App\Http\Controllers\Api\Tenant\DashboardController;
use App\Http\Controllers\Api\Tenant\DocumentController;
use App\Http\Controllers\Api\Tenant\InformationController;
use App\Http\Controllers\Api\Tenant\InvoiceController;
use App\Http\Controllers\Api\Tenant\TicketController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api', 'tenant'], 'prefix' => 'tenant'], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard']);

    // invoice
    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::get('invoice-details/{id}', [InvoiceController::class, 'details']);
    Route::get('invoice-pay/{id}', [InvoiceController::class, 'pay']);
    Route::get('invoice-currency-by-gateway', [InvoiceController::class, 'getCurrencyByGateway']);

    // information
    Route::get('information', [InformationController::class, 'index']);
    Route::get('information-details', [InformationController::class, 'getInfo']);

    // document
    Route::get('documents', [DocumentController::class, 'index']);
    Route::post('document-store', [DocumentController::class, 'store']);
    Route::get('document-details', [DocumentController::class, 'getInfo']);
    Route::get('document-config-info', [DocumentController::class, 'getConfigInfo']);
    Route::get('document-delete/{id}', [DocumentController::class, 'delete']);
    Route::get('document-configs', [DocumentController::class, 'configs']);

    // ticket
    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('ticket-details/{id}', [TicketController::class, 'details']);
    Route::post('ticket-store', [TicketController::class, 'store']);
    Route::post('ticket-reply', [TicketController::class, 'reply']);
    Route::get('ticket-status-change', [TicketController::class, 'statusChange']);
    Route::get('ticket-delete/{id}', [TicketController::class, 'delete']);
    Route::get('ticket-topics', [TicketController::class, 'topics']);
});
