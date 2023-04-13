<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Secured\ProductCategoryController;
use App\Http\Controllers\API\Secured\ProductController;
use App\Http\Controllers\API\Secured\UserController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'validateBearerToken'])->group(function () {
    Route::get('logged-in-user', [UserController::class, 'getLoggedInUser']);
    Route::resource('users', UserController::class);
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);
    Route::delete('logout', [AuthController::class, 'logout']);
});
