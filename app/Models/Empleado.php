<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Importar solo Model, ya no Authenticatable

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados'; 
    protected $primaryKey = 'idEmp'; // Especificar la clave primaria correcta

    protected $fillable = [
        'idUserFK', // CRÍTICO: Asegurarse de que esta clave foránea esté aquí
        'telefono',
        'direccion',
    ];
    
    // Opcional: Define la relación inversa con User
    public function user()
    {
        // El empleado pertenece a un usuario (relación 1:1)
        return $this->belongsTo(User::class, 'idUserFK');
    }
}