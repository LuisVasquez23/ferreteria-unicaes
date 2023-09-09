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
            CREATE FUNCTION PeriodsExistForYear() RETURNS INT
            BEGIN
                DECLARE year_actual INT;
        
                SET year_actual = YEAR(CURDATE());
                
                IF EXISTS (SELECT 1 FROM periodos WHERE YEAR(fecha_inicio) = year_actual) THEN
                    RETURN 1; 
                ELSE
                    RETURN 0;
                END IF;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS PeriodsExistForYear;');
    }
};






