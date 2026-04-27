<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cuentas', function (Blueprint $table) {
            // Añadir columna pedido_id después de product_id (o donde prefieras)
            // Debe ser nullable porque una cuenta puede no estar vendida aún
            // Definir clave foránea a la tabla 'pedidos'
            $table->foreignId('pedido_id')
                  ->nullable()
                  ->after('product_id') // Opcional: para orden de columna
                  ->constrained('pedidos') // Asume que tu tabla se llama 'pedidos'
                  ->onDelete('set null'); // Si se borra un pedido, la cuenta queda sin pedido_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuentas', function (Blueprint $table) {
            // Para poder eliminar la clave foránea, primero eliminamos el índice
            // El nombre del índice suele ser nombretabla_nombrecolumna_foreign
            $table->dropForeign(['pedido_id']); // O $table->dropForeign('cuentas_pedido_id_foreign'); si le dio otro nombre
            $table->dropColumn('pedido_id');
        });
    }
};