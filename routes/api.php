<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [UserController::class, 'login']);

Route::prefix('user')->group(function () {
    Route::post('', [UserController::class, 'create']);
    Route::patch('{id}/{name}/{email}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'delete']);
    Route::get('{id}', [UserController::class, 'selectById']);
});