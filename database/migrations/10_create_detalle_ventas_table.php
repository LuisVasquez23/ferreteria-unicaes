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
            $table->float('precio');
            $table->unsignedBigInteger('compra_id');
            $table->unsignedBigInteger('producto_id');

            // Campos de auditoria
            $table->string('creado_por');
            $table->dateTime('fecha_creacion');
            $table->string('actualizado_por');
            $table->dateTime('fecha_actualizacion');
            $table->string('bloqueado_por');
            $table->dateTime('fecha_bloqueo');

            // Llaves foraneas
            $table->foreign('compra_id')->references('compra_id')->on('compras')->onDelete('cascade')->onUpdate('cascade');
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
