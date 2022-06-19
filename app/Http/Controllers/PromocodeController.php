<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// All good
class PromocodeController extends Controller
{
    public function create(Request $request){
        try {
            $this->authorize('create', Promocode::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to create a Promocode!', 405);
        }
        $validator = Validator::make($request->all(), [
            "user_id" => 'required|exists:users,id',
            "promocode" => 'required|unique:promocodes,promocode',
            "count" => 'required|numeric',
            "discount" => 'nullable'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $promocode = Promocode::where('promocode', $request->promocode)
        ->first();
        if($promocode){
            return ResponseController::error('The promocode is exists', 422);
        }
        Promocode::create([
            "user_id" => $request->user_id,
            "promocode" => $request->promocode,
            "count" => $request->count ?? 0,
            "discount" => $request->discount ?? 0   ,
        ]);
        return ResponseController::success();
    }
    public function show(){
        try {
            $this->authorize('view', Promocode::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to view a Promocode!', 405);
        }
        $promocodes = Promocode::paginate(10);
        $collection = [
            "last_page" => $promocodes->lastPage(),
            "promocodes" => [],
        ];
        if(count($promocodes) == 0){
            return ResponseController::error('There is no promocode to show', 404);
        }
        foreach($promocodes as $promocode){
            $collection["promocodes"][] = [
                "user_id" => $promocode->user_id,
                "promocode" => $promocode->promocode,
                "count" => $promocode->count,
                "discount" => $promocode->discount,
            ];
        }
        return ResponseController::data($collection);
    }
    public function delete($promocode){
        try {
            $this->authorize('delete', Promocode::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to delete a Promocode!', 405);
        }
        $promocode = Promocode::find($promocode);
        if(!$promocode){
            return ResponseController::error('There is no Promocode to delete', 404);
        }
        $promocode->delete();
        return ResponseController::success();
    }
    public function update($promocode, Request $request){
        try {
            $this->authorize('update', Promocode::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to update a Promocode!', 405);
        }
        $validator = Validator::make($request->all(), [
            "user_id" => 'required|exists:users,id',
            "promocode" => 'required|unique:promocodes,promocode',
            "count" => 'required|numeric',
            "discount" => 'nullable|'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $promocode = Promocode::find($promocode);
        if(!$promocode){
            return ResponseController::error('There is no Promocode to update', 404);
        }
        $promocode->update($request->all());
        return ResponseController::success();
    }
}
