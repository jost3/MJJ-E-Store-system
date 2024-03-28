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
       $user = User::where('email', $request->email)->first();
        $departamento_nombre = null;
        
        if ($user->departamento_id) {
            $departamento_nombre = departamento::where('id', $user->departamento_id)->value('nombre');
        }
        
        return response()->json([
            'status'=> true,
            'message'=> 'Usuario logueado satisfactoriamente',
            'data' => [
                'user' => $user,
                'departamento' => $departamento_nombre
            ],
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response ()->json ([
            'status'=> true,
            'message'=> 'usuario ha cerrado su sesi贸n satisfactoriamente'
        ],200);
    }
    
}