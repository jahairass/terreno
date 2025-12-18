<?php

namespace Database\Seeders; // Asegúrate de que el namespace sea correcto

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CajaPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cargo ID 1 = Super Administrador
        $cargo_id = 1;
        
        // Obtener el ID del módulo 'cajas' (CRÍTICO)
        $modulo_cajas = DB::table('modulos')->where('nombre', 'cajas')->first();

        if ($modulo_cajas) {
            // Usamos updateOrInsert para evitar duplicados si ya existe el permiso
            DB::table('permisos')->updateOrInsert(
                [
                    'cargo_id' => $cargo_id,
                    'modulo_id' => $modulo_cajas->id
                ],
                [
                    'mostrar' => 1,
                    'detalle' => 1,
                    'alta' => 1,
                    'editar' => 1,
                    'eliminar' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}