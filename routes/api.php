<?php
use App\Models\Comment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\PromocodeController;
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
        Route::get('news/onenews/{news}','singleoneNews');
        Route::get('allNews','allnews');
        Route::get('news/comments/{news}','comments');
    });

    //favourites
    Route::controller(FavouriteController::class)->group(function(){
        Route::post('favourites/create','create');
        Route::delete('favourites/delete/{product_id}','delete');
        Route::get('favourites','favourites');
    });


    //order
    Route::controller(OrderController::class)->group(function(){
        Route::post('order/create','create');
        Route::delete('order/delete/{order}','delete');
    });

    //basket
    Route::controller(BasketController::class)->group(function(){
        Route::get('allbaskets','allBaskets');
        Route::get('basket','basket');
        Route::delete('basket/delete/{basket}','delete');
        Route::post('basket/pay/{basket}','pay');
    });


    // developers
    Route::controller(DeveloperController::class)->group(function(){
        Route::post('/developer/create', 'create');
        Route::get('/developer/show/{developer}', 'showSingeDeveloper');
        Route::get('/developer/all', 'showAll');
        Route::put('/developer/{developer}', 'update');
        Route::delete('/developer/{developer}', 'delete');
        Route::get('/developer/history', 'history');
        Route::get('/developer/restore', 'restore');
    });

    // products
    Route::controller(ProductController::class)->group(function(){
        Route::post('product/create', 'create');
        Route::get('product/all/show', 'all');
        Route::get('product/show', 'show');
        Route::put('product/{product}', 'update');
        Route::delete('product/delete/{product}', 'delete');
        Route::get('product/history', 'history');
        Route::get('product/restore/{product}', [ProductController::class, 'restore']);
        Route::get('product/genre/{genre}', 'genre');
        Route::get('product/developer/{developer}', 'developer');
        Route::get('product/publisher/{publisher}', 'publisher');
        Route::get('product/comments/{product}', 'product_comments');
        Route::get('product/last/added', 'lastAdded');
        Route::get('product/order/rating', 'orderByRating');
        Route::get('product/order/discount', 'orderByDiscount');
        Route::get('product/order/games', 'orderByPurchasedGames');
        Route::get('product/order/price', 'orderByPrice');
    });

    //genres
    Route::controller(GenreController::class)->group(function(){
        Route::post('/genre/create', 'create');
        Route::get('/genre/show', 'show');
        Route::put('/genre/{genre}', 'update');
        Route::delete('/genre/delete/{genre}', 'delete');
        Route::get('/genre/history', 'history');
        Route::get('/genre/restore', 'restore');
    });

    //comments
    Route::controller(CommentController::class)->group(function(){
        Route::post('/comment', 'create');
        Route::get('/comment/product', 'productComments');
        Route::get('/comment/news', 'newsComments');
        Route::delete('/comment/delete/{comment}', 'delete');
        Route::get('/comment/history', 'history');
        Route::get('/comment/restore', 'restore');
        Route::get('/comment/update/{comment}', 'update');
        Route::get('/comment/add/point', 'addPoint');
    });

    //publishers
    Route::controller(PublisherController::class)->group(function(){
        Route::post('/publisher/create', 'create');
        Route::get('/publisher/show', 'show');
        Route::put('/publisher/update', 'update');
        Route::delete('/publisher/{publisher}', 'delete');
        Route::get('/publisher/restore', 'restore');
    });

    // promocode
    Route::controller(PromocodeController::class)->group(function(){
        Route::post('/promocode/create', 'create');
        Route::get('/promocode/show', 'show');
        Route::delete('/promocode/delete/{promocode}', 'delete');
        Route::put('/promocode/update/{promocode}', 'update');
    });

    // image
    Route::controller(ImageController::class)->group(function(){
        Route::post('/image/upload', 'upload');
        Route::delete('/image/delete/{fileName}', 'deleteImage');
    });
});
