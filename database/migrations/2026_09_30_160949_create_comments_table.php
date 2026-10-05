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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // Relaciones principales
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            
            // LLAVE FORÁNEA AUTOREFERENCIADA (Para responder comentarios)
            // nullable() -> Si es null, es un comentario principal.
            // Si tiene ID, es una respuesta a ese comentario.
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            
            // Contenido
            $table->text('body');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
