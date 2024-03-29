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

    public function index()
    {
        $users = User::with('departamento')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'departamento_id' => $user->departamento_id,
                'departamento' => $user->departamento->nombre,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });
    
        return response()->json([
            'status'=> true,
            'message'=> 'Usuarios obtenidos satisfactoriamente',
            'data' => $users
        ], 200);
    }
    public function show($id)
    {
        $user = User::with('departamento')->find($id);
    
        if (!$user) {
            return response()->json([
                'status'=> false,
                'message'=> 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'status'=> true,
            'message'=> 'Usuario obtenido satisfactoriamente',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'departamento' => $user->departamento->nombre,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,'.$id,
            'departamento_id' => 'required|numeric',
        ];
    
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
    
        try {
            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Usuario actualizado satisfactoriamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar el usuario'
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'Usuario eliminado satisfactoriamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar el usuario'
            ], 500);
        }
    }
}