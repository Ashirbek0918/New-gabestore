<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DeveloperController;


Route::post('/register',[AuthController::class,'register']);
Route::get('/login',[AuthController::class,'login']);
Route::get('employee/login',[AuthController::class,'employeeLogin']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('getme',[AuthController::class,'getme']);
    Route::get('logout',[AuthController::class,'logOut']);

    Route::post('/developer/create', [DeveloperController::class, 'create']);
    Route::get('/developer/show/{developer}', [DeveloperController::class, 'showSingeDeveloper']);
    Route::get('/developer/all', [DeveloperController::class, 'showAll']);
    Route::get('/developer/update', [DeveloperController::class, 'update']);
    Route::get('/developer/delete', [DeveloperController::class, 'delete']);
    Route::get('/developer/restore', [DeveloperController::class, 'restore']);

    Route::post('product/create', [ProductController::class, 'create']);
    Route::get('product/show', [ProductController::class, 'show']);
    Route::put('product/update', [ProductController::class, 'update']);
});
