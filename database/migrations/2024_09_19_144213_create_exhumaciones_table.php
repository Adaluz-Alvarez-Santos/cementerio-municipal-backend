<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExhumacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exhumaciones', function (Blueprint $table) {
            $table->id();
            $table-> text('nro_comprobante');
            $table->date('fecha_comprobante');
            $table->date('fecha_exhumacion');
            $table->text('motivo'); 
            $table->unsignedBigInteger('inhumacion_id');
            $table->foreign('inhumacion_id')->references('id')->on('inhumaciones')->onDelete('cascade')->unique();          
            $table->unsignedBigInteger('familiar_id');
            $table->foreign('familiar_id')->references('id')->on('familiares')->onDelete('cascade')->unique();                     
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
        Schema::dropIfExists('exhumaciones');
    }
}
