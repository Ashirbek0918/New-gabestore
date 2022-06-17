<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\ResponseController;

class NewsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'title' =>'required|string|max:255',
            'body' =>'required|string',
            'image' =>'required|url',
            'text' =>'nullable|string'
        ];
        return ResponseController::success('Successfuly created');
    }
}
