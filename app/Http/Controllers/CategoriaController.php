<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules=['nombre'=>'required|string|min:1|max:100'];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        $categoria = new Categoria($request->input());
        $categoria->save();
        return response()->json([
            'status' => true,
            'message' => 'categoria creado satisfactoriamente'
        ],200);
    }   

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            return response()->json(['status' => true, 'data' => $categoria]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'categoria no encontrado'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria, $id)
    {
        $rules = ['nombre'=> 'required|string|min:1|max:100'];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->update($request->input());
            return response()->json([
                'status' => true,
                'message' => 'categoria actualizado satisfactoriamente'
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'categoria no encontrado'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            return response()->json([
                'status' => true,
                'message' => 'categoria eliminado satisfactoriamente'
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'categoria no encontrado'
            ], 404);
        }
    }
}
