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
        Schema::create('ventas', function (Blueprint $table) {

            // Campos generales de la tabla
            $table->id('venta_id');
            $table->float('monto');
            $table->string('numerosfactura')->unique();
            $table->unsignedBigInteger('periodo_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('vendedor_id');

            // Campos de auditoria
            $table->string('creado_por')->nullable();
            $table->dateTime('fecha_creacion')->nullable();
            $table->string('actualizado_por')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->string('bloqueado_por')->nullable();
            $table->dateTime('fecha_bloqueo')->nullable();
            $table->timestamps();

            // Llaves foraneas
            $table->foreign('cliente_id')->references('usuario_id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendedor_id')->references('usuario_id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('periodo_id')->references('periodo_id')->on('periodos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
