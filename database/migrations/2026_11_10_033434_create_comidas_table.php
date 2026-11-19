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
        Schema::create('comidas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // Ej: Hamburguesa, Tacos
        $table->decimal('precio', 8, 2); // Ej: 45.00
        $table->string('categoria'); // Ej: Comida rápida, Bebidas
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comidas');
    }
};
