<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terrenos', function (Blueprint $table) {
            $table->id();

            // Identificador visible (1,2,3) o clave pública
            $table->string('codigo', 50)->unique(); // ej: T-001, T-002

            $table->string('nombre', 150);          // ej: Terreno 1 / Lote A
            $table->string('ubicacion', 255)->nullable();
            $table->string('alcaldia', 150)->nullable();

            $table->decimal('precio_total', 10, 2)->default(0);

            // Estado del terreno
            $table->enum('estado', ['disponible', 'apartado', 'vendido'])->default('disponible');

            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terrenos');
    }
};
