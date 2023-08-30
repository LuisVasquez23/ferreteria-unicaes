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
        Schema::create('productos', function (Blueprint $table) {

            // Campos generales de la tabla
            $table->id('producto_id');
            $table->string('nombre');
            $table->longText('descripcion');
            $table->float('precio');
            $table->integer('cantidad');
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('periodo_id');

            // Campos de auditoria
            $table->string('creado_por')->nullable();
            $table->dateTime('fecha_creacion')->nullable();
            $table->string('actualizado_por')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->string('bloqueado_por')->nullable();
            $table->dateTime('fecha_bloqueo')->nullable();
            $table->timestamps();

            // Llaves foraneas
            $table->foreign('proveedor_id')->references('usuario_id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('categoria_id')->references('categoria_id')->on('categorias')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('periodo_id')->references('periodo_id')->on('periodos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
