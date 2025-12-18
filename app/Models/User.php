<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB; // Añadir DB

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cargo_id', 
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
    
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'idUserFK');
    }

    /**
     * Verifica si el usuario tiene permiso para un módulo y acción específicos.
     * @param string $module Nombre del módulo (ej: 'empleados')
     * @param string $action Nombre de la acción (ej: 'mostrar', 'alta', 'editar')
     * @return bool
     */
    public function hasPermissionTo(string $module, string $action): bool
    {
        // El Super Administrador (ID 1) siempre tiene todos los permisos
        if ($this->cargo_id === 1) {
            return true;
        }

        // Usar join para verificar si existe el permiso
        $hasPermission = DB::table('permisos')
            ->join('modulos', 'permisos.modulo_id', '=', 'modulos.id')
            ->where('permisos.cargo_id', $this->cargo_id)
            ->where('modulos.nombre', $module)
            ->where('permisos.' . $action, 1) // Debe ser 1 (TRUE)
            ->exists();

        return $hasPermission;
    }
}