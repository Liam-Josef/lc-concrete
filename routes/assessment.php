<?php

use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Admin: rebuild assessments from lesson videos' questions
    Route::post('/mex-admin/lessons/{lesson}/assessments/rebuild', [AssessmentController::class,'rebuild'])
        ->name('assessments.rebuild')
        ->middleware('admin');

    // Student: take an assessment
    Route::get('/lessons/{lesson}/{type}-test',  [AssessmentController::class,'show'])->name('assessments.show');   // type = pre|post
    Route::post('/assessments/{assessment}',     [AssessmentController::class,'submit'])->name('assessments.submit');

    // Student: compare pre vs post
    Route::get('/lessons/{lesson}/progress',     [AssessmentController::class,'progress'])->name('assessments.progress');
});
