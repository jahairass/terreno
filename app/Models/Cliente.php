<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes'; // por si acaso

    // Tu PK es "id" (según tu tabla). Si fuera otro, cámbialo.
    protected $primaryKey = 'id';

    protected $fillable = [
        'Nombre',          // <- OJO mayúscula como en tu BD
        'telefono',
        'correo',
        'identificacion',
        'direccion',
        'fecha_compra',
    ];
}
