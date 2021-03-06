<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
// All good
class ImageController extends Controller{

    public function upload(Request $request){
        $validator = Validator::make($request->all(), [
            "images" => 'required',
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $image_url = [];
        $images = $request->file('images');
        if(!is_array($images)){
            $image_name = time()."_".Str::random(10).".".$images->getClientOriginalExtension();
            $images->move('Images', $image_name);
            $image_url[] = env('APP_URL')."/images/".$image_name;
        }
        foreach($images as $image){
            $image_name = time()."_".Str::random(10).".".$image->getClientOriginalExtension();
            $image->move('Images', $image_name);
            $image_url[] = env('APP_URL')."/images".$image;
        }
        return $image_url;
    }
    public function deleteImage(Request $request){
        $path = public_path('/images/'.$request->image);
        if(!$path){
            return ResponseController::error('Image does not exists', 404);
        }
        File::delete($path);
        return ResponseController::success();
    }
}
