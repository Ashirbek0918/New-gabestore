<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Promocode;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function allBaskets(){
        $baskets = Basket::paginate(30);
        $final = [
            'last_page' =>$baskets->lastPage(),
            'baskets' => []
        ];
        foreach ($baskets as $basket){
            $final['baskets'][] = [
                'id' =>$basket->id,
                'user' =>[
                    'id' =>$basket->user->id,
                    'name' =>$basket->user->name,
                    'email' =>$basket->user->email,
                    'point' =>$basket->user->point,
                ],
                'status' =>$basket->status,
                'price' =>$basket->price,
                'discount' =>$basket->discount,
                'discount_price' =>$basket->discount_price,
                'ordered_at' =>$basket->created_at,
                'orders_count' =>$basket->orders()->count()
            ];
        }
        return ResponseController::data($final);
    }

    public function basket(Request $request){
        $user = $request->user();
        $basket  = $user->basket()->where('status','not_purchased')->first();
        if(!$basket){
            return ResponseController::error('Basket not yet');
        }
        $final = [];
        $final['basket_id'] = $basket->id;
        $final['orders'] = $basket->orders;
        return ResponseController::data($final);
    }

    public function delete(Basket $basket){
        $basket->orders()->delete();
        $basket->delete();
        return ResponseController::success('Successfuly deleted',201);
    }

    public function pay(Request $request,Basket $basket){
        $user  = $request->user();
        $orders = $basket->orders();
        try {
            if(!is_null($request->promocode)){
                $promocode = Promocode::where('promocode',$request->promocode)->first();
                $discount = $promocode->discount;
                $discount_price = $basket->price - ($basket->price*$discount/100);
                $promocode->decrement('count');
                if($promocode->count == 0){
                    $promocode->delete();
                }
            }
        } catch (\Throwable $th) {
            return ResponseController::error('No such promocode is available or is outdated');
        }
        if($basket->status != 'purchased'){
            $count = $basket->orders()->count();
            $point = $user->point + $count*30;
            $user->update([
                'buy_games_number' =>$user->buy_games_number +$count,
                'point' =>$point
            ]);
            $basket->update([
                'status' =>'purchased',
                'discount' =>$discount ?? 0,
                'discount_price' =>$discount_price ?? 0,
            ]);
            foreach ($orders as $order){
                $product = $order->product;
                $product->increment('buy_count');
            }
        }else{
            return ResponseController::error('Basket already payed');
        }
        return ResponseController::success('successfully payed');
    }
}
