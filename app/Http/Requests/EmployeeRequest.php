<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' =>'required|string',
            'email' =>'required|string|unique:employees,email',
            'password' =>'required|string|min:6',
            'role' =>'required'
        ];
    }
}
