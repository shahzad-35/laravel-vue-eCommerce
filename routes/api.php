<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
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

Route::middleware(['auth:sanctum', 'admin'])->controller(AuthController::class)->group(function () {
    Route::get('/user', 'getUser');
    Route::post('/logout', 'logout');

    Route::apiResource('products', ProductController::class);
});
Route::post('/login', [AuthController::class, 'login']);
