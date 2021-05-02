<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->references('id')->on('actividads')->constrained()->onUpdate('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->constrained()->onUpdate('cascade');
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
        Schema::dropIfExists('actividad_user');
    }
}
