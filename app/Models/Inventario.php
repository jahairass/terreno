<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    // 👇 IMPORTANTE: apunta a la tabla TERRENOS
    protected $table = 'terrenos';

    // Campos reales de la tabla terrenos
    protected $fillable = [
        'codigo',
        'alcaldia',
        'ubicacion',
        'precio',
        'estado',
        'fila',
        'columna',
    ];
}


