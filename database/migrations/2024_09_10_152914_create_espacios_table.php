<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspaciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('espacios', function (Blueprint $table) {
            $table->id();
            $table->enum('estado', ['ocupado', 'disponible'])->default('disponible');
            $table->unsignedBigInteger('fila_id');
            $table->foreign('fila_id')->references('id')->on('filas')->onDelete('cascade');            
            $table->unsignedBigInteger('columna_id');
            $table->foreign('columna_id')->references('id')->on('columnas')->onDelete('cascade');
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
        Schema::dropIfExists('espacios');
    }
}
