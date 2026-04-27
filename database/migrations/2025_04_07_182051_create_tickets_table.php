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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // Columna id (BigInt unsigned primary key)

            // user_id (foreign key a users)
            // constrained() asume que la tabla es 'users' y la columna 'id'
            // onDelete('cascade') significa que si se borra un usuario, se borran sus tickets
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('subject'); // Columna subject (VARCHAR)
            $table->text('message');   // Columna message (TEXT)

            // Columna status con un valor por defecto 'Abierto'
            $table->string('status')->default('Abierto');

            // timestamps() crea las columnas created_at y updated_at (TIMESTAMP nullable)
            $table->timestamps();

            // --- Opcional: Añadir priority o category_id aquí si los necesitas ---
            // $table->string('priority')->nullable();
            // $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};