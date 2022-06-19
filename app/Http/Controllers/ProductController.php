<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\GenreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // All good
    public function create(Request $request){
        try {
            $this->authorize('create', Product::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create any Product!', 405);
        }
        $validator = Validator::make($request->all(), [
            'title' =>'required|unique:products,title|max:50',
            'title_img' => 'required|url',
            'rating' =>'required|numeric',
            'first_price' =>'required|numeric',
            'discount' =>'nullable|numeric',
            'discount_price' => 'nullable|numeric',
            'about' =>'nullable|string',
            'minimal_system' =>'nullable|',
            'recommended_system' =>'nullable|',
            'warn' =>'required|boolean',
            'warn_text' =>'nullable|',
            'screenshots' =>'required|',
            'trailers' =>'required|',
            'language' =>'required|string|max:255',
            'location' =>'nullable|string|max:255',
            'genre' =>'required|exists:genres,id',
            'developer_id' =>'required|exists:developers,id',
            'publisher_id' =>'required|exists:publishers,id',
            'platform' =>'required|string',
            'release' =>'required|'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $product = Product::where('title', $request->title)
        ->first();
        $genres = $request->genre;
        if($product){
            return ResponseController::error('The product is exists!', 422);
        }
        $product = Product::create([
            "title" => $request->title,
            "title_img" => $request->title_img,
            "rating" => $request->rating,
            "first_price" => $request->first_price,
            "discount" => $request->discount,
            "discount_price" => $request->first_price-($request->first_price*$request->discount)/100,
            "about" => $request->about,
            "minimal_system" => $request->minimal_system,
            "recommended_system" => $request->recommended_system,
            "warn" => $request->warn,
            "warn_text" => $request->warn_text,
            "screenshots" => $request->screenshots,
            "trailers" => $request->trailers,
            "language" => $request->language,
            "location" => $request->location,
            "publisher_id" => $request->publisher_id,
            "developer_id" => $request->developer_id,
            "platform" => $request->platform,
            "release" => $request->release,
        ]);
        foreach($genres as $genre){
            GenreProduct::create([
                "genre_id" => $genre,
                "product_id" => $product->id
            ]);
        }
        return ResponseController::success();
    }
    // need to be done
    public function all(Request $request){
        $orderBy = $request->orderBy; // expensive, inexpensice, popular, date, alphabet,
        $genre = $request->genre; // 1, 2, 3
        $price = $request->price;
         $products = Product::when($orderBy, function ($query, $orderBy){
            if($orderBy == 'inexpensive'){
                $query->orderBy('first_price', 'desc');
            }elseif($orderBy == 'expensive'){
                $query->orderBy('first_price', 'asc');
            }elseif($orderBy == 'alphabet'){
                $query->orderBy('title', 'asc');
            }elseif($orderBy == 'date'){
                $query->orderBy('created_at', 'desc');
            }elseif($orderBy == 'popular'){
                $query->orderBy('purchased_games', 'desc');
            }
         });
         return $products;
    }
    // need to be  modified
    public function show(){
        $products = Product::paginate(20);
        $collection = [
            "last_page" => $products->lastPage(),
            "products" => [],
        ];
        if(!$products){
            return ResponseController::error('There is no product available', 404);
        }
        foreach($products as $product){
            $comments = Comment::where('id', $product->id)->get();
            $collection["products"][] = [
                "product_id" => $product->id,
                "product_title" => $product->title,
                "product_img" => $product->title_img,
                "product_price" => $product->first_price,
                "product_discount" => $product->discount ?? 0,
                "discount_price" => $product->discount_price ?? 0,
                "comments" => $comments ?? 0,
                "developer_id" => $product->developer_id,
                "publisher_id" => $product->publisher_id,
                "genre_ids" => $product->genre
            ];
        }
        return ResponseController::data($collection);
    }
    // All good
    public function update(Product $product,Request $request){
        try {
            $this->authorize('update', Product::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to update any Product!', 405);
        }
        $validator = Validator::make($request->all(), [
            'title' =>'required|unique:products,title|max:50',
            'title_img' => 'required|url',
            'rating' =>'required|numeric',
            'first_price' =>'required|numeric',
            'discount' =>'nullable|numeric',
            'discount_price' => 'nullable|numeric',
            'about' =>'nullable|string',
            'minimal_system' =>'nullable|',
            'recommended_system' =>'nullable|',
            'warn' =>'required|boolean',
            'warn_text' =>'nullable|',
            'screenshots' =>'required|',
            'trailers' =>'required|',
            'language' =>'required|string|max:255',
            'location' =>'nullable|string|max:255',
            'developer_id' =>'required|exists:developers,id',
            'publisher_id' =>'required|exists:publishers,id',
            'platform' =>'required|string',
            'release' =>'required|'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $product->update($request->all());
        return ResponseController::success();
    }
    // All good
    public function delete($product_id){
        try{
            $this->authorize('delete', Product::class);
        }catch(\Throwable $th){
            return ResponseController::error('You are not permitted to delete any Product!', 405);
        }
        $product = Product::find($product_id);
        if(!$product){
            return ResponseController::error('No product is found here to delete!', 404);
        }
        Comment::where('product_id', $product_id)->delete();
        $product->delete();
        return ResponseController::success();
    }
    // All good
    public function history(){
        try {
            $this->authorize('view', Product::class);
        } catch (\Throwable $th) {
            return ResponseController::error("You are not allowed to see deleted products",405);
        }
        $products = Product::onlyTrashed()->get(['id', 'title', 'title_img', 'first_price', 'discount', 'discount_price']);
        if(count($products) == 0){
            return ResponseController::error('No deleted porducts has found ', 404);
        }
        return ResponseController::data($products);
    }
    public function restore($product_id){
        $product = Product::withTrashed()->find($product_id);
        if($product->trashed()){
            $product->restore();
            $comments = $product->comments()->restore();
            return ResponseController::success();
        }
        return ResponseController::error('No product found to delete!', 404);
    }
    // ALL good
    public function genre(Genre $genre){
        $product_ids = GenreProduct::where('genre_id', $genre->id)->get('product_id');
        $products = [];
        if(!$product_ids){
            return ResponseController::error('No product found there!', 404);
        }
        foreach($product_ids as $id){
            // $comments = Comment::where('product_id', $id['product_id'])->get();
            $product = Product::where('id', $id['product_id'])->first();
            $products[] = [
                "product_id" => $product->id,
                "product_title" => $product->title,
                "title_img" => $product->title_img,
                "price" => $product->first_price,
                "discount" => $product->discount ?? 0,
                "current_price" => $product->discount ?? 0,
                "purchased_games" => $product->purchased_games,
                "developers" => $product->developer_id,
                "publisher" => $product->publisher_id,
                // "commments" => $product->commments


            ];
        }
        return ResponseController::data($products);
    }
    // ALL good
    public function developer($developer_id){
        $developer = Developer::find($developer_id);
        if(!$developer ){
            return ResponseController::error('No developer is found', 404);
        }
        $products = $developer->products()->get(['id', 'title', 'title_img', 'first_price', 'discount', 'discount_price']);
        if(!$products){
            return ResponseController::error('No product is found from the Developer!', 404);
        }
        $developer["products"] = $products;
        return ResponseController::data($developer);
    }
    // All good
    public function publisher($publisher_id){
        $publisher = Publisher::find($publisher_id);
        if(!$publisher){
            return ResponseController::error('No publisher is found!', 404);
        }
        $products = $publisher->products()->get(['id', 'title', 'title_img', 'first_price', 'discount', 'discount_price']);
        if(!$products){
            return ResponseController::error('No product is found from this Pulbisher', 404);
        }
        $publisher["products"] = $products;
        return ResponseController::data($publisher);
    }
    // All good
    public function product_comments(Product $product){
        $comments = $product->comments()->paginate(30);
        $collection = [
            "last_page" => $comments->lastPage(),
            "comments" => [],
        ];
        if(count($comments) == 0){
            return ResponseController::error('No comments are found by the product', 404);
        }
        foreach($comments as $comment){
            $collection["comments"][] = [
                "title" => $comment->title,
                "user_id" => [
                    "user_id" => $comment->user->id,
                    "name" => $comment->user->name,
                ],
                "product_id" => $comment->product_id ?? 0,
                "news_id" => $comment->news_id ?? 0,
                "status" => $comment->status,
                "created_at" => $comment->created_at,
            ];
        }
        return ResponseController::data($collection);
    }
    // All good
    public function lastAdded(){
        $products = Product::orderBy('created_at', 'desc')->take(10)->get(['id', 'title', 'title_img', 'first_price', 'discount', 'discount_price']);
        return ResponseController::data($products);

    }
    // All good
    public function orderByRating(){
        $products = Product::orderBy('rating', 'desc')->take(10)->get(['id', 'title', 'title_img', 'first_price', 'discount', 'discount_price']);
        return ResponseController::data($products);
    }
    // All good
    public function orderByDiscount(){
        $products = Product::orderBy('discount', 'desc')->take(10)->get(['id', 'title', 'title_img', 'first_price', 'discount', 'discount_price']);
        return ResponseController::data($products);
    }
    // All good
    public function orderByPurchasedGames(){
        $products = Product::orderBy('purchased_games', 'desc')->take(10)->get(['id', 'title', 'title_img', 'first_price', 'discount', 'discount_price']);
        return ResponseController::data($products);
    }
    // All good
    public function orderByPrice(){
        $product = Product::orderBy('first_price', 'desc')->take(10)->get(['id', 'title', 'title_img', 'first_price', 'discount', 'discount_price']);
        return ResponseController::data($product);
    }
}
