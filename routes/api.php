<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Resources\AuthorizationResource;
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

Route::prefix('v1')->group(function() {
    Route::get('/', function() {
        return response()->json(['message' => "OK!"], 200);
    });

    Route::post('register', [AuthorizationController::class, 'register']);
    Route::post('connect', [AuthorizationController::class, 'connect']);

    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/user', function (Request $request) {
            return new AuthorizationResource($request->user());
        });

        Route::post('disconnect', [AuthorizationController::class, 'disconnect']);

        Route::apiResource('product', ProductController::class);
        Route::apiResource('stock', StockController::class);
        Route::apiResource('category', CategoryController::class);
        Route::apiResource('image', ImageController::class);
        Route::apiResource('history', HistoryController::class);
    });
});
