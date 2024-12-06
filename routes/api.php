<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Protected Routes
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/refresh', [AuthController::class, 'ref aresh']);
    // Users
    Route::prefix('user')->controller(AuthController::class)->group(function () {
        Route::get('show', 'getAll');
        Route::get('showbyid/{id}', 'getbyId');
        Route::post('update/{id}', 'updateUser');
        Route::post('create', 'register');
        Route::delete('delete/{user_id}', 'remove');
    });
});

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// Users
Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('getAllUsers', 'index');
    Route::get('singleUser/{id}', 'showSingleUser');
    Route::post('create', 'create');
    Route::post('update', 'update');
    Route::delete('delete/{id}', 'delete');
});


// Products
Route::prefix('product')->controller(ProductsController::class)->group(function () {
    Route::post('create', 'create');
    Route::get('show', 'index');
    Route::get('showbyid/{id}', 'getbyId');
    Route::delete('delete/{id}', 'destroy');
});



// Protected Routes
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});