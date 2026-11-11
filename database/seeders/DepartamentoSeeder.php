<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            'Administracion',
            'Ventas',
            'Cajas',
            'Almacen',
            'Atencion al cleinte',
        ];
        foreach ($departamentos as $nombre) {
            departamento::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
