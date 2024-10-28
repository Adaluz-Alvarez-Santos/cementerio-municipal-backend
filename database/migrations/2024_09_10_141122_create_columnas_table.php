<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('columnas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero'); // Número de la columna dentro de la fila
            $table->unsignedBigInteger('fila_id'); // Relación con la fila
            $table->foreign('fila_id')->references('id')->on('filas')->onDelete('cascade');
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
        Schema::dropIfExists('columnas');
    }
}
