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
        Schema::create('unidades_medida', function (Blueprint $table) {
            $table->id('unidad_medida_id');
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();


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
        Schema::dropIfExists('unidades_medida');
    }
};
