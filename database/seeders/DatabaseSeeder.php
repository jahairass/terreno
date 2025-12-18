<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. ACLs (Deben ir primero)
            CargoSeeder::class, // SEGURO
            ModuloSeeder::class, // SEGURO
            PermisoSeeder::class, // Ejecuta después de Cargo y Módulo

            // 2. Usuario Inicial (Depende de Cargos)
            UserSeeder::class, 

            // 3. Permisos específicos que dependen de los anteriores
            CajaPermisoSeeder::class,

            // NOTA: Los seeders de Catálogo/Clientes/Proveedores deben crearse
            // y llamarse aquí si se necesitan datos de prueba.
        ]);
    }
}