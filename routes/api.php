<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\RatingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('area', AreaController::class);

Route::apiResource('course', CourseController::class);

Route::post('course/filter', [CourseController::class, 'filter']);

Route::put('enrollment', [EnrollmentController::class, 'enroll']);

Route::post('rating', [RatingController::class, 'rate']);

Route::get('course/{course}/ratings', [RatingController::class, 'getCourseRatings']);
