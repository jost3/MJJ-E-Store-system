<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;
    protected $fillable = ['precio_unitario','total_venta','id_producto','id_usuario'];

    public function User()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
    public function Producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
