<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\EmployeeRequest;

class EmployeeController extends Controller
{
    public function create(EmployeeRequest $request){
        try{
            $this->authorize('create',Employee::class);
        }catch(\Throwable $th){
            return ResponseController::error('You Are not allowed');
        }
        $employee = Employee::where('email',$request->email)->first();
        if($employee){
            return ResponseController::error('The email has already been taken.');
        }
        Employee::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'password' =>Hash::make($request->password),
            'role' =>$request->role
        ]);
        return ResponseController::success('Successfuly created ');
    }

    public function login(Request $request){
        $employee = Employee::where('email',$request->email)->first();
        $password = $request->password;
        if(!$employee OR !Hash::check($password,$employee->password)){
            return ResponseController::error('Phone or password incorrect');
        }
        $token = $employee->createToken('employee :'.$employee->phone)->plainTextToken;
        return ResponseController::data([
            'employee_id'=>$employee->id,
            'name'=>$employee->name,
            'email' =>$employee->email,
            'role' =>$employee->role,
            'token'=>$token
        ]);
    }
    
    public function destroy(Employee $employee){
        try{
            $this->authorize('delete',Employee::class);
        }catch(\Throwable $th){
            return ResponseController::error('You Are not allowed');
        }
        $employee->delete();
        return ResponseController::success('Successfuly deleted');
    }

    public function update(EmployeeRequest $request,Employee $employee){
        try{
            $this->authorize('update',Employee::class);
        }catch(\Throwable $th){
            return ResponseController::error('You Are not allowed');
        }
        $employee->update($request->only([
            'name',
            'email',
            'password'
        ]));
        return ResponseController::success('Successfuly updated');   
    }
}
