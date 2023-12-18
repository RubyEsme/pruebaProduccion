<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLimitesHistoricoTable extends Migration
{
    public function up()
    {
        Schema::create('limites_historico', function (Blueprint $table) {
            $table->id();
            $table->text('codigo_mp')->nullable();
            $table->text('descripcion_1')->nullable();
            $table->text('mes')->nullable();
            $table->integer('año')->nullable(); // Cambiado a integer
            $table->integer('limite')->nullable(); // Cambiado a integer
            $table->double('entregado')->default(0); // Cambiado a double
            $table->text('status')->default('Sin uso');
            $table->double('porcentaje_uso')->default(0); // Cambiado a integer
            $table->double('kg_diferencia')->default(0); // Cambiado a double
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // Agregar trigger antes de insertar en la tabla limites_historico
        DB::unprepared('
            CREATE TRIGGER tr_before_insert_limites_historico
            BEFORE INSERT ON limites_historico
            FOR EACH ROW
            BEGIN
                CASE NEW.mes
                    WHEN 1 THEN SET NEW.mes = "Enero";
                    WHEN 2 THEN SET NEW.mes = "Febrero";
                    WHEN 3 THEN SET NEW.mes = "Marzo";
                    WHEN 4 THEN SET NEW.mes = "Abril";
                    WHEN 5 THEN SET NEW.mes = "Mayo";
                    WHEN 6 THEN SET NEW.mes = "Junio";
                    WHEN 7 THEN SET NEW.mes = "Julio";
                    WHEN 8 THEN SET NEW.mes = "Agosto";
                    WHEN 9 THEN SET NEW.mes = "Septiembre";
                    WHEN 10 THEN SET NEW.mes = "Octubre";
                    WHEN 11 THEN SET NEW.mes = "Noviembre";
                    WHEN 12 THEN SET NEW.mes = "Diciembre";
                    ELSE SET NEW.mes = "Mes Desconocido";
                END CASE;
            END
        ');
    }

    public function down()
    {
        Schema::dropIfExists('limites_historico');
        // No se eliminará el trigger aquí ya que los comandos `down` generalmente se utilizan para deshacer cambios en la migración.
    }
}
