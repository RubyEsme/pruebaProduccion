<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->text('sku')->nullable();
            $table->text('formato')->nullable();
            $table->text('modelo')->nullable();
            $table->text('tipo')->nullable();
            $table->text('codigo_mp')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('descripcion_1')->nullable();
            $table->double('cantidad')->nullable();
            $table->text('um')->nullable();
            $table->bigInteger('planta')->nullable();
            $table->text('linea')->nullable();
            $table->text('rodillo_digital')->nullable();
            $table->text('observaciones')->nullable();
            $table->bigInteger('idreceta')->nullable();
            $table->text('proveedor')->nullable();
            $table->text('ficha')->nullable();
            $table->timestamps();
        });

        // Agregar el trigger
        DB::unprepared('
            CREATE TRIGGER `concatenar_ficha` BEFORE INSERT ON `recetas` FOR EACH ROW
            SET NEW.ficha = CONCAT(NEW.planta, \'-\', NEW.linea, \'-\', NEW.idreceta, \'-\', NEW.sku);
        ');

        // Agregar el trigger
        DB::unprepared('
            CREATE TRIGGER `concatenar_ficha_edicion` BEFORE UPDATE ON `recetas` FOR EACH ROW
            SET NEW.ficha = CONCAT(NEW.planta, \'-\', NEW.linea, \'-\', NEW.idreceta, \'-\', NEW.sku);
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recetas');
    }
}
