<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    // All good
    public function create(Request $request){
        try {
            $this->authorize('create', Genre::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You do not have permission to create any Genre!', 405);
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
            $collection["genres"][] = [
                "genre_id" => $genre->id,
                "genre_name" => $genre->name,
            ];
        }
        return ResponseController::data($collection);
    }
}
