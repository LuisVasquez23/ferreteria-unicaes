<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('
    CREATE PROCEDURE create_periods_for_year_if_not_exist(created_by_name VARCHAR(255))
    BEGIN
        DECLARE fecha_actual DATE;
        DECLARE end_date DATE;
        DECLARE year_exists INT; -- Variable para almacenar el resultado de la función
        
        -- Llama a la función para verificar si existen registros para el año actual
        SET year_exists = PeriodsExistForYear();
        
        -- Verifica si ya existen registros para el año actual
        IF year_exists = 0 THEN
            SET fecha_actual = CURDATE(); -- Obtiene la fecha actual
            SET fecha_actual = DATE_ADD(fecha_actual, INTERVAL (1 - DAY(fecha_actual)) DAY); -- Establece el día al 1
        
            REPEAT
                SET end_date = DATE_ADD(LAST_DAY(fecha_actual), INTERVAL 1 DAY); -- Obtiene el primer día del mes siguiente
            
                INSERT INTO periodos (fecha_inicio, fecha_fin, creado_por, fecha_creacion)
                VALUES (fecha_actual, end_date, created_by_name, NOW()); -- Agrega el nombre del usuario y la fecha de creación
            
                SET fecha_actual = DATE_ADD(fecha_actual, INTERVAL 1 MONTH); -- Avanza al próximo mes
            UNTIL YEAR(fecha_actual) != YEAR(CURDATE()) END REPEAT;
        END IF;
    END
');

    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS create_periods_for_year_if_not_exist;');
    }
};
