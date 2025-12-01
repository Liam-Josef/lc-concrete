<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;

Auth::routes();
Route::middleware('auth')->group(function() {

    Route::get('/certificates/{lesson}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{lesson}/download', [CertificateController::class, 'download'])->name('certificates.download');

});
