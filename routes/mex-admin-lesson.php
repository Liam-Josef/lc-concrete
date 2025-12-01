<?php

use App\Http\Controllers\SectionController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware('auth')->group(function() {


    Route::get('/mex-admin/course/index', [App\Http\Controllers\LessonController::class, 'index'])->name('lesson.index');
    Route::get('/mex-admin/course/inactive', [App\Http\Controllers\LessonController::class, 'inactive'])->name('lesson.inactive');
    Route::get('/mex-admin/course/create', [App\Http\Controllers\LessonController::class, 'create'])->name('lesson.create');
    Route::get('/mex-admin/course/{lesson}/view', [App\Http\Controllers\LessonController::class, 'view'])->name('lesson.view');
    Route::get('/mex-admin/course/{lesson}/edit', [App\Http\Controllers\LessonController::class, 'edit'])->name('lesson.edit');
    Route::post('/mex-admin/course/store', [App\Http\Controllers\LessonController::class, 'store'])->name('lesson.store');
    Route::put('/mex-admin/course/{lesson}/update', [App\Http\Controllers\LessonController::class, 'update'])->name('lesson.update');
    Route::put('/mex-admin/course/{lesson}/activate', [App\Http\Controllers\LessonController::class, 'activate'])->name('lesson.activate');
    Route::put('/mex-admin/course/{lesson}/deactivate', [App\Http\Controllers\LessonController::class, 'deactivate'])->name('lesson.deactivate');




});
