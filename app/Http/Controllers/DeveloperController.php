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
        $collection = [];
        
    }
}
