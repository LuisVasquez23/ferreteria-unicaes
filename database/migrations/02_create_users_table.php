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
            $table->string('dui', 10)->unique()->nullable();
            $table->string('nit', 17)->unique()->nullable();
            $table->string('nombres', 50)->nullable();
            $table->string('apellidos', 50)->nullable();
            $table->string('telefono', 9)->nullable();
            $table->string('departamento')->nullable();
            $table->string('municipio')->nullable();
            $table->string('direccion')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();

            // Campos de auditoria
            $table->string('creado_por')->nullable();
            $table->dateTime('fecha_creacion')->nullable();
            $table->string('actualizado_por')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->string('bloqueado_por')->nullable();
            $table->dateTime('fecha_bloqueo')->nullable();
            $table->timestamps();
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
