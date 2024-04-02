<?php

namespace App\Http\Controllers;

use App\Models\Ventas;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventas = DB::table('ventas')
            ->select('ventas.*', 'productos.nombre as nombre_producto', 'users.name as nombre_usuario')
            ->join('productos', 'productos.id', '=', 'ventas.id_producto')
            ->join('users', 'users.id', '=', 'ventas.id_usuario')
            ->get();
        return response()->json($ventas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'id_usuario' => 'required|exists:users,id',
            'id_producto' => 'required|exists:productos,id',
            'precio_unitario' => 'required|numeric|min:0',
            'total_venta' => 'required|numeric|min:0',
            'estado'=> 'required',
            'cantidad'=> 'required',
        ];
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
    
        try {
            $ventas = new Ventas();
            $ventas->id_usuario = $request->input('id_usuario');
            $ventas->id_producto = $request->input('id_producto');
            $ventas->precio_unitario = $request->input('precio_unitario');
            $ventas->total_venta = $request->input('total_venta');
            $ventas->estado = $request->input('estado');
            $ventas->cantidad = $request->input('cantidad');
            $ventas->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Venta creada satisfactoriamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear la venta'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ventas $ventas, $id)
    {
        try {
            $ventas = Ventas::with(['Producto:id,nombre', 'User:id,name'])
                ->select('id', 'precio_unitario', 'total_venta', 'id_producto', 'id_usuario','estado','cantidad', 'created_at', 'updated_at')
                ->findOrFail($id);
    
            $ventas->nombre_producto = $ventas->Producto->nombre;
            unset($ventas->Producto);
    
            $ventas->nombre_usuario = $ventas->User->name;
            unset($ventas->User);
    
            return response()->json(['status' => true, 'data' => $ventas]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ventas $ventas, $id)
    {
        $rules = [
            'id_usuario' => 'required|exists:users,id',
            'id_producto' => 'required|exists:productos,id',
            'precio_unitario' => 'required|numeric|min:0',
            'total_venta' => 'required|numeric|min:0',
            'estado' => 'required',
        ];
    
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
    
        try {
            $ventas = Ventas::findOrFail($id);
            $ventas->update($request->input());
            return response()->json([
                'status' => true,
                'message' => 'Venta actualizada satisfactoriamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar la venta'
            ], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ventas $ventas, $id)
    {
        try {
            $ventas = Ventas::findOrFail($id);
            $ventas->delete();
            return response()->json([
                'status' => true,
                'message' => 'Venta eliminada satisfactoriamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar la venta'
            ], 500);
        }
    }
    public function ventasDiarias()
    {
        try {
            $ventasDiarias = DB::table('ventas')
                ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('SUM(total_venta) as total_ventas'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->get();
            
            return response()->json($ventasDiarias);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al obtener las ventas diarias'], 500);
        }
    }
    
}
