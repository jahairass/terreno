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
        Schema::table('movimientos_caja', function (Blueprint $table) {
            // Añade la columna user_id
            $table->foreignId('user_id')
                  ->nullable() // Permite nulos por si hay registros antiguos
                  ->after('caja_id') // La pone después de caja_id (opcional)
                  ->constrained('users') // Crea la llave foránea a la tabla 'users'
                  ->onDelete('set null'); // Si se borra un usuario, el movimiento queda (no se borra)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos_caja', function (Blueprint $table) {
            // Para poder eliminar la columna, primero hay que eliminar la llave foránea
            
            // Laravel 10+ infiere el nombre así:
            $table->dropForeign(['user_id']); 
            
            // Y luego elimina la columna
            $table->dropColumn('user_id');
        });
    }
};