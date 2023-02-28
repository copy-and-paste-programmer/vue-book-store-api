<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegisterController;
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

Route::get('/hello', function () {
    return response('hello');
});

Route::middleware(['auth:api'])->group(function () {
    //Author
    Route::group(['prefix' => 'authors'], function () {
        Route::get('/', [AuthorController::class, 'index']);
        Route::post('/', [AuthorController::class, 'create']);
        Route::put('/{id}', [AuthorController::class, 'update']);
        Route::delete('/{id}', [AuthorController::class, 'destroy']);
    });

    // Category
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories/store', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Book
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
    Route::post('/books/rates/{id}', [BookController::class, 'rate']);

    //Order
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    
    Route::get('/carts-items', [CartItemController::class, 'index']);
    Route::post('/carts-items', [CartItemController::class, 'store']);
    Route::put('/carts-items/{id}', [CartItemController::class, 'update']);
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthController::class, 'authenticated']);
Route::post('/refresh', [AuthController::class, 'refresh']);
