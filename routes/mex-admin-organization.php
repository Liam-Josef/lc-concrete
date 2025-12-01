<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware('auth')->group(function() {


Route::get('/mex-admin/organizations/index', [App\Http\Controllers\OrganizationController::class, 'index'])->name('organization.index');
Route::get('/mex-admin/organizations/inactive', [App\Http\Controllers\OrganizationController::class, 'inactive'])->name('organization.inactive');
Route::get('/mex-admin/organizations/create', [App\Http\Controllers\OrganizationController::class, 'create'])->name('organization.create');
Route::get('/mex-admin/organizations/{organization}/view', [App\Http\Controllers\OrganizationController::class, 'view'])->name('organization.view');
Route::get('/mex-admin/organizations/{organization}/edit', [App\Http\Controllers\OrganizationController::class, 'edit'])->name('organization.edit');
Route::post('/mex-admin/organizations/store', [App\Http\Controllers\OrganizationController::class, 'store'])->name('organization.store');
Route::put('/mex-admin/organizations/{organization}/update', [App\Http\Controllers\OrganizationController::class, 'update'])->name('organization.update');
Route::put('/mex-admin/organizations/{organization}/activate', [App\Http\Controllers\OrganizationController::class, 'activate'])->name('organization.activate');
Route::put('/mex-admin/organizations/{organization}/deactivate', [App\Http\Controllers\OrganizationController::class, 'deactivate'])->name('organization.deactivate');

});
