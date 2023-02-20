<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CategoryController;

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

Route::get('/author/list' , [AuthorController::class , 'getAuthor']);

Route::post('/author/create' , [AuthorController::class , 'create']);

// Category
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories/store', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);


Route::prefix('upload')->name('upload.')->controller(UploadController::class)->group(function () {
    Route::post('images', 'images')->name('images');
});
