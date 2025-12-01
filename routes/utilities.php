<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserIndexController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','admin'])
    ->prefix('mex-admin/utilities/pages')
    ->name('utilities.')
    ->group(function () {
        Route::get('/',                [\App\Http\Controllers\PageController::class, 'page_index'])->name('page_index');
        Route::get('/create',          [\App\Http\Controllers\PageController::class, 'page_create'])->name('page_create');
        Route::post('/',               [\App\Http\Controllers\PageController::class, 'page_store'])->name('page_store');
        Route::get('/{page}/edit',     [\App\Http\Controllers\PageController::class, 'page_edit'])->name('page_edit');
        Route::put('/{page}/update',   [\App\Http\Controllers\PageController::class, 'page_update'])->name('page_update');

        // optional nicety: if someone visits “…/index”, redirect to “…/”
        Route::get('/index', fn() => redirect()->route('utilities.page_index'));
    });

Route::middleware(['auth'])->name('admin.')->group(function () {
    Route::get('mex-admin/utilities/users', [UserIndexController::class, 'index'])->name('utilities.user.index');

    // NEW:
    Route::get('mex-admin/utilities/users/create', [UserIndexController::class, 'create'])
        ->name('utilities.user.create');

    Route::post('mex-admin/utilities/users', [UserIndexController::class, 'store'])
        ->name('utilities.user.store');

    Route::get('mex-admin/utilities/user/{user}/profile', [UserController::class, 'user_profile'])
        ->name('utilities.user.profile');

    // Reuse becomeStudent, but allow admin to target a specific user_id
    Route::post('/users/{user}/make-student', [StudentController::class, 'becomeStudent'])
        ->name('users.make-student');
});
