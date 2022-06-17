<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// No problem at all.
class DeveloperController extends Controller
{
    // All good
    public function create(Request $request){
        try {
            $this->authorize('create', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create any Developer!');
        }
        $validator = Validator::make($request->all(), [
            "title" => 'required',
            "image" => 'required|url',
            "logotip" => 'required|url',
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
            "logotip" => $request->logotip,
        ]);
        return ResponseController::success();
    }
    // All good
    public function showSingeDeveloper(Developer $developer){
        return ResponseController::data($developer);
    }
    //All good
    public function showAll(){
        $developers = Developer::paginate(10);
        $collection = [
            "last_page" => $developers->lastPage(),
            "developers" => [],
        ];
        foreach($developers as $developer){
            $count = $developer->products()->count();
            $collection["developers"][] = [
                "developer_id" => $developer->id,
                "developer_title" => $developer->title,
                "developer_image" => $developer->image,
                "developer_logotip" => $developer->logotip,
                "count" => $count
            ];
        }
        return ResponseController::data($collection);
    }
    // All good
    public function update($developer,Request $request){
        try{
            $this->authorize('update', Developer::class);
        }catch(\Throwable $th){
            return ResponseController::error('You are not allowed to update any Developer!', 405);
        }
        $developer = Developer::find($developer);
        if(!$developer){
            return ResponseController::error('Developer not found',404);
        }
        $validation = Validator::make($request->all(),[
            'title' =>'required|unique:developers,title',
            'image' =>'required|url',
            'logotip' =>'required|url',
        ]);
        if ($validation->fails()) {
            return ResponseController::error($validation->errors()->first(), 422);
        }

        $developer->update($request->all());
        return ResponseController::success();
    }
    // All good
    public function delete($developer, Request $request){
        try {
            $this->authorize('delete', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You do not have an access to delete any Developer!');
        }
        $developer = Developer::where('id', $developer)->first();
        if(!$developer){
            return ResponseController::error('There is no Developer with this ID', 404);
        }
        $developer->products()->delete();
        $developer->delete();
        return ResponseController::success();

    }
    // All good
    public function history(){
        try {
            $this->authorize('view', Developer::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You cannot see any Developer due to the authorization!', 405);
        }
        $developers = Developer::onlyTrashed()->get();
        if(count($developers) == 0){
            return ResponseController::error('No deleted developers has been found!', 404);
        }
        return ResponseController::data($developers);
    }
    // All good
    public function restore(Request $request){
        $developer = Developer::withTrashed()->find($request->id);
        if($developer->trashed()){
            $developer->products()->restore();
            $developer->restore();
            return ResponseController::success();
        }
        return ResponseController::error('No deleted developer is found!', 404);
    }
}
