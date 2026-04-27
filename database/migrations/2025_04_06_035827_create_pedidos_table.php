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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id(); // ID único del pedido (autoincremental)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Quién hizo el pedido
            $table->string('status')->default('Completado'); // Estado: Completado, Procesando, Fallido, etc.
            $table->decimal('total_amount', 10, 2); // Monto total del pedido (ajusta precisión si es necesario)
            $table->integer('item_count')->default(1); // Cuántos items (cuentas) hay en este pedido
            $table->string('payment_method')->nullable(); // Método de pago (ej. 'Saldo Monedero')
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};