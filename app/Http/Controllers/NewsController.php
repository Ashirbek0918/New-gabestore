<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function create (NewsRequest $request){
        try{
            $this->authorize('create',News::class);
        }catch(\Throwable $th){
            return ResponseController::error('You Are not allowed');
        }
        News::create([
            'title' =>$request->title,
            'body' =>$request->body,
            'image' =>$request->image,
            'text' =>$request->text,
        ]);
    }

    public function update(NewsRequest $request, News $news){
        try{
            $this->authorize('update',News::class);
        }catch(\Throwable $th){
            return ResponseController::error('You Are not allowed');
        }
        $news->update($request->only([
            'title',
            'body',
            'image',
            'text'
        ]));
        return ResponseController::success('Successfuly updated');
    }

    public function destroy (News $news){
        try{
            $this->authorize('delete',News::class);
        }catch(\Throwable $th){
            return ResponseController::error('You Are not allowed');
        }
        $news->comments()->delete();
        $news->delete();
        return ResponseController::success('Successfuly deleted');
    }

    public function singleoneNews(News $news){
        foreach ($news as $new){
            $news['comments'] =  $new->comments()->count();
        }
        $news->increment('views');
        return ResponseController::data($news);
    }

    public function allnews(){
        $news = News::orderby('id','Desc')->paginate(10);
        if(empty($news)){
            return ResponseController::error('News not yet');
        }
        $final = [
            'last_page' =>$news->lastPage(),
            'news' =>[],
        ];
        foreach($news as $new){
            $final['news'][] = [
                'id' =>$new->id,
                'title' =>$new->title,
                'body' =>$new->body,
                'image' =>$new->image,
                'views' =>$new->views,
                'created_at' =>$new->created_at,
                'comments' =>$new->comments()->count()
            ];
        }
        return ResponseController::data($final);
    }

    public function comments(News $news){
        $comments = $news->comments();
        if(empty($comments)){
            return ResponseController::error('Comments not yet');
        }
        foreach ($comments as $comment){
            $comment['user'] = [
                'id' =>$comment->user()->id,
                'name' =>$comment->user()->name
            ];
        }
        return ResponseController::data($comments);
    }
}
