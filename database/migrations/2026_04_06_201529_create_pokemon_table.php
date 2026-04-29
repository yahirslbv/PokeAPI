<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('pokemon', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->integer('pokedex_number');
        $table->string('name');
        
        $table->text('types');         
        $table->string('image');
        $table->string('animated');
        $table->string('species'); 
        $table->text('description'); 
        $table->float('height'); 
        $table->float('weight'); 
        
        // Todas las estadísticas base
        $table->integer('hp')->default(0);
        $table->integer('attack')->default(0);
        $table->integer('defense')->default(0);
        $table->integer('special_attack')->default(0); 
        $table->integer('special_defense')->default(0); 
        $table->integer('speed')->default(0); 
        
        // Guardamos la línea evolutiva completa para que funcione offline
        $table->text('evolution_chain'); 
        
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('pokemon');
    }
};