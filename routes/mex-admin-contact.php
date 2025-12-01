<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware('auth')->group(function() {


    Route::get('/mex-admin/contacts/index', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
    Route::get('/mex-admin/contacts/inactive', [App\Http\Controllers\ContactController::class, 'inactive'])->name('contact.inactive');
    Route::get('/mex-admin/contacts/create', [App\Http\Controllers\ContactController::class, 'create'])->name('contact.create');
    Route::get('/mex-admin/contacts/{contact}/view', [App\Http\Controllers\ContactController::class, 'view'])->name('contact.view');
    Route::get('/mex-admin/contacts/{contact}/edit', [App\Http\Controllers\ContactController::class, 'edit'])->name('contact.edit');
    Route::post('/mex-admin/contacts/store', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
    Route::put('/mex-admin/contacts/{contact}/update', [App\Http\Controllers\ContactController::class, 'update'])->name('contact.update');
    Route::put('/mex-admin/contacts/{contact}/activate', [App\Http\Controllers\ContactController::class, 'activate'])->name('contact.activate');
    Route::put('/mex-admin/contacts/{contact}/deactivate', [App\Http\Controllers\ContactController::class, 'deactivate'])->name('contact.deactivate');

});
