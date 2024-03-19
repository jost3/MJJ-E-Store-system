<?php

namespace App\Http\Controllers;

use App\Models\departamento;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentos = departamento::all();
        return response()->json($departamentos);
    }

    public function store(Request $request)
    {
        $rules = ['nombre'=> 'required|string|min:1|max:100'];
        $validator = \Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        $departamento = new departamento($request->input());
        $departamento->save();
        return response()->json([
            'status' => true,
            'message' => 'Departamento creado satisfactoriamente'
        ],200);
    }

    public function show(departamento $departamento, $id)
    {
        try {
            $departamento = departamento::findOrFail($id);
            return response()->json(['status' => true, 'data' => $departamento]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Departamento no encontrado'
            ], 404);
        }
    }

    public function update(Request $request, departamento $departamento, $id)
    {
        $rules = ['nombre'=> 'required|string|min:1|max:100'];
        $validator = \Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        try {
            $departamento = departamento::findOrFail($id);
            $departamento->update($request->input());
            return response()->json([
                'status' => true,
                'message' => 'Departamento actualizado satisfactoriamente'
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Departamento no encontrado'
            ], 404);
        }
    }

    public function destroy(departamento $departamento, $id)
    {
        try {
            $departamento = departamento::findOrFail($id);
            $departamento->delete();
            return response()->json([
                'status' => true,
                'message' => 'Departamento eliminado satisfactoriamente'
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Departamento no encontrado'
            ], 404);
        }
    }
}
