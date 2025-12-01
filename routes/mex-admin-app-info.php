<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;

Auth::routes();
Route::middleware('auth')->group(function() {
    Route::get('mex-admin/utilities/app', [AppController::class, 'edit'])->name('admin.app.edit');
    Route::put('mex-admin/utilities/app', [AppController::class, 'update'])->name('admin.app.update');
});
