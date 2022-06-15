<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function create(Request $request ){
        $validator = Validator::make($request->all(), [
            "title" => 'required',
            "user_id" => 'required|exists:users,id',
            "product_id" => 'nullable',
            "news_id" => 'nullable'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $comment = Comment::where('title', $request->title)
        ->first();
        if($comment){
            return ResponseController::error('Comment is already written!', 403);
        }
        Comment::create([
            "title" => $request->title,
            "user_id" => $request->user_id,
            "product_id" => $request->product_id ?? 0,
            "news_id" => $request->news_id ?? 0,
        ]);
        return ResponseController::success();
    }
}
