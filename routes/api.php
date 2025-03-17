<?php
use App\Http\Controllers\SiteAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\HeroSectionController;
use App\Http\Controllers\AuctionDetailsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LatestNewsController;
use App\Http\Controllers\FeaturedSellerController;
use App\Http\Controllers\ProductController;
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

/* web site auction auth */
Route::post('/site/login', [SiteAuthController::class, 'login']);
Route::post('/site/register', [SiteAuthController::class, 'register']);
Route::middleware('auth:api')->get('/clients', [SiteAuthController::class, 'getAllClients']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/site/logout', [SiteAuthController::class, 'logout']);
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

Route::prefix('v1/customers')->controller(CustomerController::class)->group(callback: function () {
    Route::get('', 'index');
    Route::get('show/{id}', 'show');
    Route::post('create', 'create');
    Route::delete('delete/{id}', 'delete');
    Route::post('update/{id}', 'update');
});

Route::prefix('v1/LatestNews')->controller(LatestNewsController::class)->group(callback: function () {
    Route::get('', 'index');
    Route::get('show/{id}', 'show');
    Route::post('create', 'store');
    Route::delete('delete/{id}', 'kill');
    Route::post('update/{id}', 'update');
});

Route::prefix('v1/featured-sellers')->controller(FeaturedSellerController::class)->group(callback: function () {
    Route::get('', 'index');
    Route::post('create', 'create');
    Route::get('show/{id}', 'show');
    Route::post('update/{id}', 'update');
    Route::delete('delete/{id}', 'kill');
});

Route::prefix('v1/faqs')->controller(FaqController::class)->group(callback: function () {
    Route::get('', 'index');
    Route::post('create', 'store');
    Route::get('showSingleFaq/{id}', 'showSingleFaq');
    Route::post('update/{id}', 'update');
    Route::delete('delete/{id}', 'kill');
});

Route::prefix('v1/hero-section')->controller(HeroSectionController::class)->group(function () {
    Route::post('create', 'create');
    Route::get('show', 'index');
    Route::post('update/{id}', 'update');
});

Route::prefix('v1/category')->controller(CategoryController::class)->group(function () {
    Route::get('show', 'index');
    Route::get('singleCategory/{id}', 'showSingleCategory');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::delete('delete/{id}', 'kill');
});

Route::prefix('v1/auctions')->controller(AuctionController::class)->group(function () {
    Route::get('show', 'index');
    Route::get('showSingleAuction/{id}', 'showSingleAuction');
    Route::post('create', 'create');
    Route::post('Bidding/{auctionId}/place-bid', 'placeBid');
    Route::post('update/{id}', 'update');
    Route::delete('delete/{id}', 'kill');
});

Route::prefix('v1/auctions-details')->controller(AuctionDetailsController::class)->group(function () {
    Route::post('create', 'create');
});

Route::prefix('v1/products')->controller(ProductController::class)->group(function () {
    Route::get('', 'index');
    Route::get('{id}', 'show');

    Route::post('create', 'store');
    Route::post('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
