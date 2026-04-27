<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Añadimos la columna 'is_admin' de tipo booleano (0 o 1)
            // Por defecto será false (0) para los usuarios existentes y nuevos
            $table->boolean('is_admin')->default(false)->after('saldo'); // O después de 'email', donde prefieras
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin'); // Para poder revertir la migración
        });
    }
};
