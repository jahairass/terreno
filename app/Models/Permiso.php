<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permiso extends Model
{
    use HasFactory;
    
    protected $table = 'permisos';
    
    protected $fillable = [
        'cargo_id',
        'modulo_id',
        'mostrar',
        'alta',
        'detalle',
        'editar',
        'eliminar'
    ];

    // Un Permiso pertenece a un Cargo
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    // Un Permiso pertenece a un Módulo
    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }
}
