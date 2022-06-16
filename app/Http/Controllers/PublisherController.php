<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', Publisher::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You do not have permission to create a Publisher', 403);
        }
        $validator = Validator::make($request->all(), [
            "title" => 'required|string',
            "image" => 'required|url',
            "logotip" => 'required|url',
            "description" => 'nullable'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $publisher = Publisher::where('title', $request->title)
        ->first();
        if($publisher){
            return ResponseController::error('The publisher is already exists!', 403);
        }
        Publisher::create([
            "title" => $request->title,
            "image" => $request->image,
            "logotip" => $request->logotip,
            "description" => $request->description
        ]);
        return ResponseController::success();
    }
}
