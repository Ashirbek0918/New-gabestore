<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\PublisherController;


Route::post('/register/user',[AuthController::class,'register']);
Route::get('/login',[AuthController::class,'login']);
Route::get('employee/login',[AuthController::class,'employeeLogin']);

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
    Route::get('getme',[AuthController::class,'getme']);
    Route::get('logout',[AuthController::class,'logOut']);

    Route::post('/developer/create', [DeveloperController::class, 'create']);
    Route::get('/developer/show/{developer}', [DeveloperController::class, 'showSingeDeveloper']);
    Route::get('/developer/all', [DeveloperController::class, 'showAll']);
    Route::put('/developer/{developer}', [DeveloperController::class, 'update']);
    Route::delete('/developer/{developer}', [DeveloperController::class, 'delete']);
    Route::get('/developer/history', [DeveloperController::class, 'history']);
    Route::get('/developer/restore', [DeveloperController::class, 'restore']);

    Route::post('product/create', [ProductController::class, 'create']);
    Route::get('product/show', [ProductController::class, 'show']);
    Route::put('product/update', [ProductController::class, 'update']);
    Route::delete('product/delete/{product}', [ProductController::class, 'delete']);
    Route::get('product/history', [ProductController::class, 'history']);
    Route::get('product/restore/{product}', [ProductController::class, 'restore']);
    Route::get('product/genre/{genre}', [ProductController::class, 'genre']);
    Route::get('product/developer/{developer}', [ProductController::class, 'developer']);
    Route::get('product/publisher/{publisher}', [ProductController::class, 'publisher']);

    Route::post('/genre/create', [GenreController::class, 'create']);
    Route::get('/genre/show', [GenreController::class, 'show']);
    Route::put('/genre/{genre}', [GenreController::class, 'update']);
    Route::delete('/genre/delete/{genre}', [GenreController::class, 'delete']);
    Route::get('/genre/history', [GenreController::class, 'history']);
    Route::get('/genre/restore', [GenreController::class, 'restore']);

    Route::post('/comment', [CommentController::class, 'create']);

    Route::post('/publisher/create', [PublisherController::class, 'create']);
    Route::get('/publisher/show', [PublisherController::class, 'show']);
    Route::put('/publisher/update', [PublisherController::class, 'update']);
    Route::delete('/publisher/{publisher}', [PublisherController::class, 'delete']);
    Route::get('/publisher/restore', [PublisherController::class, 'restore']);
});
