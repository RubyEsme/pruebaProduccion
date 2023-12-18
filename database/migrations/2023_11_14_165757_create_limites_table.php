<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLimitesTable extends Migration
{
    public function up()
    {
        Schema::create('limites', function (Blueprint $table) {
            $table->id();
            $table->text('codigo_mp')->nullable();
            $table->text('descripcion_1')->nullable();
            $table->integer('mes')->nullable(); // Cambiado a integer
            $table->integer('año')->nullable(); // Cambiado a integer
            $table->integer('limite')->nullable(); // Cambiado a integer
            $table->double('entregado')->default(0); // Cambiado a double
            $table->text('status')->default('Sin uso');
            $table->double('porcentaje_uso')->default(0); // Cambiado a double
            $table->double('kg_diferencia')->default(0); // Cambiado a double
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // Trigger AFTER DELETE
        DB::unprepared('
            CREATE TRIGGER tr_limites_delete
            AFTER DELETE ON limites
            FOR EACH ROW
            BEGIN
                IF IFNULL(OLD.codigo_mp, "0") <> "0" THEN
                    INSERT INTO limites_historico (id, codigo_mp, descripcion_1, mes, año, limite, entregado, status, porcentaje_uso, kg_diferencia, created_at, updated_at)
                    VALUES (OLD.id, OLD.codigo_mp, OLD.descripcion_1, OLD.mes, OLD.año, OLD.limite, OLD.entregado, OLD.status, OLD.porcentaje_uso, OLD.kg_diferencia, NOW(), NOW());
                END IF;
            END
        ');

        // Trigger BEFORE UPDATE
        DB::unprepared('
            CREATE TRIGGER tr_limites_before_update
            BEFORE UPDATE ON limites
            FOR EACH ROW
            BEGIN
                SET NEW.porcentaje_uso = IF(NEW.limite > 0, (NEW.entregado / NEW.limite) * 100, 101);
                IF NEW.entregado <= NEW.limite THEN
                    SET NEW.kg_diferencia = 0;
                ELSE
                    SET NEW.kg_diferencia = NEW.entregado - NEW.limite;
                END IF;
            END
        ');
    }

    public function down()
    {
        Schema::dropIfExists('limites');
        // No se eliminarán los triggers aquí, ya que los comandos `down` generalmente se utilizan para deshacer cambios en la migración.
    }
}
