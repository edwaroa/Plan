<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion');
            $table->foreignId('actividad_id')->unsigned()->references('id')->on('actividads')->constrained()
            ->onUpdate('cascade');
            $table->integer('usuario_id')->unsigned()->references('id')->on('users')->constrained()
            ->onUpdate('cascade');
            $table->string('url_documento');
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
        Schema::dropIfExists('evidencias');
    }
}
