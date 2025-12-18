<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs de Cargos y Módulos
        $cargos = DB::table('cargos')->pluck('id', 'nombre');
        $modulos = DB::table('modulos')->pluck('id', 'nombre');

        $permisos = [];

        // ----------------------------------------------------
        // 1. Permisos para Super Administrador (ID 1)
        // Se asume que el Gate::before() en AuthServiceProvider le da acceso total.
        // Pero se añaden todos los permisos explícitamente para consistencia.
        // ----------------------------------------------------
        foreach ($modulos as $nombreModulo => $idModulo) {
             $permisos[] = [
                'cargo_id' => $cargos['Super Administrador'], 
                'modulo_id' => $idModulo,
                'mostrar' => true, 'alta' => true, 'detalle' => true, 'editar' => true, 'eliminar' => true,
                'created_at' => now(), 'updated_at' => now()
            ];
        }

        // ----------------------------------------------------
        // 2. Permisos para Cajero / Vendedor (ID 3)
        // Solo para ventas y caja
        // ----------------------------------------------------
        // Módulo 'ventas'
        $permisos[] = [
            'cargo_id' => $cargos['Cajero / Vendedor'], 'modulo_id' => $modulos['ventas'],
            'mostrar' => true, 'alta' => true, 'detalle' => true, 'editar' => false, 'eliminar' => false,
            'created_at' => now(), 'updated_at' => now()
        ];
        // Módulo 'cajas' (abrir/cerrar/ver)
        $permisos[] = [
            'cargo_id' => $cargos['Cajero / Vendedor'], 'modulo_id' => $modulos['cajas'],
            'mostrar' => true, 'alta' => true, 'detalle' => true, 'editar' => false, 'eliminar' => false,
            'created_at' => now(), 'updated_at' => now()
        ];

        // ----------------------------------------------------
        // 3. Permisos para Inventario (ID 4)
        // Solo para inventario y compras
        // ----------------------------------------------------
        // Módulo 'inventario'
        $permisos[] = [
            'cargo_id' => $cargos['Inventario'], 'modulo_id' => $modulos['inventario'],
            'mostrar' => true, 'alta' => true, 'detalle' => true, 'editar' => true, 'eliminar' => false,
            'created_at' => now(), 'updated_at' => now()
        ];
        // Módulo 'compras'
        $permisos[] = [
            'cargo_id' => $cargos['Inventario'], 'modulo_id' => $modulos['compras'],
            'mostrar' => true, 'alta' => true, 'detalle' => true, 'editar' => false, 'eliminar' => false,
            'created_at' => now(), 'updated_at' => now()
        ];
        
        // ----------------------------------------------------
        // 4. Inserción de todos los Permisos
        // ----------------------------------------------------
        DB::table('permisos')->insert($permisos);
    }
}