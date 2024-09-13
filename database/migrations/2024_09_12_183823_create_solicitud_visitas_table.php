<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudVisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_visitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('persona_id');
            $table->unsignedBigInteger('propiedad_id');
            $table->date('fecha_visita');
            $table->text('comentarios')->nullable();
            $table->timestamps();

            // Llaves foráneas
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('propiedad_id')->references('id')->on('propiedades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes_visitas');
    }
}
