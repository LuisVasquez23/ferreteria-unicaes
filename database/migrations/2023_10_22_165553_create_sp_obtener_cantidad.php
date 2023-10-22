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
        DB::unprepared("   
                CREATE PROCEDURE OBTENER_CANTIDAD_DISPONIBLE(IN idProducto INT)
                BEGIN
                    SELECT
                        *
                    FROM
                    (
                        SELECT
                            COMPRAS.PRODUCTO_ID,
                            COMPRAS.NUMERO_LOTE,
                            COMPRAS.CANTIDAD AS CANTIDAD_COMPRADA,
                            IFNULL(SUM(VENTAS.CANTIDAD), 0) AS CANTIDAD_VENDIDA,
                            COMPRAS.CANTIDAD - IFNULL(SUM(VENTAS.CANTIDAD), 0) AS CANTIDAD_DISPONIBLE,
                            COMPRAS.PRECIOUNITARIO
                        FROM
                            DETALLE_COMPRAS COMPRAS
                        LEFT JOIN
                            DETALLE_VENTAS VENTAS ON COMPRAS.NUMERO_LOTE = VENTAS.NUMERO_LOTE AND COMPRAS.PRODUCTO_ID = VENTAS.PRODUCTO_ID
                        WHERE COMPRAS.PRODUCTO_ID = idProducto 
                        GROUP BY
                            COMPRAS.PRODUCTO_ID, COMPRAS.NUMERO_LOTE , COMPRAS.CANTIDAD , COMPRAS.PRECIOUNITARIO
                        ORDER BY
                            COMPRAS.PRODUCTO_ID, COMPRAS.NUMERO_LOTE 
                    ) PRODUCTOS
                    WHERE PRODUCTOS.CANTIDAD_DISPONIBLE != 0;
                END;
            ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS OBTENER_CANTIDAD_DISPONIBLE');
    }
};
