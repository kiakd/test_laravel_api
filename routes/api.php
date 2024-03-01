<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginPage;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTestController;
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
Route::post("loginpage", [LoginPage::class,"login"]);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('product', ProductController::class);
Route::apiResource('customer', CustomerController::class);

// api test
Route::get("producttest", [ProductTestController::class,"ProductTest"]);
