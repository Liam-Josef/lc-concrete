<?php

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware('auth')->group(function() {

    Route::post('/course/videos/store', [VideoController::class, 'store'])->name('videos.store');
    Route::put('/course/videos/{video}/update', [VideoController::class, 'update'])->name('videos.update');

});
