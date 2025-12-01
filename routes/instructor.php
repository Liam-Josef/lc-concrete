<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {

    Route::get('/mex-admin/instructors', [App\Http\Controllers\InstructorController::class, 'index'])->name('instructor.index');
    Route::get('/mex-admin/instructors/create', [App\Http\Controllers\InstructorController::class, 'create'])->name('instructor.create');
    Route::post('/mex-admin/instructors/store', [App\Http\Controllers\InstructorController::class, 'store'])->name('instructor.store');
    Route::get('/mex-admin/instructors/{instructor}/v', [App\Http\Controllers\InstructorController::class, 'view'])->name('instructor.view');
    Route::get('/mex-admin/instructors/{instructor}/edit', [App\Http\Controllers\InstructorController::class, 'edit'])->name('instructor.edit');
    Route::put('/mex-admin/instructors/{instructor}/update', [App\Http\Controllers\InstructorController::class, 'update'])->name('instructor.update');
    Route::get('/mex-admin/instructors/inactive', [App\Http\Controllers\InstructorController::class, 'inactive'])->name('instructor.inactive');
    Route::put('/mex-admin/instructors/{instructor}/activate', [App\Http\Controllers\InstructorController::class, 'activate'])->name('instructor.activate');
    Route::put('/mex-admin/instructors/{instructor}/deactivate', [App\Http\Controllers\InstructorController::class, 'deactivate'])->name('instructor.deactivate');

    Route::post('/admin/instructors/quick-store', [\App\Http\Controllers\InstructorController::class, 'quickStore'])
        ->name('admin.instructors.quick-store')
        ->middleware(['auth', 'admin']); // adjust middleware to your stack






});
