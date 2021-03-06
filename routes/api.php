<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function() {
    Route::post('/images', [ImageController::class, 'store']);

    Route::post('/order', [OrderController::class, 'create']);
    Route::get('/single/order/{order_id}', [OrderController::class, 'singleOrder']);
    Route::get('/view-orders', [OrderController::class, 'viewOrders']);

    Route::delete('/delete/basket/{basket_id}', [OrderController::class, 'deleteBasket']);
    Route::patch('/edit/basket/{basket_id}', [OrderController::class, 'editBasket']);
    Route::get('/history', [OrderController::class, 'completedHistory']);
    Route::get('/single/basket/{basket_id}', [OrderController::class, 'singleBasket']);
    Route::get('/all/baskets/{user_id}', [OrderController::class, 'allBasketsOfUser']);
    Route::get('/view-baskets', [BasketController::class, 'index']);

    Route::post('/make/room', [OrderController::class, 'makeRoom']);
    Route::patch('/edit/room/{room_id}', [OrderController::class, 'editRoom']);
    Route::delete('/delete/room/{room_id}', [OrderController::class, 'deleteRoom']);
    Route::get('/view-rooms', [OrderController::class, 'viewRooms']);

    Route::post('/create/category', [CategoryController::class, 'create']);
    Route::patch('/edit/category/{cat_id}', [CategoryController::class, 'edit']);
    Route::delete('/delete/category/{cat_id}', [CategoryController::class, 'delete']);
    
    Route::post('/create/event', [EventsController::class, 'create']);
    Route::patch('/edit/event/{event_id}', [EventsController::class, 'edit']);
    Route::delete('/delete/event/{event_id}', [EventsController::class, 'delete']);
    
    Route::post('/create/product', [ProductController::class, 'create']);
    Route::patch('/edit/product/{product_id}', [ProductController::class, 'edit']);
    Route::delete('/delete/product/{product_id}', [ProductController::class, 'delete']);
    
    Route::post('/users/logout', [AuthController::class, 'logout']);
    Route::post('/users/getme', [AuthController::class, 'getme']); 
    Route::get('/all/users', [AuthController::class, 'allUsers']);

    Route::post('/comment', [CommentsController::class, 'leaveComment']);
    Route::get('/comments', [CommentsController::class, 'index']);
    Route::get('/user/comments/{user_id}', [CommentsController::class, 'allUserComments']);
});

Route::post('/users/register', [AuthController::class, 'register']);
Route::post('/users/login', [AuthController::class, 'login']);

Route::get('/category', [CategoryController::class, 'view']);
Route::get('/event', [EventsController::class, 'view']);

Route::get('/product', [ProductController::class, 'view']);
Route::get('/product/{product_id}', [ProductController::class, 'singleProduct']);
Route::get('/category/product', [ProductController::class, 'showWithCat']);
Route::get('/recommended/products', [ProductController::class, 'iRecommend']);

Route::get('/single/user/{user_id}', [AuthController::class, 'singleUser']);
