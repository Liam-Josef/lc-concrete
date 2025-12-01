<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;

Auth::routes();
Route::middleware('auth')->group(function() {


    Route::get('/u/{user}/courses', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::get('/u/{user}/account', [App\Http\Controllers\UserController::class, 'account'])->name('user.account');
    Route::get('/u/{user}/settings', [App\Http\Controllers\UserController::class, 'settings'])->name('user.settings');

    Route::put('/u/{user}/info/update', [App\Http\Controllers\UserController::class, 'update_info'])->name('user.info.update');

});
