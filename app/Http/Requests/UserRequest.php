<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'=>'required|string',
            'email' =>'required|string|unique:users,email',
            'password' =>'required|min:6',
            'profile_photo' =>'required|url'
        ];
    }
}
