<?php

use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware('auth')->group(function() {


    Route::post('/course/{lesson}/sections', [SectionController::class, 'store'])->name('sections.store');

});
