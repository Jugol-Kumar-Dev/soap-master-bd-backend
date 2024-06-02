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




Route::get('/api/delete', function (){
    $filePath = base_path("routes/new.php");
    if (File::exists($filePath)) {
        return response()->download($filePath)->deleteFileAfterSend(true);
    } else {
        return response()->json(['error' => 'Model file not found'], 404);
    }
});


Route::middleware('auth:sanctum')->group(function(){
    Route::get('/logout', [StudentController::class, 'logoutStudent']);
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




Route::get('/student/courses/{id}', [CourseController::class, 'singleCourse']);
Route::get('/course/module/{id}', [CourseController::class, 'courseModules']);


Route::apiResource('category', CategoryController::class);
Route::apiResource('course', CourseController::class);
Route::apiResource('lesson', LessonController::class);

Route::get('/get-counter', [SupportController::class, 'getSliders']);

Route::post('/support', [SupportController::class, 'store']);

Route::get('/promos', [\App\Http\Controllers\API\V1\PromoController::class, 'index']);
Route::get('/promos/{id}', [\App\Http\Controllers\API\V1\PromoController::class, 'show']);

Route::get('/sliders', [\App\Http\Controllers\API\V1\SliderController::class, 'index']);


Route::post('subbycat', [DependencyController::class, 'subCategoryByCategory'])->name('subbycat');
Route::post('subqbycat', [DependencyController::class, 'subQuestionByCategory'])->name('subqbycat');
Route::post('childbysubcat', [DependencyController::class, 'childCategoryBySubCategory'])->name('childbysubcat');

Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blog/{id}', [BlogController::class, 'show']);

Route::get('/get-faqs', [SupportController::class, 'getFaqs']);

Route::get('/get-settings', [SupportController::class, 'getSettings']);
Route::get('/get-settings/header-pages', [SupportController::class, 'navPages']);
Route::get('/get-settings/footer-pages', [SupportController::class, 'footPages']);
Route::get('/single-page/{slug}', [SupportController::class, 'singlePage']);
Route::get('/reviews', [SupportController::class, 'reviews']);

Route::post('/checkout', [StripeController::class, 'checkout'])->name('checkout');
Route::post('/verify', [StripeController::class, 'verify'])->name('verify');
Route::get('/success', [StripeController::class, 'index'])->name('success');
Route::get('/cancel', [StripeController::class, 'cancel'])->name('cancel');
