<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // URL de la imagen aleatoria
        $imagenUrl = $this->faker->imageUrl(400, 300);

        // Obtener el contenido de la imagen desde la URL
        $imagenBinaria = file_get_contents($imagenUrl);

        // Generar un nombre único para la imagen
        $nombreImagen = 'imagen_' . uniqid() . '.jpg';

        // Almacenar la imagen en el sistema de archivos
        Storage::put('public/' . $nombreImagen, $imagenBinaria);

        // Ruta de la imagen almacenada en el sistema de archivos
        $rutaImagen = 'storage/' . $nombreImagen;

        return [
            'nombre' => $this->faker->name,
            'descripcion' => $this->faker->text,
            'precio' => $this->faker->randomFloat(2, 0, 1000), // Genera un precio aleatorio entre 0 y 1000 con 2 decimales
            'stock' => $this->faker->numberBetween(0, 100), // Genera una cantidad de stock aleatoria entre 0 y 100
            'imagen' => $rutaImagen, // Almacena la ruta de la imagen
            'estado' => $this->faker->randomElement(['activo', 'inactivo']), // Elige aleatoriamente entre 'activo' e 'inactivo'
            'categoria_id' => $this->faker->numberBetween(1,6)
        ];
    }
}
