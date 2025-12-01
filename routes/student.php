<?php

use Illuminate\Support\Facades\Route;
// If the controller file is app/Http/Controllers/LessonFlowController.php:
use App\Http\Controllers\LessonFlowController;
// If you moved it to app/Http/Controllers/Student/LessonFlowController.php, use this instead:
// use App\Http\Controllers\Student\LessonFlowController;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentRegisterController;


Route::get('/student/register', [StudentRegisterController::class, 'show'])->name('student.register');
Route::post('/student/register', [StudentRegisterController::class, 'store'])->name('student.register.store');

Route::middleware('auth')->post('/student/become', [StudentController::class, 'becomeStudent'])->name('student.become');

Route::middleware(['auth'])->group(function () {

    Route::get('/lessons/{lesson}/start', [LessonFlowController::class, 'start'])->name('lessons.start');

    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');

    Route::get('/student/course/{lesson}/lesson', [StudentController::class, 'course_lesson'])->name('student.course_lesson');

    Route::get('/lessons/{lesson}/video/{video}', [StudentController::class, 'loadVideo'])->name('lesson.showVideo');

    Route::get('/lesson/{lesson}/next/{currentVideo}', [\App\Http\Controllers\StudentController::class, 'goToNextVideo'])
        ->name('lesson.goToNextVideo');

    Route::post('/lessons/{video}/answer/submit', [StudentController::class, 'submitAnswers'])->name('student.submitAnswers');

    Route::post('/lessons/{lesson}/video/{video}/watched',
        [\App\Http\Controllers\StudentController::class, 'markWatched']
    )->name('lesson.markWatched');

    Route::get('/student/{lesson}/lesson', [StudentController::class, 'lesson'])->name('student.lesson');

    Route::get('/courses/{lesson}/register', [LessonController::class, 'register'])->name('lesson.register');
    Route::post('/lessons/{lesson}/register', [LessonController::class, 'student_register'])->name('lesson.student-register');
});
