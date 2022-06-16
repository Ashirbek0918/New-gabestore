<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FavouriteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' =>'required|exists:users,id',
            'product_id'=>'required|exists:products,id'
        ];
    }
}
