<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerpetuidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perpetuidades', function (Blueprint $table) {

            $table->id();
            $table->text('nro_comprobante'); // Número de comprobante para la perpetuidad
            $table->date('fecha_comprobante');
            $table->date('fecha_perpetuidad'); // Fecha en la que se registra la 
            $table->text('motivo');
            $table->unsignedBigInteger('inhumacion_id');
            $table->foreign('inhumacion_id')->references('id')->on('inhumaciones')->onDelete('cascade');
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
        Schema::dropIfExists('perpetuidades');
    }
}
