<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web','admin'])
    ->prefix('mex-admin')
    ->name('admin.courses.')
    ->group(function () {
        Route::get('/series',                 [CourseController::class, 'index'])->name('index');

        Route::get('/series/create',          [CourseController::class, 'createStep1'])->name('create');
        Route::post('/series/create',         [CourseController::class, 'postStep1'])->name('create.post');

        Route::get('/series/create/details',  [CourseController::class, 'createStep2'])->name('create.details');
        Route::post('/series',                [CourseController::class, 'store'])->name('store');

        Route::get('/series/{course}',        [CourseController::class, 'show'])->name('show');
        Route::post('/series/quick-store', [CourseController::class, 'quickStore'])
            ->name('quick-store');

        Route::get('/series/{course}/edit', [CourseController::class, 'edit'])->name('edit');
        Route::put('/series/{course}',       [CourseController::class, 'update'])->name('update');

        Route::get('/series/by-org/{org}', [CourseController::class, 'byOrg'])
            ->name('by-org');

        Route::put('/series/{course}/deactivate', [CourseController::class, 'deactivate'])
            ->name('deactivate');
    });



Route::middleware(['web','admin'])
    ->prefix('admin')
    ->name('admin.organizations.')
    ->group(function () {
        Route::post('/organizations/quick-store', [OrganizationController::class, 'quickStore'])
            ->name('quick-store');
    });

