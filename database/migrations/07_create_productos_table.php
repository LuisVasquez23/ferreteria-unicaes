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
            $table->id('producto_id')->zerofill();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->longText('img_path');
            $table->integer('cantidad');
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('estante_id');
            $table->unsignedBigInteger('unidad_medida_id');
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
            $table->foreign('estante_id')->references('estante_id')->on('estantes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('unidad_medida_id')->references('unidad_medida_id')->on('unidades_medida')->onDelete('cascade')->onUpdate('cascade');

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
