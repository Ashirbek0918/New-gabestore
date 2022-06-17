<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;

class AuthController extends Controller
{
    public function register(Request $request){
        $email = $request->email;
        $user = User::where('email',$email)->first();
        if($user){
            return ResponseController::error('This email already taken',422);
        }
        User::create([
            'name' =>$request->name,
            'email' =>$email,
            'password' =>Hash::make($request->password),
            'profile_photo' =>$request->profile_photo,
        ]);
        return ResponseController::success('Successfuly registered');
    }

    public function login(LoginUserRequest $request){
        $user = User::where('email',$request->email)->first();
        $password = $request->password;
        if (!$user OR !Hash::check($password,$user->password)){
            return  ResponseController::error('Email or password incorrect');
        }
        $token = $user->createToken('user')->plainTextToken;
        return ResponseController::data([
            'token' =>$token
        ]);
    }

    public function getme(Request $request){
        return $request->user();
    }

    public function logOut(Request $request){
        $request->user()->currentAccessToken()->delete();
        return ResponseController::success('You have successfully logged out');
    }
    public function employeeLogin(Request $request){
        $employee = Employee::where('email', $request->email)->first();
        // return $employee;
        if(!$employee or !Hash::check($request->password, $employee->password)){
            return ResponseController::error('Either password or email is incorrect', 422);
        }
        $token = $employee->createToken('employee: '. $request->email)->plainTextToken;
        return response([
            "token" => $token
        ]);
    }
}
