<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('area', AreaController::class)->except(['index', 'show']);

    Route::apiResource('course', CourseController::class)->except(['index', 'show']);

    Route::post('enrollment', [EnrollmentController::class, 'enroll']);

    Route::post('rating', [RatingController::class, 'rate']);
});

Route::apiResource('area', AreaController::class)->only(['index', 'show']);

Route::apiResource('course', CourseController::class)->only(['index', 'show']);

Route::post('course/filter', [CourseController::class, 'filter']);

Route::post('login', [AuthController::class, 'login']);
