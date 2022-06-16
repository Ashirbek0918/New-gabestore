<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/employee/login',[EmployeeController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('getme',[AuthController::class,'getme']);
    Route::get('logout',[AuthController::class,'logOut']); 
    Route::put('update/{user}',[AuthController::class,'update']);
    Route::get('allusers',[UserController::class,'allUsers']);
    Route::get('popularusers',[UserController::class,'orderbyPoint']);
    Route::get('user/{user}',[UserController::class,'singleUser']);

    //employee
    Route::controller(EmployeeController::class)->group(function(){
        Route::post('employee/create','create');
        Route::delete('employee/delete/{employee}','destroy');
        Route::put('employee/update/{employee}','update');
    });

    //news
    Route::controller(NewsController::class)->group(function(){
        Route::post('news/create','create');
        Route::put('news/update/{news}','update');
        Route::delete('news/delete/{news}','destroy');
        Route::get('news/onenews/{news}','singleNews');
        Route::get('allNews','allnews');
        Route::get('news/comments/{news}','comments');
    });

    //favourites
    Route::controller(FavouriteController::class)->group(function(){
        Route::post('favourites/create','create');
        Route::delete('favourites/delete/{product_id}','delete');
        Route::get('favourites','favourites');
    });
});
