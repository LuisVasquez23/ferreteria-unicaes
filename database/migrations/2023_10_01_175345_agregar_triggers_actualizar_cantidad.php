<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER triger_actualizar_cantidad_compra
            AFTER INSERT ON detalle_compras FOR EACH ROW
            BEGIN
                UPDATE productos
                SET cantidad = (
                    SELECT (SUM(cantidad_compras) - SUM(cantidad_ventas))
                    FROM (
                        SELECT 
                            IFNULL(SUM(cantidad), 0) AS cantidad_compras, 
                            0 AS cantidad_ventas
                        FROM detalle_compras
                        WHERE producto_id = NEW.producto_id
                        UNION ALL
                        SELECT 
                            0 AS cantidad_compras, 
                            IFNULL(SUM(cantidad), 0) AS cantidad_ventas
                        FROM detalle_ventas
                        WHERE producto_id = NEW.producto_id
                    ) AS productos_totales
                )
                WHERE producto_id = NEW.producto_id;
            END;
            
            CREATE TRIGGER triger_actualizar_cantidad_venta
            AFTER INSERT ON detalle_ventas FOR EACH ROW
            BEGIN
                UPDATE productos
                SET cantidad = (
                    SELECT (SUM(cantidad_compras) - SUM(cantidad_ventas))
                    FROM (
                        SELECT 
                            IFNULL(SUM(cantidad), 0) AS cantidad_compras, 
                            0 AS cantidad_ventas
                        FROM detalle_compras
                        WHERE producto_id = NEW.producto_id
                        UNION ALL
                        SELECT 
                            0 AS cantidad_compras, 
                            IFNULL(SUM(cantidad), 0) AS cantidad_ventas
                        FROM detalle_ventas
                        WHERE producto_id = NEW.producto_id
                    ) AS productos_totales
                )
                WHERE producto_id = NEW.producto_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS triger_actualizar_cantidad_compra;
            DROP TRIGGER IF EXISTS triger_actualizar_cantidad_venta;
        ');
    }
};
