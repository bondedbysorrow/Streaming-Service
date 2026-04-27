<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto; // <-- ¡IMPORTANTE! Verifica que esta sea la ruta y nombre correctos de tu Modelo.
use Illuminate\Support\Facades\DB; // Opcional, para limpiar la tabla

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opcional: Limpiar la tabla antes de insertar para evitar duplicados si corres el seeder varias veces
        // DB::table('productos')->truncate(); // Alternativa a delete(), más rápida si no hay llaves foráneas complejas

        $this->command->info('Poblando tabla de productos...'); // Mensaje para la consola

        // Producto 1 (Netflix que ya tenías - ajusta el precio si lo cambiaste)
         Producto::create([
            'nombre' => 'Netflix Premium', // Cambié un poco el nombre
            'descripcion_corta' => '4 pantallas + Calidad Ultra HD.',
            'imagen_url' => 'images/productos/Netflix.png', // ¡Usa la ruta relativa que creaste!
            'precio' => 10000.00, // ¡Usa el precio correcto en formato numérico!
            'es_popular' => true,
            'ventas' => 155,
        ]);

        // Producto 2
        Producto::create([
            'nombre' => 'STstreaming Básico Individual',
            'descripcion_corta' => '1 pantalla, calidad estándar.',
             // Asegúrate de tener esta imagen o cambia la ruta/nombre
            'imagen_url' => 'images/productos/Basico.png', // Ejemplo de otra ruta relativa
            'precio' => 9900.00,
            'es_popular' => true,
            'ventas' => 95,
        ]);

        // Producto 3
        Producto::create([
            'nombre' => 'Combo Stream + Music',
            'descripcion_corta' => 'Películas, series y música sin límites.',
            'imagen_url' => 'images/productos/ComboMusic.png', // Ejemplo
            'precio' => 18500.00,
            'es_popular' => false,
            'ventas' => 310, // Más vendido
        ]);

         // Producto 4
        Producto::create([
            'nombre' => 'Recarga Saldo $5.000', // Sé específico con las recargas
            'descripcion_corta' => 'Añade $5.000 COP a tu cuenta.',
            'imagen_url' => 'images/productos/Saldo5k.png', // Ejemplo
            'precio' => 5000.00,
            'es_popular' => true, // Las recargas pueden ser populares
            'ventas' => 500, // Muy vendido
        ]);

         // Producto 5
        Producto::create([
            'nombre' => 'Cuenta Mubi x 1 Mes',
            'descripcion_corta' => 'Cine de autor y clásicos.',
            'imagen_url' => 'images/productos/Mubi.png', // Ejemplo
            'precio' => 8000.00,
            'es_popular' => false,
            'ventas' => 75,
        ]);

         // Producto 6
         Producto::create([
            'nombre' => 'Picsart Gold Anual',
            'descripcion_corta' => 'Edición avanzada de fotos y videos.',
            'imagen_url' => 'images/productos/Picsart.png', // Ejemplo
            'precio' => 50000.00, // Precio anual (ejemplo)
            'es_popular' => false,
            'ventas' => 40,
        ]);


        $this->command->info('¡Tabla de productos poblada!');
    }
}