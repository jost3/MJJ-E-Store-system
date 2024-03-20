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
        'precio'=> 'required|min:1|max:100',
        'stock'=> 'required|min:1|max:100',
        'imagen'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  
        'estado'=> 'required',
        'categoria_id'=>'required|numeric'
    ];
    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()){
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()->all()
        ],400);
    }

    // Obtener la imagen del formulario
    $imagen = $request->file('imagen');

    // Generar un nombre único para la imagen
    $nombreImagen = 'imagen_' . uniqid() . '.' . $imagen->getClientOriginalExtension();

    // Almacenar la imagen en el sistema de archivos
    $imagen->storeAs('public/', $nombreImagen);

    // Ruta de la imagen almacenada en el sistema de archivos
    $rutaImagen = 'public/storage/' . $nombreImagen;

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
        'precio'=> 'required|min:1|max:100',
        'stock'=> 'required|min:1|max:100',
        'estado'=> 'required',
        'categoria_id'=>'required|numeric'
    ];

    // Validar la imagen solo si se ha enviado una nueva imagen en la solicitud
    if ($request->hasFile('imagen')) {
        $rules['imagen'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
    } else {
        // Si no se envía una imagen nueva, excluirla de los datos a actualizar
        unset($request['imagen']);
    }

    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()){
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()->all()
        ],400);
    }
    try {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all()); // Usar $request->all() para obtener todos los datos de la solicitud
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
    //obtiene la cantidad de productos por cada categoría 
    public function ProductbyCategory(){
        $productos = Producto::select(DB::raw('count(productos.id) as count'), 'categorias.nombre')
                        ->join('categorias', 'categorias.id', '=', 'productos.categoria_id')
                        ->groupBy('categorias.nombre')
                        ->get();
    
        return response()->json($productos);
    }
    
   
}
