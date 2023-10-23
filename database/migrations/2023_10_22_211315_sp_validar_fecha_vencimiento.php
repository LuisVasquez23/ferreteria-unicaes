<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
                CREATE PROCEDURE SP_VALIDAR_FECHA_VENCIMIENTO()
                BEGIN
                    -- Crear una tabla temporal para almacenar los IDs de productos vencidos
                    CREATE TABLE IF NOT EXISTS productos_vencidos (
                        producto_id INT
                    );

                    -- Insertar en la tabla temporal los IDs de productos con fecha de vencimiento pasada
                    INSERT INTO 
                        productos_vencidos (producto_id)
                    SELECT 
                            dc.producto_id
                    FROM 	detalle_compras dc
                    WHERE 	dc.fecha_vencimiento < CURDATE();

                    -- Actualizar la cantidad en la tabla productos para los productos en la tabla temporal
                    UPDATE 
                            productos p
                    JOIN 	productos_vencidos pv ON p.producto_id = pv.producto_id
                    SET 	p.cantidad = 0;

                    -- Eliminar la tabla temporal
                    DROP TABLE IF EXISTS productos_vencidos;
                END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS SP_VALIDAR_FECHA_VENCIMIENTO');
    }
};
