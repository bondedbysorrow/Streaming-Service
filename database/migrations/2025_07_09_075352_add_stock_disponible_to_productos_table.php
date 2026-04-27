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
        // Edita la tabla 'productos' para añadir la nueva columna
        Schema::table('productos', function (Blueprint $table) {
            // Añade la columna para el stock. La hacemos integer, sin signo (no puede ser negativo)
            // y con un valor por defecto de 0.
            $table->unsignedInteger('stock_disponible')->default(0)->after('precio'); // La puedes poner después de la columna que prefieras
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // En caso de que necesites revertir la migración, esto eliminará la columna
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('stock_disponible');
        });
    }
};
