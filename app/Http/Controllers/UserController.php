<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function allUsers(){
        $users = User::paginate(10);
        $final = [
            'last_page' =>$users->lastPage(),
            'users' => [],
        ];
        foreach ($users as $user){ 
            $final['users'][] = [
                'id'=>$user->id,
                'name'=>$user->name,
                'profile_photo'=>$user->profile_photo,
                'point' =>$user->point,
                'level'=>$user->level,
                'comments' =>$user->comments()->count(),
            ];
            $user['comments'] = $user->comments()->count();
        }
    
        return ResponseController::data($final);
    }

    public function orderbyPoint(Request $request){
        $final = [];
        $user = $request->user();
        $users = User::orderBy('point','Desc')->take(10)->get(['id','name','profile_photo','point','level']);
        $final['user'] = $user;
        $final['users'] = $users;
        return ResponseController::data($final);
    }

    public function singleUser($user_id){
        $user = User::find($user_id);
        if(!$user){
            return ResponseController::error('User not found',404);
        }
        return ResponseController::data($user);
     }
}
