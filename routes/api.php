<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::prefix('user')->group(function () {      
        Route::patch('{id}/{name}/{email}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'delete']);
        Route::get('{id}', [UserController::class, 'selectById']);
    });

    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/refresh', [AuthController::class, 'refresh']);
});

Route::post('/user', [UserController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);
