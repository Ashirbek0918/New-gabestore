<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

class DeveloperController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create any Developer here!');
        }
        $validator = Validator::make($request->all(), [
            "title" => 'required|string',
            "image" => 'required|url',
            "logotip" => 'required|url',
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first(), 422);
        }
        $developer = Developer::where('title', $request->title)->first();
        if($developer){
            return ResponseController::error('This developer is exists', 422);
        }
        Developer::create([
            "title" => $request->title,
            "image" => $request->image,
            "logotip" => $request->logotip,
        ]);
        return ResponseController::success();
    }
    public function showById(Developer $developer){
        return ResponseController::data($developer);
    }
    public function showAll(){
        $developers = Developer::paginate(20);
        $collection = [
            "last_page" => $developers->lastPage(),
            "developers" => [],
        ];
        foreach($developers as $developer){
            $collection["developers"][] = [
                "developer_id" => $developer->id,
                "title" => $developer->title,
                "image" => $developer->image,
                "logotip" => $developer->logotip,
            ];
        }
        return ResponseController::data($collection);
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            "title" => 'required',
            "image" => 'required|url',
            "logotip" => 'required|url',
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $developer = Developer::find($request->developer_id);
        $developer->update([
            "title" => $request->title,
            "image" => $request->image,
            "logotip" => $request->logotip,
        ]);
        return ResponseController::success();
    }
    public function delete(Request $request){
        $developer_id = $request->id;
        if(!$developer_id){
            return ResponseController::error('There is no such Developer');
        }
        $developer = Developer::where('id', $developer_id)->first();
        $product = Product::where('developer_id', $developer_id)->get();
        $developer->delete();
        $product->delete();
        return ResponseController::success();
    }
}
