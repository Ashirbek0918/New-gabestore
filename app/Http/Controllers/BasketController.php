<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function getme(Request $request){
        return $request->user;
    }
}
