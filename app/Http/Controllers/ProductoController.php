<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = DB::table('productos')
        ->select('productos.*', 'categorias.nombre as categoria')
        ->join('categorias', 'categorias.id', '=', 'productos.categoria_id')
        ->get();
        return response()->json($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nombre'=> 'required|string|min:1|max:100',
            'descripcion'=> 'required|string',
            'precio'=> 'required|string|min:1|max:100',
            'stock'=> 'required|string|min:1|max:100',
            'imagen'=> 'required',  
            'estado'=> 'required',
            'categoria_id'=>'required|numeric'
        ];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
          // Guardar la imagen en el sistema de archivos y obtener su ruta
        $rutaImagen = $request->file('imagen')->store('public/imagenes');
        $producto = new Producto($request->except('imagen')); // Excluir la imagen de los datos del producto
        $producto->imagen = $rutaImagen; // Asignar la ruta de la imagen
        $producto->save();
        return response()->json([
            'status' => true,
            'message' => 'Producto creado satisfactoriamente'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto, $id)
    {
        try {
            $producto = Producto::findOrFail($id);
            return response()->json(['status' => true, 'data' => $producto]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto, $id)
    {
        $rules = [
            'nombre'=> 'required|string|min:1|max:100',
            'descripcion'=> 'required|string',
            'precio'=> 'required|string|min:1|max:100',
            'stock'=> 'required|string|min:1|max:100',
            'imagen'=> 'required',  
            'estado'=> 'required',
            'categoria_id'=>'required|numeric'
        ];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        try {
            $producto = Producto::findOrFail($id);
            $producto->update($request->input());
            return response()->json([
                'status' => true,
                'message' => 'Producto actualizado satisfactoriamente'
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto, $id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();
            return response()->json([
                'status' => true,
                'message' => 'Producto eliminado satisfactoriamente'
            ],200);
        } catch (ModelNotFoundException $e) {   
            return response()->json([
                'status' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }
    public function ProductbyCategory(){
        $productos = Producto::select(DB::raw('count(productos.id) as count'), 'categorias.nombre')
                        ->join('categorias', 'categorias.id', '=', 'productos.categoria_id')
                        ->groupBy('categorias.nombre')
                        ->get();
    
        return response()->json($productos);
    }
    
   
}
