<?php

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

Route::apiResource('/akar-kuadrat-api', App\Http\Controllers\Api\AkarApiController::class);
Route::apiResource('/akar-kuadrat-plsql', App\Http\Controllers\Api\AkarPlsqlController::class);

Route::get('/data-akar', [App\Http\Controllers\Api\DataController::class, 'index']);
Route::get('/data-akar-api', [App\Http\Controllers\Api\DataController::class, 'dataApi']);
Route::get('/data-akar-plsql', [App\Http\Controllers\Api\DataController::class, 'dataPlsql']);
Route::get('/data-user', [App\Http\Controllers\Api\DataController::class, 'dataUser']);

