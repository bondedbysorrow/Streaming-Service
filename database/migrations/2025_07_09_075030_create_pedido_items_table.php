<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Este comando crea la tabla 'pedido_items' en tu base de datos
        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id(); // Columna para el ID único de cada item

            // Columna para relacionar este item con un pedido.
            // Si se borra un pedido, se borran sus items en cascada.
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');

            // Columna para relacionar este item con un producto.
            $table->foreignId('product_id')->constrained('productos')->onDelete('cascade');

            $table->integer('quantity'); // Cantidad de este producto comprado
            $table->decimal('precio_unitario', 10, 2); // Precio del producto en ese momento
            $table->decimal('subtotal', 10, 2); // (precio * cantidad)

            $table->timestamps(); // Columnas created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_items');
    }
};
