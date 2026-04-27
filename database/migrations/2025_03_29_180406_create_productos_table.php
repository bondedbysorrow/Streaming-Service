<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ejecuta las migraciones para crear la tabla.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            // Columna de ID (ya la tenías)
            $table->id();

            // --- COLUMNAS AÑADIDAS ---
            $table->string('nombre'); // Columna para el nombre del producto/cuenta
            $table->text('descripcion_corta')->nullable(); // Descripción (puede ser nula)
            $table->string('imagen_url')->nullable(); // URL de la imagen (puede ser nula)
            $table->decimal('precio', 8, 2)->default(0); // Precio (ej: 123456.78), por defecto 0
            $table->boolean('es_popular')->default(false); // Para marcar si es popular (por defecto no)
            $table->integer('ventas')->default(0); // Contador de ventas (ejemplo, por defecto 0)
            // --- FIN DE COLUMNAS AÑADIDAS ---

            // Columnas de timestamps (ya las tenías)
            $table->timestamps(); // Crea created_at y updated_at

            // Puedes añadir otras columnas aquí si las necesitas en el futuro
            // $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade'); // Ejemplo de llave foránea
        });
    }

    /**
     * Reverse the migrations.
     * Revierte las migraciones (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};