<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cargo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usamos el modelo Cargo para buscar el rol.
        $superAdminCargo = Cargo::where('nombre', 'Super Administrador')->first();

        // 2. Verificamos si el cargo existe antes de crear el usuario.
        if (!$superAdminCargo) {
            $this->command->error('El cargo "Super Administrador" no fue encontrado. Ejecuta primero el CargoSeeder.');
            return; // Detiene la ejecución de este seeder si no se encuentra el cargo.
        }

        // 3. Usamos el método firstOrCreate para evitar crear usuarios duplicados.
        User::firstOrCreate(
            ['email' => 'admin@panaderia.com'], // Busca un usuario con este email.
            [
                'name' => 'Super Administrador',
                'password' => Hash::make('password'),
                'cargo_id' => $superAdminCargo->id,
            ]
        ]);
    }
}

