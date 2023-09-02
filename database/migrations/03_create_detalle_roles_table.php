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
        Schema::create('detalle_roles', function (Blueprint $table) {

            // Campos generales de la tabla
            $table->id('detalle_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('usuario_id');

            // Campos de auditoria
            $table->string('creado_por')->nullable();
            $table->dateTime('fecha_creacion')->nullable();
            $table->string('actualizado_por')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->string('bloqueado_por')->nullable();
            $table->dateTime('fecha_bloqueo')->nullable();
            $table->timestamps();

            // Llaves foreneas
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('usuario_id')->references('usuario_id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_roles');
    }
};
