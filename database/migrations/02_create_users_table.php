<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {

            // Campos generales de la tabla
            $table->id('usuario_id');
            $table->string('dui', 10)->unique();
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->string('telefono', 9);
            $table->string('departamento');
            $table->string('municipio');
            $table->string('direccion');
            $table->string('fecha_nacimiento');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();

            // Campos de auditoria
            $table->string('creado_por');
            $table->dateTime('fecha_creacion');
            $table->string('actualizado_por');
            $table->dateTime('fecha_actualizacion');
            $table->string('bloqueado_por');
            $table->dateTime('fecha_bloqueo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
