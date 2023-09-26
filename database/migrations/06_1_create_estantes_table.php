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
        Schema::create('estantes', function (Blueprint $table) {
            $table->id('estante_id');
            $table->string('estante')->unique();
            $table->string('ubicacion');
            $table->longText('descripcion')->nullable();

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
        Schema::dropIfExists('estantes');
    }
};
