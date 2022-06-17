<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
    // All good
    public function show(){
        $publishers = Publisher::paginate(15);
        $collection = [
            "last_page" => $publishers->lastPage(),
            "publishers" => [],
        ];
        if(!$publishers){
            return ResponseController::error('No publisher in there!', 404);
        }
        foreach($publishers as $publisher){
            $collection["publishers"][] = [
                "publisher_id" => $publisher->id,
                "publisher_title" => $publisher->title,
                "publisher_image" => $publisher->image,
                "publisher_logotip" => $publisher->logotip,
                "publisher_description" => $publisher->description
            ];
        }
        return ResponseController::data($collection);
    }
    // All good
    public function update(Request $request ){
        try {
            $this->authorize('update', Publisher::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not permitted to update a Publisher!', 404);
        }
        $validator = Validator::make($request->all(), [
            "title" => 'required',
            "image" => 'required|url',
            "logotip" => 'required|url',
            "description" => 'nullable'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $publisher = Publisher::find($request->id);
        if(!$publisher ){
            return ResponseController::error('No publisher has found!', 404);
        }
        $publisher->update([
            "title" => $request->title,
            "image" => $request->image,
            "logotip" => $request->logotip,
            "description" => $request->description ?? null,
        ]);
        return ResponseController::success();
    }
    // All good
    public function delete($publisher, Request $request){
        try {
            $this->authorize('delete', Publisher::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to delete any Publisher!', 405);
        }
        $publisher = Publisher::find($publisher);
        $product = Product::where('publisher_id', $publisher->id)->get();
        $count = count($product);
            foreach($product as $item){
                $item->delete();
            }
        $publisher->delete();
        return ResponseController::success('Publisher and related Porducts are deleted');
    }
    // All good
    public function restore(Request $request){
        try {
            $this->authorize('restore', Publisher::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to restore any Publisher!', 405);
        }
        $publisher = Publisher::withTrashed()->find($request->id);
        $products = $publisher->products;
        return $products;
        // $product = Product::withTrashed()->where('publisher_id', $request->id)->get();
        if(!$publisher){
            return ResponseController::error('No deleted Publisher found to restore!', 404);
        }
        $publisher->restore();
        return ResponseController::success('Publisher and Porducts are restored', 200);
    }
}
