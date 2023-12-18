<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMaterialesTable extends Migration
{
    public function up()
    {
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();
            $table->text('codigo_mp')->nullable();
            $table->text('descripcion_1')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materiales');
        // No se eliminará el trigger aquí ya que los comandos `down` generalmente se utilizan para deshacer cambios en la migración.
    }
}
