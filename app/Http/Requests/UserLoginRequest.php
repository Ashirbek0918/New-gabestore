<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name'=>'required|string',
            'email' =>'required|string',
            'password' =>'required|min:6',
            'profile_photo' =>'required|url'
        ];
    }
}
