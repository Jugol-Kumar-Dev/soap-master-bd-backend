<?php

use App\Http\Controllers\API\DependencyController;
use App\Http\Controllers\API\V1\BlogController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\CourseController;
use App\Http\Controllers\API\V1\LessonController;
use App\Http\Controllers\API\V1\MockTestController;
use App\Http\Controllers\API\V1\StripeController;
use App\Http\Controllers\API\V1\StudentController;
use App\Http\Controllers\API\V1\SupportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [StudentController::class, 'loginStudent'])->name('api.login');
Route::post('/create', [StudentController::class, 'saveStudent']);
Route::get('/verified-email-notification', [SupportController::class, 'verifiedEmail'])->name('api.verifiedEmail');
Route::post('/forgot-password', [StudentController::class, 'sendForgotPasswordReqs']);
Route::get('/forgot-password-notification', [SupportController::class, 'checkForgotPassword'])->name('api.forgotPasswordEmail');
Route::post('/save-new-password', [SupportController::class, 'saveNewChangedPassword'])->name('api.saveNewChangedPassword');




Route::middleware('auth:sanctum')->group(function(){
    Route::any('/logout', [StudentController::class, 'logoutStudent']);

    Route::get('/read-lesson/{id}', [StudentController::class, 'readLesson']);

    Route::get('/verification-resend/{email}', [SupportController::class, 'refendEmail'])->name('resend.email');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('student')->group(function (){
        Route::get('/enrolled-courses', [CourseController::class, 'index']);
        Route::get('/my-courses', [CourseController::class, 'myCourses']);
        Route::get('/courses/{id}', [CourseController::class, 'singleCourse']);

        Route::get('/course/content/{id}', [CourseController::class, 'courseContent']);

        Route::get('/course/zooms/{course_id}', [CourseController::class, 'courseZoomClasses']);

        Route::get('/courses/files/{id}', [CourseController::class, 'singleFile']);
        Route::get('/my-mock-tests/{student_id}', [MocktestController::class, 'index']);

        Route::get('/single-mock-tests/{id}', [MocktestController::class, 'singleMocktest']);

        Route::post("/check-mock-tests-password/{id}", [MockTestController::class, 'checkMockPassword']);
        Route::get("/mock-questions/{id}", [MockTestController::class, 'mockQuestions']);

        Route::post('/save-mock-exam', [MockTestController::class, 'saveMockExam']);

        Route::get('/mocktest-result/{id}', [MockTestController::class,'showResult']);

    });

    Route::apiResource('category', CategoryController::class);
});



Route::apiResource('course', CourseController::class)->only(['index']);
Route::apiResource('lesson', LessonController::class)->only(['index']);

