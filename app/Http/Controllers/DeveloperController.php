<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeveloperController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create any Developer!');
        }
        $validator = Validator::make($request->all(), [
            "title" => 'required',
            "image" => 'required|url',
            "logo_img" => 'required|url',
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $developer = Developer::where('title', $request->title)->first();
        if($developer){
            return ResponseController::error('The Developer is already exists', 422);
        }
        Developer::create([
            "title" => $request->title,
            "image" => $request->image,
            "logo_img" => $request->logo_img,
        ]);
        return ResponseController::success();
    }
    public function showSingeDeveloper(Developer $developer){
        return ResponseController::data($developer);
    }
    public function showAll(){
        $developers = Developer::paginate(10);
        $collection = [
            "last_page" => $developers->lastPage(),
            "developers" => [],
        ];
        foreach($developers as $developer){
            $collection["developers"][] = [
                "developer_id" => $developer->id,
                "developer_title" => $developer->title,
                "developer_image" => $developer->image,
                "developer_logotip" => $developer->logotip,
            ];
        }
        return ResponseController::data($collection);
    }
    public function update(Request $request){
        try {
            $this->authorize('update', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('Youn are not allowed to update a Developer!');
        }
        $validator = Validator::make($request->all(), [
            "id" => 'required|exists:developers,id',
            "title" => 'required',
            "image" => 'required|url',
            "logotip" => 'required|url'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $developer = Developer::find($request->id);
        if(!$developer){
            return ResponseController::error('There is no Developer', 404);
        }
        $developer->update([
            "id" => $request->id,
            "title" => $request->title,
            "image" => $request->image,
            "logotip" => $request->logotip,
        ]);
        return ResponseController::success();
    }
    public function delete(Request $request){
        try {
            $this->authorize('delete', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You do not have an access to delete any Developer!');
        }
        $developer = Developer::where('id', $request->developer_id)->first();
        if(!$developer){
            return ResponseController::error('There is no Developer with this ID', 404);
        }
        $developer->products()->delete();
        $developer->delete();
        return ResponseController::success();
    }
    public function restore(Request $request){
        $developer = Developer::withTrashed()->find($request->id);
        if($developer->trashed()){
            $developer->restore();
            return ResponseController::success();;
        }
        return ResponseController::error('No deleted developer is found!', 404);
    }
}
