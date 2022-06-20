<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' =>'required|string',
            'email' =>['required', Rule::unique('users')->ignore($this->user)],
            'password' =>'required|min:6',
            'profile_photo' =>'required|url'
        ];
    }
}
