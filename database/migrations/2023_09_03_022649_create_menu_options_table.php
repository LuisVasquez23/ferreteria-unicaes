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
        Schema::create('menu_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(); // Para opciones hijas
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            // Definir la clave foránea para la relación entre opciones y opciones hijas
            $table->foreign('parent_id')->references('id')->on('menu_options')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_options');
    }
};
