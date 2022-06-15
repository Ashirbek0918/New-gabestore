<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
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
            "purchased_games" => $request->purchased_games,
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
            "genre" => $request->genre,
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
    //All good
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
    // Need to check
    public function update(Request $request){
        try {
            $this->authorize('update', Product::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to update any Product!', 405);
        }
        $validator = Validator::make($request->all(), [
            'itle' =>'required|unique:products,title|max:50',
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
        $product = Product::find($request->product_id);
        if(!$product){
            return ResponseController::error('There is no Product to update!', 404);
        }
        $product->update([
            "title" => $request->title,
            "title_img" => $request->title_img,
            "rating" => $request->rating,
            "first_price" => $request->first_price,
            "discount" => $request->discount,
            "discount_price" => $request->discount_price,
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
            "genre" => $request->genre,
            "platform" => $request->platform,
            "release" => $request->release,
        ]);
    }
}
