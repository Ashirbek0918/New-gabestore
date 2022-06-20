<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// All good
class GenreController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You do not have permission to create any Genre!', 405);
        }
        $validator = Validator::make($request->all(), [
            "name" => 'required|unique:genres,name'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $genre = Genre::where('name', $request->name)
        ->first();
        if($genre){
            return ResponseController::error('The genre is already exists', 403);
        }
        Genre::create([
            "name" => $request->name,
        ]);
        return ResponseController::success();
    }
    public function show(){
        $genres = Genre::paginate(20);
        $collection = [
            "last_page" => $genres->lastPage(),
            "genres" => [],
        ];
        foreach($genres as $genre){
            $products = $genre->genre_products()->count();
            $collection["genres"][] = [
                "genre_id" => $genre->id,
                "genre_name" => $genre->name,
                "products" => $products
            ];
        }
        return ResponseController::data($collection);
    }
    public function update($genre, Request $request){
        try {
            $this->authorize('update', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to update any Genre!', 405);
        }
        $validator = Validator::make($request->all(), [
            "name" => 'required|unique:genres,name'
        ]);
        if($validator->fails()){
        return ResponseController::error($validator->errors()->first());
        }
        $genre = Genre::find($genre);
        if(!$genre){
            return ResponseController::error('No genre is found to update', 422);
        }
        $genre->update([
            "name" => $request->name
        ]);
        return ResponseController::success();
    }
    public function delete($genre){
        try {
            $this->authorize('delete', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You cannot delete any Genre due to the Authorization', 405);
        }
        $genre = Genre::find($genre);
        if(!$genre){
            return ResponseController::error('Genre is not exists', 404);
        }
        $genre->delete();
        $genre->genre_products()->delete();
        return ResponseController::success();
    }
    public function history(){
        try {
            $this->authorize('view', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to view any deleted genre!', 405);
        }
        $genres = Genre::onlyTrashed()->orderBy('deleted_at', 'Desc')->get();
        if(count($genres) == 0 ){
            return ResponseController::error('No deleted genres have been found!', 404);
        }
        return ResponseController::data($genres);
    }
    public function restore(Request $request){
        $genre = Genre::withTrashed()->find($request->genre_id);
        if($genre->trashed()){
            $genre->restore();
            $genre->genre_products()->restore();
            return ResponseController::success('Genre and products are restored');
        }
        return ResponseController::error('No deleted genre has found to restore', 404);
    }
}
