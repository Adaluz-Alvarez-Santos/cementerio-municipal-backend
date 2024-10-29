<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhumacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhumaciones', function (Blueprint $table) {
            $table->id(); 
            $table->date('fecha_entrada');
            $table->date('fecha_comprobante');
            $table->string('nro_comprobante');
            $table->date('fecha_finalizado')->nullable();
            $table->date('fecha_extendido_hasta')->nullable(); 
            $table->enum('estado', ['inhumacion', 'extendido', 'exhumacion', 'perpetuidad'])->default('inhumacion');  // Estado actual de la inhumación
            $table->unsignedBigInteger('espacio_id')->nullable();
    $table->foreign('espacio_id')->references('id')->on('espacios')->onDelete('set null');
            $table->unsignedBigInteger('persona_id'); 
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
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
        Schema::dropIfExists('inhumaciones');

    }
}
