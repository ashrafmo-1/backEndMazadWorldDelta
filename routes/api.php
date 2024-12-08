<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

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

// Protected Routes not used
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('v1/users')->controller(UserController::class)->group(function () {
    Route::get('getAllUsers', 'index');
    Route::get('singleUser/{id}', 'showSingleUser');
    Route::post('create', 'create');
    Route::post('update/{id}', 'updateUser');
    Route::delete('delete/{id}', 'delete');
});

Route::prefix('v1/category')->controller(CategoryController::class)->group(function () {
    Route::get('show', 'index');
    Route::get('singleCategory/{id}', 'showSingleCategory');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::delete('delete/{id}', 'kill');
});

// Route::prefix('v1/auctions')->


Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});