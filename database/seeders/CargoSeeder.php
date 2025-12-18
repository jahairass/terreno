<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = [
            'Super Administrador',
            'Administrador',
            'Cajero / Vendedor',
            'Inventario',
        ];

        foreach ($cargos as $nombre_cargo) {
            // USANDO updateOrInsert para evitar fallos de duplicados
            DB::table('cargos')->updateOrInsert(
                ['nombre' => $nombre_cargo],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}