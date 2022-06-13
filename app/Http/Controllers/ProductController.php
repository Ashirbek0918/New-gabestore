<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ResponseController;

class ProductController extends Controller
{
    public function create(Request $request){
        $product = Product::where('title', $request->title)->first();
        if($product){
            return ResponseController::error('This product is exists', 422);
        }
        Product::create();
    }
}
