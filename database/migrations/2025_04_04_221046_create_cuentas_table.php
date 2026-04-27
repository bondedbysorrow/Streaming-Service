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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('productos')->onDelete('cascade'); // Relación con productos
            $table->string('email_usuario'); // Email/Usuario de la cuenta
            $table->string('password');      // Contraseña de la cuenta
            $table->text('detalles')->nullable(); // Campo extra para notas, perfil, PIN, etc.
            $table->enum('status', ['disponible', 'vendida'])->default('disponible'); // Estado de la cuenta
            $table->foreignId('sold_to_user_id')->nullable()->constrained('users')->onDelete('set null'); // A qué usuario se vendió
            $table->timestamp('sold_at')->nullable(); // Cuándo se vendió
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};