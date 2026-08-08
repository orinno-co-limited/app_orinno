<?php

use App\Http\Controllers\Api\Owner\DashboardController;
use App\Http\Controllers\Api\Owner\DocumentController;
use App\Http\Controllers\Api\Owner\ExpenseController;
use App\Http\Controllers\Api\Owner\InformationController;
use App\Http\Controllers\Api\Owner\InvoiceController;
use App\Http\Controllers\Api\Owner\MaintainerController;
use App\Http\Controllers\Api\Owner\MaintenanceRequestController;
use App\Http\Controllers\Api\Owner\NoticeBoardController;
use App\Http\Controllers\Api\Owner\PropertyController;
use App\Http\Controllers\Api\Owner\SubscriptionController;
use App\Http\Controllers\Api\Owner\TenantController;
use App\Http\Controllers\Api\Owner\TicketController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api', 'owner'], 'prefix' => 'owner'], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard']);

    // property
    Route::get('properties', [PropertyController::class, 'allProperty']);
    Route::get('property-details/{id}', [PropertyController::class, 'details']);
    Route::get('units', [PropertyController::class, 'allUnit']);

    // tenant
    Route::get('tenants', [TenantController::class, 'index']);
    Route::get('tenant-details/{id}', [TenantController::class, 'details']);

    // invoice
    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::post('invoice-store', [InvoiceController::class, 'store']);
    Route::get('invoice-details/{id}', [InvoiceController::class, 'details']);
    Route::get('invoice-destroy/{id}', [InvoiceController::class, 'destroy']);
    Route::get('invoice-types', [InvoiceController::class, 'types']);
    Route::post('invoice-send-notification', [InvoiceController::class, 'sendNotification']);
    Route::post('invoice-payment-status', [InvoiceController::class, 'paymentStatus']);

    // expense
    Route::get('expenses', [ExpenseController::class, 'index']);
    Route::post('expense-store', [ExpenseController::class, 'store']);
    Route::get('expense-destroy/{id}', [ExpenseController::class, 'destroy']);

    // document
    Route::get('documents', [DocumentController::class, 'index']);
    Route::get('document-status/{id}', [DocumentController::class, 'statusChange']);
    Route::get('document-details/{id}', [DocumentController::class, 'getInfo']);
    Route::post('document-reject-reason', [DocumentController::class, 'rejectReasonStore']);
    Route::get('document-delete/{id}', [DocumentController::class, 'delete']);

    // information
    Route::get('information', [InformationController::class, 'index']);
    Route::post('information-store', [InformationController::class, 'store']);
    Route::get('information-details/{id}', [InformationController::class, 'getInfo']);
    Route::get('information-delete/{id}', [InformationController::class, 'delete']);

    // maintainer
    Route::get('maintainers', [MaintainerController::class, 'index']);
    Route::post('maintainer-store', [MaintainerController::class, 'store']);
    Route::get('maintainer-details/{id}', [MaintainerController::class, 'details']);
    Route::get('maintainer-delete/{id}', [MaintainerController::class, 'delete']);

    // maintenance
    Route::get('maintenance-requests', [MaintenanceRequestController::class, 'index']);
    Route::post('maintenance-request-store', [MaintenanceRequestController::class, 'store']);
    Route::get('maintenance-request-details/{id}', [MaintenanceRequestController::class, 'getInfo']);
    Route::post('maintenance-request-status-change', [MaintenanceRequestController::class, 'statusChange']);
    Route::get('maintenance-request-delete/{id}', [MaintenanceRequestController::class, 'delete']);

    // ticket
    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('ticket-details/{id}', [TicketController::class, 'details']);
    Route::post('ticket-reply', [TicketController::class, 'reply']);
    Route::get('ticket-status-change', [TicketController::class, 'statusChange']);

    // noticeboard
    Route::get('notice-boards', [NoticeBoardController::class, 'index']);
    Route::post('notice-board-store', [NoticeBoardController::class, 'store']);
    Route::get('notice-board-details/{id}', [NoticeBoardController::class, 'getInfo']);
    Route::get('notice-board-delete/{id}', [NoticeBoardController::class, 'delete']);

    // subscription
    Route::get('subscription', [SubscriptionController::class, 'index']);
    Route::post('subscription-order', [SubscriptionController::class, 'order']);
    Route::get('subscription-plan', [SubscriptionController::class, 'getPlan']);
    Route::get('subscription-currency-by-gateway', [SubscriptionController::class, 'getCurrencyByGateway']);
    Route::post('subscription-cancel', [SubscriptionController::class, 'cancel']);
});
