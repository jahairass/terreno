<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terreno extends Model
{
    use HasFactory;

    protected $table = 'terrenos';

    protected $fillable = [
        'codigo',
        'nombre',
        'ubicacion',
        'alcaldia',
        'precio_total',
        'estado',
        'descripcion',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'terreno_id');
    }
}
