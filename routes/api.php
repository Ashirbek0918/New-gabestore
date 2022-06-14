<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DeveloperController;


Route::post('/product/create', [ProductController::class, 'create']);

Route::post('/developer/create', [DeveloperController::class, 'create']);
Route::get('/developer/show/{developer}', [DeveloperController::class, 'showById']);
Route::get('/developer/all', [DeveloperController::class, 'showAll']);
Route::get('/developer/update', [DeveloperController::class, 'update']);
Route::get('/developer/delete', [DeveloperController::class, 'delete']);
