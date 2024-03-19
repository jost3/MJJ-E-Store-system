<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\departamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function create(Request $request){
        $rules=[
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            'departamento_id' => 'required|numeric',
        ];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ],400);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->departamento_id = $request->departamento_id;
        $user->save();
        return response ()->json ([
            'status'=> true,
            ',message'=> 'usuario creado satisfactoriamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken   
        ],200);
    }
    public function login(Request $request){
        $rules=[
            'email' => 'required|string|email|max:100    ',
            'password' => 'required|string',
        ];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()->all()
            ],400);
        }
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'status'=> false,
                'errors'=> ['no autorizado']
            ],401);
        }
        $user = User::where('email',$request->email)->first();
        return response ()->json ([
            'status'=> true,
            'message'=> 'usuario logueado satisfactoriamente',
            'data' =>$user,
            'token' => $user->createToken('API TOKEN')->plainTextToken   
        ],200);
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response ()->json ([
            'status'=> true,
            'message'=> 'usuario ha cerrado su sesión satisfactoriamente'
        ],200);
    }
    
}
