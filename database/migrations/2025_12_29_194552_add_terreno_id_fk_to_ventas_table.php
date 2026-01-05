<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {

            // Si NO existe aún terreno_id en ventas, lo agregamos:
            if (!Schema::hasColumn('ventas', 'terreno_id')) {
                $table->unsignedBigInteger('terreno_id')->nullable()->after('id');
            }

            // FK (con nombre explícito)
            $table->foreign('terreno_id', 'ventas_terreno_id_foreign')
                ->references('id')
                ->on('terrenos')
                ->nullOnDelete()     // ON DELETE SET NULL
                ->cascadeOnUpdate(); // ON UPDATE CASCADE
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Primero se elimina la FK
            $table->dropForeign('ventas_terreno_id_foreign');

            // (Opcional) si quieres también quitar la columna:
            // $table->dropColumn('terreno_id');
        });
    }
};
