<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
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

Route::post('/images', [ImageController::class, 'store']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/create/category', [CategoryController::class, 'create'])->name('createCategory');
    Route::patch('/edit/category/{cat_id}', [CategoryController::class, 'edit']);
    Route::delete('/delete/category/{cat_id}', [CategoryController::class, 'delete']);

    Route::post('/create/event', [EventsController::class, 'create'])->name('createEvent');
    Route::patch('/edit/event/{event_id}', [EventsController::class, 'edit'])->name('edit');
    Route::delete('/delete/event/{event_id}', [EventsController::class, 'delete']);

    Route::post('/create/product', [ProductController::class, 'create'])->name('createProduct');
    Route::patch('/edit/product/{product_id}', [ProductController::class, 'edit']);
    Route::delete('/delete/product/{product_id}', [ProductController::class, 'delete']);
    Route::post('/users/logout', [AuthController::class, 'logout']);
    Route::post('/users/getme', [AuthController::class, 'getme']);
});


Route::post('/users/register', [AuthController::class, 'register']);
Route::post('/users/login', [AuthController::class, 'login']);

Route::prefix('/view')->group(function(){
    Route::get('/event', [EventsController::class, 'view']);
    Route::get('/category', [CategoryController::class, 'view']);
    
    Route::get('/product', [ProductController::class, 'view']);
    Route::get('/product/{product_id}', [ProductController::class, 'singleProduct']);
    Route::get('/category/product', [ProductController::class, 'showWithCat']);
});
