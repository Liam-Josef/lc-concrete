<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {


    Route::get('/mex-admin/accounting/transactions', [PaymentController::class, 'index'])
        ->name('admin.transactions.index');
    Route::get('/mex-admin/accounting/unsettled-transactions', [PaymentController::class, 'unsettled'])
        ->name('admin.transactions.unsettled');

    Route::get('/mex-admin/accounting/invoices/{invoice}', [PaymentController::class, 'showInvoice'])
        ->name('admin.invoices.show');

    Route::get('/my-account/invoices/{invoice}', [UserController::class, 'showInvoice'])
        ->name('billing.invoice.show');


});
