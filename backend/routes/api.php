<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\TweetController;
use App\Models\Tweet;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    "prefix" => "/v1"
], function () {
    // v1 
    Route::group([
        "prefix" => "/auth",
        "controller" => AuthController::class
    ], function () {
        // v1 auth
        Route::post('/login', 'postLogin');
        Route::post('/register', 'postRegister');
        Route::post('/logout', 'postLogout')->middleware('auth:sanctum');
        Route::put('/update', 'updateMe')->middleware('auth:sanctum');
        Route::get('/me', 'getMe')->middleware('auth:sanctum');
    });

    Route::group([
        "middleware" => "auth:sanctum",
    ], function () {
        Route::get('/tweet/user', [TweetController::class, "user"]);
        Route::apiResource("/tweet", TweetController::class);

        Route::apiResource("/comment", CommentController::class)->except(['index']);
    
        Route::group([
            "prefix" => "/filter",
            "controller" => FilterController::class
        ], function () {
            Route::get("/", "getFilter");
            Route::get("/tweet", "getFilteredTweet");
        });
    });
});
