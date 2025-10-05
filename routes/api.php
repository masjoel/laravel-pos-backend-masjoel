<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RegistrationController;

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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('change-password', [AuthController::class, 'changepassword']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
Route::apiResource('categories', CategoryController::class)->middleware('auth:sanctum');
Route::apiResource('orders', OrderController::class)->middleware('auth:sanctum');
Route::post('sync-products', [ProductController::class, 'syncProducts'])->middleware('auth:sanctum');
Route::post('savedeviceid', [AuthController::class, 'savedeviceid'])->middleware('auth:sanctum');
Route::get('prospect/{id}', [AuthController::class, 'prospect'])->middleware('auth:sanctum');
Route::get('marketing', [AuthController::class, 'marketing']);
Route::post('/register', [RegistrationController::class, 'store']);
Route::post('/register-reseller', [RegistrationController::class, 'storeReseller']);
Route::post('/data-reseller', [RegistrationController::class, 'dataReseller']);