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
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
        $table->integer('pokedex_number'); // Número oficial
        $table->string('name');
        $table->string('type');
        $table->string('image');    // Ruta local del sprite
        $table->string('animated'); // Ruta local del GIF
        $table->integer('hp')->default(0);
        $table->integer('attack')->default(0);
        $table->integer('defense')->default(0);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('pokemon');
    }
};