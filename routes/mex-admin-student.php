<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware('auth')->group(function() {


    Route::get('/mex-admin/students/index', [App\Http\Controllers\StudentController::class, 'index'])->name('student.index');
    Route::get('/mex-admin/students/inactive', [App\Http\Controllers\StudentController::class, 'inactive'])->name('student.inactive');
    Route::get('/mex-admin/students/create', [App\Http\Controllers\StudentController::class, 'create'])->name('student.create');
    Route::get('/mex-admin/students/{student}/view', [App\Http\Controllers\StudentController::class, 'view'])->name('student.view');
    Route::get('/mex-admin/students/{student}/edit', [App\Http\Controllers\StudentController::class, 'edit'])->name('student.edit');
    Route::post('/mex-admin/students/store', [App\Http\Controllers\StudentController::class, 'store'])->name('student.store');
    Route::put('/mex-admin/students/{student}/update', [App\Http\Controllers\StudentController::class, 'update'])->name('student.update');
    Route::put('/mex-admin/students/{student}/activate', [App\Http\Controllers\StudentController::class, 'activate'])->name('student.activate');
    Route::put('/mex-admin/students/{student}/deactivate', [App\Http\Controllers\StudentController::class, 'deactivate'])->name('student.deactivate');

});
