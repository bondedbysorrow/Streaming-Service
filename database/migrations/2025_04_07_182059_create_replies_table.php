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
        Schema::create('replies', function (Blueprint $table) {
            $table->id(); // Columna id

            // ticket_id (foreign key a tickets)
            // onDelete('cascade') significa que si se borra un ticket, se borran sus respuestas
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');

            // user_id (foreign key a users - quién respondió)
            // onDelete('cascade') si quieres borrar respuestas si se borra el usuario que respondió
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->text('message');   // Columna message (TEXT)

            // timestamps() crea created_at y updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
};