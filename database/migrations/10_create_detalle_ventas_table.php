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
        Schema::create('detalle_ventas', function (Blueprint $table) {

            // Campos generlaes de la tabla
            $table->id('detalle_venta_id');
            $table->integer('cantidad');
            $table->integer('numero_lote')->default(0)->unsigned()->nullable(false);
            $table->float('precio');
            $table->unsignedBigInteger('venta_id');
            $table->unsignedBigInteger('producto_id');

            // Campos de auditoria
            $table->string('creado_por')->nullable();
            $table->dateTime('fecha_creacion')->nullable();
            $table->string('actualizado_por')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->string('bloqueado_por')->nullable();
            $table->dateTime('fecha_bloqueo')->nullable();
            $table->timestamps();

            // Llaves foraneas
            $table->foreign('venta_id')->references('venta_id')->on('ventas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('producto_id')->references('producto_id')->on('productos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
