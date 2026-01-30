<?php

use App\Http\Controllers\ApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['throttle:api'])->group(function () {
    Route::controller(ApiController::class)->group(function () {
        Route::group(['prefix' => 'app'], function() {
            Route::post('/create', 'createApp');
            Route::post('/webhook/process/run', 'webhookProcess');
            Route::group(['prefix' => 'payment'], function() {
                Route::post('/initialize', 'initializePayment');
                Route::get('/callback/{reference}', 'callBackHandling');
            });
        });
    });
});

