<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Basket;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(OrderRequest $orderRequest){
        $user = $orderRequest->user();
        $basket = $user->basket()->where('status','not_purchased')->first();
        $product_id = $orderRequest->product_id;
        if($basket){
            $order = $basket->orders()->where('product_id',$product_id)->first();
            if($order){
                return ResponseController::error('This product already exists',403);
            }
        }
        if(!$basket){
            $basket = Basket::create([
                'user_id' =>$user->id,
                'ordered_at'=>Carbon::now()
            ]);
            Order::create([
                'basket_id'=>$basket->id,
                'product_id' =>$product_id
            ]);
        }else{
            Order::create([
                'basket_id'=>$basket->id,
                'product_id' =>$product_id
            ]);
        }
        return ResponseController::success('succcesfuly created',201);
    }

    public function delete(Order $order){
        $order->delete();
        return ResponseController::success('Successfuly deleted',202);
    }
}
