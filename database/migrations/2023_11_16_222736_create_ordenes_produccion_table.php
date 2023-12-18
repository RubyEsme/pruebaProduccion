<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesProduccionTable extends Migration
{
    public function up()
    {
        Schema::create('ordenes_produccion', function (Blueprint $table) {
            $table->id();
            $table->text('usuario')->nullable(); 
            $table->text('noOrden')->nullable(); 
            $table->date('fecha')->nullable();
            $table->text('codigo_mp')->nullable();
            $table->text('descripcion_1')->nullable();
            $table->double('requerido')->nullable(); // Cambiado a double
            $table->text('um')->nullable();
            $table->double('entregado')->nullable(); // Cambiado a double
            $table->double('pendiente')->nullable(); // Cambiado a double
            $table->double('devuelto')->nullable(); // Cambiado a double
            $table->text('status')->nullable();
            $table->text('motivo_devolucion')->nullable();
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER update_pendiente_status BEFORE UPDATE ON ordenes_produccion
            FOR EACH ROW
            BEGIN
                -- Declarar la variable pendiente_val
                DECLARE pendiente_val DECIMAL(10,2);

                -- Verificar si el status no es "cerrada"
                IF NEW.status <> "cerrada" THEN
                    -- Inicializar la variable pendiente_val
                    SET pendiente_val = NEW.requerido - NEW.entregado;

                    -- Verificar si entregado es mayor que requerido
                    IF NEW.entregado > NEW.requerido THEN
                        SET NEW.pendiente = 0;
                    ELSE
                        SET NEW.pendiente = pendiente_val;
                    END IF;

                    -- Actualizar el campo status cuando pendiente es igual a 0
                    IF NEW.pendiente = 0 THEN
                        SET NEW.status = "surtida";
                    ELSE
                        SET NEW.status = "pendiente";
                    END IF;
                END IF;
            END;
        ');
    }

    public function down()
    {
        Schema::dropIfExists('ordenes_produccion');
    }
}
