<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//temp


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
use App\Http\Controllers\HomeController;

Route::get('/courses', [HomeController::class, 'courses'])->name('home.courses');
Route::get('/courses/{course}', [HomeController::class, 'courseShow'])->name('home.courses.show');
Route::get('/lessons/{lesson}', [HomeController::class, 'course_lesson'])->name('home.course_lesson');


Route::get('/courses/shipping-series', [App\Http\Controllers\HomeController::class, 'course'])->name('home.course');

Route::get('/courses/{lesson}/register', [App\Http\Controllers\LessonController::class, 'register'])->name('lesson.register');

Route::get('/news', [App\Http\Controllers\NewsController::class, 'home_news'])->name('home.news');
Route::get('/news/{news}/article', [App\Http\Controllers\NewsController::class, 'home_show'])->name('home.news-post');

Route::get('/terms-and-conditions', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');

Route::post('/ajax/login', [LoginController::class, 'ajaxLogin'])
    ->name('ajax.login')
    ->middleware('throttle:10,1');

Route::middleware('auth')->group(function() {

    Route::get('/mex-admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');

    Route::get('/mex-admin/news/create', [App\Http\Controllers\NewsController::class, 'create'])->name('news.create');
    Route::get('/limits', function () {
        return [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
    });


});
