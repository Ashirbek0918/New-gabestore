<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        Comment::create([
            "title" => $request->title,
            "user_id" => $request->user_id,
            "product_id" => $request->product_id ?? 0,
            "news_id" => $request->news_id ?? 0,
        ]);
        return ResponseController::success();
    }
    public function productComments(){
        $comments = Comment::whereNotNull('product_id')
        ->where('status', 'unchecked')
        ->orderBy('created_at', 'desc')
        ->paginate(30);
        if(empty($comments)){
            return ResponseController::error('No comment has found', 404);
        }
        $collection = [
            "last_page" => $comments->lastPage(),
            "comments" => [],
        ];
        foreach($comments as $comment){
            $collection["comments"][] = [
                "id" => $comment->id,
                "title" => $comment->title,
                "user" => [
                    "id" => $comment->user->id,
                    "name" => $comment->user->name,
                ],
                "created_at" => $comment->created_at,
            ];
        }
        return ResponseController::data($collection);
    }
    public function newsComments(){
        $comments = Comment::whereNotNull('news_id')
        ->orderBy('id','Desc')
        ->paginate(30);
        if(empty($comments)){
            return ResponseController::error('No comments found here',404);
        }
        $final = [
            'last_page' =>$comments->lastPage(),
            'comments' => []
        ];
        foreach ($comments as $comment){
            $final['comments'][] = [
                'id'=> $comment->id,
                'title' =>$comment->title,
                'user' => [
                    'id' =>$comment->user->id,
                    'name' =>$comment->user->name,
                ],
                'created_at' =>$comment->created_at,
            ];
        }
        return ResponseController::data($final);
    }
    public function delete($comment){
        $comment = Comment::find($comment);
        if(!$comment){
            return ResponseController::error('Comment not found to delete',404);
        }
        $comment->delete();
        return ResponseController::success();
    }
    public function history(){
        try{
            $this->authorize('view',Comment::class);
        }catch(\Throwable $th){
            return ResponseController::error('You are not allowed to see any deleted Comment here', 405 );
        }
        $comments = Comment::onlyTrashed()
        ->orderBy('deleted_at','Desc')
        ->get();
        if(count($comments)==0){
            return ResponseController::error('No deleted comments are found',404);
        }
        return ResponseController::data($comments);
    }
    public function restore(Request $request){
        $comment_id = $request->comment_id;
        $comment = Comment::onlyTrashed()
        ->where('id',$comment_id)
        ->first();
        if(!$comment){
            return ResponseController::error('There is no deleted comment to restore');
        }
        $comment->restore();
        return ResponseController::success();
    }
    public function update($comment_id, Request $request){
        $comment = Comment::find($comment_id);
        if(!$comment){
            return ResponseController::error('There is no comment to update', 404);
        }
        $comment->update($request->all());
        return ResponseController::success();;
    }
    public function addPoint(Request $request){
        $id = $request->comment_id;
        $comment = Comment::find($id);
        if($comment->status == 'unchecked'){
            $user = User::find($request->user_id);
            $user->update([
                'point' => $user->point+6,
            ]);
            $comment->update([
                'status' => 'checked'
            ]);
            return ResponseController::success();
        }else{
            return ResponseController::error('Comment is already checked');
        }


    }
}
