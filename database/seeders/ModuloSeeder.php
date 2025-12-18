<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos_completos = [
            'usuarios', 
            'cargos', 
            'categorias',
            'productos', 
            'inventario', 
            'proveedores', 
            'ventas', 
            'compras', 
            'cajas',
            'clientes', // Añadimos clientes para que aparezca en la matriz
        ];

        foreach ($modulos_completos as $nombre) {
            // USANDO updateOrInsert para evitar fallos de duplicados
            DB::table('modulos')->updateOrInsert(
                ['nombre' => $nombre],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}