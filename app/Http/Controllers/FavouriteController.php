<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Http\Requests\FavouriteRequest;

class FavouriteController extends Controller
{
    public function create(FavouriteRequest $request){
        $favourite = Favourite::where('user_id',$request->user_id)->where('product_id',$request->product_id)->first();
        if($favourite){
            return ResponseController::error('This favourite product already exits');
        }
        Favourite::create([
            'user_id' =>$request->user_id,
            'product_id' =>$request->product_id
        ]);
        return ResponseController::success('Successfuly aded');
    }

    public function delete(Request $request,$product_id){
        $favourite = Favourite::where('user_id',$request->user_id)->where('product_id',$product_id)->first();
       if(!$favourite){
        return ResponseController::error('This favourite not found');
       }
       $favourite->delete();
       return ResponseController::success('Successfuly deleted');
    }

    public function favourites(Request $request){
        $favourites = $request->user()->favourites()->get();
        if(count($favourites) == 0){
            return ResponseController::error('Favourite products not yet',404);
        }
        $final = [];
        foreach ($favourites as $favourite){
            $product = $favourite->products->first();
            $temp =[
                'id' =>$product->id,
                'title' =>$product->title,
                'title_img' =>$product->title_img,
                'first_price' =>$product->first_price,
                'discount' =>$product->discount,
                'second_price' =>$product->second_price,
            ];
            $final[]= $temp;
        }
        return ResponseController::data($final);
    }
}
