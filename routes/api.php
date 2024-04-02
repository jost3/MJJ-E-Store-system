<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentasController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register',[AuthController::class,'create']);
Route::post('auth/login',[AuthController::class,'login']);
Route::middleware(['auth:sanctum'])->group(function(){
    /* rutas para modificar, ver , editar y eliminar usuario*/
    Route::get('users', [AuthController::class, 'index']);
    Route::get('users/{id}', [AuthController::class, 'show']);
    Route::put('users/{id}', [AuthController::class, 'update']);
    Route::delete('users/{id}', [AuthController::class, 'destroy']);
      /* rutas de productos*/
    Route::get('productbycategory',[ProductoController::class,'ProductbyCategory']);
    Route::resource('product', ProductoController::class);
    /* rutas de categoria*/
    Route::resource('category', CategoriaController::class);
    /* rutas de venta*/
    Route::resource('venta', VentasController::class);
    Route::get('ventdia',[VentasController::class,'ventasDiarias']);
    
    Route::middleware(['auth', 'departamento:Battery Repairer'])->group(function () {
        Route::resource('departaments', DepartamentoController::class);
    });
    Route::get('auth/logout',[AuthController::class,'logout']);
});

