<?php

namespace App\Observers;

use App\Http\Controllers\ResponseController;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    public function creating(Order $order){
        $product = $order->product ;
        $order->activation_code = Str::uuid();
        $order->price = $product->first_price;
        $order->discount = $product->discount ?? 0;
        $order->discount_price = $product->discount_price;
    }
    public function created(Order $order)
    {
        $basket = $order->basket;
        $price = ($order->discount_price)+$basket->price;
        $basket->update([
            'price'=> $price
        ]);
    }

    public function deleted(Order $order)
    {
        $basket = $order->basket;
        $price = ($basket->price) - $order->discount_price;
        $basket->update([
            'price'=> $price,
        ]);
    }

   
}
